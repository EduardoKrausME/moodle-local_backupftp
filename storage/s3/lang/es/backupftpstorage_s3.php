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

$string['accesskey'] = 'ID de la clave de acceso';
$string['aws'] = 'Amazon S3';
$string['bucket'] = 'Nombre del bucket / Space';
$string['extensionrequired'] = 'Las extensiones cURL y SimpleXML de PHP son necesarias para el almacenamiento S3.';
$string['folder'] = 'Prefijo de las copias de seguridad';
$string['httperror'] = 'Ha fallado la solicitud a S3 (HTTP {$a}).';
$string['invalidconfig'] = 'Configuración del destino S3 no válida.';
$string['invalidfolder'] = 'Prefijo de copias de seguridad S3 no válido.';
$string['password'] = 'Clave de acceso secreta';
$string['pluginname'] = 'Amazon S3 / DigitalOcean Spaces';
$string['region'] = 'Región (p. ej., us-east-1 o nyc1)';
$string['service'] = 'Servicio de almacenamiento';
$string['space'] = 'DigitalOcean Spaces';
$string['transferfailed'] = 'Ha fallado la transferencia de la copia de seguridad a S3.';
