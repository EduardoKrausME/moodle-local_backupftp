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
 * Settings file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    if (!$ADMIN->locate("integracaoroot")) {
        $ADMIN->add("root", new admin_category("integracaoroot", get_string("settings_integrations", "local_backupftp")));
    }

    $ADMIN->add("integracaoroot",
        new admin_externalpage(
            "local_backupftp",
            get_string("modulename", "local_backupftp"),
            "{$CFG->wwwroot}/local/backupftp/index.php"
        )
    );
}

if (is_siteadmin()) {
    $settings = new admin_settingpage("local_backupftp", get_string("pluginname", "local_backupftp"));
    $ADMIN->add("localplugins", $settings);

    $name = "local_backupftp/ftpenable";
    $title = get_string("settings_ftpenable", "local_backupftp");
    $setting = new admin_setting_configcheckbox($name, $title, "", 1);
    $settings->add($setting);

    $name = "local_backupftp/ftpnames";
    $title = get_string("settings_ftpnames", "local_backupftp");
    $description = get_string("settings_ftpnames_desc", "local_backupftp");
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $settings->add($setting);

    $name = "local_backupftp/ftpurl";
    $title = get_string("settings_ftpurl", "local_backupftp");
    $description = get_string("settings_ftpurl_desc", "local_backupftp");
    $setting = new admin_setting_configtext($name, $title, $description, "");
    $settings->add($setting);

    $name = "local_backupftp/ftppasv";
    $title = get_string("settings_ftppasv", "local_backupftp");
    $description = get_string("settings_ftppasv_desc", "local_backupftp");
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $settings->add($setting);

    $name = "local_backupftp/ftpusername";
    $title = get_string("settings_ftpusername", "local_backupftp");
    $setting = new admin_setting_configtext($name, $title, "", "");
    $settings->add($setting);

    $name = "local_backupftp/ftppassword";
    $title = get_string("settings_ftppassword", "local_backupftp");
    $setting = new admin_setting_configtext($name, $title, "", "");
    $settings->add($setting);

    $name = "local_backupftp/ftppasta";
    $title = get_string("settings_ftppasta", "local_backupftp");
    $description = get_string("settings_ftppasta_desc", "local_backupftp");
    $setting = new admin_setting_configtext($name, $title, $description, "");
    $settings->add($setting);

    $name = "local_backupftp/localdelete";
    $title = get_string("settings_localdelete", "local_backupftp");
    $setting = new admin_setting_configcheckbox($name, $title, "", 1);
    $settings->add($setting);

    $name = "local_backupftp/ftporganize";
    $title = get_string("settings_ftporganize", "local_backupftp");
    $description = get_string("settings_ftporganize_desc", "local_backupftp");
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $settings->add($setting);

    $setting = new admin_setting_heading("local_backupftp/heading1",
        get_string("settings_mbz_settings", "local_backupftp"), "");
    $settings->add($setting);

    $name = "local_backupftp/settingsrootusers";
    $title = get_string("settings_rootsettingusers", "local_backupftp");
    $setting = new admin_setting_configcheckbox($name, $title, "", 1);
    $settings->add($setting);

    $name = "local_backupftp/settingsrootanonymize";
    $title = get_string("settings_rootsettinganonymize", "local_backupftp");
    $setting = new admin_setting_configcheckbox($name, $title, "", 0);
    $settings->add($setting);

    $setting = new admin_setting_heading("local_backupftp/heading2",
        get_string("settings_restore_settings", "local_backupftp"), "");
    $settings->add($setting);

    $name = "local_backupftp/categorystart";
    $title = get_string("settings_categorystart", "local_backupftp");
    $description = get_string("settings_categorystart_desc", "local_backupftp");
    $setting = new admin_setting_configtext($name, $title, $description, 0, PARAM_INT);
    $settings->add($setting);
}
