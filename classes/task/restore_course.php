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
 * Restore course file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\task;

use backup;
use core_course_category;
use Exception;
use local_backupftp\server\ftp;
use restore_controller;

/**
 * Class restore_course
 *
 * @package local_backupftp\task
 */
class restore_course extends \core\task\scheduled_task {
    /**
     * Function get_name
     *
     * @return string
     */
    public function get_name() {
        return "Restore the scheduled ones that are on the FTP";
    }

    /**
     * Function execute
     *
     * @param int $limite
     *
     * @throws \dml_exception
     */
    public function execute($limite = 30) {
        global $DB, $CFG;

        require_once("{$CFG->dirroot}/backup/util/includes/restore_includes.php");
        require_once("{$CFG->dirroot}/local/backupftp/classes/server/ftp.php");
        require_once("{$CFG->dirroot}/course/classes/category.php");

        $backupftprestores = $DB->get_records_sql("
                SELECT * FROM {local_backupftp_restore}
                 WHERE status LIKE 'waiting'
              ORDER BY RAND()
                 LIMIT {$limite}");

        if ($backupftprestores) {
            foreach ($backupftprestores as $backupftprestore) {
                $backupftprestore->timestart = time();
                $backupftprestore->status = "initiated";
                $DB->update_record("local_backupftp_restore", $backupftprestore);

                try {
                    $logs = $this->execute_restore($backupftprestore->remotefile);
                } catch (Exception $e) {
                    $logs[] = "Exception: <b>" . $e->getMessage() . "</b>";
                }
                $logs = implode("\n", $logs);

                $backupftprestore->logs = $logs;
                $backupftprestore->timeend = time();
                $backupftprestore->status = "completed";
                $DB->update_record("local_backupftp_restore", $backupftprestore);

                echo "{$logs}\n<br>\n";
            }
        } else {
            echo "Nothing to execute";
        }
    }

    /**
     * Function execute_restore
     *
     * @param $remotefile
     *
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    private function execute_restore($remotefile) {
        global $CFG, $DB;

        $logs = [];

        $extension = pathinfo($remotefile, PATHINFO_EXTENSION);
        $filename = pathinfo($remotefile, PATHINFO_FILENAME);

        if ($extension != "mbz") {
            $logs[] = "File is not MBZ";
            return $logs;
        }

        $localfile = "{$CFG->tempdir}/backup-" . uniqid() . ".mbz";
        $fileresource = fopen($localfile, "w");

        $ftp = new ftp();
        $logs = $ftp->connect($logs);

        $size = ftp_size($ftp->conn_id, $remotefile);
        $size = preg_replace('/[^0-9]/', "", $size);
        if (ftp::format_bytes($size) < 10) {
            $logs[] = get_string('ftp_remote_file_size', 'local_backupftp', ['size' => $size]);
            return $logs;
        }

        $logs[] = get_string('processing_file', 'local_backupftp',
            ['remote_file' => $remotefile, 'size' => ftp::format_bytes($size)]);

        $logs = $ftp->connect($logs);
        if (ftp_fget($ftp->conn_id, $fileresource, $remotefile, FTP_BINARY)) {
            $logs[] = get_string('file_found_and_downloaded', 'local_backupftp');
        } else {
            $logs[] = get_string('error_downloading_file', 'local_backupftp', ['error' => error_get_last()]);
            return $logs;
        }

        $packer = get_file_packer("application/vnd.moodle.backup");
        $backupid = \restore_controller::get_tempdir_name(SITEID, get_admin()->id);
        $path = "{$CFG->tempdir}/backup/{$backupid}/";
        if ($packer->extract_to_pathname($localfile, $path)) {
            $logs[] = get_string('mbz_extracted_successfully', 'local_backupftp');
        } else {
            $logs[] = get_string('error_extracting_mbz', 'local_backupftp');
            return $logs;
        }

        $transaction = $DB->start_delegated_transaction();

        $folder = $backupid;
        $userdoingrestore = get_admin()->id;

        $categoria = $this->categoryid($remotefile, $logs);
        $logs[] = get_string('adding_to_category', 'local_backupftp', ['categoria' => $categoria]);

        $course = $DB->get_record_sql("SELECT id FROM {course} WHERE fullname = '{$filename}' AND category = '{$categoria}'");
        if ($course) {
            $logs[] = get_string('restore_course_already_exists', 'local_backupftp',
                ['course_url' => "{$CFG->wwwroot}/course/view.php?id={$course->id}"]);
            return $logs;
        }

        $courseid = \restore_dbops::create_new_course("", "", $categoria);
        $logs[] = get_string('access_course', 'local_backupftp',
            ['course_url' => "{$CFG->wwwroot}/course/view.php?id={$courseid}"]);

        $controller = new restore_controller($folder, $courseid,
            backup::INTERACTIVE_NO, backup::MODE_GENERAL, $userdoingrestore,
            backup::TARGET_NEW_COURSE);
        if ($controller->execute_precheck()) {
            $controller->execute_plan();
        } else {
            try {
                $transaction->rollback(new Exception("..."));
            } catch (Exception $e) {
                unset($transaction);
                $controller->destroy();
                unset($controller);
                unlink($localfile);

                $logs[] = get_string('pre_check_failure', 'local_backupftp');
                return $logs;
            }
        }

        unset($transaction);
        $controller->destroy();
        unset($controller);
        unlink($localfile);

        $logs[] = get_string('temporary_files_deleted', 'local_backupftp');

        return $logs;
    }

    /**
     * Function categoryid
     *
     * @param $remotefile
     * @param $logs
     *
     * @return int|mixed
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    private function categoryid($remotefile, &$logs) {
        global $DB;

        $ftppasta = get_config("local_backupftp", "ftppasta");
        $categoria = str_replace($ftppasta, "", $remotefile);

        $dirname = pathinfo($categoria, PATHINFO_DIRNAME);

        $categorias = explode("/", $dirname);
        unset($categorias[0]);

        $categoriaid = get_config("local_backupftp", "categorystart");
        foreach ($categorias as $categoriname) {
            $categoriadb = $DB->get_record_sql("
                    SELECT *
                      FROM {course_categories}
                     WHERE name LIKE '{$categoriname}'
                       AND parent = {$categoriaid}");
            if ($categoriadb) {
                $categoriaid = $categoriadb->id;
            } else {
                $category = (object)[
                    "name" => $categoriname,
                    "parent" => $categoriaid,
                    "visible" => 1,
                    "visibleold" => 1,
                ];
                $category = core_course_category::create($category);
                $categoriaid = $category->id;

                $logs[] = get_string('category_created_successfully', 'local_backupftp', ['categoria_nome' => $categoriname]);
            }
        }
        return $categoriaid;
    }
}
