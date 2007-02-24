<?php
/**
 * Document Manager Module - portuguese.inc.php
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Joao Peixoto
 * For: MODx CMS (www.modxcms.com)
 * Date:08/01/2006 Version: 1.6
 * 
 */
 
//-- PORTUGUESE LANGUAGE FILE
 
//-- titles
$_lang['DM_module_title'] = 'Gestor de Documentos';
$_lang['DM_action_title'] = 'Seleccionar uma Ac&ccedil;&atilde;o';
$_lang['DM_range_title'] = 'Especificar uma Gama de IDs de documentos';
$_lang['DM_tree_title'] = 'Seleccionar documentos a partir da &aacute;rvore';
$_lang['DM_update_title'] = 'Actualiza&ccedil;&atilde;o completa';
$_lang['DM_sort_title'] = 'Editar &Iacute;ndices nos Menus';

//-- tabs
$_lang['DM_doc_permissions'] = 'Permiss&otilde;es de Documentos';
$_lang['DM_template_variables'] = 'Vari&aacute;veis de Template';
$_lang['DM_sort_menu'] = 'Ordenar Items nos Menus';
$_lang['DM_change_template'] = 'Mudar Modelo (template)';
$_lang['DM_publish'] = 'Publicar/Retirar';
$_lang['DM_other'] = 'Outras Propriedades';
 
//-- buttons
$_lang['DM_close'] = 'Fechar o Gestor de Documentos';
$_lang['DM_cancel'] = 'Voltar';
$_lang['DM_go'] = 'Executar';
$_lang['DM_save'] = 'Guardar';
$_lang['DM_sort_another'] = 'Ordenar outro';

//-- templates tab
$_lang['DM_tpl_desc'] = 'Escolha o Modelo (template) pretendido da tabela abaixo e especifique as IDs dos documentos que quer alterar atrav&eacute;s da gama de IDs - consulte as op&ccedil;&otilde;es mais abaixo para adaptar a gama ao detalhe.';
$_lang['DM_tpl_no_templates'] = 'Nenhum Modelo (template) encontrado';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Nome';
$_lang['DM_tpl_column_description'] ='Descri&ccedil;&atilde;o';
$_lang['DM_tpl_blank_template'] = 'Modelo (template) em Branco';

$_lang['DM_tpl_results_message']= 'Use o bot&atilde;o "Voltar" se precisa de fazer mais altera&ccedil;&otilde;es. A Cache do site foi limpa automaticamente.';

//-- template variables tab
$_lang['DM_tv_desc'] = 'Indique as IDs dos Documentos que quer alterar, quer atrav&eacute;s da gama de IDs - consulte as op&ccedil;&otilde;es mais abaixo para adaptar a gama ao detalhe. Em seguida escolha o Modelo (template) requerido a partir da tabela e as Vari&aacute;veis de Template associadas ser&atilde;o carregadas. Indique a Vari&aacute;vel de Template pretendida e submeta as suas escolhas para processamento.';
$_lang['DM_tv_template_mismatch'] = 'Este documento n&atilde;o utiliza o Modelo (template) escolhido.';
$_lang['DM_tv_doc_not_found'] = 'Este documento n&atilde;o foi encontrado na Base de Dados.';
$_lang['DM_tv_no_tv'] = 'N&atilde;o foram encontradas Vari&aacute;veis de Template para este Modelo.';
$_lang['DM_tv_no_docs'] = 'N&atilde;o foi seleccionado nenhum documento para actualiza&ccedil;&atilde;o.';
$_lang['DM_tv_no_template_selected'] = 'N&atilde;o foi seleccionado nenhum Modelo (template).';
$_lang['DM_tv_loading'] = 'A carregar as Vari&aacute;veis de Template ...';
$_lang['DM_tv_ignore_tv'] = 'Ignorar estas Vari&aacute;veis de Template (separar valores com v&iacute;rgulas):';
$_lang['DM_tv_ajax_insertbutton'] = 'Inserir';

