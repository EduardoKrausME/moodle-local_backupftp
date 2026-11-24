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

$string['accesskey'] = 'Erişim anahtarı kimliği';
$string['aws'] = 'Amazon S3';
$string['bucket'] = 'Bucket / Space adı';
$string['extensionrequired'] = 'S3 depolaması için PHP cURL ve SimpleXML uzantıları gereklidir.';
$string['folder'] = 'Yedekleme ön eki';
$string['httperror'] = 'S3 isteği başarısız oldu (HTTP {$a}).';
$string['invalidconfig'] = 'Geçersiz S3 hedef yapılandırması.';
$string['invalidfolder'] = 'Geçersiz S3 yedekleme ön eki.';
$string['password'] = 'Gizli erişim anahtarı';
$string['pluginname'] = 'Amazon S3 / DigitalOcean Spaces';
$string['region'] = 'Bölge (ör. us-east-1 veya nyc1)';
$string['service'] = 'Depolama hizmeti';
$string['space'] = 'DigitalOcean Spaces';
$string['transferfailed'] = 'S3 yedek aktarımı başarısız oldu.';
