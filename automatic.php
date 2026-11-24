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
 * Configure native automated backup transfers.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

use local_backupftp\automatic;
use local_backupftp\form\automatic_form;

$context = context_system::instance();
require_login();
require_capability('local/backupftp:manage', $context);
$url = new moodle_url('/local/backupftp/automatic.php');
$home = new moodle_url('/local/backupftp/');
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('automatic_title', 'local_backupftp'));
$PAGE->set_heading(get_string('automatic_title', 'local_backupftp'));

$storages = automatic::storages();
$settings = automatic::settings();
$form = new automatic_form($url, ['storages' => $storages]);
$form->set_data([
    'enabled' => $settings['enabled'],
    'storageids' => array_keys(automatic::selected((array)$settings['storageids'], $storages)),
    'retention' => (int)$settings['days'] > 0 ? 1 : 0,
    'days' => (int)$settings['days'] ?: 30,
]);
if ($form->is_cancelled()) {
    redirect($home);
}
if ($data = $form->get_data()) {
    require_sesskey();
    $ids = array_keys(automatic::selected((array)($data->storageids ?? []), automatic::storages()));
    if (!empty($data->enabled) && !$ids) {
        redirect($url, get_string('storage_select', 'local_backupftp'), null, \core\output\notification::NOTIFY_ERROR);
    }
    set_config('automaticsettings', json_encode([
        'enabled' => !empty($data->enabled),
        'storageids' => $ids,
        'days' => !empty($data->retention) ? (int)$data->days : 0,
    ]), 'local_backupftp');
    redirect($url, get_string('changessaved'));
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_backupftp/page_navigation', ['backurl' => $home]);
echo $OUTPUT->notification(get_string('automatic_intro', 'local_backupftp'), 'info');
echo html_writer::tag('p', get_string('automatic_local_retention', 'local_backupftp'));
echo html_writer::link(new moodle_url('/admin/settings.php', ['section' => 'automated']),
    get_string('automatic_native_settings', 'local_backupftp'), ['class' => 'btn btn-outline-secondary mb-3']);
echo ' ';
echo html_writer::link(new moodle_url('/local/backupftp/storage.php'), get_string('storage_systems', 'local_backupftp'),
    ['class' => 'btn btn-outline-secondary mb-3']);
$form->display();

echo $OUTPUT->heading(get_string('automatic_history', 'local_backupftp'), 3);
$table = new html_table();
$table->head = [get_string('course'), get_string('created_at', 'local_backupftp'),
    get_string('storage_systems', 'local_backupftp'), get_string('status', 'local_backupftp')];
foreach ($DB->get_records(automatic::TABLE, [], 'id DESC', '*', 0, 50) as $job) {
    $destinations = json_decode($job->destinations, true);
    $total = count($destinations);
    $done = count(array_filter($destinations, static function($destination) {
        return $destination['path'] !== '';
    }));
    $table->data[] = [(int)$job->courseid, userdate($job->timecreated), $done . ' / ' . $total,
        get_string('automatic_status_' . $job->status, 'local_backupftp')];
}
if ($table->data) {
    echo html_writer::table($table);
} else {
    echo html_writer::tag('p', get_string('automatic_nohistory', 'local_backupftp'));
}
echo $OUTPUT->footer();
