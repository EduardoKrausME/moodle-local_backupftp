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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local filesystem storage implementation.
 *
 * @package   backupftpstorage_local
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace backupftpstorage_local;

/**
 * Local backup destination.
 */
class provider extends \local_backupftp\storage\provider {
    /**
     * Return the localized provider name.
     *
     * @return string
     */
    public static function get_label(): string {
        return get_string('pluginname', 'backupftpstorage_local');
    }

    /**
     * Register the provider-specific configuration fields.
     *
     * @param \MoodleQuickForm $form
     */
    public static function add_fields(\MoodleQuickForm $form): void {
        $form->addElement('text', 'path', get_string('settings_localfilepath', 'local_backupftp'));
        $form->setType('path', PARAM_RAW_TRIMMED);
        $form->addElement('advcheckbox', 'organize', get_string('settings_ftporganize', 'local_backupftp'));
        $form->addElement('advcheckbox', 'names', get_string('settings_ftpnames', 'local_backupftp'));
    }

    /**
     * Provide default storage settings.
     *
     * @return array
     */
    public static function defaults(): array {
        global $CFG;
        return ['path' => $CFG->dataroot . '/backup', 'organize' => 1, 'names' => 1];
    }

    /**
     * Validate and normalize the destination settings.
     *
     * @param array $data
     * @return array
     */
    public static function normalize(array $data): array {
        $config = array_merge(self::defaults(), $data);
        $path = rtrim(trim((string) $config['path']), '/\\');
        if ($path === '' || !preg_match('~^(/|[A-Za-z]:[\\\\/])~', $path) || strlen($path) < 4) {
            throw new \invalid_parameter_exception('Local backup folder must be an absolute path');
        }
        $config['path'] = $path;
        $config['organize'] = !empty($config['organize']) ? 1 : 0;
        $config['names'] = !empty($config['names']) ? 1 : 0;
        return $config;
    }

    /**
     * Get the configured storage root.
     *
     * @return string
     */
    public function root(): string {
        return $this->config['path'];
    }

    /**
     * Write the backup file to the selected destination.
     *
     * @param string $source
     * @param string $filename
     * @param int $courseid
     * @param array $logs
     * @return bool
     */
    public function store(string $source, string $filename, int $courseid, array &$logs): bool {
        $dir = $this->root();
        foreach ($this->course_directories($courseid) as $part) {
            $dir .= DIRECTORY_SEPARATOR . $part;
        }
        if (!make_writable_directory($dir)) {
            return false;
        }
        $target = $dir . DIRECTORY_SEPARATOR . $this->filename($filename, $courseid);
        $ok = @copy($source, $target);
        if ($ok) {
            $logs[] = get_string('log:savelocal:success', 'local_backupftp', $target);
        }
        return $ok;
    }

    /**
     * List available backup files.
     *
     * @return array
     */
    public function list_files(): array {
        $root = realpath($this->root());
        if (!$root || !is_dir($root)) {
            return [];
        }
        $result = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            if (!$file->isFile() || strtolower($file->getExtension()) !== 'mbz') {
                continue;
            }
            $result[] = ['path' => $file->getPathname(), 'size' => $file->getSize(),
                'modified' => date('Y-m-d H:i:s', $file->getMTime())];
        }
        return $result;
    }

    /**
     * Verify that a given backup path belongs to this instance.
     *
     * @param string $path
     * @return bool
     */
    public function owns(string $path): bool {
        $root = realpath($this->root());
        $file = realpath($path);
        return $root && $file && is_file($file) &&
            strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'mbz' &&
            strpos($file, rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR) === 0;
    }

    /**
     * Retrieve a stored backup into the local staging directory.
     *
     * @param string $path
     * @param string $destination
     * @param array $logs
     * @return bool
     */
    public function fetch(string $path, string $destination, array &$logs): bool {
        if (!$this->owns($path) || !@copy($path, $destination)) {
            return false;
        }
        if (filesize($destination) < 10) {
            @unlink($destination);
            return false;
        }
        $logs[] = get_string('file_found_and_downloaded', 'local_backupftp');
        return true;
    }
}
