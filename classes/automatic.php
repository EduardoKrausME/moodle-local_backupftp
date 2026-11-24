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
 * Native automated backup capture and transfer queue.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp;

use local_backupftp\storage\manager;

/**
 * Captures the exact native backup before Moodle can remove its own copy.
 */
class automatic {
    /** @var string Queue table. */
    public const TABLE = 'local_backupftp_auto';
    /** @var string Private file area. */
    public const AREA = 'automatic';

    /**
     * Return the saved settings. Zero days means retain forever.
     *
     * @return array
     */
    public static function settings(): array {
        $saved = json_decode((string)get_config('local_backupftp', 'automaticsettings'), true);
        return array_replace(['enabled' => false, 'storageids' => [], 'days' => 0], is_array($saved) ? $saved : []);
    }

    /**
     * List active installed destinations supporting automatic backups.
     *
     * @return array
     */
    public static function storages(): array {
        $result = [];
        foreach (manager::instances(true) as $record) {
            try {
                if (manager::from_record($record)->supports_automatic()) {
                    $result[$record->id] = $record;
                }
            } catch (\Throwable $e) {
                // An uninstalled provider cannot accept new jobs.
                continue;
            }
        }
        return $result;
    }

    /**
     * Resolve destinations. A sole active storage is implicit, multiple require explicit selection.
     *
     * @param array $ids Selected IDs.
     * @param array $storages Active destinations indexed by ID.
     * @return array
     */
    public static function selected(array $ids, array $storages): array {
        if (count($storages) === 1) {
            return $storages;
        }
        return array_intersect_key($storages, array_fill_keys(array_map('intval', $ids), true));
    }

    /**
     * Fingerprint the destination configuration to avoid writing or deleting at a changed location.
     *
     * @param \stdClass $record Destination record.
     * @return string
     */
    public static function fingerprint(\stdClass $record): string {
        return hash('sha256', $record->type . "\n" . $record->config);
    }

    /**
     * The backup event is emitted before the native retention routine runs.
     *
     * @param \core\event\course_backup_created $event Completion event.
     */
    public static function observe(\core\event\course_backup_created $event): void {
        global $CFG, $DB;
        $settings = self::settings();
        if (empty($settings['enabled'])) {
            return;
        }
        require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
        if ((int)$event->other['mode'] !== \backup::MODE_AUTOMATED ||
                $event->other['format'] !== \backup::FORMAT_MOODLE) {
            return;
        }
        $backupid = (string)$event->other['backupid'];
        if (!preg_match('/\A[a-f0-9]{32}\z/', $backupid)) {
            return;
        }
        $lock = \core\lock\lock_config::get_lock_factory('local_backupftp_auto')->get_lock($backupid, 10);
        if (!$lock) {
            mtrace('BackupFTP: could not lock automated backup ' . $backupid);
            return;
        }
        try {
            if ($DB->record_exists(self::TABLE, ['backupid' => $backupid])) {
                return;
            }
            $destinations = [];
            foreach (self::selected((array)$settings['storageids'], self::storages()) as $record) {
                $destinations[] = [
                    'id' => (int)$record->id,
                    'hash' => self::fingerprint($record),
                    'path' => '',
                    'deleted' => false,
                ];
            }
            if (!$destinations) {
                mtrace('BackupFTP: no selected active destination for automated backup.');
                return;
            }
            $job = (object)[
                'backupid' => $backupid,
                'courseid' => (int)$event->objectid,
                'status' => 'capturefailed',
                'destinations' => json_encode($destinations),
                'timecreated' => (int)$event->timecreated,
                'nextattempt' => 0,
                'timecompleted' => 0,
            ];
            $job->id = $DB->insert_record(self::TABLE, $job);
            $source = self::find_source($event);
            if ($source === null) {
                mtrace('BackupFTP: exact automated backup archive not found: ' . $backupid);
                return;
            }
            $fs = get_file_storage();
            $record = [
                'contextid' => \context_system::instance()->id, 'component' => 'local_backupftp',
                'filearea' => self::AREA, 'itemid' => $job->id, 'filepath' => '/', 'filename' => 'backup.mbz', 'userid' => 0,
            ];
            if ($source instanceof \stored_file) {
                $fs->create_file_from_storedfile($record, $source);
            } else {
                $fs->create_file_from_pathname($record, $source);
            }
            $job->status = 'pending';
            $DB->update_record(self::TABLE, $job);
        } catch (\Throwable $e) {
            // A plugin failure must never abort Moodle's native backup.
            mtrace('BackupFTP: capture failed for automated backup ' . $backupid);
        } finally {
            $lock->release();
        }
    }

