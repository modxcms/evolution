<?php
/**
 * Document Manager Module - english.inc.php
 *
 * Purpose: Contains the language strings for use in the module.
 * Author: Garry Nutting
 * For: MODx CMS (www.modxcms.com)
 * Date:29/09/2006 Version: 1.6
 * Translation: Nicola Lambathakis (banzai)   
 */

//-- ITALIAN LANGUAGE FILE

//-- titles
$_lang['DM_module_title'] = 'Gestione Documenti';
$_lang['DM_action_title'] = ' Selezionare un\'azione';
$_lang['DM_range_title'] = 'Specificare gli id dei documenti';
$_lang['DM_tree_title'] = 'Seleziona i Documenti dall\'albero';
$_lang['DM_update_title'] = 'Aggiornamento Completato';
$_lang['DM_sort_title'] = 'Modifica indice del menu';

//-- tabs
$_lang['DM_doc_permissions'] = 'Permessi del documento';
$_lang['DM_template_variables'] = 'Template Variabili';
$_lang['DM_sort_menu'] = 'Ordina voci di Menu';
$_lang['DM_change_template'] = 'Cambia Template';
$_lang['DM_publish'] = 'Pubblica/Ritira';
$_lang['DM_other'] = 'Altre propriet&#224;';

//-- buttons
$_lang['DM_close'] = 'Chiudi Gestione Documenti';
$_lang['DM_cancel'] = 'indietro';
$_lang['DM_go'] = 'Vai';
$_lang['DM_save'] = 'Salva';
$_lang['DM_sort_another'] = 'Ordina un altro';

//-- templates tab
$_lang['DM_tpl_desc'] = 'Scegliere il template dalla tabella qui sotto e quindi specificare gli id dei documenti che devono essere cambiati. Specificando una gamma di id o usando l\'opzione dell\'albero qui sotto.';
$_lang['DM_tpl_no_templates'] = 'Nessun template trovato';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Nome';
$_lang['DM_tpl_column_description'] ='Descrizione';
$_lang['DM_tpl_blank_template'] = 'Template vuoto';

$_lang['DM_tpl_results_message']= 'Utilizzare il tasto indietro se avete bisogno di fare pi&#249; cambiamenti. La cache del sito &#232; stata pulita automaticamente.';

//-- template variables tab
$_lang['DM_tv_desc'] = 'Scegliere il template dalla tabella qui sotto e quindi specificare gli id dei documenti che devono essere cambiati. Specificando una gamma di id o usando l\'opzione dell\'albero qui sotto, quindi scegli il template dalla tabella e la template veriabile associata da caricare. Inserisci il valore desiderato per la  Template Variable e clicca su invia.';
$_lang['DM_tv_template_mismatch'] = 'Questo documento non usa il template selezionato.';
$_lang['DM_tv_doc_not_found'] = 'Questo documento non &#232; stato trovato nel database.';
$_lang['DM_tv_no_tv'] = 'Nessuna template variabile trovata per questo template.';
$_lang['DM_tv_no_docs'] = 'Nessun documento selezionato per l\'aggiornamento.';
$_lang['DM_tv_no_template_selected'] = 'Nessun template &#232; stato selezionato.';
$_lang['DM_tv_loading'] = 'Caricamento template variabili ...';
$_lang['DM_tv_ignore_tv'] = 'Ignora queste template variabili (valori separati da virgola):';
$_lang['DM_tv_ajax_insertbutton'] = 'Inserisci';

//-- document permissions tab
$_lang['DM_doc_desc'] = ' Scegliere il gruppo di documenti dalla tabella qui sotto e se desiderate aggiungere o rimuovere il gruppo. Quindi specificare gli id dei documenti che devono essere cambiati. Specificando una gamma di id o usando l\'opzione dell\'albero qui sotto.';
$_lang['DM_doc_no_docs'] = 'Nessun gruppo di documenti trovato';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Nome';
$_lang['DM_doc_radio_add'] = 'Aggiungi un gruppo di documenti';
$_lang['DM_doc_radio_remove'] = 'Rimuovi un gruppo di documenti';

$_lang['DM_doc_skip_message1'] = 'Documento con ID';
$_lang['DM_doc_skip_message2'] = '&#232; gia parte del gruppo di documenti selezionato (saltare)';

//-- sort menu tab
$_lang['DM_sort_pick_item'] = 'Seleziona nell\'albero dei documenti la root del sito o il documento genitore del documento che vorresti ordinare.';
$_lang['DM_sort_updating'] = 'Aggiornamento ...';
$_lang['DM_sort_updated'] = 'Aggiornato';
$_lang['DM_sort_nochildren'] = 'Il documento genitore non ha sotto documenti';
$_lang['DM_sort_noid']=' Nessun documento &#232; stato selezionato. Tornare indietro e selezionare un documento';

//-- other tab
$_lang['DM_other_header'] = ' Impostazioni varie del documento';
$_lang['DM_misc_label'] = 'Settaggi disponibili:';
$_lang['DM_misc_desc'] = ' Selezionare un\' impostazione dal menu a discesa e la relativa opzione. Attenzione può essere cambiata solo una impostazione alla volta.';

$_lang['DM_other_dropdown_publish'] = 'Pubblica/Ritira';
$_lang['DM_other_dropdown_show'] = 'Mostra/Nascondi nel Menu';
$_lang['DM_other_dropdown_search'] = 'Ricercabile/Non ricercabile';
$_lang['DM_other_dropdown_cache'] = 'Situabile in cache/Non in cache';
$_lang['DM_other_dropdown_richtext'] = 'Richtext/No Richtext Editor';
$_lang['DM_other_dropdown_delete'] = 'Elimina/Ripristina';

