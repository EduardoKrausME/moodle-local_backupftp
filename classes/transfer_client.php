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
 * Remote Moodle transfer client.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp;

use moodle_exception;

/**
 * Helper to call the transfer API and download backups from a previous Moodle site.
 */
class transfer_client {

    /**
     * Clean and validate an old Moodle wwwroot.
     *
     * @param string $wwwroot Raw wwwroot.
     * @return string
     * @throws moodle_exception
     */
    public static function clean_wwwroot(string $wwwroot): string {
        $wwwroot = trim(str_replace(["\r", "\n", "\t", chr(0)], '', $wwwroot));
        $wwwroot = rtrim($wwwroot, '/');

        if ($wwwroot === '') {
            throw new moodle_exception('transfer_restore_wwwroot_required', 'local_backupftp');
        }

        $parts = parse_url($wwwroot);
        if (empty($parts['scheme']) || empty($parts['host']) || !in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            throw new moodle_exception('transfer_restore_wwwroot_invalid', 'local_backupftp');
        }

        return $wwwroot;
    }

    /**
     * Clean and validate optional old server IP.
     *
     * @param string $ip Raw IP.
     * @return string
     * @throws moodle_exception
     */
    public static function clean_ip(string $ip): string {
        $ip = trim(str_replace(["\r", "\n", "\t", chr(0)], '', $ip));
        if ($ip === '') {
            return '';
        }

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new moodle_exception('transfer_restore_ip_invalid', 'local_backupftp');
        }