//-- document permissions tab
$_lang['DM_doc_desc'] = 'Escolha o grupo de documentos na tabela abaixo e se deseja adicionar ou remover o grupo. Em seguida indique as IDs dos documentos que quer alterar atrav&eacute;s da gama de IDs - consulte as op&ccedil;&otilde;es mais abaixo para adaptar a gama ao detalhe.';
$_lang['DM_doc_no_docs'] = 'N&atilde;o foram encontrados Grupos de Documentos';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Nome';
$_lang['DM_doc_radio_add'] = 'Adicionar um Grupo de Documentos';
$_lang['DM_doc_radio_remove'] = 'Remover um Grupo de Documentos';

$_lang['DM_doc_skip_message1'] = 'Documento com ID';
$_lang['DM_doc_skip_message2'] = 'j&aacute; &eacute; parte do grupo de documentos seleccionado (ignorado)';

//-- sort menu tab
$_lang['DM_sort_pick_item'] = 'Por favor clique na raiz do site ou no documento antecessor ("pai") na &Aacute;rvore de Documentos que deseja ordenar.'; 
$_lang['DM_sort_updating'] = 'Actualizando ...';
$_lang['DM_sort_updated'] = 'Actualizado';
$_lang['DM_sort_nochildren'] = 'O Antecessor n&atilde;o tem nenhum dependente';
$_lang['DM_sort_noid']='N&atilde;o foi seleccionado nenhum documento. Por favor volte atr&aacute;s e seleccione um documento.';

//-- other tab
$_lang['DM_other_header'] = 'Op&ccedil;&otilde;es diversas';
$_lang['DM_misc_label'] = 'Op&ccedil;&otilde;es dispon&iacute;veis:';
$_lang['DM_misc_desc'] = 'Por favor escolha uma op&ccedil;&atilde;o a partir do menu abaixo e em seguida a op&ccedil;&atilde;o pretendida. Note que apenas pode ser alterada uma op&ccedil;&atilde;o de cada vez.';

$_lang['DM_other_dropdown_publish'] = 'Publicar/Retirar';
$_lang['DM_other_dropdown_show'] = 'Mostrar/Ocultar nos Menus';
$_lang['DM_other_dropdown_search'] = 'Activar/Desactivar Procura';
$_lang['DM_other_dropdown_cache'] = 'Usar/N&atilde;o usar Cache';
$_lang['DM_other_dropdown_richtext'] = 'Usar/N&atilde;o usar Editor gr&aacute;fico de texto (tipo Word)';
$_lang['DM_other_dropdown_delete'] = 'Apagar/Recuperar';

//-- radio button text
$_lang['DM_other_publish_radio1'] = 'Publicar'; 
$_lang['DM_other_publish_radio2'] = 'Retirar';
$_lang['DM_other_show_radio1'] = 'Ocultar nos Menus'; 
$_lang['DM_other_show_radio2'] = 'Mostra nos Menus';
$_lang['DM_other_search_radio1'] = 'Activar Procura'; 
$_lang['DM_other_search_radio2'] = 'Desactivar Procura';
$_lang['DM_other_cache_radio1'] = 'Usar Cache'; 
$_lang['DM_other_cache_radio2'] = 'N&atilde;o usar Cache';
$_lang['DM_other_richtext_radio1'] = 'Usar Editor gr&aacute;fico de texto (tipo Word)'; 
$_lang['DM_other_richtext_radio2'] = 'N&atilde;o usar Editor gr&aacute;fico de texto (tipo Word)';
$_lang['DM_other_delete_radio1'] = 'Apagar'; 
$_lang['DM_other_delete_radio2'] = 'Recuperar';

//-- adjust dates 
$_lang['DM_adjust_dates_header'] = 'Atribuir Datas a documentos';
$_lang['DM_adjust_dates_desc'] = 'Qualquer uma das seguintes op&ccedil;&otilde;es de Data dos documentos pode ser alterada. Use a op&ccedil;&atilde;o "Ver Calend&aacute;rio" para atribuir datas.';
$_lang['DM_view_calendar'] = 'Ver Calend&aacute;rio';
$_lang['DM_clear_date'] = 'Limpar Datas';

//-- adjust authors
$_lang['DM_adjust_authors_header'] = 'Atribuir Autores';
$_lang['DM_adjust_authors_desc'] = 'Use as listas abaixo para atribuir novos autores para o Documento.';
$_lang['DM_adjust_authors_createdby'] = 'Criado por:';
$_lang['DM_adjust_authors_editedby'] = 'Editado por:';
$_lang['DM_adjust_authors_noselection'] = 'Nenhuma altera&ccedil;&atilde;o';

 //-- labels
