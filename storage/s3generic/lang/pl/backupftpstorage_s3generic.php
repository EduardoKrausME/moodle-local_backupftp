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

$string['accesskey'] = 'Identyfikator klucza dostępu';
$string['bucket'] = 'Nazwa bucketu';
$string['endpoint'] = 'Punkt końcowy S3 (HTTPS)';
$string['folder'] = 'Prefiks kopii zapasowych';
$string['password'] = 'Tajny klucz dostępu';
$string['pluginname'] = 'Zgodny z S3 (ogólny)';
$string['region'] = 'Region podpisywania';
$string['styleauto'] = 'Automatyczny';
$string['stylepath'] = 'Styl ścieżki (endpoint/bucket)';
$string['stylevirtual'] = 'Styl hosta wirtualnego (bucket.endpoint)';
$string['urlstyle'] = 'Styl adresu URL bucketu';
$string['urlstyle_help'] = 'Użyj stylu adresowania wymaganego przez dostawcę. Tryb automatyczny stosuje styl ścieżki dla adresów IP, jednoelementowych nazw hostów i nazw bucketów zawierających kropki, a w pozostałych przypadkach styl hosta wirtualnego. Wybierz styl ścieżki wprost, jeśli dostawca tego wymaga.';
