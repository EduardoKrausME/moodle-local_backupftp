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
 * Backup API helper.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\renderer;

use context_system;
use stdClass;

/**
 * Class backup
 */
class backup {
    /**
     * Render nested category selector.
     *
     * @param int $parentid Parent category id.
     * @return string
     * @throws \dml_exception
     */
    public static function categorias(int $parentid): string {
        global $DB;

        $categories = $DB->get_records('course_categories', ['parent' => $parentid], 'sortorder', 'id,name,parent');
        if (!$categories) {
            return '';
        }

        $out = '';
        foreach ($categories as $category) {
            $out .= self::render_category_node($category);
        }

        return $out;
    }

    /**
     * Render a single category node.
     *
     * @param stdClass $category Category record.
     * @return string
     * @throws \dml_exception
     */
    private static function render_category_node(stdClass $category): string {
        global $DB, $OUTPUT;

        $context = context_system::instance();
        $categoryid = $category->id;
        $unique = uniqid('lbfcat_');
        $inputid = 'id-' . $unique;

        $coursecount = $DB->count_records('course', ['category' => $categoryid]);
        $statusrows = $DB->get_records_sql(
            "SELECT status, COUNT(1) AS linhas
           FROM {local_backupftp_course}
          WHERE courseid IN (SELECT c.id FROM {course} c WHERE c.category = :category)
       GROUP BY status
       ORDER BY status",
            ['category' => $categoryid]
        );

        $statuslist = [];
        foreach ($statusrows as $row) {
            $statuslist[] = [
                'label' => $row->status . ': ' . (int) $row->linhas,
            ];
        }

        $name = format_string($category->name, true, ['context' => $context]);
        $children = self::categorias($categoryid);

        return $OUTPUT->render_from_template('local_backupftp/backup_category_node', [
            'categoryid' => $categoryid,
            'inputid' => $inputid,
            'name' => $name,
            'coursecount' => $coursecount,
            'statuses' => $statuslist,
            'haschildren' => $children !== '',
            'children' => $children,
        ]);
    }
}