$_lang['DM_date_pubdate'] = 'Data de publica&ccedil;&atilde;o:';
$_lang['DM_date_unpubdate'] = 'Data de retirada:';
$_lang['DM_date_createdon'] = 'Data de cria&ccedil;&atilde;o:';
$_lang['DM_date_editedon'] = 'Editado na Data:';
//$_lang['DM_date_deletedon'] = 'Deleted On Date';

$_lang['DM_date_notset'] = ' (n&atilde;o indicada)';
//deprecated
$_lang['DM_date_dateselect_label'] = 'Seleccione uma Data: ';

//-- document select section
$_lang['DM_select_submit'] = 'Submeter';
$_lang['DM_select_range'] = 'Voltar a scolher uma gama de IDs de Documentos';
$_lang['DM_select_range_text'] = '<p><strong>Legenda (onde n &eacute; o n&uacute;mero de ID de um documento):</strong><br /><br />
							  n* - Mudar op&ccedil;&otilde;es para este documento e para os seus descendentes directos<br /> 
							  n** - Mudar op&ccedil;&otilde;es para este documento e para TODOS os seus descendentes<br /> 
							  n-n2 - Mudar op&ccedil;&otilde;es para esta gama de documentos<br /> 
							  n - Mudar op&ccedil;&otilde;es para um &uacute;nico documento</p> 
							  <p>Exemplo: 1*,4**,2-20,25 - Isto ir&aacute; mudar as op&ccedil;&otilde;es escolhidas
						      para o documento 1 e descendentes directos, documento 4 e todos os seus descendentes, documentos
									2 at&eacute; 20 e para o documento 25.</p>';
$_lang['DM_select_tree'] ='Ver e seleccionar documentos usando a &Aacute;rvore de Documentos';

//-- process tree/range messages
$_lang['DM_process_noselection'] = 'N&atilde;o foi feita nenhuma selec&ccedil;&atilde;o. ';
$_lang['DM_process_novalues'] = 'N&atilde;o foram especificados nenhuns valores.';
$_lang['DM_process_limits_error'] = 'Limite superior menor que o limite inferior:';
$_lang['DM_process_invalid_error'] = 'Valor Inv&aacute;lido:';
$_lang['DM_process_update_success'] = 'Actualiza&ccedil;&atilde;o completa, sem erros a assinalar.';
$_lang['DM_process_update_error'] = 'Actualiza&ccedil;&atilde;o completa, mas foram encontrados erros:';
$_lang['DM_process_back'] = 'Voltar';

//-- manager access logging
$_lang['DM_log_template'] = 'Gestor de Documentos: Modelos (templates) alterados.';
$_lang['DM_log_templatevariables'] = 'Gestor de Documentos: Vari&aacute;veis de Template alteradas.';
$_lang['DM_log_docpermissions'] ='Gestor de Documentos: Permiss&otilde;es de Documentos alteradas.';
$_lang['DM_log_sortmenu']='Gestor de Documentos: Indexa&ccedil;&atilde;o de Menus completa.';
$_lang['DM_log_publish']='Gestor de Documentos: Op&ccedil;&otilde;es de Publicar/Retirar alteradas.';
$_lang['DM_log_hidemenu']='Gestor de Documentos: Op&ccedil;&otilde;es Mostrar/Ocultar alteradas.';
$_lang['DM_log_search']='Gestor de Documentos: Op&ccedil;&otilde;es Activar/Desactivar Procura alteradas.';
$_lang['DM_log_cache']='Gestor de Documentos: Op&ccedil;&otilde;es Usar/N&atilde;o usar Cache alteradas.';
$_lang['DM_log_richtext']='Gestor de Documentos: Op&ccedil;&otilde;es Usar/N&atilde;o usar Editor gr&aacute;fico de texto (tipo Word) alteradas.';
$_lang['DM_log_delete']='Gestor de Documentos: Op&ccedil;&otilde;es Apagar/Recuperar alteradas.';
$_lang['DM_log_dates']='Gestor de Documentos: Op&ccedil;&otilde;es de Datas alteradas.';
$_lang['DM_log_authors']='Gestor de Documentos: Op&ccedil;&otilde;es de Autores alteradas.';

?>
