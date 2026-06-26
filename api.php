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
 * Public transfer API authenticated by a short lived token.
 *
 * Examples:
 *   /local/backupftp/api.php?action=courses&token=TOKEN
 *   /local/backupftp/api.php?action=course&id=12&token=TOKEN
 *   /local/backupftp/api.php?action=categories&token=TOKEN
 *   /local/backupftp/api.php?action=category&id=3&token=TOKEN
 *   /local/backupftp/api.php?action=backups&token=TOKEN
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_backupftp\api;
use local_backupftp\token;

require(__DIR__ . '/../../config.php');

require_once($CFG->dirroot . '/course/lib.php');

@header('Content-Type: application/json; charset=utf-8');
@header('Cache-Control: no-store, no-cache, must-revalidate');

try {
    $action = optional_param('action', 'info', PARAM_ALPHANUMEXT);
    $requesttoken = token::get_request_token();
    $tokenrecord = null;

    if ($action !== 'info') {
        $tokenrecord = token::require_valid_token(true);
    }

    switch ($action) {
        case 'info':
            local_backupftp_api_response([
                'plugin' => 'local_backupftp',
                'actions' => [
                    'courses',
                    'course',
                    'categories',
                    'category',
                    'backups',
                ],
                'auth' => 'Use token=TOKEN or Authorization: Bearer TOKEN',
                'tokenlifetime' => token::get_lifetime(),
            ]);
            break;

        case 'courses':
            local_backupftp_api_response(api::list_courses(), $tokenrecord);
            break;

        case 'course':
            local_backupftp_api_response(api::get_course(), $tokenrecord);
            break;

        case 'categories':
            local_backupftp_api_response(api::list_categories(), $tokenrecord);
            break;

        case 'category':
            local_backupftp_api_response(api::get_category(), $tokenrecord);
            break;

        case 'backups':
            local_backupftp_api_response(api::list_backups($requesttoken), $tokenrecord);
            break;

        default:
            local_backupftp_api_error('invalid_action', get_string('api_invalid_action', 'local_backupftp'), 400);
    }
} catch (required_capability_exception $e) {
    local_backupftp_api_error('forbidden', $e->getMessage(), 403);
} catch (moodle_exception $e) {
    $code = ($e->errorcode === 'token_invalid_or_expired') ? 403 : 400;
    local_backupftp_api_error($e->errorcode, $e->getMessage(), $code);
} catch (Throwable $e) {
    local_backupftp_api_error('internal_error', $e->getMessage(), 500);
}

/**
 * Send a JSON success response.
 *
 * @param mixed $data Payload.
 * @param stdClass|null $tokenrecord Token record.
 * @return void
 */
function local_backupftp_api_response($data, ?stdClass $tokenrecord = null): void {
    $response = [
        'success' => true,
        'time' => time(),
        'data' => $data,
    ];

    if ($tokenrecord) {
        $response['token'] = [
            'id' => (int)$tokenrecord->id,
            'name' => $tokenrecord->name,
            'timeexpires' => (int)$tokenrecord->timeexpires,
        ];
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Send a JSON error response.
 *
 * @param string $error Error code.
 * @param string $message Error message.
 * @param int $httpcode HTTP status code.
 * @return void
 */
function local_backupftp_api_error(string $error, string $message, int $httpcode = 400): void {
    http_response_code($httpcode);
    echo json_encode([
        'success' => false,
        'time' => time(),
        'error' => $error,
        'message' => $message,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}
