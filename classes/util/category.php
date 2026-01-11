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

use core\exception\moodle_exception;
use core_course_category;
use core_text;
use Exception;
use local_backupftp\localfilepath;
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
                $returnlink .= ' / <a href="' . s($url->out(false)) . '" target="_blank" rel="noopener noreferrer">' . s($segment) . '</a>';
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
    public static function get_categoryid(string $remotefile, array &$logs): int {
        global $DB;

        $ftpenable = get_config('local_backupftp', 'ftpenable');
        $localfileenable = get_config('local_backupftp', 'localfileenable');

        if ($ftpenable) {
            $ftppasta = get_config('local_backupftp', 'ftppasta');
            $categoria = ($ftppasta !== '') ? str_replace($ftppasta, '', $remotefile) : $remotefile;
        } else if ($localfileenable) {
            $localfilepath = localfilepath::get_path();
            $categoria = ($localfilepath !== '') ? str_replace($localfilepath, '', $remotefile) : $remotefile;
        } else {
            return 0;
        }

        $dirname = pathinfo($categoria, PATHINFO_DIRNAME);
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
        if (core_text::strlen($name) > 255) {
            $name = core_text::substr($name, 0, 255);
        }

        return $name;
    }
}
