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
 * Web service API contract snapshot and comparison.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp;

/**
 * Read-only contract comparison to check migration compatibility.
 */
final class service_contract {
    /** Snapshot schema identifier. */
    public const FORMAT = 'local_backupftp_service_contract_v1';

    /**
     * Export the declared external functions on this Moodle installation.
     *
     * No implementation is invoked: only the parameters/returns descriptions are read.
     *
     * @return array
     */
    public static function snapshot(): array {
        global $CFG, $DB;

        require_once($CFG->libdir . '/externallib.php');

        $apiclass = class_exists('\\core_external\\external_api') ?
            '\\core_external\\external_api' : '\\external_api';
        $functions = [];
        $records = $DB->get_records('external_functions', [], 'name ASC');
        foreach ($records as $record) {
            try {
                $info = $apiclass::external_function_info($record);
                $functions[$record->name] = [
                    'status' => 'ok',
                    'parameters' => self::describe($info->parameters_desc),
                    'returns' => self::describe($info->returns_desc),
                ];
            } catch (\Throwable $e) {
                // Keep the function visible, but never mistake an unreadable contract for a valid one.
                $functions[$record->name] = ['status' => 'unresolved'];
            }
        }
        ksort($functions, SORT_STRING);

        return [
            'format' => self::FORMAT,
            'moodle' => [
                'version' => (string) $CFG->version,
                'release' => (string) $CFG->release,
            ],
            'functions' => $functions,
        ];
    }

    /**
     * Represent the public structure without descriptions or default values.
     * Neither documentation strings nor potentially sensitive defaults are exported.
     *
     * @param mixed $description External function description.
     * @param int $depth Recursion depth.
     * @return array
     */
    public static function describe($description, int $depth = 0): array {
        if ($description === null) {
            return ['kind' => 'void'];
        }
        if ($depth > 32 || !is_object($description)) {
            return ['kind' => 'unknown'];
        }

        $result = [
            'required' => isset($description->required) ? (int) $description->required : null,
            'nullable' => isset($description->allownull) ? (bool) $description->allownull : false,
        ];
        if (property_exists($description, 'keys') && is_array($description->keys)) {
            $result['kind'] = 'object';
            $keys = [];
            foreach ($description->keys as $name => $child) {
                $keys[(string) $name] = self::describe($child, $depth + 1);
            }
            ksort($keys, SORT_STRING);
            $result['fields'] = $keys;
        } else if (property_exists($description, 'content')) {
            $result['kind'] = 'list';
            $result['items'] = self::describe($description->content, $depth + 1);
        } else if (property_exists($description, 'type')) {
            $result['kind'] = 'value';
            $result['type'] = (string) $description->type;
        } else {
            $result['kind'] = 'unknown';
        }
        return $result;
    }

    /**
     * Validate a snapshot supplied by a Moodle administrator.
     *
     * @param string $json JSON document.
     * @return array
     */
    public static function decode(string $json): array {
        if (strlen($json) > 8388608) {
            throw new \invalid_parameter_exception('Snapshot exceeds 8 MiB');
        }
        $snapshot = json_decode($json, true, 128);
        if (!is_array($snapshot) || ($snapshot['format'] ?? null) !== self::FORMAT ||
                !isset($snapshot['functions']) || !is_array($snapshot['functions']) ||
                !isset($snapshot['moodle']) || !is_array($snapshot['moodle']) ||
                count($snapshot['functions']) > 20000) {
            throw new \invalid_parameter_exception('Invalid service contract snapshot');
        }
        foreach ($snapshot['functions'] as $name => $function) {
            if (!is_string($name) || !preg_match('/^[a-z][a-z0-9_]*$/D', $name) ||
                    !is_array($function) ||
                    !in_array($function['status'] ?? '', ['ok', 'unresolved'], true)) {
                throw new \invalid_parameter_exception('Invalid service contract function');
            }
            if ($function['status'] === 'ok' &&
                    (!isset($function['parameters'], $function['returns']) ||
                    !is_array($function['parameters']) || !is_array($function['returns']))) {
                throw new \invalid_parameter_exception('Invalid service contract signature');
            }
        }
        return $snapshot;
    }

    /**
     * Compare a source (old Moodle) and target (new Moodle) snapshot.
     * A signature difference is reported for review; it is not automatically
     * labelled a breaking change.
     *
     * @param array $source Source environment snapshot.
     * @param array $target Target environment snapshot.
     * @return array
     */
    public static function compare(array $source, array $target): array {
        $old = $source['functions'];
        $new = $target['functions'];
        $names = array_unique(array_merge(array_keys($old), array_keys($new)));
        sort($names, SORT_STRING);

        $counts = ['removed' => 0, 'added' => 0, 'changed' => 0, 'unresolved' => 0, 'unchanged' => 0];
        $changes = [];
        foreach ($names as $name) {
            $before = $old[$name] ?? null;
            $after = $new[$name] ?? null;
            $fields = [];
            if ($before === null) {
                $status = 'added';
            } else if ($after === null) {
                $status = 'removed';
            } else if ($before['status'] !== 'ok' || $after['status'] !== 'ok') {
                $status = 'unresolved';
            } else {
                foreach (['parameters', 'returns'] as $field) {
                    if (self::canonical($before[$field]) !== self::canonical($after[$field])) {
                        $fields[] = $field;
                    }
                }
                $status = $fields ? 'changed' : 'unchanged';
            }
            ++$counts[$status];
            if ($status !== 'unchanged') {
                $changes[] = [
                    'name' => $name,
                    'status' => $status,
                    'fields' => implode(', ', $fields),
                    'source' => $before === null ? '' : self::pretty($before),
                    'target' => $after === null ? '' : self::pretty($after),
                ];
            }
        }

        return [
            'sourceversion' => (string) ($source['moodle']['release'] ?? ''),
            'targetversion' => (string) ($target['moodle']['release'] ?? ''),
            'counts' => $counts,
            'changes' => $changes,
            'haschanges' => !empty($changes),
        ];
    }

    /**
     * Canonicalize nested associative arrays for deterministic comparisons.
     *
     * @param mixed $value Input.
     * @return string
     */
    private static function canonical($value): string {
        return json_encode(self::sort_recursive($value), JSON_UNESCAPED_SLASHES);
    }

    /**
     * Recursively sort associative arrays.
     *
     * @param mixed $value Input.
     * @return mixed
     */
    private static function sort_recursive($value) {
        if (!is_array($value)) {
            return $value;
        }
        foreach ($value as $key => $item) {
            $value[$key] = self::sort_recursive($item);
        }
        if (array_keys($value) !== range(0, count($value) - 1)) {
            ksort($value, SORT_STRING);
        }
        return $value;
    }

    /**
     * Format a contract signature for display.
     *
     * @param array $function Function definition.
     * @return string
     */
    private static function pretty(array $function): string {
        return (string) json_encode($function, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES);
    }
}
