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
 * Upgrade file.
 *
 * @package    local_backupftp
 * @copyright  2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade local_backupftp database schema.
 *
 * @param int $oldversion Old plugin version.
 * @return bool
 * @throws \ddl_change_structure_exception
 * @throws \ddl_exception
 * @throws \ddl_table_missing_exception
 * @throws \downgrade_exception
 * @throws \moodle_exception
 * @throws \upgrade_exception
 */
function xmldb_local_backupftp_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026062600) {
        $table = new xmldb_table('local_backupftp_token');

        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
            $table->add_field('tokenhash', XMLDB_TYPE_CHAR, '64', null, XMLDB_NOTNULL, null, null);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table->add_field('timeexpires', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table->add_field('lastused', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('downloadcount', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('revoked', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');

            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table->add_index('tokenhash_uix', XMLDB_INDEX_UNIQUE, ['tokenhash']);
            $table->add_index('timeexpires_ix', XMLDB_INDEX_NOTUNIQUE, ['timeexpires']);

            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2026062600, 'local', 'backupftp');
    }

    if ($oldversion < 2026062900) {
        $table = new xmldb_table('local_backupftp_restore');

        $fields = [
            new xmldb_field('source', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, 'configured', 'remotefile'),
            new xmldb_field('sourcewwwroot', XMLDB_TYPE_TEXT, null, null, null, null, null, 'source'),
            new xmldb_field('sourceip', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'sourcewwwroot'),
            new xmldb_field('sourcetoken', XMLDB_TYPE_TEXT, null, null, null, null, null, 'sourceip'),
            new xmldb_field('sourcetimemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'sourcefilesize'),
        ];

        foreach ($fields as $field) {
            if (!$dbman->field_exists($table, $field)) {
                $dbman->add_field($table, $field);
            }
        }

        $index = new xmldb_index('source_ix', XMLDB_INDEX_NOTUNIQUE, ['source']);
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        upgrade_plugin_savepoint(true, 2026062900, 'local', 'backupftp');
    }

    return true;
}
