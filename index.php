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
 * Index page.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/backupftp/lib.php');

global $PAGE, $OUTPUT;

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url("/local/backupftp/"));
$PAGE->set_pagelayout("admin");
$PAGE->set_title(get_string('courses_and_categories', 'local_backupftp'));
$PAGE->set_heading(get_string('courses_and_categories', 'local_backupftp'));

require_login();
require_capability('local/backupftp:manage', $context);

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_backupftp/index', local_backupftp_get_index_context());
echo $OUTPUT->footer();
