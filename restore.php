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
use local_backupftp\server\ftp;
use local_backupftp\transfer_client;
use local_backupftp\util\category;

require(__DIR__ . '/../../config.php');

global $DB, $CFG, $PAGE, $OUTPUT;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/backupftp/restore.php'));
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('restore_courses_and_categories', 'local_backupftp'));
$PAGE->set_heading(get_string('restore_courses_and_categories', 'local_backupftp'));

require_login();
require_capability('local/backupftp:manage', $context);

echo $OUTPUT->header();

// Handle POST: queue remote Moodle transfer backups.
$remotequeue = optional_param('remotequeue', 0, PARAM_INT);
if (!empty($remotequeue)) {
    require_sesskey();
    local_backupftp_queue_remote_transfer_restore();
}

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
        $remotefile = local_backupftp_clean_remotefile($remotefile);
        if ($remotefile === '') {
            continue;
        }

        if (!local_backupftp_is_allowed_restore_target($remotefile, $ftpenable, $ftppasta, $localfileenable, $localfilepath)) {
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

        $data = (object)[
            'remotefile' => $remotefile,
            'source' => 'configured',
            'sourcewwwroot' => '',
            'sourceip' => '',
            'sourcetoken' => '',
            'sourceexpires' => 0,
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

// Info / links.
echo $OUTPUT->render_from_template('local_backupftp/restore_info', []);

echo local_backupftp_render_remote_transfer_form();

// Validate local path (avoid accidentally pointing to "/").
if (strlen($localfilepath) < 4) {
    $localfilepath = '';
    $localfileenable = false;
}

// Render existing FTP/local selection form.
echo $OUTPUT->render_from_template('local_backupftp/restore_form', [
    'actionurl' => $PAGE->url->out(false),
    'sesskey' => sesskey(),
    'list_files_ftp' => $ftpenable ? local_backupftp_list_filesfromftp($ftppasta) : '',
    'list_files_local' => $localfileenable ? local_backupftp_list_filesfromlocal($localfilepath) : '',
]);

echo $OUTPUT->footer();

/**
 * Queue remote Moodle backup files using the transfer API.
 *
 * @return void
 */
function local_backupftp_queue_remote_transfer_restore(): void {
    global $DB, $OUTPUT;

    $remotewwwroot = optional_param('remotewwwroot', '', PARAM_RAW_TRIMMED);
    $remoteip = optional_param('remoteip', '', PARAM_RAW_TRIMMED);
    $remotetoken = optional_param('remotetoken', '', PARAM_RAW_TRIMMED);

    try {
        $remotewwwroot = transfer_client::clean_wwwroot($remotewwwroot);
        $remoteip = transfer_client::clean_ip($remoteip);
        $remotetoken = transfer_client::clean_token($remotetoken);

        $response = transfer_client::fetch_backups($remotewwwroot, $remoteip, $remotetoken);
        $tokenexpires = transfer_client::get_token_expires_from_response($response);
        $files = $response['data']['files'] ?? [];

        if (!is_array($files) || empty($files)) {
            echo $OUTPUT->notification(get_string('transfer_restore_no_backups', 'local_backupftp'), 'notifyproblem');
            return;
        }

        $queued = 0;
        $updated = 0;
        $ignored = 0;
        $rows = [];

        foreach ($files as $file) {
            if (!is_array($file)) {
                $ignored++;
                continue;
            }

            $relativepath = transfer_client::clean_backup_file((string)($file['relativepath'] ?? ''));
            if ($relativepath === '') {
                $ignored++;
                continue;
            }

            $filename = (string)($file['filename'] ?? basename($relativepath));
            $filesize = (int)($file['size'] ?? 0);
            $timemodified = (int)($file['timemodified'] ?? 0);

            $existing = $DB->get_record_select('local_backupftp_restore',
                'source = :source AND sourcewwwroot = :sourcewwwroot AND remotefile = :remotefile AND status <> :completed',
                [
                    'source' => 'transfer',
                    'sourcewwwroot' => $remotewwwroot,
                    'remotefile' => $relativepath,
                    'completed' => 'completed',
                ], '*', IGNORE_MULTIPLE);

            if ($existing) {
                $existing->sourceip = $remoteip;
                $existing->sourcetoken = $remotetoken;
                $existing->sourceexpires = $tokenexpires;
                $existing->sourcefilesize = $filesize;
                $existing->sourcetimemodified = $timemodified;
                if ($existing->status === 'error' || (int)$existing->sourceexpires < time()) {
                    $existing->status = 'waiting';
                    $existing->logs = '';
                    $existing->timestart = 0;
                    $existing->timeend = 0;
                }
                $DB->update_record('local_backupftp_restore', $existing);
                $updated++;
            } else {
                $data = (object)[
                    'remotefile' => $relativepath,
                    'source' => 'transfer',
                    'sourcewwwroot' => $remotewwwroot,
                    'sourceip' => $remoteip,
                    'sourcetoken' => $remotetoken,
                    'sourceexpires' => $tokenexpires,
                    'sourcefilesize' => $filesize,
                    'sourcetimemodified' => $timemodified,
                    'status' => 'waiting',
                    'logs' => '',
                    'timecreated' => time(),
                    'timestart' => 0,
                    'timeend' => 0,
                ];
                $DB->insert_record('local_backupftp_restore', $data);
                $queued++;
            }

            $rows[] = [s($filename), s($relativepath), display_size($filesize)];
        }

        $summary = get_string('transfer_restore_queue_summary', 'local_backupftp', [
            'queued' => $queued,
            'updated' => $updated,
            'ignored' => $ignored,
        ]);

        $html = html_writer::tag('p', $summary);
        if ($tokenexpires > 0) {
            $html .= html_writer::tag('p', get_string('transfer_restore_token_counter', 'local_backupftp') . ' ' .
                local_backupftp_render_countdown($tokenexpires));
        }
        $html .= local_backupftp_render_remote_files_table($rows);

        echo $OUTPUT->notification($html, 'notifysuccess');
    } catch (Throwable $e) {
        echo $OUTPUT->notification(s($e->getMessage()), 'notifyproblem');
    }
}

/**
 * Render remote transfer form.
 *
 * @return string
 */
function local_backupftp_render_remote_transfer_form(): string {
    global $PAGE;

    $html = html_writer::start_div('card mb-4');
    $html .= html_writer::start_div('card-body');
    $html .= html_writer::tag('h3', get_string('transfer_restore_title', 'local_backupftp'));
    $html .= html_writer::tag('p', get_string('transfer_restore_desc', 'local_backupftp'));

    $html .= html_writer::start_tag('form', ['method' => 'post', 'action' => $PAGE->url->out(false)]);
    $html .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
    $html .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'remotequeue', 'value' => 1]);

    $html .= html_writer::start_div('form-group');
    $html .= html_writer::tag('label', get_string('transfer_restore_wwwroot', 'local_backupftp'), ['for' => 'id_remotewwwroot']);
    $html .= html_writer::empty_tag('input', [
        'type' => 'url',
        'name' => 'remotewwwroot',
        'id' => 'id_remotewwwroot',
        'class' => 'form-control',
        'required' => 'required',
        'placeholder' => 'https://moodle-antigo.exemplo.com.br',
    ]);
    $html .= html_writer::tag('small', get_string('transfer_restore_wwwroot_desc', 'local_backupftp'), ['class' => 'form-text text-muted']);
    $html .= html_writer::end_div();

    $html .= html_writer::start_div('form-group');
    $html .= html_writer::tag('label', get_string('transfer_restore_ip', 'local_backupftp'), ['for' => 'id_remoteip']);
    $html .= html_writer::empty_tag('input', [
        'type' => 'text',
        'name' => 'remoteip',
        'id' => 'id_remoteip',
        'class' => 'form-control',
        'placeholder' => '192.0.2.10',
    ]);
    $html .= html_writer::tag('small', get_string('transfer_restore_ip_desc', 'local_backupftp'), ['class' => 'form-text text-muted']);
    $html .= html_writer::end_div();

    $html .= html_writer::start_div('form-group');
    $html .= html_writer::tag('label', get_string('transfer_restore_token', 'local_backupftp'), ['for' => 'id_remotetoken']);
    $html .= html_writer::empty_tag('input', [
        'type' => 'password',
        'name' => 'remotetoken',
        'id' => 'id_remotetoken',
        'class' => 'form-control',
        'required' => 'required',
        'autocomplete' => 'off',
    ]);
    $html .= html_writer::tag('small', get_string('transfer_restore_token_desc', 'local_backupftp'), ['class' => 'form-text text-muted']);
    $html .= html_writer::end_div();

    $html .= html_writer::empty_tag('input', [
        'type' => 'submit',
        'class' => 'btn btn-primary',
        'value' => get_string('transfer_restore_queue_button', 'local_backupftp'),
    ]);

    $html .= html_writer::end_tag('form');
    $html .= html_writer::end_div();
    $html .= html_writer::end_div();

    return $html;
}

