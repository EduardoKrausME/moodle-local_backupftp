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
 * Admin settings.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $OUTPUT;

require_once($CFG->dirroot . '/local/backupftp/lib.php');

$settings = new admin_settingpage('local_backupftp', get_string('pluginname', 'local_backupftp'));
$ADMIN->add('localplugins', $settings);

if ($hassiteconfig) {
    if (!$ADMIN->locate('integracaoroot')) {
        $ADMIN->add('root', new admin_category('integracaoroot', get_string('settings_integrations', 'local_backupftp')));
    }

    $ADMIN->add('integracaoroot',
        new admin_externalpage(
            'local_backupftp2',
            get_string('modulename', 'local_backupftp'),
            $CFG->wwwroot . "/local/backupftp/"
        )
    );

    $ADMIN->add('integracaoroot',
        new admin_externalpage(
            'local_backupftp_tokens',
            get_string('transfer_tokens', 'local_backupftp'),
            $CFG->wwwroot . '/local/backupftp/tokens.php'
        )
    );
}

if (!is_siteadmin()) {
    return;
}

$settings->add(new admin_setting_heading('local_backupftp/indexlinks',
    get_string('courses_and_categories', 'local_backupftp'),
    $OUTPUT->render_from_template('local_backupftp/index', local_backupftp_get_index_context())
));

$settings->add(new admin_setting_heading('local_backupftp/heading1',
    get_string('settings_mbz_settings', 'local_backupftp'), ''));

$settings->add(new admin_setting_configcheckbox('local_backupftp/settingrootusers',
    get_string('settings_rootsettingusers', 'local_backupftp'), '', 1));

$settings->add(new admin_setting_configcheckbox('local_backupftp/settingrootanonymize',
    get_string('settings_rootsettinganonymize', 'local_backupftp'), '', 0));

$settings->add(new admin_setting_heading('local_backupftp/settings_transfer_api',
    get_string('settings_transfer_api', 'local_backupftp'),
    get_string('settings_transfer_api_desc', 'local_backupftp')
));

$settings->add(new admin_setting_configduration('local_backupftp/tokenduration',
    get_string('settings_tokenduration', 'local_backupftp'),
    get_string('settings_tokenduration_desc', 'local_backupftp'),
    604800 // 7 days.
));

$settings->add(new admin_setting_heading('local_backupftp/heading2',
    get_string('settings_restore_settings', 'local_backupftp'), ''));

$settings->add(new admin_setting_configtext('local_backupftp/categorystart',
    get_string('settings_categorystart', 'local_backupftp'),
    get_string('settings_categorystart_desc', 'local_backupftp'),
    0,
    PARAM_INT
));
