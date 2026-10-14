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
 * Renderer API helper.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\renderer;

use html_writer;
use local_backupftp\storage\provider;
use local_backupftp\util\file;
use local_backupftp\transfer_client;
use local_backupftp\util\category;
use moodle_url;
use stdClass;
use Throwable;

/**
 * Class restore
 */
class restore {
    /**
     * Return the remote Moodle transfer data saved in the user's session.
     *
     * @return array
     */
    public static function get_remote_transfer_restore_session(): array {
        global $SESSION;

        $data = $SESSION->local_backupftp_remote_restore ?? [];
        if (!is_array($data)) {
            return [];
        }

        return [
            'remotewwwroot' => (string) ($data['remotewwwroot'] ?? ''),
            'remoteip' => (string) ($data['remoteip'] ?? ''),
            'remotetoken' => (string) ($data['remotetoken'] ?? ''),
            'timecreated' => (int) ($data['timecreated'] ?? 0),
        ];
    }

    /**
     * Save remote Moodle transfer form data in the user's session.
     *
     * @return void
     * @throws \coding_exception
     */
    public static function save_remote_transfer_restore_session(): void {
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
     * @throws \coding_exception
     */
    public static function clear_remote_transfer_restore_session(): void {
        global $SESSION, $OUTPUT;

        unset($SESSION->local_backupftp_remote_restore);
        echo $OUTPUT->notification(get_string('transfer_restore_session_cleared', 'local_backupftp'), 'notifysuccess');
    }

    /**
     * Render the remote Moodle backup summary using the data saved in session.
     *
     * @return string
     */
    public static function render_remote_transfer_restore_summary(): string {
        global $OUTPUT, $PAGE;

        $sessiondata = self::get_remote_transfer_restore_session();
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
                    'countdown' => $tokenexpires > 0 ? self::render_countdown($tokenexpires) : '',
                    'restoreurl' => new moodle_url('/local/backupftp/report-restore.php'),
                ] + self::render_remote_files_table($files)
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
     * @throws \coding_exception
     */
    public static function queue_remote_transfer_restore(array $selectedfiles): void {
        global $DB, $OUTPUT;

        $sessiondata = self::get_remote_transfer_restore_session();
        if (empty($sessiondata['remotewwwroot']) || empty($sessiondata['remotetoken'])) {
            echo $OUTPUT->notification(get_string('transfer_restore_missing_remote_data', 'local_backupftp'), 'notifyproblem');
            return;
        }

        $selected = [];
        foreach ($selectedfiles as $selectedfile) {
            $relativepath = transfer_client::clean_backup_file((string) $selectedfile);
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

                $relativepath = transfer_client::clean_backup_file((string) ($file['relativepath'] ?? ''));
                if ($relativepath === '' || empty($selected[$relativepath])) {
                    continue;
                }

                $filesize = (int) ($file['size'] ?? 0);
                $timemodified = (int) ($file['timemodified'] ?? 0);

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
                    $existing->sourcefilesize = $filesize;
                    $existing->sourcetimemodified = $timemodified;
                    if ($existing->status === 'error' ) {
                        $existing->status = 'waiting';
                        $existing->logs = '';
                        $existing->timestart = 0;
                        $existing->timeend = 0;
                    }
                    $DB->update_record('local_backupftp_restore', $existing);
                    $updated++;
                } else {
                    $data = (object) [
                        'remotefile' => $relativepath,
                        'source' => 'transfer',
                        'sourcewwwroot' => $remotewwwroot,
                        'sourceip' => $remoteip,
                        'sourcetoken' => $remotetoken,
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

            try {
                $usersresult = self::restore_remote_users($remotewwwroot, $remoteip, $remotetoken);
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
    public static function restore_remote_users(string $remotewwwroot, string $remoteip, string $remotetoken): stdClass {
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

                $user = self::prepare_remote_user($remoteuser);
                if (!$user) {
                    $summary->ignored++;
                    continue;
                }

                $existing = self::find_existing_user($user);
                if ($existing) {
                    $user->id = (int) $existing->id;
                    unset($user->password);
                    if (method_exists(\core\user::class, 'update_user')) {
                        \core\user::update_user($user, false, false);
                    } else {
                        user_update_user($user, false, false);
                    }
                    $summary->updated++;
                } else {
                    $user->password = random_string(32);
                    if (method_exists(\core\user::class, 'create_user')) {
                        $userid = \core\user::create_user($user, true, false);
                    } else {
                        $userid = user_create_user($user, true, false);
                    }
                    if ($user->auth === 'manual') {
                        set_user_preference('auth_forcepasswordchange', 1, $userid);
                    }
                    $summary->created++;
                }
            } catch (Throwable) {
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
     * @throws \coding_exception
     */
    public static function prepare_remote_user(array $remoteuser): ?stdClass {
        global $CFG;

        $oldid = (int) ($remoteuser['id'] ?? 0);
        $username = clean_param((string) ($remoteuser['username'] ?? ''), PARAM_USERNAME);
        $username = trim(strtolower($username));

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
            'idnumber' => self::limit_text((string) ($remoteuser['idnumber'] ?? ''), 255),
            'firstname' => self::limit_text((string) ($remoteuser['firstname'] ?? $username), 100),
            'lastname' => self::limit_text((string) ($remoteuser['lastname'] ?? '-'), 100),
            'email' => self::limit_text($email, 100),
            'emailstop' => (int) ($remoteuser['emailstop'] ?? 0),
            'phone1' => self::limit_text((string) ($remoteuser['phone1'] ?? ''), 20),
            'phone2' => self::limit_text((string) ($remoteuser['phone2'] ?? ''), 20),
            'institution' => self::limit_text((string) ($remoteuser['institution'] ?? ''), 255),
            'department' => self::limit_text((string) ($remoteuser['department'] ?? ''), 255),
            'address' => self::limit_text((string) ($remoteuser['address'] ?? ''), 255),
            'city' => self::limit_text((string) ($remoteuser['city'] ?? ''), 120),
            'country' => self::limit_text((string) ($remoteuser['country'] ?? ''), 2),
            'lang' => self::limit_text((string) ($remoteuser['lang'] ?? current_language()), 30),
            'calendartype' => self::limit_text((string) ($remoteuser['calendartype'] ?? ''), 30),
            'theme' => self::limit_text((string) ($remoteuser['theme'] ?? ''), 50),
            'timezone' => self::limit_text((string) ($remoteuser['timezone'] ?? '99'), 100),
            'mailformat' => (int) ($remoteuser['mailformat'] ?? 1),
            'maildigest' => (int) ($remoteuser['maildigest'] ?? 0),
            'maildisplay' => (int) ($remoteuser['maildisplay'] ?? 2),
            'autosubscribe' => (int) ($remoteuser['autosubscribe'] ?? 1),
            'trackforums' => (int) ($remoteuser['trackforums'] ?? 0),
            'description' => (string) ($remoteuser['description'] ?? ''),
            'descriptionformat' => (int) ($remoteuser['descriptionformat'] ?? FORMAT_HTML),
            'imagealt' => self::limit_text((string) ($remoteuser['imagealt'] ?? ''), 255),
            'lastnamephonetic' => self::limit_text((string) ($remoteuser['lastnamephonetic'] ?? ''), 255),
            'firstnamephonetic' => self::limit_text((string) ($remoteuser['firstnamephonetic'] ?? ''), 255),
            'middlename' => self::limit_text((string) ($remoteuser['middlename'] ?? ''), 255),
            'alternatename' => self::limit_text((string) ($remoteuser['alternatename'] ?? ''), 255),
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
    public static function find_existing_user(stdClass $user) {
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
    public static function limit_text(string $value, int $maxlength): string {
        $value = trim(str_replace(chr(0), '', $value));
        if (strlen($value) > $maxlength) {
            $value = substr($value, 0, $maxlength);
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
    public static function guess_remote_file_origin(string $filename, string $relativepath): array {
        $courseid = 0;
        $coursefullname = '';

        if (preg_match('/^(\d+)\s*-\s*(.+)\.mbz$/iu', $filename, $matches)) {
            $courseid = (int) $matches[1];
            $coursefullname = trim($matches[2]);
        } else if (preg_match('/backup-moodle2-course-(\d+)-([^\/]+?)-\d{8,}/iu', $filename, $matches)) {
            $courseid = (int) $matches[1];
            $coursefullname = trim(str_replace(['_', '-'], ' ', $matches[2]));
        } else if (preg_match('/backup-moodle2-course-(\d+)-/iu', $filename, $matches)) {
            $courseid = (int) $matches[1];
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
    public static function render_remote_files_table(array $files): array {
        if (empty($files)) {
            return [];
        }

        $tablerows = [];
        foreach ($files as $file) {
            if (!is_array($file)) {
                continue;
            }

            $relativepath = transfer_client::clean_backup_file((string) ($file['relativepath'] ?? ''));
            if ($relativepath === '') {
                continue;
            }

            $filename = (string) ($file['filename'] ?? basename($relativepath));
            $courseid = (int) ($file['courseid'] ?? 0);
            $coursefullname = (string) ($file['coursefullname'] ?? ($file['coursename'] ?? ''));
            $categoryid = (int) ($file['categoryid'] ?? 0);
            $categoryname = (string) ($file['categoryname'] ?? '');

            if ($courseid <= 0 && $coursefullname === '') {
                $guessedorigin = self::guess_remote_file_origin($filename, $relativepath);
                $courseid = (int) $guessedorigin['courseid'];
                $coursefullname = $guessedorigin['coursefullname'];
                if ($categoryname === '') {
                    $categoryname = $guessedorigin['categoryname'];
                }
            }

            $tablerows[] = [
                'filename' => $filename,
                'relativepath' => $relativepath,
                'filesize' => display_size((int) ($file['size'] ?? 0)),
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
    public static function render_countdown(int $timeexpires): string {
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
    public static function clean_remotefile(string $remotefile): string {
        $remotefile = trim(str_replace(chr(0), '', $remotefile));

        if ($remotefile === '' || strpos($remotefile, '\\') !== false) {
            return '';
        }
        if (preg_match('#(^|/)\.\.(/|$)#', $remotefile)) {
            return '';
        }

        $ext = strtolower(pathinfo($remotefile, PATHINFO_EXTENSION));
        if ($ext !== 'mbz') {
            return '';
        }

        return $remotefile;
    }

    /**
     * Render MBZ files provided by any configured storage subplugin.
     *
     * @param provider $provider Configured provider.
     * @return string
     */
    public static function render_storage_files(provider $provider): string {
        global $DB, $OUTPUT;

        $grouped = [];
        foreach ($provider->list_files() as $fileinfo) {
            $path = (string) $fileinfo['path'];
            if (!$provider->owns($path)) {
                continue;
            }
            $grouped[dirname($path)][] = $fileinfo;
        }

        $html = '';
        foreach ($grouped as $directory => $files) {
            $relative = substr($directory, strlen(rtrim($provider->root(), '/\\')));
            $category = category::get_category($relative);
            $rows = '';
            foreach ($files as $info) {
                $path = (string) $info['path'];
                $existing = $DB->get_record('local_backupftp_restore', [
                    'storageid' => (int) $provider->record()->id,
                    'remotefile' => $path,
                    'source' => 'configured',
                ], 'id,status', IGNORE_MULTIPLE);
                $rows .= $OUTPUT->render_from_template('local_backupftp/restore_p', [
                    'remotefile' => s($provider->record()->id . '|' . $path),
                    'filename' => s(basename($path)),
                    'filesize' => s(get_string('file_size', 'local_backupftp', [
                        'size' => file::format_bytes((int) $info['size']),
                    ])),
                    'createdontime' => s(get_string('created_on_time', 'local_backupftp',
                        ['modify' => $info['modified']])),
                    'alreadyadded' => (bool) $existing,
                    'alreadytext' => $existing ? s(get_string('already_added_status', 'local_backupftp',
                        ['status' => $existing->status])) : '',
                ]);
            }
            if ($rows !== '') {
                $html .= $OUTPUT->render_from_template('local_backupftp/restore_fieldset', [
                    'infocategori_link' => $category['link'],
                    'unique' => uniqid('lbf_'),
                    'data' => $rows,
                ]);
            }
        }
        return $html;
    }

}
