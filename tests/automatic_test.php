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
 * Tests for automatic backup selection and manifest identification.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp;

/**
 * Automatic backup transfer tests.
 *
 * @covers \local_backupftp\automatic
 */
class automatic_test extends \advanced_testcase {
    /**
     * One destination is implicit; multiple destinations require explicit IDs.
     */
    public function test_destination_selection(): void {
        $a = (object)['id' => 1];
        $b = (object)['id' => 2];
        $this->assertSame([1 => $a], automatic::selected([], [1 => $a]));
        $this->assertSame([], automatic::selected([], [1 => $a, 2 => $b]));
        $this->assertSame([2 => $b], automatic::selected([2, 999], [1 => $a, 2 => $b]));
    }

    /**
     * The filename cannot collide between different jobs or storage instances.
     */
    public function test_filename(): void {
        $job = (object)['courseid' => 42, 'backupid' => str_repeat('a', 32)];
        $this->assertSame('backup-moodle2-course-42-auto-' . str_repeat('a', 32) . '-7.mbz',
            automatic::filename($job, 7));
        $this->assertNotSame(automatic::filename($job, 7), automatic::filename($job, 8));
    }

    /**
     * A different backup ID or course must never be captured.
     */
    public function test_manifest_identity(): void {
        $dir = make_request_directory();
        $id = str_repeat('b', 32);
        $manifest = '<moodle_backup><information><original_course_id>42</original_course_id><details>' .
            '<detail backup_id="' . $id . '"/></details></information></moodle_backup>';
        file_put_contents($dir . '/source.xml', $manifest);
        $archive = $dir . '/source.mbz';
        $packer = get_file_packer('application/vnd.moodle.backup');
        $this->assertTrue($packer->archive_to_pathname(['moodle_backup.xml' => $dir . '/source.xml'], $archive));
        $this->assertTrue(automatic::matches($archive, $dir, $id, 42));
        $this->assertFalse(automatic::matches($archive, $dir, str_repeat('c', 32), 42));
        $this->assertFalse(automatic::matches($archive, $dir, $id, 43));
    }

    /**
     * Local automatic copies do not overwrite manual names and can be removed independently.
     */
    public function test_local_automatic_retention(): void {
        $this->resetAfterTest();
        $root = make_request_directory();
        $provider = new \backupftpstorage_local\provider((object)[
            'id' => 1, 'name' => 'Test', 'type' => 'local',
            'config' => json_encode(['path' => $root, 'names' => 1, 'organize' => 1]),
        ]);
        $source = $root . '/manual.mbz';
        file_put_contents($source, str_repeat('a', 100));
        $job = (object)['courseid' => 42, 'backupid' => str_repeat('c', 32)];
        $logs = [];
        $path = $provider->store_automatic($source, automatic::filename($job, 1), 42, $logs);
        $this->assertFileExists($path);
        $this->assertNotSame($source, $path);
        $this->assertTrue($provider->delete_automatic($path));
        $this->assertFileDoesNotExist($path);
        $this->assertFileExists($source);
    }

}
