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
 * Report for backup.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir . '/tablelib.php');
require_once(__DIR__ . "/classes/report/restore_view.php");

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/backupftp/report-restore.php');
$PAGE->set_pagelayout("base");
$PAGE->set_title(get_string('restore_report', 'local_backupftp'));
$PAGE->set_heading(get_string('restore_report', 'local_backupftp'));

require_login();
require_capability("local/backupftp:manage", context_system::instance());

$table = new \local_backupftp\report\restore_view("backup_report");

if (!$table->is_downloading()) {
    echo $OUTPUT->header();

    echo $OUTPUT->heading(get_string('report', 'local_backupftp'), 2, "main", "backupheading");
}

$table->define_baseurl("{$CFG->wwwroot}/local/backupftp/report-restore.php");
$table->out(40, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}
