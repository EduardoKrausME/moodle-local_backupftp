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
 * Backup page (queue backup jobs for courses inside selected categories).
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

global $DB, $PAGE, $OUTPUT;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/backupftp/backup.php'));
$PAGE->set_pagelayout("admin");
$PAGE->set_title(get_string('backup_courses_and_categories', 'local_backupftp'));
$PAGE->set_heading(get_string('backup_courses_and_categories', 'local_backupftp'));
$PAGE->requires->js_call_amd('local_backupftp/categoryselector', 'init');

require_login();
require_capability('local/backupftp:manage', $context);

echo $OUTPUT->header();

// Handle POST: add courses from selected categories to backup queue.
$categoryids = optional_param_array('category', [], PARAM_INT);
if (!empty($categoryids)) {
    require_sesskey();

    foreach ($categoryids as $categoryid) {
        $courses = $DB->get_records('course', ['category' => $categoryid], 'id', 'id,fullname,category');
        foreach ($courses as $course) {
            $courseid = $course->id;
            $coursename = format_string($course->fullname, true, ['context' => context_course::instance($courseid)]);

            $exists = $DB->record_exists_select(
                'local_backupftp_course',
                'courseid = :courseid AND status = :status',
                ['courseid' => $courseid, 'status' => 'waiting']
            );

            if ($exists) {
                continue;
            }

            $data = (object) [
                'courseid' => $courseid,
                'status' => 'waiting',
                'logs' => '',
                'timecreated' => time(),
                'timestart' => 0,
                'timeend' => 0,
            ];
            $DB->insert_record('local_backupftp_course', $data);

            echo html_writer::tag(
                'p',
                get_string('course_added_to_backup_queue', 'local_backupftp', [
                    'course_id' => $courseid,
                    'course_name' => $coursename,
                ]),
                ['class' => 'alert alert-info']
            );
        }
    }
}

// Navigation.
echo $OUTPUT->render_from_template('local_backupftp/backup_action_cards', [
    'reporturl' => (new moodle_url('/local/backupftp/report-backup.php'))->out(false),
    'taskurl' => (new moodle_url('/local/backupftp/run-task.php'))->out(false),
]);

// Form.
echo $OUTPUT->render_from_template('local_backupftp/backup_form', [
    'actionurl' => $PAGE->url->out(false),
    'sesskey' => sesskey(),
    'tree' => $OUTPUT->render_from_template('local_backupftp/backup_tree_toolbar', []),
    'categorias' => local_backupftp_categorias(0),
]);

echo $OUTPUT->footer();

/**
 * Render nested category selector.
 *
 * @param int $parentid Parent category id.
 * @return string
 */
function local_backupftp_categorias(int $parentid): string {
    global $DB;

    $categories = $DB->get_records('course_categories', ['parent' => $parentid], 'sortorder', 'id,name,parent');
    if (!$categories) {
        return '';
    }

    $out = '';
    foreach ($categories as $category) {
        $out .= local_backupftp_render_category_node($category);
    }

    return $out;
}

/**
 * Render a single category node.
 *
 * @param stdClass $category Category record.
 * @return string
 */
function local_backupftp_render_category_node(stdClass $category): string {
    global $DB, $OUTPUT;

    $context = context_system::instance();
    $categoryid = $category->id;
    $unique = uniqid('lbfcat_');
    $inputid = 'id-' . $unique;

    $coursecount = $DB->count_records('course', ['category' => $categoryid]);
    $statusrows = $DB->get_records_sql(
        "SELECT status, COUNT(1) AS linhas
           FROM {local_backupftp_course}
          WHERE courseid IN (SELECT c.id FROM {course} c WHERE c.category = :category)
       GROUP BY status
       ORDER BY status",
        ['category' => $categoryid]
    );

    $statuslist = [];
    foreach ($statusrows as $row) {
        $statuslist[] = [
            'label' => $row->status . ': ' . (int) $row->linhas,
        ];
    }

    $name = format_string($category->name, true, ['context' => $context]);
    $children = local_backupftp_categorias($categoryid);

    return $OUTPUT->render_from_template('local_backupftp/backup_category_node', [
        'categoryid' => $categoryid,
        'inputid' => $inputid,
        'name' => $name,
        'coursecount' => $coursecount,
        'statuses' => $statuslist,
        'haschildren' => $children !== '',
        'children' => $children,
    ]);
}
