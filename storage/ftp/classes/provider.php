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
 * FTP and FTPS transport, listing and configuration.
 *
 * @package   backupftpstorage_ftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace backupftpstorage_ftp;

/**
 * All FTP operations are implemented in this subplugin.
 */
class provider extends \local_backupftp\storage\provider {
    /**
     * Return the localized provider name.
     *
     * @return string
     */
    public static function get_label(): string {
        return get_string('pluginname', 'backupftpstorage_ftp');
    }

    /**
     * Register the provider-specific configuration fields.
     *
     * @param \MoodleQuickForm $form
     */
    public static function add_fields(\MoodleQuickForm $form): void {
        $form->addElement('text', 'url', get_string('settings_ftpurl', 'local_backupftp'));
        $form->setType('url', PARAM_RAW_TRIMMED);
        $form->addElement('text', 'username', get_string('settings_ftpusername', 'local_backupftp'));
        $form->setType('username', PARAM_RAW_TRIMMED);
        $form->addElement('password', 'password', get_string('settings_ftppassword', 'local_backupftp'));
        $form->setType('password', PARAM_RAW);
        $form->addElement('text', 'folder', get_string('settings_ftppasta', 'local_backupftp'));
        $form->setType('folder', PARAM_RAW_TRIMMED);
        $form->addElement('advcheckbox', 'passive', get_string('settings_ftppasv', 'local_backupftp'));
        $form->addElement('advcheckbox', 'organize', get_string('settings_ftporganize', 'local_backupftp'));
        $form->addElement('advcheckbox', 'names', get_string('settings_ftpnames', 'local_backupftp'));
    }

    /**
     * Provide default storage settings.
     *
     * @return array
     */
    public static function defaults(): array {
        return ['url' => '', 'username' => '', 'password' => '', 'folder' => '/backup',
            'passive' => 0, 'organize' => 1, 'names' => 1];
    }

