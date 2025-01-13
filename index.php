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
 * Index file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require("../../config.php");
global $DB, $PAGE, $OUTPUT;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url("/local/backupftp/index.php"));
$PAGE->set_pagelayout("base");
$PAGE->set_title(get_string("courses_and_categories", "local_backupftp"));
$PAGE->set_heading(get_string("courses_and_categories", "local_backupftp"));

require_login();
require_capability("local/backupftp:manage", context_system::instance());

echo $OUTPUT->header();

echo "<p>" . get_string("add_backup", "local_backupftp") . " <a href='backup.php'>backup.php</a></p>";
echo "<p>" . get_string("add_restore", "local_backupftp") . " <a href='restore.php'>restore.php</a></p>";

echo "<h2>" . get_string("reports", "local_backupftp") . "</h2>";
echo "<p>" . get_string("view_backup_report", "local_backupftp") . " <a href='report-backup.php'>report-backup.php</a></p>";
echo "<p>" . get_string("view_restore_report", "local_backupftp") . " <a href='report-restore.php'>report-restore.php</a></p>";

echo $OUTPUT->footer();
