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
 * Automatic backup settings form.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\form;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Configure destination selection and retention.
 */
class automatic_form extends \moodleform {
    /**
     * Define settings.
     */
    public function definition() {
        $mform = $this->_form;
        $component = 'local_backupftp';
        $storages = $this->_customdata['storages'];
        $mform->addElement('advcheckbox', 'enabled', get_string('automatic_enabled', $component));
        if (count($storages) === 1) {
            $record = reset($storages);
            $mform->addElement('static', 'onlystorage', get_string('storage_systems', $component), s($record->name));
        } else if (!$storages) {
            $mform->addElement('static', 'nostorage', get_string('storage_systems', $component),
                get_string('automatic_nostorage', $component));
        } else {
            $options = [];
            foreach ($storages as $record) {
                $options[$record->id] = format_string($record->name);
            }
            $mform->addElement('select', 'storageids', get_string('storage_systems', $component), $options,
                ['multiple' => 'multiple', 'size' => min(8, count($options))]);
        }
        $mform->addElement('select', 'retention', get_string('automatic_retention', $component), [
            0 => get_string('automatic_never', $component),
            1 => get_string('automatic_delete_after', $component),
        ]);
        $mform->addElement('text', 'days', get_string('automatic_days', $component));
        $mform->setType('days', PARAM_INT);
        $mform->hideIf('days', 'retention', 'eq', 0);
        $this->add_action_buttons();
    }

    /**
     * Validate settings on the server, including active storage selection.
     *
     * @param array $data Submitted data.
     * @param array $files Submitted files.
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $storages = \local_backupftp\automatic::storages();
        if (!empty($data['enabled']) &&
                !\local_backupftp\automatic::selected((array)($data['storageids'] ?? []), $storages)) {
            $errors['enabled'] = get_string('storage_select', 'local_backupftp');
        }
        if (!empty($data['retention']) && ((int)($data['days'] ?? 0) < 1 || (int)$data['days'] > 36500)) {
            $errors['days'] = get_string('automatic_invaliddays', 'local_backupftp');
        }
        return $errors;
    }
}
