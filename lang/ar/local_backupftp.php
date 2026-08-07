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
 * Lang ar file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">الوصول إلى المقرر</a>';
$string['adding_to_category'] = 'سيتمت إضافته إلى التصنيف {$a->categoria}';
$string['already_added_status'] = 'تمت إضافته مسبقًا والحالة هي {$a->status}';
$string['api_invalid_action'] = 'إجراء API غير صالح.';
$string['backup_category_select_help'] = 'حدد التصنيفات التي يجب إضافة مقرراتها إلى قائمة انتظار النسخ الاحتياطي. تؤثر الأزرار في كل بطاقة على ذلك التصنيف وعلى جميع التصنيفات الفرعية داخله.';
$string['backup_courses_and_categories'] = 'نسخ احتياطي: المقررات والتصنيفات';
$string['backup_creation_parameters'] = 'سيتم إنشاء النسخة الاحتياطية بالمعلمات التالية';
$string['backup_end'] = 'انتهى النسخ الاحتياطي في';
$string['backup_end_time'] = 'وقت انتهاء النسخ الاحتياطي';
$string['backup_report'] = 'تقرير النسخ الاحتياطي';
$string['backup_start'] = 'بدأ النسخ الاحتياطي في';
$string['backup_start_time'] = 'وقت بدء النسخ الاحتياطي';
$string['backupftp:manage'] = 'إدارة النسخ الاحتياطي';
$string['categories'] = 'التصنيفات';
$string['category_created_successfully'] = ' ==> تم إنشاء التصنيف {$a->categoria_nome} بنجاح';
$string['category_link'] = 'التصنيف <a href="{$a}" target="blank">التصنيف الجذر</a>';
$string['click_here'] = 'انقر هنا';
$string['course'] = 'المقرر';
$string['course_added_to_backup_queue'] = 'تمت إضافة المقرر {$a->course_id} ({$a->course_name}) إلى قائمة انتظار النسخ الاحتياطي.';
$string['courses'] = 'المقررات';
$string['courses_and_categories'] = 'المقررات والتصنيفات';
$string['created_at'] = 'تم الإنشاء في';
$string['created_on'] = 'تم الإنشاء في';
$string['created_on_time'] = 'تم الإنشاء في {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'إلغاء تحديد الكل';
$string['error_creating_folder'] = '<span style="color:#d10707">خطأ في إنشاء المجلد</span> "<b>{$a->ftppasta}</b>" على FTP مع الخطأ "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'خطأ في تنزيل ملف MBZ، مع الخطأ "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'خطأ في استخراج ملف MBZ';
$string['file_added_to_restore_queue'] = 'تمت إضافة الملف {$a->file} إلى قائمة انتظار الاستعادة';
$string['file_found_and_downloaded'] = 'تم العثور على الملف وتنزيله';
$string['file_size'] = 'بحجم {$a->size}';
$string['file_size_label'] = 'حجم الملف';
$string['file_uploaded'] = 'تم رفع الملف "<b>{$a->file}</b>" إلى "<b>{$a->remote_file}</b>"!';
$string['ftp_error_connecting'] = 'خطأ في الاتصال بـ FTP';
$string['ftp_error_login'] = 'تعذر الاتصال باستخدام {$a->username}@{$a->url}';
$string['ftp_remote_file_size'] = 'أرجع FTP أن الملف البعيد حجمه "<b>{$a->size} بايت</b>"';
$string['index_backup_button'] = 'فتح شاشة النسخ الاحتياطي';
$string['index_backup_desc'] = 'استخدم هذه المنطقة لاختيار المقررات والتصنيفات ووضع إنشاء النسخ الاحتياطية في قائمة الانتظار. يمكن حفظ الملفات التي يتم إنشاؤها محليًا و/أو إرسالها إلى FTP حسب إعدادات الإضافة.';
$string['index_backup_report_button'] = 'عرض تقرير النسخ الاحتياطي';
$string['index_backup_title'] = 'نسخ احتياطي للمقررات';
$string['index_flow_step1_after_old_moodle'] = 'أنشئ أو حدّث النسخ الاحتياطية للمقررات التي سيتم نقلها.';
$string['index_flow_step2_after_mbz'] = 'التنزيلات فقط أثناء صلاحيته.';
$string['index_flow_step2_after_token_before_mbz'] = 'يقوم بتمكين API و';
$string['index_flow_step2_before_token'] = 'ما زلت في Moodle القديم، أنشئ';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'افتح شاشة الاستعادة وأدخل القديم';
$string['index_flow_step3_after_wwwroot'] = '، والرمز، وإذا لزم الأمر، عنوان IP للجهاز القديم.';
$string['index_flow_step4'] = 'تحقق من القائمة التي أعادتها API وأرسل الملفات إلى قائمة الانتظار. سيقوم cron بتنزيل المقررات واستعادتها في الخلفية.';
$string['index_flow_step_moodle'] = 'في';
$string['index_intro_desc'] = 'تساعد هذه الإضافة على ترحيل المقررات من تثبيت Moodle إلى آخر بأمان أكبر. يقوم Moodle القديم بإنشاء النسخ الاحتياطية ومنح الوصول عبر رمز مؤقت. يستعلم Moodle الجديد من API، ويعرض الملفات المتاحة، ويضع عمليات الاستعادة في قائمة الانتظار لتنفيذها عبر cron.';
$string['index_new_moodle'] = 'Moodle الجديد';
$string['index_old_moodle'] = 'Moodle القديم';
$string['index_recommended_flow_title'] = 'التدفق الموصى به';
$string['index_reports_desc'] = 'استخدم التقارير لمتابعة ما تم وضعه بالفعل في قائمة الانتظار، وما تتم معالجته، والنسخ الاحتياطية المكتملة، وعمليات الاستعادة التي تحتاج إلى اهتمام.';
$string['index_reports_title'] = 'التقارير والمراقبة';
$string['index_restore_button'] = 'فتح شاشة الاستعادة';
$string['index_restore_desc_after_wwwroot'] = '، والرمز، واختياريًا IP الجهاز القديم عندما يكون النطاق قد تم ترحيله بالفعل إلى الخادم الجديد.';
$string['index_restore_desc_before_wwwroot'] = 'استخدم هذه الشاشة لاستيراد النسخ الاحتياطية من Moodle آخر. أدخل القديم';
$string['index_restore_queue_desc'] = 'بعد الاستعلام، يتم وضع الملفات البعيدة في قائمة انتظار الاستعادة. وبهذا يمكن أن تستمر عملية الترحيل عبر cron دون الاعتماد على بقاء الصفحة مفتوحة في المتصفح.';
$string['index_restore_report_button'] = 'عرض تقرير الاستعادة';
$string['index_restore_title'] = 'الاستعادة في Moodle الجديد';
$string['index_title'] = 'نقل المقررات بين أنظمة Moodle';
$string['index_token_time_desc'] = 'للرمز مدة صلاحية محدودة يتم إعدادها في صفحة الإدارة هذه. قبل بدء ترحيل كبير، تأكد من أن cron في Moodle الجديد نشط وأن الوقت المتبقي للرمز كاف لتنزيل جميع النسخ الاحتياطية المطلوبة.';
$string['index_token_time_title'] = 'انتبه إلى مدة صلاحية الرمز';
$string['index_tokens_button'] = 'إدارة الرموز';
$string['index_tokens_desc_after_mbz'] = 'لا يحل الرمز محل تسجيل دخول إداري: يجب مشاركته فقط خلال نافذة الترحيل.';
$string['index_tokens_desc_before_mbz'] = 'أنشئ رموزًا مؤقتة للسماح لـ Moodle آخر بالاستعلام عن المقررات والتصنيفات والنسخ الاحتياطية وتنزيل';
$string['index_transfer_token'] = 'رمز النقل';
$string['log:savelocal:error'] = 'فشل حفظ النسخة الاحتياطية محليًا: {$a}';
$string['log:savelocal:success'] = 'تم حفظ النسخة الاحتياطية محليًا: {$a}';
$string['logs'] = 'السجلات';
$string['manual_cron_button'] = 'فتح التنفيذ اليدوي';
$string['manual_cron_desc'] = 'استخدم هذه الصفحة لمعالجة النسخ الاحتياطية أو عمليات الاستعادة الموجودة في قائمة الانتظار الآن، أو اختبار المهمة يدويًا، أو تسريع الترحيل دون انتظار دورة Moodle CRON المجدولة التالية.';
$string['manual_cron_title'] = 'تنفيذ CRON يدوي';
$string['mbz_extracted_successfully'] = 'تم استخراج MBZ بنجاح';
$string['nothing_to_execute'] = 'لا يوجد شيء للتنفيذ';
$string['pluginname'] = 'Backup FTP/Local';
$string['pre_check_failure'] = 'فشل الفحص المسبق';
$string['privacy:metadata'] = 'لا تجمع إضافة local_backupftp ولا تخزن بيانات شخصية أو أي بيانات حساسة أخرى. تستخدم فقط إعدادات FTP المقدمة لتنفيذ النسخ الاحتياطية، دون تسجيل أو الاحتفاظ بمعلومات مرتبطة بالمستخدمين أو بالبيانات المنقولة.';
$string['processing_file'] = 'جارٍ المعالجة: <b>{$a->remote_file}</b> بحجم {$a->size}';
$string['remote_file'] = 'ملف بعيد';
$string['report'] = 'تقرير';
$string['reports'] = 'تقارير';
$string['requeue_backup'] = 'إعادة الإرسال';
$string['requeue_backup_confirm'] = 'إعادة إرسال هذه النسخة الاحتياطية؟ ستتم إعادة تعيينها ووضعها مرة أخرى في قائمة الانتظار.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">المقرر موجود بالفعل</a>';
$string['restore_courses_and_categories'] = 'استعادة: المقررات والتصنيفات';
$string['restore_file_select_help'] = 'حدد ملفات MBZ التي يجب إضافتها إلى قائمة انتظار الاستعادة. تؤثر الأزرار في كل تصنيف على ذلك الفرع فقط.';
$string['restore_report'] = 'تقرير الاستعادة';
$string['runtask_backup_desc'] = 'يعالج المقررات التي تم وضعها بالفعل في قائمة انتظار النسخ الاحتياطي وينشئ/يرسل ملفات MBZ المهيأة.';
$string['runtask_backup_title'] = 'تشغيل قائمة انتظار النسخ الاحتياطي يدويًا';
$string['runtask_execute_five_courses'] = 'معالجة ما يصل إلى 5 عناصر الآن';
$string['runtask_execute_ten_courses'] = 'معالجة ما يصل إلى 10 عناصر الآن';
$string['runtask_manual_desc'] = 'تقوم هذه الصفحة بتشغيل نفس مهام النسخ الاحتياطي والاستعادة التي ينفذها Moodle CRON عادةً. وهي مفيدة عندما تريد معالجة قائمة الانتظار يدويًا أو التحقق من الإعدادات أو تسريع الترحيل بتشغيل دفعة صغيرة فورًا.';
$string['runtask_manual_note'] = 'التنفيذ اليدوي لا يستبدل Moodle CRON المجدول. أبقِ CRON العادي نشطًا حتى تستمر قائمة الانتظار في المعالجة تلقائيًا.';
$string['runtask_manual_note_title'] = 'مهم:';
$string['runtask_restore_desc'] = 'يعالج ملفات MBZ التي تم وضعها بالفعل في قائمة انتظار الاستعادة ويستعيدها داخل Moodle.';
$string['runtask_restore_title'] = 'تشغيل قائمة انتظار الاستعادة يدويًا';
$string['select_all'] = 'تحديد الكل';
$string['settings_categorystart'] = 'معرّف التصنيف الجذر';
$string['settings_categorystart_desc'] = 'معرّف التصنيف الجذر لبدء استعادة المقررات';
$string['settings_error'] = 'والخطأ';
$string['settings_error_sending_backup'] = 'خطأ في إرسال النسخة الاحتياطية إلى';
$string['settings_file_size'] = 'مع حجم الملف';
$string['settings_ftp'] = 'تخزين FTP';
$string['settings_ftpenable'] = 'الإرسال إلى FTP';
$string['settings_ftpnames'] = 'استخدام اسم المقرر كاسم ملف النسخة الاحتياطية';
$string['settings_ftpnames_desc'] = 'إذا تم تحديده، فسيكون اسم الملف المرسل هو اسم المقرر. وإلا فسيكون الاسم الذي يعيّنه Moodle، مثل backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'تنظيم النسخ الاحتياطية على FTP حسب التصنيفات';
$string['settings_ftporganize_desc'] = 'سيتم حفظ الملف كـ Category/Category/course.mbz';
$string['settings_ftppassword'] = 'كلمة مرور FTP';
$string['settings_ftppasta'] = 'مجلد FTP البعيد';
$string['settings_ftppasta_desc'] = 'يجب أن يبدأ مجلد الوجهة بـ / وألا ينتهي بـ / (مثال: /backup، /save/backup)';
$string['settings_ftppasv'] = 'إرسال الملف في الوضع السلبي؟';
$string['settings_ftppasv_desc'] = 'وضع FTP الافتراضي في PHP هو الوضع النشط. نادرًا ما يعمل الوضع النشط بسبب الجدران النارية/NAT/الوكلاء. لذلك تحتاج غالبًا إلى استخدام الوضع السلبي.';
$string['settings_ftpurl'] = 'رابط FTP';
$string['settings_ftpurl_desc'] = 'أدخل عنوان IP أو اسم المضيف لخادم FTP المطلوب. إذا كان منفذ خادم FTP مختلفًا عن 21، فحدده بإضافة نقطتين (:) متبوعة برقم المنفذ، مثل 127.0.0.1:29. إذا كان FTP يستخدم SSL، أضف ftps:// قبل النطاق.';
$string['settings_ftpusername'] = 'تسجيل دخول FTP';
$string['settings_integrations'] = 'التكاملات';
$string['settings_local'] = 'التخزين المحلي';
$string['settings_localfile'] = 'حفظ النسخ الاحتياطية في مجلد محلي';
$string['settings_localfile_desc'] = 'إذا تم تمكينه، فسيتم تخزين نسخة من النسخ الاحتياطية في مجلد محلي محدد أدناه.';
$string['settings_localfilepath'] = 'مسار مجلد النسخ الاحتياطي المحلي';
$string['settings_localfilepath_desc'] = 'أدخل المسار الكامل للمجلد الذي سيتم تخزين النسخ الاحتياطية فيه محليًا. تأكد من أن لدى الخادم صلاحيات كتابة لهذا المجلد. إذا تُرك فارغًا، فسيتم حفظ النسخ الاحتياطية في [MOODLEDATA]/backup/';
$string['settings_mbz_settings'] = 'إعدادات إنشاء النسخ الاحتياطية';
$string['settings_restore_settings'] = 'إعدادات الاستعادة';
$string['settings_rootsettinganonymize'] = 'إخفاء هوية مستخدمي الجذر';
$string['settings_rootsettingusers'] = 'إعداد مستخدمي الجذر';
$string['settings_tokenduration'] = 'مدة صلاحية الرمز';
$string['settings_tokenduration_desc'] = 'مدة بقاء كل رمز نقل يتم إنشاؤه صالحًا. الافتراضي هو 48 ساعة.';
$string['settings_transfer_api'] = 'API نقل المقررات';
$string['settings_transfer_api_desc'] = 'تسمح الرموز قصيرة العمر لموقع Moodle آخر بعرض المقررات والتصنيفات والنسخ الاحتياطية وتنزيل ملفات MBZ.';
$string['status'] = 'الحالة';
$string['submit'] = 'إرسال';
$string['token_invalid_or_expired'] = 'رمز النقل غير صالح أو منتهي الصلاحية.';
$string['transfer_restore_clear_session_button'] = 'مسح البيانات البعيدة';
$string['transfer_restore_curl_required'] = 'امتداد PHP cURL مطلوب لنقل النسخ الاحتياطية من Moodle آخر.';
$string['transfer_restore_desc'] = 'استخدم هذا الخيار لسحب قائمة النسخ الاحتياطية من Moodle السابق. يتم حفظ بيانات النموذج في جلستك، ولا توضع الملفات في قائمة انتظار الاستعادة إلا بعد تحديدها.';
$string['transfer_restore_download_too_small'] = 'ملف النسخة الاحتياطية الذي تم تنزيله فارغ أو صغير جدًا.';
$string['transfer_restore_downloading'] = 'جارٍ تنزيل النسخة الاحتياطية البعيدة من {$a->url}';
$string['transfer_restore_http_error'] = 'خطأ في الاتصال بـ Moodle السابق: {$a}';
$string['transfer_restore_http_status'] = 'أرجع Moodle السابق حالة HTTP {$a}.';
$string['transfer_restore_invalid_backup_file'] = 'ملف نسخة احتياطية بعيد غير صالح.';
$string['transfer_restore_invalid_json'] = 'لم يُرجع Moodle السابق استجابة JSON صالحة.';
$string['transfer_restore_ip'] = 'IP الخادم القديم (اختياري)';
$string['transfer_restore_ip_desc'] = 'استخدمه فقط عندما يكون النطاق قد تم ترحيله بالفعل إلى Moodle الجديد هذا. يحافظ الطلب على مضيف wwwroot القديم، لكنه يفرض حل DNS إلى هذا IP.';
$string['transfer_restore_ip_invalid'] = 'IP الخادم القديم غير صالح.';
$string['transfer_restore_missing_remote_data'] = 'بيانات Moodle البعيد المطلوبة لتنزيل النسخة الاحتياطية مفقودة.';
$string['transfer_restore_no_backups'] = 'لم يُرجع Moodle السابق أي ملفات نسخ احتياطي بعيدة.';
$string['transfer_restore_no_selection'] = 'حدد ملف نسخة احتياطية بعيد واحدًا على الأقل لاستعادته.';
$string['transfer_restore_original_category'] = 'معرّف/اسم التصنيف الأصلي';
$string['transfer_restore_original_course'] = 'معرّف/اسم المقرر الأصلي';
$string['transfer_restore_queue_button'] = 'عرض النسخ الاحتياطية البعيدة';
$string['transfer_restore_queue_summary'] = 'تم تحديث قائمة انتظار الاستعادة البعيدة. جديد: {$a->queued}. محدث: {$a->updated}. متجاهل: {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'أرجع Moodle السابق خطأ: {$a}';
$string['transfer_restore_select_file'] = 'تحديد';
$string['transfer_restore_selected_button'] = 'استعادة المحدد';
$string['transfer_restore_session_cleared'] = 'تمت إزالة بيانات Moodle البعيد من جلستك.';
$string['transfer_restore_session_saved'] = 'تم حفظ بيانات Moodle البعيد في جلستك.';
$string['transfer_restore_session_summary'] = 'تم العثور على ملفات نسخ احتياطي بعيدة: {$a}. حدد الملفات التي تريد استعادتها.';
$string['transfer_restore_source'] = 'المصدر';
$string['transfer_restore_table_limited'] = 'عرض أول 50 من {$a} ملفًا في قائمة الانتظار.';
$string['transfer_restore_tempfile_error'] = 'تعذر إنشاء ملف النسخة الاحتياطية المؤقت.';
$string['transfer_restore_title'] = 'الاستعادة من Moodle آخر';
$string['transfer_restore_token'] = 'رمز النقل';
$string['transfer_restore_token_counter'] = 'العد التنازلي لصلاحية الرمز:';
$string['transfer_restore_token_desc'] = 'الصق الرمز الذي تم إنشاؤه في Moodle السابق ضمن Backup FTP/Local > رموز النقل.';
$string['transfer_restore_token_required'] = 'رمز النقل مطلوب.';
$string['transfer_restore_users_failed'] = 'تعذر استيراد المستخدمين البعيدين: {$a}';
$string['transfer_restore_users_summary'] = 'تم استيراد المستخدمين البعيدين. تم الإنشاء: {$a->created}. تم التحديث: {$a->updated}. تم التجاهل: {$a->ignored}. الأخطاء: {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'wwwroot لـ Moodle السابق';
$string['transfer_restore_wwwroot_desc'] = 'مثال: https://ead-antigo.instituicao.edu.br. لا تقم بتضمين /local/backupftp.';
$string['transfer_restore_wwwroot_invalid'] = 'wwwroot لـ Moodle السابق غير صالح.';
$string['transfer_restore_wwwroot_required'] = 'wwwroot لـ Moodle السابق مطلوب.';
$string['transfer_token_create'] = 'إنشاء رمز';
$string['transfer_token_created_once'] = 'تم إنشاء الرمز. انسخه الآن:';
$string['transfer_token_created_once_desc'] = 'لأسباب أمنية، يتم عرض الرمز الكامل مرة واحدة فقط. بعد ذلك، يتم تخزين التجزئة فقط.';
$string['transfer_token_default_name'] = 'رمز نقل المقرر';
$string['transfer_token_expired'] = 'منتهي الصلاحية';
$string['transfer_token_expires'] = 'ينتهي';
$string['transfer_token_lastused'] = 'آخر استخدام';
$string['transfer_token_name'] = 'اسم الرمز';
$string['transfer_token_remaining'] = 'المتبقي';
$string['transfer_token_revoke'] = 'ملغى';
$string['transfer_token_revoke_confirm'] = 'إلغاء هذا الرمز؟ لن يتم قبوله بعد الآن من قبل API أو التنزيلات.';
$string['transfer_token_revoked'] = 'تم إلغاء الرمز.';
$string['transfer_token_status_active'] = 'نشط';
$string['transfer_token_uses'] = 'الاستخدامات';
$string['transfer_tokens'] = 'رموز النقل';
$string['transfer_tokens_desc'] = 'تسمح الرموز لـ API النقل وتنزيلات MBZ لـ {$a}. أنشئ رمزًا جديدًا عندما يحتاج موقع Moodle آخر إلى وصول مؤقت.';
$string['view_backup_report'] = 'تابع قائمة انتظار النسخ الاحتياطي في مكان واحد: المقررات المعلقة، والعناصر قيد المعالجة، والنسخ الاحتياطية المكتملة، والسجلات التي تحتاج إلى اهتمام.';
$string['view_restore_report'] = 'تابع قائمة انتظار الاستعادة في مكان واحد: ملفات MBZ المحددة، والعناصر قيد المعالجة، وعمليات الاستعادة المكتملة، والسجلات التي تحتاج إلى اهتمام.';
