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
 * Restore page (queue restore jobs from FTP/local backups).
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_backupftp\localfilepath;
use local_backupftp\server\ftp;
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

// Config (used by list/render helpers below).
$ftppasta = get_config('local_backupftp', 'ftppasta');
$localfilepath = localfilepath::get_path();
$ftpenable = get_config('local_backupftp', 'ftpenable');
$localfileenable = get_config('local_backupftp', 'localfileenable');

// Handle POST: add selected files to restore queue.
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
            'remotefile = :remotefile AND status <> :status',
            ['remotefile' => $remotefile, 'status' => 'completed']
        );

        if ($exists) {
            continue;
        }

        $data = (object)[
            'remotefile' => $remotefile,
            'status' => 'waiting',
            'logs' => '',
            'timecreated' => time(),
            'timestart' => 0,
            'timeend' => 0,
        ];
        $DB->insert_record('local_backupftp_restore', $data);

        echo html_writer::tag(
            'p',
            get_string('file_added_to_restore_queue', 'local_backupftp', ['file' => s(basename($remotefile))]),
            ['style' => 'color:#2196F3;font-weight:bold;']
        );
    }
}

// Info / links.
echo $OUTPUT->render_from_template('local_backupftp/restore_info', []);

// Validate local path (avoid accidentally pointing to "/").
if (strlen($localfilepath) < 4) {
    $localfilepath = '';
    $localfileenable = false;
}

// Render selection form.
echo $OUTPUT->render_from_template('local_backupftp/restore_form', [
    'actionurl' => $PAGE->url->out(false),
    'sesskey' => sesskey(),
    'list_files_ftp' => $ftpenable ? local_backupftp_list_filesfromftp($ftppasta) : '',
    'list_files_local' => $localfileenable ? local_backupftp_list_filesfromlocal($localfilepath) : '',
]);

echo $OUTPUT->footer();

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
