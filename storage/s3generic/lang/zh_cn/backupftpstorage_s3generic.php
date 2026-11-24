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
 * Generic S3 backup storage.
 *
 * @package backupftpstorage_s3generic
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['accesskey'] = '访问密钥 ID';
$string['bucket'] = 'Bucket 名称';
$string['endpoint'] = 'S3 端点（HTTPS）';
$string['folder'] = '备份前缀';
$string['password'] = '访问密钥';
$string['pluginname'] = '兼容 S3（通用）';
$string['region'] = '签名区域';
$string['styleauto'] = '自动';
$string['stylepath'] = '路径式（endpoint/bucket）';
$string['stylevirtual'] = '虚拟主机式（bucket.endpoint）';
$string['urlstyle'] = 'Bucket URL 样式';
$string['urlstyle_help'] = '请使用提供商要求的寻址方式。自动模式对 IP 地址、单标签主机名及带点的 Bucket 名称使用路径式寻址，其他情况使用虚拟主机式寻址。如果提供商要求路径式寻址，请明确选择该方式。';
