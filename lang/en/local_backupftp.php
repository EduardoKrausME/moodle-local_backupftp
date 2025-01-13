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
 * Lang en file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Access the course</a>';
$string['add_backup'] = 'To add a backup, access';
$string['add_restore'] = 'To add restorations, access';
$string['adding_to_category'] = 'It will be added to the category {$a->categoria}';
$string['already_added_status'] = 'Already added and the status is {$a->status}';
$string['backup_courses_and_categories'] = 'Backup: Courses and categories';
$string['backup_creation_parameters'] = 'Backup will be created with the following parameters';
$string['backup_end'] = 'Backup ended on';
$string['backup_end_time'] = 'Backup End Time';
$string['backup_report'] = 'Backup Report';
$string['backup_start'] = 'Backup started on';
$string['backup_start_time'] = 'Backup Start Time';
$string['backupftp:manage'] = 'Manage backup';
$string['categories'] = 'Categories';
$string['category_created_successfully'] = ' ==> Category {$a->categoria_nome} created successfully';
$string['category_link'] = 'Category <a href="{$a}" target="blank">Root Category</a>';
$string['click_here'] = 'Click here';
$string['created_at'] = 'Created at';
$string['course'] = 'Course';
$string['course_added_to_backup_queue'] = 'Course {$a->course_id} added to the backup queue';
$string['course_already_exists'] = 'Course already exists';
$string['courses'] = 'Courses';
$string['courses_and_categories'] = 'Courses and Categories';
$string['created_on'] = 'Created on';
$string['created_on_time'] = 'Created on {$a->modify}';
$string['cron'] = 'the CRON';
$string['cron_task'] = 'CRON task';
$string['error_creating_folder'] = '<span style="color:#d10707">Error creating folder</span> "<b>{$a->ftppasta}</b>" on FTP with error "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'Error downloading the MBZ file, with error "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Error extracting the MBZ file';
$string['file_added_to_restore_queue'] = 'File {$a->file} added to the restore queue';
$string['file_found_and_downloaded'] = 'File located and downloaded';
$string['file_size'] = 'with size {$a->size}';
$string['file_uploaded'] = 'File "<b>{$a->file}</b>" uploaded to "<b>{$a->remote_file}</b>"!';
$string['ftp_error_connecting'] = 'Error connecting to FTP';
$string['ftp_error_login'] = 'Unable to connect with {$a->username}@{$a->url}';
$string['ftp_files'] = 'Files on FTP';
$string['ftp_remote_file_size'] = 'FTP returned that the remote file has "<b>{$a->size} bytes</b>"';
$string['logs'] = 'Logs';
$string['mbz_extracted_successfully'] = 'MBZ extracted successfully';
$string['modulename'] = 'Backup FTP';
$string['nothing_to_execute'] = 'Nothing to execute';
$string['pluginname'] = 'Backup FTP';
$string['pre_check_failure'] = 'Pre-check failed';
$string['processing_file'] = 'Processing: <b>{$a->remote_file}</b> with {$a->size}';
$string['remote_file'] = 'Remote File';
$string['report'] = 'Report';
$string['reports'] = 'Reports';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Course already exists</a>';
$string['restore_courses_and_categories'] = 'Restore: Courses and Categories';
$string['restore_report'] = 'Restore Report';
$string['run_cron'] = 'Run the';
$string['runtask_execute_one_course'] = 'Execute only 1 course at a time';
$string['runtask_execute_five_courses'] = 'Execute only 5 courses at a time';
$string['runtask_execute_ten_courses'] = 'Execute only 10 courses at a time';
$string['runtask_backup'] = 'To execute the backup';
$string['runtask_restore'] = 'To execute the restore';
$string['runtask_click_here'] = 'Click here';
$string['select_deselect_all'] = 'Select/Deselect All';
$string['send'] = 'Send';
$string['settings_categorystart'] = 'Root Category ID';
$string['settings_categorystart_desc'] = 'The ID of the root category to start restoring courses';
$string['settings_error'] = 'and error';
$string['settings_error_sending_backup'] = 'Error sending backup to';
$string['settings_file_size'] = 'with file size';
$string['settings_ftpenable'] = 'Send to FTP';
$string['settings_ftpnames'] = 'Use course name as backup file name';
$string['settings_ftpnames_desc'] = 'If checked, the file name sent will be the course name. Otherwise, it will be the name Moodle assigns, similar to backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'Organize backups on FTP by categories';
$string['settings_ftporganize_desc'] = 'The file will be saved as Category/Category/course.mbz';
$string['settings_ftppassword'] = 'FTP Password';
$string['settings_ftppasta'] = 'Remote FTP Folder';
$string['settings_ftppasta_desc'] = 'The destination folder must start with / and not end with / (e.g., /backup, /save/backup)';
$string['settings_ftppasv'] = 'Send file in passive mode?';
$string['settings_ftppasv_desc'] = 'The default FTP mode in PHP is active mode. Active mode rarely works due to firewalls/NATs/proxies. Therefore, you almost always need to use passive mode.';
$string['settings_ftpurl'] = 'FTP URL';
$string['settings_ftpurl_desc'] = 'Enter the IP address or hostname of the desired FTP server. If the FTP server port is different from 21, specify it by adding a colon (:) followed by the port number, e.g., 127.0.0.1:29. If your FTP uses SSL, add ftps:// before the domain.';
$string['settings_ftpusername'] = 'FTP Login';
$string['settings_integrations'] = 'Integrations';
$string['settings_localdelete'] = 'Delete local file after sending';
$string['settings_mbz_settings'] = 'MBZ Generation Settings';
$string['settings_restore_settings'] = 'Restore Settings';
$string['settings_rootsettinganonymize'] = 'Anonymize Root Users';
$string['settings_rootsettingusers'] = 'Root Users Setting';
$string['status'] = 'Status';
$string['submit'] = 'Submit';
$string['temporary_files_deleted'] = 'Temporary files deleted';
$string['total_files'] = 'Total files:';
$string['total_in_category'] = 'Total within this category: {$a->total}';
$string['view_backup_report'] = 'To track backups, access the';
$string['view_restore_report'] = 'To track restorations, access the';
