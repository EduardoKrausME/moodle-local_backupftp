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
 * phpcs:disable moodle.Files.MoodleInternal.MoodleInternalGlobalState
 *
 * Restore course file.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\task;

use backup;
use core\task\scheduled_task;
use Exception;
use local_backupftp\server\ftp;
use local_backupftp\transfer_client;
use local_backupftp\util\category;
use restore_controller;
use restore_dbops;
use stdClass;

global $CFG;
require_once("{$CFG->dirroot}/backup/util/includes/backup_includes.php");
require_once("{$CFG->dirroot}/backup/util/includes/restore_includes.php");
require_once("{$CFG->dirroot}/local/backupftp/classes/server/ftp.php");
require_once("{$CFG->dirroot}/course/classes/category.php");

/**
 * Class restore_course.
 *
 * @package local_backupftp\task
 */
class restore_course extends scheduled_task {
    /**
     * Function get_name.
     *
     * @return string
     */
    public function get_name() {
        return 'Restore queued MBZ backups from FTP, local folder or another Moodle transfer API';
    }

    /**
     * Function execute.
     *
     * @param int $limit Number of restores to process.
     * @throws Exception
     */
    public function execute($limit = 30) {
        global $DB, $CFG;

        require_once("{$CFG->dirroot}/backup/util/includes/restore_includes.php");
        require_once("{$CFG->dirroot}/local/backupftp/classes/server/ftp.php");
        require_once("{$CFG->dirroot}/course/classes/category.php");

        $cutoff = time() - 6 * 3600;
        $sql = "
            UPDATE {local_backupftp_restore}
               SET status = 'waiting'
             WHERE status = 'initiated'
               AND timestart < :cutoff";
        $DB->execute($sql, ['cutoff' => $cutoff]);

        for ($i = 0; $i < $limit; $i++) {
            if ($DB->get_dbfamily() == 'postgres') {
                $randon = "ORDER BY RANDOM()";
            } else {
                $randon = "ORDER BY RAND()";
            }

            $sql = "
                SELECT * FROM {local_backupftp_restore}
                 WHERE status LIKE 'waiting'
              {$randon}
                 LIMIT 1";
            $backupftprestore = $DB->get_record_sql($sql);

            if ($backupftprestore) {
                $backupftprestore->timestart = time();
                $backupftprestore->status = 'initiated';
                $backupftprestore->timeend = 0;
                $DB->update_record('local_backupftp_restore', $backupftprestore);

                try {
                    $result = $this->execute_restore($backupftprestore);
                    $logs = $result['logs'];
                    $status = $result['status'];
                } catch (Exception $e) {
                    $logs = ['Exception: <b>' . $e->getMessage() . '</b>'];
                    $status = 'error';
                }
                $logs = implode("\n", $logs);

                $backupftprestore->logs = $logs;
                $backupftprestore->timeend = time();
                $backupftprestore->status = $status;
                $DB->update_record('local_backupftp_restore', $backupftprestore);

                mtrace($logs);
            } else {
                mtrace(get_string('nothing_to_execute', 'local_backupftp'));
                return;
            }
        }
    }

    /**
     * Execute a restore row.
     *
     * @param stdClass|string $restore Restore record or legacy remote file string.
     * @return array{status:string,logs:array}
     * @throws Exception
     */
    private function execute_restore($restore): array {
        global $CFG, $DB;

        $record = is_object($restore) ? $restore : (object) ['remotefile' => $restore, 'source' => 'configured'];
        $source = empty($record->source) ? 'configured' : $record->source;
        $remotefile = (string) $record->remotefile;

        mtrace("File is {$remotefile}");
        $logs = ["File is {$remotefile}"];

        $extension = pathinfo($remotefile, PATHINFO_EXTENSION);
        $filename = pathinfo($remotefile, PATHINFO_FILENAME);

        if (strtolower($extension) !== 'mbz') {
            $logs[] = 'File is not MBZ';
            return ['status' => 'error', 'logs' => $logs];
        }

        $localfile = make_temp_directory('local_backupftp_' . uniqid()) . '/backup-' . uniqid('', true) . '.mbz';

        if ($source === 'transfer') {
            $result = $this->download_transfer_file($record, $localfile, $logs);
        } else {
            $result = $this->copy_configured_source_file($remotefile, $localfile, $logs);
        }
        if ($result['status'] !== 'completed') {
            return ['status' => $result['status'], 'logs' => $logs];
        }
        $size = $result['size'];

        $logs[] = get_string('processing_file', 'local_backupftp', [
            'remote_file' => $remotefile,
            'size' => ftp::format_bytes($size),
        ]);

        $packer = get_file_packer('application/vnd.moodle.backup');
        $backuptmpdir = restore_controller::get_tempdir_name(SITEID, get_admin()->id);
        $path = make_backup_temp_directory($backuptmpdir);
        if ($packer->extract_to_pathname($localfile, $path)) {
            $logs[] = get_string('mbz_extracted_successfully', 'local_backupftp');
            $logs[] = $path;
        } else {
            @unlink($localfile);
            $logs[] = get_string('error_extracting_mbz', 'local_backupftp');
            return ['status' => 'error', 'logs' => $logs];
        }

        $transaction = $DB->start_delegated_transaction();

        $userdoingrestore = get_admin()->id;

        if ($source === 'transfer') {
            $categoria = category::get_categoryid_from_backup_path($remotefile, $logs);
        } else {
            $categoria = category::get_categoryid($remotefile, $logs);
        }
        $logs[] = get_string('adding_to_category', 'local_backupftp', ['categoria' => $categoria]);

        $course = $DB->get_record_sql(
            'SELECT id FROM {course} WHERE fullname = :fullname AND category = :category',
            ['fullname' => $filename, 'category' => $categoria]
        );
        if ($course) {
            $transaction->allow_commit();
            @unlink($localfile);
            $logs[] = get_string(
                'restore_course_already_exists', 'local_backupftp',
                ['course_url' => "{$CFG->wwwroot}/course/view.php?id={$course->id}"]
            );
            return ['status' => 'completed', 'logs' => $logs];
        }

        $courseid = restore_dbops::create_new_course('', '', $categoria);
        $logs[] = get_string(
            'access_course', 'local_backupftp',
            ['course_url' => "{$CFG->wwwroot}/course/view.php?id={$courseid}"]
        );

        $controller = new restore_controller(
            $backuptmpdir, $courseid,
            backup::INTERACTIVE_NO, backup::MODE_GENERAL, $userdoingrestore,
            backup::TARGET_NEW_COURSE
        );

        try {
            $controller->execute_precheck();

            $precheckresults = $controller->get_precheck_results();
            if (!empty($precheckresults['warnings'])) {
                $items = [];

                foreach ($precheckresults['warnings'] as $warning) {
                    $items[] = "<li>{$warning}</li>";
                }

                $logs[] = '<div class="alert alert-warning"><b>Restore precheck warnings:</b><ul class="mb-0">' .
                    implode('', $items) .
                    '</ul></div>';
            }

            if (!empty($precheckresults['errors'])) {
                $items = [];

                foreach ($precheckresults['errors'] as $error) {
                    $items[] = "<li>{$error}</li>";
                }

                $logs[] = '<div class="alert alert-danger"><b>Restore precheck errors:</b><ul class="mb-0">' .
                    implode('', $items) .
                    '</ul></div>';

                throw new Exception('Restore precheck failed');
            }

            $controller->execute_plan();
            $transaction->allow_commit();
        } catch (Exception $e) {
            try {
                $transaction->rollback($e);
            } catch (Exception $e) { // phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedCatch
                // Keep original error in the logs below.
            }
            $controller->destroy();
            @unlink($localfile);

            $logs[] = get_string('pre_check_failure', 'local_backupftp') . ': ' . $e->getMessage();
            return ['status' => 'error', 'logs' => $logs];
        }

        $controller->destroy();
        @unlink($localfile);

        return ['status' => 'completed', 'logs' => $logs];
    }

