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
 * FTP helper.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus{@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\server;

use Exception;

/**
 * FTP connection helper.
 */
class ftp {

    /**
     * FTP connection resource.
     *
     * @var resource|null
     */
    public $connid = null;

    /**
     * Connect to FTP/FTPS using plugin config.
     *
     * @param array $logs Logs.
     * @return array Updated logs.
     * @throws Exception
     */
    public function connect(array $logs = []): array {
        $ftpurl = trim(get_config('local_backupftp', 
            'ftpurl'));
        $ftppasv = get_config('local_backupftp', 
            'ftppasv');
        $ftpusername = get_config('local_backupftp', 
            'ftpusername');
        $ftppassword = get_config('local_backupftp', 
            'ftppassword');

        if ($ftpurl === '') {
            $logs[] = get_string('ftp_error_connecting', 
            'local_backupftp');
            return $logs;
        }

        $parsed = $this->parse_ftp_url($ftpurl);
        if (empty($parsed['host'])) {
            $logs[] = get_string('ftp_error_connecting', 
            'local_backupftp');
            return $logs;
        }

        $host = $parsed['host'];
        $port = $parsed['port'];
        $useftps = $parsed['ftps'];

        if ($useftps && function_exists('ftp_ssl_connect')) {
            $this->connid = @ftp_ssl_connect($host, $port);
        } else {
            $this->connid = @ftp_connect($host, $port);
        }

        if (empty($this->connid)) {
            $logs[] = get_string('ftp_error_connecting', 
            'local_backupftp');
            return $logs;
        }

        if (!@ftp_login($this->connid, $ftpusername, $ftppassword)) {
            $logs[] = get_string('ftp_error_login', 
            'local_backupftp', ['username' => $ftpusername, 'url' => $ftpurl]);
            $this->connid = null;
            return $logs;
        }

        if ($ftppasv) {
            @ftp_pasv($this->connid, true);
        }

        return $logs;
    }

    /**
     * Close connection.
     */
    public function close(): void {
        if (!empty($this->connid)) {
            @ftp_close($this->connid);
        }
        $this->connid = null;
    }

    /**
     * Parse FTP URL or host string.
     *
     * @param string $ftpurl URL/host.
     * @return array{host:string,port:int,ftps:bool}
     */
    private function parse_ftp_url(string $ftpurl): array {
        $ftpurl = trim($ftpurl);

        // If user provided only "host" (no scheme), parse_url treats it as path.
        $candidate = $ftpurl;
        if (!preg_match('~^[a-z][a-z0-9+\-.]*://~i', $candidate)) {
            $candidate = 'ftp://' . $candidate;
        }

        $url = @parse_url($candidate);
        if (!is_array($url)) {
            return ['host' => '', 
            'port' => 21, 'ftps' => false];
        }

        $scheme = strtolower(($url['scheme'] ?? 'ftp'));
        $host = ($url['host'] ?? '');
        if ($host === '' && !empty($url['path'])) {
            $host = ltrim($url['path'], '/');
        }

        $port = ($url['port'] ?? 21);
        $ftps = ($scheme === 'ftps');

        return ['host' => $host, 'port' => $port, 'ftps' => $ftps];
    }

    /**
     * Format bytes into human readable.
     *
     * @param int $bytes Bytes.
     * @return string
     */
    public static function format_bytes(int $bytes): string {
        if ($bytes <= 0) {
            return '0';
        }

        $kb = $bytes / 1000;
        if ($kb < 1000) {
            return self::remove_zero(number_format($kb, 1, ',', 
            '.') . ' KB', 1);
        }

        $mb = $kb / 1000;
        if ($mb < 1000) {
            return self::remove_zero(number_format($mb, 1, ',', 
            '.') . ' MB', 1);
        }

        $gb = $mb / 1000;
        if ($gb < 1000) {
            return self::remove_zero(number_format($gb, 2, ',', 
            '.') . ' GB', 2);
        }

        $tb = $gb / 1000;
        return self::remove_zero(number_format($tb, 3, ',', 
            '.') . ' TB', 3);
    }

    /**
     * Remove trailing zeros.
     *
     * @param string $text Text.
     * @param int $count Decimals.
     * @return string
     */
    private static function remove_zero(string $text, int $count): string {
        if ($count === 3) {
            return str_replace(',000', 
            '', $text);
        } else if ($count === 2) {
            return str_replace(',00', 
            '', $text);
        }
        return str_replace(',0', 
            '', $text);
    }

    /**
     * Remove accents and restrict to safe chars for file/folder names.
     *
     * @param string $string Input.
     * @return string
     */
    public static function remove_accents(string $string): string {
        $map = [
            'À' => 'A', 
            'Á' => 'A', 
            'Â' => 'A', 
            'Ã' => 'A', 
            'Ä' => 'A', 
            'Å' => 'A', 
            'Æ' => 'AE', 
            'Ç' => 'C', 
            'È' => 'E', 
            'É' => 'E',
            'Ê' => 'E', 
            'Ë' => 'E', 
            'Ì' => 'I', 
            'Í' => 'I', 
            'Î' => 'I', 
            'Ï' => 'I', 
            'Ð' => 'D', 
            'Ñ' => 'N', 
            'Ò' => 'O', 
            'Ó' => 'O',
            'Ô' => 'O', 
            'Õ' => 'O', 
            'Ö' => 'O', 
            'Ø' => 'O', 
            'Ù' => 'U', 
            'Ú' => 'U', 
            'Û' => 'U', 
            'Ü' => 'U', 
            'Ý' => 'Y', 
            'Þ' => 'TH',
            'ß' => 'ss', 
            'à' => 'a', 
            'á' => 'a', 
            'â' => 'a', 
            'ã' => 'a', 
            'ä' => 'a', 
            'å' => 'a', 
            'æ' => 'ae', 
            'ç' => 'c',
            'è' => 'e', 
            'é' => 'e', 
            'ê' => 'e', 
            'ë' => 'e', 
            'ì' => 'i', 
            'í' => 'i', 
            'î' => 'i', 
            'ï' => 'i', 
            'ð' => 'd', 
            'ñ' => 'n',
            'ò' => 'o', 
            'ó' => 'o', 
            'ô' => 'o', 
            'õ' => 'o', 
            'ö' => 'o', 
            'ø' => 'o', 
            'ù' => 'u', 
            'ú' => 'u', 
            'û' => 'u', 
            'ü' => 'u',
            'ý' => 'y', 
            'þ' => 'th', 
            'ÿ' => 'y', 
            'ª' => 'a', 
            'º' => 'o',
        ];

        $string = str_replace(chr(0), '', $string);
        $string = strtr($string, $map);

        // Keep only safe characters.
        $string = preg_replace('/[^A-Za-z0-9\-_\. ]/', 
            '', $string);
        $string = trim($string);

        return $string;
    }
}
