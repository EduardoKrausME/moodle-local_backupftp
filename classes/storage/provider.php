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
 * Storage provider interface.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp\storage;

/**
 * Base contract implemented by each storage subplugin.
 */
abstract class provider {
    /** @var \stdClass Storage destination database record. */
    protected $record;

    /** @var array Instance-specific configuration. */
    protected $config;

    /**
     * Initialize a storage provider from its configured destination.
     *
     * @param \stdClass $record Configured destination.
     */
    public function __construct(\stdClass $record) {
        $this->record = $record;
        $config = json_decode((string) $record->config, true);
        $this->config = is_array($config) ? $config : [];
    }

    /**
     * Return the localized provider name.
     *
     * @return string Provider display name.
     */
    abstract public static function get_label(): string;

    /**
     * Register the provider-specific configuration fields.
     *
     * @param \MoodleQuickForm $form Configuration form.
     */
    abstract public static function add_fields(\MoodleQuickForm $form): void;

    /**
     * Provide default storage settings.
     *
     * @return array Default configuration values.
     */
    abstract public static function defaults(): array;

    /**
     * Validate and normalize an instance configuration.
     *
     * @param array $data Submitted configuration.
     * @return array Sanitized configuration.
     */
    abstract public static function normalize(array $data): array;

    /**
     * Get the configured storage root.
     *
     * @return string Root path for the destination.
     */
    abstract public function root(): string;

    /**
     * Store a generated backup in this destination.
     *
     * @param string $source Local MBZ file.
     * @param string $filename Filename supplied by Moodle.
     * @param int $courseid Course id.
     * @param array $logs By-reference log.
     * @return bool
     */
    abstract public function store(string $source, string $filename, int $courseid, array &$logs): bool;

    /**
     * List available MBZ files.
     *
     * @return array<int,array{path:string,size:int,modified:string}>
     */
    abstract public function list_files(): array;

    /**
     * Check whether a file belongs to this destination.
     *
     * @param string $path Provider-specific path.
     * @return bool
     */
    abstract public function owns(string $path): bool;

    /**
     * Copy a selected MBZ into the restore staging area.
     *
     * @param string $path Provider-specific path.
     * @param string $destination Local temporary path.
     * @param array $logs By-reference log.
     * @return bool
     */
    abstract public function fetch(string $path, string $destination, array &$logs): bool;

    /**
     * Return current provider configuration.
     *
     * @return array
     */
    public function configuration(): array {
        return $this->config;
    }

    /**
     * Return the configured destination record.
     *
     * @return \stdClass
     */
    public function record(): \stdClass {
        return $this->record;
    }

    /**
     * Return shared category directory components (also used by local provider).
     *
     * @param int $courseid Course id.
     * @return array
     */
    protected function course_directories(int $courseid): array {
        global $DB;
        if (empty($this->config['organize'])) {
            return [];
        }
        $course = $DB->get_record('course', ['id' => $courseid], 'id,category', MUST_EXIST);
        $categories = [];
        $categoryid = (int) $course->category;
        while ($categoryid) {
            $category = $DB->get_record('course_categories', ['id' => $categoryid], 'name,parent', MUST_EXIST);
            $part = trim(str_replace(['/', '\\'], '.', $category->name));
            if ($part !== '' && $part !== '.' && $part !== '..') {
                $categories[] = $part;
            }
            $categoryid = (int) $category->parent;
        }
        return array_reverse($categories);
    }

    /**
     * Return a safe backup filename.
     *
     * @param string $filename Filename provided by Moodle.
     * @param int $courseid Course id.
     * @return string
     */
    protected function filename(string $filename, int $courseid): string {
        global $DB;
        if (!empty($this->config['names'])) {
            $course = $DB->get_record('course', ['id' => $courseid], 'id,fullname', MUST_EXIST);
            $filename = $course->id . ' - ' . $course->fullname . '.mbz';
        }
        $name = \local_backupftp\util\file::remove_accents(str_replace(['/', '\\'], '.', $filename));
        if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) !== 'mbz') {
            $name .= '.mbz';
        }
        return $name;
    }
}
