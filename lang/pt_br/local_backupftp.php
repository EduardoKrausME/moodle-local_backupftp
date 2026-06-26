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
 * Brazilian Portuguese language file.
 *
 * @package   local_backupftp
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['backup_category_select_help'] = 'Selecione as categorias cujos cursos devem ser adicionados à fila de backup. Os botões de cada cartão afetam aquela categoria e todas as subcategorias dentro dela.';
$string['backup_report'] = 'Relatório de backups';
$string['categories'] = 'Categorias';
$string['courses'] = 'Cursos';
$string['cron'] = 'CRON';
$string['cron_task'] = 'Execução manual do CRON';
$string['deselect_all'] = 'Desmarcar tudo';
$string['manual_cron_button'] = 'Abrir execução manual';
$string['manual_cron_desc'] = 'Use esta página para processar agora os backups ou restores que estão na fila, agilizar uma migração sem esperar o próximo ciclo agendado do CRON do Moodle.';
$string['manual_cron_title'] = 'Execução manual do CRON';
$string['report'] = 'Relatório';
$string['restore_file_select_help'] = 'Selecione os arquivos MBZ que devem ser adicionados à fila de restauração. Os botões de cada categoria afetam apenas aquele ramo.';
$string['restore_report'] = 'Relatório de restaurações';
$string['run_cron'] = 'Execução manual do CRON';
$string['runtask_backup'] = 'Processar fila de backup';
$string['runtask_backup_desc'] = 'Processa os cursos que já foram colocados na fila de backup e gera/envia os arquivos MBZ conforme a configuração do plugin.';
$string['runtask_backup_title'] = 'Executar a fila de backup manualmente';
$string['runtask_execute_five_courses'] = 'Processar até 5 itens agora';
$string['runtask_execute_one_course'] = 'Processar 1 item agora';
$string['runtask_execute_ten_courses'] = 'Processar até 10 itens agora';
$string['runtask_manual_desc'] = 'Esta página executa as mesmas tarefas de backup e restauração que normalmente são executadas pelo CRON do Moodle. Ela é útil para processar a fila manualmente, validar a configuração ou agilizar uma migração executando um pequeno lote imediatamente.';
$string['runtask_manual_note'] = 'a execução manual não substitui o CRON agendado do Moodle. Mantenha o CRON normal ativo para que a fila continue sendo processada automaticamente.';
$string['runtask_manual_note_title'] = 'Importante:';
$string['runtask_restore'] = 'Processar fila de restauração';
$string['runtask_restore_desc'] = 'Processa os arquivos MBZ que já foram colocados na fila de restauração e restaura esses cursos no Moodle.';
$string['runtask_restore_title'] = 'Executar a fila de restauração manualmente';
$string['select_all'] = 'Marcar tudo';
$string['select_deselect_all'] = 'Marcar/Desmarcar tudo';
$string['settings_ftp'] = 'Armazenamento FTP';
$string['settings_local'] = 'Armazenamento local';
$string['submit'] = 'Enviar';
$string['view_backup_report'] = 'Acompanhe os backups em um único lugar: cursos pendentes, itens em processamento, backups concluídos e registros que precisam de atenção.';
$string['view_restore_report'] = 'Acompanhe as restaurações em um único lugar: arquivos MBZ selecionados, itens em processamento, restaurações concluídas e registros que precisam de atenção.';
$string['already_added_status'] = 'Já adicionado à fila, com status {$a->status}';
$string['category_link'] = 'Categoria <a href="{$a}" target="blank">Categoria raiz</a>';
$string['created_on_time'] = 'criado em {$a->modify}';
$string['file_size'] = 'com tamanho {$a->size}';
$string['file_added_to_restore_queue'] = 'Arquivo {$a->file} adicionado à fila de restauração';
$string['course_added_to_backup_queue'] = 'Curso {$a->course_id} ({$a->course_name}) adicionado à fila de backup.';
$string['backup_courses_and_categories'] = 'Backup: cursos e categorias';
$string['restore_courses_and_categories'] = 'Restauração: cursos e categorias';
