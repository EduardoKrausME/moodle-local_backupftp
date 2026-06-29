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
 * Transfer token helper.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp;

use moodle_exception;
use stdClass;

/**
 * Helper for short lived transfer tokens.
 */
class token {

    /**
     * Token table name.
     */
    public const TABLE = 'local_backupftp_token';

    /**
     * Default token lifetime: 48 hours.
     */
    public const DEFAULT_LIFETIME = 172800;

    /**
     * Create a new transfer token.
     *
     * The plain token is returned only once. Only the SHA-256 hash is stored.
     *
     * @param string $name Friendly token name.
     * @return array{token:string,record:stdClass}
     * @throws \dml_exception
     */
    public static function create(string $name = ''): array {
        global $DB;

        self::cleanup_expired();

        $plain = self::generate_plain_token();
        $now = time();

        $record = (object)[
            'name' => self::clean_name($name),
            'tokenhash' => self::hash_token($plain),
            'timecreated' => $now,
            'timeexpires' => $now + self::get_lifetime(),
            'lastused' => 0,
            'downloadcount' => 0,
            'revoked' => 0,
        ];

        $record->id = $DB->insert_record(self::TABLE, $record);

        return [
            'token' => $plain,
            'record' => $record,
        ];
    }

    /**
     * Revoke a token.
     *
     * @param int $id Token id.
     * @return bool
     * @throws \dml_exception
     */
    public static function revoke(int $id): bool {
        global $DB;

        if (!$DB->record_exists(self::TABLE, ['id' => $id])) {
            return false;
        }

        $DB->update_record(self::TABLE, (object)[
            'id' => $id,
            'revoked' => 1,
        ]);

        return true;
    }

    /**
     * Validate a token and return its record.
     *
     * @param string $plain Plain token.
     * @param bool $touch Update last used/download counter.
     * @return stdClass|null
     * @throws \dml_exception
     */
    public static function validate(string $plain, bool $touch = true): ?stdClass {
        global $DB;

        $plain = trim($plain);
        if ($plain === '') {
            return null;
        }

        $record = $DB->get_record(self::TABLE, [
            'tokenhash' => self::hash_token($plain),
            'revoked' => 0,
        ]);

        if (!$record) {
            return null;
        }

        if ((int)$record->timeexpires < time()) {
            return null;
        }

        if ($touch) {
            $record->lastused = time();
            $record->downloadcount = ((int)$record->downloadcount) + 1;
            $DB->update_record(self::TABLE, $record);
        }

        return $record;
    }

    /**
     * Validate token from request or throw a Moodle exception.
     *
     * @param bool $touch Update last used/download counter.
     * @return stdClass
     * @throws moodle_exception
     * @throws \dml_exception
     */
    public static function require_valid_token(bool $touch = true): stdClass {
        $plain = self::get_request_token();
        $record = self::validate($plain, $touch);
        if (!$record) {
            throw new moodle_exception('token_invalid_or_expired', 'local_backupftp');
        }

        return $record;
    }

    /**
     * Return token supplied by query/body param or Authorization Bearer header.
     *
     * @return string
     */
    public static function get_request_token(): string {
        $token = optional_param('token', '', PARAM_RAW_TRIMMED);
        if ($token !== '') {
            return $token;
        }

        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        }

        foreach ($headers as $name => $value) {
            if (strtolower($name) === 'authorization' && preg_match('/^Bearer\s+(.+)$/i', trim($value), $matches)) {
                return trim($matches[1]);
            }
        }

        if (!empty($_SERVER['HTTP_AUTHORIZATION']) && preg_match('/^Bearer\s+(.+)$/i', trim($_SERVER['HTTP_AUTHORIZATION']), $matches)) {
            return trim($matches[1]);
        }

        if (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && preg_match('/^Bearer\s+(.+)$/i', trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']), $matches)) {
            return trim($matches[1]);
        }

        return '';
    }

    /**
     * Return configured lifetime in seconds.
     *
     * @return int
     */
    public static function get_lifetime(): int {
        $lifetime = (int)get_config('local_backupftp', 'tokenduration');
        if ($lifetime < 3600) {
            $lifetime = self::DEFAULT_LIFETIME;
        }

        return $lifetime;
    }

    /**
     * Remove expired/revoked tokens older than 7 days.
     *
     * @return void
     * @throws \dml_exception
     */
    public static function cleanup_expired(): void {
        global $DB;

        $cutoff = time() - WEEKSECS;
        $DB->delete_records_select(self::TABLE,
            '(timeexpires < :cutoffexpired) OR (revoked = 1 AND timecreated < :cutoffrevoked)',
            [
                'cutoffexpired' => $cutoff,
                'cutoffrevoked' => $cutoff,
            ]
        );
    }



    /**
     * Try to detect the public IP address of this server.
     *
     * This value is only a helper for migrations where DNS has already been
     * pointed to the new Moodle. It first tries small public IP services and
     * falls back to the web server address when external calls are unavailable.
     *
     * @return string
     */
    public static function get_public_ip(): string {
        global $CFG;

        $services = [
            'https://api.ipify.org',
            'https://checkip.amazonaws.com',
            'https://ifconfig.me/ip',
        ];

        if (function_exists('curl_init')) {
            foreach ($services as $service) {
                $ch = curl_init($service);
                if (!$ch) {
                    continue;
                }

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
                curl_setopt($ch, CURLOPT_TIMEOUT, 4);
                curl_setopt($ch, CURLOPT_USERAGENT, 'local_backupftp_ip_detector/1.0');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

                $body = curl_exec($ch);
                $httpcode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpcode >= 200 && $httpcode < 300 && is_string($body)) {
                    $ip = trim($body);
                    if (filter_var($ip, FILTER_VALIDATE_IP)) {
                        return $ip;
                    }
                }
            }
        }

        $candidates = [];
        if (!empty($_SERVER['SERVER_ADDR'])) {
            $candidates[] = $_SERVER['SERVER_ADDR'];
        }
        if (!empty($CFG->wwwroot)) {
            $host = parse_url($CFG->wwwroot, PHP_URL_HOST);
            if (!empty($host)) {
                $resolved = gethostbyname($host);
                if ($resolved && $resolved !== $host) {
                    $candidates[] = $resolved;
                }
            }
        }

        foreach ($candidates as $candidate) {
            $candidate = trim((string)$candidate);
            if (filter_var($candidate, FILTER_VALIDATE_IP)) {
                return $candidate;
            }
        }

        return '';
    }

    /**
     * Generate plain token.
     *
     * @return string
     */
    private static function generate_plain_token(): string {
        if (function_exists('random_bytes')) {
            return 'lbf_' . bin2hex(random_bytes(32));
        }

        return 'lbf_' . random_string(64);
    }

    /**
     * Hash token.
     *
     * @param string $plain Plain token.
     * @return string
     */
    private static function hash_token(string $plain): string {
        return hash('sha256', trim($plain));
    }

    /**
     * Clean token name.
     *
     * @param string $name Name.
     * @return string
     */
    private static function clean_name(string $name): string {
        $name = trim(str_replace(["\r", "\n", "\t", chr(0)], ' ', $name));
        if ($name === '') {
            $name = get_string('transfer_token_default_name', 'local_backupftp');
        }

        if (\core_text::strlen($name) > 255) {
            $name = \core_text::substr($name, 0, 255);
        }

        return $name;
    }
}
