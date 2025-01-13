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
 * restore file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_backupftp\server\ftp;

require('../../config.php');
require(__DIR__ . '/classes/server/ftp.php');
global $DB, $PAGE, $OUTPUT;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url("/local/backupftp/restore.php"));
$PAGE->set_pagelayout("base");
$PAGE->set_title(get_string('restore_courses_and_categories', 'local_backupftp'));
$PAGE->set_heading(get_string('restore_courses_and_categories', 'local_backupftp'));

require_login();
require_capability("local/backupftp:manage", context_system::instance());

echo $OUTPUT->header();

if (isset($_POST["file"])) {

    foreach ($_POST["file"] as $file) {
        if (!$DB->get_record_sql("
                    SELECT *
                      FROM {local_backupftp_restore}
                     WHERE remotefile = '{$file}'
                       AND status != 'completed'")) {
            $data = (object)[
                "remotefile" => $file,
                "status" => "waiting",
                "logs" => "",
                "timecreated" => time(),
                "timestart" => 0,
                "timeend" => 0,
            ];
            $DB->insert_record("local_backupftp_restore", $data);

            echo "<p style='color:#2196F3;font-weight:bold;'>" .
                get_string('file_added_to_restore_queue', 'local_backupftp', ['file' => $file]) . "</p>";

        }
    }
}
echo "<p>" . get_string('view_backup_report', 'local_backupftp') .
    " <a href='report-restore.php'>" . get_string('report', 'local_backupftp') . "</a></p>";

require_once("{$CFG->dirroot}/local/backupftp/classes/server/ftp.php");

$ftppasta = get_config("local_backupftp", "ftppasta");

echo '<form method="post">';
echo "<h2>" . get_string('ftp_files', 'local_backupftp') . "</h2>";
echo listar_arquivos($ftppasta);
echo '<input type="submit" value="' . get_string('send', 'local_backupftp') . '"></form>';

echo $OUTPUT->footer();

/**
 * Function listar_arquivos
 *
 * @param $pasta
 *
 * @return string
 * @throws coding_exception
 * @throws dml_exception
 */
function listar_arquivos($pasta) {
    global $DB, $CFG, $ftppasta;

    $ftp = new ftp();
    $ftp->connect();

    $files = [];
    $ftprawlists = ftp_rawlist($ftp->conn_id, $pasta . "/");
    foreach ($ftprawlists as $file) {
        preg_match('/(.*?)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\w+ \d+ \d+:\d+)\s+(.*)/', $file, $info);
        $files[] = [
            "type" => strpos($info[0], "d") === 0 ? "dir" : "file",
            "size" => $info[5],
            "modify" => $info[6],
            "name" => $info[7],
        ];
    }

    $return = "";
    if ($files) {
        $unique = uniqid();
        $categoria = str_replace($ftppasta, "", $pasta);

        $infocategori = get_categoria($categoria);

        $return .= "<fieldset id='id-{$unique}' style='border:1px solid #959595;padding:6px;padding-left:50px;margin:3px;'>";
        $return .= "<legend style='float: initial;width: auto;padding: 0 11px;margin-bottom: 4px;'>" .
            $infocategori["link"] . "</legend>";
        $return .= "<span style='float:right;color:#E91E63;margin-top: -21px;' onclick=\"$('#id-{$unique} input').click();\">" .
            get_string("select_deselect_all", "local_backupftp") . "</span>";

        $countall = $countexist = 0;
        foreach ($files as $file) {

            if ($file["type"] == "dir") {
                $return .= listar_arquivos("{$pasta}/{$file["name"]}");
            } else if ($file["type"] == "file") {
                $countall++;

                $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
                if ($extension == "mbz") {
                    $restoretext = "";
                    $showinput = "<input type='checkbox' name='file[]' value='{$pasta}/{$file["name"]}'>";
                    if ($restore = $DB->get_record_sql("
                                SELECT *
                                  FROM {local_backupftp_restore}
                                 WHERE remotefile = '{$pasta}/{$file["name"]}'
                                 LIMIT 1")) {
                        $restoretext .= "<br> / <span style='color:#3F51B5'>" .
                            get_string('already_added_status', 'local_backupftp', ['status' => $restore->status]) . "</span>";
                    }

                    $filename = pathinfo($file["name"], PATHINFO_FILENAME);
                    if ($infocategori["id"] > 1 && $course = $DB->get_record_sql("
                                SELECT id
                                  FROM {course}
                                 WHERE fullname = '{$filename}'
                                   AND category = '{$infocategori["id"]}'")) {
                        $showinput = "";
                        $restoretext .=
                            " / <a style='color:#a41d1d' target='_blank' href='{$CFG->wwwroot}/course/view.php?id={$course->id}'>" .
                            get_string('course_already_exists', 'local_backupftp') . "</a>";
                        $countexist++;
                    }

                    $return .= "
                        <p>
                            {$showinput}
                            <strong>{$file['name']}</strong>, " . get_string('file_size', 'local_backupftp',
                            ['size' => ftp::format_bytes($file['size'])]) . ", " . get_string('created_on', 'local_backupftp',
                            ['modify' => $file['modify']]) . "
                            {$restoretext}
                        </p>";
                }
            }
        }

        $return .= "<h4 style='float:right;color:#1E58E9;padding-top: -21px;'>" . get_string('total_files', 'local_backupftp') .
            " {$countall}<br>" . get_string("course_already_exists", "local_backupftp") . ": {$countexist}</h4>";
        $return .= "</fieldset>";
    }
    return $return;
}

/**
 * Function get_categoria
 *
 * @param $pasta
 *
 * @return array
 * @throws dml_exception
 * @throws coding_exception
 */
function get_categoria($pasta) {
    global $DB, $CFG;

    $returnlink = get_string('category_link', 'local_backupftp', "{$CFG->wwwroot}/course/management.php?categoryid=1");

    $categorias = explode("/", $pasta);
    unset($categorias[0]);
    $categoriaid = get_config("local_backupftp", "categorystart");
    foreach ($categorias as $categoriname) {
        if ($categoriaid == -1) {
            $returnlink .= " / <span style='color: #E91E63;'>{$categoriname}</span>";
        } else {
            $categoriadb = $DB->get_record_sql("
                    SELECT *
                      FROM {course_categories}
                     WHERE name LIKE '{$categoriname}'
                       AND parent = {$categoriaid}");
            if ($categoriadb) {
                $categoriaid = $categoriadb->id;
                $returnlink .= " / <a href='{$CFG->wwwroot}/course/management.php?categoryid={$categoriadb->id}'
                                      target='blank'>{$categoriname}</a>";
            } else {
                $categoriaid = -1;
                $returnlink .= " / <span style='color: #E91E63;'>{$categoriname}</span>";
            }
        }
    }
    return ["link" => $returnlink, "id" => $categoriaid];
}
