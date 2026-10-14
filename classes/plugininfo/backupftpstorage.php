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
 * Moodle discovery class for Backup FTP storage subplugins.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\plugininfo;

/**
 * Storage subplugins are bundled with the parent and managed as destinations.
 */
class backupftpstorage extends \core\plugininfo\base {
    /**
     * Prevent removal of bundled providers while configured instances exist.
     *
     * @return bool
     */
    public function is_uninstall_allowed() {
        global $DB;
        if (!$DB->get_manager()->table_exists(new \xmldb_table('local_backupftp_storage'))) {
            return false;
        }
        return !$DB->record_exists('local_backupftp_storage', ['type' => $this->name]);
    }
}
