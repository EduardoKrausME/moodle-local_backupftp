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
 * Token administration page.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_backupftp\token;

require(__DIR__ . '/../../config.php');

require_login();

$context = context_system::instance();
require_capability('local/backupftp:manage', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/backupftp/tokens.php'));
$PAGE->set_pagelayout("admin");
$PAGE->set_title(get_string('transfer_tokens', 'local_backupftp'));
$PAGE->set_heading(get_string('transfer_tokens', 'local_backupftp'));
$PAGE->requires->js_call_amd('local_backupftp/confirmation', 'init');
$PAGE->requires->strings_for_js(['yes', 'no'], 'moodle');
$PAGE->requires->strings_for_js(['confirmation'], 'admin');

$createdtoken = '';

$action = optional_param('action', '', PARAM_ALPHA);
if ($action === 'revoke') {
    require_sesskey();
    $id = required_param('id', PARAM_INT);
    token::revoke($id);
    redirect(new moodle_url('/local/backupftp/tokens.php'), get_string('transfer_token_revoked', 'local_backupftp'));
}

if (optional_param('createtoken', 0, PARAM_INT)) {
    require_sesskey();
    $name = optional_param('name', '', PARAM_TEXT);
    $created = token::create($name);
    $createdtoken = $created['token'];
}

token::cleanup_expired();

echo $OUTPUT->header();
echo $OUTPUT->render_from_template("local_backupftp/page_navigation", [
    "backurl" => new moodle_url("/local/backupftp/"),
]);

if ($createdtoken !== '') {
    echo $OUTPUT->render_from_template('local_backupftp/token_created_notification', [
        'wwwroot' => $CFG->wwwroot,
        'serverip' => trim(file_get_contents('https://api.ipify.org')),
        'token' => $createdtoken,
    ]);
}

echo $OUTPUT->render_from_template('local_backupftp/tokens_form', [
    'actionurl' => $PAGE->url->out(false),
    'sesskey' => sesskey(),
]);

$records = $DB->get_records("local_backupftp_token", null, 'timecreated DESC');
$rows = [];

foreach ($records as $record) {
    $expired = ((int) $record->timeexpires < time());
    $revoked = !empty($record->revoked);
    if ($revoked) {
        $status = get_string('transfer_token_revoke', 'local_backupftp');
    } else if ($expired) {
        $status = get_string('transfer_token_expired', 'local_backupftp');
    } else {
        $status = get_string('transfer_token_status_active', 'local_backupftp');
    }

    $hasaction = !$revoked && !$expired;
    $revokeurl = '';
    $confirmmessage = '';
    if ($hasaction) {
        $url = new moodle_url('/local/backupftp/tokens.php', [
            'action' => 'revoke',
            'id' => $record->id,
            'sesskey' => sesskey(),
        ]);
        $revokeurl = $url->out(false);
        $confirmmessage = get_string('transfer_token_revoke_confirm', 'local_backupftp');
    }

    $rows[] = [
        'name' => $record->name,
        'status' => $status,
        'created' => userdate((int) $record->timecreated, get_string('strftimedatetimeshort', 'langconfig')),
        'expires' => userdate((int) $record->timeexpires, get_string('strftimedatetimeshort', 'langconfig')),
        'remaining' => $expired ? get_string('transfer_token_expired', 'local_backupftp') :
            format_time((int) $record->timeexpires - time()),
        'lastused' => empty($record->lastused) ? '-' :
            userdate((int) $record->lastused, get_string('strftimedatetimeshort', 'langconfig')),
        'uses' => (int) $record->downloadcount,
        'hasaction' => $hasaction,
        'revokeurl' => $revokeurl,
        'confirmmessage' => $confirmmessage,
    ];
}

echo $OUTPUT->render_from_template('local_backupftp/tokens_table', [
    'rows' => $rows,
]);

echo $OUTPUT->footer();
