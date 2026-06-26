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
 * Transfer API helper.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp;

use context_course;
use context_coursecat;
use core_course_category;
use moodle_url;
use stdClass;

/**
 * Data builder for the transfer API.
 */
class api {

    /**
     * List Moodle courses.
     *
     * @return array
     * @throws \dml_exception
     */
    public static function list_courses(): array {
        global $DB;

        $categoryid = optional_param('categoryid', 0, PARAM_INT);
        $search = optional_param('search', '', PARAM_TEXT);
        $visible = optional_param('visible', -1, PARAM_INT);
        $limit = self::get_limit();
        $offset = self::get_offset();

        $params = ['siteid' => SITEID];
        $where = ['c.id <> :siteid'];

        if ($categoryid > 0) {
            $where[] = 'c.category = :categoryid';
            $params['categoryid'] = $categoryid;
        }

        if ($visible === 0 || $visible === 1) {
            $where[] = 'c.visible = :visible';
            $params['visible'] = $visible;
        }

        if ($search !== '') {
            $where[] = '(' . $DB->sql_like('c.fullname', ':searchfullname', false) . ' OR ' .
                $DB->sql_like('c.shortname', ':searchshortname', false) . ')';
            $params['searchfullname'] = '%' . $DB->sql_like_escape($search) . '%';
            $params['searchshortname'] = '%' . $DB->sql_like_escape($search) . '%';
        }

        $sql = 'SELECT c.id, c.category, cc.name AS categoryname, c.sortorder, c.fullname, c.shortname, c.idnumber,
                       c.summary, c.summaryformat, c.format, c.showgrades, c.newsitems, c.startdate, c.enddate,
                       c.visible, c.enablecompletion, c.lang, c.timecreated, c.timemodified
                  FROM {course} c
             LEFT JOIN {course_categories} cc ON cc.id = c.category
                 WHERE ' . implode(' AND ', $where) . '
              ORDER BY cc.sortorder, c.sortorder, c.fullname';

        $records = $DB->get_records_sql($sql, $params, $offset, $limit);
        $courses = [];

        foreach ($records as $record) {
            $courses[] = self::course_record_to_array($record, false);
        }

        return [
            'count' => count($courses),
            'limit' => $limit,
            'offset' => $offset,
            'items' => $courses,
        ];
    }

    /**
     * Return one course with more data.
     *
     * @return array
     * @throws \dml_exception
     */
    public static function get_course(): array {
        global $DB;

        $id = optional_param('id', 0, PARAM_INT);
        $shortname = optional_param('shortname', '', PARAM_RAW_TRIMMED);

        if ($id > 0) {
            $course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
        } else {
            $course = $DB->get_record('course', ['shortname' => $shortname], '*', MUST_EXIST);
        }

        $category = $DB->get_record('course_categories', ['id' => $course->category], '*', IGNORE_MISSING);

        $data = self::course_record_to_array($course, true);
        $data['category'] = $category ? self::category_record_to_array($category, false) : null;
        $data['backupjobs'] = self::get_course_backup_jobs((int)$course->id);

        return $data;
    }

    /**
     * List course categories.
     *
     * @return array
     * @throws \dml_exception
     */
    public static function list_categories(): array {
        global $DB;

        $parent = optional_param('parent', -1, PARAM_INT);
        $limit = self::get_limit(1000, 2000);
        $offset = self::get_offset();

        $params = [];
        $where = '1=1';
        if ($parent >= 0) {
            $where = 'parent = :parent';
            $params['parent'] = $parent;
        }

        $records = $DB->get_records_select('course_categories', $where, $params, 'sortorder, name', '*', $offset, $limit);
        $items = [];
        foreach ($records as $record) {
            $items[] = self::category_record_to_array($record, true);
        }

        return [
            'count' => count($items),
            'limit' => $limit,
            'offset' => $offset,
            'items' => $items,
        ];
    }

