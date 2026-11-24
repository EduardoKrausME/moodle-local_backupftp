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

$string['accesskey'] = '存取金鑰 ID';
$string['aws'] = 'Amazon S3';
$string['bucket'] = 'Bucket / Space 名稱';
$string['extensionrequired'] = 'S3 儲存需要 PHP cURL 與 SimpleXML 擴充套件。';
$string['folder'] = '備份前綴';
$string['httperror'] = 'S3 請求失敗（HTTP {$a}）。';
$string['invalidconfig'] = 'S3 目的地設定無效。';
$string['invalidfolder'] = 'S3 備份前綴無效。';
$string['password'] = '秘密存取金鑰';
$string['pluginname'] = 'Amazon S3 / DigitalOcean Spaces';
$string['region'] = '區域（例如 us-east-1 或 nyc1）';
$string['service'] = '儲存服務';
$string['space'] = 'DigitalOcean Spaces';
$string['transferfailed'] = 'S3 備份傳輸失敗。';
