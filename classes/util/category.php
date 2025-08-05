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
 * Category file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\util;

use core_course_category;
use Exception;

/**
 * Class category
 *
 * @package local_backupftp\util
 */
class category {
    /**
     * Function local_backupftp_get_category
     *
     * @param $directory
     * @return array
     * @throws Exception
     */
    public static function get_category($directory) {
        global $DB, $CFG;

        $returnlink = get_string('category_link', 'local_backupftp', "{$CFG->wwwroot}/course/management.php?categoryid=1");

        $categorias = explode("/", $directory);
        unset($categorias[0]);
        $categoriaid = get_config("local_backupftp", "categorystart");
        foreach ($categorias as $categoriname) {
            if ($categoriaid == -1) {
                $returnlink .= " / <span style='color: #E91E63;'>{$categoriname}</span>";
            } else {
                $categoriadb = $DB->get_record_sql("
                    SELECT *
                      FROM {course_categories}
                     WHERE name LIKE '{$categoriname}'
                       AND parent = {$categoriaid}");
                if ($categoriadb) {
                    $categoriaid = $categoriadb->id;
                    $returnlink .= " / <a href='{$CFG->wwwroot}/course/management.php?categoryid={$categoriadb->id}'
                                      target='blank'>{$categoriname}</a>";
                } else {
                    $categoriaid = -1;
                    $returnlink .= " / <span style='color: #E91E63;'>{$categoriname}</span>";
                }
            }
        }
        return ["link" => $returnlink, "id" => $categoriaid];
    }

    /**
     * Function categoryid
     *
     * @param $remotefile
     * @param $logs
     * @return int|mixed
     * @throws Exception
     */
    public static function get_categoryid($remotefile, &$logs) {
        global $DB;

        $ftpenable = get_config("local_backupftp", "ftpenable");
        $localfileenable = get_config("local_backupftp", "localfileenable");
        if ($ftpenable) {
            $ftppasta = get_config("local_backupftp", "ftppasta");
            $categoria = str_replace($ftppasta, "", $remotefile);
        } else if ($localfileenable) {
            $localfilepath = get_config("local_backupftp", "localfilepath");
            $categoria = str_replace($localfilepath, "", $remotefile);
        } else {
            return 0;
        }

        $dirname = pathinfo($categoria, PATHINFO_DIRNAME);

        $categorias = explode("/", $dirname);
        unset($categorias[0]);

        $categoriaid = get_config("local_backupftp", "categorystart");
        foreach ($categorias as $categoriname) {
            $categoriadb = $DB->get_record_sql("
                    SELECT *
                      FROM {course_categories}
                     WHERE name LIKE '{$categoriname}'
                       AND parent = {$categoriaid}");
            if ($categoriadb) {
                $categoriaid = $categoriadb->id;
            } else {
                $category = (object)[
                    "name" => $categoriname,
                    "parent" => $categoriaid,
                    "visible" => 1,
                    "visibleold" => 1,
                ];
                $category = core_course_category::create($category);
                $categoriaid = $category->id;

                $logs[] = get_string('category_created_successfully', 'local_backupftp', ['categoria_nome' => $categoriname]);
            }
        }
        return $categoriaid;
    }
}
