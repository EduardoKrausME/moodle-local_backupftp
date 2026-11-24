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

$string['accesskey'] = '访问密钥 ID';
$string['aws'] = 'Amazon S3';
$string['bucket'] = 'Bucket / Space 名称';
$string['extensionrequired'] = 'S3 存储需要 PHP cURL 和 SimpleXML 扩展。';
$string['folder'] = '备份前缀';
$string['httperror'] = 'S3 请求失败（HTTP {$a}）。';
$string['invalidconfig'] = 'S3 目标配置无效。';
$string['invalidfolder'] = 'S3 备份前缀无效。';
$string['password'] = '访问密钥';
$string['pluginname'] = 'Amazon S3 / DigitalOcean Spaces';
$string['region'] = '区域（例如 us-east-1 或 nyc1）';
$string['service'] = '存储服务';
$string['space'] = 'DigitalOcean Spaces';
$string['transferfailed'] = 'S3 备份传输失败。';
