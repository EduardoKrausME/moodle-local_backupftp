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
 * Lang sk file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Otvoriť kurz</a>';
$string['adding_to_category'] = 'Bude pridané do kategórie {$a->categoria}';
$string['already_added_status'] = 'Už pridané a stav je {$a->status}';
$string['api_invalid_action'] = 'Neplatná akcia API.';
$string['backup_category_select_help'] = 'Vyberte kategórie, ktorých kurzy majú byť pridané do frontu zálohovania. Tlačidlá v každej karte ovplyvnia túto kategóriu a všetky jej podkategórie.';
$string['backup_courses_and_categories'] = 'Záloha: kurzy a kategórie';
$string['backup_creation_parameters'] = 'Záloha bude vytvorená s nasledujúcimi parametrami';
$string['backup_end'] = 'Zálohovanie skončilo';
$string['backup_end_time'] = 'Čas ukončenia zálohy';
$string['backup_report'] = 'Správa o zálohe';
$string['backup_start'] = 'Zálohovanie začalo';
$string['backup_start_time'] = 'Čas začiatku zálohy';
$string['backupftp:manage'] = 'Spravovať zálohu';
$string['categories'] = 'Kategórie';
$string['category_created_successfully'] = ' ==> Kategória {$a->categoria_nome} bola úspešne vytvorená';
$string['category_link'] = 'Kategória <a href="{$a}" target="blank">Koreňová kategória</a>';
$string['click_here'] = 'Kliknite sem';
$string['course'] = 'Kurz';
$string['course_added_to_backup_queue'] = 'Kurz {$a->course_id} ({$a->course_name}) bol pridaný do frontu zálohovania.';
$string['courses'] = 'Kurzy';
$string['courses_and_categories'] = 'Kurzy a kategórie';
$string['created_at'] = 'Vytvorené';
$string['created_on'] = 'Vytvorené';
$string['created_on_time'] = 'Vytvorené {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'Zrušiť výber všetkých';
$string['error_creating_folder'] = '<span style="color:#d10707">Chyba pri vytváraní priečinka</span> "<b>{$a->ftppasta}</b>" na FTP s chybou "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'Chyba pri sťahovaní súboru MBZ, chyba "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Chyba pri extrakcii súboru MBZ';
$string['file_added_to_restore_queue'] = 'Súbor {$a->file} bol pridaný do frontu obnovy';
$string['file_found_and_downloaded'] = 'Súbor bol nájdený a stiahnutý';
$string['file_size'] = 's veľkosťou {$a->size}';
$string['file_size_label'] = 'Veľkosť súboru';
$string['file_uploaded'] = 'Súbor "<b>{$a->file}</b>" bol nahraný do "<b>{$a->remote_file}</b>"!';
$string['ftp_error_connecting'] = 'Chyba pri pripájaní k FTP';
$string['ftp_error_login'] = 'Nepodarilo sa pripojiť ako {$a->username}@{$a->url}';
$string['ftp_remote_file_size'] = 'FTP vrátilo, že vzdialený súbor má "<b>{$a->size} bajtov</b>"';
$string['index_backup_button'] = 'Otvoriť obrazovku zálohy';
$string['index_backup_desc'] = 'Použite túto oblasť na výber kurzov a kategórií a zaradenie vytvárania záloh do frontu. Vytvorené súbory môžu byť uložené lokálne a/alebo odoslané na FTP podľa nastavení pluginu.';
$string['index_backup_report_button'] = 'Zobraziť správu o zálohe';
$string['index_backup_title'] = 'Záloha kurzov';
$string['index_flow_step1_after_old_moodle'] = 'vytvorte alebo aktualizujte zálohy kurzov, ktoré budú prenesené.';
$string['index_flow_step2_after_mbz'] = 'sťahovania iba počas jeho platnosti.';
$string['index_flow_step2_after_token_before_mbz'] = 'Povolí API a';
$string['index_flow_step2_before_token'] = 'Ešte v starom Moodle vytvorte';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'otvorte obrazovku obnovy a zadajte starý';
$string['index_flow_step3_after_wwwroot'] = ', token a v prípade potreby IP starej mašiny.';
$string['index_flow_step4'] = 'Skontrolujte zoznam vrátený API a odošlite súbory do frontu. Cron stiahne a obnoví kurzy na pozadí.';
$string['index_flow_step_moodle'] = 'V';
$string['index_intro_desc'] = 'Tento plugin pomáha bezpečnejšie migrovať kurzy z jednej inštalácie Moodle do druhej. Starý Moodle vytvorí zálohy a umožní prístup cez dočasný token. Nový Moodle sa dotáže API, vypíše dostupné súbory a zaradí obnovy do frontu na vykonanie cronom.';
$string['index_new_moodle'] = 'novom Moodle';
$string['index_old_moodle'] = 'starom Moodle';
$string['index_recommended_flow_title'] = 'Odporúčaný postup';
$string['index_reports_desc'] = 'Použite správy na sledovanie toho, čo už bolo zaradené do frontu, čo sa spracúva, ktoré zálohy boli dokončené a ktoré obnovy si vyžadujú pozornosť.';
$string['index_reports_title'] = 'Správy a monitorovanie';
$string['index_restore_button'] = 'Otvoriť obrazovku obnovy';
$string['index_restore_desc_after_wwwroot'] = ', token a voliteľne IP starej mašiny, keď už bola doména migrovaná na nový server.';
$string['index_restore_desc_before_wwwroot'] = 'Použite túto obrazovku na import záloh z iného Moodle. Zadajte starý';
$string['index_restore_queue_desc'] = 'Po dotaze sa vzdialené súbory zaradia do frontu obnovy. Migrácia tak môže pokračovať cez cron bez toho, aby stránka musela zostať otvorená v prehliadači.';
$string['index_restore_report_button'] = 'Zobraziť správu o obnove';
$string['index_restore_title'] = 'Obnova v novom Moodle';
$string['index_title'] = 'Prenos kurzov medzi Moodle';
$string['index_token_time_desc'] = 'Token má obmedzenú platnosť nastavenú na tejto administračnej stránke. Pred spustením veľkej migrácie overte, že cron nového Moodle je aktívny a zostávajúci čas tokenu stačí na stiahnutie všetkých potrebných záloh.';
$string['index_token_time_title'] = 'Venujte pozornosť platnosti tokenu';
$string['index_tokens_button'] = 'Spravovať tokeny';
$string['index_tokens_desc_after_mbz'] = 'Token nenahrádza administrátorské prihlásenie: má sa zdieľať iba počas migračného okna.';
$string['index_tokens_desc_before_mbz'] = 'Vytvorte dočasné tokeny, aby iný Moodle mohol dotazovať kurzy, kategórie, zálohy a sťahovať';
$string['index_transfer_token'] = 'token prenosu';
$string['log:savelocal:error'] = 'Zálohu sa nepodarilo uložiť lokálne: {$a}';
$string['log:savelocal:success'] = 'Záloha uložená lokálne: {$a}';
$string['logs'] = 'Logy';
$string['manual_cron_button'] = 'Otvoriť ručné spustenie';
$string['manual_cron_desc'] = 'Použite túto stránku na okamžité spracovanie záloh alebo obnov vo fronte, manuálne otestovanie úlohy alebo zrýchlenie migrácie bez čakania na ďalší plánovaný cyklus Moodle CRON.';
$string['manual_cron_title'] = 'Ručné spustenie CRON';
$string['mbz_extracted_successfully'] = 'MBZ bol úspešne extrahovaný';
$string['nothing_to_execute'] = 'Nie je čo vykonať';
$string['pluginname'] = 'Backup FTP/Local';
$string['pre_check_failure'] = 'Predbežná kontrola zlyhala';
$string['privacy:metadata'] = 'Plugin local_backupftp nezhromažďuje ani neukladá osobné údaje ani iné citlivé údaje. Používa iba poskytnuté FTP konfigurácie na vykonávanie záloh bez zapisovania alebo uchovávania informácií o používateľoch alebo prenášaných údajoch.';
$string['processing_file'] = 'Spracúva sa: <b>{$a->remote_file}</b> s {$a->size}';
$string['remote_file'] = 'Vzdialený súbor';
$string['report'] = 'Správa';
$string['reports'] = 'Správy';
$string['requeue_backup'] = 'Odoslať znova';
$string['requeue_backup_confirm'] = 'Odoslať túto zálohu znova? Bude resetovaná a opäť zaradená do frontu.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Kurz už existuje</a>';
$string['restore_courses_and_categories'] = 'Obnova: kurzy a kategórie';
$string['restore_file_select_help'] = 'Vyberte súbory MBZ, ktoré majú byť pridané do frontu obnovy. Tlačidlá v každej kategórii ovplyvnia iba túto vetvu.';
$string['restore_report'] = 'Správa o obnove';
$string['runtask_backup_desc'] = 'Spracuje kurzy už zaradené vo fronte zálohovania a vytvorí/odošle nakonfigurované súbory MBZ.';
$string['runtask_backup_title'] = 'Spustiť front zálohovania ručne';
$string['runtask_execute_five_courses'] = 'Spracovať teraz najviac 5 položiek';
$string['runtask_execute_ten_courses'] = 'Spracovať teraz najviac 10 položiek';
$string['runtask_manual_desc'] = 'Táto stránka spúšťa rovnaké úlohy zálohovania a obnovy, ktoré bežne vykonáva Moodle CRON. Je užitočná, keď chcete front spracovať ručne, overiť konfiguráciu alebo zrýchliť migráciu okamžitým spustením malej dávky.';
$string['runtask_manual_note'] = 'ručné spustenie nenahrádza plánovaný Moodle CRON. Nechajte normálny CRON aktívny, aby sa front ďalej spracúval automaticky.';
$string['runtask_manual_note_title'] = 'Dôležité:';
$string['runtask_restore_desc'] = 'Spracuje súbory MBZ už zaradené vo fronte obnovy a obnoví ich do Moodle.';
$string['runtask_restore_title'] = 'Spustiť front obnovy ručne';
$string['select_all'] = 'Vybrať všetko';
$string['settings_categorystart'] = 'ID koreňovej kategórie';
$string['settings_categorystart_desc'] = 'ID koreňovej kategórie, od ktorej sa má začať obnova kurzov';
$string['settings_error'] = 'a chyba';
$string['settings_error_sending_backup'] = 'Chyba pri odosielaní zálohy na';
$string['settings_file_size'] = 's veľkosťou súboru';
$string['settings_ftp'] = 'FTP úložisko';
$string['settings_ftpenable'] = 'Odoslať na FTP';
$string['settings_ftpnames'] = 'Použiť názov kurzu ako názov súboru zálohy';
$string['settings_ftpnames_desc'] = 'Ak je zaškrtnuté, názov odoslaného súboru bude názov kurzu. Inak to bude názov priradený Moodle, podobný backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'Organizovať zálohy na FTP podľa kategórií';
$string['settings_ftporganize_desc'] = 'Súbor bude uložený ako Kategória/Kategória/course.mbz';
$string['settings_ftppassword'] = 'FTP heslo';
$string['settings_ftppasta'] = 'Vzdialený FTP priečinok';
$string['settings_ftppasta_desc'] = 'Cieľový priečinok musí začínať znakom / a nesmie končiť znakom / (napr. /backup, /save/backup)';
$string['settings_ftppasv'] = 'Odoslať súbor v pasívnom režime?';
$string['settings_ftppasv_desc'] = 'Predvolený režim FTP v PHP je aktívny režim. Aktívny režim zriedka funguje kvôli firewallom/NAT/proxy. Preto je takmer vždy potrebné použiť pasívny režim.';
$string['settings_ftpurl'] = 'FTP URL';
$string['settings_ftpurl_desc'] = 'Zadajte IP adresu alebo názov hostiteľa požadovaného FTP servera. Ak je port FTP servera iný ako 21, zadajte ho pridaním dvojbodky (:) a čísla portu, napr. 127.0.0.1:29. Ak váš FTP používa SSL, pridajte pred doménu ftps://.';
$string['settings_ftpusername'] = 'FTP prihlasovacie meno';
$string['settings_integrations'] = 'Integrácie';
$string['settings_local'] = 'Lokálne úložisko';
$string['settings_localfile'] = 'Ukladať zálohy do lokálneho priečinka';
$string['settings_localfile_desc'] = 'Ak je povolené, kópia záloh bude uložená v lokálnom priečinku uvedenom nižšie.';
$string['settings_localfilepath'] = 'Cesta k lokálnemu priečinku záloh';
$string['settings_localfilepath_desc'] = 'Zadajte úplnú cestu k priečinku, kde budú zálohy uložené lokálne. Uistite sa, že server má právo zápisu do tohto priečinka. Ak zostane prázdne, zálohy sa uložia do [MOODLEDATA]/backup/';
$string['settings_mbz_settings'] = 'Nastavenia vytvárania záloh';
$string['settings_restore_settings'] = 'Nastavenia obnovy';
$string['settings_rootsettinganonymize'] = 'Anonymizovať root používateľov';
$string['settings_rootsettingusers'] = 'Nastavenie root používateľov';
$string['settings_tokenduration'] = 'Platnosť tokenu';
$string['settings_tokenduration_desc'] = 'Ako dlho zostane každý vygenerovaný token prenosu platný. Predvolené je 48 hodín.';
$string['settings_transfer_api'] = 'API prenosu kurzov';
$string['settings_transfer_api_desc'] = 'Krátkodobé tokeny umožnia inému Moodle webu vypísať kurzy, kategórie a zálohy a sťahovať súbory MBZ.';
$string['status'] = 'Stav';
$string['submit'] = 'Odoslať';
$string['temporary_files_deleted'] = 'Dočasné súbory boli odstránené';
$string['token_invalid_or_expired'] = 'Neplatný alebo expirovaný token prenosu.';
$string['transfer_restore_clear_session_button'] = 'Vymazať vzdialené údaje';
$string['transfer_restore_curl_required'] = 'Rozšírenie PHP cURL je potrebné na prenos záloh z iného Moodle.';
$string['transfer_restore_desc'] = 'Použite túto možnosť na načítanie zoznamu záloh z predchádzajúceho Moodle. Údaje formulára sa uložia do vašej relácie a súbory sa zaradia do frontu obnovy až po ich výbere.';
$string['transfer_restore_download_too_small'] = 'Stiahnutý súbor zálohy je prázdny alebo príliš malý.';
$string['transfer_restore_downloading'] = 'Sťahuje sa vzdialená záloha z {$a->url}';
$string['transfer_restore_http_error'] = 'Chyba pri pripájaní k predchádzajúcemu Moodle: {$a}';
$string['transfer_restore_http_status'] = 'Predchádzajúci Moodle vrátil HTTP stav {$a}.';
$string['transfer_restore_invalid_backup_file'] = 'Neplatný vzdialený súbor zálohy.';
$string['transfer_restore_invalid_json'] = 'Predchádzajúci Moodle nevrátil platnú JSON odpoveď.';
$string['transfer_restore_ip'] = 'IP starého servera (voliteľné)';
$string['transfer_restore_ip_desc'] = 'Použite iba vtedy, keď už bola doména migrovaná na tento nový Moodle. Požiadavka zachová starý hostiteľ wwwroot, ale vynúti DNS rozlíšenie na túto IP.';
$string['transfer_restore_ip_invalid'] = 'Neplatná IP starého servera.';
$string['transfer_restore_missing_remote_data'] = 'Chýbajú vzdialené Moodle údaje na stiahnutie zálohy.';
$string['transfer_restore_no_backups'] = 'Predchádzajúci Moodle nevrátil žiadne vzdialené súbory záloh.';
$string['transfer_restore_no_selection'] = 'Vyberte aspoň jeden vzdialený súbor zálohy na obnovenie.';
$string['transfer_restore_original_category'] = 'Pôvodné ID/názov kategórie';
$string['transfer_restore_original_course'] = 'Pôvodné ID/názov kurzu';
$string['transfer_restore_queue_button'] = 'Zobraziť vzdialené zálohy';
$string['transfer_restore_queue_summary'] = 'Front vzdialenej obnovy bol aktualizovaný. Nové: {$a->queued}. Aktualizované: {$a->updated}. Ignorované: {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'Predchádzajúci Moodle vrátil chybu: {$a}';
$string['transfer_restore_select_file'] = 'Vybrať';
$string['transfer_restore_selected_button'] = 'Obnoviť vybrané';
$string['transfer_restore_session_cleared'] = 'Vzdialené Moodle údaje boli odstránené z vašej relácie.';
$string['transfer_restore_session_saved'] = 'Vzdialené Moodle údaje boli uložené vo vašej relácii.';
$string['transfer_restore_session_summary'] = 'Nájdené vzdialené súbory záloh: {$a}. Vyberte súbory, ktoré chcete obnoviť.';
$string['transfer_restore_source'] = 'Zdroj';
$string['transfer_restore_table_limited'] = 'Zobrazuje sa prvých 50 z {$a} súborov vo fronte.';
$string['transfer_restore_tempfile_error'] = 'Nepodarilo sa vytvoriť dočasný súbor zálohy.';
$string['transfer_restore_title'] = 'Obnoviť z iného Moodle';
$string['transfer_restore_token'] = 'Token prenosu';
$string['transfer_restore_token_counter'] = 'Odpočítavanie platnosti tokenu:';
$string['transfer_restore_token_desc'] = 'Vložte token vygenerovaný v predchádzajúcom Moodle v Backup FTP/Local > Tokeny prenosu.';
$string['transfer_restore_token_required'] = 'Token prenosu je povinný.';
$string['transfer_restore_users_failed'] = 'Vzdialených používateľov sa nepodarilo importovať: {$a}';
$string['transfer_restore_users_summary'] = 'Vzdialení používatelia importovaní. Vytvorené: {$a->created}. Aktualizované: {$a->updated}. Ignorované: {$a->ignored}. Chyby: {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'wwwroot predchádzajúceho Moodle';
$string['transfer_restore_wwwroot_desc'] = 'Príklad: https://ead-antigo.instituicao.edu.br. Neuvádzajte /local/backupftp.';
$string['transfer_restore_wwwroot_invalid'] = 'Neplatný wwwroot predchádzajúceho Moodle.';
$string['transfer_restore_wwwroot_required'] = 'wwwroot predchádzajúceho Moodle je povinný.';
$string['transfer_token_create'] = 'Vytvoriť token';
$string['transfer_token_created_once'] = 'Token bol vytvorený. Skopírujte ho teraz:';
$string['transfer_token_created_once_desc'] = 'Z bezpečnostných dôvodov sa celý token zobrazí iba raz. Potom sa uloží iba hash.';
$string['transfer_token_default_name'] = 'Token prenosu kurzov';
$string['transfer_token_expired'] = 'Expirovaný';
$string['transfer_token_expires'] = 'Expiruje';
$string['transfer_token_lastused'] = 'Naposledy použité';
$string['transfer_token_name'] = 'Názov tokenu';
$string['transfer_token_remaining'] = 'Zostáva';
$string['transfer_token_revoke'] = 'Odvolaný';
$string['transfer_token_revoke_confirm'] = 'Odvolať tento token? API ani sťahovania ho už nebudú prijímať.';
$string['transfer_token_revoked'] = 'Token bol odvolaný.';
$string['transfer_token_status_active'] = 'Aktívny';
$string['transfer_token_uses'] = 'Použitia';
$string['transfer_tokens'] = 'Tokeny prenosu';
$string['transfer_tokens_desc'] = 'Tokeny autorizujú API prenosu a MBZ sťahovania pre {$a}. Vytvorte nový token, keď iný Moodle web potrebuje dočasný prístup.';
$string['view_backup_report'] = 'Sledujte front zálohovania na jednom mieste: čakajúce kurzy, spracúvané položky, dokončené zálohy a záznamy vyžadujúce pozornosť.';
$string['view_restore_report'] = 'Sledujte front obnovy na jednom mieste: vybrané súbory MBZ, spracúvané položky, dokončené obnovy a záznamy vyžadujúce pozornosť.';