    /**
     * Return category data.
     *
     * @return array
     * @throws \dml_exception
     */
    public static function get_category(): array {
        global $DB;

        $id = required_param('id', PARAM_INT);
        $category = $DB->get_record('course_categories', ['id' => $id], '*', MUST_EXIST);
        $data = self::category_record_to_array($category, true);

        $children = $DB->get_records('course_categories', ['parent' => $id], 'sortorder, name', 'id, name, parent, visible, coursecount');
        $data['children'] = [];
        foreach ($children as $child) {
            $data['children'][] = self::category_record_to_array($child, false);
        }

        $courses = $DB->get_records('course', ['category' => $id], 'sortorder, fullname',
            'id, fullname, shortname, idnumber, category, visible, startdate, enddate, timecreated, timemodified');
        $data['courses'] = [];
        foreach ($courses as $course) {
            $data['courses'][] = self::course_record_to_array($course, false);
        }

        return $data;
    }

    /**
     * List local backup files and backup queue rows.
     *
     * @param string $requesttoken Plain request token, used to build direct download URLs.
     * @return array
     * @throws \dml_exception
     */
    public static function list_backups(string $requesttoken = ''): array {
        global $DB;

        $files = self::list_local_backup_files($requesttoken);

        $limit = self::get_limit();
        $offset = self::get_offset();

        $sql = 'SELECT lbc.id, lbc.courseid, c.fullname AS coursefullname, c.shortname AS courseshortname,
                       c.category, cc.name AS categoryname, lbc.status, lbc.logs,
                       lbc.timecreated, lbc.timestart, lbc.timeend
                  FROM {local_backupftp_course} lbc
             LEFT JOIN {course} c ON c.id = lbc.courseid
             LEFT JOIN {course_categories} cc ON cc.id = c.category
              ORDER BY lbc.timecreated DESC';

        $queue = [];
        $records = $DB->get_records_sql($sql, [], $offset, $limit);
        foreach ($records as $record) {
            $queue[] = [
                'id' => (int)$record->id,
                'courseid' => (int)$record->courseid,
                'coursefullname' => $record->coursefullname,
                'courseshortname' => $record->courseshortname,
                'categoryid' => isset($record->category) ? (int)$record->category : null,
                'categoryname' => $record->categoryname,
                'status' => $record->status,
                'logs' => $record->logs,
                'timecreated' => (int)$record->timecreated,
                'timestart' => (int)$record->timestart,
                'timeend' => (int)$record->timeend,
            ];
        }

        return [
            'localpath' => localfilepath::get_path(),
            'files' => $files,
            'queue' => $queue,
        ];
    }

    /**
     * Convert course DB record to API array.
     *
     * @param stdClass $record Course record.
     * @param bool $full Include more fields.
     * @return array
     */
    private static function course_record_to_array(stdClass $record, bool $full): array {
        global $CFG;

        $contextid = null;
        if (!empty($record->id) && (int)$record->id !== SITEID) {
            try {
                $contextid = context_course::instance((int)$record->id)->id;
            } catch (\Exception $e) {
                $contextid = null;
            }
        }

        $data = [
            'id' => (int)$record->id,
            'fullname' => $record->fullname ?? '',
            'shortname' => $record->shortname ?? '',
            'idnumber' => $record->idnumber ?? '',
            'categoryid' => isset($record->category) ? (int)$record->category : null,
            'categoryname' => $record->categoryname ?? null,
            'visible' => isset($record->visible) ? (int)$record->visible : null,
            'startdate' => isset($record->startdate) ? (int)$record->startdate : null,
            'enddate' => isset($record->enddate) ? (int)$record->enddate : null,
            'timecreated' => isset($record->timecreated) ? (int)$record->timecreated : null,
            'timemodified' => isset($record->timemodified) ? (int)$record->timemodified : null,
            'url' => new moodle_url('/course/view.php', ['id' => (int)$record->id]),
        ];

        if ($full) {
            $data += [
                'summary' => $record->summary ?? '',
                'summaryformat' => isset($record->summaryformat) ? (int)$record->summaryformat : null,
                'format' => $record->format ?? '',
                'showgrades' => isset($record->showgrades) ? (int)$record->showgrades : null,
                'newsitems' => isset($record->newsitems) ? (int)$record->newsitems : null,
                'enablecompletion' => isset($record->enablecompletion) ? (int)$record->enablecompletion : null,
                'lang' => $record->lang ?? '',
                'contextid' => $contextid,
                'wwwroot' => $CFG->wwwroot,
            ];
        }

        return $data;
    }

