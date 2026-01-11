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
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('backup_courses_and_categories', 'local_backupftp'));
$PAGE->set_heading(get_string('backup_courses_and_categories', 'local_backupftp'));

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

            $data = (object)[
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
                ['style' => 'color:#2196F3;font-weight:bold;']
            );
        }
    }
}

// Navigation.
echo html_writer::tag('p',
    get_string('view_backup_report', 'local_backupftp') . ' ' .
    html_writer::link(new moodle_url('/local/backupftp/report-backup.php'), get_string('report', 'local_backupftp'))
);
echo html_writer::tag('p',
    get_string('run_cron', 'local_backupftp') . ' ' .
    html_writer::link(new moodle_url('/local/backupftp/run-task.php'), get_string('cron', 'local_backupftp'))
);

// Form.
echo html_writer::start_tag('form', ['method' => 'post', 'action' => $PAGE->url->out(false)]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
echo html_writer::tag('h2', get_string('categories', 'local_backupftp'));

echo local_backupftp_categorias(0);

echo html_writer::empty_tag('input', [
    'type' => 'submit',
    'value' => get_string('submit', 'local_backupftp'),
]);
echo html_writer::end_tag('form');

echo $OUTPUT->footer();

/**
 * Render nested category selector.
 *
 * @param int $parentid Parent category id.
 * @return string
 */
function local_backupftp_categorias(int $parentid): string {
    global $DB;

    $context = context_system::instance();

    $categories = $DB->get_records('course_categories', ['parent' => $parentid], 'sortorder', 'id,name,parent');
    if (!$categories) {
        return '';
    }

    $unique = uniqid('lbfcat_');
    $fieldsetid = 'id-' . $unique;

    $out = html_writer::start_tag('fieldset', [
        'id' => $fieldsetid,
        'style' => 'border:1px solid #959595;padding:6px;padding-left:50px;margin:3px;',
    ]);

    $out .= html_writer::tag(
        'span',
        get_string('select_deselect_all', 'local_backupftp'),
        [
            'style' => 'float:right;color:#E91E63;',
            'onclick' => "\$('#{$fieldsetid} input').click();",
        ]
    );

    $countcat = 0;

    foreach ($categories as $category) {
        $categoryid = $category->id;

        $count = $DB->count_records('course', ['category' => $categoryid]);
        $countcat += $count;

        $statusrows = $DB->get_records_sql(
            "SELECT status, COUNT(1) AS linhas
               FROM {local_backupftp_course}
              WHERE courseid IN (SELECT c.id FROM {course} c WHERE c.category = :category)
           GROUP BY status
           ORDER BY status",
            ['category' => $categoryid]
        );

        $statusfolder = '';
        foreach ($statusrows as $row) {
            $statusfolder .= ' / ' .
                html_writer::tag('strong', s($row->status) . ':') . ' ' .
                $row->linhas;
        }

        $name = format_string($category->name, true, ['context' => $context]);

        $checkbox = html_writer::empty_tag('input', [
            'type' => 'checkbox',
            'name' => "category[{$categoryid}]",
            'value' => $categoryid,
        ]);

        $label = html_writer::tag('label', html_writer::tag('strong', s($name)));
        $courseslabel = html_writer::tag('strong', get_string('courses', 'local_backupftp') . ':');

        $out .= html_writer::tag('div', $checkbox . ' ' . $label . ' ' . $courseslabel . ' ' . $count . $statusfolder);

        $out .= local_backupftp_categorias($categoryid);
    }

    $out .= html_writer::tag(
        'span',
        get_string('total_in_category', 'local_backupftp', ['total' => $countcat]),
        ['style' => 'color:#2196F3;']
    );

    $out .= html_writer::end_tag('fieldset');
    return $out;
}
