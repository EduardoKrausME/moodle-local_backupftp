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

$string['accesskey'] = 'Toegangssleutel-ID';
$string['aws'] = 'Amazon S3';
$string['bucket'] = 'Bucket-/Space-naam';
$string['extensionrequired'] = 'De PHP-extensies cURL en SimpleXML zijn vereist voor S3-opslag.';
$string['folder'] = 'Back-upprefix';
$string['httperror'] = 'Het S3-verzoek is mislukt (HTTP {$a}).';
$string['invalidconfig'] = 'Ongeldige S3-bestemmingsconfiguratie.';
$string['invalidfolder'] = 'Ongeldige S3-back-upprefix.';
$string['password'] = 'Geheime toegangssleutel';
$string['pluginname'] = 'Amazon S3 / DigitalOcean Spaces';
$string['region'] = 'Regio (bijv. us-east-1 of nyc1)';
$string['service'] = 'Opslagdienst';
$string['space'] = 'DigitalOcean Spaces';
$string['transferfailed'] = 'De overdracht van de S3-back-up is mislukt.';
