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

$string['pluginname'] = 'S3 compatible (generic)';
$string['endpoint'] = 'S3 endpoint (HTTPS)';
$string['region'] = 'Signing region';
$string['bucket'] = 'Bucket name';
$string['accesskey'] = 'Access key ID';
$string['password'] = 'Secret access key';
$string['folder'] = 'Backup prefix';
$string['urlstyle'] = 'Bucket URL style';
$string['styleauto'] = 'Automatic';
$string['stylepath'] = 'Path-style (endpoint/bucket)';
$string['stylevirtual'] = 'Virtual-hosted (bucket.endpoint)';
$string['urlstyle_help'] = 'Use the addressing style required by your provider. Automatic uses path-style for IP addresses, single-label hosts and dotted bucket names, and virtual-hosted style otherwise. Select path-style explicitly if your provider requires it.';
