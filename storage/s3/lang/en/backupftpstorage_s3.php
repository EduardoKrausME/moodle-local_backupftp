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
 * S3 backup storage.
 *
 * @package backupftpstorage_s3
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Amazon S3 / DigitalOcean Spaces';
$string['service'] = 'Storage service';
$string['aws'] = 'Amazon S3';
$string['space'] = 'DigitalOcean Spaces';
$string['region'] = 'Region (e.g. us-east-1 or nyc1)';
$string['bucket'] = 'Bucket / Space name';
$string['accesskey'] = 'Access key ID';
$string['password'] = 'Secret access key';
$string['folder'] = 'Backup prefix';
$string['invalidconfig'] = 'Invalid S3 destination configuration.';
$string['invalidfolder'] = 'Invalid S3 backup prefix.';
$string['extensionrequired'] = 'The PHP cURL and SimpleXML extensions are required for S3 storage.';
$string['transferfailed'] = 'The S3 backup transfer failed.';
$string['httperror'] = 'The S3 request failed (HTTP {$a}).';
