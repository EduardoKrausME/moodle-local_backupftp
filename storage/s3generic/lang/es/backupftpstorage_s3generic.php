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

$string['accesskey'] = 'ID de la clave de acceso';
$string['bucket'] = 'Nombre del bucket';
$string['endpoint'] = 'Endpoint de S3 (HTTPS)';
$string['folder'] = 'Prefijo de las copias de seguridad';
$string['password'] = 'Clave de acceso secreta';
$string['pluginname'] = 'Compatible con S3 (genérico)';
$string['region'] = 'Región de firma';
$string['styleauto'] = 'Automático';
$string['stylepath'] = 'Estilo de ruta (endpoint/bucket)';
$string['stylevirtual'] = 'Alojamiento virtual (bucket.endpoint)';
$string['urlstyle'] = 'Estilo de URL del bucket';
$string['urlstyle_help'] = 'Use el estilo de direccionamiento que requiera su proveedor. El modo automático utiliza el estilo de ruta para direcciones IP, hosts de una sola etiqueta y nombres de bucket con puntos; en los demás casos utiliza el estilo de alojamiento virtual. Seleccione expresamente el estilo de ruta si su proveedor lo requiere.';