/**
 * Render a small table with queued remote files.
 *
 * @param array $rows Table rows.
 * @return string
 */
function local_backupftp_render_remote_files_table(array $rows): string {
    if (empty($rows)) {
        return '';
    }

    $table = new html_table();
    $table->head = [
        get_string('filename'),
        get_string('remote_file', 'local_backupftp'),
        get_string('file_size_label', 'local_backupftp'),
    ];
    $table->data = array_slice($rows, 0, 50);

    $html = html_writer::table($table);
    if (count($rows) > 50) {
        $html .= html_writer::tag('p', get_string('transfer_restore_table_limited', 'local_backupftp', count($rows)));
    }

    return $html;
}

/**
 * Render a live countdown for a Unix timestamp.
 *
 * @param int $timeexpires Expiry timestamp.
 * @return string
 */
function local_backupftp_render_countdown(int $timeexpires): string {
    $id = 'lbf_countdown_' . uniqid();
    $remaining = max(0, $timeexpires - time());

    $html = html_writer::tag('span', format_time($remaining), [
        'id' => $id,
        'class' => 'badge badge-info',
        'data-expires' => $timeexpires,
    ]);

    $html .= html_writer::script("(function(){
        var el = document.getElementById('" . $id . "');
        if (!el) { return; }
        function pad(n){ return n < 10 ? '0' + n : '' + n; }
        function tick(){
            var diff = parseInt(el.getAttribute('data-expires'), 10) - Math.floor(Date.now() / 1000);
            if (diff <= 0) {
                el.textContent = '" . addslashes(get_string('transfer_restore_token_expired', 'local_backupftp')) . "';
                el.className = 'badge badge-danger';
                return;
            }
            var days = Math.floor(diff / 86400);
            diff = diff % 86400;
            var hours = Math.floor(diff / 3600);
            diff = diff % 3600;
            var minutes = Math.floor(diff / 60);
            var seconds = diff % 60;
            el.textContent = (days > 0 ? days + 'd ' : '') + pad(hours) + ':' + pad(minutes) + ':' + pad(seconds);
        }
        tick();
        window.setInterval(tick, 1000);
    })();");

    return $html;
}

