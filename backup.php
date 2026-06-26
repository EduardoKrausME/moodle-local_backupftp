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
                ['class' => 'alert alert-info']
            );
        }
    }
}

// Navigation.
echo local_backupftp_render_action_cards();

// Form.
echo html_writer::start_tag('form', ['method' => 'post', 'action' => $PAGE->url->out(false)]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
echo html_writer::tag('h2', get_string('categories', 'local_backupftp'));
echo html_writer::tag('p', get_string('backup_category_select_help', 'local_backupftp'), ['class' => 'text-muted']);

echo html_writer::start_div('local-backupftp-tree', ['data-region' => 'local-backupftp-tree']);
echo local_backupftp_render_tree_toolbar();
echo local_backupftp_categorias(0);
echo html_writer::end_div();

echo html_writer::empty_tag('input', [
    'type' => 'submit',
    'class' => 'btn btn-primary mt-3',
    'value' => get_string('submit', 'local_backupftp'),
]);
echo html_writer::end_tag('form');

echo $OUTPUT->footer();

/**
 * Render page action cards.
 *
 * @return string
 */
function local_backupftp_render_action_cards(): string {
    $reporturl = new moodle_url('/local/backupftp/report-backup.php');
    $taskurl = new moodle_url('/local/backupftp/run-task.php');

    $html = html_writer::start_div('local-backupftp-page-links');

    $html .= html_writer::start_div('local-backupftp-action-card');
    $html .= html_writer::tag('h3', get_string('backup_report', 'local_backupftp'));
    $html .= html_writer::tag('p', get_string('view_backup_report', 'local_backupftp'));
    $html .= html_writer::link($reporturl, get_string('report', 'local_backupftp'), ['class' => 'btn btn-outline-primary btn-sm']);
    $html .= html_writer::end_div();

    $html .= html_writer::start_div('local-backupftp-action-card');
    $html .= html_writer::tag('h3', get_string('manual_cron_title', 'local_backupftp'));
    $html .= html_writer::tag('p', get_string('manual_cron_desc', 'local_backupftp'));
    $html .= html_writer::link($taskurl, get_string('manual_cron_button', 'local_backupftp'), ['class' => 'btn btn-outline-secondary btn-sm']);
    $html .= html_writer::end_div();

    $html .= html_writer::end_div();

    return $html;
}


/**
 * Render select/deselect toolbar for the whole tree.
 *
 * @return string
 */
function local_backupftp_render_tree_toolbar(): string {
    $html = html_writer::start_div('local-backupftp-tree-toolbar local-backupftp-tree-actions');
    $html .= html_writer::tag('button', get_string('select_all', 'local_backupftp'), [
        'type' => 'button',
        'class' => 'btn btn-sm btn-outline-primary',
        'data-action' => 'local-backupftp-select-all',
    ]);
    $html .= html_writer::tag('button', get_string('deselect_all', 'local_backupftp'), [
        'type' => 'button',
        'class' => 'btn btn-sm btn-outline-secondary',
        'data-action' => 'local-backupftp-deselect-all',
    ]);
    $html .= html_writer::end_div();

    return $html;
}

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
    global $DB;

    $context = context_system::instance();
    $categoryid = (int)$category->id;
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

    $statushtml = '';
    foreach ($statusrows as $row) {
        $statushtml .= html_writer::tag(
            'span',
            s($row->status) . ': ' . (int)$row->linhas,
            ['class' => 'badge badge-info']
        );
    }

    $name = format_string($category->name, true, ['context' => $context]);
    $children = local_backupftp_categorias($categoryid);

    $checkbox = html_writer::empty_tag('input', [
        'type' => 'checkbox',
        'id' => $inputid,
        'name' => "category[{$categoryid}]",
        'value' => $categoryid,
    ]);

    $html = html_writer::start_div('local-backupftp-tree-node', ['data-region' => 'local-backupftp-node']);
    $html .= html_writer::start_div('local-backupftp-tree-card');

    $html .= html_writer::start_div('local-backupftp-tree-header');
    $html .= html_writer::start_div('local-backupftp-tree-title');
    $html .= $checkbox;
    $html .= html_writer::tag('span', '▣', ['class' => 'local-backupftp-tree-icon', 'aria-hidden' => 'true']);
    $html .= html_writer::start_div();
    $html .= html_writer::tag('h4', html_writer::tag('label', s($name), ['for' => $inputid]));
    $html .= html_writer::start_div('local-backupftp-tree-meta');
    $html .= html_writer::tag('span', get_string('courses', 'local_backupftp') . ': ' . $coursecount, [
        'class' => 'badge badge-secondary',
    ]);
    $html .= $statushtml;
    $html .= html_writer::end_div();
    $html .= html_writer::end_div();
    $html .= html_writer::end_div();

    $html .= html_writer::start_div('local-backupftp-tree-actions');
    $html .= html_writer::tag('button', get_string('select_all', 'local_backupftp'), [
        'type' => 'button',
        'class' => 'btn btn-sm btn-outline-primary',
        'data-action' => 'local-backupftp-select-all',
    ]);
    $html .= html_writer::tag('button', get_string('deselect_all', 'local_backupftp'), [
        'type' => 'button',
        'class' => 'btn btn-sm btn-outline-secondary',
        'data-action' => 'local-backupftp-deselect-all',
    ]);
    $html .= html_writer::end_div();
    $html .= html_writer::end_div();

    if ($children !== '') {
        $html .= html_writer::start_div('local-backupftp-tree-body');
        $html .= html_writer::start_div('local-backupftp-tree-children');
        $html .= $children;
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
    }

    $html .= html_writer::end_div();
    $html .= html_writer::end_div();

    return $html;
}
