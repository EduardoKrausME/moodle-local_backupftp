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
 * Configure backup storage destinations.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

use local_backupftp\form\storage_form;
use local_backupftp\storage\manager;

require_login();
require_capability('local/backupftp:manage', context_system::instance());

$id = optional_param('id', 0, PARAM_INT);
$type = optional_param('type', '', PARAM_ALPHANUMEXT);
$record = $id ? $DB->get_record('local_backupftp_storage', ['id' => $id], '*', MUST_EXIST) : null;
if ($record) {
    $type = $record->type;
}
$types = manager::types();
if ($type !== '' && !array_key_exists($type, $types)) {
    throw new moodle_exception('storage_type_missing', 'local_backupftp', '', $type);
}

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/backupftp/storage.php', ['id' => $id, 'type' => $type]));
$PAGE->set_pagelayout('admin');
$PAGE->set_heading(get_string('storage_systems', 'local_backupftp'));
$PAGE->set_title(get_string('storage_systems', 'local_backupftp'));

$form = null;
if ($type !== '') {
    $form = new storage_form(null, ['id' => $id, 'type' => $type]);
    if ($record) {
        $config = json_decode($record->config, true) ?: [];
        // Credentials are never sent back to the browser when editing an existing destination.
        unset($config['password']);
        $form->set_data((object) array_merge($config, [
            'id' => $id, 'type' => $type, 'name' => $record->name, 'enabled' => $record->enabled,
        ]));
    } else {
        $class = '\\backupftpstorage_' . $type . '\\provider';
        $defaults = $class::defaults();
        unset($defaults['password']);
        $form->set_data((object) array_merge($defaults, [
            'id' => 0, 'type' => $type, 'name' => $types[$type], 'enabled' => 1,
        ]));
    }
    if ($form->is_cancelled()) {
        redirect(new moodle_url('/local/backupftp/storage.php'));
    }
    if ($data = $form->get_data()) {
        // Whitelist the fields known to the selected provider, never save arbitrary POST keys.
        $class = '\\backupftpstorage_' . $type . '\\provider';
        $allowed = array_keys($class::defaults());
        $settings = [];
        foreach ($allowed as $key) {
            if (property_exists($data, $key)) {
                $settings[$key] = $data->$key;
            } else if (in_array($key, ['organize', 'names', 'passive'], true)) {
                $settings[$key] = 0;
            }
        }
        manager::save($type, $data->name, !empty($data->enabled), $settings, $id);
        redirect(new moodle_url('/local/backupftp/storage.php'),
            get_string('storage_configured', 'local_backupftp'));
    }
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_backupftp/page_navigation', [
    'backurl' => new moodle_url('/local/backupftp/'),
]);

if ($form) {
    $form->display();
} else {
    echo html_writer::tag('h3', get_string('storage_instances', 'local_backupftp'));
    $instances = manager::instances();
    if (!$instances) {
        echo $OUTPUT->notification(get_string('storage_none', 'local_backupftp'), 'info');
    }
    foreach ($instances as $instance) {
        $typecaption = isset($types[$instance->type]) ? $types[$instance->type] : $instance->type;
        $label = s($instance->name) . ' (' . s($typecaption) . ')';
        $label .= $instance->enabled ? ' - ' . get_string('storage_enabled', 'local_backupftp') : '';
        echo html_writer::div(
            html_writer::span($label, 'mr-3') .
            html_writer::link(new moodle_url('/local/backupftp/storage.php', ['id' => $instance->id]),
                get_string('storage_edit', 'local_backupftp'), ['class' => 'btn btn-outline-primary btn-sm']),
            'card card-body mb-2'
        );
    }

    echo html_writer::tag('h3', get_string('storage_choose', 'local_backupftp'));
    foreach ($types as $key => $label) {
        echo html_writer::div(html_writer::link(
            new moodle_url('/local/backupftp/storage.php', ['type' => $key]),
            s($label), ['class' => 'btn btn-primary']),
            'mb-2');
    }
}
echo $OUTPUT->footer();
