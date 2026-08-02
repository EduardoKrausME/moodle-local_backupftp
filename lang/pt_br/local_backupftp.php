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
 * Lang pt_br file
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['access_course'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">Acessar o curso</a>';
$string['adding_to_category'] = 'Será adicionado à categoria {$a->categoria}';
$string['already_added_status'] = 'Já adicionado e o status é {$a->status}';
$string['api_invalid_action'] = 'Ação inválida da API.';
$string['backup_category_select_help'] = 'Selecione as categorias cujos cursos devem ser adicionados à fila de backup. Os botões em cada cartão afetam essa categoria e todas as subcategorias dentro dela.';
$string['backup_courses_and_categories'] = 'Backup: Cursos e categorias';
$string['backup_creation_parameters'] = 'O backup será criado com os seguintes parâmetros';
$string['backup_end'] = 'Backup finalizado em';
$string['backup_end_time'] = 'Hora de término do backup';
$string['backup_report'] = 'Relatório de backup';
$string['backup_start'] = 'Backup iniciado em';
$string['backup_start_time'] = 'Hora de início do backup';
$string['back_to_plugin_home'] = 'Voltar ao início do plugin';
$string['plugin_navigation'] = 'Navegação do Backup FTP/Local';
$string['backupftp:manage'] = 'Gerenciar backup';
$string['categories'] = 'Categorias';
$string['category_created_successfully'] = ' ==> Categoria {$a->categoria_nome} criada com sucesso';
$string['category_link'] = 'Categoria <a href="{$a}" target="blank">Categoria raiz</a>';
$string['click_here'] = 'Clique aqui';
$string['course'] = 'Curso';
$string['course_added_to_backup_queue'] = 'Curso {$a->course_id} ({$a->course_name}) adicionado à fila de backup.';
$string['courses'] = 'Cursos';
$string['courses_and_categories'] = 'Cursos e categorias';
$string['created_at'] = 'Criado em';
$string['created_on'] = 'Criado em';
$string['created_on_time'] = 'Criado em {$a->modify}';
$string['cron'] = 'CRON';
$string['deselect_all'] = 'Desmarcar todos';
$string['error_creating_folder'] = '<span style="color:#d10707">Erro ao criar pasta</span> "<b>{$a->ftppasta}</b>" no FTP com o erro "<b>{$->errormsg}</b>"!';
$string['error_downloading_file'] = 'Erro ao baixar o arquivo MBZ, com o erro "<b>{$a->error}</b>"';
$string['error_extracting_mbz'] = 'Erro ao extrair o arquivo MBZ';
$string['file_added_to_restore_queue'] = 'Arquivo {$a->file} adicionado à fila de restauração';
$string['file_found_and_downloaded'] = 'Arquivo localizado e baixado';
$string['file_size'] = 'com tamanho {$a->size}';
$string['file_size_label'] = 'Tamanho do arquivo';
$string['file_uploaded'] = 'Arquivo "<b>{$a->file}</b>" enviado para "<b>{$a->remote_file}</b>"!';
$string['ftp_error_connecting'] = 'Erro ao conectar ao FTP';
$string['ftp_error_login'] = 'Não foi possível conectar com {$a->username}@{$a->url}';
$string['ftp_remote_file_size'] = 'O FTP retornou que o arquivo remoto tem "<b>{$a->size} bytes</b>"';
$string['index_backup_button'] = 'Abrir tela de backup';
$string['index_backup_desc'] = 'Use esta área para selecionar cursos e categorias e colocar a geração de backups na fila. Os arquivos gerados podem ser salvos localmente e/ou enviados para FTP, conforme as configurações do plugin.';
$string['index_backup_report_button'] = 'Ver relatório de backup';
$string['index_backup_title'] = 'Backup de cursos';
$string['index_flow_step1_after_old_moodle'] = 'gere ou atualize os backups dos cursos que serão transferidos.';
$string['index_flow_step2_after_mbz'] = 'downloads apenas enquanto ele estiver válido.';
$string['index_flow_step2_after_token_before_mbz'] = 'Ele habilita a API e';
$string['index_flow_step2_before_token'] = 'Ainda no Moodle antigo, crie um';
$string['index_flow_step3_after_new_moodle_before_wwwroot'] = 'abra a tela de restauração e informe o';
$string['index_flow_step3_after_wwwroot'] = ', o token e, se necessário, o IP da máquina antiga.';
$string['index_flow_step4'] = 'Confira a lista retornada pela API e envie os arquivos para a fila. O cron fará o download e restaurará os cursos em segundo plano.';
$string['index_flow_step_moodle'] = 'No';
$string['index_intro_desc'] = 'Este plugin ajuda a migrar cursos de uma instalação Moodle para outra com mais segurança. O Moodle antigo gera os backups e libera o acesso por meio de um token temporário. O Moodle novo consulta a API, lista os arquivos disponíveis e coloca as restaurações na fila para execução pelo cron.';
$string['index_new_moodle'] = 'Moodle novo';
$string['index_old_moodle'] = 'Moodle antigo';
$string['index_recommended_flow_title'] = 'Fluxo recomendado';
$string['index_reports_desc'] = 'Use os relatórios para acompanhar o que já foi colocado na fila, o que está sendo processado, quais backups foram concluídos e quais restaurações precisam de atenção.';
$string['index_reports_title'] = 'Relatórios e monitoramento';
$string['index_restore_button'] = 'Abrir tela de restauração';
$string['index_restore_desc_after_wwwroot'] = ', o token e, opcionalmente, o IP da máquina antiga quando o domínio já tiver sido migrado para o novo servidor.';
$string['index_restore_desc_before_wwwroot'] = 'Use esta tela para importar backups de outro Moodle. Informe o';
$string['index_restore_queue_desc'] = 'Após a consulta, os arquivos remotos são colocados na fila de restauração. Assim, a migração pode continuar pelo cron sem depender da página aberta no navegador.';
$string['index_restore_report_button'] = 'Ver relatório de restauração';
$string['index_restore_title'] = 'Restauração no Moodle novo';
$string['index_title'] = 'Transferência de cursos entre Moodles';
$string['index_token_time_desc'] = 'O token tem validade limitada, configurada nesta página de administração. Antes de iniciar uma migração grande, confirme se o cron do Moodle novo está ativo e se o tempo restante do token é suficiente para baixar todos os backups necessários.';
$string['index_token_time_title'] = 'Atenção ao tempo de validade do token';
$string['index_tokens_button'] = 'Gerenciar tokens';
$string['index_tokens_desc_after_mbz'] = 'O token não substitui um login administrativo: ele deve ser compartilhado apenas durante a janela de migração.';
$string['index_tokens_desc_before_mbz'] = 'Crie tokens temporários para permitir que outro Moodle consulte cursos, categorias, backups e baixe';
$string['index_transfer_token'] = 'token de transferência';
$string['log:savelocal:error'] = 'Falha ao salvar o backup localmente: {$a}';
$string['log:savelocal:success'] = 'Backup salvo localmente: {$a}';
$string['logs'] = 'Logs';
$string['manual_cron_button'] = 'Abrir execução manual';
$string['manual_cron_desc'] = 'Use esta página para processar backups ou restaurações em fila agora, testar a tarefa manualmente ou acelerar uma migração sem esperar pelo próximo ciclo agendado do CRON do Moodle.';
$string['manual_cron_title'] = 'Execução manual do CRON';
$string['mbz_extracted_successfully'] = 'MBZ extraído com sucesso';
$string['nothing_to_execute'] = 'Nada para executar';
$string['pluginname'] = 'Backup FTP/Local';
$string['pre_check_failure'] = 'Falha na pré-verificação';
$string['privacy:metadata'] = 'O plugin local_backupftp não coleta nem armazena dados pessoais ou qualquer outro dado sensível. Ele usa apenas as configurações de FTP fornecidas para realizar backups, sem registrar ou reter informações relacionadas aos usuários ou aos dados transferidos.';
$string['processing_file'] = 'Processando: <b>{$a->remote_file}</b> com {$a->size}';
$string['remote_file'] = 'Arquivo remoto';
$string['report'] = 'Relatório';
$string['reports'] = 'Relatórios';
$string['requeue_backup'] = 'Reenviar';
$string['requeue_backup_confirm'] = 'Reenviar este backup? Ele será redefinido e colocado novamente na fila.';
$string['restore_course_already_exists'] = '<a style="color:#a41d1d" target="_blank" href="{$a->course_url}">O curso já existe</a>';
$string['restore_courses_and_categories'] = 'Restauração: Cursos e categorias';
$string['restore_file_select_help'] = 'Selecione os arquivos MBZ que devem ser adicionados à fila de restauração. Os botões em cada categoria afetam apenas esse ramo.';
$string['restore_report'] = 'Relatório de restauração';
$string['runtask_backup_desc'] = 'Processa cursos já colocados na fila de backup e gera/envia os arquivos MBZ configurados.';
$string['runtask_backup_title'] = 'Executar fila de backup manualmente';
$string['runtask_execute_five_courses'] = 'Processar até 5 itens agora';
$string['runtask_execute_ten_courses'] = 'Processar até 10 itens agora';
$string['runtask_manual_desc'] = 'Esta página executa as mesmas tarefas de backup e restauração que o CRON do Moodle normalmente executa. Ela é útil quando você deseja processar a fila manualmente, validar a configuração ou acelerar uma migração executando um pequeno lote imediatamente.';
$string['runtask_manual_note'] = 'a execução manual não substitui o CRON agendado do Moodle. Mantenha o CRON normal ativo para que a fila continue sendo processada automaticamente.';
$string['runtask_manual_note_title'] = 'Importante:';
$string['runtask_restore_desc'] = 'Processa arquivos MBZ já colocados na fila de restauração e os restaura no Moodle.';
$string['runtask_restore_title'] = 'Executar fila de restauração manualmente';
$string['select_all'] = 'Selecionar todos';
$string['settings_categorystart'] = 'ID da categoria raiz';
$string['settings_categorystart_desc'] = 'O ID da categoria raiz para iniciar a restauração dos cursos';
$string['settings_error'] = 'e erro';
$string['settings_error_sending_backup'] = 'Erro ao enviar backup para';
$string['settings_file_size'] = 'com tamanho de arquivo';
$string['settings_ftp'] = 'Armazenamento FTP';
$string['settings_ftpenable'] = 'Enviar para FTP';
$string['settings_ftpnames'] = 'Usar o nome do curso como nome do arquivo de backup';
$string['settings_ftpnames_desc'] = 'Se marcado, o nome do arquivo enviado será o nome do curso. Caso contrário, será o nome atribuído pelo Moodle, semelhante a backup-moodle2-course-21-name-20240208.mbz';
$string['settings_ftporganize'] = 'Organizar backups no FTP por categorias';
$string['settings_ftporganize_desc'] = 'O arquivo será salvo como Categoria/Categoria/curso.mbz';
$string['settings_ftppassword'] = 'Senha do FTP';
$string['settings_ftppasta'] = 'Pasta FTP remota';
$string['settings_ftppasta_desc'] = 'A pasta de destino deve começar com / e não terminar com / (ex.: /backup, /save/backup)';
$string['settings_ftppasv'] = 'Enviar arquivo em modo passivo?';
$string['settings_ftppasv_desc'] = 'O modo FTP padrão no PHP é o modo ativo. O modo ativo raramente funciona devido a firewalls/NATs/proxies. Portanto, quase sempre é necessário usar o modo passivo.';
$string['settings_ftpurl'] = 'URL do FTP';
$string['settings_ftpurl_desc'] = 'Informe o endereço IP ou hostname do servidor FTP desejado. Se a porta do servidor FTP for diferente de 21, especifique-a adicionando dois-pontos (:) seguido do número da porta, por exemplo, 127.0.0.1:29. Se o seu FTP usa SSL, adicione ftps:// antes do domínio.';
$string['settings_ftpusername'] = 'Login do FTP';
$string['settings_integrations'] = 'Integrações';
$string['settings_local'] = 'Armazenamento local';
$string['settings_localfile'] = 'Salvar backups em uma pasta local';
$string['settings_localfile_desc'] = 'Se habilitado, uma cópia dos backups será armazenada em uma pasta local especificada abaixo.';
$string['settings_localfilepath'] = 'Caminho para a pasta local de backup';
$string['settings_localfilepath_desc'] = 'Informe o caminho completo da pasta onde os backups serão armazenados localmente. Certifique-se de que o servidor tenha permissão de escrita nessa pasta. Se ficar em branco, os backups serão salvos em [MOODLEDATA]/backup/';
$string['settings_mbz_settings'] = 'Configurações de geração de backup';
$string['settings_restore_settings'] = 'Configurações de restauração';
$string['settings_rootsettinganonymize'] = 'Anonimizar usuários raiz';
$string['settings_rootsettingusers'] = 'Configuração de usuários raiz';
$string['settings_tokenduration'] = 'Validade do token';
$string['settings_tokenduration_desc'] = 'Por quanto tempo cada token de transferência gerado permanece válido. O padrão é 48 horas.';
$string['settings_transfer_api'] = 'API de transferência de cursos';
$string['settings_transfer_api_desc'] = 'Tokens de curta duração permitem que outro site Moodle liste cursos, categorias e backups, e baixe arquivos MBZ.';
$string['status'] = 'Status';
$string['submit'] = 'Enviar';
$string['temporary_files_deleted'] = 'Arquivos temporários excluídos';
$string['token_invalid_or_expired'] = 'Token de transferência inválido ou expirado.';
$string['transfer_restore_clear_session_button'] = 'Limpar dados remotos';
$string['transfer_restore_curl_required'] = 'A extensão PHP cURL é obrigatória para transferir backups de outro Moodle.';
$string['transfer_restore_desc'] = 'Use esta opção para buscar a lista de backups do Moodle anterior. Os dados do formulário são salvos na sua sessão e os arquivos só são colocados na fila de restauração depois que você os selecionar.';
$string['transfer_restore_download_too_small'] = 'O arquivo de backup baixado está vazio ou é pequeno demais.';
$string['transfer_restore_downloading'] = 'Baixando backup remoto de {$a->url}';
$string['transfer_restore_http_error'] = 'Erro ao conectar ao Moodle anterior: {$a}';
$string['transfer_restore_http_status'] = 'O Moodle anterior retornou o status HTTP {$a}.';
$string['transfer_restore_invalid_backup_file'] = 'Arquivo de backup remoto inválido.';
$string['transfer_restore_invalid_json'] = 'O Moodle anterior não retornou uma resposta JSON válida.';
$string['transfer_restore_ip'] = 'IP do servidor antigo (opcional)';
$string['transfer_restore_ip_desc'] = 'Use apenas quando o domínio já tiver sido migrado para este novo Moodle. A requisição mantém o host wwwroot antigo, mas força a resolução DNS para este IP.';
$string['transfer_restore_ip_invalid'] = 'IP do servidor antigo inválido.';
$string['transfer_restore_missing_remote_data'] = 'Faltam dados do Moodle remoto para baixar o backup.';
$string['transfer_restore_no_backups'] = 'Nenhum arquivo de backup remoto foi retornado pelo Moodle anterior.';
$string['transfer_restore_no_selection'] = 'Selecione pelo menos um arquivo de backup remoto para restaurar.';
$string['transfer_restore_original_category'] = 'ID/nome da categoria original';
$string['transfer_restore_original_course'] = 'ID/nome do curso original';
$string['transfer_restore_queue_button'] = 'Listar backups remotos';
$string['transfer_restore_queue_summary'] = 'Fila de restauração remota atualizada. Novos: {$a->queued}. Atualizados: {$a->updated}. Ignorados: {$a->ignored}.';
$string['transfer_restore_remote_error'] = 'O Moodle anterior retornou um erro: {$a}';
$string['transfer_restore_select_file'] = 'Selecionar';
$string['transfer_restore_selected_button'] = 'Restaurar selecionados';
$string['transfer_restore_session_cleared'] = 'Dados do Moodle remoto removidos da sua sessão.';
$string['transfer_restore_session_saved'] = 'Dados do Moodle remoto salvos na sua sessão.';
$string['transfer_restore_session_summary'] = 'Arquivos de backup remotos encontrados: {$a}. Selecione os arquivos que deseja restaurar.';
$string['transfer_restore_source'] = 'Origem';
$string['transfer_restore_table_limited'] = 'Mostrando os primeiros 50 de {$a} arquivos em fila.';
$string['transfer_restore_tempfile_error'] = 'Não foi possível criar o arquivo temporário de backup.';
$string['transfer_restore_title'] = 'Restaurar de outro Moodle';
$string['transfer_restore_token'] = 'Token de transferência';
$string['transfer_restore_token_counter'] = 'Contagem regressiva da validade do token:';
$string['transfer_restore_token_desc'] = 'Cole o token gerado no Moodle anterior em Backup FTP/Local > Tokens de transferência.';
$string['transfer_restore_token_remaining_log'] = 'Token de transferência ainda válido por {$a}.';
$string['transfer_restore_token_required'] = 'O token de transferência é obrigatório.';
$string['transfer_restore_users_failed'] = 'Não foi possível importar os usuários remotos: {$a}';
$string['transfer_restore_users_summary'] = 'Usuários remotos importados. Criados: {$a->created}. Atualizados: {$a->updated}. Ignorados: {$a->ignored}. Erros: {$a->errors}.';
$string['transfer_restore_wwwroot'] = 'wwwroot do Moodle anterior';
$string['transfer_restore_wwwroot_desc'] = 'Exemplo: https://ead-antigo.instituicao.edu.br. Não inclua /local/backupftp.';
$string['transfer_restore_wwwroot_invalid'] = 'wwwroot do Moodle anterior inválido.';
$string['transfer_restore_wwwroot_required'] = 'O wwwroot do Moodle anterior é obrigatório.';
$string['transfer_token_create'] = 'Criar token';
$string['transfer_token_created_once'] = 'Token criado. Copie agora:';
$string['transfer_token_created_once_desc'] = 'Por segurança, o token completo é exibido apenas uma vez. Depois disso, somente o hash é armazenado.';
$string['transfer_token_default_name'] = 'Token de transferência de cursos';
$string['transfer_token_expired'] = 'Expirado';
$string['transfer_token_expired_before_restore'] = 'O token de transferência expirou antes que este backup pudesse ser restaurado.';
$string['transfer_token_expires'] = 'Expira em';
$string['transfer_token_lastused'] = 'Último uso';
$string['transfer_token_name'] = 'Nome do token';
$string['transfer_token_remaining'] = 'Restante';
$string['transfer_token_revoke'] = 'Revogado';
$string['transfer_token_revoke_confirm'] = 'Revogar este token? Ele não será mais aceito pela API ou pelos downloads.';
$string['transfer_token_revoked'] = 'Token revogado.';
$string['transfer_token_status_active'] = 'Ativo';
$string['transfer_token_uses'] = 'Usos';
$string['transfer_tokens'] = 'Tokens de transferência';
$string['transfer_tokens_desc'] = 'Os tokens autorizam a API de transferência e os downloads MBZ para {$a}. Crie um novo token quando outro site Moodle precisar de acesso temporário.';
$string['view_backup_report'] = 'Acompanhe a fila de backup em um só lugar: cursos pendentes, itens em processamento, backups concluídos e registros que precisam de atenção.';
$string['view_restore_report'] = 'Acompanhe a fila de restauração em um só lugar: arquivos MBZ selecionados, itens em processamento, restaurações concluídas e registros que precisam de atenção.';
