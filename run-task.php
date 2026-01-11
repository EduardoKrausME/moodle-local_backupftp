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
 * Manual task runner (web).
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_backupftp\task\backup_course;
use local_backupftp\task\restore_course;

require('../../config.php');

$CFG->debug = 32767;
$CFG->debugdisplay = 1;
ini_set("display_errors", "1");
ini_set("log_errors", "1");
session_write_close();
ignore_user_abort(true);

global $PAGE, $OUTPUT;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/backupftp/run-task.php'));
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('restore_report', 'local_backupftp'));
$PAGE->set_heading(get_string('restore_report', 'local_backupftp'));

require_login();
require_capability('local/backupftp:manage', $context);

echo $OUTPUT->header();

$action = optional_param('action', '', PARAM_ALPHA);
$nun = optional_param('nun', 0, PARAM_INT);
if ($nun < 1) {
    $nun = 0;
}
if ($nun > 50) {
    $nun = 50;
}

if ($action === 'backup' || $action === 'restore') {
    require_sesskey();

    \core\session\manager::write_close();
    ignore_user_abort(true);
    core_php_time_limit::raise(300);

    ob_start();
    try {
        $limit = $nun ?: 30;
        if ($action === 'backup') {
            (new backup_course())->execute($limit);
        } else {
            (new restore_course())->execute($limit);
        }
    } catch (Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
    $raw = ob_get_clean();
    $text = trim(strip_tags($raw));

    echo html_writer::tag('h3', get_string('cron', 'local_backupftp'));
    echo html_writer::tag('pre', s($text), ['style' => 'max-height:70vh;overflow:auto;']);

    echo html_writer::tag(
        'p',
        html_writer::link(new moodle_url('/local/backupftp/run-task.php'), get_string('back')),
        ['style' => 'margin-top: 1rem;']
    );

    echo $OUTPUT->footer();
    exit;
}

$base = new moodle_url('/local/backupftp/run-task.php', ['sesskey' => sesskey()]);

echo $OUTPUT->render_from_template('local_backupftp/run-task', [
    'backup5url' => (new moodle_url($base, ['action' => 'backup', 'nun' => 5]))->out(false),
    'restore5url' => (new moodle_url($base, ['action' => 'restore', 'nun' => 5]))->out(false),
    'backup10url' => (new moodle_url($base, ['action' => 'backup', 'nun' => 10]))->out(false),
    'restore10url' => (new moodle_url($base, ['action' => 'restore', 'nun' => 10]))->out(false),
]);

echo $OUTPUT->footer();
