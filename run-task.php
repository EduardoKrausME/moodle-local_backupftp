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
 * run-task file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
$CFG->debug = 32767;
$CFG->debugdisplay = 1;
ini_set("display_errors", "1");
ini_set("log_errors", "1");
ob_end_flush();
session_write_close();

require_once("{$CFG->dirroot}/local/backupftp/classes/task/restore_course.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url("/local/backupftp/run-task.php"));

$action = optional_param("action", false, PARAM_TEXT);
$nun = optional_param("nun", false, PARAM_TEXT);

if ($action == "backup") {
    (new \local_backupftp\task\backup_course())->execute($nun);
} else if ($action == "restore") {
    (new \local_backupftp\task\restore_course())->execute($nun);
} else {
    echo "<h3>" . get_string('runtask_execute_one_course', 'local_backupftp') . "</h3>";
    echo "<p>" . get_string('runtask_backup', 'local_backupftp') .
        " <a href='?action=backup&nun=1'>" . get_string('runtask_click_here', 'local_backupftp') . "</a></p>";
    echo "<p>" . get_string('runtask_restore', 'local_backupftp') .
        " <a href='?action=restore&nun=1'>" . get_string('runtask_click_here', 'local_backupftp') . "</a></p>";

    echo "<h3>" . get_string('runtask_execute_five_courses', 'local_backupftp') . "</h3>";
    echo "<p>" . get_string('runtask_backup', 'local_backupftp') .
        " <a href='?action=backup&nun=5'>" . get_string('runtask_click_here', 'local_backupftp') . "</a></p>";
    echo "<p>" . get_string('runtask_restore', 'local_backupftp') .
        " <a href='?action=restore&nun=5'>" . get_string('runtask_click_here', 'local_backupftp') . "</a></p>";

    echo "<h3>" . get_string('runtask_execute_ten_courses', 'local_backupftp') . "</h3>";
    echo "<p>" . get_string('runtask_backup', 'local_backupftp') .
        " <a href='?action=backup&nun=10'>" . get_string('runtask_click_here', 'local_backupftp') . "</a></p>";
    echo "<p>" . get_string('runtask_restore', 'local_backupftp') .
        " <a href='?action=restore&nun=10'>" . get_string('runtask_click_here', 'local_backupftp') . "</a></p>";
}