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
 * Lib file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Function local_backupftp_extends_navigation
 *
 * @param global_navigation $nav
 *
 * @throws \core\exception\moodle_exception
 * @throws coding_exception
 * @throws dml_exception
 */
function local_backupftp_extends_navigation(global_navigation $nav) {
    local_backupftp_extend_navigation($nav);
}

/**
 * Function local_backupftp_extend_navigation
 *
 * @param global_navigation $nav
 *
 * @throws \core\exception\moodle_exception
 * @throws coding_exception
 * @throws dml_exception
 */
function local_backupftp_extend_navigation(global_navigation $nav) {
    global $CFG;

    $context = context_system::instance();
    if (has_capability("moodle/site:config", $context)) {

        $node = $nav->add(
            get_string("pluginname", "local_backupftp"),
            new moodle_url("{$CFG->wwwroot}/local/backupftp/index.php"),
            navigation_node::TYPE_CUSTOM,
            null,
            "backup",
            new pix_icon("icon", get_string("pluginname", "local_backupftp"), "local_backupftp")
        );

        $node->showinflatnavigation = true;
    }
}

/**
 * Function local_backupftp_extend_navigation_course
 *
 * @param $navigation
 * @param $course
 * @param $context
 *
 * @throws \core\exception\moodle_exception
 * @throws coding_exception
 */
function local_backupftp_extend_navigation_course($navigation, $course, $context) {
    if (has_capability("moodle/site:config", $context)) {
        $certificatenode1 = $navigation->add(get_string("pluginname", "local_backupftp"),
            null, navigation_node::TYPE_CONTAINER, null, "course_certificatebeautiful");
        $url = new moodle_url("/local/backupftp/index.php", ["course" => $course->id]);
        $certificatenode1->add(get_string("pluginname", "local_backupftp"), $url, navigation_node::TYPE_SETTING,
            null, null, new pix_icon("i/report", ""));
    }
}