/**
 * Basic remotefile sanitizer (keeps unicode/spaces, blocks traversal).
 */
function local_backupftp_clean_remotefile(string $remotefile): string {
    $remotefile = trim(str_replace(chr(0), '', $remotefile));

    if ($remotefile === '' || strpos($remotefile, '\\') !== false) {
        return '';
    }
    if (preg_match('#(^|/)\.\.(/|$)#', $remotefile)) {
        return '';
    }

    $ext = core_text::strtolower(pathinfo($remotefile, PATHINFO_EXTENSION));
    if ($ext !== 'mbz') {
        return '';
    }

    return $remotefile;
}

/**
 * Ensure the target file belongs to configured restore sources.
 */
function local_backupftp_is_allowed_restore_target(
    string $remotefile,
    bool $ftpenable,
    string $ftppasta,
    bool $localfileenable,
    string $localfilepath
): bool {
    if ($localfileenable && $localfilepath !== '') {
        $root = realpath($localfilepath);
        $real = realpath($remotefile);
        if ($root && $real) {
            $root = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if (strpos($real, $root) === 0) {
                return true;
            }
        }
    }

    if ($ftpenable && $ftppasta !== '') {
        $prefix = rtrim($ftppasta, '/');
        if (strpos($remotefile, $prefix . '/') === 0) {
            return true;
        }
    }

    return false;
}

/**
 * List files from FTP source and return HTML for the restore form.
 */
