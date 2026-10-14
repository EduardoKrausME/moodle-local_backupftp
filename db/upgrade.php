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

    if ($oldversion < 2026101402) {
        $storage = new xmldb_table('local_backupftp_storage');
        if (!$dbman->table_exists($storage)) {
            $storage->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
            $storage->add_field('type', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL);
            $storage->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);
            $storage->add_field('enabled', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
            $storage->add_field('config', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL);
            $storage->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
            $storage->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
            $storage->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $storage->add_index('type_ix', XMLDB_INDEX_NOTUNIQUE, ['type']);
            $dbman->create_table($storage);
        }

        $course = new xmldb_table('local_backupftp_course');
        $coursefield = new xmldb_field('storageids', XMLDB_TYPE_TEXT);
        if (!$dbman->field_exists($course, $coursefield)) {
            $dbman->add_field($course, $coursefield);
        }

        $restore = new xmldb_table('local_backupftp_restore');
        $restorefield = new xmldb_field('storageid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'source');
        if (!$dbman->field_exists($restore, $restorefield)) {
            $dbman->add_field($restore, $restorefield);
        }

        // Upgrade is idempotent and does not overwrite instances created by administrators.
        $time = time();
        $oldorganize = get_config('local_backupftp', 'ftporganize');
        $oldnames = get_config('local_backupftp', 'ftpnames');
        // Unset flags historically defaulted to enabled in the administration form.
        $organize = $oldorganize === false ? 1 : (int) (bool) $oldorganize;
        $names = $oldnames === false ? 1 : (int) (bool) $oldnames;
        if (get_config('local_backupftp', 'localfileenable') &&
                !$DB->record_exists('local_backupftp_storage', ['type' => 'local'])) {
            $path = trim((string) get_config('local_backupftp', 'localfilepath'));
            if ($path === '') {
                global $CFG;
                $path = $CFG->dataroot . '/backup';
            }
            $DB->insert_record('local_backupftp_storage', (object) [
                'type' => 'local', 'name' => 'Local storage', 'enabled' => 1,
                'config' => json_encode(['path' => $path, 'organize' => $organize, 'names' => $names]),
                'timecreated' => $time, 'timemodified' => $time,
            ]);
        }

        $ftpurl = trim((string) get_config('local_backupftp', 'ftpurl'));
        if (get_config('local_backupftp', 'ftpenable') && $ftpurl !== '' &&
                !$DB->record_exists('local_backupftp_storage', ['type' => 'ftp'])) {
            $DB->insert_record('local_backupftp_storage', (object) [
                'type' => 'ftp', 'name' => 'FTP', 'enabled' => 1,
                'config' => json_encode([
                    'url' => $ftpurl,
                    'username' => (string) get_config('local_backupftp', 'ftpusername'),
                    'password' => (string) get_config('local_backupftp', 'ftppassword'),
                    'folder' => (string) get_config('local_backupftp', 'ftppasta'),
                    'passive' => (int) (bool) get_config('local_backupftp', 'ftppasv'),
                    'organize' => $organize, 'names' => $names,
                ]),
                'timecreated' => $time, 'timemodified' => $time,
            ]);
        }

        upgrade_plugin_savepoint(true, 2026101402, 'local', 'backupftp');
    }

    return true;
}