    /**
     * Match the archive's own backup ID, not just the newest filename or timestamp.
     *
     * @param \core\event\course_backup_created $event Event.
     * @return \stored_file|string|null
     */
    private static function find_source(\core\event\course_backup_created $event) {
        global $DB;
        $courseid = (int)$event->objectid;
        $backupid = (string)$event->other['backupid'];
        $controller = $DB->get_record('backup_controllers', ['backupid' => $backupid], 'id,timecreated');
        $since = ($controller ? (int)$controller->timecreated : (int)$event->timecreated - 600) - 5;
        $fs = get_file_storage();
        $files = $fs->get_area_files($event->contextid, 'backup', 'automated', 0, 'timecreated DESC', false);
        $dir = make_request_directory();
        try {
            foreach ($files as $file) {
                if ($file->get_timecreated() < $since || $file->get_filesize() < 10) {
                    continue;
                }
                $path = $dir . '/candidate.mbz';
                if ($file->copy_content_to($path) && self::matches($path, $dir, $backupid, $courseid)) {
                    return $file;
                }
                @unlink($path);
            }
            $config = get_config('backup');
            if ((int)($config->backup_auto_storage ?? 0) !== 0 && !empty($config->backup_auto_destination)) {
                $root = realpath($config->backup_auto_destination);
                if ($root && is_dir($root) && is_readable($root)) {
                    foreach (new \DirectoryIterator($root) as $entry) {
                        if (!$entry->isFile() || $entry->isLink() || $entry->getMTime() < $since ||
                                strpos($entry->getFilename(), 'backup-moodle2-course-' . $courseid . '-') !== 0 ||
                                strtolower($entry->getExtension()) !== 'mbz') {
                            continue;
                        }
                        if (self::matches($entry->getPathname(), $dir, $backupid, $courseid)) {
                            return $entry->getPathname();
                        }
                    }
                }
            }
            return null;
        } finally {
            remove_dir($dir);
        }
    }

