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
 * Backup report page.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_backupftp\report\backup_view;

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/tablelib.php');
require_once(__DIR__ . '/classes/report/backup_view.php');

global $DB, $PAGE, $OUTPUT;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/backupftp/report-backup.php'));
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('backup_report', 'local_backupftp'));
$PAGE->set_heading(get_string('backup_report', 'local_backupftp'));

require_login();
require_capability('local/backupftp:manage', $context);

// Action: recreate (CSRF-protected).
$recreate = optional_param('recreate', 0, PARAM_INT);
if ($recreate > 0) {
    require_sesskey();

    if ($DB->record_exists('local_backupftp_course', ['id' => $recreate])) {
        $DB->update_record('local_backupftp_course', (object)[
            'id' => $recreate,
            'status' => 'waiting',
            'logs' => '',
            'timestart' => 0,
            'timeend' => 0,
        ]);
    }

    redirect(new moodle_url('/local/backupftp/report-backup.php'));
}

// Action: requeue (CSRF-protected). Keep backward-compat with recreate.
$requeue = optional_param('requeue', 0, PARAM_INT);
$recreate = optional_param('recreate', 0, PARAM_INT);
$targetid = ($requeue > 0) ? $requeue : $recreate;

if ($targetid > 0) {
    require_sesskey();

    if ($DB->record_exists('local_backupftp_course', ['id' => $targetid])) {
        $DB->update_record('local_backupftp_course', (object)[
            'id' => $targetid,
            'status' => 'waiting',
            'logs' => '',
            'timestart' => 0,
            'timeend' => 0,
        ]);
    }

    redirect(new moodle_url('/local/backupftp/report-backup.php'));
}

$table = new backup_view('backup_report');

if (!$table->is_downloading()) {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('report', 'local_backupftp'), 2, 'main', 'backupheading');
}

$table->define_baseurl(new moodle_url('/local/backupftp/report-backup.php'));
$table->out(40, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}
