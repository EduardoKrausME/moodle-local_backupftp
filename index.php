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
 * Index page.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

global $PAGE, $OUTPUT;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/backupftp/index.php'));
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('courses_and_categories', 'local_backupftp'));
$PAGE->set_heading(get_string('courses_and_categories', 'local_backupftp'));

require_login();
require_capability('local/backupftp:manage', $context);

echo $OUTPUT->header();

echo html_writer::tag('p',
    get_string('add_backup', 'local_backupftp') . ' ' .
    html_writer::link(new moodle_url('/local/backupftp/backup.php'), 'backup.php')
);

echo html_writer::tag('p',
    get_string('add_restore', 'local_backupftp') . ' ' .
    html_writer::link(new moodle_url('/local/backupftp/restore.php'), 'restore.php')
);

echo html_writer::tag('h2', get_string('reports', 'local_backupftp'));

echo html_writer::tag('p',
    get_string('view_backup_report', 'local_backupftp') . ' ' .
    html_writer::link(new moodle_url('/local/backupftp/report-backup.php'), 'report-backup.php')
);

echo html_writer::tag('p',
    get_string('view_restore_report', 'local_backupftp') . ' ' .
    html_writer::link(new moodle_url('/local/backupftp/report-restore.php'), 'report-restore.php')
);

echo $OUTPUT->footer();
