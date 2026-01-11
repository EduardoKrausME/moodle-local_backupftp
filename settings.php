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
            $CFG->wwwroot . '/local/backupftp/index.php'
        )
    );
}

if (!is_siteadmin()) {
    return;
}

$settings->add(new admin_setting_heading('local_backupftp/heading1',
    get_string('settings_mbz_settings', 'local_backupftp'), ''));

$settings->add(new admin_setting_configcheckbox('local_backupftp/settingrootusers',
    get_string('settings_rootsettingusers', 'local_backupftp'), '', 1));

$settings->add(new admin_setting_configcheckbox('local_backupftp/settingrootanonymize',
    get_string('settings_rootsettinganonymize', 'local_backupftp'), '', 0));

$settings->add(new admin_setting_configcheckbox('local_backupftp/ftporganize',
    get_string('settings_ftporganize', 'local_backupftp'),
    get_string('settings_ftporganize_desc', 'local_backupftp'),
    1
));

$settings->add(new admin_setting_configcheckbox('local_backupftp/ftpnames',
    get_string('settings_ftpnames', 'local_backupftp'),
    get_string('settings_ftpnames_desc', 'local_backupftp'),
    1
));

$settings->add(new admin_setting_heading('local_backupftp/settings_local',
    get_string('settings_local', 'local_backupftp'), ''));

$settings->add(new admin_setting_configcheckbox('local_backupftp/localfileenable',
    get_string('settings_localfile', 'local_backupftp'),
    get_string('settings_localfile_desc', 'local_backupftp'),
    1
));

$settings->add(new admin_setting_configtext('local_backupftp/localfilepath',
    get_string('settings_localfilepath', 'local_backupftp'),
    get_string('settings_localfilepath_desc', 'local_backupftp'),
    ''
));

$settings->add(new admin_setting_heading('local_backupftp/settings_ftp',
    get_string('settings_ftp', 'local_backupftp'), ''));

$settings->add(new admin_setting_configcheckbox('local_backupftp/ftpenable',
    get_string('settings_ftpenable', 'local_backupftp'), '', 1));

$settings->add(new admin_setting_configtext('local_backupftp/ftpurl',
    get_string('settings_ftpurl', 'local_backupftp'),
    get_string('settings_ftpurl_desc', 'local_backupftp'),
    ''
));

$settings->add(new admin_setting_configcheckbox('local_backupftp/ftppasv',
    get_string('settings_ftppasv', 'local_backupftp'),
    get_string('settings_ftppasv_desc', 'local_backupftp'),
    1
));

$settings->add(new admin_setting_configtext('local_backupftp/ftpusername',
    get_string('settings_ftpusername', 'local_backupftp'), '', ''));

// Harden: password field.
$settings->add(new admin_setting_configpasswordunmask('local_backupftp/ftppassword',
    get_string('settings_ftppassword', 'local_backupftp'), '', ''));

$settings->add(new admin_setting_configtext('local_backupftp/ftppasta',
    get_string('settings_ftppasta', 'local_backupftp'),
    get_string('settings_ftppasta_desc', 'local_backupftp'),
    ''
));

$settings->add(new admin_setting_heading('local_backupftp/heading2',
    get_string('settings_restore_settings', 'local_backupftp'), ''));

$settings->add(new admin_setting_configtext('local_backupftp/categorystart',
    get_string('settings_categorystart', 'local_backupftp'),
    get_string('settings_categorystart_desc', 'local_backupftp'),
    0,
    PARAM_INT
));
