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

$string['accesskey'] = '存取金鑰 ID';
$string['bucket'] = 'Bucket 名稱';
$string['endpoint'] = 'S3 端點（HTTPS）';
$string['folder'] = '備份前綴';
$string['password'] = '秘密存取金鑰';
$string['pluginname'] = '相容 S3（通用）';
$string['region'] = '簽署區域';
$string['styleauto'] = '自動';
$string['stylepath'] = '路徑式（endpoint/bucket）';
$string['stylevirtual'] = '虛擬主機式（bucket.endpoint）';
$string['urlstyle'] = 'Bucket URL 樣式';
$string['urlstyle_help'] = '請使用供應商要求的定址方式。自動模式對 IP 位址、單一標籤主機名稱及含點號的 Bucket 名稱使用路徑式定址，其他情況使用虛擬主機式定址。若供應商要求路徑式定址，請明確選擇該方式。';
