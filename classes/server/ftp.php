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
 * Ftp file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\server;

use dml_exception;

/**
 * Class ftp
 *
 * @package local_backupftp\server
 */
class ftp {
    /** @var resource */
    public $connid;

    /**
     * Function connect
     *
     * @param array $logs
     *
     * @return array
     * @throws dml_exception
     */
    public function connect($logs = []) {
        $ftpurl = get_config("local_backupftp", "ftpurl");
        $ftppasv = get_config("local_backupftp", "ftppasv");
        $ftpusername = get_config("local_backupftp", "ftpusername");
        $ftppassword = get_config("local_backupftp", "ftppassword");

        $url = parse_url($ftpurl);

        if (isset($url["path"])) {
            $url["host"] = $url["path"];
        }
        if (!isset($url["port"])) {
            $url["port"] = 21;
        }

        if (isset($url["scheme"]) && $url["scheme"] == "ftps") {
            $this->conn_id = ftp_ssl_connect($url["host"], $url["port"]);
        } else {
            $this->conn_id = ftp_connect($url["host"], $url["port"]);
        }

        if (!$this->conn_id) {
            $logs[] = get_string("ftp_error_connecting", "local_backupftp");
            $this->conn_id = null;
            return $logs;
        }

        if (!ftp_login($this->conn_id, $ftpusername, $ftppassword)) {
            $logs[] = get_string("ftp_error_login", "local_backupftp", ['username' => $ftpusername, 'url' => $ftpurl]);
            $this->conn_id = null;
            return $logs;
        }

        if ($ftppasv) {
            ftp_pasv($this->conn_id, true);
        }

        return $logs;
    }

    /**
     * Function format_bytes
     *
     * @param $bytes
     *
     * @return mixed|string
     */
    public static function format_bytes($bytes) {
        if ($bytes == 0) {
            return "0";
        }
        $bytes = $bytes / 1000;
        if ($bytes < 1000) {
            return self::remove_zero(number_format($bytes, 1, ",", ".") . " KB", 1);
        }

        $bytes = $bytes / 1000 / 1000;
        if ($bytes < 1000) {
            return self::remove_zero(number_format($bytes, 1, ",", ".") . " MB", 1);
        }

        $bytes = $bytes / 1000 / 1000 / 1000;
        if ($bytes < 1000) {
            return self::remove_zero(number_format($bytes, 2, ",", ".") . " GB", 2);
        }

        $bytes = $bytes / 1000 / 1000 / 1000 / 1000;

        return self::remove_zero(number_format($bytes, 3, ",", ".") . " TB", 3);
    }

    /**
     * Function remove_zero
     *
     * @param $texto
     * @param $count
     *
     * @return mixed
     */
    private static function remove_zero($texto, $count) {
        if ($count == 3) {
            return str_replace(",000", "", $texto);
        } else if ($count == 2) {
            return str_replace(",00", "", $texto);
        } else {
            return str_replace(",0", "", $texto);
        }
    }
}
