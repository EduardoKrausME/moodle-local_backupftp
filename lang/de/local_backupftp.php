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
 * Lang de file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Kurs öffnen</a>';
$string['adding_to_category'] = 'Wird zur Kategorie {$a->categoria} hinzugefügt';
$string['already_added_status'] = 'Bereits hinzugefügt und der Status ist {$a->status}';
$string['api_invalid_action'] = 'Ungültige API-Aktion.';
$string['backup_category_select_help'] = 'Wählen Sie die Kategorien aus, deren Kurse zur Backup-Warteschlange hinzugefügt werden sollen. Die Schaltflächen in jeder Karte wirken auf diese Kategorie und alle darin enthaltenen Unterkategorien.';
$string['backup_courses_and_categories'] = 'Backup: Kurse und Kategorien';
$string['backup_creation_parameters'] = 'Das Backup wird mit den folgenden Parametern erstellt';
$string['backup_end'] = 'Backup beendet am';
$string['backup_end_time'] = 'Backup-Endzeit';
$string['backup_report'] = 'Backup-Bericht';
$string['backup_start'] = 'Backup gestartet am';
$string['backup_start_time'] = 'Backup-Startzeit';
$string['backupftp:manage'] = 'Backup verwalten';
$string['categories'] = 'Kategorien';
$string['category_created_successfully'] = ' ==> Kategorie {$a->categoria_nome} erfolgreich erstellt';
$string['category_link'] = 'Kategorie <a href="{$a}" target="blank">Stammkategorie</a>';
$string['click_here'] = 'Hier klicken';
$string['course'] = 'Kurs';
$string['course_added_to_backup_queue'] = 'Kurs {$a->course_id} ({$a->course_name}) zur Backup-Warteschlange hinzugefügt.';
$string['courses'] = 'Kurse';
$string['courses_and_categories'] = 'Kurse und Kategorien';
$string['created_at'] = 'Erstellt am';
$string['created_on'] = 'Erstellt am';
$string['created_on_time'] = 'Erstellt am {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'Alle abwählen';
$string['error_creating_folder'] = '<span style="color:#d10707">Fehler beim Erstellen des Ordners</span> "<b>{$a->ftppasta}</b>" auf FTP mit Fehler "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'Fehler beim Herunterladen der MBZ-Datei, mit Fehler "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Fehler beim Entpacken der MBZ-Datei';
$string['file_added_to_restore_queue'] = 'Datei {$a->file} zur Wiederherstellungswarteschlange hinzugefügt';
$string['file_found_and_downloaded'] = 'Datei gefunden und heruntergeladen';
$string['file_size'] = 'mit Größe {$a->size}';
$string['file_size_label'] = 'Dateigröße';
$string['file_uploaded'] = 'Datei "<b>{$a->file}</b>" nach "<b>{$a->remote_file}</b>" hochgeladen!';
$string['ftp_error_connecting'] = 'Fehler beim Verbinden mit FTP';
$string['ftp_error_login'] = 'Verbindung mit {$a->username}@{$a->url} nicht möglich';
$string['ftp_remote_file_size'] = 'FTP meldete, dass die entfernte Datei "<b>{$a->size} Bytes</b>" hat';
$string['index_backup_button'] = 'Backup-Seite öffnen';
$string['index_backup_desc'] = 'Verwenden Sie diesen Bereich, um Kurse und Kategorien auszuwählen und die Backup-Erstellung in die Warteschlange zu stellen. Erzeugte Dateien können lokal gespeichert und/oder gemäß den Plugin-Einstellungen per FTP gesendet werden.';
$string['index_backup_report_button'] = 'Backup-Bericht anzeigen';
$string['index_backup_title'] = 'Kurs-Backup';
$string['index_flow_step1_after_old_moodle'] = 'erstellen oder aktualisieren Sie die Backups der Kurse, die übertragen werden.';
$string['index_flow_step2_after_mbz'] = 'Downloads nur solange er gültig ist.';
$string['index_flow_step2_after_token_before_mbz'] = 'Er aktiviert die API und';
$string['index_flow_step2_before_token'] = 'Erstellen Sie noch im alten Moodle ein';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'öffnen Sie die Wiederherstellungsseite und geben Sie den alten';
$string['index_flow_step3_after_wwwroot'] = ', den Token und, falls nötig, die IP der alten Maschine ein.';
$string['index_flow_step4'] = 'Prüfen Sie die von der API zurückgegebene Liste und senden Sie die Dateien an die Warteschlange. Der Cron lädt die Kurse im Hintergrund herunter und stellt sie wieder her.';
$string['index_flow_step_moodle'] = 'Im';
$string['index_intro_desc'] = 'Dieses Plugin hilft, Kurse sicherer von einer Moodle-Installation in eine andere zu migrieren. Das alte Moodle erzeugt die Backups und gewährt Zugriff über einen temporären Token. Das neue Moodle fragt die API ab, listet die verfügbaren Dateien auf und legt die Wiederherstellungen zur Ausführung durch Cron in die Warteschlange.';
$string['index_new_moodle'] = 'neuen Moodle';
$string['index_old_moodle'] = 'alten Moodle';
$string['index_recommended_flow_title'] = 'Empfohlener Ablauf';
$string['index_reports_desc'] = 'Verwenden Sie die Berichte, um zu verfolgen, was bereits in die Warteschlange gestellt wurde, was verarbeitet wird, welche Backups abgeschlossen wurden und welche Wiederherstellungen Aufmerksamkeit benötigen.';
$string['index_reports_title'] = 'Berichte und Überwachung';
$string['index_restore_button'] = 'Wiederherstellungsseite öffnen';
$string['index_restore_desc_after_wwwroot'] = ', den Token und optional die IP der alten Maschine ein, wenn die Domain bereits auf den neuen Server migriert wurde.';
$string['index_restore_desc_before_wwwroot'] = 'Verwenden Sie diese Seite, um Backups aus einem anderen Moodle zu importieren. Geben Sie den alten';
$string['index_restore_queue_desc'] = 'Nach der Abfrage werden die entfernten Dateien in die Wiederherstellungswarteschlange gestellt. So kann die Migration über Cron fortgesetzt werden, ohne dass die Seite im Browser geöffnet bleiben muss.';
$string['index_restore_report_button'] = 'Wiederherstellungsbericht anzeigen';
$string['index_restore_title'] = 'Wiederherstellung im neuen Moodle';
$string['index_title'] = 'Kursübertragung zwischen Moodles';
$string['index_token_time_desc'] = 'Der Token hat eine begrenzte Lebensdauer, die auf dieser Administrationsseite konfiguriert wird. Bevor Sie eine große Migration starten, prüfen Sie, ob der Cron des neuen Moodle aktiv ist und ob die verbleibende Token-Zeit ausreicht, um alle benötigten Backups herunterzuladen.';
$string['index_token_time_title'] = 'Achten Sie auf die Token-Lebensdauer';
$string['index_tokens_button'] = 'Tokens verwalten';
$string['index_tokens_desc_after_mbz'] = 'Der Token ersetzt keine administrative Anmeldung: Er sollte nur während des Migrationsfensters geteilt werden.';
$string['index_tokens_desc_before_mbz'] = 'Erstellen Sie temporäre Tokens, damit ein anderes Moodle Kurse, Kategorien und Backups abfragen und herunterladen kann';
$string['index_transfer_token'] = 'Übertragungstoken';
$string['log:savelocal:error'] = 'Backup konnte nicht lokal gespeichert werden: {$a}';
$string['log:savelocal:success'] = 'Backup lokal gespeichert: {$a}';
$string['logs'] = 'Protokolle';
$string['manual_cron_button'] = 'Manuelle Ausführung öffnen';
$string['manual_cron_desc'] = 'Verwenden Sie diese Seite, um Backups oder Wiederherstellungen in der Warteschlange jetzt zu verarbeiten, die Aufgabe manuell zu testen oder eine Migration zu beschleunigen, ohne auf den nächsten geplanten Moodle-CRON-Zyklus zu warten.';
$string['manual_cron_title'] = 'Manuelle CRON-Ausführung';
$string['mbz_extracted_successfully'] = 'MBZ erfolgreich entpackt';
$string['nothing_to_execute'] = 'Nichts auszuführen';
$string['pluginname'] = 'Backup FTP/Lokal';
$string['pre_check_failure'] = 'Vorprüfung fehlgeschlagen';
$string['privacy:metadata'] = 'Das Plugin local_backupftp erfasst oder speichert keine personenbezogenen Daten oder andere sensible Daten. Es verwendet nur die bereitgestellten FTP-Konfigurationen, um Backups durchzuführen, ohne Informationen zu Benutzern oder übertragenen Daten zu protokollieren oder aufzubewahren.';
$string['processing_file'] = 'Verarbeitung: <b>{$a->remote_file}</b> mit {$a->size}';
$string['remote_file'] = 'Entfernte Datei';
$string['report'] = 'Bericht';
$string['reports'] = 'Berichte';
$string['requeue_backup'] = 'Erneut senden';
$string['requeue_backup_confirm'] = 'Dieses Backup erneut senden? Es wird zurückgesetzt und wieder in die Warteschlange gestellt.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Kurs existiert bereits</a>';
$string['restore_courses_and_categories'] = 'Wiederherstellung: Kurse und Kategorien';
$string['restore_file_select_help'] = 'Wählen Sie die MBZ-Dateien aus, die zur Wiederherstellungswarteschlange hinzugefügt werden sollen. Die Schaltflächen in jeder Kategorie wirken nur auf diesen Zweig.';
$string['restore_report'] = 'Wiederherstellungsbericht';
$string['runtask_backup_desc'] = 'Verarbeitet Kurse, die bereits in der Backup-Warteschlange stehen, und erzeugt/sendet die konfigurierten MBZ-Dateien.';
$string['runtask_backup_title'] = 'Backup-Warteschlange manuell ausführen';
$string['runtask_execute_five_courses'] = 'Jetzt bis zu 5 Elemente verarbeiten';
$string['runtask_execute_ten_courses'] = 'Jetzt bis zu 10 Elemente verarbeiten';
$string['runtask_manual_desc'] = 'Diese Seite führt dieselben Backup- und Wiederherstellungsaufgaben aus, die der Moodle-CRON normalerweise ausführt. Sie ist nützlich, wenn Sie die Warteschlange manuell verarbeiten, die Konfiguration prüfen oder eine Migration durch sofortiges Ausführen eines kleinen Stapels beschleunigen möchten.';
$string['runtask_manual_note'] = 'die manuelle Ausführung ersetzt den geplanten Moodle-CRON nicht. Lassen Sie den normalen CRON aktiv, damit die Warteschlange automatisch weiterverarbeitet wird.';
$string['runtask_manual_note_title'] = 'Wichtig:';
$string['runtask_restore_desc'] = 'Verarbeitet MBZ-Dateien, die bereits in der Wiederherstellungswarteschlange stehen, und stellt sie in Moodle wieder her.';
$string['runtask_restore_title'] = 'Wiederherstellungswarteschlange manuell ausführen';
$string['select_all'] = 'Alle auswählen';
$string['settings_categorystart'] = 'ID der Stammkategorie';
$string['settings_categorystart_desc'] = 'Die ID der Stammkategorie, ab der die Kurse wiederhergestellt werden sollen';
$string['settings_error'] = 'und Fehler';
$string['settings_error_sending_backup'] = 'Fehler beim Senden des Backups an';
$string['settings_file_size'] = 'mit Dateigröße';
$string['settings_ftp'] = 'FTP-Speicher';
$string['settings_ftpenable'] = 'An FTP senden';
$string['settings_ftpnames'] = 'Kursnamen als Namen der Backup-Datei verwenden';
$string['settings_ftpnames_desc'] = 'Wenn aktiviert, wird als gesendeter Dateiname der Kursname verwendet. Andernfalls wird der von Moodle vergebene Name verwendet, ähnlich wie backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'Backups auf FTP nach Kategorien organisieren';
$string['settings_ftporganize_desc'] = 'Die Datei wird als Kategorie/Kategorie/kurs.mbz gespeichert';
$string['settings_ftppassword'] = 'FTP-Passwort';
$string['settings_ftppasta'] = 'Entfernter FTP-Ordner';
$string['settings_ftppasta_desc'] = 'Der Zielordner muss mit / beginnen und darf nicht mit / enden (z. B. /backup, /save/backup)';
$string['settings_ftppasv'] = 'Datei im passiven Modus senden?';
$string['settings_ftppasv_desc'] = 'Der Standard-FTP-Modus in PHP ist der aktive Modus. Der aktive Modus funktioniert wegen Firewalls/NATs/Proxys selten. Daher müssen Sie fast immer den passiven Modus verwenden.';
$string['settings_ftpurl'] = 'FTP-URL';
$string['settings_ftpurl_desc'] = 'Geben Sie die IP-Adresse oder den Hostnamen des gewünschten FTP-Servers ein. Wenn der Port des FTP-Servers von 21 abweicht, geben Sie ihn durch Anhängen eines Doppelpunkts (:) und der Portnummer an, z. B. 127.0.0.1:29. Wenn Ihr FTP SSL verwendet, fügen Sie ftps:// vor der Domain hinzu.';
$string['settings_ftpusername'] = 'FTP-Login';
$string['settings_integrations'] = 'Integrationen';
$string['settings_local'] = 'Lokaler Speicher';
$string['settings_localfile'] = 'Backups in einem lokalen Ordner speichern';
$string['settings_localfile_desc'] = 'Wenn aktiviert, wird eine Kopie der Backups in einem unten angegebenen lokalen Ordner gespeichert.';
$string['settings_localfilepath'] = 'Pfad zum lokalen Backup-Ordner';
$string['settings_localfilepath_desc'] = 'Geben Sie den vollständigen Pfad des Ordners ein, in dem Backups lokal gespeichert werden. Stellen Sie sicher, dass der Server Schreibrechte für diesen Ordner hat. Wenn leer, werden Backups in [MOODLEDATA]/backup/ gespeichert';
$string['settings_mbz_settings'] = 'Einstellungen zur Backup-Erstellung';
$string['settings_restore_settings'] = 'Wiederherstellungseinstellungen';
$string['settings_rootsettinganonymize'] = 'Root-Benutzer anonymisieren';
$string['settings_rootsettingusers'] = 'Root-Benutzereinstellung';
$string['settings_tokenduration'] = 'Token-Lebensdauer';
$string['settings_tokenduration_desc'] = 'Wie lange jeder erzeugte Übertragungstoken gültig bleibt. Standard ist 48 Stunden.';
$string['settings_transfer_api'] = 'API zur Kursübertragung';
$string['settings_transfer_api_desc'] = 'Kurzlebige Tokens erlauben einem anderen Moodle, Kurse, Kategorien und Backups aufzulisten und MBZ-Dateien herunterzuladen.';
$string['status'] = 'Status';
$string['submit'] = 'Absenden';
$string['temporary_files_deleted'] = 'Temporäre Dateien gelöscht';
$string['token_invalid_or_expired'] = 'Ungültiger oder abgelaufener Übertragungstoken.';
$string['transfer_restore_clear_session_button'] = 'Entfernte Daten löschen';
$string['transfer_restore_curl_required'] = 'Die PHP-Erweiterung cURL ist erforderlich, um Backups von einem anderen Moodle zu übertragen.';
$string['transfer_restore_desc'] = 'Verwenden Sie diese Option, um die Backup-Liste aus dem vorherigen Moodle abzurufen. Die Formulardaten werden in Ihrer Sitzung gespeichert und die Dateien werden erst nach Ihrer Auswahl in die Wiederherstellungswarteschlange gestellt.';
$string['transfer_restore_download_too_small'] = 'Die heruntergeladene Backup-Datei ist leer oder zu klein.';
$string['transfer_restore_downloading'] = 'Entferntes Backup wird von {$a->url} heruntergeladen';
$string['transfer_restore_http_error'] = 'Fehler beim Verbinden mit dem vorherigen Moodle: {$a}';
$string['transfer_restore_http_status'] = 'Das vorherige Moodle gab den HTTP-Status {$a} zurück.';
$string['transfer_restore_invalid_backup_file'] = 'Ungültige entfernte Backup-Datei.';
$string['transfer_restore_invalid_json'] = 'Das vorherige Moodle hat keine gültige JSON-Antwort zurückgegeben.';
$string['transfer_restore_ip'] = 'IP des alten Servers (optional)';
$string['transfer_restore_ip_desc'] = 'Nur verwenden, wenn die Domain bereits auf dieses neue Moodle migriert wurde. Die Anfrage behält den alten wwwroot-Host bei, erzwingt aber die DNS-Auflösung auf diese IP.';
$string['transfer_restore_ip_invalid'] = 'Ungültige IP des alten Servers.';
$string['transfer_restore_missing_remote_data'] = 'Entfernte Moodle-Daten zum Herunterladen des Backups fehlen.';
$string['transfer_restore_no_backups'] = 'Vom vorherigen Moodle wurden keine entfernten Backup-Dateien zurückgegeben.';
$string['transfer_restore_no_selection'] = 'Wählen Sie mindestens eine entfernte Backup-Datei zur Wiederherstellung aus.';
$string['transfer_restore_original_category'] = 'Ursprüngliche Kategorie-ID/-Name';
$string['transfer_restore_original_course'] = 'Ursprüngliche Kurs-ID/-Name';
$string['transfer_restore_queue_button'] = 'Entfernte Backups auflisten';
$string['transfer_restore_queue_summary'] = 'Entfernte Wiederherstellungswarteschlange aktualisiert. Neu: {$a->queued}. Aktualisiert: {$a->updated}. Ignoriert: {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'Das vorherige Moodle gab einen Fehler zurück: {$a}';
$string['transfer_restore_select_file'] = 'Auswählen';
$string['transfer_restore_selected_button'] = 'Ausgewählte wiederherstellen';
$string['transfer_restore_session_cleared'] = 'Entfernte Moodle-Daten aus Ihrer Sitzung entfernt.';
$string['transfer_restore_session_saved'] = 'Entfernte Moodle-Daten in Ihrer Sitzung gespeichert.';
$string['transfer_restore_session_summary'] = 'Entfernte Backup-Dateien gefunden: {$a}. Wählen Sie die Dateien aus, die Sie wiederherstellen möchten.';
$string['transfer_restore_source'] = 'Quelle';
$string['transfer_restore_table_limited'] = 'Zeige die ersten 50 von {$a} Dateien in der Warteschlange.';
$string['transfer_restore_tempfile_error'] = 'Temporäre Backup-Datei konnte nicht erstellt werden.';
$string['transfer_restore_title'] = 'Aus anderem Moodle wiederherstellen';
$string['transfer_restore_token'] = 'Übertragungstoken';
$string['transfer_restore_token_counter'] = 'Countdown der Token-Gültigkeit:';
$string['transfer_restore_token_desc'] = 'Fügen Sie den im vorherigen Moodle erzeugten Token unter Backup FTP/Lokal > Übertragungstokens ein.';
$string['transfer_restore_token_remaining_log'] = 'Übertragungstoken noch gültig für {$a}.';
$string['transfer_restore_token_required'] = 'Der Übertragungstoken ist erforderlich.';
$string['transfer_restore_users_failed'] = 'Entfernte Benutzer konnten nicht importiert werden: {$a}';
$string['transfer_restore_users_summary'] = 'Entfernte Benutzer importiert. Erstellt: {$a->created}. Aktualisiert: {$a->updated}. Ignoriert: {$a->ignored}. Fehler: {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'wwwroot des vorherigen Moodle';
$string['transfer_restore_wwwroot_desc'] = 'Beispiel: https://ead-antigo.instituicao.edu.br. /local/backupftp nicht einschließen.';
$string['transfer_restore_wwwroot_invalid'] = 'Ungültiger wwwroot des vorherigen Moodle.';
$string['transfer_restore_wwwroot_required'] = 'Der wwwroot des vorherigen Moodle ist erforderlich.';
$string['transfer_token_create'] = 'Token erstellen';
$string['transfer_token_created_once'] = 'Token erstellt. Jetzt kopieren:';
$string['transfer_token_created_once_desc'] = 'Aus Sicherheitsgründen wird der vollständige Token nur einmal angezeigt. Danach wird nur der Hash gespeichert.';
$string['transfer_token_default_name'] = 'Kursübertragungstoken';
$string['transfer_token_expired'] = 'Abgelaufen';
$string['transfer_token_expired_before_restore'] = 'Der Übertragungstoken ist abgelaufen, bevor dieses Backup wiederhergestellt werden konnte.';
$string['transfer_token_expires'] = 'Läuft ab';
$string['transfer_token_lastused'] = 'Zuletzt verwendet';
$string['transfer_token_name'] = 'Token-Name';
$string['transfer_token_remaining'] = 'Verbleibend';
$string['transfer_token_revoke'] = 'Widerrufen';
$string['transfer_token_revoke_confirm'] = 'Diesen Token widerrufen? Er wird von API oder Downloads nicht mehr akzeptiert.';
$string['transfer_token_revoked'] = 'Token widerrufen.';
$string['transfer_token_status_active'] = 'Aktiv';
$string['transfer_token_uses'] = 'Verwendungen';
$string['transfer_tokens'] = 'Übertragungstokens';
$string['transfer_tokens_desc'] = 'Tokens autorisieren die Übertragungs-API und MBZ-Downloads für {$a}. Erstellen Sie einen neuen Token, wenn eine andere Moodle-Site vorübergehenden Zugriff benötigt.';
$string['view_backup_report'] = 'Verfolgen Sie die Backup-Warteschlange an einem Ort: ausstehende Kurse, gerade verarbeitete Elemente, abgeschlossene Backups und Datensätze, die Aufmerksamkeit benötigen.';
$string['view_restore_report'] = 'Verfolgen Sie die Wiederherstellungswarteschlange an einem Ort: ausgewählte MBZ-Dateien, gerade verarbeitete Elemente, abgeschlossene Wiederherstellungen und Datensätze, die Aufmerksamkeit benötigen.';
