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
 * Task file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$tasks = [
    [
        "classname" => "\\local_backupftp\\task\\backup_course",
        "blocking" => 1,
        "minute" => "*",
        "hour" => "*",
        "day" => "*",
        "dayofweek" => "*",
        "month" => "*",
    ], [
        "classname" => "\\local_backupftp\\task\\restore_course",
        "blocking" => 1,
        "minute" => "*",
        "hour" => "*",
        "day" => "*",
        "dayofweek" => "*",
        "month" => "*",
    ],
];
