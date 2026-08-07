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
 * Lang ru file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Открыть курс</a>';
$string['adding_to_category'] = 'Будет добавлено в категорию {$a->categoria}';
$string['already_added_status'] = 'Уже добавлено, статус: {$a->status}';
$string['api_invalid_action'] = 'Недопустимое действие API.';
$string['backup_category_select_help'] = 'Выберите категории, курсы которых должны быть добавлены в очередь резервного копирования. Кнопки в каждой карточке влияют на эту категорию и все ее подкатегории.';
$string['backup_courses_and_categories'] = 'Резервное копирование: курсы и категории';
$string['backup_creation_parameters'] = 'Резервная копия будет создана со следующими параметрами';
$string['backup_end'] = 'Резервное копирование завершено';
$string['backup_end_time'] = 'Время окончания резервного копирования';
$string['backup_report'] = 'Отчет о резервном копировании';
$string['backup_start'] = 'Резервное копирование начато';
$string['backup_start_time'] = 'Время начала резервного копирования';
$string['backupftp:manage'] = 'Управлять резервным копированием';
$string['categories'] = 'Категории';
$string['category_created_successfully'] = ' ==> Категория {$a->categoria_nome} успешно создана';
$string['category_link'] = 'Категория <a href="{$a}" target="blank">Корневая категория</a>';
$string['click_here'] = 'Нажмите здесь';
$string['course'] = 'Курс';
$string['course_added_to_backup_queue'] = 'Курс {$a->course_id} ({$a->course_name}) добавлен в очередь резервного копирования.';
$string['courses'] = 'Курсы';
$string['courses_and_categories'] = 'Курсы и категории';
$string['created_at'] = 'Создано';
$string['created_on'] = 'Создано';
$string['created_on_time'] = 'Создано {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'Снять выделение со всех';
$string['error_creating_folder'] = '<span style="color:#d10707">Ошибка создания папки</span> "<b>{$a->ftppasta}</b>" на FTP с ошибкой "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'Ошибка загрузки файла MBZ, ошибка "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Ошибка извлечения файла MBZ';
$string['file_added_to_restore_queue'] = 'Файл {$a->file} добавлен в очередь восстановления';
$string['file_found_and_downloaded'] = 'Файл найден и загружен';
$string['file_size'] = 'размер {$a->size}';
$string['file_size_label'] = 'Размер файла';
$string['file_uploaded'] = 'Файл "<b>{$a->file}</b>" загружен в "<b>{$a->remote_file}</b>"!';
$string['ftp_error_connecting'] = 'Ошибка подключения к FTP';
$string['ftp_error_login'] = 'Не удалось подключиться с {$a->username}@{$a->url}';
$string['ftp_remote_file_size'] = 'FTP сообщил, что удаленный файл имеет размер "<b>{$a->size} байт</b>"';
$string['index_backup_button'] = 'Открыть экран резервного копирования';
$string['index_backup_desc'] = 'Используйте эту область для выбора курсов и категорий и постановки создания резервных копий в очередь. Созданные файлы могут быть сохранены локально и/или отправлены на FTP согласно настройкам плагина.';
$string['index_backup_report_button'] = 'Просмотреть отчет о резервном копировании';
$string['index_backup_title'] = 'Резервное копирование курсов';
$string['index_flow_step1_after_old_moodle'] = 'создайте или обновите резервные копии курсов, которые будут перенесены.';
$string['index_flow_step2_after_mbz'] = 'загрузки только пока он действителен.';
$string['index_flow_step2_after_token_before_mbz'] = 'Он включает API и';
$string['index_flow_step2_before_token'] = 'Еще в старом Moodle создайте';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'откройте экран восстановления и укажите старый';
$string['index_flow_step3_after_wwwroot'] = ', токен и, при необходимости, IP старой машины.';
$string['index_flow_step4'] = 'Проверьте список, возвращенный API, и отправьте файлы в очередь. Cron загрузит и восстановит курсы в фоновом режиме.';
$string['index_flow_step_moodle'] = 'В';
$string['index_intro_desc'] = 'Этот плагин помогает безопаснее переносить курсы из одной установки Moodle в другую. Старый Moodle создает резервные копии и предоставляет доступ через временный токен. Новый Moodle запрашивает API, показывает доступные файлы и помещает восстановления в очередь для выполнения cron.';
$string['index_new_moodle'] = 'новом Moodle';
$string['index_old_moodle'] = 'старом Moodle';
$string['index_recommended_flow_title'] = 'Рекомендуемый порядок';
$string['index_reports_desc'] = 'Используйте отчеты, чтобы отслеживать, что уже помещено в очередь, что обрабатывается, какие резервные копии завершены и какие восстановления требуют внимания.';
$string['index_reports_title'] = 'Отчеты и мониторинг';
$string['index_restore_button'] = 'Открыть экран восстановления';
$string['index_restore_desc_after_wwwroot'] = ', токен и, при необходимости, IP старой машины, если домен уже перенесен на новый сервер.';
$string['index_restore_desc_before_wwwroot'] = 'Используйте этот экран для импорта резервных копий из другого Moodle. Укажите старый';
$string['index_restore_queue_desc'] = 'После запроса удаленные файлы помещаются в очередь восстановления. Так миграция может продолжаться через cron без необходимости держать страницу открытой в браузере.';
$string['index_restore_report_button'] = 'Просмотреть отчет о восстановлении';
$string['index_restore_title'] = 'Восстановление в новом Moodle';
$string['index_title'] = 'Передача курсов между Moodle';
$string['index_token_time_desc'] = 'Токен имеет ограниченный срок действия, настроенный на этой странице администрирования. Перед большой миграцией убедитесь, что cron нового Moodle активен и оставшегося времени токена достаточно для загрузки всех необходимых резервных копий.';
$string['index_token_time_title'] = 'Обратите внимание на срок действия токена';
$string['index_tokens_button'] = 'Управлять токенами';
$string['index_tokens_desc_after_mbz'] = 'Токен не заменяет административный вход: им следует делиться только во время окна миграции.';
$string['index_tokens_desc_before_mbz'] = 'Создайте временные токены, чтобы другой Moodle мог запрашивать курсы, категории, резервные копии и загружать';
$string['index_transfer_token'] = 'токен передачи';
$string['log:savelocal:error'] = 'Не удалось сохранить резервную копию локально: {$a}';
$string['log:savelocal:success'] = 'Резервная копия сохранена локально: {$a}';
$string['logs'] = 'Журналы';
$string['manual_cron_button'] = 'Открыть ручное выполнение';
$string['manual_cron_desc'] = 'Используйте эту страницу, чтобы сейчас обработать резервные копии или восстановления в очереди, вручную протестировать задачу или ускорить миграцию, не ожидая следующего запланированного цикла Moodle CRON.';
$string['manual_cron_title'] = 'Ручное выполнение CRON';
$string['mbz_extracted_successfully'] = 'MBZ успешно извлечен';
$string['nothing_to_execute'] = 'Нечего выполнять';
$string['pluginname'] = 'Backup FTP/Local';
$string['pre_check_failure'] = 'Предварительная проверка не пройдена';
$string['privacy:metadata'] = 'Плагин local_backupftp не собирает и не хранит персональные данные или другие конфиденциальные данные. Он использует только предоставленные настройки FTP для выполнения резервного копирования, не регистрируя и не сохраняя информацию о пользователях или передаваемых данных.';
$string['processing_file'] = 'Обработка: <b>{$a->remote_file}</b> размер {$a->size}';
$string['remote_file'] = 'Удаленный файл';
$string['report'] = 'Отчет';
$string['reports'] = 'Отчеты';
$string['requeue_backup'] = 'Отправить повторно';
$string['requeue_backup_confirm'] = 'Повторно отправить эту резервную копию? Она будет сброшена и снова помещена в очередь.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Курс уже существует</a>';
$string['restore_courses_and_categories'] = 'Восстановление: курсы и категории';
$string['restore_file_select_help'] = 'Выберите файлы MBZ, которые должны быть добавлены в очередь восстановления. Кнопки в каждой категории влияют только на эту ветку.';
$string['restore_report'] = 'Отчет о восстановлении';
$string['runtask_backup_desc'] = 'Обрабатывает курсы, уже помещенные в очередь резервного копирования, и создает/отправляет настроенные файлы MBZ.';
$string['runtask_backup_title'] = 'Запустить очередь резервного копирования вручную';
$string['runtask_execute_five_courses'] = 'Обработать до 5 элементов сейчас';
$string['runtask_execute_ten_courses'] = 'Обработать до 10 элементов сейчас';
$string['runtask_manual_desc'] = 'Эта страница выполняет те же задачи резервного копирования и восстановления, которые обычно выполняет Moodle CRON. Она полезна, когда нужно обработать очередь вручную, проверить конфигурацию или ускорить миграцию, сразу запустив небольшой пакет.';
$string['runtask_manual_note'] = 'ручное выполнение не заменяет запланированный Moodle CRON. Держите обычный CRON активным, чтобы очередь продолжала обрабатываться автоматически.';
$string['runtask_manual_note_title'] = 'Важно:';
$string['runtask_restore_desc'] = 'Обрабатывает файлы MBZ, уже помещенные в очередь восстановления, и восстанавливает их в Moodle.';
$string['runtask_restore_title'] = 'Запустить очередь восстановления вручную';
$string['select_all'] = 'Выбрать все';
$string['settings_categorystart'] = 'ID корневой категории';
$string['settings_categorystart_desc'] = 'ID корневой категории, с которой начинать восстановление курсов';
$string['settings_error'] = 'и ошибка';
$string['settings_error_sending_backup'] = 'Ошибка отправки резервной копии в';
$string['settings_file_size'] = 'с размером файла';
$string['settings_ftp'] = 'FTP-хранилище';
$string['settings_ftpenable'] = 'Отправлять на FTP';
$string['settings_ftpnames'] = 'Использовать название курса как имя файла резервной копии';
$string['settings_ftpnames_desc'] = 'Если отмечено, имя отправленного файла будет названием курса. В противном случае будет использовано имя, назначенное Moodle, например backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'Организовать резервные копии на FTP по категориям';
$string['settings_ftporganize_desc'] = 'Файл будет сохранен как Категория/Категория/course.mbz';
$string['settings_ftppassword'] = 'Пароль FTP';
$string['settings_ftppasta'] = 'Удаленная папка FTP';
$string['settings_ftppasta_desc'] = 'Папка назначения должна начинаться с / и не заканчиваться на / (например, /backup, /save/backup)';
$string['settings_ftppasv'] = 'Отправлять файл в пассивном режиме?';
$string['settings_ftppasv_desc'] = 'Режим FTP по умолчанию в PHP — активный. Активный режим редко работает из-за firewall/NAT/proxy. Поэтому почти всегда необходимо использовать пассивный режим.';
$string['settings_ftpurl'] = 'URL FTP';
$string['settings_ftpurl_desc'] = 'Введите IP-адрес или имя хоста нужного FTP-сервера. Если порт FTP-сервера отличается от 21, укажите его, добавив двоеточие (:) и номер порта, например 127.0.0.1:29. Если ваш FTP использует SSL, добавьте ftps:// перед доменом.';
$string['settings_ftpusername'] = 'Логин FTP';
$string['settings_integrations'] = 'Интеграции';
$string['settings_local'] = 'Локальное хранилище';
$string['settings_localfile'] = 'Сохранять резервные копии в локальную папку';
$string['settings_localfile_desc'] = 'Если включено, копия резервных копий будет сохраняться в указанной ниже локальной папке.';
$string['settings_localfilepath'] = 'Путь к локальной папке резервных копий';
$string['settings_localfilepath_desc'] = 'Введите полный путь к папке, где резервные копии будут храниться локально. Убедитесь, что сервер имеет права на запись в эту папку. Если оставить пустым, резервные копии будут сохранены в [MOODLEDATA]/backup/';
$string['settings_mbz_settings'] = 'Настройки создания резервных копий';
$string['settings_restore_settings'] = 'Настройки восстановления';
$string['settings_rootsettinganonymize'] = 'Анонимизировать root-пользователей';
$string['settings_rootsettingusers'] = 'Настройка root-пользователей';
$string['settings_tokenduration'] = 'Срок действия токена';
$string['settings_tokenduration_desc'] = 'Как долго каждый созданный токен передачи остается действительным. По умолчанию 48 часов.';
$string['settings_transfer_api'] = 'API передачи курсов';
$string['settings_transfer_api_desc'] = 'Краткосрочные токены позволяют другому сайту Moodle получать список курсов, категорий и резервных копий, а также загружать файлы MBZ.';
$string['status'] = 'Статус';
$string['submit'] = 'Отправить';
$string['token_invalid_or_expired'] = 'Недействительный или истекший токен передачи.';
$string['transfer_restore_clear_session_button'] = 'Очистить удаленные данные';
$string['transfer_restore_curl_required'] = 'Расширение PHP cURL требуется для передачи резервных копий из другого Moodle.';
$string['transfer_restore_desc'] = 'Используйте эту опцию, чтобы получить список резервных копий из предыдущего Moodle. Данные формы сохраняются в вашей сессии, а файлы помещаются в очередь восстановления только после выбора.';
$string['transfer_restore_download_too_small'] = 'Загруженный файл резервной копии пуст или слишком мал.';
$string['transfer_restore_downloading'] = 'Загрузка удаленной резервной копии с {$a->url}';
$string['transfer_restore_http_error'] = 'Ошибка подключения к предыдущему Moodle: {$a}';
$string['transfer_restore_http_status'] = 'Предыдущий Moodle вернул HTTP-статус {$a}.';
$string['transfer_restore_invalid_backup_file'] = 'Недействительный удаленный файл резервной копии.';
$string['transfer_restore_invalid_json'] = 'Предыдущий Moodle не вернул корректный JSON-ответ.';
$string['transfer_restore_ip'] = 'IP старого сервера (необязательно)';
$string['transfer_restore_ip_desc'] = 'Используйте только когда домен уже перенесен на этот новый Moodle. Запрос сохраняет старый хост wwwroot, но принудительно разрешает DNS на этот IP.';
$string['transfer_restore_ip_invalid'] = 'Недействительный IP старого сервера.';
$string['transfer_restore_missing_remote_data'] = 'Отсутствуют данные удаленного Moodle для загрузки резервной копии.';
$string['transfer_restore_no_backups'] = 'Предыдущий Moodle не вернул удаленные файлы резервных копий.';
$string['transfer_restore_no_selection'] = 'Выберите хотя бы один удаленный файл резервной копии для восстановления.';
$string['transfer_restore_original_category'] = 'Исходный ID/название категории';
$string['transfer_restore_original_course'] = 'Исходный ID/название курса';
$string['transfer_restore_queue_button'] = 'Показать удаленные резервные копии';
$string['transfer_restore_queue_summary'] = 'Очередь удаленного восстановления обновлена. Новые: {$a->queued}. Обновленные: {$a->updated}. Игнорированные: {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'Предыдущий Moodle вернул ошибку: {$a}';
$string['transfer_restore_select_file'] = 'Выбрать';
$string['transfer_restore_selected_button'] = 'Восстановить выбранные';
$string['transfer_restore_session_cleared'] = 'Данные удаленного Moodle удалены из вашей сессии.';
$string['transfer_restore_session_saved'] = 'Данные удаленного Moodle сохранены в вашей сессии.';
$string['transfer_restore_session_summary'] = 'Найдены удаленные файлы резервных копий: {$a}. Выберите файлы, которые хотите восстановить.';
$string['transfer_restore_source'] = 'Источник';
$string['transfer_restore_table_limited'] = 'Показаны первые 50 из {$a} файлов в очереди.';
$string['transfer_restore_tempfile_error'] = 'Не удалось создать временный файл резервной копии.';
$string['transfer_restore_title'] = 'Восстановить из другого Moodle';
$string['transfer_restore_token'] = 'Токен передачи';
$string['transfer_restore_token_counter'] = 'Обратный отсчет срока действия токена:';
$string['transfer_restore_token_desc'] = 'Вставьте токен, созданный в предыдущем Moodle, в Backup FTP/Local > Токены передачи.';
$string['transfer_restore_token_required'] = 'Токен передачи обязателен.';
$string['transfer_restore_users_failed'] = 'Не удалось импортировать удаленных пользователей: {$a}';
$string['transfer_restore_users_summary'] = 'Удаленные пользователи импортированы. Создано: {$a->created}. Обновлено: {$a->updated}. Игнорировано: {$a->ignored}. Ошибки: {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'wwwroot предыдущего Moodle';
$string['transfer_restore_wwwroot_desc'] = 'Пример: https://ead-antigo.instituicao.edu.br. Не включайте /local/backupftp.';
$string['transfer_restore_wwwroot_invalid'] = 'Недействительный wwwroot предыдущего Moodle.';
$string['transfer_restore_wwwroot_required'] = 'wwwroot предыдущего Moodle обязателен.';
$string['transfer_token_create'] = 'Создать токен';
$string['transfer_token_created_once'] = 'Токен создан. Скопируйте его сейчас:';
$string['transfer_token_created_once_desc'] = 'В целях безопасности полный токен отображается только один раз. После этого хранится только хеш.';
$string['transfer_token_default_name'] = 'Токен передачи курсов';
$string['transfer_token_expired'] = 'Истек';
$string['transfer_token_expires'] = 'Истекает';
$string['transfer_token_lastused'] = 'Последнее использование';
$string['transfer_token_name'] = 'Имя токена';
$string['transfer_token_remaining'] = 'Осталось';
$string['transfer_token_revoke'] = 'Отозван';
$string['transfer_token_revoke_confirm'] = 'Отозвать этот токен? Он больше не будет приниматься API или загрузками.';
$string['transfer_token_revoked'] = 'Токен отозван.';
$string['transfer_token_status_active'] = 'Активен';
$string['transfer_token_uses'] = 'Использования';
$string['transfer_tokens'] = 'Токены передачи';
$string['transfer_tokens_desc'] = 'Токены разрешают API передачи и загрузки MBZ для {$a}. Создайте новый токен, когда другому сайту Moodle потребуется временный доступ.';
$string['view_backup_report'] = 'Отслеживайте очередь резервного копирования в одном месте: ожидающие курсы, обрабатываемые элементы, завершенные резервные копии и записи, требующие внимания.';
$string['view_restore_report'] = 'Отслеживайте очередь восстановления в одном месте: выбранные файлы MBZ, обрабатываемые элементы, завершенные восстановления и записи, требующие внимания.';