    /**
     * Validate and normalize the destination settings.
     *
     * @param array $data
     * @return array
     */
    public static function normalize(array $data): array {
        $config = array_merge(self::defaults(), $data);
        $url = trim((string) $config['url']);
        $parts = @parse_url(strpos($url, '://') === false ? 'ftp://' . $url : $url);
        if (!$parts || !in_array(strtolower($parts['scheme'] ?? ''), ['ftp', 'ftps'], true) ||
                empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])) {
            throw new \invalid_parameter_exception('Invalid FTP/FTPS host');
        }
        $config['url'] = $url;
        $config['username'] = trim((string) $config['username']);
        $config['password'] = (string) $config['password'];
        $folder = '/' . trim(str_replace('\\', '/', (string) $config['folder']), '/');
        if (strpos($folder, '..') !== false || preg_match('/[\x00-\x1f]/', $folder)) {
            throw new \invalid_parameter_exception('Invalid FTP folder');
        }
        $config['folder'] = $folder;
        foreach (['passive', 'organize', 'names'] as $flag) {
            $config[$flag] = !empty($config[$flag]) ? 1 : 0;
        }
        return $config;
    }

    /**
     * Get the configured storage root.
     *
     * @return string
     */
    public function root(): string {
        return rtrim((string) $this->config['folder'], '/');
    }

    /**
     * Connect to the configured FTP server.
     *
     * @return \FTP\Connection|resource
     */
    private function connect() {
        if (!function_exists('ftp_connect')) {
            throw new \moodle_exception('storage_ftp_extension_required', 'local_backupftp');
        }
        $url = $this->config['url'];
        $parts = parse_url(strpos($url, '://') === false ? 'ftp://' . $url : $url);
        $ssl = strtolower($parts['scheme'] ?? 'ftp') === 'ftps';
        if ($ssl && !function_exists('ftp_ssl_connect')) {
            throw new \moodle_exception('storage_ftp_extension_required', 'local_backupftp');
        }
        $conn = $ssl ? @ftp_ssl_connect($parts['host'], $parts['port'] ?? 21, 30) :
            @ftp_connect($parts['host'], $parts['port'] ?? 21, 30);
        if (!$conn) {
            throw new \moodle_exception('ftp_error_connecting', 'local_backupftp');
        }
        if (!@ftp_login($conn, $this->config['username'], $this->config['password'])) {
            @ftp_close($conn);
            throw new \moodle_exception('ftp_error_login', 'local_backupftp', '', (object)[
                'username' => $this->config['username'], 'url' => $this->config['url']]);
        }
        @ftp_pasv($conn, !empty($this->config['passive']));
        return $conn;
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
        $conn = $this->connect();
        try {
            $path = $this->root();
            foreach ($this->course_directories($courseid) as $folder) {
                $path .= '/' . $folder;
                @ftp_mkdir($conn, $path);
            }
            $target = $path . '/' . $this->filename($filename, $courseid);
            $handle = fopen($source, 'rb');
            if (!$handle) {
                return false;
            }
            try {
                if (!@ftp_fput($conn, $target, $handle, FTP_BINARY)) {
                    return false;
                }
                $logs[] = get_string('file_uploaded', 'local_backupftp', [
                    'file' => basename($source), 'remote_file' => $target]);
                return true;
            } finally {
                fclose($handle);
            }
        } finally {
            ftp_close($conn);
        }
    }

    /**
     * List available backup files.
     *
     * @return array
     */
    public function list_files(): array {
        $conn = $this->connect();
        try {
            return $this->walk($conn, $this->root(), 0);
        } finally {
            ftp_close($conn);
        }
    }

    /**
     * Walk directories and collect backup file information.
     *
     * @param mixed $conn
     * @param string $dir
     * @param int $depth
     * @return array
     */
    private function walk($conn, string $dir, int $depth): array {
        if ($depth > 20) {
            return [];
        }
        $files = [];
        // MLSD is structured and avoids ambiguous UNIX raw listing formats.
        $items = function_exists('ftp_mlsd') ? @ftp_mlsd($conn, $dir) : false;
        if (!is_array($items)) {
            $items = [];
            $raw = @ftp_rawlist($conn, $dir);
            foreach (is_array($raw) ? $raw : [] as $line) {
                $parts = preg_split('/\s+/', trim($line), 9);
                if (count($parts) !== 9) {
                    continue;
                }
                $items[] = ['name' => $parts[8], 'type' => substr($parts[0], 0, 1) === 'd' ? 'dir' : 'file',
                    'size' => (int) $parts[4], 'modify' => ''];
            }
        }
        foreach ($items as $item) {
            $name = $item['name'] ?? '';
            if ($name === '' || $name === '.' || $name === '..' || strpos($name, '/') !== false) {
                continue;
            }
            $path = rtrim($dir, '/') . '/' . $name;
            if (($item['type'] ?? '') === 'dir') {
                $files = array_merge($files, $this->walk($conn, $path, $depth + 1));
            } else if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) === 'mbz') {
                $files[] = ['path' => $path, 'size' => (int)($item['size'] ?? 0),
                    'modified' => (string)($item['modify'] ?? '')];
            }
        }
        return $files;
    }

    /**
     * Verify that a given backup path belongs to this instance.
     *
     * @param string $path
     * @return bool
     */
    public function owns(string $path): bool {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'mbz' &&
            strpos($path, $this->root() . '/') === 0 &&
            strpos(str_replace('\\', '/', $path), '/../') === false;
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
        if (!$this->owns($path)) {
            return false;
        }
        $conn = $this->connect();
        try {
            $fp = fopen($destination, 'wb');
            if (!$fp) {
                return false;
            }
            try {
                $ok = @ftp_fget($conn, $fp, $path, FTP_BINARY);
            } finally {
                fclose($fp);
            }
            if (!$ok || !is_file($destination) || filesize($destination) < 10) {
                @unlink($destination);
                return false;
            }
            $logs[] = get_string('file_found_and_downloaded', 'local_backupftp');
            return true;
        } finally {
            ftp_close($conn);
        }
    }
}
