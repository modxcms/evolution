<?php
/**
 * Document Manager Module
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: João Peixoto
 * Language: Portuguese
 * Date: 2014/02/24
 */
// titles
$_lang['DM_module_title'] = 'Gestor de Documentos';
$_lang['DM_action_title'] = 'Seleccionar uma Acção';
$_lang['DM_range_title'] = 'Especificar uma Gama de IDs de documentos';
$_lang['DM_tree_title'] = 'Seleccionar documentos a partir da árvore';
$_lang['DM_update_title'] = 'Actualização completa';
$_lang['DM_sort_title'] = 'Editar Índices nos Menus';

// tabs
$_lang['DM_doc_permissions'] = 'Permissões de Documentos';
$_lang['DM_template_variables'] = 'Variáveis de Template';
$_lang['DM_sort_menu'] = 'Ordenar Items nos Menus';
$_lang['DM_change_template'] = 'Mudar Template';
$_lang['DM_publish'] = 'Publicar/Retirar';
$_lang['DM_other'] = 'Outras Propriedades';

// buttons
$_lang['DM_close'] = 'Fechar o Gestor de Documentos';
$_lang['DM_cancel'] = 'Voltar';
$_lang['DM_go'] = 'Executar';
$_lang['DM_save'] = 'Guardar';
$_lang['DM_sort_another'] = 'Ordenar outro';

// templates tab
$_lang['DM_tpl_desc'] = 'Escolha o Modelo (template) pretendido da tabela abaixo e especifique as IDs dos documentos que quer alterar através da gama de IDs - consulte as opções mais abaixo para adaptar a gama ao detalhe.';
$_lang['DM_tpl_no_templates'] = 'Nenhum Modelo (template) encontrado';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Nome';
$_lang['DM_tpl_column_description'] = 'Descrição';
$_lang['DM_tpl_blank_template'] = 'Modelo (template) em Branco';
$_lang['DM_tpl_results_message'] = 'Use o botão "Voltar" se precisa de fazer mais alterações. A Cache do site foi limpa automaticamente.';

// template variables tab
$_lang['DM_tv_desc'] = 'Indique as IDs dos Documentos que quer alterar, quer através da gama de IDs - consulte as opções mais abaixo para adaptar a gama ao detalhe. Em seguida escolha o Modelo (template) requerido a partir da tabela e as Variáveis de Template associadas serão carregadas. Indique a Variável de Template pretendida e submeta as suas escolhas para processamento.';
$_lang['DM_tv_template_mismatch'] = 'Este documento não utiliza o Template escolhido.';
$_lang['DM_tv_doc_not_found'] = 'Este documento não foi encontrado na Base de Dados.';
$_lang['DM_tv_no_tv'] = 'Não foram encontradas Variáveis para este Template.';
$_lang['DM_tv_no_docs'] = 'Não foi seleccionado nenhum documento para actualização.';
$_lang['DM_tv_no_template_selected'] = 'Não foi seleccionado nenhum Template.';
$_lang['DM_tv_loading'] = 'A carregar as Variáveis de Template ...';
$_lang['DM_tv_ignore_tv'] = 'Ignorar estas Variáveis de Template (separar valores com vírgulas):';
$_lang['DM_tv_ajax_insertbutton'] = 'Inserir';

// document permissions tab
$_lang['DM_doc_desc'] = 'Escolha o grupo de documentos na tabela abaixo e se deseja adicionar ou remover o grupo. Em seguida indique as IDs dos documentos que quer alterar através da gama de IDs - consulte as opções mais abaixo para adaptar a gama ao detalhe.';
$_lang['DM_doc_no_docs'] = 'Não foram encontrados Grupos de Documentos';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Nome';
$_lang['DM_doc_radio_add'] = 'Adicionar um Grupo de Documentos';
$_lang['DM_doc_radio_remove'] = 'Remover um Grupo de Documentos';

$_lang['DM_doc_skip_message1'] = 'Documento com ID';
$_lang['DM_doc_skip_message2'] = 'já é parte do grupo de documentos seleccionado (ignorado)';

// sort menu tab
$_lang['DM_sort_pick_item'] = 'Por favor clique na raiz do site ou no documento antecessor ("pai") na Árvore de Documentos que deseja ordenar.';
$_lang['DM_sort_updating'] = 'Actualizando ...';
$_lang['DM_sort_updated'] = 'Actualizado';
$_lang['DM_sort_nochildren'] = 'O Antecessor não tem nenhum dependente';
$_lang['DM_sort_noid'] = 'Não foi seleccionado nenhum documento. Por favor volte atrás e seleccione um documento.';

// other tab
$_lang['DM_other_header'] = 'Opções diversas';
$_lang['DM_misc_label'] = 'Opções disponíveis:';
$_lang['DM_misc_desc'] = 'Por favor escolha uma opção a partir do menu abaixo e em seguida a opção pretendida. Note que apenas pode ser alterada uma opção de cada vez.';

$_lang['DM_other_dropdown_publish'] = 'Publicar/Retirar';
$_lang['DM_other_dropdown_show'] = 'Mostrar/Ocultar nos Menus';
$_lang['DM_other_dropdown_search'] = 'Activar/Desactivar Procura';
$_lang['DM_other_dropdown_cache'] = 'Usar/Não usar Cache';
$_lang['DM_other_dropdown_richtext'] = 'Usar/Não usar Editor gráfico de texto (tipo Word)';
$_lang['DM_other_dropdown_delete'] = 'Apagar/Recuperar';

