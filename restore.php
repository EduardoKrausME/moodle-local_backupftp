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

$files = optional_param_array("file", false, PARAM_TEXT);
if ($files) {

    foreach ($files as $file) {
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

echo $OUTPUT->render_from_template("local_backupftp/local_backupftp_list_files-info");

require_once("{$CFG->dirroot}/local/backupftp/classes/server/ftp.php");

$ftppasta = get_config("local_backupftp", "ftppasta");

echo $OUTPUT->render_from_template("local_backupftp/local_backupftp_list_files-form",
    ["list_files" => local_backupftp_list_files($ftppasta)]);

echo $OUTPUT->footer();

/**
 * Function local_backupftp_list_files
 *
 * @param $pasta
 *
 * @return string
 * @throws coding_exception
 * @throws dml_exception
 */
function local_backupftp_list_files($pasta) {
    global $DB, $CFG, $OUTPUT, $ftppasta;

    $ftp = new \local_backupftp\server\ftp();
    $ftp->connect();

    if (!$ftp->conn_id) {
        return "";
    }

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

        $infocategori = local_backupftp_get_categoria($categoria);

        $countall = $countexist = 0;
        $internalreturn = "";
        foreach ($files as $file) {

            if ($file["type"] == "dir") {
                $internalreturn .= local_backupftp_list_files("{$pasta}/{$file["name"]}");
            } else if ($file["type"] == "file") {
                $countall++;

                $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
                if ($extension == "mbz") {
                    $restoretext = "";
                    $showinput = "<input type='checkbox' name='file[]' value='{$pasta}/{$file["name"]}'>";
                    if ($restore = $DB->get_record_sql("
                                SELECT *
                                  FROM {local_backupftp_restore}
                                 WHERE remotefile = :remotefile
                                 LIMIT 1", ["remotefile" => "{$pasta}/{$file["name"]}"])) {
                        $restoretext .= "<br> / <span style='color:#3F51B5'>" .
                            get_string('already_added_status', 'local_backupftp', ['status' => $restore->status]) . "</span>";
                    }

                    $filename = pathinfo($file["name"], PATHINFO_FILENAME);
                    if ($infocategori["id"] > 1 && $course = $DB->get_record_sql("
                                SELECT id
                                  FROM {course}
                                 WHERE fullname = :fullname
                                   AND category = :category",
                            ["fullname" => $filename, "category" => $infocategori["id"]])) {
                        $showinput = "";
                        $restoretext .=
                            " / <a style='color:#a41d1d' target='_blank' href='{$CFG->wwwroot}/course/view.php?id={$course->id}'>" .
                            get_string('course_already_exists', 'local_backupftp') . "</a>";
                        $countexist++;
                    }

                    $filesize = get_string('file_size', 'local_backupftp', ['size' => \local_backupftp\server\ftp::format_bytes($file['size'])]);
                    $createdontime = get_string('created_on_time', 'local_backupftp', ['modify' => $file['modify']]);

                    $internalreturn .= $OUTPUT->render_from_template("local_backupftp/local_backupftp_list_files-p", [
                        "showinput" => $showinput,
                        "filename" => $file['name'],
                        "filesize" => $filesize,
                        "createdontime" => $createdontime,
                        "restoretext" => $restoretext,
                    ]);
                }
            }
        }

        $return .= $OUTPUT->render_from_template("local_backupftp/local_backupftp_list_files-fieldset", [
            "infocategori_link" => $infocategori["link"],
            "unique" => $unique,
            "countall" => $countall,
            "countexist" => $countexist,
            "data" => $internalreturn,
        ]);
    }
    return $return;
}

/**
 * Function local_backupftp_get_categoria
 *
 * @param $pasta
 *
 * @return array
 * @throws dml_exception
 * @throws coding_exception
 */
function local_backupftp_get_categoria($pasta) {
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
