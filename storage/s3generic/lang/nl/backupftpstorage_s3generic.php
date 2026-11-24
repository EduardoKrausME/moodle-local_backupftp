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

$string['accesskey'] = 'Toegangssleutel-ID';
$string['bucket'] = 'Bucketnaam';
$string['endpoint'] = 'S3-endpoint (HTTPS)';
$string['folder'] = 'Back-upprefix';
$string['password'] = 'Geheime toegangssleutel';
$string['pluginname'] = 'S3-compatibel (generiek)';
$string['region'] = 'Ondertekeningsregio';
$string['styleauto'] = 'Automatisch';
$string['stylepath'] = 'Padstijl (endpoint/bucket)';
$string['stylevirtual'] = 'Virtuele hoststijl (bucket.endpoint)';
$string['urlstyle'] = 'Bucket-URL-stijl';
$string['urlstyle_help'] = 'Gebruik de adresseringsstijl die je provider vereist. Automatisch gebruikt padstijl voor IP-adressen, hostnamen met één label en bucketnamen met punten, en anders virtuele hoststijl. Selecteer padstijl expliciet als je provider dit vereist.';
