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
 * Restore page.
 *
 * This page supports two restore sources:
 * 1. Existing FTP/local backup selection.
 * 2. Remote Moodle transfer API using wwwroot + optional old IP + token.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_backupftp\localfilepath;
use local_backupftp\renderer\restore;
use local_backupftp\server\ftp;
use local_backupftp\transfer_client;
use local_backupftp\util\category;

require(__DIR__ . '/../../config.php');

global $DB, $CFG, $PAGE, $OUTPUT;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/backupftp/restore.php'));
$PAGE->set_pagelayout("admin");
$PAGE->set_title(get_string('restore_courses_and_categories', 'local_backupftp'));
$PAGE->set_heading(get_string('restore_courses_and_categories', 'local_backupftp'));
$PAGE->requires->js_call_amd('local_backupftp/categoryselector', 'init');

require_login();
require_capability('local/backupftp:manage', $context);

echo $OUTPUT->header();
echo $OUTPUT->render_from_template("local_backupftp/page_navigation", [
    "backurl" => new moodle_url("/local/backupftp/"),
]);

// Config (used by list/render helpers below).
$ftppasta = get_config('local_backupftp', 'ftppasta');
$localfilepath = localfilepath::get_path();
$ftpenable = get_config('local_backupftp', 'ftpenable');
$localfileenable = get_config('local_backupftp', 'localfileenable');

// Handle POST: add selected FTP/local files to restore queue.
$files = optional_param_array('file', [], PARAM_RAW_TRIMMED);
if (!empty($files)) {
    require_sesskey();

    foreach ($files as $remotefile) {
        $remotefile = restore::clean_remotefile($remotefile);
        if ($remotefile === '') {
            continue;
        }

        if (!restore::is_allowed_restore_target($remotefile, $ftpenable, $ftppasta, $localfileenable, $localfilepath)) {
            continue;
        }

        $exists = $DB->record_exists_select(
            'local_backupftp_restore',
            'remotefile = :remotefile AND source = :source AND status <> :status',
            ['remotefile' => $remotefile, 'source' => 'configured', 'status' => 'completed']
        );

        if ($exists) {
            continue;
        }

        $data = (object) [
            'remotefile' => $remotefile,
            'source' => 'configured',
            'sourcewwwroot' => '',
            'sourceip' => '',
            'sourcetoken' => '',
            'sourcefilesize' => 0,
            'sourcetimemodified' => 0,
            'status' => 'waiting',
            'logs' => '',
            'timecreated' => time(),
            'timestart' => 0,
            'timeend' => 0,
        ];
        $DB->insert_record('local_backupftp_restore', $data);

        echo html_writer::tag(
            'p',
            get_string('file_added_to_restore_queue', 'local_backupftp', ['file' => s($remotefile)]),
            ['style' => 'color:#2196F3;font-weight:bold;']
        );
    }
}

// Handle POST: save remote Moodle transfer form data in the user's session.
$remotequeue = optional_param('remotequeue', 0, PARAM_INT);
if (!empty($remotequeue)) {
    require_sesskey();
    restore::save_remote_transfer_restore_session();
}

// Handle POST: clear the remote Moodle transfer session data.
$remoteclear = optional_param('remoteclear', 0, PARAM_INT);
if (!empty($remoteclear)) {
    require_sesskey();
    restore::clear_remote_transfer_restore_session();
}

// Handle POST: queue only the checked remote Moodle backup files.
$remoterestoremarked = optional_param('remoterestoremarked', 0, PARAM_INT);
if (empty($remoteclear) && !empty($remoterestoremarked)) {
    require_sesskey();
    $selectedfiles = optional_param_array('remotefile', [], PARAM_RAW_TRIMMED);
    restore::queue_remote_transfer_restore($selectedfiles);
}

// Info / links.
echo $OUTPUT->render_from_template('local_backupftp/restore_info', []);

$remotesession = restore::get_remote_transfer_restore_session();
$remoteformdata = $remotesession;

// Preserve submitted values when validation fails so the administrator can correct them.
if (!empty($remotequeue)) {
    $remoteformdata = [
        'remotewwwroot' => optional_param('remotewwwroot', '', PARAM_RAW_TRIMMED),
        'remoteip' => optional_param('remoteip', '', PARAM_RAW_TRIMMED),
        'remotetoken' => optional_param('remotetoken', '', PARAM_RAW_TRIMMED),
    ];
}

$remoteopen = trim((string) ($remoteformdata['remotewwwroot'] ?? '')) !== '' ||
    trim((string) ($remoteformdata['remoteip'] ?? '')) !== '' ||
    trim((string) ($remoteformdata['remotetoken'] ?? '')) !== '';

echo $OUTPUT->render_from_template('local_backupftp/remote_transfer_form', [
    'actionurl' => $PAGE->url->out(false),
    'sesskey' => sesskey(),
    'remotewwwroot' => $remoteformdata['remotewwwroot'] ?? '',
    'remoteip' => $remoteformdata['remoteip'] ?? '',
    'remotetoken' => $remoteformdata['remotetoken'] ?? '',
    'remoteopen' => $remoteopen,
]);

echo restore::render_remote_transfer_restore_summary();

// Validate local path (avoid accidentally pointing to "/").
if (strlen($localfilepath) < 4) {
    $localfilepath = '';
    $localfileenable = false;
}

// Render existing FTP/local selection form.
echo $OUTPUT->render_from_template('local_backupftp/restore_form', [
    'actionurl' => $PAGE->url->out(false),
    'sesskey' => sesskey(),
    'list_files_ftp' => $ftpenable ? restore::list_filesfromftp($ftppasta) : '',
    'list_files_local' => $localfileenable ? restore::list_filesfromlocal($localfilepath) : '',
]);

echo $OUTPUT->footer();