        return $ip;
    }

    /**
     * Clean a token supplied by the administrator.
     *
     * @param string $token Raw token.
     * @return string
     * @throws moodle_exception
     */
    public static function clean_token(string $token): string {
        $token = trim(str_replace(["\r", "\n", "\t", chr(0)], '', $token));
        if ($token === '') {
            throw new moodle_exception('transfer_restore_token_required', 'local_backupftp');
        }

        return $token;
    }

    /**
     * Clean a backup relative path returned by the remote API.
     *
     * @param string $file Remote relative file.
     * @return string
     */
    public static function clean_backup_file(string $file): string {
        $file = trim(str_replace(chr(0), '', $file));
        $file = str_replace('\\', '/', $file);
        $file = ltrim($file, '/');

        if ($file === '' || preg_match('#(^|/)\.\.(/|$)#', $file)) {
            return '';
        }

        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'mbz') {
            return '';
        }

        return $file;
    }

    /**
     * Fetch the remote backup list.
     *
     * @param string $wwwroot Previous Moodle wwwroot.
     * @param string $ip Optional old server IP.
     * @param string $token Transfer token.
     * @return array
     * @throws moodle_exception
     */
    public static function fetch_backups(string $wwwroot, string $ip, string $token): array {
        $url = self::build_url($wwwroot, '/local/backupftp/api.php', [
            'action' => 'backups',
            'limit' => 2000,
            'token' => $token,
        ]);

        $response = self::request($url, $ip);
        $json = json_decode($response['body'], true);

        if (!is_array($json)) {
            throw new moodle_exception('transfer_restore_invalid_json', 'local_backupftp');
        }

        if (empty($json['success'])) {
            $message = $json['message'] ?? ($json['error'] ?? 'remote_error');
            throw new moodle_exception('transfer_restore_remote_error', 'local_backupftp', '', s($message));
        }

        return $json;
    }



    /**
     * Fetch users from the remote Moodle transfer API.
     *
     * @param string $wwwroot Previous Moodle wwwroot.
     * @param string $ip Optional old server IP.
     * @param string $token Transfer token.
     * @return array
     * @throws moodle_exception
     */
    public static function fetch_users(string $wwwroot, string $ip, string $token): array {
        $url = self::build_url($wwwroot, '/local/backupftp/api.php', [
            'action' => 'users',
            'limit' => 5000,
            'token' => $token,
        ]);

        $response = self::request($url, $ip);
        $json = json_decode($response['body'], true);

        if (!is_array($json)) {
            throw new moodle_exception('transfer_restore_invalid_json', 'local_backupftp');
        }

        if (empty($json['success'])) {
            $message = $json['message'] ?? ($json['error'] ?? 'remote_error');
            throw new moodle_exception('transfer_restore_remote_error', 'local_backupftp', '', s($message));
        }

        return $json;
    }

    /**
     * Download a remote backup to a local temp file.
     *
     * @param string $wwwroot Previous Moodle wwwroot.
     * @param string $ip Optional old server IP.
     * @param string $token Transfer token.
     * @param string $remotefile Remote relative backup path.
     * @param string $target Local target file.
     * @param array $logs Logs by reference.
     * @return int Downloaded size.
     * @throws moodle_exception
     */
    public static function download_backup(
        string $wwwroot,
        string $ip,
        string $token,
        string $remotefile,
        string $target,
        array &$logs
    ): int {
        $remotefile = self::clean_backup_file($remotefile);
        if ($remotefile === '') {
            throw new moodle_exception('transfer_restore_invalid_backup_file', 'local_backupftp');
        }

        $url = self::build_url($wwwroot, '/local/backupftp/download.php', [
            'f' => $remotefile,
            'token' => $token,
        ]);

        $logs[] = get_string('transfer_restore_downloading', 'local_backupftp', [
            'url' => self::mask_token_in_url($url),
        ]);

        self::request($url, $ip, $target);

        clearstatcache(true, $target);
        $size = is_file($target) ? (int)filesize($target) : 0;
        if ($size < 10) {
            @unlink($target);
            throw new moodle_exception('transfer_restore_download_too_small', 'local_backupftp');
        }

        return $size;
    }

    /**
     * Build a remote URL.
     *
     * @param string $wwwroot Previous Moodle wwwroot.
     * @param string $path Path.
     * @param array $params Query params.
     * @return string
     */
    public static function build_url(string $wwwroot, string $path, array $params = []): string {
        $wwwroot = rtrim($wwwroot, '/');
        $path = '/' . ltrim($path, '/');
        $query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        return $wwwroot . $path . ($query === '' ? '' : '?' . $query);
    }

    /**
     * Return remaining token lifetime from an API response.
     *
     * @param array $response API response.
     * @return int
     */
    public static function get_token_expires_from_response(array $response): int {
        if (!empty($response['token']['timeexpires'])) {
            return (int)$response['token']['timeexpires'];
        }

        return 0;
    }

    /**
     * Make a HTTP request using cURL.
     *
     * @param string $url URL.
     * @param string $ip Optional old IP to force DNS resolution.
     * @param string $targetfile Optional target file for streaming downloads.
     * @return array{httpcode:int,body:string}
     * @throws moodle_exception
     */
    private static function request(string $url, string $ip = '', string $targetfile = ''): array {
        if (!function_exists('curl_init')) {
            throw new moodle_exception('transfer_restore_curl_required', 'local_backupftp');
        }

        $ch = curl_init($url);
        if (!$ch) {
            throw new moodle_exception('transfer_restore_curl_required', 'local_backupftp');
        }

        $headers = [
            'Accept: application/json, application/vnd.moodle.backup, */*',
        ];

        $fp = null;
        if ($targetfile !== '') {
            $fp = fopen($targetfile, 'wb');
            if ($fp === false) {
                curl_close($ch);
                throw new moodle_exception('transfer_restore_tempfile_error', 'local_backupftp');
            }
            curl_setopt($ch, CURLOPT_FILE, $fp);
        } else {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, $targetfile === '' ? 120 : 0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_USERAGENT, 'local_backupftp_transfer/1.0');

        if ($ip !== '') {
            $parts = parse_url($url);
            $host = $parts['host'] ?? '';
            $scheme = strtolower($parts['scheme'] ?? 'https');
            $port = (int)($parts['port'] ?? ($scheme === 'http' ? 80 : 443));
            if ($host !== '' && $port > 0) {
                curl_setopt($ch, CURLOPT_RESOLVE, ["{$host}:{$port}:{$ip}"]);
            }
        }

        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $httpcode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        if ($fp) {
            fclose($fp);
        }

        if ($errno) {
            if ($targetfile !== '') {
                @unlink($targetfile);
            }
            throw new moodle_exception('transfer_restore_http_error', 'local_backupftp', '', s($error));
        }

        if ($httpcode >= 400 || $httpcode === 0) {
            if ($targetfile !== '') {
                @unlink($targetfile);
            }
            throw new moodle_exception('transfer_restore_http_status', 'local_backupftp', '', $httpcode);
        }

        return [
            'httpcode' => $httpcode,
            'body' => is_string($body) ? $body : '',
        ];
    }

    /**
     * Hide token value in URLs printed to logs.
     *
     * @param string $url URL.
     * @return string
     */
    private static function mask_token_in_url(string $url): string {
        return preg_replace('/([?&]token=)[^&]+/i', '$1***', $url);
    }
}
