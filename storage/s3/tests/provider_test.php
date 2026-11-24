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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * S3 backup storage.
 *
 * @package backupftpstorage_s3
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace backupftpstorage_s3;

/**
 * Configuration and object ownership tests.
 *
 * @covers \backupftpstorage_s3\provider
 */
final class provider_test extends \advanced_testcase {
    /**
     * Verify defaults, prefix boundaries and invalid paths.
     */
    public function test_config_and_ownership(): void {
        foreach (['aws' => 'us-east-1', 'space' => 'nyc1'] as $service => $region) {
            $config = provider::normalize(['service' => $service, 'region' => $region,
                'bucket' => 'test-backups', 'accesskey' => 'example', 'password' => 'secret', 'folder' => '/backup/']);
            $provider = new provider((object)['config' => json_encode($config)]);
            $this->assertSame('backup', $provider->root());
            $this->assertTrue($provider->owns('backup/course/file.mbz'));
            $this->assertFalse($provider->owns('backup-other/file.mbz'));
            $this->assertFalse($provider->owns('backup/../file.mbz'));
            $this->assertFalse($provider->owns('backup/file.txt'));
        }
    }

    /**
     * Reject invalid region strings before building a hostname.
     */
    public function test_invalid_region(): void {
        $this->expectException(\invalid_parameter_exception::class);
        provider::normalize(['region' => 'example.com/path', 'bucket' => 'test-backups',
            'accesskey' => 'example', 'password' => 'secret']);
    }
}
