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

$string['accesskey'] = 'Erişim anahtarı kimliği';
$string['bucket'] = 'Bucket adı';
$string['endpoint'] = 'S3 uç noktası (HTTPS)';
$string['folder'] = 'Yedekleme ön eki';
$string['password'] = 'Gizli erişim anahtarı';
$string['pluginname'] = 'S3 uyumlu (genel)';
$string['region'] = 'İmzalama bölgesi';
$string['styleauto'] = 'Otomatik';
$string['stylepath'] = 'Yol biçimi (endpoint/bucket)';
$string['stylevirtual'] = 'Sanal ana bilgisayar biçimi (bucket.endpoint)';
$string['urlstyle'] = 'Bucket URL biçimi';
$string['urlstyle_help'] = 'Sağlayıcınızın gerektirdiği adresleme biçimini kullanın. Otomatik seçenek IP adresleri, tek etiketli ana bilgisayar adları ve nokta içeren bucket adları için yol biçimini, diğer durumlarda sanal ana bilgisayar biçimini kullanır. Sağlayıcınız gerektiriyorsa yol biçimini açıkça seçin.';
