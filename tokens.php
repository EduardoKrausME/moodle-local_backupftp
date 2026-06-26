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
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('transfer_tokens', 'local_backupftp'));
$PAGE->set_heading(get_string('transfer_tokens', 'local_backupftp'));

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
echo $OUTPUT->heading(get_string('transfer_tokens', 'local_backupftp'));

if ($createdtoken !== '') {
    $apiurl = new moodle_url('/local/backupftp/api.php', [
        'action' => 'backups',
        'token' => $createdtoken,
    ]);
    $downloadexample = new moodle_url('/local/backupftp/download.php', [
        'f' => 'CAMINHO/ARQUIVO.mbz',
        'token' => $createdtoken,
    ]);

    echo $OUTPUT->notification(
        html_writer::tag('strong', get_string('transfer_token_created_once', 'local_backupftp')) .
        html_writer::tag('pre', s($createdtoken), ['style' => 'white-space:pre-wrap;']) .
        html_writer::tag('p', get_string('transfer_token_created_once_desc', 'local_backupftp')) .
        html_writer::tag('p', html_writer::link($apiurl, s($apiurl->out(false)), ['target' => '_blank'])) .
        html_writer::tag('p', s($downloadexample->out(false))),
        'notifysuccess'
    );
}

echo html_writer::tag('p', get_string('transfer_tokens_desc', 'local_backupftp', format_time(token::get_lifetime())));

echo html_writer::start_tag('form', ['method' => 'post', 'action' => $PAGE->url->out(false), 'class' => 'mb-4']);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'createtoken', 'value' => 1]);
echo html_writer::tag('label', get_string('transfer_token_name', 'local_backupftp'), ['for' => 'id_name']);
echo html_writer::empty_tag('input', [
    'type' => 'text',
    'name' => 'name',
    'id' => 'id_name',
    'class' => 'form-control',
    'maxlength' => 255,
]);
echo html_writer::empty_tag('br');
echo html_writer::empty_tag('input', [
    'type' => 'submit',
    'class' => 'btn btn-primary',
    'value' => get_string('transfer_token_create', 'local_backupftp'),
]);
echo html_writer::end_tag('form');

$records = $DB->get_records(token::TABLE, null, 'timecreated DESC');

$table = new html_table();
$table->head = [
    get_string('name'),
    get_string('status', 'local_backupftp'),
    get_string('created_at', 'local_backupftp'),
    get_string('transfer_token_expires', 'local_backupftp'),
    get_string('transfer_token_lastused', 'local_backupftp'),
    get_string('transfer_token_uses', 'local_backupftp'),
    get_string('actions'),
];
$table->data = [];

foreach ($records as $record) {
    $expired = ((int)$record->timeexpires < time());
    $revoked = !empty($record->revoked);
    if ($revoked) {
        $status = get_string('transfer_token_status_revoked', 'local_backupftp');
    } else if ($expired) {
        $status = get_string('transfer_token_status_expired', 'local_backupftp');
    } else {
        $status = get_string('transfer_token_status_active', 'local_backupftp');
    }

    $actions = '-';
    if (!$revoked && !$expired) {
        $url = new moodle_url('/local/backupftp/tokens.php', [
            'action' => 'revoke',
            'id' => $record->id,
            'sesskey' => sesskey(),
        ]);
        $actions = html_writer::link($url, get_string('transfer_token_revoke', 'local_backupftp'), [
            'class' => 'btn btn-danger btn-sm',
            'onclick' => 'return confirm(' . json_encode(get_string('transfer_token_revoke_confirm', 'local_backupftp')) . ');',
        ]);
    }

    $table->data[] = [
        s($record->name),
        $status,
        userdate((int)$record->timecreated, get_string('strftimedatetimeshort', 'langconfig')),
        userdate((int)$record->timeexpires, get_string('strftimedatetimeshort', 'langconfig')),
        empty($record->lastused) ? '-' : userdate((int)$record->lastused, get_string('strftimedatetimeshort', 'langconfig')),
        (int)$record->downloadcount,
        $actions,
    ];
}

echo html_writer::table($table);

echo html_writer::tag('h3', get_string('transfer_api_examples', 'local_backupftp'));
echo html_writer::tag('pre', s("GET /local/backupftp/api.php?action=courses&token=TOKEN\n" .
    "GET /local/backupftp/api.php?action=course&id=2&token=TOKEN\n" .
    "GET /local/backupftp/api.php?action=categories&token=TOKEN\n" .
    "GET /local/backupftp/api.php?action=category&id=1&token=TOKEN\n" .
    "GET /local/backupftp/api.php?action=backups&token=TOKEN\n" .
    "GET /local/backupftp/download.php?f=RELATIVE/PATH/backup.mbz&token=TOKEN"));

echo $OUTPUT->footer();