function local_backupftp_list_filesfromftp(string $directory): string {
    global $DB, $CFG, $OUTPUT, $ftppasta;

    if (!get_config('local_backupftp', 'ftpenable')) {
        return '';
    }

    require_once($CFG->dirroot . '/local/backupftp/classes/server/ftp.php');
    $ftp = new ftp();
    $ftp->connect();

    if (empty($ftp->conn_id)) {
        return html_writer::tag('p', get_string('ftp_error_connecting', 'local_backupftp'));
    }

    $files = [];
    $raw = @ftp_rawlist($ftp->conn_id, rtrim($directory, '/') . '/');

    if (is_array($raw)) {
        foreach ($raw as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = preg_split('/\s+/', $line, 9);
            if (count($parts) < 9) {
                continue;
            }

            $perm = $parts[0];
            $type = (isset($perm[0]) && $perm[0] === 'd') ? 'dir' : 'file';
            $size = $parts[4];
            $modify = $parts[5] . ' ' . $parts[6] . ' ' . $parts[7];
            $name = $parts[8];

            if ($name === '.' || $name === '..') {
                continue;
            }

            $files[] = [
                'type' => $type,
                'size' => $size,
                'modify' => $modify,
                'name' => $name,
            ];
        }
    }

    if (empty($files)) {
        return '';
    }

    $unique = uniqid('lbf_');
    $categoria = str_replace($ftppasta, '', $directory);
    $infocategori = category::get_category($categoria);

    $internalreturn = '';

    foreach ($files as $file) {
        if ($file['type'] === 'dir') {
            $internalreturn .= local_backupftp_list_filesfromftp(rtrim($directory, '/') . '/' . $file['name']);
            continue;
        }

        $ext = core_text::strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'mbz') {
            continue;
        }

        $remotefile = rtrim($directory, '/') . '/' . $file['name'];

        $restoretext = '';
        $showinput = html_writer::empty_tag('input', [
            'type' => 'checkbox',
            'name' => 'file[]',
            'value' => $remotefile,
        ]);

        if ($restore = $DB->get_record('local_backupftp_restore', ['remotefile' => $remotefile], '*', IGNORE_MULTIPLE)) {
            $restoretext .= html_writer::empty_tag('br') . ' / ' .
                html_writer::tag(
                    'span',
                    get_string('already_added_status', 'local_backupftp', ['status' => s($restore->status)]),
                    ['style' => 'color:#3F51B5']
                );
        }

        $filesize = get_string('file_size', 'local_backupftp', [
            'size' => ftp::format_bytes($file['size']),
        ]);
        $createdontime = get_string('created_on_time', 'local_backupftp', ['modify' => s($file['modify'])]);

        $internalreturn .= $OUTPUT->render_from_template('local_backupftp/restore_p', [
            'showinput' => $showinput,
            'filename' => $file['name'],
            'filesize' => $filesize,
            'createdontime' => $createdontime,
            'restoretext' => $restoretext,
        ]);
    }

    return $OUTPUT->render_from_template('local_backupftp/restore_fieldset', [
        'infocategori_link' => $infocategori['link'],
        'unique' => $unique,
        'data' => $internalreturn,
    ]);
}

/**
 * List files from local filesystem source and return HTML for the restore form.
 */
function local_backupftp_list_filesfromlocal(string $directory): string {
    global $DB, $OUTPUT, $localfilepath;

    if (!get_config('local_backupftp', 'localfileenable')) {
        return '';
    }

    if ($directory === '' || !is_dir($directory) || !is_readable($directory)) {
        return '';
    }

    $files = [];

    foreach (new DirectoryIterator($directory) as $fileinfo) {
        if ($fileinfo->isDot() || $fileinfo->isLink()) {
            continue;
        }

        $files[] = [
            'type' => $fileinfo->isDir() ? 'dir' : 'file',
            'size' => $fileinfo->isFile() ? $fileinfo->getSize() : 0,
            'modify' => date('Y-m-d H:i:s', $fileinfo->getMTime()),
            'name' => $fileinfo->getPathname(),
        ];
    }

    if (empty($files)) {
        return '';
    }

    $unique = uniqid('lbf_');
    $categoria = str_replace($localfilepath, '', $directory);
    $infocategori = category::get_category($categoria);

    $internalreturn = '';

    foreach ($files as $file) {
        if ($file['type'] === 'dir') {
            $root = realpath($localfilepath);
            $real = realpath($file['name']);
            if ($root && $real) {
                $root = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                if (strpos($real, $root) === 0) {
                    $internalreturn .= local_backupftp_list_filesfromlocal($file['name']);
                }
            }
            continue;
        }

        $ext = core_text::strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'mbz') {
            continue;
        }

        $remotefile = $file['name'];

        $restoretext = '';
        $showinput = html_writer::empty_tag('input', [
            'type' => 'checkbox',
            'name' => 'file[]',
            'value' => $remotefile,
        ]);

        if ($restore = $DB->get_record('local_backupftp_restore', ['remotefile' => $remotefile], '*', IGNORE_MULTIPLE)) {
            $restoretext .= html_writer::empty_tag('br') . ' / ' .
                html_writer::tag(
                    'span',
                    get_string('already_added_status', 'local_backupftp', ['status' => s($restore->status)]),
                    ['style' => 'color:#3F51B5']
                );
        }

        $displayname = basename($file['name']);

        $filesize = get_string('file_size', 'local_backupftp', [
            'size' => ftp::format_bytes($file['size']),
        ]);
        $createdontime = get_string('created_on_time', 'local_backupftp', ['modify' => s($file['modify'])]);

        $internalreturn .= $OUTPUT->render_from_template('local_backupftp/restore_p', [
            'showinput' => $showinput,
            'filename' => $displayname,
            'filesize' => $filesize,
            'createdontime' => $createdontime,
            'restoretext' => $restoretext,
        ]);
    }

    return $OUTPUT->render_from_template('local_backupftp/restore_fieldset', [
        'infocategori_link' => $infocategori['link'],
        'unique' => $unique,
        'data' => $internalreturn,
    ]);
}
