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
    'retention' => (int)$settings['copies'] > 0 ? 2 : ((int)$settings['days'] > 0 ? 1 : 0),
    'days' => (int)$settings['days'] ?: 30,
    'copies' => (int)$settings['copies'] ?: 7,
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
        'days' => (int)$data->retention === 1 ? (int)$data->days : 0,
        'copies' => (int)$data->retention === 2 ? (int)$data->copies : 0,
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
$table->head = [get_string('course'), get_string('created_at', 'local_backupftp'), get_string('filename'),
    get_string('size'), get_string('storage_systems', 'local_backupftp'), get_string('status', 'local_backupftp')];
$jobs = $DB->get_records(automatic::TABLE, [], 'id DESC', '*', 0, 50);
$courses = $jobs ? $DB->get_records_list('course', 'id', array_unique(array_column($jobs, 'courseid')), '', 'id,fullname') : [];
$allstorages = \local_backupftp\storage\manager::instances();
foreach ($jobs as $job) {
    $destinations = json_decode($job->destinations, true);
    $names = [];
    $size = 0;
    $details = [];
    foreach ($destinations as $destination) {
        $id = (int)$destination['id'];
        $name = $allstorages[$id]->name ?? ('#' . $id);
        $status = !empty($destination['deleted']) ? 'expired' :
            (!empty($destination['path']) ? 'complete' : ($job->status === 'capturefailed' ? 'capturefailed' : 'pending'));
        $details[] = s($name) . ': ' . get_string('automatic_status_' . $status, 'local_backupftp');
        $filename = $destination['filename'] ?? (!empty($destination['path']) ? basename($destination['path']) : '');
        if ($filename !== '') {
            $names[] = s($filename);
        }
        $size = max($size, (int)($destination['size'] ?? 0));
    }
    $course = isset($courses[$job->courseid]) ? s($courses[$job->courseid]->fullname) : ('#' . (int)$job->courseid);
    $table->data[] = [$course, userdate($job->timecreated), implode(html_writer::empty_tag('br'), array_unique($names)),
        $size ? display_size($size) : '-', implode(html_writer::empty_tag('br'), $details),
        get_string('automatic_status_' . $job->status, 'local_backupftp')];
}
if ($table->data) {
    echo html_writer::table($table);
} else {
    echo html_writer::tag('p', get_string('automatic_nohistory', 'local_backupftp'));
}
echo $OUTPUT->footer();
