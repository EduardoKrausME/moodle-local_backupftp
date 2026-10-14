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
use DOMDocument;
use DOMXPath;
use Exception;
use local_backupftp\storage\manager;
use local_backupftp\util\file;
use stored_file;
use Throwable;

global $CFG;
defined('MOODLE_INTERNAL') || die;

require_once("{$CFG->dirroot}/backup/util/includes/backup_includes.php");
require_once("{$CFG->dirroot}/backup/util/includes/restore_includes.php");
require_once("{$CFG->dirroot}/course/classes/category.php");

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
        return 'Generates scheduled backups and sends them to configured storage providers';
    }

    /**
     * Execute queued backups.
     *
     * @param int $limit Maximum number of courses to process.
     * @return array{processed:int,completed:int,errors:int}
     * @throws \coding_exception
     * @throws \dml_exception
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

        $cutoff = time() - 6 * 3600;
        $DB->execute(
            "UPDATE {local_backupftp_course}
                SET status = 'waiting'
              WHERE status = 'initiated'
                AND timestart < :cutoff",
            ['cutoff' => $cutoff]
        );

        for ($i = 0; $i < $limit; $i++) {
            if ($DB->get_dbfamily() == 'postgres') {
                $randon = "ORDER BY RANDOM()";
            } else {
                $randon = "ORDER BY RAND()";
            }

            $sql = "
                 SELECT *
                   FROM {local_backupftp_course}
                  WHERE status = 'waiting'
               {$randon}
                  LIMIT 1";
            $backupftpcourse = $DB->get_record_sql($sql);

            if (!$backupftpcourse) {
                mtrace(get_string('nothing_to_execute', 'local_backupftp'));
                return $summary;
            }

            $backupftpcourse->timestart = time();
            $backupftpcourse->status = 'initiated';
            $backupftpcourse->timeend = 0;
            $DB->update_record('local_backupftp_course', $backupftpcourse);

            $logs = [];

            try {
                $result = $this->execute_backup((int)$backupftpcourse->courseid, (string)($backupftpcourse->storageids ?? ''));
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
     * @throws \backup_controller_exception
     * @throws \base_plan_exception
     * @throws \base_setting_exception
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \Exception
     * @throws \Throwable
     */
    private function execute_backup(int $courseid, string $storageids): array {
        global $CFG;

        $logs = [];
        $controller = null;
        $file = null;
        $backuptempfile = null;
        $sanitizetempdir = null;

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

            $controller->get_plan()->get_setting('filename')->set_value(file::remove_accents($filename));
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

            // Use Moodle's File API so backups also work with alternative file systems (e.g. S3).
            $backuptempfile = $file->copy_content_to_temp('local_backupftp', 'backup_');
            if ($backuptempfile === false) {
                throw new Exception('Unable to copy MBZ backup from Moodle file storage to a temporary file');
            }

            $preparedbackup = $this->remove_admin_from_backup($backuptempfile, $logs);
            $localtempfile = $preparedbackup['path'];
            $sanitizetempdir = $preparedbackup['tempdir'];

            return $this->send_file_to_storages($localtempfile, $file->get_filename(),
                $courseid, $logs, $storageids);
        } finally {
            if ($sanitizetempdir && is_dir($sanitizetempdir)) {
                fulldelete($sanitizetempdir);
            }
            if (is_string($backuptempfile) && is_file($backuptempfile)) {
                @unlink($backuptempfile);
            }
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
     * Remove the admin user from users.xml while keeping all other backup users.
     *
     * @param string $originalpath Local temporary copy of the generated Moodle backup.
     * @param array $logs Backup logs by reference.
     * @return array
     * @throws \Throwable
     * @throws \coding_exception
     * @throws \invalid_dataroot_permissions
     * @throws \moodle_exception
     */
    private function remove_admin_from_backup(string $originalpath, array &$logs): array {
        $tempdir = make_temp_directory('local_backupftp_' . uniqid()) . '/sanitize-' . uniqid('', true);
        $extractdir = $tempdir . '/extract';

        if (!make_writable_directory($extractdir)) {
            throw new Exception('Unable to create temporary directory to sanitize MBZ backup');
        }

        try {
            $packer = get_file_packer('application/vnd.moodle.backup');
            if (!$packer->extract_to_pathname($originalpath, $extractdir, null, null, true)) {
                throw new Exception('Unable to extract MBZ backup to remove admin user');
            }

            $usersfile = $extractdir . '/users.xml';
            if (!is_file($usersfile)) {
                fulldelete($tempdir);
                return ['path' => $originalpath, 'tempdir' => null];
            }

            $dom = new DOMDocument();
            $dom->preserveWhiteSpace = true;
            if (!$dom->load($usersfile)) {
                throw new Exception('Unable to read users.xml from MBZ backup');
            }

            $xpath = new DOMXPath($dom);
            $adminnodes = $xpath->query('/users/user[username="admin"]');
            if ($adminnodes === false || $adminnodes->length === 0) {
                fulldelete($tempdir);
                return ['path' => $originalpath, 'tempdir' => null];
            }

            $removed = $adminnodes->length;
            for ($i = $adminnodes->length - 1; $i >= 0; $i--) {
                $node = $adminnodes->item($i);
                if ($node && $node->parentNode) {
                    $node->parentNode->removeChild($node);
                }
            }

            if ($dom->save($usersfile) === false) {
                throw new Exception('Unable to save users.xml after removing admin user');
            }

            $filestemp = get_directory_list($extractdir, '', false, true);
            $files = [];
            foreach ($filestemp as $archivepath) {
                $files[$archivepath] = $extractdir . '/' . $archivepath;
            }

            $sanitizedfile = $tempdir . '/backup.mbz';
            if (!$packer->archive_to_pathname($files, $sanitizedfile)) {
                throw new Exception('Unable to rebuild MBZ backup after removing admin user');
            }

            $logs[] = "Removed {$removed} admin user(s) from backup users.xml";

            return ['path' => $sanitizedfile, 'tempdir' => $tempdir];
        } catch (Throwable $e) {
            if (is_dir($tempdir)) {
                fulldelete($tempdir);
            }
            throw $e;
        }
    }

    /**
     * Dispatch generated MBZ to selected storage instances via the subplugin API.
     *
     * @param string $localtempfile Prepared MBZ pathname.
     * @param string $filename Backup filename.
     * @param int $courseid Course id.
     * @param array $logs Backup log.
     * @param string $storageids JSON list of selected instance IDs, blank for old queue entries.
     * @return array
     */
    private function send_file_to_storages(
        string $localtempfile,
        string $filename,
        int $courseid,
        array $logs,
        string $storageids
    ): array {
        if (!is_readable($localtempfile)) {
            $logs[] = get_string('backup_tempfile_unreadable', 'local_backupftp', $localtempfile);
            return ['status' => 'error', 'logs' => $logs];
        }

        $available = manager::instances(true);
        $selected = $storageids === '' ? array_keys($available) : json_decode($storageids, true);
        $selected = is_array($selected) ? array_unique(array_map('intval', $selected)) : [];
        if (!$selected) {
            $logs[] = get_string('backup_no_destination', 'local_backupftp');
            return ['status' => 'error', 'logs' => $logs];
        }

        $status = 'completed';
        foreach ($selected as $id) {
            if (empty($available[$id])) {
                $logs[] = get_string('storage_failure', 'local_backupftp', (string) $id);
                $status = 'error';
                continue;
            }
            try {
                $destination = manager::from_record($available[$id]);
                if (!$destination->store($localtempfile, $filename, $courseid, $logs)) {
                    $logs[] = get_string('storage_failure', 'local_backupftp', $available[$id]->name);
                    $status = 'error';
                }
            } catch (\Throwable $e) {
                $logs[] = get_string('storage_failure', 'local_backupftp', $available[$id]->name) .
                    ' ' . $e->getMessage();
                $status = 'error';
            }
        }
        return ['status' => $status, 'logs' => $logs];
    }
}
