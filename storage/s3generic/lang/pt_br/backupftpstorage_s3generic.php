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

$string['accesskey'] = 'Chave de acesso';
$string['bucket'] = 'Nome do bucket';
$string['endpoint'] = 'Endpoint S3 (HTTPS)';
$string['folder'] = 'Prefixo dos backups';
$string['password'] = 'Chave secreta';
$string['pluginname'] = 'S3 compatível (genérico)';
$string['region'] = 'Região de assinatura';
$string['styleauto'] = 'Automático';
$string['stylepath'] = 'Path-style (endpoint/bucket)';
$string['stylevirtual'] = 'Virtual-hosted (bucket.endpoint)';
$string['urlstyle'] = 'Estilo de URL do bucket';
$string['urlstyle_help'] = 'Use o estilo de endereçamento exigido pelo provedor. O modo automático usa path-style para endereços IP, hosts sem domínio e buckets com pontos, e virtual-hosted nos demais casos. Selecione path-style explicitamente quando exigido pelo serviço.';
