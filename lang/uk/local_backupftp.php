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
 * Lang uk file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Відкрити курс</a>';
$string['adding_to_category'] = 'Буде додано до категорії {$a->categoria}';
$string['already_added_status'] = 'Уже додано, статус: {$a->status}';
$string['api_invalid_action'] = 'Недійсна дія API.';
$string['backup_category_select_help'] = 'Виберіть категорії, курси яких потрібно додати до черги резервного копіювання. Кнопки в кожній картці впливають на цю категорію та всі її підкатегорії.';
$string['backup_courses_and_categories'] = 'Резервне копіювання: курси та категорії';
$string['backup_creation_parameters'] = 'Резервну копію буде створено з такими параметрами';
$string['backup_end'] = 'Резервне копіювання завершено';
$string['backup_end_time'] = 'Час завершення резервного копіювання';
$string['backup_report'] = 'Звіт про резервне копіювання';
$string['backup_start'] = 'Резервне копіювання розпочато';
$string['backup_start_time'] = 'Час початку резервного копіювання';
$string['backupftp:manage'] = 'Керувати резервним копіюванням';
$string['categories'] = 'Категорії';
$string['category_created_successfully'] = ' ==> Категорію {$a->categoria_nome} успішно створено';
$string['category_link'] = 'Категорія <a href="{$a}" target="blank">Коренева категорія</a>';
$string['click_here'] = 'Натисніть тут';
$string['course'] = 'Курс';
$string['course_added_to_backup_queue'] = 'Курс {$a->course_id} ({$a->course_name}) додано до черги резервного копіювання.';
$string['courses'] = 'Курси';
$string['courses_and_categories'] = 'Курси та категорії';
$string['created_at'] = 'Створено';
$string['created_on'] = 'Створено';
$string['created_on_time'] = 'Створено {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'Зняти вибір з усіх';
$string['error_creating_folder'] = '<span style="color:#d10707">Помилка створення папки</span> "<b>{$a->ftppasta}</b>" на FTP з помилкою "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'Помилка завантаження файлу MBZ, помилка "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Помилка розпакування файлу MBZ';
$string['file_added_to_restore_queue'] = 'Файл {$a->file} додано до черги відновлення';
$string['file_found_and_downloaded'] = 'Файл знайдено та завантажено';
$string['file_size'] = 'з розміром {$a->size}';
$string['file_size_label'] = 'Розмір файлу';
$string['file_uploaded'] = 'Файл "<b>{$a->file}</b>" завантажено до "<b>{$a->remote_file}</b>"!';
$string['ftp_error_connecting'] = 'Помилка підключення до FTP';
$string['ftp_error_login'] = 'Не вдалося підключитися як {$a->username}@{$a->url}';
$string['ftp_remote_file_size'] = 'FTP повідомив, що віддалений файл має "<b>{$a->size} байт</b>"';
$string['index_backup_button'] = 'Відкрити екран резервного копіювання';
$string['index_backup_desc'] = 'Використовуйте цю область, щоб вибрати курси й категорії та поставити створення резервних копій у чергу. Створені файли можна зберегти локально та/або надіслати на FTP згідно з налаштуваннями плагіна.';
$string['index_backup_report_button'] = 'Переглянути звіт про резервне копіювання';
$string['index_backup_title'] = 'Резервне копіювання курсів';
$string['index_flow_step1_after_old_moodle'] = 'створіть або оновіть резервні копії курсів, які буде перенесено.';
$string['index_flow_step2_after_mbz'] = 'завантаження лише поки він чинний.';
$string['index_flow_step2_after_token_before_mbz'] = 'Він вмикає API та';
$string['index_flow_step2_before_token'] = 'Ще у старому Moodle створіть';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'відкрийте екран відновлення та введіть старий';
$string['index_flow_step3_after_wwwroot'] = ', токен і, за потреби, IP старої машини.';
$string['index_flow_step4'] = 'Перевірте список, повернений API, і надішліть файли до черги. Cron завантажить і відновить курси у фоновому режимі.';
$string['index_flow_step_moodle'] = 'У';
$string['index_intro_desc'] = 'Цей плагін допомагає безпечніше переносити курси з однієї інсталяції Moodle до іншої. Старий Moodle створює резервні копії та надає доступ через тимчасовий токен. Новий Moodle звертається до API, показує доступні файли та ставить відновлення в чергу для виконання cron.';
$string['index_new_moodle'] = 'новому Moodle';
$string['index_old_moodle'] = 'старому Moodle';
$string['index_recommended_flow_title'] = 'Рекомендований порядок';
$string['index_reports_desc'] = 'Використовуйте звіти, щоб відстежувати, що вже поставлено в чергу, що обробляється, які резервні копії завершено та які відновлення потребують уваги.';
$string['index_reports_title'] = 'Звіти та моніторинг';
$string['index_restore_button'] = 'Відкрити екран відновлення';
$string['index_restore_desc_after_wwwroot'] = ', токен і, за бажанням, IP старої машини, якщо домен уже перенесено на новий сервер.';
$string['index_restore_desc_before_wwwroot'] = 'Використовуйте цей екран для імпорту резервних копій з іншого Moodle. Введіть старий';
$string['index_restore_queue_desc'] = 'Після запиту віддалені файли ставляться в чергу відновлення. Так міграція може продовжуватися через cron без потреби тримати сторінку відкритою в браузері.';
$string['index_restore_report_button'] = 'Переглянути звіт про відновлення';
$string['index_restore_title'] = 'Відновлення у новому Moodle';
$string['index_title'] = 'Передавання курсів між Moodle';
$string['index_token_time_desc'] = 'Токен має обмежений строк дії, налаштований на цій сторінці адміністрування. Перед великою міграцією переконайтеся, що cron нового Moodle активний і залишку часу токена достатньо для завантаження всіх потрібних резервних копій.';
$string['index_token_time_title'] = 'Зверніть увагу на строк дії токена';
$string['index_tokens_button'] = 'Керувати токенами';
$string['index_tokens_desc_after_mbz'] = 'Токен не замінює адміністративний вхід: ним слід ділитися лише під час вікна міграції.';
$string['index_tokens_desc_before_mbz'] = 'Створіть тимчасові токени, щоб інший Moodle міг запитувати курси, категорії, резервні копії та завантажувати';
$string['index_transfer_token'] = 'токен передавання';
$string['log:savelocal:error'] = 'Не вдалося зберегти резервну копію локально: {$a}';
$string['log:savelocal:success'] = 'Резервну копію збережено локально: {$a}';
$string['logs'] = 'Журнали';
$string['manual_cron_button'] = 'Відкрити ручне виконання';
$string['manual_cron_desc'] = 'Використовуйте цю сторінку, щоб зараз обробити резервні копії або відновлення в черзі, вручну протестувати завдання або прискорити міграцію без очікування наступного запланованого циклу Moodle CRON.';
$string['manual_cron_title'] = 'Ручне виконання CRON';
$string['mbz_extracted_successfully'] = 'MBZ успішно розпаковано';
$string['nothing_to_execute'] = 'Немає що виконувати';
$string['pluginname'] = 'Backup FTP/Local';
$string['pre_check_failure'] = 'Попередня перевірка не пройдена';
$string['privacy:metadata'] = 'Плагін local_backupftp не збирає та не зберігає персональні дані або інші чутливі дані. Він використовує лише надані FTP-конфігурації для виконання резервних копій, без журналювання або збереження інформації про користувачів чи передані дані.';
$string['processing_file'] = 'Обробка: <b>{$a->remote_file}</b> з {$a->size}';
$string['remote_file'] = 'Віддалений файл';
$string['report'] = 'Звіт';
$string['reports'] = 'Звіти';
$string['requeue_backup'] = 'Надіслати повторно';
$string['requeue_backup_confirm'] = 'Надіслати цю резервну копію повторно? Її буде скинуто та знову поставлено в чергу.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Курс уже існує</a>';
$string['restore_courses_and_categories'] = 'Відновлення: курси та категорії';
$string['restore_file_select_help'] = 'Виберіть файли MBZ, які потрібно додати до черги відновлення. Кнопки в кожній категорії впливають лише на цю гілку.';
$string['restore_report'] = 'Звіт про відновлення';
$string['runtask_backup_desc'] = 'Обробляє курси, вже поставлені в чергу резервного копіювання, і створює/надсилає налаштовані файли MBZ.';
$string['runtask_backup_title'] = 'Запустити чергу резервного копіювання вручну';
$string['runtask_execute_five_courses'] = 'Обробити до 5 елементів зараз';
$string['runtask_execute_ten_courses'] = 'Обробити до 10 елементів зараз';
$string['runtask_manual_desc'] = 'Ця сторінка виконує ті самі завдання резервного копіювання та відновлення, які зазвичай виконує Moodle CRON. Вона корисна, коли потрібно обробити чергу вручну, перевірити конфігурацію або прискорити міграцію, одразу запустивши невелику партію.';
$string['runtask_manual_note'] = 'ручне виконання не замінює запланований Moodle CRON. Залишайте звичайний CRON активним, щоб черга продовжувала оброблятися автоматично.';
$string['runtask_manual_note_title'] = 'Важливо:';
$string['runtask_restore_desc'] = 'Обробляє файли MBZ, уже поставлені в чергу відновлення, і відновлює їх у Moodle.';
$string['runtask_restore_title'] = 'Запустити чергу відновлення вручну';
$string['select_all'] = 'Вибрати все';
$string['settings_categorystart'] = 'ID кореневої категорії';
$string['settings_categorystart_desc'] = 'ID кореневої категорії, з якої починати відновлення курсів';
$string['settings_error'] = 'і помилка';
$string['settings_error_sending_backup'] = 'Помилка надсилання резервної копії до';
$string['settings_file_size'] = 'з розміром файлу';
$string['settings_ftp'] = 'FTP-сховище';
$string['settings_ftpenable'] = 'Надсилати на FTP';
$string['settings_ftpnames'] = 'Використовувати назву курсу як ім’я файлу резервної копії';
$string['settings_ftpnames_desc'] = 'Якщо позначено, ім’ям надісланого файлу буде назва курсу. Інакше буде використано ім’я, призначене Moodle, схоже на backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'Організувати резервні копії на FTP за категоріями';
$string['settings_ftporganize_desc'] = 'Файл буде збережено як Категорія/Категорія/course.mbz';
$string['settings_ftppassword'] = 'FTP-пароль';
$string['settings_ftppasta'] = 'Віддалена FTP-папка';
$string['settings_ftppasta_desc'] = 'Папка призначення має починатися з / і не закінчуватися на / (наприклад, /backup, /save/backup)';
$string['settings_ftppasv'] = 'Надсилати файл у пасивному режимі?';
$string['settings_ftppasv_desc'] = 'Стандартний режим FTP у PHP — активний. Активний режим рідко працює через firewall/NAT/proxy. Тому майже завжди потрібно використовувати пасивний режим.';
$string['settings_ftpurl'] = 'FTP URL';
$string['settings_ftpurl_desc'] = 'Введіть IP-адресу або ім’я хоста потрібного FTP-сервера. Якщо порт FTP-сервера відрізняється від 21, вкажіть його, додавши двокрапку (:) і номер порту, наприклад 127.0.0.1:29. Якщо ваш FTP використовує SSL, додайте ftps:// перед доменом.';
$string['settings_ftpusername'] = 'FTP-логін';
$string['settings_integrations'] = 'Інтеграції';
$string['settings_local'] = 'Локальне сховище';
$string['settings_localfile'] = 'Зберігати резервні копії в локальну папку';
$string['settings_localfile_desc'] = 'Якщо увімкнено, копія резервних копій зберігатиметься в локальній папці, зазначеній нижче.';
$string['settings_localfilepath'] = 'Шлях до локальної папки резервних копій';
$string['settings_localfilepath_desc'] = 'Введіть повний шлях до папки, де резервні копії зберігатимуться локально. Переконайтеся, що сервер має права на запис у цю папку. Якщо залишити порожнім, резервні копії буде збережено в [MOODLEDATA]/backup/';
$string['settings_mbz_settings'] = 'Налаштування створення резервних копій';
$string['settings_restore_settings'] = 'Налаштування відновлення';
$string['settings_rootsettinganonymize'] = 'Анонімізувати root-користувачів';
$string['settings_rootsettingusers'] = 'Налаштування root-користувачів';
$string['settings_tokenduration'] = 'Строк дії токена';
$string['settings_tokenduration_desc'] = 'Як довго кожен створений токен передавання залишається чинним. Типове значення — 48 годин.';
$string['settings_transfer_api'] = 'API передавання курсів';
$string['settings_transfer_api_desc'] = 'Короткострокові токени дозволяють іншому сайту Moodle отримувати список курсів, категорій і резервних копій, а також завантажувати файли MBZ.';
$string['status'] = 'Статус';
$string['submit'] = 'Надіслати';
$string['temporary_files_deleted'] = 'Тимчасові файли видалено';
$string['token_invalid_or_expired'] = 'Недійсний або прострочений токен передавання.';
$string['transfer_restore_clear_session_button'] = 'Очистити віддалені дані';
$string['transfer_restore_curl_required'] = 'Розширення PHP cURL потрібне для передавання резервних копій з іншого Moodle.';
$string['transfer_restore_desc'] = 'Використовуйте цей параметр, щоб отримати список резервних копій з попереднього Moodle. Дані форми зберігаються у вашій сесії, а файли ставляться в чергу відновлення лише після вибору.';
$string['transfer_restore_download_too_small'] = 'Завантажений файл резервної копії порожній або замалий.';
$string['transfer_restore_downloading'] = 'Завантаження віддаленої резервної копії з {$a->url}';
$string['transfer_restore_http_error'] = 'Помилка підключення до попереднього Moodle: {$a}';
$string['transfer_restore_http_status'] = 'Попередній Moodle повернув HTTP-статус {$a}.';
$string['transfer_restore_invalid_backup_file'] = 'Недійсний віддалений файл резервної копії.';
$string['transfer_restore_invalid_json'] = 'Попередній Moodle не повернув дійсну JSON-відповідь.';
$string['transfer_restore_ip'] = 'IP старого сервера (необов’язково)';
$string['transfer_restore_ip_desc'] = 'Використовуйте лише тоді, коли домен уже перенесено до цього нового Moodle. Запит зберігає старий хост wwwroot, але примусово спрямовує DNS-резолюцію на цей IP.';
$string['transfer_restore_ip_invalid'] = 'Недійсний IP старого сервера.';
$string['transfer_restore_missing_remote_data'] = 'Бракує даних віддаленого Moodle для завантаження резервної копії.';
$string['transfer_restore_no_backups'] = 'Попередній Moodle не повернув віддалених файлів резервних копій.';
$string['transfer_restore_no_selection'] = 'Виберіть принаймні один віддалений файл резервної копії для відновлення.';
$string['transfer_restore_original_category'] = 'Оригінальний ID/назва категорії';
$string['transfer_restore_original_course'] = 'Оригінальний ID/назва курсу';
$string['transfer_restore_queue_button'] = 'Показати віддалені резервні копії';
$string['transfer_restore_queue_summary'] = 'Чергу віддаленого відновлення оновлено. Нові: {$a->queued}. Оновлені: {$a->updated}. Проігноровані: {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'Попередній Moodle повернув помилку: {$a}';
$string['transfer_restore_select_file'] = 'Вибрати';
$string['transfer_restore_selected_button'] = 'Відновити вибрані';
$string['transfer_restore_session_cleared'] = 'Дані віддаленого Moodle видалено з вашої сесії.';
$string['transfer_restore_session_saved'] = 'Дані віддаленого Moodle збережено у вашій сесії.';
$string['transfer_restore_session_summary'] = 'Знайдено віддалені файли резервних копій: {$a}. Виберіть файли, які потрібно відновити.';
$string['transfer_restore_source'] = 'Джерело';
$string['transfer_restore_table_limited'] = 'Показано перші 50 з {$a} файлів у черзі.';
$string['transfer_restore_tempfile_error'] = 'Не вдалося створити тимчасовий файл резервної копії.';
$string['transfer_restore_title'] = 'Відновити з іншого Moodle';
$string['transfer_restore_token'] = 'Токен передавання';
$string['transfer_restore_token_counter'] = 'Відлік чинності токена:';
$string['transfer_restore_token_desc'] = 'Вставте токен, створений у попередньому Moodle, у Backup FTP/Local > Токени передавання.';
$string['transfer_restore_token_remaining_log'] = 'Токен передавання ще чинний протягом {$a}.';
$string['transfer_restore_token_required'] = 'Токен передавання обов’язковий.';
$string['transfer_restore_users_failed'] = 'Не вдалося імпортувати віддалених користувачів: {$a}';
$string['transfer_restore_users_summary'] = 'Віддалених користувачів імпортовано. Створено: {$a->created}. Оновлено: {$a->updated}. Проігноровано: {$a->ignored}. Помилки: {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'wwwroot попереднього Moodle';
$string['transfer_restore_wwwroot_desc'] = 'Приклад: https://ead-antigo.instituicao.edu.br. Не додавайте /local/backupftp.';
$string['transfer_restore_wwwroot_invalid'] = 'Недійсний wwwroot попереднього Moodle.';
$string['transfer_restore_wwwroot_required'] = 'wwwroot попереднього Moodle обов’язковий.';
$string['transfer_token_create'] = 'Створити токен';
$string['transfer_token_created_once'] = 'Токен створено. Скопіюйте його зараз:';
$string['transfer_token_created_once_desc'] = 'З міркувань безпеки повний токен показується лише один раз. Після цього зберігається лише хеш.';
$string['transfer_token_default_name'] = 'Токен передавання курсів';
$string['transfer_token_expired'] = 'Прострочено';
$string['transfer_token_expired_before_restore'] = 'Токен передавання сплив до того, як цю резервну копію можна було відновити.';
$string['transfer_token_expires'] = 'Спливає';
$string['transfer_token_lastused'] = 'Останнє використання';
$string['transfer_token_name'] = 'Назва токена';
$string['transfer_token_remaining'] = 'Залишилось';
$string['transfer_token_revoke'] = 'Відкликано';
$string['transfer_token_revoke_confirm'] = 'Відкликати цей токен? Він більше не буде прийматися API або завантаженнями.';
$string['transfer_token_revoked'] = 'Токен відкликано.';
$string['transfer_token_status_active'] = 'Активний';
$string['transfer_token_uses'] = 'Використання';
$string['transfer_tokens'] = 'Токени передавання';
$string['transfer_tokens_desc'] = 'Токени авторизують API передавання та завантаження MBZ для {$a}. Створіть новий токен, коли іншому сайту Moodle потрібен тимчасовий доступ.';
$string['view_backup_report'] = 'Відстежуйте чергу резервного копіювання в одному місці: курси в очікуванні, елементи в обробці, завершені резервні копії та записи, що потребують уваги.';
$string['view_restore_report'] = 'Відстежуйте чергу відновлення в одному місці: вибрані файли MBZ, елементи в обробці, завершені відновлення та записи, що потребують уваги.';
