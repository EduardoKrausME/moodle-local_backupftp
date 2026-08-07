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
 * Lang it file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Accedi al corso</a>';
$string['adding_to_category'] = 'Sarà aggiunto alla categoria {$a->categoria}';
$string['already_added_status'] = 'Già aggiunto e lo stato è {$a->status}';
$string['api_invalid_action'] = 'Azione API non valida.';
$string['backup_category_select_help'] = 'Seleziona le categorie i cui corsi devono essere aggiunti alla coda di backup. I pulsanti in ogni scheda agiscono su quella categoria e su tutte le sottocategorie al suo interno.';
$string['backup_courses_and_categories'] = 'Backup: corsi e categorie';
$string['backup_creation_parameters'] = 'Il backup sarà creato con i seguenti parametri';
$string['backup_end'] = 'Backup terminato il';
$string['backup_end_time'] = 'Ora di fine backup';
$string['backup_report'] = 'Report di backup';
$string['backup_start'] = 'Backup avviato il';
$string['backup_start_time'] = 'Ora di inizio backup';
$string['backupftp:manage'] = 'Gestisci backup';
$string['categories'] = 'Categorie';
$string['category_created_successfully'] = ' ==> Categoria {$a->categoria_nome} creata correttamente';
$string['category_link'] = 'Categoria <a href="{$a}" target="blank">Categoria radice</a>';
$string['click_here'] = 'Fai clic qui';
$string['course'] = 'Corso';
$string['course_added_to_backup_queue'] = 'Corso {$a->course_id} ({$a->course_name}) aggiunto alla coda di backup.';
$string['courses'] = 'Corsi';
$string['courses_and_categories'] = 'Corsi e categorie';
$string['created_at'] = 'Creato il';
$string['created_on'] = 'Creato il';
$string['created_on_time'] = 'Creato il {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'Deseleziona tutto';
$string['error_creating_folder'] = '<span style="color:#d10707">Errore durante la creazione della cartella</span> "<b>{$a->ftppasta}</b>" su FTP con errore "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'Errore durante il download del file MBZ, con errore "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Errore durante l\'estrazione del file MBZ';
$string['file_added_to_restore_queue'] = 'File {$a->file} aggiunto alla coda di ripristino';
$string['file_found_and_downloaded'] = 'File individuato e scaricato';
$string['file_size'] = 'con dimensione {$a->size}';
$string['file_size_label'] = 'Dimensione file';
$string['file_uploaded'] = 'File "<b>{$a->file}</b>" caricato in "<b>{$a->remote_file}</b>"!';
$string['ftp_error_connecting'] = 'Errore di connessione a FTP';
$string['ftp_error_login'] = 'Impossibile connettersi con {$a->username}@{$a->url}';
$string['ftp_remote_file_size'] = 'FTP ha indicato che il file remoto ha "<b>{$a->size} byte</b>"';
$string['index_backup_button'] = 'Apri schermata di backup';
$string['index_backup_desc'] = 'Usa quest\'area per selezionare corsi e categorie e inserire la generazione dei backup nella coda. I file generati possono essere salvati localmente e/o inviati via FTP, secondo le impostazioni del plugin.';
$string['index_backup_report_button'] = 'Visualizza report di backup';
$string['index_backup_title'] = 'Backup dei corsi';
$string['index_flow_step1_after_old_moodle'] = 'genera o aggiorna i backup dei corsi che saranno trasferiti.';
$string['index_flow_step2_after_mbz'] = 'download solo finché è valido.';
$string['index_flow_step2_after_token_before_mbz'] = 'Abilita l\'API e i';
$string['index_flow_step2_before_token'] = 'Sempre nel vecchio Moodle, crea un';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'apri la schermata di ripristino e inserisci il vecchio';
$string['index_flow_step3_after_wwwroot'] = ', il token e, se necessario, l\'IP della vecchia macchina.';
$string['index_flow_step4'] = 'Controlla l\'elenco restituito dall\'API e invia i file alla coda. Il cron scaricherà e ripristinerà i corsi in background.';
$string['index_flow_step_moodle'] = 'Nel';
$string['index_intro_desc'] = 'Questo plugin aiuta a migrare corsi da un\'installazione Moodle a un\'altra con maggiore sicurezza. Il vecchio Moodle genera i backup e concede l\'accesso tramite un token temporaneo. Il nuovo Moodle interroga l\'API, elenca i file disponibili e inserisce i ripristini nella coda per l\'esecuzione tramite cron.';
$string['index_new_moodle'] = 'nuovo Moodle';
$string['index_old_moodle'] = 'vecchio Moodle';
$string['index_recommended_flow_title'] = 'Flusso consigliato';
$string['index_reports_desc'] = 'Usa i report per monitorare ciò che è già stato inserito in coda, ciò che è in elaborazione, quali backup sono stati completati e quali ripristini richiedono attenzione.';
$string['index_reports_title'] = 'Report e monitoraggio';
$string['index_restore_button'] = 'Apri schermata di ripristino';
$string['index_restore_desc_after_wwwroot'] = ', il token e, opzionalmente, l\'IP della vecchia macchina quando il dominio è già stato migrato al nuovo server.';
$string['index_restore_desc_before_wwwroot'] = 'Usa questa schermata per importare backup da un altro Moodle. Inserisci il vecchio';
$string['index_restore_queue_desc'] = 'Dopo la query, i file remoti vengono inseriti nella coda di ripristino. In questo modo la migrazione può continuare tramite cron senza dipendere dalla pagina aperta nel browser.';
$string['index_restore_report_button'] = 'Visualizza report di ripristino';
$string['index_restore_title'] = 'Ripristino nel nuovo Moodle';
$string['index_title'] = 'Trasferimento di corsi tra Moodle';
$string['index_token_time_desc'] = 'Il token ha una durata limitata, configurata in questa pagina di amministrazione. Prima di iniziare una migrazione grande, conferma che il cron del nuovo Moodle sia attivo e che il tempo residuo del token sia sufficiente per scaricare tutti i backup necessari.';
$string['index_token_time_title'] = 'Presta attenzione alla durata del token';
$string['index_tokens_button'] = 'Gestisci token';
$string['index_tokens_desc_after_mbz'] = 'Il token non sostituisce un accesso amministrativo: deve essere condiviso solo durante la finestra di migrazione.';
$string['index_tokens_desc_before_mbz'] = 'Crea token temporanei per consentire a un altro Moodle di consultare corsi, categorie, backup e scaricare';
$string['index_transfer_token'] = 'token di trasferimento';
$string['log:savelocal:error'] = 'Impossibile salvare il backup localmente: {$a}';
$string['log:savelocal:success'] = 'Backup salvato localmente: {$a}';
$string['logs'] = 'Log';
$string['manual_cron_button'] = 'Apri esecuzione manuale';
$string['manual_cron_desc'] = 'Usa questa pagina per elaborare ora backup o ripristini in coda, testare manualmente l\'attività o accelerare una migrazione senza attendere il prossimo ciclo CRON pianificato di Moodle.';
$string['manual_cron_title'] = 'Esecuzione manuale del CRON';
$string['mbz_extracted_successfully'] = 'MBZ estratto correttamente';
$string['nothing_to_execute'] = 'Nulla da eseguire';
$string['pluginname'] = 'Backup FTP/Locale';
$string['pre_check_failure'] = 'Pre-verifica non riuscita';
$string['privacy:metadata'] = 'Il plugin local_backupftp non raccoglie né memorizza dati personali o altri dati sensibili. Utilizza solo le configurazioni FTP fornite per eseguire i backup, senza registrare o conservare informazioni relative agli utenti o ai dati trasferiti.';
$string['processing_file'] = 'Elaborazione: <b>{$a->remote_file}</b> con {$a->size}';
$string['remote_file'] = 'File remoto';
$string['report'] = 'Report';
$string['reports'] = 'Report';
$string['requeue_backup'] = 'Reinvia';
$string['requeue_backup_confirm'] = 'Reinviare questo backup? Sarà reimpostato e rimesso in coda.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Il corso esiste già</a>';
$string['restore_courses_and_categories'] = 'Ripristino: corsi e categorie';
$string['restore_file_select_help'] = 'Seleziona i file MBZ che devono essere aggiunti alla coda di ripristino. I pulsanti in ogni categoria agiscono solo su quel ramo.';
$string['restore_report'] = 'Report di ripristino';
$string['runtask_backup_desc'] = 'Elabora i corsi già inseriti nella coda di backup e genera/invia i file MBZ configurati.';
$string['runtask_backup_title'] = 'Esegui manualmente la coda di backup';
$string['runtask_execute_five_courses'] = 'Elabora fino a 5 elementi ora';
$string['runtask_execute_ten_courses'] = 'Elabora fino a 10 elementi ora';
$string['runtask_manual_desc'] = 'Questa pagina esegue le stesse attività di backup e ripristino che il CRON di Moodle normalmente esegue. È utile quando vuoi elaborare manualmente la coda, validare la configurazione o accelerare una migrazione eseguendo subito un piccolo lotto.';
$string['runtask_manual_note'] = 'l\'esecuzione manuale non sostituisce il CRON pianificato di Moodle. Mantieni attivo il CRON normale affinché la coda continui a essere elaborata automaticamente.';
$string['runtask_manual_note_title'] = 'Importante:';
$string['runtask_restore_desc'] = 'Elabora i file MBZ già inseriti nella coda di ripristino e li ripristina in Moodle.';
$string['runtask_restore_title'] = 'Esegui manualmente la coda di ripristino';
$string['select_all'] = 'Seleziona tutto';
$string['settings_categorystart'] = 'ID categoria radice';
$string['settings_categorystart_desc'] = 'L\'ID della categoria radice da cui iniziare il ripristino dei corsi';
$string['settings_error'] = 'ed errore';
$string['settings_error_sending_backup'] = 'Errore nell\'invio del backup a';
$string['settings_file_size'] = 'con dimensione file';
$string['settings_ftp'] = 'Archiviazione FTP';
$string['settings_ftpenable'] = 'Invia a FTP';
$string['settings_ftpnames'] = 'Usa il nome del corso come nome del file di backup';
$string['settings_ftpnames_desc'] = 'Se selezionato, il nome del file inviato sarà il nome del corso. In caso contrario, sarà il nome assegnato da Moodle, simile a backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'Organizza i backup su FTP per categorie';
$string['settings_ftporganize_desc'] = 'Il file sarà salvato come Categoria/Categoria/corso.mbz';
$string['settings_ftppassword'] = 'Password FTP';
$string['settings_ftppasta'] = 'Cartella FTP remota';
$string['settings_ftppasta_desc'] = 'La cartella di destinazione deve iniziare con / e non terminare con / (es.: /backup, /save/backup)';
$string['settings_ftppasv'] = 'Inviare il file in modalità passiva?';
$string['settings_ftppasv_desc'] = 'La modalità FTP predefinita in PHP è la modalità attiva. La modalità attiva raramente funziona a causa di firewall/NAT/proxy. Pertanto, quasi sempre è necessario usare la modalità passiva.';
$string['settings_ftpurl'] = 'URL FTP';
$string['settings_ftpurl_desc'] = 'Inserisci l\'indirizzo IP o il nome host del server FTP desiderato. Se la porta del server FTP è diversa da 21, specificala aggiungendo due punti (:) seguiti dal numero di porta, ad esempio 127.0.0.1:29. Se il tuo FTP usa SSL, aggiungi ftps:// prima del dominio.';
$string['settings_ftpusername'] = 'Login FTP';
$string['settings_integrations'] = 'Integrazioni';
$string['settings_local'] = 'Archiviazione locale';
$string['settings_localfile'] = 'Salva i backup in una cartella locale';
$string['settings_localfile_desc'] = 'Se abilitato, una copia dei backup sarà memorizzata in una cartella locale specificata qui sotto.';
$string['settings_localfilepath'] = 'Percorso della cartella locale di backup';
$string['settings_localfilepath_desc'] = 'Inserisci il percorso completo della cartella in cui i backup saranno archiviati localmente. Assicurati che il server abbia i permessi di scrittura per questa cartella. Se lasciato vuoto, i backup saranno salvati in [MOODLEDATA]/backup/';
$string['settings_mbz_settings'] = 'Impostazioni di generazione backup';
$string['settings_restore_settings'] = 'Impostazioni di ripristino';
$string['settings_rootsettinganonymize'] = 'Anonimizza utenti root';
$string['settings_rootsettingusers'] = 'Impostazione utenti root';
$string['settings_tokenduration'] = 'Durata del token';
$string['settings_tokenduration_desc'] = 'Per quanto tempo ogni token di trasferimento generato rimane valido. Il valore predefinito è 48 ore.';
$string['settings_transfer_api'] = 'API di trasferimento corsi';
$string['settings_transfer_api_desc'] = 'I token di breve durata consentono a un altro sito Moodle di elencare corsi, categorie e backup, e di scaricare file MBZ.';
$string['status'] = 'Stato';
$string['submit'] = 'Invia';
$string['token_invalid_or_expired'] = 'Token di trasferimento non valido o scaduto.';
$string['transfer_restore_clear_session_button'] = 'Cancella dati remoti';
$string['transfer_restore_curl_required'] = 'L\'estensione PHP cURL è necessaria per trasferire backup da un altro Moodle.';
$string['transfer_restore_desc'] = 'Usa questa opzione per recuperare l\'elenco dei backup dal Moodle precedente. I dati del modulo vengono salvati nella tua sessione e i file vengono inseriti nella coda di ripristino solo dopo averli selezionati.';
$string['transfer_restore_download_too_small'] = 'Il file di backup scaricato è vuoto o troppo piccolo.';
$string['transfer_restore_downloading'] = 'Download del backup remoto da {$a->url}';
$string['transfer_restore_http_error'] = 'Errore di connessione al Moodle precedente: {$a}';
$string['transfer_restore_http_status'] = 'Il Moodle precedente ha restituito lo stato HTTP {$a}.';
$string['transfer_restore_invalid_backup_file'] = 'File di backup remoto non valido.';
$string['transfer_restore_invalid_json'] = 'Il Moodle precedente non ha restituito una risposta JSON valida.';
$string['transfer_restore_ip'] = 'IP del vecchio server (opzionale)';
$string['transfer_restore_ip_desc'] = 'Usa solo quando il dominio è già stato migrato a questo nuovo Moodle. La richiesta mantiene il vecchio host wwwroot, ma forza la risoluzione DNS verso questo IP.';
$string['transfer_restore_ip_invalid'] = 'IP del vecchio server non valido.';
$string['transfer_restore_missing_remote_data'] = 'Dati del Moodle remoto mancanti per scaricare il backup.';
$string['transfer_restore_no_backups'] = 'Nessun file di backup remoto è stato restituito dal Moodle precedente.';
$string['transfer_restore_no_selection'] = 'Seleziona almeno un file di backup remoto da ripristinare.';
$string['transfer_restore_original_category'] = 'ID/nome categoria originale';
$string['transfer_restore_original_course'] = 'ID/nome corso originale';
$string['transfer_restore_queue_button'] = 'Elenca backup remoti';
$string['transfer_restore_queue_summary'] = 'Coda di ripristino remoto aggiornata. Nuovi: {$a->queued}. Aggiornati: {$a->updated}. Ignorati: {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'Il Moodle precedente ha restituito un errore: {$a}';
$string['transfer_restore_select_file'] = 'Seleziona';
$string['transfer_restore_selected_button'] = 'Ripristina selezionati';
$string['transfer_restore_session_cleared'] = 'Dati del Moodle remoto rimossi dalla tua sessione.';
$string['transfer_restore_session_saved'] = 'Dati del Moodle remoto salvati nella tua sessione.';
$string['transfer_restore_session_summary'] = 'File di backup remoti trovati: {$a}. Seleziona i file che vuoi ripristinare.';
$string['transfer_restore_source'] = 'Origine';
$string['transfer_restore_table_limited'] = 'Visualizzazione dei primi 50 di {$a} file in coda.';
$string['transfer_restore_tempfile_error'] = 'Impossibile creare il file temporaneo di backup.';
$string['transfer_restore_title'] = 'Ripristina da un altro Moodle';
$string['transfer_restore_token'] = 'Token di trasferimento';
$string['transfer_restore_token_counter'] = 'Conto alla rovescia validità token:';
$string['transfer_restore_token_desc'] = 'Incolla il token generato nel Moodle precedente in Backup FTP/Local > Token di trasferimento.';
$string['transfer_restore_token_required'] = 'Il token di trasferimento è obbligatorio.';
$string['transfer_restore_users_failed'] = 'Gli utenti remoti non hanno potuto essere importati: {$a}';
$string['transfer_restore_users_summary'] = 'Utenti remoti importati. Creati: {$a->created}. Aggiornati: {$a->updated}. Ignorati: {$a->ignored}. Errori: {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'wwwroot del Moodle precedente';
$string['transfer_restore_wwwroot_desc'] = 'Esempio: https://ead-antigo.instituicao.edu.br. Non includere /local/backupftp.';
$string['transfer_restore_wwwroot_invalid'] = 'wwwroot del Moodle precedente non valido.';
$string['transfer_restore_wwwroot_required'] = 'Il wwwroot del Moodle precedente è obbligatorio.';
$string['transfer_token_create'] = 'Crea token';
$string['transfer_token_created_once'] = 'Token creato. Copialo ora:';
$string['transfer_token_created_once_desc'] = 'Per sicurezza, il token completo viene mostrato una sola volta. Dopo, viene memorizzato solo l\'hash.';
$string['transfer_token_default_name'] = 'Token di trasferimento corsi';
$string['transfer_token_expired'] = 'Scaduto';
$string['transfer_token_expires'] = 'Scade';
$string['transfer_token_lastused'] = 'Ultimo uso';
$string['transfer_token_name'] = 'Nome token';
$string['transfer_token_remaining'] = 'Rimanente';
$string['transfer_token_revoke'] = 'Revocato';
$string['transfer_token_revoke_confirm'] = 'Revocare questo token? Non sarà più accettato dall\'API o dai download.';
$string['transfer_token_revoked'] = 'Token revocato.';
$string['transfer_token_status_active'] = 'Attivo';
$string['transfer_token_uses'] = 'Utilizzi';
$string['transfer_tokens'] = 'Token di trasferimento';
$string['transfer_tokens_desc'] = 'I token autorizzano l\'API di trasferimento e i download MBZ per {$a}. Crea un nuovo token quando un altro sito Moodle necessita di accesso temporaneo.';
$string['view_backup_report'] = 'Monitora la coda di backup in un unico punto: corsi in attesa, elementi in elaborazione, backup completati e record che richiedono attenzione.';
$string['view_restore_report'] = 'Monitora la coda di ripristino in un unico punto: file MBZ selezionati, elementi in elaborazione, ripristini completati e record che richiedono attenzione.';
