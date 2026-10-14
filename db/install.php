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
 * install file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Function xmldb_local_backupftp_install
 *
 * @return bool
 */
function xmldb_local_backupftp_install() {
    global $DB, $CFG;

    // New installations always begin with one enabled local storage destination.
    $DB->insert_record('local_backupftp_storage', (object) [
        'type' => 'local',
        'name' => 'Local storage',
        'enabled' => 1,
        'config' => json_encode([
            'path' => $CFG->dataroot . '/backup',
            'organize' => 1,
            'names' => 1,
        ]),
        'timecreated' => time(),
        'timemodified' => time(),
    ]);
    return true;
}
