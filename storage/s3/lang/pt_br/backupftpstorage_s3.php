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

$string['accesskey'] = 'Chave de acesso';
$string['aws'] = 'Amazon S3';
$string['bucket'] = 'Nome do bucket / Space';
$string['extensionrequired'] = 'As extensões PHP cURL e SimpleXML são necessárias para o armazenamento S3.';
$string['folder'] = 'Prefixo dos backups';
$string['httperror'] = 'A requisição S3 falhou (HTTP {$a}).';
$string['invalidconfig'] = 'Configuração de destino S3 inválida.';
$string['invalidfolder'] = 'Prefixo de backup S3 inválido.';
$string['password'] = 'Chave secreta';
$string['pluginname'] = 'Amazon S3 / DigitalOcean Spaces';
$string['region'] = 'Região (ex.: us-east-1 ou nyc1)';
$string['service'] = 'Serviço de armazenamento';
$string['space'] = 'DigitalOcean Spaces';
$string['transferfailed'] = 'Falha na transferência do backup S3.';
