<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * S3 backup storage.
 *
 * @package backupftpstorage_s3
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace backupftpstorage_s3;

/**
 * Amazon S3 and DigitalOcean Spaces backup destination.
 */
class provider extends \local_backupftp\storage\provider {
    /**
     * Return the provider name.
     *
     * @return string
     */
    public static function get_label(): string {
        return get_string('pluginname', 'backupftpstorage_s3');
    }

    /**
     * Add storage settings.
     *
     * @param \MoodleQuickForm $form Configuration form.
     */
    public static function add_fields(\MoodleQuickForm $form): void {
        $component = 'backupftpstorage_s3';
        $form->addElement('select', 'service', get_string('service', $component), [
            'aws' => get_string('aws', $component),
            'space' => get_string('space', $component),
        ]);
        foreach (['region', 'bucket', 'accesskey', 'folder'] as $field) {
            $form->addElement('text', $field, get_string($field, $component));
            $form->setType($field, PARAM_RAW_TRIMMED);
        }
        // The parent manager deliberately never returns or overwrites a blank password.
        $form->addElement('password', 'password', get_string('password', $component));
        $form->setType('password', PARAM_RAW);
        $form->addElement('advcheckbox', 'organize', get_string('settings_ftporganize', 'local_backupftp'));
        $form->addElement('advcheckbox', 'names', get_string('settings_ftpnames', 'local_backupftp'));
    }

    /**
     * Return the default settings.
     *
     * @return array
     */
    public static function defaults(): array {
        return ['service' => 'aws', 'region' => '', 'bucket' => '', 'accesskey' => '',
            'password' => '', 'folder' => 'backup', 'organize' => 1, 'names' => 1];
    }

    /**
     * Validate saved settings.
     *
     * @param array $data Configuration.
     * @return array
     */
    public static function normalize(array $data): array {
        $config = array_replace(self::defaults(), array_intersect_key($data, self::defaults()));
        foreach (['service', 'region', 'bucket', 'accesskey', 'folder'] as $field) {
            $config[$field] = trim((string)$config[$field]);
        }
        $config['password'] = (string)$config['password'];
        if (!in_array($config['service'], ['aws', 'space'], true) ||
                !preg_match('/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $config['region']) ||
                strlen($config['region']) > 40 ||
                !preg_match('/\A[a-z0-9][a-z0-9.-]{1,61}[a-z0-9]\z/', $config['bucket']) ||
                strpos($config['bucket'], '..') !== false || filter_var($config['bucket'], FILTER_VALIDATE_IP) ||
                !preg_match('/\A[A-Za-z0-9_-]+\z/', $config['accesskey']) ||
                $config['password'] === '' || preg_match('/[\x00-\x1f\x7f]/', $config['password'])) {
            throw new \invalid_parameter_exception(get_string('invalidconfig', 'backupftpstorage_s3'));
        }
        $config['folder'] = trim($config['folder'], '/');
        if (!self::valid_path($config['folder'])) {
            throw new \invalid_parameter_exception(get_string('invalidfolder', 'backupftpstorage_s3'));
        }
        foreach (['organize', 'names'] as $flag) {
            $config[$flag] = empty($config[$flag]) ? 0 : 1;
        }
        return $config;
    }

    /**
     * Reject ambiguous keys and control characters.
     *
     * @param string $path Object key or prefix.
     * @return bool
     */
    private static function valid_path(string $path): bool {
        return $path === '' || (!preg_match('/[\x00-\x1f\x7f\\\\]/', $path) &&
            !preg_match('~(?:^|/)\.{1,2}(?:/|$)~', $path) && strpos($path, '//') === false);
    }

    /**
     * Return the object prefix.
     *
     * @return string
     */
    public function root(): string {
        return trim((string)$this->config['folder'], '/');
    }

    /**
     * Create the S3 client for this instance.
     *
     * @return client
     */
    private function connect(): client {
        return new client(static::normalize($this->config));
    }

    /**
     * Upload an MBZ backup.
     *
     * @param string $source Local file.
     * @param string $filename Moodle filename.
     * @param int $courseid Course ID.
     * @param array $logs Log entries.
     * @return bool
     */
    public function store(string $source, string $filename, int $courseid, array &$logs): bool {
        $parts = array_merge([$this->root()], $this->course_directories($courseid), [$this->filename($filename, $courseid)]);
        $key = implode('/', array_filter($parts, static function($part) {
            return $part !== '';
        }));
        if (!$this->owns($key)) {
            return false;
        }
        $this->connect()->upload($source, $key);
        $logs[] = get_string('file_uploaded', 'local_backupftp', [
            'file' => basename($source), 'remote_file' => $key]);
        return true;
    }

    /**
     * List the available backups.
     *
     * @return array
     */
    public function list_files(): array {
        $prefix = $this->root() === '' ? '' : $this->root() . '/';
        $files = [];
        foreach ($this->connect()->objects($prefix) as $object) {
            if ($this->owns($object['path'])) {
                $files[] = $object;
            }
        }
        return $files;
    }

    /**
     * Verify the key belongs to this destination.
     *
     * @param string $path Object key.
     * @return bool
     */
    public function owns(string $path): bool {
        $prefix = $this->root() === '' ? '' : $this->root() . '/';
        return $path !== '' && $path[0] !== '/' && self::valid_path($path) &&
            strpos($path, $prefix) === 0 && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'mbz';
    }

    /**
     * Download a selected backup into local staging.
     *
     * @param string $path Object key.
     * @param string $destination Local staging path.
     * @param array $logs Log entries.
     * @return bool
     */
    public function fetch(string $path, string $destination, array &$logs): bool {
        if (!$this->owns($path)) {
            return false;
        }
        $this->connect()->download($path, $destination);
        $logs[] = get_string('file_found_and_downloaded', 'local_backupftp');
        return true;
    }

    /**
     * Indicate support for automatic backup transfers.
     *
     * @return bool
     */
    public function supports_automatic(): bool {
        return true;
    }

    /**
     * Build the exact key for an automatic backup.
     *
     * @param string $filename Unique filename.
     * @return string
     */
    protected function automatic_path(string $filename): string {
        return ($this->root() === '' ? '' : $this->root() . '/') . $filename;
    }

    /**
     * Delete a previously transferred automatic backup.
     *
     * @param string $path Exact recorded object key.
     * @return bool
     */
    public function delete_automatic(string $path): bool {
        if ($path !== $this->automatic_path(basename($path)) || !$this->owns($path)) {
            return false;
        }
        $this->connect()->delete($path);
        return true;
    }


    /**
     * Verify a transferred automatic object.
     *
     * @param string $path Object key.
     * @param int $size Expected size.
     * @return bool
     */
    protected function verify_automatic(string $path, int $size): bool {
        return $this->owns($path) && $this->connect()->size($path) === $size;
    }

}