    /**
     * Read only Moodle's manifest from the archive and match its event and course.
     *
     * @param string $archive Archive path.
     * @param string $dir Temporary directory.
     * @param string $backupid Backup ID.
     * @param int $courseid Course ID.
     * @return bool
     */
    public static function matches(string $archive, string $dir, string $backupid, int $courseid): bool {
        $manifest = $dir . '/moodle_backup.xml';
        @unlink($manifest);
        try {
            $packer = get_file_packer('application/vnd.moodle.backup');
            $packer->extract_to_pathname($archive, $dir, ['moodle_backup.xml']);
            if (!is_file($manifest) || filesize($manifest) > 16777216) {
                return false;
            }
            $content = file_get_contents($manifest);
            if ($content === false || stripos($content, '<!DOCTYPE') !== false) {
                return false;
            }
            $xml = @simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NONET);
            return $xml !== false && (int)$xml->information->original_course_id === $courseid &&
                (string)$xml->information->details->detail['backup_id'] === $backupid;
        } catch (\Throwable $e) {
            return false;
        } finally {
            @unlink($manifest);
        }
    }

    /**
     * Produce a unique, deterministic and restore-compatible filename per destination.
     *
     * @param \stdClass $job Transfer record.
     * @param int $storageid Storage ID.
     * @return string
     */
    public static function filename(\stdClass $job, int $storageid): string {
        return 'backup-moodle2-course-' . $job->courseid . '-auto-' . $job->backupid . '-' . $storageid . '.mbz';
    }

    /**
     * Process transfers and scoped retention; scheduled-task locking serializes cron runs.
     */
    public static function run(): void {
        global $DB;
        $settings = self::settings();
        if (empty($settings['enabled'])) {
            return;
        }
        $jobs = $DB->get_records_select(self::TABLE, 'status = :status AND nextattempt <= :now',
            ['status' => 'pending', 'now' => time()], 'nextattempt ASC, id ASC', '*', 0, 10);
        foreach ($jobs as $job) {
            try {
                self::transfer($job);
            } catch (\Throwable $e) {
                mtrace('BackupFTP: transfer retry pending for backup ' . $job->backupid);
            }
        }
        // Recover a staged reference if cron stopped after recording completion but before deleting it.
        $sql = "SELECT f.id
                  FROM {files} f
                  JOIN {local_backupftp_auto} a ON a.id = f.itemid
                 WHERE f.contextid = :contextid AND f.component = :component AND f.filearea = :area
                   AND a.status IN ('complete', 'expired')";
        $staged = $DB->get_records_sql($sql, [
            'contextid' => \context_system::instance()->id, 'component' => 'local_backupftp', 'area' => self::AREA,
        ], 0, 100);
        $fs = get_file_storage();
        foreach ($staged as $record) {
            $file = $fs->get_file_by_id($record->id);
            if ($file) {
                $file->delete();
            }
        }
        $days = (int)$settings['days'];
        if ($days > 0) {
            $expired = $DB->get_records_select(self::TABLE,
                'status = :status AND timecompleted <= :cutoff AND nextattempt <= :now',
                ['status' => 'complete', 'cutoff' => time() - $days * DAYSECS, 'now' => time()],
                'timecompleted ASC, id ASC', '*', 0, 50);
            foreach ($expired as $job) {
                self::expire($job);
            }
        }
    }

    /**
     * Process destinations independently, retaining the staged copy until every upload succeeds.
     *
     * @param \stdClass $job Transfer record.
     */
    private static function transfer(\stdClass $job): void {
        global $DB;
        $job->nextattempt = time() + 1800;
        $DB->update_record(self::TABLE, $job);
        $fs = get_file_storage();
        $file = $fs->get_file(\context_system::instance()->id, 'local_backupftp', self::AREA, $job->id, '/', 'backup.mbz');
        if (!$file) {
            $job->status = 'capturefailed';
            $DB->update_record(self::TABLE, $job);
            return;
        }
        $destinations = json_decode($job->destinations, true);
        $dir = make_request_directory();
        try {
            $source = $dir . '/backup.mbz';
            if (!$file->copy_content_to($source)) {
                return;
            }
            foreach ($destinations as &$destination) {
                if ($destination['path'] !== '') {
                    continue;
                }
                try {
                    $provider = self::destination($destination);
                    if (!$provider) {
                        continue;
                    }
                    $logs = [];
                    $destination['path'] = $provider->store_automatic(
                        $source, self::filename($job, $destination['id']), $job->courseid, $logs);
                    $job->destinations = json_encode($destinations);
                    $DB->update_record(self::TABLE, $job);
                } catch (\Throwable $e) {
                    mtrace('BackupFTP: upload pending for backup ' . $job->backupid . ', destination ' . $destination['id']);
                }
            }
            unset($destination);
            foreach ($destinations as $destination) {
                if ($destination['path'] === '') {
                    return;
                }
            }
            $job->status = 'complete';
            $job->timecompleted = time();
            $job->nextattempt = 0;
            $DB->update_record(self::TABLE, $job);
            $fs->delete_area_files(\context_system::instance()->id, 'local_backupftp', self::AREA, $job->id);
        } finally {
            remove_dir($dir);
        }
    }

    /**
     * Load an unchanged, active destination, failing closed after storage reconfiguration.
     *
     * @param array $destination Saved destination.
     * @return \local_backupftp\storage\provider|null
     */
    private static function destination(array $destination) {
        $records = self::storages();
        $record = $records[$destination['id']] ?? null;
        if (!$record || !hash_equals($destination['hash'], self::fingerprint($record))) {
            return null;
        }
        return manager::from_record($record);
    }

    /**
     * Remove only confirmed automatic copies recorded by this plugin.
     *
     * @param \stdClass $job Transfer record.
     */
    private static function expire(\stdClass $job): void {
        global $DB;
        $job->nextattempt = time() + DAYSECS;
        $DB->update_record(self::TABLE, $job);
        $destinations = json_decode($job->destinations, true);
        foreach ($destinations as &$destination) {
            if ($destination['deleted']) {
                continue;
            }
            try {
                $provider = self::destination($destination);
                if (!$provider || basename($destination['path']) !== self::filename($job, $destination['id'])) {
                    continue;
                }
                if ($provider->delete_automatic($destination['path'])) {
                    $destination['deleted'] = true;
                    $job->destinations = json_encode($destinations);
                    $DB->update_record(self::TABLE, $job);
                }
            } catch (\Throwable $e) {
                mtrace('BackupFTP: retention pending for backup ' . $job->backupid . ', destination ' . $destination['id']);
            }
        }
        unset($destination);
        foreach ($destinations as $destination) {
            if (!$destination['deleted']) {
                return;
            }
        }
        $job->status = 'expired';
        $DB->update_record(self::TABLE, $job);
    }
}
