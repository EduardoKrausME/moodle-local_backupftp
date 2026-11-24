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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Generic S3 backup storage.
 *
 * @package backupftpstorage_s3generic
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace backupftpstorage_s3generic;

use backupftpstorage_s3\client;

/**
 * S3-compatible destinations with a configurable HTTPS service endpoint.
 *
 * Backup and restore operations are inherited from the Amazon S3 provider.
 */
class provider extends \backupftpstorage_s3\provider {
    /**
     * Return the provider name.
     *
     * @return string
     */
    public static function get_label(): string {
        return get_string('pluginname', 'backupftpstorage_s3generic');
    }

    /**
     * Register the generic endpoint and common S3 credentials.
     *
     * @param \MoodleQuickForm $form Configuration form.
     */
    public static function add_fields(\MoodleQuickForm $form): void {
        $component = 'backupftpstorage_s3generic';
        foreach (['endpoint', 'region', 'bucket', 'accesskey', 'folder'] as $field) {
            $form->addElement('text', $field, get_string($field, $component));
            $form->setType($field, PARAM_RAW_TRIMMED);
        }
        $form->addElement('select', 'urlstyle', get_string('urlstyle', $component), [
            'auto' => get_string('styleauto', $component),
            'path-style' => get_string('stylepath', $component),
            'virtual-hosted' => get_string('stylevirtual', $component),
        ]);
        $form->addHelpButton('urlstyle', 'urlstyle', $component);
        $form->addElement('password', 'password', get_string('password', $component));
        $form->setType('password', PARAM_RAW);
        $form->addElement('advcheckbox', 'organize', get_string('settings_ftporganize', 'local_backupftp'));
        $form->addElement('advcheckbox', 'names', get_string('settings_ftpnames', 'local_backupftp'));
    }

    /**
     * Return default destination settings.
     *
     * @return array
     */
    public static function defaults(): array {
        $config = parent::defaults();
        unset($config['service']);
        $config['endpoint'] = '';
        $config['urlstyle'] = 'auto';
        return $config;
    }

    /**
     * Normalize credentials and validate the configured service origin.
     *
     * @param array $data Configuration.
     * @return array
     */
    public static function normalize(array $data): array {
        $data = array_replace(self::defaults(), array_intersect_key($data, self::defaults()));
        // Reuse the existing provider's bucket, credential and prefix validation.
        $config = parent::normalize($data + ['service' => 'aws']);
        unset($config['service']);
        $style = (string)$data['urlstyle'];
        $address = client::resolve_endpoint((string)$data['endpoint'], $config['bucket'], $style);
        $config['endpoint'] = $address['endpoint'];
        $config['urlstyle'] = $style;
        return $config;
    }
}
