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
 * Backup course file.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\task;

use backup;
use backup_controller;
use backup_plan_dbops;
use core\task\scheduled_task;
use Exception;
use local_backupftp\localfilepath;
use local_backupftp\server\ftp;
use stored_file;
use Throwable;

/**
 * Generate queued course backups and send them to configured destinations.
 */
class backup_course extends scheduled_task {

    /**
     * Return the task name.
     *
     * @return string
     */
    public function get_name() {
        return 'Generates scheduled backups and sends them via FTP';
    }

    /**
     * Execute queued backups.
     *
     * @param int $limit Maximum number of courses to process.
     * @return array{processed:int,completed:int,errors:int}
     */
    public function execute($limit = 30) {
        global $DB, $CFG;

        $limit = max(1, (int)$limit);
        $summary = [
            'processed' => 0,
            'completed' => 0,
            'errors' => 0,
        ];

        require_once("{$CFG->dirroot}/backup/util/includes/backup_includes.php");
        require_once("{$CFG->dirroot}/local/backupftp/classes/server/ftp.php");

        $cutoff = time() - 6 * 3600;
        $DB->execute(
            "UPDATE {local_backupftp_course}
                SET status = 'waiting'
              WHERE status = 'initiated'
                AND timestart < :cutoff",
            ['cutoff' => $cutoff]
        );

        for ($i = 0; $i < $limit; $i++) {
            if ($DB->get_dbfamily() === 'postgres') {
                $backupftpcourse = $DB->get_record_sql(
                    "SELECT *
                       FROM {local_backupftp_course}
                      WHERE status = 'waiting'
                   ORDER BY RANDOM()
                      LIMIT 1"
                );
            } else {
                $backupftpcourse = $DB->get_record_sql(
                    "SELECT *
                       FROM {local_backupftp_course}
                      WHERE status = 'waiting'
                   ORDER BY RAND()
                      LIMIT 1"
                );
            }

            if (!$backupftpcourse) {
                mtrace(get_string('nothing_to_execute', 'local_backupftp'));
                break;
            }

            $backupftpcourse->timestart = time();
            $backupftpcourse->status = 'initiated';
            $backupftpcourse->timeend = 0;
            $DB->update_record('local_backupftp_course', $backupftpcourse);

            $status = 'completed';
            $logs = [];

            try {
                $result = $this->execute_backup((int)$backupftpcourse->courseid);
                $logs = $result['logs'];
                $status = $result['status'];
            } catch (Throwable $e) {
                $logs[] = get_string('backup_unexpected_error', 'local_backupftp', $e->getMessage());
                $status = 'error';
            }

            $logtext = implode("\n", $logs);
            $backupftpcourse->logs = $logtext;
            $backupftpcourse->timeend = time();
            $backupftpcourse->status = $status;
            $DB->update_record('local_backupftp_course', $backupftpcourse);

            $summary['processed']++;
            if ($status === 'error') {
                $summary['errors']++;
                mtrace('ERROR: ' . $logtext);
            } else {
                $summary['completed']++;
                mtrace($logtext);
            }
        }

        return $summary;
    }

    /**
     * Create a course backup and send it to configured destinations.
     *
     * @param int $courseid Course id.
     * @return array{status:string,logs:array}
     */
    private function execute_backup(int $courseid): array {
        global $CFG;

        $logs = [];
        $controller = null;
        $file = null;

        $logs[] = get_string('backup_creation_parameters', 'local_backupftp') . "\n
   type     : COURSE
   courseid : {$courseid}
   format   : MOODLE2";

        try {
            $controller = new backup_controller(
                backup::TYPE_1COURSE,
                $courseid,
                backup::FORMAT_MOODLE,
                backup::INTERACTIVE_YES,
                backup::MODE_GENERAL,
                get_admin()->id
            );

            $filename = backup_plan_dbops::get_default_backup_filename(
                $controller->get_format(),
                $controller->get_type(),
                $controller->get_id(),
                false,
                false
            );

            $controller->get_plan()->get_setting('filename')->set_value(ftp::remove_accents($filename));
            $controller->get_plan()->get_setting('users')->set_value(
                get_config('local_backupftp', 'settingrootusers')
            );
            $controller->get_plan()->get_setting('anonymize')->set_value(
                get_config('local_backupftp', 'settingrootanonymize')
            );

            $controller->finish_ui();
            $controller->execute_plan();
            $results = $controller->get_results();

            /** @var stored_file|null $file */
            $file = $results['backup_destination'] ?? null;
            if (!$file) {
                $logs[] = get_string('backup_destination_missing', 'local_backupftp');
                return ['status' => 'error', 'logs' => $logs];
            }

            $logs[] = 'MBZ file created';

            $contenthash = $file->get_contenthash();
            $l1 = $contenthash[0] . $contenthash[1];
            $l2 = $contenthash[2] . $contenthash[3];
            $localtempfile = "{$CFG->dataroot}/filedir/{$l1}/{$l2}/{$contenthash}";

            return $this->send_file_ftp_local(
                $localtempfile,
                $file->get_filename(),
                $courseid,
                $logs
            );
        } finally {
            if ($file instanceof stored_file) {
                $file->delete();
                mtrace('Temp local file deleted');
            }
            if ($controller instanceof backup_controller) {
                $controller->destroy();
            }
        }
    }

