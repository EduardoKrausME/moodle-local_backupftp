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
 * Lang ja file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">コースにアクセス</a>';
$string['adding_to_category'] = 'カテゴリ {$a->categoria} に追加されます';
$string['already_added_status'] = 'すでに追加済みで、ステータスは {$a->status} です';
$string['api_invalid_action'] = '無効なAPIアクションです。';
$string['backup_category_select_help'] = 'バックアップキューに追加するコースを含むカテゴリを選択してください。各カードのボタンは、そのカテゴリと配下のすべてのサブカテゴリに影響します。';
$string['backup_courses_and_categories'] = 'バックアップ: コースとカテゴリ';
$string['backup_creation_parameters'] = 'バックアップは次のパラメータで作成されます';
$string['backup_end'] = 'バックアップ終了日時';
$string['backup_end_time'] = 'バックアップ終了時刻';
$string['backup_report'] = 'バックアップレポート';
$string['backup_start'] = 'バックアップ開始日時';
$string['backup_start_time'] = 'バックアップ開始時刻';
$string['backupftp:manage'] = 'バックアップを管理';
$string['categories'] = 'カテゴリ';
$string['category_created_successfully'] = ' ==> カテゴリ {$a->categoria_nome} が正常に作成されました';
$string['category_link'] = 'カテゴリ <a href="{$a}" target="blank">ルートカテゴリ</a>';
$string['click_here'] = 'ここをクリック';
$string['course'] = 'コース';
$string['course_added_to_backup_queue'] = 'コース {$a->course_id} ({$a->course_name}) がバックアップキューに追加されました。';
$string['courses'] = 'コース';
$string['courses_and_categories'] = 'コースとカテゴリ';
$string['created_at'] = '作成日時';
$string['created_on'] = '作成日';
$string['created_on_time'] = '{$a->modify} に作成';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'すべて選択解除';
$string['error_creating_folder'] = '<span style="color:#d10707">フォルダ作成エラー</span> FTP上の "<b>{$a->ftppasta}</b>" でエラー "<b>{$->errormsg}</b>" が発生しました!';
$string['error_downloading_file'] = 'MBZファイルのダウンロード中にエラーが発生しました。エラー: "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'MBZファイルの展開中にエラーが発生しました';
$string['file_added_to_restore_queue'] = 'ファイル {$a->file} が復元キューに追加されました';
$string['file_found_and_downloaded'] = 'ファイルが見つかり、ダウンロードされました';
$string['file_size'] = 'サイズ {$a->size}';
$string['file_size_label'] = 'ファイルサイズ';
$string['file_uploaded'] = 'ファイル "<b>{$a->file}</b>" が "<b>{$a->remote_file}</b>" にアップロードされました!';
$string['ftp_error_connecting'] = 'FTPへの接続エラー';
$string['ftp_error_login'] = '{$a->username}@{$a->url} で接続できません';
$string['ftp_remote_file_size'] = 'FTPはリモートファイルのサイズを "<b>{$a->size} bytes</b>" と返しました';
$string['index_backup_button'] = 'バックアップ画面を開く';
$string['index_backup_desc'] = 'この領域でコースとカテゴリを選択し、バックアップ生成をキューに入れます。生成されたファイルは、プラグイン設定に従ってローカル保存および/またはFTP送信できます。';
$string['index_backup_report_button'] = 'バックアップレポートを表示';
$string['index_backup_title'] = 'コースバックアップ';
$string['index_flow_step1_after_old_moodle'] = '転送するコースのバックアップを生成または更新します。';
$string['index_flow_step2_after_mbz'] = '有効な間のみダウンロードできます。';
$string['index_flow_step2_after_token_before_mbz'] = 'APIと';
$string['index_flow_step2_before_token'] = '古いMoodleで、次を作成します:';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = '復元画面を開き、古い';
$string['index_flow_step3_after_wwwroot'] = '、トークン、必要に応じて古いマシンのIPを入力します。';
$string['index_flow_step4'] = 'APIが返した一覧を確認し、ファイルをキューに送信します。cronがバックグラウンドでコースをダウンロードして復元します。';
$string['index_flow_step_moodle'] = '対象:';
$string['index_intro_desc'] = 'このプラグインは、1つのMoodleインストールから別のMoodleへ、より安全にコースを移行するのに役立ちます。古いMoodleはバックアップを生成し、一時トークンでアクセスを許可します。新しいMoodleはAPIを照会し、利用可能なファイルを一覧表示し、cron実行のために復元をキューに入れます。';
$string['index_new_moodle'] = '新しいMoodle';
$string['index_old_moodle'] = '古いMoodle';
$string['index_recommended_flow_title'] = '推奨フロー';
$string['index_reports_desc'] = 'レポートを使用して、すでにキューに入ったもの、処理中のもの、完了したバックアップ、注意が必要な復元を追跡します。';
$string['index_reports_title'] = 'レポートと監視';
$string['index_restore_button'] = '復元画面を開く';
$string['index_restore_desc_after_wwwroot'] = '、トークン、任意でドメインがすでに新サーバへ移行済みの場合は古いマシンのIPを入力します。';
$string['index_restore_desc_before_wwwroot'] = 'この画面を使用して別のMoodleからバックアップをインポートします。古い';
$string['index_restore_queue_desc'] = '照会後、リモートファイルは復元キューに入れられます。これにより、ブラウザでページを開いたままにしなくても、cronで移行を続行できます。';
$string['index_restore_report_button'] = '復元レポートを表示';
$string['index_restore_title'] = '新しいMoodleで復元';
$string['index_title'] = 'Moodle間のコース転送';
$string['index_token_time_desc'] = 'トークンの有効期間は、この管理ページで設定される制限付きです。大規模な移行を開始する前に、新しいMoodleのcronが有効であり、残りのトークン時間が必要なすべてのバックアップのダウンロードに十分であることを確認してください。';
$string['index_token_time_title'] = 'トークンの有効期間に注意';
$string['index_tokens_button'] = 'トークンを管理';
$string['index_tokens_desc_after_mbz'] = 'トークンは管理者ログインの代替ではありません。移行期間中のみ共有してください。';
$string['index_tokens_desc_before_mbz'] = '別のMoodleがコース、カテゴリ、バックアップを照会し、ダウンロードできるように一時トークンを作成します';
$string['index_transfer_token'] = '転送トークン';
$string['log:savelocal:error'] = 'バックアップをローカルに保存できませんでした: {$a}';
$string['log:savelocal:success'] = 'バックアップをローカルに保存しました: {$a}';
$string['logs'] = 'ログ';
$string['manual_cron_button'] = '手動実行を開く';
$string['manual_cron_desc'] = 'このページを使用して、キュー内のバックアップまたは復元を今すぐ処理したり、タスクを手動でテストしたり、次のMoodle CRON予定サイクルを待たずに移行を高速化したりできます。';
$string['manual_cron_title'] = '手動CRON実行';
$string['mbz_extracted_successfully'] = 'MBZを正常に展開しました';
$string['nothing_to_execute'] = '実行するものはありません';
$string['pluginname'] = 'Backup FTP/Local';
$string['pre_check_failure'] = '事前チェックに失敗しました';
$string['privacy:metadata'] = 'local_backupftpプラグインは、個人データやその他の機微情報を収集または保存しません。提供されたFTP設定のみを使用してバックアップを実行し、ユーザーまたは転送データに関する情報を記録または保持しません。';
$string['processing_file'] = '処理中: <b>{$a->remote_file}</b> サイズ {$a->size}';
$string['remote_file'] = 'リモートファイル';
$string['report'] = 'レポート';
$string['reports'] = 'レポート';
$string['requeue_backup'] = '再送信';
$string['requeue_backup_confirm'] = 'このバックアップを再送信しますか? リセットされ、キューに戻されます。';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">コースはすでに存在します</a>';
$string['restore_courses_and_categories'] = '復元: コースとカテゴリ';
$string['restore_file_select_help'] = '復元キューに追加するMBZファイルを選択してください。各カテゴリのボタンはそのブランチのみに影響します。';
$string['restore_report'] = '復元レポート';
$string['runtask_backup_desc'] = 'バックアップキューにすでに配置されたコースを処理し、設定されたMBZファイルを生成/送信します。';
$string['runtask_backup_title'] = 'バックアップキューを手動で実行';
$string['runtask_execute_five_courses'] = '今すぐ最大5件を処理';
$string['runtask_execute_ten_courses'] = '今すぐ最大10件を処理';
$string['runtask_manual_desc'] = 'このページは、Moodle CRONが通常実行するものと同じバックアップおよび復元タスクを実行します。キューを手動処理したい場合、設定を検証したい場合、または小さなバッチを即時実行して移行を高速化したい場合に便利です。';
$string['runtask_manual_note'] = '手動実行はスケジュールされたMoodle CRONの代替ではありません。キューが自動的に処理され続けるように、通常のCRONを有効にしておいてください。';
$string['runtask_manual_note_title'] = '重要:';
$string['runtask_restore_desc'] = '復元キューにすでに配置されたMBZファイルを処理し、Moodleに復元します。';
$string['runtask_restore_title'] = '復元キューを手動で実行';
$string['select_all'] = 'すべて選択';
$string['settings_categorystart'] = 'ルートカテゴリID';
$string['settings_categorystart_desc'] = 'コース復元を開始するルートカテゴリのID';
$string['settings_error'] = 'およびエラー';
$string['settings_error_sending_backup'] = 'バックアップ送信エラー:';
$string['settings_file_size'] = 'ファイルサイズ';
$string['settings_ftp'] = 'FTPストレージ';
$string['settings_ftpenable'] = 'FTPへ送信';
$string['settings_ftpnames'] = 'バックアップファイル名にコース名を使用';
$string['settings_ftpnames_desc'] = 'チェックすると、送信されるファイル名はコース名になります。未チェックの場合、backup-moodle2-course-21-name-20240208.mbz のようにMoodleが割り当てた名前になります。';
$string['settings_ftporganize'] = 'FTP上のバックアップをカテゴリ別に整理';
$string['settings_ftporganize_desc'] = 'ファイルは Category/Category/course.mbz として保存されます';
$string['settings_ftppassword'] = 'FTPパスワード';
$string['settings_ftppasta'] = 'リモートFTPフォルダ';
$string['settings_ftppasta_desc'] = '宛先フォルダは / で始まり、/ で終わらない必要があります（例: /backup, /save/backup）';
$string['settings_ftppasv'] = 'パッシブモードでファイルを送信しますか?';
$string['settings_ftppasv_desc'] = 'PHPのデフォルトFTPモードはアクティブモードです。アクティブモードはファイアウォール/NAT/プロキシのため、ほとんど動作しません。そのため、通常はパッシブモードを使用する必要があります。';
$string['settings_ftpurl'] = 'FTP URL';
$string['settings_ftpurl_desc'] = '目的のFTPサーバのIPアドレスまたはホスト名を入力します。FTPサーバのポートが21と異なる場合は、コロン(:)とポート番号を追加して指定します（例: 127.0.0.1:29）。FTPがSSLを使用する場合は、ドメインの前に ftps:// を追加してください。';
$string['settings_ftpusername'] = 'FTPログイン';
$string['settings_integrations'] = '連携';
$string['settings_local'] = 'ローカルストレージ';
$string['settings_localfile'] = 'バックアップをローカルフォルダに保存';
$string['settings_localfile_desc'] = '有効にすると、バックアップのコピーが下記で指定したローカルフォルダに保存されます。';
$string['settings_localfilepath'] = 'ローカルバックアップフォルダのパス';
$string['settings_localfilepath_desc'] = 'バックアップをローカルに保存するフォルダの完全パスを入力してください。サーバにこのフォルダへの書き込み権限があることを確認してください。空白の場合、バックアップは [MOODLEDATA]/backup/ に保存されます';
$string['settings_mbz_settings'] = 'バックアップ生成設定';
$string['settings_restore_settings'] = '復元設定';
$string['settings_rootsettinganonymize'] = 'ルートユーザを匿名化';
$string['settings_rootsettingusers'] = 'ルートユーザ設定';
$string['settings_tokenduration'] = 'トークン有効期間';
$string['settings_tokenduration_desc'] = '生成された各転送トークンが有効な期間です。デフォルトは48時間です。';
$string['settings_transfer_api'] = 'コース転送API';
$string['settings_transfer_api_desc'] = '短期間有効なトークンにより、別のMoodleサイトがコース、カテゴリ、バックアップを一覧表示し、MBZファイルをダウンロードできます。';
$string['status'] = 'ステータス';
$string['submit'] = '送信';
$string['token_invalid_or_expired'] = '転送トークンが無効または期限切れです。';
$string['transfer_restore_clear_session_button'] = 'リモートデータをクリア';
$string['transfer_restore_curl_required'] = '別のMoodleからバックアップを転送するには、PHP cURL拡張が必要です。';
$string['transfer_restore_desc'] = 'このオプションを使用して、以前のMoodleからバックアップ一覧を取得します。フォームデータはセッションに保存され、ファイルは選択後にのみ復元キューへ配置されます。';
$string['transfer_restore_download_too_small'] = 'ダウンロードされたバックアップファイルが空、または小さすぎます。';
$string['transfer_restore_downloading'] = '{$a->url} からリモートバックアップをダウンロード中';
$string['transfer_restore_http_error'] = '以前のMoodleへの接続エラー: {$a}';
$string['transfer_restore_http_status'] = '以前のMoodleがHTTPステータス {$a} を返しました。';
$string['transfer_restore_invalid_backup_file'] = '無効なリモートバックアップファイルです。';
$string['transfer_restore_invalid_json'] = '以前のMoodleが有効なJSON応答を返しませんでした。';
$string['transfer_restore_ip'] = '古いサーバIP（任意）';
$string['transfer_restore_ip_desc'] = 'ドメインがすでにこの新しいMoodleに移行済みの場合のみ使用してください。リクエストは古いwwwrootホストを保持しますが、このIPへのDNS解決を強制します。';
$string['transfer_restore_ip_invalid'] = '古いサーバIPが無効です。';
$string['transfer_restore_missing_remote_data'] = 'バックアップをダウンロードするためのリモートMoodleデータが不足しています。';
$string['transfer_restore_no_backups'] = '以前のMoodleからリモートバックアップファイルが返されませんでした。';
$string['transfer_restore_no_selection'] = '復元するリモートバックアップファイルを少なくとも1つ選択してください。';
$string['transfer_restore_original_category'] = '元のカテゴリID/名前';
$string['transfer_restore_original_course'] = '元のコースID/名前';
$string['transfer_restore_queue_button'] = 'リモートバックアップを一覧表示';
$string['transfer_restore_queue_summary'] = 'リモート復元キューを更新しました。新規: {$a->queued}。更新: {$a->updated}。無視: {$a->ignored}。';
$string['transfer_restore_remote_error'] = '以前のMoodleがエラーを返しました: {$a}';
$string['transfer_restore_select_file'] = '選択';
$string['transfer_restore_selected_button'] = '選択したものを復元';
$string['transfer_restore_session_cleared'] = 'リモートMoodleデータをセッションから削除しました。';
$string['transfer_restore_session_saved'] = 'リモートMoodleデータをセッションに保存しました。';
$string['transfer_restore_session_summary'] = 'リモートバックアップファイルが見つかりました: {$a}。復元するファイルを選択してください。';
$string['transfer_restore_source'] = 'ソース';
$string['transfer_restore_table_limited'] = 'キュー内の {$a} ファイルのうち最初の50件を表示しています。';
$string['transfer_restore_tempfile_error'] = '一時バックアップファイルを作成できませんでした。';
$string['transfer_restore_title'] = '別のMoodleから復元';
$string['transfer_restore_token'] = '転送トークン';
$string['transfer_restore_token_counter'] = 'トークン有効期限のカウントダウン:';
$string['transfer_restore_token_desc'] = '以前のMoodleで生成されたトークンを Backup FTP/Local > 転送トークン に貼り付けてください。';
$string['transfer_restore_token_required'] = '転送トークンは必須です。';
$string['transfer_restore_users_failed'] = 'リモートユーザをインポートできませんでした: {$a}';
$string['transfer_restore_users_summary'] = 'リモートユーザをインポートしました。作成: {$a->created}。更新: {$a->updated}。無視: {$a->ignored}。エラー: {$a->errors}。';
$string['transfer_restore_wwwroot'] = '以前のMoodle wwwroot';
$string['transfer_restore_wwwroot_desc'] = '例: https://ead-antigo.instituicao.edu.br。/local/backupftp は含めないでください。';
$string['transfer_restore_wwwroot_invalid'] = '以前のMoodle wwwroot が無効です。';
$string['transfer_restore_wwwroot_required'] = '以前のMoodle wwwroot は必須です。';
$string['transfer_token_create'] = 'トークンを作成';
$string['transfer_token_created_once'] = 'トークンを作成しました。今すぐコピーしてください:';
$string['transfer_token_created_once_desc'] = 'セキュリティのため、完全なトークンは一度だけ表示されます。その後はハッシュのみが保存されます。';
$string['transfer_token_default_name'] = 'コース転送トークン';
$string['transfer_token_expired'] = '期限切れ';
$string['transfer_token_expires'] = '有効期限';
$string['transfer_token_lastused'] = '最終使用';
$string['transfer_token_name'] = 'トークン名';
$string['transfer_token_remaining'] = '残り';
$string['transfer_token_revoke'] = '取り消し済み';
$string['transfer_token_revoke_confirm'] = 'このトークンを取り消しますか? APIまたはダウンロードでは受け付けられなくなります。';
$string['transfer_token_revoked'] = 'トークンを取り消しました。';
$string['transfer_token_status_active'] = '有効';
$string['transfer_token_uses'] = '使用回数';
$string['transfer_tokens'] = '転送トークン';
$string['transfer_tokens_desc'] = 'トークンは {$a} の転送APIおよびMBZダウンロードを承認します。別のMoodleサイトに一時アクセスが必要な場合は新しいトークンを作成してください。';
$string['view_backup_report'] = 'バックアップキューを1か所で追跡します: 保留中のコース、処理中の項目、完了したバックアップ、注意が必要なレコード。';
$string['view_restore_report'] = '復元キューを1か所で追跡します: 選択済みMBZファイル、処理中の項目、完了した復元、注意が必要なレコード。';
