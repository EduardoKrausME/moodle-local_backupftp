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
 * Discovery and persistence of configured storage instances.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\storage;

/**
 * Provider discovery uses Moodle's native subplugin registry.
 */
class manager {
    /**
     * Discover registered storage provider subplugins.
     *
     * @return array<string,string>
     */
    public static function types(): array {
        $result = [];
        foreach (\core_component::get_plugin_list('backupftpstorage') as $name => $directory) {
            $class = '\\backupftpstorage_' . $name . '\\provider';
            if (class_exists($class) && is_subclass_of($class, provider::class)) {
                $result[$name] = $class::get_label();
            }
        }
        return $result;
    }

    /**
     * Load storage destinations saved in the database.
     *
     * @return array
     */
    public static function instances(bool $enabledonly = false): array {
        global $DB;
        $conditions = $enabledonly ? ['enabled' => 1] : [];
        return $DB->get_records('local_backupftp_storage', $conditions, 'name ASC, id ASC');
    }

    /**
     * Load a destination by ID.
     *
     * @param int $id Destination id.
     * @return provider
     */
    public static function get(int $id): provider {
        global $DB;
        $record = $DB->get_record('local_backupftp_storage', ['id' => $id], '*', MUST_EXIST);
        return self::from_record($record);
    }

    /**
     * Instantiate the storage provider for a database record.
     *
     * @param \stdClass $record Destination record.
     * @return provider
     */
    public static function from_record(\stdClass $record): provider {
        $types = self::types();
        if (!isset($types[$record->type])) {
            throw new \moodle_exception('storage_type_missing', 'local_backupftp', '', $record->type);
        }
        $class = '\\backupftpstorage_' . $record->type . '\\provider';
        return new $class($record);
    }

    /**
     * Create or update a configured storage destination.
     *
     * @param string $type Provider type.
     * @param string $name Destination name.
     * @param bool $enabled Enabled flag.
     * @param array $settings Sanitized instance settings.
     * @param int $id Existing id, zero for new.
     * @return int
     */
    public static function save(string $type, string $name, bool $enabled, array $settings, int $id = 0): int {
        global $DB;
        if (!isset(self::types()[$type])) {
            throw new \invalid_parameter_exception('Unknown storage provider');
        }
        $class = '\\backupftpstorage_' . $type . '\\provider';
        $existing = $id ? $DB->get_record('local_backupftp_storage', ['id' => $id], '*', MUST_EXIST) : null;
        if ($existing && $existing->type !== $type) {
            throw new \invalid_parameter_exception('Cannot change a storage provider type');
        }
        $config = $existing ? json_decode($existing->config, true) : $class::defaults();
        if (!is_array($config)) {
            $config = [];
        }
        foreach ($settings as $key => $value) {
            if ($key === 'password' && $value === '' && $existing) {
                continue;
            }
            $config[$key] = $value;
        }
        $config = $class::normalize($config);
        $record = (object) [
            'type' => $type,
            'name' => trim($name),
            'enabled' => (int) $enabled,
            'config' => json_encode($config, JSON_THROW_ON_ERROR),
            'timemodified' => time(),
        ];
        if ($record->name === '') {
            throw new \invalid_parameter_exception('Storage name is required');
        }
        if ($existing) {
            $record->id = $id;
            $DB->update_record('local_backupftp_storage', $record);
            return $id;
        }
        $record->timecreated = time();
        return $DB->insert_record('local_backupftp_storage', $record);
    }
}
