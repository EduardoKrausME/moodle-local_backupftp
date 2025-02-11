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
 * Report file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once("{$CFG->dirroot}/local/backupftp/classes/task/restore_course.php");

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url("/local/backupftp/report.php"));
$PAGE->set_pagelayout("base");
$PAGE->set_title(get_string('reports', 'local_backupftp'));
$PAGE->set_heading(get_string('reports', 'local_backupftp'));

require_login();
require_capability("local/backupftp:manage", context_system::instance());

echo $OUTPUT->header();

echo $OUTPUT->render_from_template("local_backupftp/report");

echo $OUTPUT->footer();
