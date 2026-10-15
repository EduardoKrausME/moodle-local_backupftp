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
 * Web service contract comparison page.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

use local_backupftp\service_contract;

$context = \context_system::instance();
require_login();
require_capability('local/backupftp:manage', $context);

$download = optional_param('download', 0, PARAM_BOOL);
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/backupftp/service-contract.php'));
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('servicecontract_title', 'local_backupftp'));
$PAGE->set_heading(get_string('servicecontract_title', 'local_backupftp'));

if ($download) {
    $snapshot = service_contract::snapshot();
    $json = json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES);
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="moodle-service-contract.json"');
    header('Cache-Control: private, no-store');
    header('X-Content-Type-Options: nosniff');
    echo $json;
    exit;
}

$error = '';
$report = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_sesskey();
    try {
        $readupload = static function(string $field, bool $mandatory): ?array {
            $upload = $_FILES[$field] ?? null;
            if (!$upload || $upload['error'] === UPLOAD_ERR_NO_FILE) {
                if ($mandatory) {
                    throw new \invalid_parameter_exception('Missing snapshot');
                }
                return null;
            }
            if ($upload['error'] !== UPLOAD_ERR_OK || $upload['size'] > 8388608 ||
                    !is_uploaded_file($upload['tmp_name'])) {
                throw new \invalid_parameter_exception('Invalid uploaded snapshot');
            }
            $content = file_get_contents($upload['tmp_name']);
            if ($content === false) {
                throw new \invalid_parameter_exception('Unreadable uploaded snapshot');
            }
            return service_contract::decode($content);
        };

        $source = $readupload('sourcesnapshot', true);
        $target = $readupload('targetsnapshot', false) ?? service_contract::snapshot();
        $report = service_contract::compare($source, $target);
    } catch (\invalid_parameter_exception $e) {
        $error = get_string('servicecontract_invalid_snapshot', 'local_backupftp');
    } catch (\Throwable $e) {
        debugging($e->getMessage(), DEBUG_DEVELOPER);
        $error = get_string('servicecontract_unavailable', 'local_backupftp');
    }
}

$downloadurl = new moodle_url('/local/backupftp/service-contract.php', ['download' => 1]);
$contextdata = [
    'downloadurl' => $downloadurl->out(false),
    'formurl' => (new moodle_url('/local/backupftp/service-contract.php'))->out(false),
    'backurl' => (new moodle_url('/local/backupftp/index.php'))->out(false),
    'sesskey' => sesskey(),
    'error' => $error,
];
if ($report !== null) {
    $contextdata['report'] = [
        'sourceversion' => $report['sourceversion'],
        'targetversion' => $report['targetversion'],
        'removed' => $report['counts']['removed'],
        'added' => $report['counts']['added'],
        'changed' => $report['counts']['changed'],
        'unresolved' => $report['counts']['unresolved'],
        'unchanged' => $report['counts']['unchanged'],
        'haschanges' => $report['haschanges'],
        'changes' => array_map(static function(array $change): array {
            $change['statuslabel'] = get_string('servicecontract_status_' . $change['status'], 'local_backupftp');
            return $change;
        }, $report['changes']),
    ];
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_backupftp/service_contract', $contextdata);
echo $OUTPUT->footer();
