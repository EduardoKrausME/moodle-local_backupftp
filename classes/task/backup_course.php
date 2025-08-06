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
 * Backup course file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\task;

use backup;
use backup_controller;
use backup_plan_dbops;
use Exception;
use local_backupftp\server\ftp;

/**
 * Class backup_course
 *
 * @package local_backupftp\task
 */
class backup_course extends \core\task\scheduled_task {

    /**
     * Function get_name
     *
     * @return string
     */
    public function get_name() {
        return "Generates scheduled backups and sends them via FTP";
    }

    /**
     * Function execute
     *
     * @param int $limite
     * @throws Exception
     */
    public function execute($limite = 30) {
        global $DB, $CFG;

        require_once("{$CFG->dirroot}/backup/util/includes/backup_includes.php");
        require_once("{$CFG->dirroot}/local/backupftp/classes/server/ftp.php");

        $sql = "
            UPDATE {local_backupftp_course}
               SET status = 'waiting'
             WHERE status = 'initiated'
               AND timestart < (UNIX_TIMESTAMP() - 6 * 3600)";
        $DB->execute($sql);

        if ($DB->get_dbfamily() == "postgres") {
            $backupftpcourses = $DB->get_records_sql("
                    SELECT * FROM {local_backupftp_course}
                     WHERE status LIKE 'waiting'
                  ORDER BY RANDOM()
                     LIMIT {$limite}");
        } else {
            $backupftpcourses = $DB->get_records_sql("
                    SELECT * FROM {local_backupftp_course}
                     WHERE status LIKE 'waiting'
                  ORDER BY RAND()
                     LIMIT {$limite}");
        }

        if ($backupftpcourses) {
            foreach ($backupftpcourses as $backupftpcourse) {
                $backupftpcourse->timestart = time();
                $backupftpcourse->status = "initiated";
                $DB->update_record("local_backupftp_course", $backupftpcourse);

                $logs = $this->execute_backup($backupftpcourse->courseid);
                $logs = implode("\n", $logs);

                $backupftpcourse->logs = $logs;
                $backupftpcourse->timeend = time();
                $backupftpcourse->status = "completed";
                $DB->update_record("local_backupftp_course", $backupftpcourse);

                echo "{$logs}\n<br>\n";
            }
        } else {
            echo get_string("nothing_to_execute", "local_backupftp");
        }
    }

    /**
     * Function execute_backup
     *
     * @param $courseid
     * @return array
     * @throws Exception
     */
    private function execute_backup($courseid) {
        global $CFG;

        $logs = [];

        $logs[] = get_string('backup_creation_parameters', 'local_backupftp') . "\n
   type     : COURSE
   courseid : {$courseid}
   format   : MOODLE2
   mode     : MODE_GENERAL";

        $bc = new backup_controller(backup::TYPE_1COURSE, $courseid, backup::FORMAT_MOODLE,
            backup::INTERACTIVE_YES, backup::MODE_GENERAL, get_admin()->id);

        $filename = backup_plan_dbops::get_default_backup_filename($bc->get_format(), $bc->get_type(), $bc->get_id(), false, false);
        $bc->get_plan()->get_setting("filename")->set_value(ftp::remove_accents($filename));
        $bc->get_plan()->get_setting("users")->set_value(get_config("local_backupftp", "settingrootusers"));
        $bc->get_plan()->get_setting("anonymize")->set_value(get_config("local_backupftp", "settingrootanonymize"));

        $bc->finish_ui();
        $bc->execute_plan();
        $results = $bc->get_results();

        /** @var \stored_file $file */
        $file = $results["backup_destination"];

        // Do we need to store backup somewhere else?
        if ($file) {
            $logs[] = "MBZ file created";

            $contenthash = $file->get_contenthash();
            $l1 = $contenthash[0] . $contenthash[1];
            $l2 = $contenthash[2] . $contenthash[3];
            $localtempfile = "{$CFG->dataroot}/filedir/{$l1}/{$l2}/{$contenthash}";

            $logs = $this->send_ftp($localtempfile, $file->get_filename(), $courseid, $logs);
            mtrace("Local file deleted\n");
            $file->delete();
        } else {
            $logs[] = "Error creating MBZ file";
        }
        $bc->destroy();

        return $logs;
    }

    /**
     * Function send_ftp
     *
     * @param $localtempfile
     * @param $filename
     * @param $courseid
     * @param $logs
     * @return array
     * @throws Exception
     */
    private function send_ftp($localtempfile, $filename, $courseid, $logs) {
        global $DB, $CFG;

        $localfileenable = get_config("local_backupftp", "localfileenable");
        $localfilepath = get_config("local_backupftp", "localfilepath");
        $ftpenable = get_config("local_backupftp", "ftpenable");
        $ftpnames = get_config("local_backupftp", "ftpnames");
        $ftppath = get_config("local_backupftp", "ftppasta");
        $ftporganize = get_config("local_backupftp", "ftporganize");

        if ($ftpenable || $localfileenable) {

            if ($ftpenable) {
                $ftp = new ftp();
                $logs = $ftp->connect($logs);
                if (!$ftp->conn_id) {
                    return $logs;
                }
            }
            if ($localfileenable) {
                if (!isset($localfilepath[3])) {
                    $localfilepath = "{$CFG->dataroot}/backup";
                }
            }

            $course = null;
            if ($ftpnames) {
                $course = $DB->get_record("course", ["id" => $courseid]);
                $filename = "{$course->fullname}.mbz";
                $filename = str_replace("/", ".", $filename);
            }

            $paths = [];
            if ($ftporganize) {
                if (!$course) {
                    $course = $DB->get_record("course", ["id" => $courseid]);
                }
                $cat = $course->category;
                while ($cat) {
                    $categorie = $DB->get_record_sql("SELECT id, name, parent FROM {course_categories} WHERE id = :id",
                        ["id" => $cat]);
                    $paths[] = $categorie->name;
                    $cat = $categorie->parent;
                }
                $paths = array_reverse($paths);
            }

            $logsfolder = [];
            foreach ($paths as $path) {
                $path = str_replace("/", ".", $path);
                if ($ftpenable) {
                    $ftppath = "{$ftppath}/{$path}";
                    if (!@ftp_mkdir($ftp->conn_id, $ftppath)) {
                        $logsfolder[] = get_string('error_creating_folder', 'local_backupftp',
                            ["ftppath" => $ftppath, "errormsg" => error_get_last()]);
                    }
                }
                if ($localfileenable) {
                    $localpath = "{$localfilepath}/{$path}";
                    @mkdir($localpath, 0777, true);
                }
            }

            $remotefilepath = "{$ftppath}/{$filename}";
            if ($ftpenable) {
                @ftp_delete($ftp->conn_id, $remotefilepath);

                if (ftp_fput($ftp->conn_id, $remotefilepath, fopen($localtempfile, "r"), FTP_BINARY)) {
                    $logs[] = get_string('file_uploaded', 'local_backupftp',
                        ['file' => $localtempfile, 'remote_file' => $remotefilepath]);
                } else {
                    $logs = array_merge($logs, $logsfolder);
                    $logs[] = "<span style='color: #d10707'>" . get_string("settings_error_sending_backup", "local_backupftp") .
                        "</span> '<b>{$remotefilepath}</b>', " .
                        get_string("settings_file_size", "local_backupftp") . ftp::format_bytes(filesize($localtempfile)) . " " .
                        get_string("settings_error", "local_backupftp") . "<b>" . error_get_last() . "</b>!";

                    return $logs;
                }
                ftp_close($ftp->conn_id);
            }
            if ($localfileenable) {
                if ($ftporganize) {
                    $backuppath = implode("/", $paths);
                    $localfile = "{$localfilepath}/{$backuppath}/{$filename}";
                } else {
                    $localfile = "{$localfilepath}/{$filename}";
                }
                copy($localtempfile, $localfile);
            }
        } else {
            $logs[] = "FTP upload disabled";
        }

        return $logs;
    }
}
