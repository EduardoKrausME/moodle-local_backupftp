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
 * Backup view table.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\report;

use context_system;
use Exception;
use html_writer;
use local_backupftp\localfilepath;
use moodle_url;
use stdClass;
use table_sql;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/tablelib.php');

/**
 * backup_view
 */
class backup_view extends table_sql {

    /**
     * Create a new instance of the sql_table.
     *
     * @param string $uniqueid a string identifying this table.Used as a key in
     *                          session  vars.
     * @throws Exception
     */
    public function __construct(string $uniqueid) {
        parent::__construct($uniqueid);

        $this->is_downloadable(true);
        $this->show_download_buttons_at([TABLE_P_BOTTOM]);

        $download = optional_param('download', null, PARAM_ALPHA);
        if (!empty($download)) {
            raise_memory_limit(MEMORY_EXTRA);
            $filename = get_string('backup_report', 'local_backupftp');
            $this->is_downloading($download, $filename);
        }

        $columns = [
            'course',
            'status',
            'logs',
            'timecreated',
            'timestart',
            'timeend',
            'actions',
        ];

        $headers = [
            get_string('course', 'local_backupftp'),
            get_string('status', 'local_backupftp'),
            get_string('logs', 'local_backupftp'),
            get_string('created_on', 'local_backupftp'),
            get_string('backup_start', 'local_backupftp'),
            get_string('backup_end', 'local_backupftp'),
            get_string('actions'),
        ];

        $this->define_columns($columns);
        $this->define_headers($headers);
    }

    /**
     * col_course
     *
     * @param stdClass $row
     * @return string
     * @throws Exception
     */
    public function col_course(stdClass $row): string {
        $name = '--';
        if (!empty($row->course)) {
            $name = format_string($row->course, true, ['context' => context_system::instance()]);
        }

        if (!empty($row->courseid)) {
            $url = new moodle_url('/course/view.php', ['id' => $row->courseid]);
            return html_writer::link($url, $name);
        }

        return $name;
    }

    /**
     * col_logs
     *
     * @param stdClass $row
     * @return string
     * @throws Exception
     */
    public function col_logs(stdClass $row): string {
        $logs = $row->logs;

        // Download/export: sem HTML, mas com URL em texto se existir.
        if ($this->is_downloading()) {
            $url = $this->get_local_download_url_from_logs($logs);
            if ($url) {
                $logs .= "\n" . get_string('download') . ': ' . $url;
            }
            return $logs;
        }

        $out = nl2br(s($logs));

        $url = $this->get_local_download_url_from_logs($logs);
        if ($url) {
            $out .= '<br>' . html_writer::link($url, get_string('download'), ['class' => 'btn btn-primary']);
        }

        return $out;
    }

    /**
     * col_timecreated
     * 
     * @param stdClass $row
     * @return string
     * @throws Exception
     */
    public function col_timecreated(stdClass $row): string {
        if (empty($row->timecreated)) {
            return '-';
        }
        return userdate($row->timecreated, get_string('strftimedatetimeshort', 'langconfig'));
    }

    /**
     * col_timestart
     * 
     * @param stdClass $row
     * @return string
     * @throws Exception
     */
    public function col_timestart(stdClass $row): string {
        if (empty($row->timestart)) {
            return '-';
        }
        return userdate($row->timestart, get_string('strftimedatetimeshort', 'langconfig'));
    }

    /**
     * col_timeend
     * 
     * @param stdClass $row
     * @return string
     * @throws Exception
     */
    public function col_timeend(stdClass $row): string {
        if (empty($row->timeend)) {
            return '-';
        }
        return userdate($row->timeend, get_string('strftimedatetimeshort', 'langconfig'));
    }

    /**
     * Actions column: "Re-enviar" button (requeue/reset).
     */
    public function col_actions(stdClass $row): string {
        if ($this->is_downloading()) {
            return '';
        }

        $url = new moodle_url('/local/backupftp/report-backup.php', [
            'requeue' => $row->id,
            'sesskey' => sesskey(),
        ]);

        $label = get_string('requeue_backup', 'local_backupftp');
        $confirm = get_string('requeue_backup_confirm', 'local_backupftp');

        return html_writer::link(
            $url,
            $label,
            [
                'class' => 'btn btn-danger btn-sm',
                'onclick' => 'return confirm(' . json_encode($confirm) . ');',
            ]
        );
    }

    /**
     * Query the db. Store results in the table object for use by build_table.
     *
     * @param int $pagesize size of page for paginated displayed table.
     * @param bool $useinitialsbar do you want to use the initials bar. Bar
     * will only be used if there is a fullname column defined for the table.
     * @throws Exception
     */
    public function query_db($pagesize, $useinitialsbar = true): void {
        global $DB;

        $sortable = [
            'course' => 'c.fullname',
            'status' => 'lbc.status',
            'timecreated' => 'lbc.timecreated',
            'timestart' => 'lbc.timestart',
            'timeend' => 'lbc.timeend',
        ];

        $order = $this->get_sort_for_table($this->uniqueid);
        $orderby = 'lbc.timecreated DESC';

        if (!empty($order)) {
            $parts = array_map('trim', explode(',', $order));
            $safe = [];

            foreach ($parts as $part) {
                if (!preg_match('/^([a-z0-9_]+)\s+(ASC|DESC)$/i', $part, $m)) {
                    continue;
                }
                $col = strtolower($m[1]);
                $dir = strtoupper($m[2]);
                if (!isset($sortable[$col])) {
                    continue;
                }
                $safe[] = $sortable[$col] . ' ' . $dir;
            }

            if (!empty($safe)) {
                $orderby = implode(', ', $safe);
            }
        }

        $fields = 'lbc.id, lbc.courseid, c.fullname AS course, lbc.status, lbc.logs, lbc.timecreated, lbc.timestart, lbc.timeend';
        $from = '{local_backupftp_course} lbc LEFT JOIN {course} c ON c.id = lbc.courseid';
        $where = '1=1';

        $sql = "SELECT {$fields} FROM {$from} WHERE {$where} ORDER BY {$orderby}";

        $limit = $this->is_downloading() ? 0 : 200;

        $this->pageable(false);
        if ($useinitialsbar && !$this->is_downloading()) {
            $this->initialbars(true);
        }

        $this->rawdata = $DB->get_recordset_sql($sql, [], 0, $limit);
    }

    /**
     * Tries to detect a local .mbz file path or filename inside the given logs and returns a download URL.
     *
     * @param string $logs Log text (possibly multiline).
     * @return moodle_url|null Download URL if a valid local file is found.
     * @throws Exception
     */
    private function get_local_download_url_from_logs(string $logs): ?moodle_url {
        $localfileenable = get_config('local_backupftp', 'localfileenable');
        if (!$localfileenable) {
            return null;
        }

        $root = localfilepath::get_path();
        $root = str_replace('/', '\/', $root);
        preg_match('/' . $root . '\/(.*?\.mbz)/', $logs, $outputarray);
        if(isset($outputarray[1])){
            return new moodle_url('/local/backupftp/download.php', [
                'f' => $outputarray[1],
                'sesskey' => sesskey(),
            ]);
        }

        return null;
    }
}
