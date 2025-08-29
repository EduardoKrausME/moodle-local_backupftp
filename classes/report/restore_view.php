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
 * Restore view file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\report;

use Exception;

defined('MOODLE_INTERNAL') || die;

require_once("{$CFG->libdir}/tablelib.php");

/**
 * Class restore_view
 *
 * @package local_backupftp\report
 */
class restore_view extends \table_sql {

    /**
     * local_backupftp_view constructor.
     *
     * @param $uniqueid
     * @throws Exception
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);

        $this->is_downloadable(true);
        $this->show_download_buttons_at([TABLE_P_BOTTOM]);

        $download = optional_param("download", null, PARAM_ALPHA);
        if ($download) {
            raise_memory_limit(MEMORY_EXTRA);
            $filename = get_string("restore_report", "local_backupftp");
            $this->is_downloading($download, $filename);
        }

        $columns = [
            "remotefile",
            "status",
            "logs",
            "timecreated",
            "timestart",
            "timeend",
        ];
        $headers = [
            get_string("remote_file", "local_backupftp"),
            get_string("status", "local_backupftp"),
            get_string("logs", "local_backupftp"),
            get_string("created_at", "local_backupftp"),
            get_string("backup_start_time", "local_backupftp"),
            get_string("backup_end_time", "local_backupftp"),
        ];

        $this->define_columns($columns);
        $this->define_headers($headers);
    }

    /**
     * Function col_logs
     *
     * @param $linha
     * @return string
     */
    public function col_logs($linha) {
        return str_replace("\n", "<br>", $linha->logs);
    }

    /**
     * Function col_timecreated
     *
     * @param $linha
     * @return string
     * @throws Exception
     */
    public function col_timecreated($linha) {
        return userdate($linha->timecreated, get_string("strftimedatetimeshort", "langconfig"));
    }

    /**
     * Function col_timestart
     *
     * @param $linha
     * @return string
     * @throws Exception
     */
    public function col_timestart($linha) {
        return userdate($linha->timestart, get_string("strftimedatetimeshort", "langconfig"));
    }

    /**
     * Function col_timeend
     *
     * @param $linha
     * @return string
     * @throws Exception
     */
    public function col_timeend($linha) {
        return userdate($linha->timeend, get_string("strftimedatetimeshort", "langconfig"));
    }

    /**
     * Function query_db
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     * @throws Exception
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        global $DB;

        $order = $this->get_sort_for_table($this->uniqueid);
        if (!$order) {
            $order = "timecreated DESC";
        }

        $limit = "LIMIT 200";
        if (optional_param("download", null, PARAM_ALPHA)) {
            $limit = "";
        }

        $this->sql = "
                SELECT *
                  FROM {local_backupftp_restore}
              ORDER BY {$order}
                 {$limit}";

        $this->pageable(false);

        if ($useinitialsbar && !$this->is_downloading()) {
            $this->initialbars(true);
        }

        $this->rawdata = $DB->get_recordset_sql($this->sql, [], $this->get_page_start(), $this->get_page_size());
    }
}
