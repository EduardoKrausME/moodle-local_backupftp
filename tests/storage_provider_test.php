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
 * PHPUnit tests for configured backup storage providers.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp;

use local_backupftp\storage\manager;

/**
 * Storage provider discovery, configuration, and local file transfer.
 *
 * @covers \local_backupftp\storage\manager
 * @covers \backupftpstorage_local\provider
 * @covers \backupftpstorage_ftp\provider
 */
final class storage_provider_test extends \advanced_testcase {
    /**
     * Verify bundled storage types are registered with Moodle.
     *
     * @return void
     */
    public function test_bundled_storage_types(): void {
        $types = manager::types();
        $this->assertArrayHasKey('local', $types);
        $this->assertArrayHasKey('ftp', $types);
    }

    /**
     * Verify a local destination can be written, listed, and restored.
     *
     * @return void
     */
    public function test_local_storage_roundtrip(): void {
        global $CFG;

        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $path = make_temp_directory('local_backupftp_test_' . uniqid());
        $id = manager::save('local', 'My backups', true, [
            'path' => $path, 'names' => 0, 'organize' => 0,
        ]);
        $provider = manager::get($id);
        $this->assertSame($path, $provider->root());

        $source = $path . '/original.mbz';
        file_put_contents($source, 'backup MBZ test payload larger than ten bytes');
        $logs = [];
        $this->assertTrue($provider->store($source, 'course.mbz', (int) $course->id, $logs));
        $this->assertTrue($provider->owns($path . '/course.mbz'));
        $files = $provider->list_files();
        $this->assertNotEmpty($files);

        $target = $path . '/restored.mbz';
        $this->assertTrue($provider->fetch($path . '/course.mbz', $target, $logs));
        $this->assertSame(file_get_contents($source), file_get_contents($target));
        $this->assertFalse($provider->owns('/etc/passwd'));
    }

    /**
     * Reject FTP credentials embedded in a URL.
     *
     * @return void
     */
    public function test_ftp_host_validation(): void {
        $this->expectException(\invalid_parameter_exception::class);
        \backupftpstorage_ftp\provider::normalize([
            'url' => 'ftp://user:secret@localhost',
        ]);
    }
}