    /**
     * Download a file from another Moodle transfer API.
     *
     * @param stdClass $record Restore row.
     * @param string $localfile Local target path.
     * @param array $logs Logs by reference.
     * @return array{status:string,size:int}
     * @throws Exception
     */
    private function download_transfer_file(stdClass $record, string $localfile, array &$logs): array {
        $wwwroot = trim((string) ($record->sourcewwwroot ?? ''));
        $ip = trim((string) ($record->sourceip ?? ''));
        $token = trim((string) ($record->sourcetoken ?? ''));

        if ($wwwroot === '' || $token === '') {
            $logs[] = get_string('transfer_restore_missing_remote_data', 'local_backupftp');
            return ['status' => 'error', 'size' => 0];
        }

        $size = transfer_client::download_backup($wwwroot, $ip, $token, (string) $record->remotefile, $localfile, $logs);
        $logs[] = get_string('file_found_and_downloaded', 'local_backupftp') . " - " . ftp::format_bytes($size);

        return ['status' => 'completed', 'size' => $size];
    }

    /**
     * Copy/download a file using the configured FTP/local source, preserving legacy behavior.
     *
     * @param string $remotefile Remote/local file path.
     * @param string $localfile Local target path.
     * @param array $logs Logs by reference.
     * @return array{status:string,size:int}
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \Exception
     */
    private function copy_configured_source_file(string $remotefile, string $localfile, array &$logs): array {
        $localfileenable = get_config('local_backupftp', 'localfileenable');
        $ftpenable = get_config('local_backupftp', 'ftpenable');

        if ($ftpenable) {
            $ftp = new ftp();
            $logs = $ftp->connect($logs);

            $size = ftp_size($ftp->connid, $remotefile);
            $size = (int) preg_replace('/[^0-9]/', '', $size);
            if ($size < 10) {
                $logs[] = get_string('ftp_remote_file_size', 'local_backupftp', ['size' => $size]);
                return ['status' => 'error', 'size' => 0];
            }

            $fileresource = fopen($localfile, 'wb');
            if ($fileresource === false) {
                $logs[] = get_string('transfer_restore_tempfile_error', 'local_backupftp');
                return ['status' => 'error', 'size' => 0];
            }

            if (ftp_fget($ftp->connid, $fileresource, $remotefile)) {
                fclose($fileresource);
                $logs[] = get_string('file_found_and_downloaded', 'local_backupftp');
            } else {
                fclose($fileresource);
                @unlink($localfile);
                $logs[] = get_string('error_downloading_file', 'local_backupftp', ['error' => json_encode(error_get_last())]);
                return ['status' => 'error', 'size' => 0];
            }
        } else if ($localfileenable) {
            $size = is_file($remotefile) ? (int) filesize($remotefile) : 0;
            if ($size < 10) {
                $logs[] = get_string('ftp_remote_file_size', 'local_backupftp', ['size' => $size]);
                return ['status' => 'error', 'size' => 0];
            }
            copy($remotefile, $localfile);
            mtrace(' Size: ' . filesize($localfile));
        } else {
            $logs[] = 'plugin Disable';
            return ['status' => 'error', 'size' => 0];
        }

        clearstatcache(true, $localfile);
        $size = is_file($localfile) ? (int) filesize($localfile) : 0;

        return ['status' => 'completed', 'size' => $size];
    }
}
