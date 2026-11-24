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

$string['accesskey'] = 'Identyfikator klucza dostępu';
$string['aws'] = 'Amazon S3';
$string['bucket'] = 'Nazwa bucketu / Space';
$string['extensionrequired'] = 'Do przechowywania w S3 wymagane są rozszerzenia PHP cURL i SimpleXML.';
$string['folder'] = 'Prefiks kopii zapasowych';
$string['httperror'] = 'Żądanie S3 nie powiodło się (HTTP {$a}).';
$string['invalidconfig'] = 'Nieprawidłowa konfiguracja miejsca docelowego S3.';
$string['invalidfolder'] = 'Nieprawidłowy prefiks kopii zapasowych S3.';
$string['password'] = 'Tajny klucz dostępu';
$string['pluginname'] = 'Amazon S3 / DigitalOcean Spaces';
$string['region'] = 'Region (np. us-east-1 lub nyc1)';
$string['service'] = 'Usługa przechowywania';
$string['space'] = 'DigitalOcean Spaces';
$string['transferfailed'] = 'Przesyłanie kopii zapasowej do S3 nie powiodło się.';
