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
$PAGE->set_pagelayout("admin");
$PAGE->set_title(get_string('restore_courses_and_categories', 'local_backupftp'));
$PAGE->set_heading(get_string('restore_courses_and_categories', 'local_backupftp'));
$PAGE->requires->js_call_amd('local_backupftp/categoryselector', 'init');

require_login();
require_capability('local/backupftp:manage', $context);

echo $OUTPUT->header();

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

        $data = (object) [
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

// Handle POST: save remote Moodle transfer form data in the user's session.
$remotequeue = optional_param('remotequeue', 0, PARAM_INT);
if (!empty($remotequeue)) {
    require_sesskey();
    local_backupftp_save_remote_transfer_restore_session();
}

// Handle POST: clear the remote Moodle transfer session data.
$remoteclear = optional_param('remoteclear', 0, PARAM_INT);
if (!empty($remoteclear)) {
    require_sesskey();
    local_backupftp_clear_remote_transfer_restore_session();
}

// Handle POST: queue only the checked remote Moodle backup files.
$remoterestoremarked = optional_param('remoterestoremarked', 0, PARAM_INT);
if (empty($remoteclear) && !empty($remoterestoremarked)) {
    require_sesskey();
    $selectedfiles = optional_param_array('remotefile', [], PARAM_RAW_TRIMMED);
    local_backupftp_queue_remote_transfer_restore($selectedfiles);
}

// Info / links.
echo $OUTPUT->render_from_template('local_backupftp/restore_info', []);

$remotesession = local_backupftp_get_remote_transfer_restore_session();
echo $OUTPUT->render_from_template('local_backupftp/remote_transfer_form', [
    'actionurl' => $PAGE->url->out(false),
    'sesskey' => sesskey(),
    'remotewwwroot' => $remotesession['remotewwwroot'] ?? '',
    'remoteip' => $remotesession['remoteip'] ?? '',
    'remotetoken' => $remotesession['remotetoken'] ?? '',
]);

echo local_backupftp_render_remote_transfer_restore_summary();

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
 * Return the remote Moodle transfer data saved in the user's session.
 *
 * @return array
 */
function local_backupftp_get_remote_transfer_restore_session(): array {
    global $SESSION;

    $data = $SESSION->local_backupftp_remote_restore ?? [];
    if (!is_array($data)) {
        return [];
    }

    return [
        'remotewwwroot' => (string)($data['remotewwwroot'] ?? ''),
        'remoteip' => (string)($data['remoteip'] ?? ''),
        'remotetoken' => (string)($data['remotetoken'] ?? ''),
        'timecreated' => (int)($data['timecreated'] ?? 0),
    ];
}

/**
 * Save remote Moodle transfer form data in the user's session.
 *
 * @return void
 */
function local_backupftp_save_remote_transfer_restore_session(): void {
    global $SESSION, $OUTPUT;

    $remotewwwroot = optional_param('remotewwwroot', '', PARAM_RAW_TRIMMED);
    $remoteip = optional_param('remoteip', '', PARAM_RAW_TRIMMED);
    $remotetoken = optional_param('remotetoken', '', PARAM_RAW_TRIMMED);

    try {
        $SESSION->local_backupftp_remote_restore = [
            'remotewwwroot' => transfer_client::clean_wwwroot($remotewwwroot),
            'remoteip' => transfer_client::clean_ip($remoteip),
            'remotetoken' => transfer_client::clean_token($remotetoken),
            'timecreated' => time(),
        ];

        echo $OUTPUT->notification(get_string('transfer_restore_session_saved', 'local_backupftp'), 'notifysuccess');
    } catch (Throwable $e) {
        unset($SESSION->local_backupftp_remote_restore);
        echo $OUTPUT->notification(s($e->getMessage()), 'notifyproblem');
    }
}

/**
 * Clear remote Moodle transfer form data from the user's session.
 *
 * @return void
 */
function local_backupftp_clear_remote_transfer_restore_session(): void {
    global $SESSION, $OUTPUT;

    unset($SESSION->local_backupftp_remote_restore);
    echo $OUTPUT->notification(get_string('transfer_restore_session_cleared', 'local_backupftp'), 'notifysuccess');
}

/**
 * Render the remote Moodle backup summary using the data saved in session.
 *
 * @return string
 */
function local_backupftp_render_remote_transfer_restore_summary(): string {
    global $OUTPUT, $PAGE;

    $sessiondata = local_backupftp_get_remote_transfer_restore_session();
    if (empty($sessiondata['remotewwwroot']) || empty($sessiondata['remotetoken'])) {
        return '';
    }

    try {
        $response = transfer_client::fetch_backups(
            $sessiondata['remotewwwroot'],
            $sessiondata['remoteip'],
            $sessiondata['remotetoken']
        );
        $tokenexpires = transfer_client::get_token_expires_from_response($response);
        $files = $response['data']['files'] ?? [];

        if (!is_array($files) || empty($files)) {
            return $OUTPUT->notification(get_string('transfer_restore_no_backups', 'local_backupftp'), 'notifyproblem');
        }

        return $OUTPUT->render_from_template(
            'local_backupftp/remote_restore_summary',
            [
                'actionurl' => $PAGE->url->out(false),
                'sesskey' => sesskey(),
                'hascounter' => $tokenexpires > 0,
                'countdown' => $tokenexpires > 0 ? local_backupftp_render_countdown($tokenexpires) : '',
                'restoreurl' => new moodle_url('/local/backupftp/report-restore.php'),
            ] + local_backupftp_render_remote_files_table($files)
        );
    } catch (Throwable $e) {
        return $OUTPUT->notification(s($e->getMessage()), 'notifyproblem');
    }
}

/**
 * Queue selected remote Moodle backup files using the transfer API.
 *
 * @param array $selectedfiles Selected remote relative paths.
 * @return void
 */
function local_backupftp_queue_remote_transfer_restore(array $selectedfiles): void {
    global $DB, $OUTPUT;

    $sessiondata = local_backupftp_get_remote_transfer_restore_session();
    if (empty($sessiondata['remotewwwroot']) || empty($sessiondata['remotetoken'])) {
        echo $OUTPUT->notification(get_string('transfer_restore_missing_remote_data', 'local_backupftp'), 'notifyproblem');
        return;
    }

    $selected = [];
    foreach ($selectedfiles as $selectedfile) {
        $relativepath = transfer_client::clean_backup_file((string)$selectedfile);
        if ($relativepath !== '') {
            $selected[$relativepath] = true;
        }
    }

    if (empty($selected)) {
        echo $OUTPUT->notification(get_string('transfer_restore_no_selection', 'local_backupftp'), 'notifyproblem');
        return;
    }

    try {
        $remotewwwroot = $sessiondata['remotewwwroot'];
        $remoteip = $sessiondata['remoteip'];
        $remotetoken = $sessiondata['remotetoken'];

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

        foreach ($files as $file) {
            if (!is_array($file)) {
                $ignored++;
                continue;
            }

            $relativepath = transfer_client::clean_backup_file((string)($file['relativepath'] ?? ''));
            if ($relativepath === '' || empty($selected[$relativepath])) {
                continue;
            }

            $filesize = (int)($file['size'] ?? 0);
            $timemodified = (int)($file['timemodified'] ?? 0);

            $existing = $DB->get_record_select(
                'local_backupftp_restore',
                'source = :source AND sourcewwwroot = :sourcewwwroot AND remotefile = :remotefile AND status <> :completed',
                [
                    'source' => 'transfer',
                    'sourcewwwroot' => $remotewwwroot,
                    'remotefile' => $relativepath,
                    'completed' => 'completed',
                ], '*', IGNORE_MULTIPLE
            );

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
        }

        if (($queued + $updated) === 0) {
            echo $OUTPUT->notification(get_string('transfer_restore_no_selection', 'local_backupftp'), 'notifyproblem');
            return;
        }

        $userssummary = '';
        try {
            $usersresult = local_backupftp_restore_remote_users($remotewwwroot, $remoteip, $remotetoken);
            $userssummary = get_string('transfer_restore_users_summary', 'local_backupftp', $usersresult);
        } catch (Throwable $userexception) {
            $userssummary = get_string('transfer_restore_users_failed', 'local_backupftp', s($userexception->getMessage()));
        }

        $summary = get_string('transfer_restore_queue_summary', 'local_backupftp', [
            'queued' => $queued,
            'updated' => $updated,
            'ignored' => $ignored,
        ]);

        if ($userssummary !== '') {
            $summary .= html_writer::tag('div', $userssummary, ['class' => 'mt-2']);
        }

        echo $OUTPUT->notification($summary, 'notifysuccess');
    } catch (Throwable $e) {
        echo $OUTPUT->notification(s($e->getMessage()), 'notifyproblem');
    }
}

/**
 * Restore users from a remote Moodle transfer API response.
 *
 * Password hashes are not imported. Newly created manual users receive a random
 * local password and are forced to change it before logging in.
 *
 * @param string $remotewwwroot Remote wwwroot.
 * @param string $remoteip Optional remote server IP.
 * @param string $remotetoken Transfer token.
 * @return stdClass Summary object for language strings.
 * @throws \moodle_exception
 */
function local_backupftp_restore_remote_users(string $remotewwwroot, string $remoteip, string $remotetoken): stdClass {
    global $CFG;

    require_once($CFG->dirroot . '/user/lib.php');

    $response = transfer_client::fetch_users($remotewwwroot, $remoteip, $remotetoken);
    $users = $response['data']['items'] ?? [];

    $summary = (object) [
        'created' => 0,
        'updated' => 0,
        'ignored' => 0,
        'errors' => 0,
    ];

    if (!is_array($users)) {
        return $summary;
    }

    foreach ($users as $remoteuser) {
        try {
            if (!is_array($remoteuser)) {
                $summary->ignored++;
                continue;
            }

            $user = local_backupftp_prepare_remote_user($remoteuser);
            if (!$user) {
                $summary->ignored++;
                continue;
            }

            $existing = local_backupftp_find_existing_user($user);
            if ($existing) {
                $user->id = (int) $existing->id;
                unset($user->password);
                user_update_user($user, false, false);
                $summary->updated++;
            } else {
                $user->password = random_string(32);
                $userid = user_create_user($user, true, false);
                if ($user->auth === 'manual') {
                    set_user_preference('auth_forcepasswordchange', 1, $userid);
                }
                $summary->created++;
            }
        } catch (Throwable $e) {
            $summary->errors++;
        }
    }

    return $summary;
}

/**
 * Prepare one remote user for local create/update.
 *
 * @param array $remoteuser Remote API user.
 * @return stdClass|null
 */
function local_backupftp_prepare_remote_user(array $remoteuser): ?stdClass {
    global $CFG;

    $oldid = (int) ($remoteuser['id'] ?? 0);
    $username = clean_param((string) ($remoteuser['username'] ?? ''), PARAM_USERNAME);
    $username = trim(core_text::strtolower($username));

    if ($oldid <= 2 || $username === '' || in_array($username, ['guest', 'admin'], true)) {
        return null;
    }

    $auth = clean_param((string) ($remoteuser['auth'] ?? 'manual'), PARAM_PLUGIN);
    if ($auth === '') {
        $auth = 'manual';
    }
    $enabledauths = get_enabled_auth_plugins();
    if (!in_array($auth, $enabledauths, true)) {
        $auth = 'manual';
    }

    $email = trim((string) ($remoteuser['email'] ?? ''));
    if (!validate_email($email)) {
        $email = 'migrated-user-' . $oldid . '@example.invalid';
        $remoteuser['emailstop'] = 1;
    }

    $user = (object) [
        'auth' => $auth,
        'confirmed' => (int) ($remoteuser['confirmed'] ?? 1),
        'policyagreed' => (int) ($remoteuser['policyagreed'] ?? 0),
        'deleted' => 0,
        'suspended' => (int) ($remoteuser['suspended'] ?? 0),
        'mnethostid' => $CFG->mnet_localhost_id,
        'username' => $username,
        'idnumber' => local_backupftp_limit_text((string) ($remoteuser['idnumber'] ?? ''), 255),
        'firstname' => local_backupftp_limit_text((string) ($remoteuser['firstname'] ?? $username), 100),
        'lastname' => local_backupftp_limit_text((string) ($remoteuser['lastname'] ?? '-'), 100),
        'email' => local_backupftp_limit_text($email, 100),
        'emailstop' => (int) ($remoteuser['emailstop'] ?? 0),
        'phone1' => local_backupftp_limit_text((string) ($remoteuser['phone1'] ?? ''), 20),
        'phone2' => local_backupftp_limit_text((string) ($remoteuser['phone2'] ?? ''), 20),
        'institution' => local_backupftp_limit_text((string) ($remoteuser['institution'] ?? ''), 255),
        'department' => local_backupftp_limit_text((string) ($remoteuser['department'] ?? ''), 255),
        'address' => local_backupftp_limit_text((string) ($remoteuser['address'] ?? ''), 255),
        'city' => local_backupftp_limit_text((string) ($remoteuser['city'] ?? ''), 120),
        'country' => local_backupftp_limit_text((string) ($remoteuser['country'] ?? ''), 2),
        'lang' => local_backupftp_limit_text((string) ($remoteuser['lang'] ?? current_language()), 30),
        'calendartype' => local_backupftp_limit_text((string) ($remoteuser['calendartype'] ?? ''), 30),
        'theme' => local_backupftp_limit_text((string) ($remoteuser['theme'] ?? ''), 50),
        'timezone' => local_backupftp_limit_text((string) ($remoteuser['timezone'] ?? '99'), 100),
        'mailformat' => (int) ($remoteuser['mailformat'] ?? 1),
        'maildigest' => (int) ($remoteuser['maildigest'] ?? 0),
        'maildisplay' => (int) ($remoteuser['maildisplay'] ?? 2),
        'autosubscribe' => (int) ($remoteuser['autosubscribe'] ?? 1),
        'trackforums' => (int) ($remoteuser['trackforums'] ?? 0),
        'description' => (string) ($remoteuser['description'] ?? ''),
        'descriptionformat' => (int) ($remoteuser['descriptionformat'] ?? FORMAT_HTML),
        'imagealt' => local_backupftp_limit_text((string) ($remoteuser['imagealt'] ?? ''), 255),
        'lastnamephonetic' => local_backupftp_limit_text((string) ($remoteuser['lastnamephonetic'] ?? ''), 255),
        'firstnamephonetic' => local_backupftp_limit_text((string) ($remoteuser['firstnamephonetic'] ?? ''), 255),
        'middlename' => local_backupftp_limit_text((string) ($remoteuser['middlename'] ?? ''), 255),
        'alternatename' => local_backupftp_limit_text((string) ($remoteuser['alternatename'] ?? ''), 255),
    ];

    if ($user->firstname === '') {
        $user->firstname = $username;
    }
    if ($user->lastname === '') {
        $user->lastname = '-';
    }
    if ($user->confirmed !== 1) {
        $user->confirmed = 1;
    }

    return $user;
}

/**
 * Find an existing local user that matches a remote user.
 *
 * @param stdClass $user Prepared user.
 * @return stdClass|false
 * @throws \dml_exception
 */
function local_backupftp_find_existing_user(stdClass $user) {
    global $CFG, $DB;

    $params = ['username' => $user->username, 'mnethostid' => $CFG->mnet_localhost_id];
    $existing = $DB->get_record_select(
        'user',
        'username = :username AND mnethostid = :mnethostid AND deleted = 0', $params, '*', IGNORE_MULTIPLE
    );
    if ($existing) {
        return $existing;
    }

    if (!empty($user->idnumber)) {
        $existing = $DB->get_record_select(
            'user', 'idnumber = :idnumber AND deleted = 0',
            ['idnumber' => $user->idnumber], '*', IGNORE_MULTIPLE
        );
        if ($existing) {
            return $existing;
        }
    }

    if (!empty($user->email)) {
        $existing = $DB->get_record_select(
            'user', 'email = :email AND deleted = 0',
            ['email' => $user->email], '*', IGNORE_MULTIPLE
        );
        if ($existing) {
            return $existing;
        }
    }

    return false;
}

/**
 * Trim DB text fields safely.
 *
 * @param string $value Raw value.
 * @param int $maxlength Max length.
 * @return string
 */
function local_backupftp_limit_text(string $value, int $maxlength): string {
    $value = trim(str_replace(chr(0), '', $value));
    if (core_text::strlen($value) > $maxlength) {
        $value = core_text::substr($value, 0, $maxlength);
    }

    return $value;
}

/**
 * Guess remote file origin from filename/path when the remote API does not send metadata yet.
 *
 * @param string $filename MBZ filename.
 * @param string $relativepath Remote relative backup path.
 * @return array
 */
function local_backupftp_guess_remote_file_origin(string $filename, string $relativepath): array {
    $courseid = 0;
    $coursefullname = '';

    if (preg_match('/^(\d+)\s*-\s*(.+)\.mbz$/iu', $filename, $matches)) {
        $courseid = (int)$matches[1];
        $coursefullname = trim($matches[2]);
    } else if (preg_match('/backup-moodle2-course-(\d+)-([^\/]+?)-\d{8,}/iu', $filename, $matches)) {
        $courseid = (int)$matches[1];
        $coursefullname = trim(str_replace(['_', '-'], ' ', $matches[2]));
    } else if (preg_match('/backup-moodle2-course-(\d+)-/iu', $filename, $matches)) {
        $courseid = (int)$matches[1];
    }

    $categoryname = '';
    $pathparts = explode('/', $relativepath);
    array_pop($pathparts);
    if (!empty($pathparts)) {
        $categoryname = implode(' / ', array_map('trim', $pathparts));
    }

    return [
        'courseid' => $courseid,
        'coursefullname' => $coursefullname,
        'categoryname' => $categoryname,
    ];
}

/**
 * Render a small table with remote files returned by the transfer API.
 *
 * @param array $files Remote API file rows.
 * @return array
 * @throws \coding_exception
 */
function local_backupftp_render_remote_files_table(array $files): array {
    if (empty($files)) {
        return [];
    }

    $tablerows = [];
    foreach ($files as $file) {
        if (!is_array($file)) {
            continue;
        }

        $relativepath = transfer_client::clean_backup_file((string)($file['relativepath'] ?? ''));
        if ($relativepath === '') {
            continue;
        }

        $filename = (string)($file['filename'] ?? basename($relativepath));
        $courseid = (int)($file['courseid'] ?? 0);
        $coursefullname = (string)($file['coursefullname'] ?? ($file['coursename'] ?? ''));
        $categoryid = (int)($file['categoryid'] ?? 0);
        $categoryname = (string)($file['categoryname'] ?? '');

        if ($courseid <= 0 && $coursefullname === '') {
            $guessedorigin = local_backupftp_guess_remote_file_origin($filename, $relativepath);
            $courseid = (int)$guessedorigin['courseid'];
            $coursefullname = $guessedorigin['coursefullname'];
            if ($categoryname === '') {
                $categoryname = $guessedorigin['categoryname'];
            }
        }

        $tablerows[] = [
            'filename' => $filename,
            'relativepath' => $relativepath,
            'filesize' => display_size((int)($file['size'] ?? 0)),
            'courseid' => $courseid > 0 ? $courseid : '',
            'coursefullname' => $coursefullname,
            'hascourse' => $courseid > 0 || $coursefullname !== '',
            'categoryid' => $categoryid > 0 ? $categoryid : '',
            'categoryname' => $categoryname,
            'hascategory' => $categoryid > 0 || $categoryname !== '',
        ];
    }

    return [
        'tablerows' => $tablerows,
        'limited' => false,
    ];
}

/**
 * Render a live countdown for a Unix timestamp.
 *
 * @param int $timeexpires Expiry timestamp.
 * @return string
 * @throws \coding_exception
 */
function local_backupftp_render_countdown(int $timeexpires): string {
    global $OUTPUT;

    $id = 'lbf_countdown_' . uniqid();
    $remaining = max(0, $timeexpires - time());
    $expiredjson = json_encode(
        get_string('transfer_token_expired', 'local_backupftp'),
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    );

    return $OUTPUT->render_from_template('local_backupftp/countdown', [
        'id' => $id,
        'remaining' => format_time($remaining),
        'timeexpires' => $timeexpires,
        'expiredjson' => $expiredjson,
    ]);
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
 *
 * @throws \coding_exception
 * @throws \dml_exception
 * @throws \Exception
 */
function local_backupftp_list_filesfromftp(string $directory): string {
    global $DB, $CFG, $OUTPUT, $ftppasta;

    if (!get_config('local_backupftp', 'ftpenable')) {
        return '';
    }

    require_once($CFG->dirroot . '/local/backupftp/classes/server/ftp.php');
    $ftp = new ftp();
    $ftp->connect();

    if (empty($ftp->connid)) {
        return html_writer::tag('p', get_string('ftp_error_connecting', 'local_backupftp'));
    }

    $files = [];
    $raw = @ftp_rawlist($ftp->connid, rtrim($directory, '/') . '/');

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

    usort($files, function(array $a, array $b): int {
        if ($a['type'] !== $b['type']) {
            return $a['type'] === 'dir' ? -1 : 1;
        }
        return strnatcasecmp($a['name'], $b['name']);
    });

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

        $alreadyadded = false;
        $alreadytext = '';
        if ($restore = $DB->get_record('local_backupftp_restore', ['remotefile' => $remotefile], '*', IGNORE_MULTIPLE)) {
            $alreadyadded = true;
            $alreadytext = get_string('already_added_status', 'local_backupftp', ['status' => $restore->status]);
        }

        $filesize = get_string('file_size', 'local_backupftp', [
            'size' => ftp::format_bytes($file['size']),
        ]);
        $createdontime = get_string('created_on_time', 'local_backupftp', ['modify' => s($file['modify'])]);

        $internalreturn .= $OUTPUT->render_from_template('local_backupftp/restore_p', [
            'remotefile' => $remotefile,
            'filename' => $file['name'],
            'filesize' => $filesize,
            'createdontime' => $createdontime,
            'alreadyadded' => $alreadyadded,
            'alreadytext' => $alreadytext,
        ]);
    }

    if ($internalreturn === '') {
        return '';
    }

    return $OUTPUT->render_from_template('local_backupftp/restore_fieldset', [
        'infocategori_link' => $infocategori['link'],
        'unique' => $unique,
        'data' => $internalreturn,
    ]);
}

/**
 * List files from local filesystem source and return HTML for the restore form.
 *
 * @throws \coding_exception
 * @throws \Exception
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

    usort($files, function(array $a, array $b): int {
        if ($a['type'] !== $b['type']) {
            return $a['type'] === 'dir' ? -1 : 1;
        }
        return strnatcasecmp($a['name'], $b['name']);
    });

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

        $alreadyadded = false;
        $alreadytext = '';

        $sql = "
            SELECT * 
              FROM {local_backupftp_restore}
             WHERE remotefile = :remotefile";
        if ($restore = $DB->get_record_sql($sql, ['remotefile' => $remotefile], IGNORE_MULTIPLE)) {
            $alreadyadded = true;
            $alreadytext = get_string('already_added_status', 'local_backupftp', ['status' => $restore->status]);
        }

        $displayname = basename($file['name']);

        $filesize = get_string('file_size', 'local_backupftp', [
            'size' => ftp::format_bytes($file['size']),
        ]);
        $createdontime = get_string('created_on_time', 'local_backupftp', ['modify' => s($file['modify'])]);

        $internalreturn .= $OUTPUT->render_from_template('local_backupftp/restore_p', [
            'remotefile' => $remotefile,
            'filename' => $displayname,
            'filesize' => $filesize,
            'createdontime' => $createdontime,
            'alreadyadded' => $alreadyadded,
            'alreadytext' => $alreadytext,
        ]);
    }

    if ($internalreturn === '') {
        return '';
    }

    return $OUTPUT->render_from_template('local_backupftp/restore_fieldset', [
        'infocategori_link' => $infocategori['link'],
        'unique' => $unique,
        'data' => $internalreturn,
    ]);
}
