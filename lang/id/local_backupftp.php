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
 * Lang id file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Akses kursus</a>';
$string['adding_to_category'] = 'Akan ditambahkan ke kategori {$a->categoria}';
$string['already_added_status'] = 'Sudah ditambahkan dan statusnya adalah {$a->status}';
$string['api_invalid_action'] = 'Aksi API tidak valid.';
$string['backup_category_select_help'] = 'Pilih kategori yang kursusnya harus ditambahkan ke antrean backup. Tombol pada setiap kartu memengaruhi kategori tersebut dan semua subkategori di dalamnya.';
$string['backup_courses_and_categories'] = 'Backup: Kursus dan kategori';
$string['backup_creation_parameters'] = 'Backup akan dibuat dengan parameter berikut';
$string['backup_end'] = 'Backup berakhir pada';
$string['backup_end_time'] = 'Waktu selesai backup';
$string['backup_report'] = 'Laporan backup';
$string['backup_start'] = 'Backup dimulai pada';
$string['backup_start_time'] = 'Waktu mulai backup';
$string['backupftp:manage'] = 'Kelola backup';
$string['categories'] = 'Kategori';
$string['category_created_successfully'] = ' ==> Kategori {$a->categoria_nome} berhasil dibuat';
$string['category_link'] = 'Kategori <a href="{$a}" target="blank">Kategori root</a>';
$string['click_here'] = 'Klik di sini';
$string['course'] = 'Kursus';
$string['course_added_to_backup_queue'] = 'Kursus {$a->course_id} ({$a->course_name}) ditambahkan ke antrean backup.';
$string['courses'] = 'Kursus';
$string['courses_and_categories'] = 'Kursus dan kategori';
$string['created_at'] = 'Dibuat pada';
$string['created_on'] = 'Dibuat pada';
$string['created_on_time'] = 'Dibuat pada {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'Batalkan semua pilihan';
$string['error_creating_folder'] = '<span style="color:#d10707">Kesalahan saat membuat folder</span> "<b>{$a->ftppasta}</b>" di FTP dengan kesalahan "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'Kesalahan saat mengunduh file MBZ, dengan kesalahan "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Kesalahan saat mengekstrak file MBZ';
$string['file_added_to_restore_queue'] = 'File {$a->file} ditambahkan ke antrean pemulihan';
$string['file_found_and_downloaded'] = 'File ditemukan dan diunduh';
$string['file_size'] = 'dengan ukuran {$a->size}';
$string['file_size_label'] = 'Ukuran file';
$string['file_uploaded'] = 'File "<b>{$a->file}</b>" diunggah ke "<b>{$a->remote_file}</b>"!';
$string['ftp_error_connecting'] = 'Kesalahan saat terhubung ke FTP';
$string['ftp_error_login'] = 'Tidak dapat terhubung dengan {$a->username}@{$a->url}';
$string['ftp_remote_file_size'] = 'FTP mengembalikan bahwa file jarak jauh memiliki "<b>{$a->size} byte</b>"';
$string['index_backup_button'] = 'Buka layar backup';
$string['index_backup_desc'] = 'Gunakan area ini untuk memilih kursus dan kategori serta menempatkan pembuatan backup ke dalam antrean. File yang dibuat dapat disimpan secara lokal dan/atau dikirim ke FTP sesuai pengaturan plugin.';
$string['index_backup_report_button'] = 'Lihat laporan backup';
$string['index_backup_title'] = 'Backup kursus';
$string['index_flow_step1_after_old_moodle'] = 'buat atau perbarui backup kursus yang akan ditransfer.';
$string['index_flow_step2_after_mbz'] = 'unduhan hanya selama token masih valid.';
$string['index_flow_step2_after_token_before_mbz'] = 'Ini mengaktifkan API dan';
$string['index_flow_step2_before_token'] = 'Masih di Moodle lama, buat sebuah';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'buka layar pemulihan dan masukkan';
$string['index_flow_step3_after_wwwroot'] = ', token dan, jika perlu, IP mesin lama.';
$string['index_flow_step4'] = 'Periksa daftar yang dikembalikan oleh API dan kirim file ke antrean. Cron akan mengunduh dan memulihkan kursus di latar belakang.';
$string['index_flow_step_moodle'] = 'Di';
$string['index_intro_desc'] = 'Plugin ini membantu memigrasikan kursus dari satu instalasi Moodle ke instalasi lainnya dengan lebih aman. Moodle lama membuat backup dan memberikan akses melalui token sementara. Moodle baru menanyakan API, mencantumkan file yang tersedia, dan menempatkan pemulihan ke antrean untuk dieksekusi oleh cron.';
$string['index_new_moodle'] = 'Moodle baru';
$string['index_old_moodle'] = 'Moodle lama';
$string['index_recommended_flow_title'] = 'Alur yang direkomendasikan';
$string['index_reports_desc'] = 'Gunakan laporan untuk melacak apa yang sudah masuk antrean, apa yang sedang diproses, backup mana yang selesai, dan pemulihan mana yang perlu perhatian.';
$string['index_reports_title'] = 'Laporan dan pemantauan';
$string['index_restore_button'] = 'Buka layar pemulihan';
$string['index_restore_desc_after_wwwroot'] = ', token dan, secara opsional, IP mesin lama saat domain sudah dimigrasikan ke server baru.';
$string['index_restore_desc_before_wwwroot'] = 'Gunakan layar ini untuk mengimpor backup dari Moodle lain. Masukkan';
$string['index_restore_queue_desc'] = 'Setelah kueri, file jarak jauh ditempatkan dalam antrean pemulihan. Dengan cara ini, migrasi dapat berlanjut melalui cron tanpa bergantung pada halaman yang terbuka di browser.';
$string['index_restore_report_button'] = 'Lihat laporan pemulihan';
$string['index_restore_title'] = 'Pemulihan di Moodle baru';
$string['index_title'] = 'Transfer kursus antar Moodle';
$string['index_token_time_desc'] = 'Token memiliki masa berlaku terbatas yang dikonfigurasi pada halaman administrasi ini. Sebelum memulai migrasi besar, pastikan cron Moodle baru aktif dan sisa waktu token cukup untuk mengunduh semua backup yang diperlukan.';
$string['index_token_time_title'] = 'Perhatikan masa berlaku token';
$string['index_tokens_button'] = 'Kelola token';
$string['index_tokens_desc_after_mbz'] = 'Token tidak menggantikan login administratif: token hanya boleh dibagikan selama jendela migrasi.';
$string['index_tokens_desc_before_mbz'] = 'Buat token sementara agar Moodle lain dapat menanyakan kursus, kategori, backup, dan mengunduh';
$string['index_transfer_token'] = 'token transfer';
$string['log:savelocal:error'] = 'Gagal menyimpan backup secara lokal: {$a}';
$string['log:savelocal:success'] = 'Backup disimpan secara lokal: {$a}';
$string['logs'] = 'Log';
$string['manual_cron_button'] = 'Buka eksekusi manual';
$string['manual_cron_desc'] = 'Gunakan halaman ini untuk memproses backup atau pemulihan yang sedang antre sekarang, menguji tugas secara manual, atau mempercepat migrasi tanpa menunggu siklus CRON Moodle terjadwal berikutnya.';
$string['manual_cron_title'] = 'Eksekusi CRON manual';
$string['mbz_extracted_successfully'] = 'MBZ berhasil diekstrak';
$string['nothing_to_execute'] = 'Tidak ada yang dijalankan';
$string['pluginname'] = 'Backup FTP/Lokal';
$string['pre_check_failure'] = 'Pra-pemeriksaan gagal';
$string['privacy:metadata'] = 'Plugin local_backupftp tidak mengumpulkan atau menyimpan data pribadi maupun data sensitif lainnya. Plugin ini hanya menggunakan konfigurasi FTP yang diberikan untuk melakukan backup, tanpa mencatat atau menyimpan informasi terkait pengguna atau data yang ditransfer.';
$string['processing_file'] = 'Memproses: <b>{$a->remote_file}</b> dengan {$a->size}';
$string['remote_file'] = 'File jarak jauh';
$string['report'] = 'Laporan';
$string['reports'] = 'Laporan';
$string['requeue_backup'] = 'Kirim ulang';
$string['requeue_backup_confirm'] = 'Kirim ulang backup ini? Backup akan direset dan dimasukkan kembali ke antrean.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Kursus sudah ada</a>';
$string['restore_courses_and_categories'] = 'Pemulihan: Kursus dan kategori';
$string['restore_file_select_help'] = 'Pilih file MBZ yang harus ditambahkan ke antrean pemulihan. Tombol pada setiap kategori hanya memengaruhi cabang tersebut.';
$string['restore_report'] = 'Laporan pemulihan';
$string['runtask_backup_desc'] = 'Memproses kursus yang sudah ditempatkan dalam antrean backup dan membuat/mengirim file MBZ yang dikonfigurasi.';
$string['runtask_backup_title'] = 'Jalankan antrean backup secara manual';
$string['runtask_execute_five_courses'] = 'Proses hingga 5 item sekarang';
$string['runtask_execute_ten_courses'] = 'Proses hingga 10 item sekarang';
$string['runtask_manual_desc'] = 'Halaman ini menjalankan tugas backup dan pemulihan yang sama dengan yang biasanya dijalankan oleh CRON Moodle. Ini berguna saat Anda ingin memproses antrean secara manual, memvalidasi konfigurasi, atau mempercepat migrasi dengan menjalankan batch kecil segera.';
$string['runtask_manual_note'] = 'eksekusi manual tidak menggantikan CRON Moodle terjadwal. Biarkan CRON normal tetap aktif agar antrean terus diproses secara otomatis.';
$string['runtask_manual_note_title'] = 'Penting:';
$string['runtask_restore_desc'] = 'Memproses file MBZ yang sudah ditempatkan dalam antrean pemulihan dan memulihkannya ke Moodle.';
$string['runtask_restore_title'] = 'Jalankan antrean pemulihan secara manual';
$string['select_all'] = 'Pilih semua';
$string['settings_categorystart'] = 'ID kategori root';
$string['settings_categorystart_desc'] = 'ID kategori root untuk memulai pemulihan kursus';
$string['settings_error'] = 'dan kesalahan';
$string['settings_error_sending_backup'] = 'Kesalahan saat mengirim backup ke';
$string['settings_file_size'] = 'dengan ukuran file';
$string['settings_ftp'] = 'Penyimpanan FTP';
$string['settings_ftpenable'] = 'Kirim ke FTP';
$string['settings_ftpnames'] = 'Gunakan nama kursus sebagai nama file backup';
$string['settings_ftpnames_desc'] = 'Jika dicentang, nama file yang dikirim akan menjadi nama kursus. Jika tidak, nama yang diberikan Moodle akan digunakan, mirip dengan backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'Atur backup di FTP berdasarkan kategori';
$string['settings_ftporganize_desc'] = 'File akan disimpan sebagai Kategori/Kategori/course.mbz';
$string['settings_ftppassword'] = 'Kata sandi FTP';
$string['settings_ftppasta'] = 'Folder FTP jarak jauh';
$string['settings_ftppasta_desc'] = 'Folder tujuan harus dimulai dengan / dan tidak diakhiri dengan / (misalnya, /backup, /save/backup)';
$string['settings_ftppasv'] = 'Kirim file dalam mode pasif?';
$string['settings_ftppasv_desc'] = 'Mode FTP default di PHP adalah mode aktif. Mode aktif jarang berfungsi karena firewall/NAT/proxy. Karena itu, hampir selalu perlu menggunakan mode pasif.';
$string['settings_ftpurl'] = 'URL FTP';
$string['settings_ftpurl_desc'] = 'Masukkan alamat IP atau nama host server FTP yang diinginkan. Jika port server FTP berbeda dari 21, tentukan dengan menambahkan titik dua (:) diikuti nomor port, misalnya 127.0.0.1:29. Jika FTP Anda menggunakan SSL, tambahkan ftps:// sebelum domain.';
$string['settings_ftpusername'] = 'Login FTP';
$string['settings_integrations'] = 'Integrasi';
$string['settings_local'] = 'Penyimpanan lokal';
$string['settings_localfile'] = 'Simpan backup ke folder lokal';
$string['settings_localfile_desc'] = 'Jika diaktifkan, salinan backup akan disimpan dalam folder lokal yang ditentukan di bawah.';
$string['settings_localfilepath'] = 'Jalur ke folder backup lokal';
$string['settings_localfilepath_desc'] = 'Masukkan jalur lengkap folder tempat backup akan disimpan secara lokal. Pastikan server memiliki izin tulis untuk folder ini. Jika dibiarkan kosong, backup akan disimpan di [MOODLEDATA]/backup/';
$string['settings_mbz_settings'] = 'Pengaturan pembuatan backup';
$string['settings_restore_settings'] = 'Pengaturan pemulihan';
$string['settings_rootsettinganonymize'] = 'Anonimkan pengguna root';
$string['settings_rootsettingusers'] = 'Pengaturan pengguna root';
$string['settings_tokenduration'] = 'Masa berlaku token';
$string['settings_tokenduration_desc'] = 'Berapa lama setiap token transfer yang dibuat tetap valid. Default adalah 48 jam.';
$string['settings_transfer_api'] = 'API transfer kursus';
$string['settings_transfer_api_desc'] = 'Token berumur pendek memungkinkan situs Moodle lain mencantumkan kursus, kategori, dan backup, serta mengunduh file MBZ.';
$string['status'] = 'Status';
$string['submit'] = 'Kirim';
$string['temporary_files_deleted'] = 'File sementara dihapus';
$string['token_invalid_or_expired'] = 'Token transfer tidak valid atau kedaluwarsa.';
$string['transfer_restore_clear_session_button'] = 'Hapus data jarak jauh';
$string['transfer_restore_curl_required'] = 'Ekstensi PHP cURL diperlukan untuk mentransfer backup dari Moodle lain.';
$string['transfer_restore_desc'] = 'Gunakan opsi ini untuk mengambil daftar backup dari Moodle sebelumnya. Data formulir disimpan dalam sesi Anda dan file hanya ditempatkan dalam antrean pemulihan setelah Anda memilihnya.';
$string['transfer_restore_download_too_small'] = 'File backup yang diunduh kosong atau terlalu kecil.';
$string['transfer_restore_downloading'] = 'Mengunduh backup jarak jauh dari {$a->url}';
$string['transfer_restore_http_error'] = 'Kesalahan saat terhubung ke Moodle sebelumnya: {$a}';
$string['transfer_restore_http_status'] = 'Moodle sebelumnya mengembalikan status HTTP {$a}.';
$string['transfer_restore_invalid_backup_file'] = 'File backup jarak jauh tidak valid.';
$string['transfer_restore_invalid_json'] = 'Moodle sebelumnya tidak mengembalikan respons JSON yang valid.';
$string['transfer_restore_ip'] = 'IP server lama (opsional)';
$string['transfer_restore_ip_desc'] = 'Gunakan hanya saat domain sudah dimigrasikan ke Moodle baru ini. Permintaan mempertahankan host wwwroot lama, tetapi memaksa resolusi DNS ke IP ini.';
$string['transfer_restore_ip_invalid'] = 'IP server lama tidak valid.';
$string['transfer_restore_missing_remote_data'] = 'Data Moodle jarak jauh untuk mengunduh backup tidak lengkap.';
$string['transfer_restore_no_backups'] = 'Tidak ada file backup jarak jauh yang dikembalikan oleh Moodle sebelumnya.';
$string['transfer_restore_no_selection'] = 'Pilih setidaknya satu file backup jarak jauh untuk dipulihkan.';
$string['transfer_restore_original_category'] = 'ID/nama kategori asli';
$string['transfer_restore_original_course'] = 'ID/nama kursus asli';
$string['transfer_restore_queue_button'] = 'Daftar backup jarak jauh';
$string['transfer_restore_queue_summary'] = 'Antrean pemulihan jarak jauh diperbarui. Baru: {$a->queued}. Diperbarui: {$a->updated}. Diabaikan: {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'Moodle sebelumnya mengembalikan kesalahan: {$a}';
$string['transfer_restore_select_file'] = 'Pilih';
$string['transfer_restore_selected_button'] = 'Pulihkan yang dipilih';
$string['transfer_restore_session_cleared'] = 'Data Moodle jarak jauh dihapus dari sesi Anda.';
$string['transfer_restore_session_saved'] = 'Data Moodle jarak jauh disimpan dalam sesi Anda.';
$string['transfer_restore_session_summary'] = 'File backup jarak jauh ditemukan: {$a}. Pilih file yang ingin Anda pulihkan.';
$string['transfer_restore_source'] = 'Sumber';
$string['transfer_restore_table_limited'] = 'Menampilkan 50 pertama dari {$a} file dalam antrean.';
$string['transfer_restore_tempfile_error'] = 'Tidak dapat membuat file backup sementara.';
$string['transfer_restore_title'] = 'Pulihkan dari Moodle lain';
$string['transfer_restore_token'] = 'Token transfer';
$string['transfer_restore_token_counter'] = 'Hitung mundur validitas token:';
$string['transfer_restore_token_desc'] = 'Tempel token yang dibuat di Moodle sebelumnya pada Backup FTP/Local > Token transfer.';
$string['transfer_restore_token_remaining_log'] = 'Token transfer masih valid selama {$a}.';
$string['transfer_restore_token_required'] = 'Token transfer diperlukan.';
$string['transfer_restore_users_failed'] = 'Pengguna jarak jauh tidak dapat diimpor: {$a}';
$string['transfer_restore_users_summary'] = 'Pengguna jarak jauh diimpor. Dibuat: {$a->created}. Diperbarui: {$a->updated}. Diabaikan: {$a->ignored}. Kesalahan: {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'wwwroot Moodle sebelumnya';
$string['transfer_restore_wwwroot_desc'] = 'Contoh: https://ead-antigo.instituicao.edu.br. Jangan sertakan /local/backupftp.';
$string['transfer_restore_wwwroot_invalid'] = 'wwwroot Moodle sebelumnya tidak valid.';
$string['transfer_restore_wwwroot_required'] = 'wwwroot Moodle sebelumnya diperlukan.';
$string['transfer_token_create'] = 'Buat token';
$string['transfer_token_created_once'] = 'Token dibuat. Salin sekarang:';
$string['transfer_token_created_once_desc'] = 'Demi keamanan, token lengkap hanya ditampilkan sekali. Setelah itu, hanya hash yang disimpan.';
$string['transfer_token_default_name'] = 'Token transfer kursus';
$string['transfer_token_expired'] = 'Kedaluwarsa';
$string['transfer_token_expired_before_restore'] = 'Token transfer kedaluwarsa sebelum backup ini dapat dipulihkan.';
$string['transfer_token_expires'] = 'Kedaluwarsa';
$string['transfer_token_lastused'] = 'Terakhir digunakan';
$string['transfer_token_name'] = 'Nama token';
$string['transfer_token_remaining'] = 'Tersisa';
$string['transfer_token_revoke'] = 'Dicabut';
$string['transfer_token_revoke_confirm'] = 'Cabut token ini? Token tidak akan lagi diterima oleh API atau unduhan.';
$string['transfer_token_revoked'] = 'Token dicabut.';
$string['transfer_token_status_active'] = 'Aktif';
$string['transfer_token_uses'] = 'Penggunaan';
$string['transfer_tokens'] = 'Token transfer';
$string['transfer_tokens_desc'] = 'Token mengotorisasi API transfer dan unduhan MBZ untuk {$a}. Buat token baru saat situs Moodle lain membutuhkan akses sementara.';
$string['view_backup_report'] = 'Pantau antrean backup di satu tempat: kursus tertunda, item yang sedang diproses, backup selesai, dan catatan yang perlu perhatian.';
$string['view_restore_report'] = 'Pantau antrean pemulihan di satu tempat: file MBZ terpilih, item yang sedang diproses, pemulihan selesai, dan catatan yang perlu perhatian.';
