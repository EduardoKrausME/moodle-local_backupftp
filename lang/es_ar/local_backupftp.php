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
 * Argentinian Spanish language overrides
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['adding_to_category'] = 'Se agregará a la categoría {$a->categoria}';
$string['already_added_status'] = 'Ya agregado y el estado es {$a->status}';
$string['automatic_copies'] = 'Cantidad de copias por curso y almacenamiento';
$string['automatic_intro'] = 'Moodle genera las copias de seguridad programadas de los cursos. Este plugin recibe la notificación de finalización de la copia y envía automáticamente el archivo generado a los destinos de almacenamiento activos seleccionados. Configurá la programación en Moodle y mantené el cron activo.';
$string['automatic_invalidcopies'] = 'Ingresá una cantidad entre 1 y 36500.';
$string['automatic_invaliddays'] = 'Ingresá un número entre 1 y 36500.';
$string['automatic_keep_count'] = 'Conservar las últimas copias por cantidad';
$string['automatic_local_retention'] = 'La retención solo se aplica a las copias automáticas enviadas por el plugin, no a las copias manuales ni a las originales de Moodle. Los días se cuentan desde que termina el envío. Configurá la retención nativa por separado. Desactivar los envíos también pausa la eliminación automática. La retención por cantidad se aplica por curso y almacenamiento seleccionado, considerando solo las copias enviadas correctamente.';
$string['automatic_nohistory'] = 'Todavía no se recibieron copias automáticas.';
$string['automatic_nostorage'] = 'Ningún almacenamiento activo admite copias automáticas. Configurá primero un destino de almacenamiento.';
$string['automatic_status_expired'] = 'Retención vencida; copias eliminadas';
$string['backup_category_select_help'] = 'Seleccioná las categorías cuyos cursos deben agregarse a la cola de copia de seguridad. Los botones de cada tarjeta afectan a esa categoría y a todas sus subcategorías.';
$string['backup_destination_missing'] = 'No se creó el archivo MBZ de copia de seguridad.';
$string['backup_errors_found_desc'] = 'Fallaron {$a} tareas de copia de seguridad. Revisá los detalles de los errores y volvé a ponerlas en cola después de corregir el problema.';
$string['backup_ftp_upload_failed'] = 'No se pudo enviar la copia de seguridad a {$a->remote_file}. Tamaño del archivo: {$a->size}. Error: {$a->error}';
$string['backup_no_destination'] = 'No hay ningún destino de copia de seguridad habilitado. Habilitá FTP o el almacenamiento local en la configuración del plugin.';
$string['click_here'] = 'Hacé clic acá';
$string['course_added_to_backup_queue'] = 'Curso {$a->course_id} ({$a->course_name}) agregado a la cola de copia de seguridad.';
$string['file_added_to_restore_queue'] = 'Archivo {$a->file} agregado a la cola de restauración';
$string['index_backup_desc'] = 'Usá esta área para seleccionar cursos y categorías y poner la generación de copias de seguridad en la cola. Los archivos generados pueden guardarse localmente y/o enviarse por FTP, según la configuración del plugin.';
$string['index_flow_step1_after_old_moodle'] = 'generá o actualizá las copias de seguridad de los cursos que se transferirán.';
$string['index_flow_step2_before_token'] = 'Todavía en el Moodle antiguo, creá un';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'abrí la pantalla de restauración e ingresá el';
$string['index_flow_step4'] = 'Revisá la lista devuelta por la API y enviá los archivos a la cola. El cron descargará y restaurará los cursos en segundo plano.';
$string['index_reports_desc'] = 'Usá los informes para hacer seguimiento de lo que ya se colocó en la cola, lo que se está procesando, qué copias de seguridad se completaron y qué restauraciones necesitan atención.';
$string['index_reports_title'] = 'Informes y monitoreo';
$string['index_restore_desc_before_wwwroot'] = 'Usá esta pantalla para importar copias de seguridad desde otro Moodle. Ingresá el';
$string['index_token_time_desc'] = 'El token tiene una vida útil limitada, configurada en esta página de administración. Antes de iniciar una migración grande, confirmá que el cron del Moodle nuevo esté activo y que el tiempo restante del token sea suficiente para descargar todas las copias de seguridad requeridas.';
$string['index_token_time_title'] = 'Prestá atención a la vida útil del token';
$string['index_tokens_desc_before_mbz'] = 'Creá tokens temporales para permitir que otro Moodle consulte cursos, categorías, copias de seguridad y descargue';
$string['manual_cron_desc'] = 'Usá esta página para procesar ahora copias de seguridad o restauraciones en cola, probar la tarea manualmente o acelerar una migración sin esperar al próximo ciclo programado del CRON de Moodle.';
$string['manual_cron_result_error'] = 'La tarea finalizó con errores';
$string['manual_cron_result_success'] = 'La tarea finalizó correctamente';
$string['restore_file_select_help'] = 'Seleccioná los archivos MBZ que deben agregarse a la cola de restauración. Los botones de cada categoría afectan solo a esa rama.';
$string['runtask_manual_desc'] = 'Esta página ejecuta las mismas tareas de copia de seguridad y restauración que normalmente ejecuta el CRON de Moodle. Es útil cuando querés procesar la cola manualmente, validar la configuración o acelerar una migración ejecutando un lote pequeño de inmediato.';
$string['runtask_manual_note'] = 'la ejecución manual no sustituye al CRON programado de Moodle. Mantené activo el CRON normal para que la cola continúe procesándose automáticamente.';
$string['servicecontract_index_desc'] = 'Compará las funciones de servicios web disponibles, sus parámetros y tipos de retorno entre un Moodle de origen y uno de destino antes de la migración.';
$string['servicecontract_intro'] = 'Compará los contratos de servicios web de Moodle antes de migrar los cursos. Descargá una instantánea del Moodle de origen y comparala con este destino, o cargá instantáneas de dos instalaciones. Solo se examinan las estructuras declaradas de parámetros y valores de retorno; no se ejecuta ninguna función de servicio web.';
$string['servicecontract_invalid_snapshot'] = 'Instantánea JSON no válida o ausente. Exportá el archivo desde Backup FTP/Local y asegurate de que no supere los 8 MiB.';
$string['servicecontract_privacy'] = 'Las instantáneas contienen firmas de la API e información sobre la versión de Moodle, no tokens de API, datos de cuentas ni valores predeterminados de parámetros. Guardá los archivos exportados en una ubicación de acceso restringido.';
$string['servicecontract_review'] = 'Una diferencia no implica necesariamente incompatibilidad. Revisá cada firma modificada antes de restaurar cursos o conectar integraciones externas.';
$string['servicecontract_same'] = 'No se encontraron diferencias en los contratos de las funciones de las instantáneas.';
$string['servicecontract_status_added'] = 'Agregada en el destino';
$string['servicecontract_target_help'] = 'Dejá el campo vacío para comparar con el Moodle en el que está abierta esta página.';
$string['servicecontract_unavailable'] = 'No se pudieron generar o comparar los contratos de servicios web. Consultá los registros de depuración de Moodle.';
$string['settings_ftpurl_desc'] = 'Ingresá la dirección IP o el nombre de host del servidor FTP deseado. Si el puerto del servidor FTP es diferente de 21, especificalo agregando dos puntos (:) seguidos del número de puerto, por ejemplo, 127.0.0.1:29. Si tu FTP usa SSL, agregá ftps:// antes del dominio.';
$string['settings_localfilepath_desc'] = 'Ingresá la ruta completa de la carpeta donde se almacenarán localmente las copias de seguridad. Asegurate de que el servidor tenga permisos de escritura para esta carpeta. Si se deja en blanco, las copias de seguridad se guardarán en [MOODLEDATA]/backup/';
$string['storage_add'] = 'Agregar sistema de almacenamiento de copias de seguridad';
$string['storage_add_new'] = 'Agregar nuevo...';
$string['storage_failure'] = 'Falló el destino de almacenamiento {$a}.';
$string['storage_select'] = 'Seleccioná al menos un destino de almacenamiento de copias de seguridad.';
$string['storage_systems_description'] = 'Configurá uno o más destinos de copia de seguridad independientes mediante los proveedores de almacenamiento instalados.';
$string['token_invalid_or_expired'] = 'Token de transferencia no válido o vencido.';
$string['transfer_restore_desc'] = 'Usá esta opción para obtener la lista de copias de seguridad del Moodle anterior. Los datos del formulario se guardan en tu sesión y los archivos solo se colocan en la cola de restauración después de seleccionarlos.';
$string['transfer_restore_ip_desc'] = 'Usá esta opción solo cuando el dominio ya se haya migrado a este nuevo Moodle. La solicitud mantiene el host wwwroot antiguo, pero fuerza la resolución DNS a esta IP.';
$string['transfer_restore_no_selection'] = 'Seleccioná al menos un archivo de copia de seguridad remoto para restaurar.';
$string['transfer_restore_session_cleared'] = 'Datos del Moodle remoto eliminados de tu sesión.';
$string['transfer_restore_session_saved'] = 'Datos del Moodle remoto guardados en tu sesión.';
$string['transfer_restore_session_summary'] = 'Archivos de copia de seguridad remotos encontrados: {$a}. Seleccioná los archivos que querés restaurar.';
$string['transfer_restore_token_counter'] = 'Cuenta regresiva de validez del token:';
$string['transfer_restore_token_desc'] = 'Pegá el token generado en el Moodle anterior en Backup FTP/Local > Tokens de transferencia.';
$string['transfer_restore_wwwroot_desc'] = 'Ejemplo: https://ead-antigo.instituicao.edu.br. No incluyas /local/backupftp.';
$string['transfer_token_created_once'] = 'Token creado. Copialo ahora:';
$string['transfer_token_expired'] = 'Vencido';
$string['transfer_token_expires'] = 'Vence';
$string['transfer_tokens_desc'] = 'Los tokens autorizan la API de transferencia y las descargas MBZ para {$a}. Creá un nuevo token cuando otro sitio Moodle necesite acceso temporal.';
$string['view_backup_report'] = 'Supervisá la cola de copia de seguridad en un solo lugar: cursos pendientes, elementos en procesamiento, copias completadas y registros que necesitan atención.';
$string['view_restore_report'] = 'Supervisá la cola de restauración en un solo lugar: archivos MBZ seleccionados, elementos en procesamiento, restauraciones completadas y registros que necesitan atención.';
