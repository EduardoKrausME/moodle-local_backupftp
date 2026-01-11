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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Secure download for local MBZ files created by local_backupftp.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\session\manager;
use local_backupftp\localfilepath;

require(__DIR__ . '/../../config.php');

global $CFG, $PAGE;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/backupftp/download.php'));

require_login();
require_capability('local/backupftp:manage', $context);
require_sesskey();

$rel = required_param('f', PARAM_RAW_TRIMMED);
$rel = str_replace(chr(0), '', $rel);
$rel = str_replace('\\', '/', $rel);
$rel = ltrim($rel, '/');

if ($rel === '' || preg_match('#(^|/)\.\.(/|$)#', $rel)) {
    send_file_not_found();
}

$ext = core_text::strtolower(pathinfo($rel, PATHINFO_EXTENSION));
if ($ext !== 'mbz') {
    send_file_not_found();
}

$root = localfilepath::get_path();

$rootreal = realpath($root);
if (!$rootreal) {
    send_file_not_found();
}
$rootreal = rtrim($rootreal, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

$full = $rootreal . str_replace('/', DIRECTORY_SEPARATOR, $rel);
$fullreal = realpath($full);

if (!$fullreal) {
    send_file_not_found();
}
if (strpos($fullreal, $rootreal) !== 0) {
    send_file_not_found();
}
if (!is_file($fullreal) || !is_readable($fullreal)) {
    send_file_not_found();
}

// Stream file (no memory blow).
manager::write_close();
ignore_user_abort(true);
core_php_time_limit::raise(0);

$filename = basename($fullreal);
$filesize = (int)filesize($fullreal);

@header('Content-Description: File Transfer');
@header('Content-Type: application/vnd.moodle.backup');
@header('Content-Disposition: attachment; filename="' . $filename . '"');
@header('Content-Length: ' . $filesize);
@header('Cache-Control: private, must-revalidate');
@header('Pragma: public');
@header('Expires: 0');

$fp = fopen($fullreal, 'rb');
if ($fp === false) {
    send_file_not_found();
}

while (!feof($fp)) {
    echo fread($fp, 1024 * 1024); // 1MB chunks.
    @flush();
}
fclose($fp);
exit;