// radio button text
$_lang['DM_other_publish_radio1'] = 'Publicar';
$_lang['DM_other_publish_radio2'] = 'Retirar';
$_lang['DM_other_show_radio1'] = 'Ocultar nos Menus';
$_lang['DM_other_show_radio2'] = 'Mostra nos Menus';
$_lang['DM_other_search_radio1'] = 'Activar Procura';
$_lang['DM_other_search_radio2'] = 'Desactivar Procura';
$_lang['DM_other_cache_radio1'] = 'Usar Cache';
$_lang['DM_other_cache_radio2'] = 'Não usar Cache';
$_lang['DM_other_richtext_radio1'] = 'Usar Editor gráfico de texto (tipo Word)';
$_lang['DM_other_richtext_radio2'] = 'Não usar Editor gráfico de texto (tipo Word)';
$_lang['DM_other_delete_radio1'] = 'Apagar';
$_lang['DM_other_delete_radio2'] = 'Recuperar';

// adjust dates
$_lang['DM_adjust_dates_header'] = 'Atribuir Datas a documentos';
$_lang['DM_adjust_dates_desc'] = 'Qualquer uma das seguintes opções de Data dos documentos pode ser alterada. Use a opção "Ver Calendário" para atribuir datas.';
$_lang['DM_view_calendar'] = 'Ver Calendário';
$_lang['DM_clear_date'] = 'Limpar Datas';

// adjust authors
$_lang['DM_adjust_authors_header'] = 'Atribuir Autores';
$_lang['DM_adjust_authors_desc'] = 'Use as listas abaixo para atribuir novos autores para o Documento.';
$_lang['DM_adjust_authors_createdby'] = 'Criado por:';
$_lang['DM_adjust_authors_editedby'] = 'Editado por:';
$_lang['DM_adjust_authors_noselection'] = 'Nenhuma alteração';

// labels
$_lang['DM_date_pubdate'] = 'Data de publicação:';
$_lang['DM_date_unpubdate'] = 'Data de retirada:';
$_lang['DM_date_createdon'] = 'Data de criação:';
$_lang['DM_date_editedon'] = 'Editado na Data:';
$_lang['DM_date_notset'] = ' (não indicada)';
$_lang['DM_date_dateselect_label'] = 'Seleccione uma Data: ';

// document select section
$_lang['DM_select_submit'] = 'Submeter';
$_lang['DM_select_range'] = 'Voltar a scolher uma gama de IDs de Documentos';
$_lang['DM_select_range_text'] = '<p>Legenda (onde <strong>n</strong> é o número de ID de um documento):<br /><br />
							  n* - Mudar opções para este documento e para os seus descendentes directos<br /> 
							  n** - Mudar opções para este documento e para TODOS os seus descendentes<br /> 
							  n-n2 - Mudar opções para esta gama de documentos<br /> 
							  n - Mudar opções para um único documento</p> 
							  <p>Exemplo: 1*,4**,2-20,25 - Isto irá mudar as opções escolhidas
						      para o documento 1 e descendentes directos, documento 4 e todos os seus descendentes, documentos
									2 até 20 e para o documento 25.</p>';
$_lang['DM_select_tree'] = 'Ver e seleccionar documentos usando a Árvore de Documentos';

// process tree/range messages
$_lang['DM_process_noselection'] = 'Não foi feita nenhuma selecção. ';
$_lang['DM_process_novalues'] = 'Não foram especificados nenhuns valores.';
$_lang['DM_process_limits_error'] = 'Limite superior menor que o limite inferior:';
$_lang['DM_process_invalid_error'] = 'Valor Inválido:';
$_lang['DM_process_update_success'] = 'Actualização completa, sem erros a assinalar.';
$_lang['DM_process_update_error'] = 'Actualização completa, mas foram encontrados erros:';
$_lang['DM_process_back'] = 'Voltar';

// manager access logging
$_lang['DM_log_template'] = 'Gestor de Documentos: Modelos (templates) alterados.';
$_lang['DM_log_templatevariables'] = 'Gestor de Documentos: Variáveis de Template alteradas.';
$_lang['DM_log_docpermissions'] = 'Gestor de Documentos: Permissões de Documentos alteradas.';
$_lang['DM_log_sortmenu'] = 'Gestor de Documentos: Indexação de Menus completa.';
$_lang['DM_log_publish'] = 'Gestor de Documentos: Opções de Publicar/Retirar alteradas.';
$_lang['DM_log_hidemenu'] = 'Gestor de Documentos: Opções Mostrar/Ocultar alteradas.';
$_lang['DM_log_search'] = 'Gestor de Documentos: Opções Activar/Desactivar Procura alteradas.';
$_lang['DM_log_cache'] = 'Gestor de Documentos: Opções Usar/Não usar Cache alteradas.';
$_lang['DM_log_richtext'] = 'Gestor de Documentos: Opções Usar/Não usar Editor gráfico de texto (tipo Word) alteradas.';
$_lang['DM_log_delete'] = 'Gestor de Documentos: Opções Apagar/Recuperar alteradas.';
$_lang['DM_log_dates'] = 'Gestor de Documentos: Opções de Datas alteradas.';
$_lang['DM_log_authors'] = 'Gestor de Documentos: Opções de Autores alteradas.';
?>
