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
 * Shared backup file formatting and naming.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\util;

/**
 * Provider-neutral file helper.
 */
class file {
    /**
     * Format a file size.
     *
     * @param int $bytes File size.
     * @return string
     */
    public static function format_bytes(int $bytes): string {
        if ($bytes <= 0) {
            return '0';
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $value = $bytes;
        $i = 0;
        while ($value >= 1000 && $i < count($units) - 1) {
            $value /= 1000;
            $i++;
        }
        return number_format($value, $i ? 1 : 0, ',', '.') . ' ' . $units[$i];
    }

    /**
     * Normalize a filename to safe ASCII.
     *
     * @param string $name Raw name.
     * @return string
     */
    public static function remove_accents(string $name): string {
        $name = \core_text::specialtoascii($name);
        return trim(preg_replace('/[^A-Za-z0-9\-_\. ]/', '', str_replace(chr(0), '', $name)));
    }
}
