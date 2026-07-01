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
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Access the course</a>';
$string['adding_to_category'] = 'It will be added to the category {$a->categoria}';
$string['already_added_status'] = 'Already added and the status is {$a->status}';
$string['api_invalid_action'] = 'Invalid API action.';
$string['backup_category_select_help'] = 'Select the categories whose courses should be added to the backup queue. The buttons in each card affect that category and all subcategories inside it.';
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
$string['course'] = 'Course';
$string['course_added_to_backup_queue'] = 'Course {$a->course_id} ({$a->course_name}) added to backup queue.';
$string['courses'] = 'Courses';
$string['courses_and_categories'] = 'Courses and Categories';
$string['created_at'] = 'Created at';
$string['created_on'] = 'Created on';
$string['created_on_time'] = 'Created on {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'Deselect all';
$string['error_creating_folder'] = '<span style="color:#d10707">Error creating folder</span> "<b>{$a->ftppasta}</b>" on FTP with error "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'Error downloading the MBZ file, with error "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Error extracting the MBZ file';
$string['file_added_to_restore_queue'] = 'File {$a->file} added to the restore queue';
$string['file_found_and_downloaded'] = 'File located and downloaded';
$string['file_size'] = 'with size {$a->size}';
$string['file_size_label'] = 'File size';
$string['file_uploaded'] = 'File "<b>{$a->file}</b>" uploaded to "<b>{$a->remote_file}</b>"!';
$string['ftp_error_connecting'] = 'Error connecting to FTP';
$string['ftp_error_login'] = 'Unable to connect with {$a->username}@{$a->url}';
$string['ftp_remote_file_size'] = 'FTP returned that the remote file has "<b>{$a->size} bytes</b>"';
$string['index_backup_button'] = 'Open backup screen';
$string['index_backup_desc'] = 'Use this area to select courses and categories and place backup generation in the queue. Generated files can be saved locally and/or sent to FTP, according to the plugin settings.';
$string['index_backup_report_button'] = 'View backup report';
$string['index_backup_title'] = 'Course backup';
$string['index_flow_step1_after_old_moodle'] = 'generate or update the backups of the courses that will be transferred.';
$string['index_flow_step2_after_mbz'] = 'downloads only while it is valid.';
$string['index_flow_step2_after_token_before_mbz'] = 'It enables the API and';
$string['index_flow_step2_before_token'] = 'Still in the old Moodle, create a';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'open the restore screen and enter the old';
$string['index_flow_step3_after_wwwroot'] = ', the token and, if necessary, the old machine IP.';
$string['index_flow_step4'] = 'Check the list returned by the API and send the files to the queue. The cron will download and restore the courses in the background.';
$string['index_flow_step_moodle'] = 'In the';
$string['index_intro_desc'] = 'This plugin helps migrate courses from one Moodle installation to another with more security. The old Moodle generates the backups and grants access through a temporary token. The new Moodle queries the API, lists the available files and places the restores in the queue for cron execution.';
$string['index_new_moodle'] = 'new Moodle';
$string['index_old_moodle'] = 'old Moodle';
$string['index_recommended_flow_title'] = 'Recommended flow';
$string['index_reports_desc'] = 'Use the reports to track what has already been placed in the queue, what is being processed, which backups were completed and which restores need attention.';
$string['index_reports_title'] = 'Reports and monitoring';
$string['index_restore_button'] = 'Open restore screen';
$string['index_restore_desc_after_wwwroot'] = ', the token and, optionally, the old machine IP when the domain has already been migrated to the new server.';
$string['index_restore_desc_before_wwwroot'] = 'Use this screen to import backups from another Moodle. Enter the old';
$string['index_restore_queue_desc'] = 'After the query, the remote files are placed in the restore queue. This way, the migration can continue through cron without depending on the page being open in the browser.';
$string['index_restore_report_button'] = 'View restore report';
$string['index_restore_title'] = 'Restore in the new Moodle';
$string['index_title'] = 'Course transfer between Moodles';
$string['index_token_time_desc'] = 'The token has a limited lifetime, configured on this administration page. Before starting a large migration, confirm that the cron of the new Moodle is active and that the remaining token time is sufficient to download all required backups.';
$string['index_token_time_title'] = 'Pay attention to the token lifetime';
$string['index_tokens_button'] = 'Manage tokens';
$string['index_tokens_desc_after_mbz'] = 'The token does not replace an administrative login: it should be shared only during the migration window.';
$string['index_tokens_desc_before_mbz'] = 'Create temporary tokens to allow another Moodle to query courses, categories, backups and download';
$string['index_transfer_token'] = 'transfer token';
$string['log:savelocal:error'] = 'Failed to save backup locally: {$a}';
$string['log:savelocal:success'] = 'Backup saved locally: {$a}';
$string['logs'] = 'Logs';
$string['manual_cron_button'] = 'Open manual execution';
$string['manual_cron_desc'] = 'Use this page to process queued backups or restores now, test the task manually, or speed up a migration without waiting for the next scheduled Moodle CRON cycle.';
$string['manual_cron_title'] = 'Manual CRON execution';
$string['mbz_extracted_successfully'] = 'MBZ extracted successfully';
$string['modulename'] = 'Backup FTP/Local';
$string['nothing_to_execute'] = 'Nothing to execute';
$string['pluginname'] = 'Backup FTP/Local';
$string['pre_check_failure'] = 'Pre-check failed';
$string['privacy:metadata'] = 'The local_backupftp plugin does not collect or store personal data or any other sensitive data. It only uses the provided FTP configurations to perform backups, without logging or retaining information related to users or the data being transferred.';
$string['processing_file'] = 'Processing: <b>{$a->remote_file}</b> with {$a->size}';
$string['remote_file'] = 'Remote File';
$string['report'] = 'Report';
$string['reports'] = 'Reports';
$string['requeue_backup'] = 'Re-send';
$string['requeue_backup_confirm'] = 'Re-send this backup? It will be reset and put back in the queue.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Course already exists</a>';
$string['restore_courses_and_categories'] = 'Restore: Courses and Categories';
$string['restore_file_select_help'] = 'Select the MBZ files that should be added to the restore queue. The buttons in each category affect only that branch.';
$string['restore_report'] = 'Restore Report';
$string['runtask_backup_desc'] = 'Processes courses already placed in the backup queue and generates/sends the configured MBZ files.';
$string['runtask_backup_title'] = 'Run backup queue manually';
$string['runtask_execute_five_courses'] = 'Process up to 5 items now';
$string['runtask_execute_ten_courses'] = 'Process up to 10 items now';
$string['runtask_manual_desc'] = 'This page runs the same backup and restore tasks that the Moodle CRON normally executes. It is useful when you want to process the queue manually, validate the configuration, or speed up a migration by running a small batch immediately.';
$string['runtask_manual_note'] = 'manual execution does not replace the scheduled Moodle CRON. Keep the normal CRON active so the queue continues to process automatically.';
$string['runtask_manual_note_title'] = 'Important:';
$string['runtask_restore_desc'] = 'Processes MBZ files already placed in the restore queue and restores them into Moodle.';
$string['runtask_restore_title'] = 'Run restore queue manually';
$string['select_all'] = 'Select all';
$string['settings_categorystart'] = 'Root Category ID';
$string['settings_categorystart_desc'] = 'The ID of the root category to start restoring courses';
$string['settings_error'] = 'and error';
$string['settings_error_sending_backup'] = 'Error sending backup to';
$string['settings_file_size'] = 'with file size';
$string['settings_ftp'] = 'FTP storage';
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
$string['settings_local'] = 'Local storage';
$string['settings_localfile'] = 'Save backups to a local folder';
$string['settings_localfile_desc'] = 'If enabled, a copy of the backups will be stored in a local folder specified below.';
$string['settings_localfilepath'] = 'Path to local backup folder';
$string['settings_localfilepath_desc'] = 'Enter the full path of the folder where backups will be stored locally. Ensure the server has write permissions for this folder. If left blank, backups will be saved in [MOODLEDATA]/backup/';
$string['settings_mbz_settings'] = 'Backup Generation Settings';
$string['settings_restore_settings'] = 'Restore Settings';
$string['settings_rootsettinganonymize'] = 'Anonymize Root Users';
$string['settings_rootsettingusers'] = 'Root Users Setting';
$string['settings_tokenduration'] = 'Token lifetime';
$string['settings_tokenduration_desc'] = 'How long each generated transfer token remains valid. The default is 48 hours.';
$string['settings_transfer_api'] = 'Course transfer API';
$string['settings_transfer_api_desc'] = 'Short-lived tokens allow another Moodle site to list courses, categories and backups, and download MBZ files.';
$string['status'] = 'Status';
$string['submit'] = 'Submit';
$string['temporary_files_deleted'] = 'Temporary files deleted';
$string['token_invalid_or_expired'] = 'Invalid or expired transfer token.';
$string['transfer_restore_clear_session_button'] = 'Clear remote data';
$string['transfer_restore_curl_required'] = 'The PHP cURL extension is required to transfer backups from another Moodle.';
$string['transfer_restore_desc'] = 'Use this option to pull the backup list from the previous Moodle. The form data is saved in your session and the files are only placed in the restore queue after you select them.';
$string['transfer_restore_download_too_small'] = 'The downloaded backup file is empty or too small.';
$string['transfer_restore_downloading'] = 'Downloading remote backup from {$a->url}';
$string['transfer_restore_http_error'] = 'Error connecting to the previous Moodle: {$a}';
$string['transfer_restore_http_status'] = 'The previous Moodle returned HTTP status {$a}.';
$string['transfer_restore_invalid_backup_file'] = 'Invalid remote backup file.';
$string['transfer_restore_invalid_json'] = 'The previous Moodle did not return a valid JSON response.';
$string['transfer_restore_ip'] = 'Old server IP (optional)';
$string['transfer_restore_ip_desc'] = 'Use only when the domain has already been migrated to this new Moodle. The request keeps the old wwwroot host, but forces DNS resolution to this IP.';
$string['transfer_restore_ip_invalid'] = 'Invalid old server IP.';
$string['transfer_restore_missing_remote_data'] = 'Missing remote Moodle data to download the backup.';
$string['transfer_restore_no_backups'] = 'No remote backup files were returned by the previous Moodle.';
$string['transfer_restore_no_selection'] = 'Select at least one remote backup file to restore.';
$string['transfer_restore_original_category'] = 'Original category ID/name';
$string['transfer_restore_original_course'] = 'Original course ID/name';
$string['transfer_restore_queue_button'] = 'List remote backups';
$string['transfer_restore_queue_summary'] = 'Remote restore queue updated. New: {$a->queued}. Updated: {$a->updated}. Ignored: {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'The previous Moodle returned an error: {$a}';
$string['transfer_restore_select_file'] = 'Select';
$string['transfer_restore_selected_button'] = 'Restaurar marcados';
$string['transfer_restore_session_cleared'] = 'Remote Moodle data removed from your session.';
$string['transfer_restore_session_saved'] = 'Remote Moodle data saved in your session.';
$string['transfer_restore_session_summary'] = 'Remote backup files found: {$a}. Select the files you want to restore.';
$string['transfer_restore_source'] = 'Source';
$string['transfer_restore_table_limited'] = 'Showing the first 50 of {$a} queued files.';
$string['transfer_restore_tempfile_error'] = 'Could not create the temporary backup file.';
$string['transfer_restore_title'] = 'Restore from another Moodle';
$string['transfer_restore_token'] = 'Transfer token';
$string['transfer_restore_token_counter'] = 'Token validity countdown:';
$string['transfer_restore_token_desc'] = 'Paste the token generated in the previous Moodle under Backup FTP/Local > Transfer tokens.';
$string['transfer_restore_token_remaining_log'] = 'Transfer token still valid for {$a}.';
$string['transfer_restore_token_required'] = 'The transfer token is required.';
$string['transfer_restore_users_failed'] = 'Remote users could not be imported: {$a}';
$string['transfer_restore_users_summary'] = 'Remote users imported. Created: {$a->created}. Updated: {$a->updated}. Ignored: {$a->ignored}. Errors: {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'Previous Moodle wwwroot';
$string['transfer_restore_wwwroot_desc'] = 'Example: https://ead-antigo.instituicao.edu.br. Do not include /local/backupftp.';
$string['transfer_restore_wwwroot_invalid'] = 'Invalid previous Moodle wwwroot.';
$string['transfer_restore_wwwroot_required'] = 'Previous Moodle wwwroot is required.';
$string['transfer_token_create'] = 'Create token';
$string['transfer_token_created_once'] = 'Token created. Copy it now:';
$string['transfer_token_created_once_desc'] = 'For security, the full token is shown only once. After that, only the hash is stored.';
$string['transfer_token_default_name'] = 'Course transfer token';
$string['transfer_token_expired'] = 'Expired';
$string['transfer_token_expired_before_restore'] = 'The transfer token expired before this backup could be restored.';
$string['transfer_token_expires'] = 'Expires';
$string['transfer_token_lastused'] = 'Last used';
$string['transfer_token_name'] = 'Token name';
$string['transfer_token_remaining'] = 'Remaining';
$string['transfer_token_revoke'] = 'Revoked';
$string['transfer_token_revoke_confirm'] = 'Revoke this token? It will no longer be accepted by API or downloads.';
$string['transfer_token_revoked'] = 'Token revoked.';
$string['transfer_token_status_active'] = 'Active';
$string['transfer_token_uses'] = 'Uses';
$string['transfer_tokens'] = 'Transfer tokens';
$string['transfer_tokens_desc'] = 'Tokens authorize the transfer API and MBZ downloads for {$a}. Create a new token when another Moodle site needs temporary access.';
$string['view_backup_report'] = 'Track the backup queue in one place: pending courses, items currently processing, completed backups and records that need attention.';
$string['view_restore_report'] = 'Track the restore queue in one place: selected MBZ files, items currently processing, completed restores and records that need attention.';
