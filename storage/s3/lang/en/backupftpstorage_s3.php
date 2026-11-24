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

$string['accesskey'] = 'Access key ID';
$string['aws'] = 'Amazon S3';
$string['bucket'] = 'Bucket / Space name';
$string['extensionrequired'] = 'The PHP cURL and SimpleXML extensions are required for S3 storage.';
$string['folder'] = 'Backup prefix';
$string['httperror'] = 'The S3 request failed (HTTP {$a}).';
$string['invalidconfig'] = 'Invalid S3 destination configuration.';
$string['invalidfolder'] = 'Invalid S3 backup prefix.';
$string['password'] = 'Secret access key';
$string['pluginname'] = 'Amazon S3 / DigitalOcean Spaces';
$string['region'] = 'Region (e.g. us-east-1 or nyc1)';
$string['service'] = 'Storage service';
$string['space'] = 'DigitalOcean Spaces';
$string['transferfailed'] = 'The S3 backup transfer failed.';
