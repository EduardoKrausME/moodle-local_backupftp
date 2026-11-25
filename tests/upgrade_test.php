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
 * Regression tests for restore-source schema upgrades.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp;

/**
 * Restore schema migration regression tests.
 *
 * @covers ::local_backupftp_upgrade_restore_source_fields
 */
final class upgrade_test extends \advanced_testcase {
    /**
     * An untouched legacy restore table can be upgraded.
     */
    public function test_legacy_restore_source_schema_upgrade(): void {
        $this->check_restore_source_schema_upgrade(false);
    }

    /**
     * A partially upgraded restore table can be repaired.
     */
    public function test_partial_restore_source_schema_upgrade(): void {
        $this->check_restore_source_schema_upgrade(true);
    }

    /**
     * Exercise the shared migration implementation against legacy records.
     *
     * @param bool $partial Whether the historical migration was interrupted.
     */
    private function check_restore_source_schema_upgrade(bool $partial): void {
        global $DB, $CFG;
        $this->resetAfterTest();
        require_once($CFG->dirroot . '/local/backupftp/db/upgrade.php');

        $dbman = $DB->get_manager();
        $table = new \xmldb_table('local_backupftp_upgrade_test');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('remotefile', XMLDB_TYPE_TEXT);
        $table->add_field('status', XMLDB_TYPE_CHAR, '20');
        $table->add_field('logs', XMLDB_TYPE_TEXT);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '20');
        $table->add_field('timestart', XMLDB_TYPE_INTEGER, '20');
        $table->add_field('timeend', XMLDB_TYPE_INTEGER, '20');
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $dbman->create_table($table);

        try {
            $id = $DB->insert_record($table->getName(), (object)[
                'remotefile' => 'legacy.mbz', 'status' => 'pending', 'logs' => 'Existing log',
            ]);
            $size = new \xmldb_field('sourcefilesize');
            $modified = new \xmldb_field('sourcetimemodified');
            $index = new \xmldb_index('source_ix', XMLDB_INDEX_NOTUNIQUE, ['source']);
            $this->assertFalse($dbman->field_exists($table, $size));
            $this->assertFalse($dbman->field_exists($table, $modified));

            if ($partial) {
                // Reproduce a partial historical upgrade: the first four fields already exist.
                $dbman->add_field($table, new \xmldb_field(
                    'source', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, 'configured', 'remotefile'));
                $dbman->add_field($table, new \xmldb_field(
                    'sourcewwwroot', XMLDB_TYPE_TEXT, null, null, null, null, null, 'source'));
                $dbman->add_field($table, new \xmldb_field(
                    'sourceip', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'sourcewwwroot'));
                $dbman->add_field($table, new \xmldb_field(
                    'sourcetoken', XMLDB_TYPE_TEXT, null, null, null, null, null, 'sourceip'));
            }

            local_backupftp_upgrade_restore_source_fields($dbman, $table);
            $this->assertTrue($dbman->field_exists($table, $size));
            $this->assertTrue($dbman->field_exists($table, $modified));
            $this->assertTrue($dbman->index_exists($table, $index));
            $record = $DB->get_record($table->getName(), ['id' => $id], '*', MUST_EXIST);
            $this->assertSame('legacy.mbz', $record->remotefile);
            $this->assertSame('Existing log', $record->logs);
            $this->assertSame('pending', $record->status);
            $this->assertSame('configured', $record->source);
            $this->assertSame(0, (int)$record->sourcefilesize);
            $this->assertSame(0, (int)$record->sourcetimemodified);

            // A site that passed the old savepoint can have the timestamp but not the file size.
            $DB->set_field($table->getName(), 'sourcetimemodified', 1720000000, ['id' => $id]);
            $dbman->drop_field($table, $size);
            local_backupftp_upgrade_restore_source_fields($dbman, $table);
            $record = $DB->get_record($table->getName(), ['id' => $id], '*', MUST_EXIST);
            $this->assertSame(0, (int)$record->sourcefilesize);
            $this->assertSame(1720000000, (int)$record->sourcetimemodified);

            // Repeated upgrades must preserve an existing size larger than 32 bits.
            $DB->set_field($table->getName(), 'sourcefilesize', 5368709120, ['id' => $id]);
            local_backupftp_upgrade_restore_source_fields($dbman, $table);
            $record = $DB->get_record($table->getName(), ['id' => $id], '*', MUST_EXIST);
            $this->assertSame(5368709120, (int)$record->sourcefilesize);
            $this->assertSame(1720000000, (int)$record->sourcetimemodified);
            $this->assertSame('Existing log', $record->logs);
        } finally {
            $dbman->drop_table($table);
        }
    }

    /**
     * A new installation already contains the required fields and index.
     */
    public function test_fresh_install_restore_schema(): void {
        global $DB;
        $dbman = $DB->get_manager();
        $table = new \xmldb_table('local_backupftp_restore');
        foreach (['source', 'sourcewwwroot', 'sourceip', 'sourcetoken', 'sourcefilesize', 'sourcetimemodified'] as $name) {
            $this->assertTrue($dbman->field_exists($table, new \xmldb_field($name)));
        }
        $this->assertTrue($dbman->index_exists($table, new \xmldb_index('source_ix', XMLDB_INDEX_NOTUNIQUE, ['source'])));
    }
}
