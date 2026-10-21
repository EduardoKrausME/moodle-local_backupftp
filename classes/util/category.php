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
 * Category helper.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\util;

use core_course_category;
use Exception;
use local_backupftp\storage\manager;
use moodle_url;

/**
 * Category helper class.
 */
class category {
    /**
     * Build a breadcrumb-like HTML link list from a directory relative path.
     *
     * @param string $directory Relative directory (e.g. "/Cat/Subcat").
     * @return array{link:string,id:int}
     * @throws Exception
     */
    public static function get_category(string $directory): array {
        global $DB;

        $categoriaid = (int)get_config('local_backupftp', 'categorystart');

        $rooturl = new moodle_url('/course/management.php', ['categoryid' => max(1, $categoriaid)]);
        $returnlink = get_string('category_link', 'local_backupftp', $rooturl->out(false));

        $segments = self::split_path($directory);

        foreach ($segments as $segment) {
            if ($categoriaid === -1) {
                $returnlink .= ' / <span style="color:#E91E63;">' . s($segment) . '</span>';
                continue;
            }

            $record = $DB->get_record('course_categories', [
                'parent' => $categoriaid,
                'name' => $segment,
            ], 'id', IGNORE_MULTIPLE);

            if ($record) {
                $categoriaid = (int)$record->id;

                $url = new moodle_url('/course/management.php', ['categoryid' => $categoriaid]);
                $returnlink .= ' / <a href="' . s($url->out(false)) .
                    '" target="_blank" rel="noopener noreferrer">' . s($segment) . '</a>';
            } else {
                $categoriaid = -1;
                $returnlink .= ' / <span style="color:#E91E63;">' . s($segment) . '</span>';
            }
        }

        return ['link' => $returnlink, 'id' => $categoriaid];
    }

    /**
     * Ensure category path exists (create missing categories) and return final category id.
     *
     * @param string $remotefile Remote/local file path (as stored in DB).
     * @param array $logs Logs (by ref).
     * @return int
     * @throws Exception
     */
    public static function get_categoryid(string $remotefile, array &$logs, int $storageid = 0): int {
        // Existing queued restores may have storageid=0, so resolve their source by ownership.
        $records = $storageid ? [manager::get($storageid)->record()] : manager::instances(true);
        foreach ($records as $record) {
            $provider = manager::from_record($record);
            if ($provider->owns($remotefile)) {
                $root = rtrim($provider->root(), '/\\');
                $relative = substr($remotefile, strlen($root));
                return self::get_categoryid_from_backup_path($relative, $logs);
            }
        }
        return self::get_categoryid_from_backup_path(basename($remotefile), $logs);
    }

    /**
     * Ensure category path exists from a backup path and return the final category id.
     *
     * The file name itself is ignored; only its directory is used. This is useful for
     * remote Moodle transfers where the backup path is already relative to the old
     * backup root, for example "Graduação/Odontologia/course.mbz".
     *
     * @param string $backuppath Backup path.
     * @param array $logs Logs (by ref).
     * @return int
     * @throws Exception
     */
    public static function get_categoryid_from_backup_path(string $backuppath, array &$logs): int {
        global $DB;

        $dirname = pathinfo(str_replace('\\', '/', $backuppath), PATHINFO_DIRNAME);
        $segments = self::split_path($dirname);

        $categoriaid = (int)get_config('local_backupftp', 'categorystart');
        if ($categoriaid < 1) {
            $categoriaid = 1;
        }

        foreach ($segments as $segment) {
            $segment = self::clean_category_name($segment);
            if ($segment === '') {
                continue;
            }

            $record = $DB->get_record('course_categories', [
                'parent' => $categoriaid,
                'name' => $segment,
            ], 'id', IGNORE_MULTIPLE);

            if ($record) {
                $categoriaid = (int)$record->id;
                continue;
            }

            $category = (object)[
                'name' => $segment,
                'parent' => $categoriaid,
                'visible' => 1,
                'visibleold' => 1,
            ];

            $created = core_course_category::create($category);
            $categoriaid = (int)$created->id;

            $logs[] = get_string('category_created_successfully', 'local_backupftp', [
                'categoria_nome' => $segment,
            ]);
        }

        return $categoriaid;
    }

    /**
     * Split a path into normalized segments.
     *
     * @param string $path Path.
     * @return string[]
     */
    private static function split_path(string $path): array {
        $path = str_replace(chr(0), '', $path);
        $path = str_replace('\\', '/', $path);
        $path = trim($path);

        $raw = explode('/', $path);
        $segments = [];

        foreach ($raw as $seg) {
            $seg = trim($seg);
            if ($seg === '' || $seg === '.' || $seg === '..') {
                continue;
            }
            $segments[] = $seg;
        }

        return $segments;
    }

    /**
     * Clean category name to avoid control characters/newlines.
     *
     * @param string $name Name.
     * @return string
     */
    private static function clean_category_name(string $name): string {
        $name = str_replace(chr(0), '', $name);
        $name = str_replace(["\r", "\n", "\t"], ' ', $name);
        $name = trim($name);

        // Reasonable safety limit.
        if (strlen($name) > 255) {
            $name = substr($name, 0, 255);
        }

        return $name;
    }
}
