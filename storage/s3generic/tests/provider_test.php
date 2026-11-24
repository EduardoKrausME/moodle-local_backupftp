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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Generic S3 backup storage.
 *
 * @package backupftpstorage_s3generic
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace backupftpstorage_s3generic;

use backupftpstorage_s3\client;

/**
 * Test S3-compatible configuration and the shared endpoint resolver.
 *
 * @covers \backupftpstorage_s3generic\provider
 * @covers \backupftpstorage_s3\client
 */
class provider_test extends \advanced_testcase {
    /**
     * Verify configuration and inherited prefix ownership.
     */
    public function test_normalize(): void {
        $config = provider::normalize(['endpoint' => 'storage.example.org:9000/', 'region' => 'us-east-1',
            'bucket' => 'test-backups', 'accesskey' => 'example', 'password' => 'secret', 'folder' => '/backup/',
            'urlstyle' => 'path-style']);
        $this->assertSame('https://storage.example.org:9000', $config['endpoint']);
        $this->assertArrayNotHasKey('service', $config);
        $destination = new provider((object)['config' => json_encode($config)]);
        $this->assertTrue($destination->owns('backup/course/file.mbz'));
        $this->assertFalse($destination->owns('backup-other/file.mbz'));
    }

    /**
     * Verify both URL styles, custom ports and automatic addressing.
     */
    public function test_endpoint_resolution(): void {
        $this->assertSame([
            'endpoint' => 'https://s3.example.org',
            'host' => 'backups.s3.example.org',
            'basepath' => '',
        ], client::resolve_endpoint('https://s3.example.org/', 'backups', 'virtual-hosted'));
        $this->assertSame([
            'endpoint' => 'https://minio.example.org:9000',
            'host' => 'minio.example.org:9000',
            'basepath' => '/backups',
        ], client::resolve_endpoint('minio.example.org:9000', 'backups', 'path-style'));
        $this->assertSame('/my.backups', client::resolve_endpoint('s3.example.org', 'my.backups', 'auto')['basepath']);
        $this->assertSame('/backups', client::resolve_endpoint('192.0.2.10:9000', 'backups', 'auto')['basepath']);
    }

    /**
     * Reject invalid or ambiguous endpoint URLs.
     */
    public function test_invalid_endpoints(): void {
        foreach (['http://example.org', 'https://user:secret@example.org', 'https://example.org/path',
                'https://example.org?x=1', 'https://example.org/#test', 'https://example.org:0',
                "https://example.org\r\n", 'https://127.1'] as $endpoint) {
            // Outer whitespace is normalized; embedded control characters are not.
            if ($endpoint === "https://example.org\r\n") {
                $endpoint = "https://example.org\r\n/injected";
            }
            try {
                client::resolve_endpoint($endpoint, 'backups', 'auto');
                $this->fail('Accepted invalid endpoint');
            } catch (\invalid_parameter_exception $e) {
                $this->assertInstanceOf(\invalid_parameter_exception::class, $e);
            }
        }
    }

    /**
     * Virtual-hosted addressing cannot use an IP endpoint.
     */
    public function test_virtual_hosted_ip(): void {
        $this->expectException(\invalid_parameter_exception::class);
        client::resolve_endpoint('192.0.2.10', 'backups', 'virtual-hosted');
    }
}
