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
 * Lang fr file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Accéder au cours</a>';
$string['adding_to_category'] = 'Sera ajouté à la catégorie {$a->categoria}';
$string['already_added_status'] = 'Déjà ajouté et le statut est {$a->status}';
$string['api_invalid_action'] = 'Action API non valide.';
$string['backup_category_select_help'] = 'Sélectionnez les catégories dont les cours doivent être ajoutés à la file de sauvegarde. Les boutons de chaque carte agissent sur cette catégorie et toutes ses sous-catégories.';
$string['backup_courses_and_categories'] = 'Sauvegarde : cours et catégories';
$string['backup_creation_parameters'] = 'La sauvegarde sera créée avec les paramètres suivants';
$string['backup_end'] = 'Sauvegarde terminée le';
$string['backup_end_time'] = 'Heure de fin de la sauvegarde';
$string['backup_report'] = 'Rapport de sauvegarde';
$string['backup_start'] = 'Sauvegarde démarrée le';
$string['backup_start_time'] = 'Heure de début de la sauvegarde';
$string['backupftp:manage'] = 'Gérer la sauvegarde';
$string['categories'] = 'Catégories';
$string['category_created_successfully'] = ' ==> Catégorie {$a->categoria_nome} créée avec succès';
$string['category_link'] = 'Catégorie <a href="{$a}" target="blank">Catégorie racine</a>';
$string['click_here'] = 'Cliquez ici';
$string['course'] = 'Cours';
$string['course_added_to_backup_queue'] = 'Cours {$a->course_id} ({$a->course_name}) ajouté à la file de sauvegarde.';
$string['courses'] = 'Cours';
$string['courses_and_categories'] = 'Cours et catégories';
$string['created_at'] = 'Créé le';
$string['created_on'] = 'Créé le';
$string['created_on_time'] = 'Créé le {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'Tout désélectionner';
$string['error_creating_folder'] = '<span style="color:#d10707">Erreur lors de la création du dossier</span> "<b>{$a->ftppasta}</b>" sur FTP avec l\'erreur "<b>{$->errormsg}</b>" !';
$string['error_downloading_file'] = 'Erreur lors du téléchargement du fichier MBZ, avec l\'erreur "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Erreur lors de l\'extraction du fichier MBZ';
$string['file_added_to_restore_queue'] = 'Fichier {$a->file} ajouté à la file de restauration';
$string['file_found_and_downloaded'] = 'Fichier trouvé et téléchargé';
$string['file_size'] = 'avec la taille {$a->size}';
$string['file_size_label'] = 'Taille du fichier';
$string['file_uploaded'] = 'Fichier "<b>{$a->file}</b>" envoyé vers "<b>{$a->remote_file}</b>" !';
$string['ftp_error_connecting'] = 'Erreur de connexion au FTP';
$string['ftp_error_login'] = 'Impossible de se connecter avec {$a->username}@{$a->url}';
$string['ftp_remote_file_size'] = 'FTP a indiqué que le fichier distant a "<b>{$a->size} octets</b>"';
$string['index_backup_button'] = 'Ouvrir l\'écran de sauvegarde';
$string['index_backup_desc'] = 'Utilisez cette zone pour sélectionner les cours et les catégories et placer la génération des sauvegardes dans la file. Les fichiers générés peuvent être enregistrés localement et/ou envoyés par FTP selon les paramètres du plugin.';
$string['index_backup_report_button'] = 'Voir le rapport de sauvegarde';
$string['index_backup_title'] = 'Sauvegarde des cours';
$string['index_flow_step1_after_old_moodle'] = 'générez ou mettez à jour les sauvegardes des cours qui seront transférés.';
$string['index_flow_step2_after_mbz'] = 'téléchargements uniquement tant qu\'il est valide.';
$string['index_flow_step2_after_token_before_mbz'] = 'Il active l\'API et les';
$string['index_flow_step2_before_token'] = 'Toujours dans l\'ancien Moodle, créez un';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'ouvrez l\'écran de restauration et saisissez l\'ancien';
$string['index_flow_step3_after_wwwroot'] = ', le jeton et, si nécessaire, l\'IP de l\'ancienne machine.';
$string['index_flow_step4'] = 'Vérifiez la liste renvoyée par l\'API et envoyez les fichiers dans la file. Le cron téléchargera et restaurera les cours en arrière-plan.';
$string['index_flow_step_moodle'] = 'Dans le';
$string['index_intro_desc'] = 'Ce plugin aide à migrer des cours d\'une installation Moodle vers une autre avec plus de sécurité. L\'ancien Moodle génère les sauvegardes et accorde l\'accès au moyen d\'un jeton temporaire. Le nouveau Moodle interroge l\'API, liste les fichiers disponibles et place les restaurations dans la file pour exécution par cron.';
$string['index_new_moodle'] = 'nouveau Moodle';
$string['index_old_moodle'] = 'ancien Moodle';
$string['index_recommended_flow_title'] = 'Flux recommandé';
$string['index_reports_desc'] = 'Utilisez les rapports pour suivre ce qui a déjà été placé dans la file, ce qui est en cours de traitement, les sauvegardes terminées et les restaurations qui nécessitent une attention.';
$string['index_reports_title'] = 'Rapports et surveillance';
$string['index_restore_button'] = 'Ouvrir l\'écran de restauration';
$string['index_restore_desc_after_wwwroot'] = ', le jeton et, éventuellement, l\'IP de l\'ancienne machine lorsque le domaine a déjà été migré vers le nouveau serveur.';
$string['index_restore_desc_before_wwwroot'] = 'Utilisez cet écran pour importer des sauvegardes depuis un autre Moodle. Saisissez l\'ancien';
$string['index_restore_queue_desc'] = 'Après la requête, les fichiers distants sont placés dans la file de restauration. Ainsi, la migration peut continuer via cron sans dépendre de la page ouverte dans le navigateur.';
$string['index_restore_report_button'] = 'Voir le rapport de restauration';
$string['index_restore_title'] = 'Restauration dans le nouveau Moodle';
$string['index_title'] = 'Transfert de cours entre Moodles';
$string['index_token_time_desc'] = 'Le jeton a une durée de vie limitée, configurée sur cette page d\'administration. Avant de démarrer une grande migration, confirmez que le cron du nouveau Moodle est actif et que le temps restant du jeton suffit pour télécharger toutes les sauvegardes nécessaires.';
$string['index_token_time_title'] = 'Faites attention à la durée de vie du jeton';
$string['index_tokens_button'] = 'Gérer les jetons';
$string['index_tokens_desc_after_mbz'] = 'Le jeton ne remplace pas une connexion administrative : il ne doit être partagé que pendant la fenêtre de migration.';
$string['index_tokens_desc_before_mbz'] = 'Créez des jetons temporaires pour permettre à un autre Moodle de consulter les cours, catégories, sauvegardes et de télécharger';
$string['index_transfer_token'] = 'jeton de transfert';
$string['log:savelocal:error'] = 'Échec de l\'enregistrement local de la sauvegarde : {$a}';
$string['log:savelocal:success'] = 'Sauvegarde enregistrée localement : {$a}';
$string['logs'] = 'Journaux';
$string['manual_cron_button'] = 'Ouvrir l\'exécution manuelle';
$string['manual_cron_desc'] = 'Utilisez cette page pour traiter maintenant les sauvegardes ou restaurations en file, tester la tâche manuellement ou accélérer une migration sans attendre le prochain cycle CRON planifié de Moodle.';
$string['manual_cron_title'] = 'Exécution manuelle du CRON';
$string['mbz_extracted_successfully'] = 'MBZ extrait avec succès';
$string['nothing_to_execute'] = 'Rien à exécuter';
$string['pluginname'] = 'Backup FTP/Local';
$string['pre_check_failure'] = 'Échec de la pré-vérification';
$string['privacy:metadata'] = 'Le plugin local_backupftp ne collecte ni ne stocke de données personnelles ou autres données sensibles. Il utilise uniquement les configurations FTP fournies pour effectuer les sauvegardes, sans journaliser ni conserver les informations relatives aux utilisateurs ou aux données transférées.';
$string['processing_file'] = 'Traitement : <b>{$a->remote_file}</b> avec {$a->size}';
$string['remote_file'] = 'Fichier distant';
$string['report'] = 'Rapport';
$string['reports'] = 'Rapports';
$string['requeue_backup'] = 'Renvoyer';
$string['requeue_backup_confirm'] = 'Renvoyer cette sauvegarde ? Elle sera réinitialisée et remise dans la file.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Le cours existe déjà</a>';
$string['restore_courses_and_categories'] = 'Restauration : cours et catégories';
$string['restore_file_select_help'] = 'Sélectionnez les fichiers MBZ qui doivent être ajoutés à la file de restauration. Les boutons de chaque catégorie n\'affectent que cette branche.';
$string['restore_report'] = 'Rapport de restauration';
$string['runtask_backup_desc'] = 'Traite les cours déjà placés dans la file de sauvegarde et génère/envoie les fichiers MBZ configurés.';
$string['runtask_backup_title'] = 'Exécuter manuellement la file de sauvegarde';
$string['runtask_execute_five_courses'] = 'Traiter jusqu\'à 5 éléments maintenant';
$string['runtask_execute_ten_courses'] = 'Traiter jusqu\'à 10 éléments maintenant';
$string['runtask_manual_desc'] = 'Cette page exécute les mêmes tâches de sauvegarde et de restauration que le CRON Moodle exécute normalement. Elle est utile lorsque vous voulez traiter la file manuellement, valider la configuration ou accélérer une migration en exécutant immédiatement un petit lot.';
$string['runtask_manual_note'] = 'l\'exécution manuelle ne remplace pas le CRON Moodle planifié. Gardez le CRON normal actif afin que la file continue à être traitée automatiquement.';
$string['runtask_manual_note_title'] = 'Important :';
$string['runtask_restore_desc'] = 'Traite les fichiers MBZ déjà placés dans la file de restauration et les restaure dans Moodle.';
$string['runtask_restore_title'] = 'Exécuter manuellement la file de restauration';
$string['select_all'] = 'Tout sélectionner';
$string['settings_categorystart'] = 'ID de la catégorie racine';
$string['settings_categorystart_desc'] = 'L\'ID de la catégorie racine pour commencer la restauration des cours';
$string['settings_error'] = 'et erreur';
$string['settings_error_sending_backup'] = 'Erreur lors de l\'envoi de la sauvegarde vers';
$string['settings_file_size'] = 'avec la taille du fichier';
$string['settings_ftp'] = 'Stockage FTP';
$string['settings_ftpenable'] = 'Envoyer vers FTP';
$string['settings_ftpnames'] = 'Utiliser le nom du cours comme nom du fichier de sauvegarde';
$string['settings_ftpnames_desc'] = 'Si coché, le nom du fichier envoyé sera le nom du cours. Sinon, ce sera le nom attribué par Moodle, similaire à backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'Organiser les sauvegardes sur FTP par catégories';
$string['settings_ftporganize_desc'] = 'Le fichier sera enregistré sous Catégorie/Catégorie/cours.mbz';
$string['settings_ftppassword'] = 'Mot de passe FTP';
$string['settings_ftppasta'] = 'Dossier FTP distant';
$string['settings_ftppasta_desc'] = 'Le dossier de destination doit commencer par / et ne pas se terminer par / (ex. : /backup, /save/backup)';
$string['settings_ftppasv'] = 'Envoyer le fichier en mode passif ?';
$string['settings_ftppasv_desc'] = 'Le mode FTP par défaut en PHP est le mode actif. Le mode actif fonctionne rarement à cause des pare-feux/NAT/proxies. Il faut donc presque toujours utiliser le mode passif.';
$string['settings_ftpurl'] = 'URL FTP';
$string['settings_ftpurl_desc'] = 'Saisissez l\'adresse IP ou le nom d\'hôte du serveur FTP souhaité. Si le port du serveur FTP est différent de 21, indiquez-le en ajoutant deux-points (:) suivis du numéro de port, par exemple 127.0.0.1:29. Si votre FTP utilise SSL, ajoutez ftps:// avant le domaine.';
$string['settings_ftpusername'] = 'Identifiant FTP';
$string['settings_integrations'] = 'Intégrations';
$string['settings_local'] = 'Stockage local';
$string['settings_localfile'] = 'Enregistrer les sauvegardes dans un dossier local';
$string['settings_localfile_desc'] = 'Si activé, une copie des sauvegardes sera stockée dans un dossier local indiqué ci-dessous.';
$string['settings_localfilepath'] = 'Chemin vers le dossier local de sauvegarde';
$string['settings_localfilepath_desc'] = 'Saisissez le chemin complet du dossier où les sauvegardes seront stockées localement. Assurez-vous que le serveur dispose des droits d\'écriture sur ce dossier. Si le champ reste vide, les sauvegardes seront enregistrées dans [MOODLEDATA]/backup/';
$string['settings_mbz_settings'] = 'Paramètres de génération des sauvegardes';
$string['settings_restore_settings'] = 'Paramètres de restauration';
$string['settings_rootsettinganonymize'] = 'Anonymiser les utilisateurs racine';
$string['settings_rootsettingusers'] = 'Paramètre des utilisateurs racine';
$string['settings_tokenduration'] = 'Durée de vie du jeton';
$string['settings_tokenduration_desc'] = 'Durée pendant laquelle chaque jeton de transfert généré reste valide. La valeur par défaut est de 48 heures.';
$string['settings_transfer_api'] = 'API de transfert des cours';
$string['settings_transfer_api_desc'] = 'Des jetons de courte durée permettent à un autre site Moodle de lister les cours, catégories et sauvegardes, et de télécharger des fichiers MBZ.';
$string['status'] = 'Statut';
$string['submit'] = 'Envoyer';
$string['temporary_files_deleted'] = 'Fichiers temporaires supprimés';
$string['token_invalid_or_expired'] = 'Jeton de transfert non valide ou expiré.';
$string['transfer_restore_clear_session_button'] = 'Effacer les données distantes';
$string['transfer_restore_curl_required'] = 'L\'extension PHP cURL est requise pour transférer des sauvegardes depuis un autre Moodle.';
$string['transfer_restore_desc'] = 'Utilisez cette option pour récupérer la liste des sauvegardes depuis le Moodle précédent. Les données du formulaire sont enregistrées dans votre session et les fichiers ne sont placés dans la file de restauration qu\'après votre sélection.';
$string['transfer_restore_download_too_small'] = 'Le fichier de sauvegarde téléchargé est vide ou trop petit.';
$string['transfer_restore_downloading'] = 'Téléchargement de la sauvegarde distante depuis {$a->url}';
$string['transfer_restore_http_error'] = 'Erreur de connexion au Moodle précédent : {$a}';
$string['transfer_restore_http_status'] = 'Le Moodle précédent a renvoyé le statut HTTP {$a}.';
$string['transfer_restore_invalid_backup_file'] = 'Fichier de sauvegarde distant non valide.';
$string['transfer_restore_invalid_json'] = 'Le Moodle précédent n\'a pas renvoyé de réponse JSON valide.';
$string['transfer_restore_ip'] = 'IP de l\'ancien serveur (facultatif)';
$string['transfer_restore_ip_desc'] = 'Utilisez uniquement lorsque le domaine a déjà été migré vers ce nouveau Moodle. La requête conserve l\'ancien hôte wwwroot, mais force la résolution DNS vers cette IP.';
$string['transfer_restore_ip_invalid'] = 'IP de l\'ancien serveur non valide.';
$string['transfer_restore_missing_remote_data'] = 'Données du Moodle distant manquantes pour télécharger la sauvegarde.';
$string['transfer_restore_no_backups'] = 'Aucun fichier de sauvegarde distant n\'a été renvoyé par le Moodle précédent.';
$string['transfer_restore_no_selection'] = 'Sélectionnez au moins un fichier de sauvegarde distant à restaurer.';
$string['transfer_restore_original_category'] = 'ID/nom de la catégorie d\'origine';
$string['transfer_restore_original_course'] = 'ID/nom du cours d\'origine';
$string['transfer_restore_queue_button'] = 'Lister les sauvegardes distantes';
$string['transfer_restore_queue_summary'] = 'File de restauration distante mise à jour. Nouveaux : {$a->queued}. Mis à jour : {$a->updated}. Ignorés : {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'Le Moodle précédent a renvoyé une erreur : {$a}';
$string['transfer_restore_select_file'] = 'Sélectionner';
$string['transfer_restore_selected_button'] = 'Restaurer les éléments sélectionnés';
$string['transfer_restore_session_cleared'] = 'Données du Moodle distant supprimées de votre session.';
$string['transfer_restore_session_saved'] = 'Données du Moodle distant enregistrées dans votre session.';
$string['transfer_restore_session_summary'] = 'Fichiers de sauvegarde distants trouvés : {$a}. Sélectionnez les fichiers que vous souhaitez restaurer.';
$string['transfer_restore_source'] = 'Source';
$string['transfer_restore_table_limited'] = 'Affichage des 50 premiers fichiers en file sur {$a}.';
$string['transfer_restore_tempfile_error'] = 'Impossible de créer le fichier temporaire de sauvegarde.';
$string['transfer_restore_title'] = 'Restaurer depuis un autre Moodle';
$string['transfer_restore_token'] = 'Jeton de transfert';
$string['transfer_restore_token_counter'] = 'Compte à rebours de validité du jeton :';
$string['transfer_restore_token_desc'] = 'Collez le jeton généré dans le Moodle précédent sous Backup FTP/Local > Jetons de transfert.';
$string['transfer_restore_token_required'] = 'Le jeton de transfert est requis.';
$string['transfer_restore_users_failed'] = 'Les utilisateurs distants n\'ont pas pu être importés : {$a}';
$string['transfer_restore_users_summary'] = 'Utilisateurs distants importés. Créés : {$a->created}. Mis à jour : {$a->updated}. Ignorés : {$a->ignored}. Erreurs : {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'wwwroot du Moodle précédent';
$string['transfer_restore_wwwroot_desc'] = 'Exemple : https://ead-antigo.instituicao.edu.br. N\'incluez pas /local/backupftp.';
$string['transfer_restore_wwwroot_invalid'] = 'wwwroot du Moodle précédent non valide.';
$string['transfer_restore_wwwroot_required'] = 'Le wwwroot du Moodle précédent est requis.';
$string['transfer_token_create'] = 'Créer un jeton';
$string['transfer_token_created_once'] = 'Jeton créé. Copiez-le maintenant :';
$string['transfer_token_created_once_desc'] = 'Pour des raisons de sécurité, le jeton complet n\'est affiché qu\'une seule fois. Ensuite, seul le hachage est stocké.';
$string['transfer_token_default_name'] = 'Jeton de transfert de cours';
$string['transfer_token_expired'] = 'Expiré';
$string['transfer_token_expires'] = 'Expire';
$string['transfer_token_lastused'] = 'Dernière utilisation';
$string['transfer_token_name'] = 'Nom du jeton';
$string['transfer_token_remaining'] = 'Restant';
$string['transfer_token_revoke'] = 'Révoqué';
$string['transfer_token_revoke_confirm'] = 'Révoquer ce jeton ? Il ne sera plus accepté par l\'API ou les téléchargements.';
$string['transfer_token_revoked'] = 'Jeton révoqué.';
$string['transfer_token_status_active'] = 'Actif';
$string['transfer_token_uses'] = 'Utilisations';
$string['transfer_tokens'] = 'Jetons de transfert';
$string['transfer_tokens_desc'] = 'Les jetons autorisent l\'API de transfert et les téléchargements MBZ pour {$a}. Créez un nouveau jeton lorsqu\'un autre site Moodle a besoin d\'un accès temporaire.';
$string['view_backup_report'] = 'Suivez la file de sauvegarde en un seul endroit : cours en attente, éléments en traitement, sauvegardes terminées et enregistrements nécessitant une attention.';
$string['view_restore_report'] = 'Suivez la file de restauration en un seul endroit : fichiers MBZ sélectionnés, éléments en traitement, restaurations terminées et enregistrements nécessitant une attention.';
