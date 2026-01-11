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
 * Local backupftp local filepath helper.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp;

use coding_exception;
use Exception;

/**
 * Helper to resolve and ensure the local storage path.
 *
 * This class returns the configured local path and ensures the directory exists.
 */
class localfilepath {

    /**
     * Returns the resolved local storage path.
     *
     * If config local_backupftp|localfilepath is empty, defaults to:
     *   [MOODLEDATA]/backup
     *
     * Ensures the directory exists (creates it if necessary) before returning.
     *
     * @return string Absolute path without trailing slash.
     * @throws Exception
     */
    public static function get_path(): string {
        global $CFG;

        $path = get_config('local_backupftp', 'localfilepath');
        if (trim($path) === '') {
            $path = $CFG->dataroot . DIRECTORY_SEPARATOR . 'backup';
        }

        $path = self::normalize_path($path);

        // Ensure directory exists and is writable.
        if (!make_writable_directory($path, true)) {
            throw new coding_exception('Unable to create or write to local_backupftp localfilepath: ' . $path);
        }

        return $path;
    }

    /**
     * Normalizes a filesystem path.
     *
     * - Trims whitespace
     * - Converts trailing slashes to none
     * - Normalizes duplicate separators
     *
     * @param string $path Raw path.
     * @return string Normalized absolute/relative path (as provided), without trailing slash.
     */
    private static function normalize_path(string $path): string {
        $path = trim($path);

        // Normalize directory separators lightly (keep OS separator).
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);

        // Remove trailing separators.
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        // Collapse duplicate separators (best effort).
        $double = DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
        while (strpos($path, $double) !== false) {
            $path = str_replace($double, DIRECTORY_SEPARATOR, $path);
        }

        return $path;
    }
}
