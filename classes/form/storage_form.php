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
 * Form for provider-specific backup storage settings.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\form;

defined('MOODLE_INTERNAL') || die;

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

/**
 * Storage instance configuration delegated to the selected subplugin.
 */
class storage_form extends \moodleform {
    /**
     * Build the destination configuration form.
     *
     * @return void
     */
    protected function definition(): void {
        $mform = $this->_form;
        $type = $this->_customdata['type'];
        $types = \local_backupftp\storage\manager::types();
        if (!isset($types[$type])) {
            throw new \invalid_parameter_exception('Unknown storage type');
        }
        $mform->addElement('header', 'storageheader', $types[$type]);
        $mform->addElement('text', 'name', get_string('storage_name', 'local_backupftp'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addElement('advcheckbox', 'enabled', get_string('storage_enabled', 'local_backupftp'));
        $class = '\\backupftpstorage_' . $type . '\\provider';
        $class::add_fields($mform);
        $mform->addElement('hidden', 'id', $this->_customdata['id']);
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden', 'type', $type);
        $mform->setType('type', PARAM_ALPHANUMEXT);
        $this->add_action_buttons();
    }
}