//-- radio button text
$_lang['DM_other_publish_radio1'] = 'Pubblica';
$_lang['DM_other_publish_radio2'] = 'Ritira';
$_lang['DM_other_show_radio1'] = 'Nascondi in Menu';
$_lang['DM_other_show_radio2'] = 'Mostra in Menu';
$_lang['DM_other_search_radio1'] = 'Ricercabile';
$_lang['DM_other_search_radio2'] = 'Non ricercabile';
$_lang['DM_other_cache_radio1'] = 'Situabile in cache';
$_lang['DM_other_cache_radio2'] = 'Non in cache';
$_lang['DM_other_richtext_radio1'] = 'Richtext';
$_lang['DM_other_richtext_radio2'] = 'No Richtext';
$_lang['DM_other_delete_radio1'] = 'Elimina';
$_lang['DM_other_delete_radio2'] = 'Ripristina';

//-- adjust dates
$_lang['DM_adjust_dates_header'] = 'Imposta la data del documento';
$_lang['DM_adjust_dates_desc'] = ' Ognuna delle seguenti regolazioni della data del documento puo\' essere cambiata. Usa l\'opzione Vedi Calendario per impostare la data.';
$_lang['DM_view_calendar'] = 'Vedi Calendario';
$_lang['DM_clear_date'] = 'Pulisci data';

//-- adjust authors
$_lang['DM_adjust_authors_header'] = 'Imposta autori';
$_lang['DM_adjust_authors_desc'] = 'Usare gli elenchi a discesa per selezionare i nuovi autori per il documento.';
$_lang['DM_adjust_authors_createdby'] = 'Creato da:';
$_lang['DM_adjust_authors_editedby'] = 'Modificato da:';
$_lang['DM_adjust_authors_noselection'] = 'Nessun cambiamento';

 //-- labels
$_lang['DM_date_pubdate'] = 'Data di pubblicazione:';
$_lang['DM_date_unpubdate'] = 'Data di ritiro:';
$_lang['DM_date_createdon'] = 'Data di creazione:';
$_lang['DM_date_editedon'] = 'Data di modifica:';
//$_lang['DM_date_deletedon'] = 'Data di eliminazione';

$_lang['DM_date_notset'] = ' (non impostato)';
//deprecated
$_lang['DM_date_dateselect_label'] = 'Seleziona una data: ';

//-- document select section
$_lang['DM_select_submit'] = 'Invia';
$_lang['DM_select_range'] = 'Torna indietro a impostare con una gamma di id del documento';
$_lang['DM_select_range_text'] = '<p><strong>Chiave (dove n numero di id del documento):</strong><br /><br />
							  n* - Cambia le impostazioni per questo documento e il primo livello di sotto documenti<br />
							  n** - Cambia le impostazioni per questo documento e TUTTI i sotto documenti<br />
							  n-n2 - Cambia le impostazioni per questa gamma di documenti<br />
							  n - Cambia le impostazioni per un solo documento</p>
							  <p>Esempio: 1*,4**,2-20,25 - Cambia le impostazioni per il documento 1 e i sotto documenti, il documento 4 e tutti i sotto documenti, i documenti da 2
						      a 20 il documento 25.</p>';
$_lang['DM_select_tree'] =' Visualizza e seleziona i documenti usando l\'albero dei documenti';

//-- process tree/range messages
$_lang['DM_process_noselection'] = 'Non &#232; stata effettuata nessuna selezione. ';
$_lang['DM_process_novalues'] = 'Nessun valore &#232; stato specicato.';
$_lang['DM_process_limits_error'] = 'Limite superiore pi&#249; basso del limite inferiore:';
$_lang['DM_process_invalid_error'] = 'Valore non valido:';
$_lang['DM_process_update_success'] = 'L\'aggiornamento &#232; stato completato con successo, senza errori.';
$_lang['DM_process_update_error'] = 'L\'aggiornamento &#232; stato completato, ma con errori.:';
$_lang['DM_process_back'] = 'Back';

//-- manager access logging
$_lang['DM_log_template'] = 'Gestione Documenti: Template sostituiti.';
$_lang['DM_log_templatevariables'] = 'Gestione Documenti: Template variabili modificate.';
$_lang['DM_log_docpermissions'] ='Gestione Documenti: Permessi dei documenti cambiati.';
$_lang['DM_log_sortmenu']='Gestione Documenti: Modifica indice del menu completata.';
$_lang['DM_log_publish']='Gestione Documenti: Impostazioni documenti Pubblicato/Ritirato modificate.';
$_lang['DM_log_hidemenu']='Gestione Documenti: Impostazioni documenti Mostra/Nascondi nel Menu  modificate.';
$_lang['DM_log_search']='Gestione Documenti: Impostazioni documenti Ricercabile/Non ricercabile modificate.';
$_lang['DM_log_cache']='Gestione Documenti: Impostazioni documenti Documents Situabile in cache/Non in cache modificate.';
$_lang['DM_log_richtext']='Gestione Documenti: Impostazioni documenti Usa Richtext Editor modificate.';
$_lang['DM_log_delete']='Gestione Documenti: Impostazioni documenti Cancella/Ripristina modificate.';
$_lang['DM_log_dates']='Gestione Documenti: Impostazioni Data dei documenti modificate.';
$_lang['DM_log_authors']='Gestione Documenti: Impostazioni Autore dei documenti modificate.';

?>