    /**
     * Send a generated backup to FTP and/or local storage.
     *
     * @param string $localtempfile Moodle filedir path.
     * @param string $filename Backup filename.
     * @param int $courseid Course id.
     * @param array $logs Existing logs.
     * @return array{status:string,logs:array}
     * @throws Exception
     */
    private function send_file_ftp_local(
        string $localtempfile,
        string $filename,
        int $courseid,
        array $logs
    ): array {
        global $DB;

        $localfileenable = (bool)get_config('local_backupftp', 'localfileenable');
        $ftpenable = (bool)get_config('local_backupftp', 'ftpenable');
        $ftpnames = (bool)get_config('local_backupftp', 'ftpnames');
        $ftppath = (string)get_config('local_backupftp', 'ftppasta');
        $ftporganize = (bool)get_config('local_backupftp', 'ftporganize');
        $localfilepath = '';
        $status = 'completed';
        $ftp = null;

        if (!$ftpenable && !$localfileenable) {
            $logs[] = get_string('backup_no_destination', 'local_backupftp');
            return ['status' => 'error', 'logs' => $logs];
        }

        if (!is_readable($localtempfile)) {
            $logs[] = get_string('backup_tempfile_unreadable', 'local_backupftp', $localtempfile);
            return ['status' => 'error', 'logs' => $logs];
        }

        if ($localfileenable) {
            $localfilepath = localfilepath::get_path();
        }

        $course = null;
        if ($ftpnames) {
            $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
            $filename = "{$course->id} - {$course->fullname}.mbz";
            $filename = str_replace('/', '.', $filename);
        }

        $paths = [];
        if ($ftporganize) {
            if (!$course) {
                $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
            }
            $categoryid = (int)$course->category;
            while ($categoryid) {
                $category = $DB->get_record(
                    'course_categories',
                    ['id' => $categoryid],
                    'id,name,parent',
                    MUST_EXIST
                );
                $paths[] = $category->name;
                $categoryid = (int)$category->parent;
            }
            $paths = array_reverse($paths);
        }

        $safepaths = [];
        foreach ($paths as $path) {
            $path = trim(str_replace(['/', '\\'], '.', $path));
            if ($path !== '') {
                $safepaths[] = $path;
            }
        }

        if ($ftpenable) {
            $ftp = new ftp();
            $logs = $ftp->connect($logs);
            if (!$ftp->connid) {
                $status = 'error';
            }
        }

        $localfolderpath = $localfilepath;
        foreach ($safepaths as $path) {
            if ($ftpenable && $ftp && $ftp->connid) {
                $ftppath = "{$ftppath}/{$path}";
                @ftp_mkdir($ftp->connid, $ftppath);
            }

            if ($localfileenable) {
                $localfolderpath .= DIRECTORY_SEPARATOR . $path;
                if (!make_writable_directory($localfolderpath, true)) {
                    $logs[] = get_string('log:savelocal:error', 'local_backupftp', $localfolderpath);
                    $status = 'error';
                }
            }
        }

        if ($ftpenable && $ftp && $ftp->connid) {
            $remotefilepath = "{$ftppath}/{$filename}";
            @ftp_delete($ftp->connid, $remotefilepath);

            $handle = @fopen($localtempfile, 'rb');
            if (!$handle) {
                $logs[] = get_string('backup_tempfile_unreadable', 'local_backupftp', $localtempfile);
                $status = 'error';
            } else {
                $uploaded = @ftp_fput($ftp->connid, $remotefilepath, $handle, FTP_BINARY);
                fclose($handle);

                if ($uploaded) {
                    $logs[] = get_string('file_uploaded', 'local_backupftp', [
                        'file' => $localtempfile,
                        'remote_file' => $remotefilepath,
                    ]);
                } else {
                    $error = error_get_last();
                    $errormessage = is_array($error) && !empty($error['message']) ? $error['message'] : '-';
                    $logs[] = get_string('backup_ftp_upload_failed', 'local_backupftp', [
                        'remote_file' => $remotefilepath,
                        'size' => ftp::format_bytes((int)filesize($localtempfile)),
                        'error' => $errormessage,
                    ]);
                    $status = 'error';
                }
            }
            $ftp->close();
        }

        if ($localfileenable) {
            $localfile = $ftporganize
                ? $localfolderpath . DIRECTORY_SEPARATOR . $filename
                : $localfilepath . DIRECTORY_SEPARATOR . $filename;

            if (!make_writable_directory(dirname($localfile), true)) {
                $logs[] = get_string('log:savelocal:error', 'local_backupftp', $localfile);
                $status = 'error';
            } else if (@copy($localtempfile, $localfile)) {
                $logs[] = get_string('log:savelocal:success', 'local_backupftp', $localfile);
            } else {
                $logs[] = get_string('log:savelocal:error', 'local_backupftp', $localfile);
                $status = 'error';
            }
        }

        return ['status' => $status, 'logs' => $logs];
    }
}