    /**
     * Convert category record to API array.
     *
     * @param stdClass $record Category record.
     * @param bool $full Include more fields.
     * @return array
     */
    private static function category_record_to_array(stdClass $record, bool $full): array {
        $contextid = null;
        try {
            $contextid = context_coursecat::instance((int)$record->id)->id;
        } catch (\Exception $e) {
            $contextid = null;
        }

        $data = [
            'id' => (int)$record->id,
            'name' => $record->name ?? '',
            'idnumber' => $record->idnumber ?? '',
            'parent' => isset($record->parent) ? (int)$record->parent : null,
            'visible' => isset($record->visible) ? (int)$record->visible : null,
            'coursecount' => isset($record->coursecount) ? (int)$record->coursecount : null,
            'sortorder' => isset($record->sortorder) ? (int)$record->sortorder : null,
        ];

        if ($full) {
            $pathids = [];
            if (!empty($record->path)) {
                foreach (explode('/', trim($record->path, '/')) as $id) {
                    if ((int)$id > 0) {
                        $pathids[] = (int)$id;
                    }
                }
            }

            $data += [
                'description' => $record->description ?? '',
                'descriptionformat' => isset($record->descriptionformat) ? (int)$record->descriptionformat : null,
                'theme' => $record->theme ?? null,
                'path' => $record->path ?? '',
                'pathids' => $pathids,
                'depth' => isset($record->depth) ? (int)$record->depth : null,
                'contextid' => $contextid,
            ];
        }

        return $data;
    }

    /**
     * Return backup queue rows for one course.
     *
     * @param int $courseid Course id.
     * @return array
     * @throws \dml_exception
     */
    private static function get_course_backup_jobs(int $courseid): array {
        global $DB;

        $records = $DB->get_records('local_backupftp_course', ['courseid' => $courseid], 'timecreated DESC');
        $items = [];
        foreach ($records as $record) {
            $items[] = [
                'id' => (int)$record->id,
                'status' => $record->status,
                'logs' => $record->logs,
                'timecreated' => (int)$record->timecreated,
                'timestart' => (int)$record->timestart,
                'timeend' => (int)$record->timeend,
            ];
        }

        return $items;
    }

    /**
     * Recursively list local .mbz files.
     *
     * @param string $requesttoken Token used in direct download URL.
     * @return array
     */
    private static function list_local_backup_files(string $requesttoken): array {
        $root = localfilepath::get_path();
        $rootreal = realpath($root);
        if (!$rootreal || !is_dir($rootreal)) {
            return [];
        }

        $limit = self::get_limit(200, 2000);
        $items = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootreal, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $fileinfo) {
            if (count($items) >= $limit) {
                break;
            }
            if (!$fileinfo->isFile() || $fileinfo->isLink()) {
                continue;
            }
            if (\core_text::strtolower($fileinfo->getExtension()) !== 'mbz') {
                continue;
            }

            $fullpath = $fileinfo->getPathname();
            $relpath = substr($fullpath, strlen(rtrim($rootreal, DIRECTORY_SEPARATOR)) + 1);
            $relpath = str_replace(DIRECTORY_SEPARATOR, '/', $relpath);

            $params = ['f' => $relpath];
            if ($requesttoken !== '') {
                $params['token'] = $requesttoken;
            }

            $items[] = [
                'filename' => $fileinfo->getFilename(),
                'relativepath' => $relpath,
                'fullpath' => $fullpath,
                'size' => $fileinfo->getSize(),
                'timemodified' => $fileinfo->getMTime(),
                'downloadurl' => new moodle_url('/local/backupftp/download.php', $params),
            ];
        }

        usort($items, static function(array $a, array $b): int {
            return $b['timemodified'] <=> $a['timemodified'];
        });

        return $items;
    }

    /**
     * Get limit param.
     *
     * @param int $default Default value.
     * @param int $max Maximum value.
     * @return int
     */
    private static function get_limit(int $default = 200, int $max = 500): int {
        $limit = optional_param('limit', $default, PARAM_INT);
        if ($limit < 1) {
            $limit = $default;
        }
        if ($limit > $max) {
            $limit = $max;
        }

        return $limit;
    }

    /**
     * Get offset param.
     *
     * @return int
     */
    private static function get_offset(): int {
        $offset = optional_param('offset', 0, PARAM_INT);
        return max(0, $offset);
    }
}
