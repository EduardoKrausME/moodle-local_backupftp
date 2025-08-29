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
 * Backup file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require("../../config.php");
global $DB, $PAGE, $OUTPUT;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url("/local/backupftp/backup.php"));
$PAGE->set_pagelayout("base");
$PAGE->set_title(get_string("backup_courses_and_categories", "local_backupftp"));
$PAGE->set_heading(get_string("backup_courses_and_categories", "local_backupftp"));

require_login();
require_capability("local/backupftp:manage", context_system::instance());

echo $OUTPUT->header();

$categorys = optional_param_array("category", false, PARAM_INT);
if ($categorys) {
    foreach ($categorys as $category) {
        $courses = $DB->get_records("course", ["category" => $category]);
        foreach ($courses as $course) {

            if (!$DB->get_record_sql("
                        SELECT * FROM {local_backupftp_course}
                         WHERE courseid = {$course->id}
                           AND status LIKE 'waiting'")) {
                $data = (object)[
                    "courseid" => $course->id,
                    "status" => "waiting",
                    "logs" => "",
                    "timecreated" => time(),
                    "timestart" => 0,
                    "timeend" => 0,
                ];
                $DB->insert_record("local_backupftp_course", $data);

                echo "<p style='color:#2196F3;font-weight:bold;'>" .
                    get_string('course_added_to_backup_queue', 'local_backupftp', ['course_id' => $course->id]) . "</p>";
            }
        }
    }
}

echo "<p>" . get_string('view_backup_report', 'local_backupftp') . " <a href='report-backup.php'>" .
    get_string('report', 'local_backupftp') . "</a></p>";
echo "<p>" . get_string('run_cron', 'local_backupftp') . " <a href='run-task.php'>" .
    get_string('cron', 'local_backupftp') . "</a></p>";
echo '<form method="post">';
echo "<h2>" . get_string('categories', 'local_backupftp') . "</h2>";

echo local_backupftp_categorias(0);

echo '<input type="submit" value="' . get_string("submit", "local_backupftp") . '"></form>';

echo $OUTPUT->footer();

/**
 * Function local_backupftp_categorias
 *
 * @param $cat
 *
 * @return string
 * @throws coding_exception
 * @throws dml_exception
 */
function local_backupftp_categorias($cat) {
    global $DB;
    $categories = $DB->get_records_sql("SELECT id, name, parent FROM {course_categories} WHERE parent = :parent",
        ["parent" => $cat]);

    $return = "";
    if ($categories) {
        $unique = uniqid();
        $return .= "<fieldset id='id-{$unique}' style='border:1px solid #959595;padding:6px;padding-left:50px;margin:3px;'>";
        $return .= "<span style='float:right;color:#E91E63;' onclick=\"$('#id-{$unique} input').click();\">" .
            get_string('select_deselect_all', 'local_backupftp') . "</span>";

        $countcat = 0;
        foreach ($categories as $categorie) {
            $params = ["category" => $categorie->id];
            $count = $DB->get_field("course", 'COUNT(*)', $params);

            $statuss = $DB->get_records_sql("
                    SELECT COUNT(*) AS linhas, status
                      FROM {local_backupftp_course}
                     WHERE courseid IN (SELECT c.id FROM {course} c WHERE c.category = :category)
                  GROUP BY status ORDER BY status", $params);
            $statusfolder = "";
            foreach ($statuss as $status) {
                $statusfolder .= " / <strong>{$status->status}:</strong> {$status->linhas}";
            }

            $return .= "
                <div>
                    <input type='checkbox' name='category[{$categorie->id}]' value='{$categorie->id}'>
                    <label><strong>{$categorie->name}</strong></label>
                    <strong>" . get_string("courses", "local_backupftp") . ":</strong> {$count} {$statusfolder}
                </div>";
            $return .= local_backupftp_categorias($categorie->id);

            $countcat += $count;
        }
        $return .= "<span style='color:#2196F3;'>" .
            get_string("total_in_category", "local_backupftp", ["total" => $countcat]) . "</span>";
        $return .= "</fieldset>";
    }
    return $return;
}
