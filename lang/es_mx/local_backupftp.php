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
 * Lang es_mx file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Acceder al curso</a>';
$string['adding_to_category'] = 'Se añadirá a la categoría {$a->categoria}';
$string['already_added_status'] = 'Ya añadido y el estado es {$a->status}';
$string['api_invalid_action'] = 'Acción de API no válida.';
$string['backup_category_select_help'] = 'Seleccione las categorías cuyos cursos deben añadirse a la cola de copia de seguridad. Los botones de cada tarjeta afectan a esa categoría y a todas sus subcategorías.';
$string['backup_courses_and_categories'] = 'Copia de seguridad: Cursos y categorías';
$string['backup_creation_parameters'] = 'La copia de seguridad se creará con los siguientes parámetros';
$string['backup_end'] = 'Copia de seguridad finalizada el';
$string['backup_end_time'] = 'Hora de finalización de la copia de seguridad';
$string['backup_report'] = 'Informe de copia de seguridad';
$string['backup_start'] = 'Copia de seguridad iniciada el';
$string['backup_start_time'] = 'Hora de inicio de la copia de seguridad';
$string['backupftp:manage'] = 'Gestionar copia de seguridad';
$string['categories'] = 'Categorías';
$string['category_created_successfully'] = ' ==> Categoría {$a->categoria_nome} creada correctamente';
$string['category_link'] = 'Categoría <a href="{$a}" target="blank">Categoría raíz</a>';
$string['click_here'] = 'Haga clic aquí';
$string['course'] = 'Curso';
$string['course_added_to_backup_queue'] = 'Curso {$a->course_id} ({$a->course_name}) añadido a la cola de copia de seguridad.';
$string['courses'] = 'Cursos';
$string['courses_and_categories'] = 'Cursos y categorías';
$string['created_at'] = 'Creado el';
$string['created_on'] = 'Creado el';
$string['created_on_time'] = 'Creado el {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'Deseleccionar todo';
$string['error_creating_folder'] = '<span style="color:#d10707">Error al crear la carpeta</span> "<b>{$a->ftppasta}</b>" en FTP con el error "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'Error al descargar el archivo MBZ, con el error "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Error al extraer el archivo MBZ';
$string['file_added_to_restore_queue'] = 'Archivo {$a->file} añadido a la cola de restauración';
$string['file_found_and_downloaded'] = 'Archivo localizado y descargado';
$string['file_size'] = 'con tamaño {$a->size}';
$string['file_size_label'] = 'Tamaño del archivo';
$string['file_uploaded'] = 'Archivo "<b>{$a->file}</b>" subido a "<b>{$a->remote_file}</b>"!';
$string['ftp_error_connecting'] = 'Error al conectar con FTP';
$string['ftp_error_login'] = 'No se pudo conectar con {$a->username}@{$a->url}';
$string['ftp_remote_file_size'] = 'FTP devolvió que el archivo remoto tiene "<b>{$a->size} bytes</b>"';
$string['index_backup_button'] = 'Abrir pantalla de copia de seguridad';
$string['index_backup_desc'] = 'Use esta área para seleccionar cursos y categorías y poner la generación de copias de seguridad en la cola. Los archivos generados pueden guardarse localmente y/o enviarse por FTP, según la configuración del plugin.';
$string['index_backup_report_button'] = 'Ver informe de copia de seguridad';
$string['index_backup_title'] = 'Copia de seguridad de cursos';
$string['index_flow_step1_after_old_moodle'] = 'genere o actualice las copias de seguridad de los cursos que se transferirán.';
$string['index_flow_step2_after_mbz'] = 'descargas solo mientras sea válido.';
$string['index_flow_step2_after_token_before_mbz'] = 'Habilita la API y';
$string['index_flow_step2_before_token'] = 'Todavía en el Moodle antiguo, cree un';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'abra la pantalla de restauración e introduzca el';
$string['index_flow_step3_after_wwwroot'] = ', el token y, si es necesario, la IP de la máquina antigua.';
$string['index_flow_step4'] = 'Revise la lista devuelta por la API y envíe los archivos a la cola. El cron descargará y restaurará los cursos en segundo plano.';
$string['index_flow_step_moodle'] = 'En el';
$string['index_intro_desc'] = 'Este plugin ayuda a migrar cursos de una instalación de Moodle a otra con más seguridad. El Moodle antiguo genera las copias de seguridad y concede acceso mediante un token temporal. El Moodle nuevo consulta la API, lista los archivos disponibles y coloca las restauraciones en la cola para su ejecución por cron.';
$string['index_new_moodle'] = 'Moodle nuevo';
$string['index_old_moodle'] = 'Moodle antiguo';
$string['index_recommended_flow_title'] = 'Flujo recomendado';
$string['index_reports_desc'] = 'Use los informes para hacer seguimiento de lo que ya se colocó en la cola, lo que se está procesando, qué copias de seguridad se completaron y qué restauraciones necesitan atención.';
$string['index_reports_title'] = 'Informes y monitoreo';
$string['index_restore_button'] = 'Abrir pantalla de restauración';
$string['index_restore_desc_after_wwwroot'] = ', el token y, opcionalmente, la IP de la máquina antigua cuando el dominio ya haya sido migrado al nuevo servidor.';
$string['index_restore_desc_before_wwwroot'] = 'Use esta pantalla para importar copias de seguridad desde otro Moodle. Introduzca el';
$string['index_restore_queue_desc'] = 'Después de la consulta, los archivos remotos se colocan en la cola de restauración. Así, la migración puede continuar mediante cron sin depender de que la página esté abierta en el navegador.';
$string['index_restore_report_button'] = 'Ver informe de restauración';
$string['index_restore_title'] = 'Restauración en el Moodle nuevo';
$string['index_title'] = 'Transferencia de cursos entre Moodles';
$string['index_token_time_desc'] = 'El token tiene una vida útil limitada, configurada en esta página de administración. Antes de iniciar una migración grande, confirme que el cron del Moodle nuevo esté activo y que el tiempo restante del token sea suficiente para descargar todas las copias de seguridad requeridas.';
$string['index_token_time_title'] = 'Preste atención a la vida útil del token';
$string['index_tokens_button'] = 'Gestionar tokens';
$string['index_tokens_desc_after_mbz'] = 'El token no sustituye un inicio de sesión administrativo: debe compartirse solo durante la ventana de migración.';
$string['index_tokens_desc_before_mbz'] = 'Cree tokens temporales para permitir que otro Moodle consulte cursos, categorías, copias de seguridad y descargue';
$string['index_transfer_token'] = 'token de transferencia';
$string['log:savelocal:error'] = 'Error al guardar la copia de seguridad localmente: {$a}';
$string['log:savelocal:success'] = 'Copia de seguridad guardada localmente: {$a}';
$string['logs'] = 'Registros';
$string['manual_cron_button'] = 'Abrir ejecución manual';
$string['manual_cron_desc'] = 'Use esta página para procesar ahora copias de seguridad o restauraciones en cola, probar la tarea manualmente o acelerar una migración sin esperar al próximo ciclo programado del CRON de Moodle.';
$string['manual_cron_title'] = 'Ejecución manual del CRON';
$string['mbz_extracted_successfully'] = 'MBZ extraído correctamente';
$string['nothing_to_execute'] = 'Nada que ejecutar';
$string['pluginname'] = 'Backup FTP/Local';
$string['pre_check_failure'] = 'Falló la comprobación previa';
$string['privacy:metadata'] = 'El plugin local_backupftp no recopila ni almacena datos personales ni otros datos sensibles. Solo usa las configuraciones FTP proporcionadas para realizar copias de seguridad, sin registrar ni conservar información relacionada con los usuarios o los datos transferidos.';
$string['processing_file'] = 'Procesando: <b>{$a->remote_file}</b> con {$a->size}';
$string['remote_file'] = 'Archivo remoto';
$string['report'] = 'Informe';
$string['reports'] = 'Informes';
$string['requeue_backup'] = 'Reenviar';
$string['requeue_backup_confirm'] = '¿Reenviar esta copia de seguridad? Se restablecerá y se volverá a colocar en la cola.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">El curso ya existe</a>';
$string['restore_courses_and_categories'] = 'Restauración: Cursos y categorías';
$string['restore_file_select_help'] = 'Seleccione los archivos MBZ que deben añadirse a la cola de restauración. Los botones de cada categoría afectan solo a esa rama.';
$string['restore_report'] = 'Informe de restauración';
$string['runtask_backup_desc'] = 'Procesa cursos ya colocados en la cola de copia de seguridad y genera/envía los archivos MBZ configurados.';
$string['runtask_backup_title'] = 'Ejecutar manualmente la cola de copia de seguridad';
$string['runtask_execute_five_courses'] = 'Procesar hasta 5 elementos ahora';
$string['runtask_execute_ten_courses'] = 'Procesar hasta 10 elementos ahora';
$string['runtask_manual_desc'] = 'Esta página ejecuta las mismas tareas de copia de seguridad y restauración que normalmente ejecuta el CRON de Moodle. Es útil cuando desea procesar la cola manualmente, validar la configuración o acelerar una migración ejecutando un lote pequeño de inmediato.';
$string['runtask_manual_note'] = 'la ejecución manual no sustituye al CRON programado de Moodle. Mantenga activo el CRON normal para que la cola continúe procesándose automáticamente.';
$string['runtask_manual_note_title'] = 'Importante:';
$string['runtask_restore_desc'] = 'Procesa archivos MBZ ya colocados en la cola de restauración y los restaura en Moodle.';
$string['runtask_restore_title'] = 'Ejecutar manualmente la cola de restauración';
$string['select_all'] = 'Seleccionar todo';
$string['settings_categorystart'] = 'ID de categoría raíz';
$string['settings_categorystart_desc'] = 'El ID de la categoría raíz desde la que iniciar la restauración de cursos';
$string['settings_error'] = 'y error';
$string['settings_error_sending_backup'] = 'Error al enviar la copia de seguridad a';
$string['settings_file_size'] = 'con tamaño de archivo';
$string['settings_ftp'] = 'Almacenamiento FTP';
$string['settings_ftpenable'] = 'Enviar a FTP';
$string['settings_ftpnames'] = 'Usar el nombre del curso como nombre del archivo de copia de seguridad';
$string['settings_ftpnames_desc'] = 'Si está marcado, el nombre del archivo enviado será el nombre del curso. De lo contrario, será el nombre que Moodle asigna, similar a backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'Organizar copias de seguridad en FTP por categorías';
$string['settings_ftporganize_desc'] = 'El archivo se guardará como Categoría/Categoría/curso.mbz';
$string['settings_ftppassword'] = 'Contraseña FTP';
$string['settings_ftppasta'] = 'Carpeta FTP remota';
$string['settings_ftppasta_desc'] = 'La carpeta de destino debe comenzar con / y no terminar con / (ej.: /backup, /save/backup)';
$string['settings_ftppasv'] = '¿Enviar archivo en modo pasivo?';
$string['settings_ftppasv_desc'] = 'El modo FTP predeterminado en PHP es el modo activo. El modo activo rara vez funciona debido a firewalls/NAT/proxies. Por lo tanto, casi siempre es necesario usar el modo pasivo.';
$string['settings_ftpurl'] = 'URL FTP';
$string['settings_ftpurl_desc'] = 'Introduzca la dirección IP o el nombre de host del servidor FTP deseado. Si el puerto del servidor FTP es diferente de 21, especifíquelo añadiendo dos puntos (:) seguidos del número de puerto, por ejemplo, 127.0.0.1:29. Si su FTP usa SSL, añada ftps:// antes del dominio.';
$string['settings_ftpusername'] = 'Usuario FTP';
$string['settings_integrations'] = 'Integraciones';
$string['settings_local'] = 'Almacenamiento local';
$string['settings_localfile'] = 'Guardar copias de seguridad en una carpeta local';
$string['settings_localfile_desc'] = 'Si está habilitado, se almacenará una copia de las copias de seguridad en una carpeta local especificada abajo.';
$string['settings_localfilepath'] = 'Ruta a la carpeta local de copias de seguridad';
$string['settings_localfilepath_desc'] = 'Introduzca la ruta completa de la carpeta donde se almacenarán localmente las copias de seguridad. Asegúrese de que el servidor tenga permisos de escritura para esta carpeta. Si se deja en blanco, las copias de seguridad se guardarán en [MOODLEDATA]/backup/';
$string['settings_mbz_settings'] = 'Configuración de generación de copias de seguridad';
$string['settings_restore_settings'] = 'Configuración de restauración';
$string['settings_rootsettinganonymize'] = 'Anonimizar usuarios raíz';
$string['settings_rootsettingusers'] = 'Configuración de usuarios raíz';
$string['settings_tokenduration'] = 'Vida útil del token';
$string['settings_tokenduration_desc'] = 'Cuánto tiempo permanece válido cada token de transferencia generado. El valor predeterminado es 48 horas.';
$string['settings_transfer_api'] = 'API de transferencia de cursos';
$string['settings_transfer_api_desc'] = 'Los tokens de corta duración permiten que otro sitio Moodle liste cursos, categorías y copias de seguridad, y descargue archivos MBZ.';
$string['status'] = 'Estado';
$string['submit'] = 'Enviar';
$string['temporary_files_deleted'] = 'Archivos temporales eliminados';
$string['token_invalid_or_expired'] = 'Token de transferencia no válido o caducado.';
$string['transfer_restore_clear_session_button'] = 'Borrar datos remotos';
$string['transfer_restore_curl_required'] = 'La extensión PHP cURL es necesaria para transferir copias de seguridad desde otro Moodle.';
$string['transfer_restore_desc'] = 'Use esta opción para obtener la lista de copias de seguridad del Moodle anterior. Los datos del formulario se guardan en su sesión y los archivos solo se colocan en la cola de restauración después de seleccionarlos.';
$string['transfer_restore_download_too_small'] = 'El archivo de copia de seguridad descargado está vacío o es demasiado pequeño.';
$string['transfer_restore_downloading'] = 'Descargando copia de seguridad remota desde {$a->url}';
$string['transfer_restore_http_error'] = 'Error al conectar con el Moodle anterior: {$a}';
$string['transfer_restore_http_status'] = 'El Moodle anterior devolvió el estado HTTP {$a}.';
$string['transfer_restore_invalid_backup_file'] = 'Archivo de copia de seguridad remoto no válido.';
$string['transfer_restore_invalid_json'] = 'El Moodle anterior no devolvió una respuesta JSON válida.';
$string['transfer_restore_ip'] = 'IP del servidor antiguo (opcional)';
$string['transfer_restore_ip_desc'] = 'Use solo cuando el dominio ya se haya migrado a este nuevo Moodle. La solicitud mantiene el host wwwroot antiguo, pero fuerza la resolución DNS a esta IP.';
$string['transfer_restore_ip_invalid'] = 'IP del servidor antiguo no válida.';
$string['transfer_restore_missing_remote_data'] = 'Faltan datos del Moodle remoto para descargar la copia de seguridad.';
$string['transfer_restore_no_backups'] = 'El Moodle anterior no devolvió archivos de copia de seguridad remotos.';
$string['transfer_restore_no_selection'] = 'Seleccione al menos un archivo de copia de seguridad remoto para restaurar.';
$string['transfer_restore_original_category'] = 'ID/nombre de categoría original';
$string['transfer_restore_original_course'] = 'ID/nombre de curso original';
$string['transfer_restore_queue_button'] = 'Listar copias de seguridad remotas';
$string['transfer_restore_queue_summary'] = 'Cola de restauración remota actualizada. Nuevos: {$a->queued}. Actualizados: {$a->updated}. Ignorados: {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'El Moodle anterior devolvió un error: {$a}';
$string['transfer_restore_select_file'] = 'Seleccionar';
$string['transfer_restore_selected_button'] = 'Restaurar seleccionados';
$string['transfer_restore_session_cleared'] = 'Datos del Moodle remoto eliminados de su sesión.';
$string['transfer_restore_session_saved'] = 'Datos del Moodle remoto guardados en su sesión.';
$string['transfer_restore_session_summary'] = 'Archivos de copia de seguridad remotos encontrados: {$a}. Seleccione los archivos que desea restaurar.';
$string['transfer_restore_source'] = 'Origen';
$string['transfer_restore_table_limited'] = 'Mostrando los primeros 50 de {$a} archivos en cola.';
$string['transfer_restore_tempfile_error'] = 'No se pudo crear el archivo temporal de copia de seguridad.';
$string['transfer_restore_title'] = 'Restaurar desde otro Moodle';
$string['transfer_restore_token'] = 'Token de transferencia';
$string['transfer_restore_token_counter'] = 'Cuenta regresiva de validez del token:';
$string['transfer_restore_token_desc'] = 'Pegue el token generado en el Moodle anterior en Backup FTP/Local > Tokens de transferencia.';
$string['transfer_restore_token_required'] = 'El token de transferencia es obligatorio.';
$string['transfer_restore_users_failed'] = 'No se pudieron importar los usuarios remotos: {$a}';
$string['transfer_restore_users_summary'] = 'Usuarios remotos importados. Creados: {$a->created}. Actualizados: {$a->updated}. Ignorados: {$a->ignored}. Errores: {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'wwwroot del Moodle anterior';
$string['transfer_restore_wwwroot_desc'] = 'Ejemplo: https://ead-antigo.instituicao.edu.br. No incluya /local/backupftp.';
$string['transfer_restore_wwwroot_invalid'] = 'wwwroot del Moodle anterior no válido.';
$string['transfer_restore_wwwroot_required'] = 'El wwwroot del Moodle anterior es obligatorio.';
$string['transfer_token_create'] = 'Crear token';
$string['transfer_token_created_once'] = 'Token creado. Cópielo ahora:';
$string['transfer_token_created_once_desc'] = 'Por seguridad, el token completo se muestra solo una vez. Después, solo se almacena el hash.';
$string['transfer_token_default_name'] = 'Token de transferencia de cursos';
$string['transfer_token_expired'] = 'Caducado';
$string['transfer_token_expires'] = 'Caduca';
$string['transfer_token_lastused'] = 'Último uso';
$string['transfer_token_name'] = 'Nombre del token';
$string['transfer_token_remaining'] = 'Restante';
$string['transfer_token_revoke'] = 'Revocado';
$string['transfer_token_revoke_confirm'] = '¿Revocar este token? Ya no será aceptado por la API ni por las descargas.';
$string['transfer_token_revoked'] = 'Token revocado.';
$string['transfer_token_status_active'] = 'Activo';
$string['transfer_token_uses'] = 'Usos';
$string['transfer_tokens'] = 'Tokens de transferencia';
$string['transfer_tokens_desc'] = 'Los tokens autorizan la API de transferencia y las descargas MBZ para {$a}. Cree un nuevo token cuando otro sitio Moodle necesite acceso temporal.';
$string['view_backup_report'] = 'Supervise la cola de copia de seguridad en un solo lugar: cursos pendientes, elementos en procesamiento, copias completadas y registros que necesitan atención.';
$string['view_restore_report'] = 'Supervise la cola de restauración en un solo lugar: archivos MBZ seleccionados, elementos en procesamiento, restauraciones completadas y registros que necesitan atención.';
