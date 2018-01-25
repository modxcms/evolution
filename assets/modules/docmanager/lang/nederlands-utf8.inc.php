<?php
/**
 * Document Manager Module
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Garry Nutting
 * Language: Dutch
 * Date: 2014/02/24
 */
// titles
$_lang['DM_module_title'] = 'Document Manager';
$_lang['DM_action_title'] = 'Kies een handeling';
$_lang['DM_range_title'] = 'Geef een bereik van Document ID\'s aan';
$_lang['DM_tree_title'] = 'Kies documenten uit de boomstructuur';
$_lang['DM_update_title'] = 'Update voltooid';
$_lang['DM_sort_title'] = 'Menu-index Editor';

// tabs
$_lang['DM_doc_permissions'] = 'Document rechten';
$_lang['DM_template_variables'] = 'Template Variabelen';
$_lang['DM_sort_menu'] = 'Sorteer menu-items';
$_lang['DM_change_template'] = 'wijzig template';
$_lang['DM_publish'] = 'Publiceren/Niet publiceren';
$_lang['DM_other'] = 'Andere eigenschappen';

// buttons
$_lang['DM_close'] = 'Sluit Document Manager';
$_lang['DM_cancel'] = 'Terug';
$_lang['DM_go'] = 'Start';
$_lang['DM_save'] = 'Opslaan';
$_lang['DM_sort_another'] = 'Andere sorteren';

// templates tab
$_lang['DM_tpl_desc'] = 'Kies de gewenste template uit de tabel hieronder en geef vervolgens de document ID\'s aan die gewijzigd moeten worden. Dit kan door een bereik van ID\'s aan te geven of de structuur optie hieronder te gebruiken.';
$_lang['DM_tpl_no_templates'] = 'Geen templates gevonden';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Naam';
$_lang['DM_tpl_column_description'] = 'Omschrijving';
$_lang['DM_tpl_blank_template'] = 'Blanco template';
$_lang['DM_tpl_results_message'] = 'Gebruik de knop \'Terug]\ als u nog meer wilt wijzigen. De site cache is automatisch gewist.';

// template variables tab
$_lang['DM_tv_desc'] = 'Geef de  document ID\'s aan die gewijzigd dienen te worden. Dit kan door een bereik van ID\'s aan te geven of de structuur optie hieronder te gebruiken. Kies vervolgens de gewenste template uit de tabel en de geassocieerde Template Variabelen worden geladen. Kies de door u gewenste Template Variabele waarden en verzend voor verwerking.';
$_lang['DM_tv_template_mismatch'] = 'Dit document gebruikt de gekozen template niet.';
$_lang['DM_tv_doc_not_found'] = 'Dit document is niet in het bestand gevonden.';
$_lang['DM_tv_no_tv'] = 'Geen Template Variabelen gevonden voor de template.';
$_lang['DM_tv_no_docs'] = 'Geen documenten geselecteerd om bij te werken.';
$_lang['DM_tv_no_template_selected'] = 'Er is geen template geselecteerd.';
$_lang['DM_tv_loading'] = 'Template Variabele worden geladen ...';
$_lang['DM_tv_ignore_tv'] = 'Negeer deze Template Variabelen (door komma\'s gescheiden waarden):';
$_lang['DM_tv_ajax_insertbutton'] = 'Invoegen';

// document permissions tab
$_lang['DM_doc_desc'] = 'Kies de gewenste documentgroep uit de tabel hieronder en voeg of verwijder de groep naar wens. Specificeer vervolgens de document ID\'s die gewijzigd moeten worden. Dit kan door een bereik van ID\'s aan te geven of de structuur optie hieronder te gebruiken.';
$_lang['DM_doc_no_docs'] = 'Geen documentgroepen gevonden';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Naam';
$_lang['DM_doc_radio_add'] = 'Documentgroep toevoegen';
$_lang['DM_doc_radio_remove'] = 'Documentgroep verwijderen';

$_lang['DM_doc_skip_message1'] = 'Document met ID';
$_lang['DM_doc_skip_message2'] = 'is al onderdeel van de geselecteerde documentgroep (wordt overgeslagen)';

// sort menu tab
$_lang['DM_sort_pick_item'] = 'Klik a.u.b. op de site root of het ouder-document van de \'MAIN DOCUMENT\' structuur die u wilt sorteren.';
$_lang['DM_sort_updating'] = 'Bijwerken ...';
$_lang['DM_sort_updated'] = 'Bijgewerkt';
$_lang['DM_sort_nochildren'] = 'Ouder heeft geen kinderen';
$_lang['DM_sort_noid'] = 'Er is geen document geselecteerd. Ga a.u.b. terug en selecteer een document.';

// other tab
$_lang['DM_other_header'] = 'Diverse document instellingen';
$_lang['DM_misc_label'] = 'Beschikbare instellingen:';
$_lang['DM_misc_desc'] = 'Kies a.u.b. een instelling van het dropdown menu en dan de gewenste optie. NB: per keer kan slechts een instelling tegelijk gewijzigd worden.';

$_lang['DM_other_dropdown_publish'] = 'Publiceren/Niet publiceren';
$_lang['DM_other_dropdown_show'] = 'Toon/Verberg in menu';
$_lang['DM_other_dropdown_search'] = 'Doorzoekbaar/Niet doorzoekbaar';
$_lang['DM_other_dropdown_cache'] = 'Cachebaar/Niet cachebaar';
$_lang['DM_other_dropdown_richtext'] = 'Richtext/Geen richtext editor';
$_lang['DM_other_dropdown_delete'] = 'Verwijderen/Herstellen';

// radio button text
$_lang['DM_other_publish_radio1'] = 'Publiceren';
$_lang['DM_other_publish_radio2'] = 'Niet publiceren';
$_lang['DM_other_show_radio1'] = 'Verberg in menu';
$_lang['DM_other_show_radio2'] = 'Toon in menu';
$_lang['DM_other_search_radio1'] = 'Doorzoekbaar';
$_lang['DM_other_search_radio2'] = 'Niet doorzoekbaar';
$_lang['DM_other_cache_radio1'] = 'Cachebaar';
$_lang['DM_other_cache_radio2'] = 'Niet cachebaar';
$_lang['DM_other_richtext_radio1'] = 'Richtext';
$_lang['DM_other_richtext_radio2'] = 'Geen Richtext';
$_lang['DM_other_delete_radio1'] = 'Verwijderen';
$_lang['DM_other_delete_radio2'] = 'Herstellen';

// adjust dates
$_lang['DM_adjust_dates_header'] = 'Document datums instellen';
$_lang['DM_adjust_dates_desc'] = 'Elke van de volgende document datuminstellingen kan gewijzigd worden. Gebruik de \'Toon kalender\' optie om de datums in te stellen.';
$_lang['DM_view_calendar'] = 'Toon kalender';
$_lang['DM_clear_date'] = 'Wis datum';

// adjust authors
$_lang['DM_adjust_authors_header'] = 'Auteurs instellen';
$_lang['DM_adjust_authors_desc'] = 'Gebruik de dropdown lijsten om nieuwe auteurs voor het document in te stellen.';
$_lang['DM_adjust_authors_createdby'] = 'Gemaakt door:';
$_lang['DM_adjust_authors_editedby'] = 'Gewijzigd door:';
$_lang['DM_adjust_authors_noselection'] = 'Ongewijzigd';

// labels
$_lang['DM_date_pubdate'] = 'Datum publiceren:';
$_lang['DM_date_unpubdate'] = 'Datum niet publiceren:';
$_lang['DM_date_createdon'] = 'Datum gemaakt:';
$_lang['DM_date_editedon'] = 'Datum gewijzigd:';
$_lang['DM_date_notset'] = ' (niet ingesteld)';
$_lang['DM_date_dateselect_label'] = 'Kies een datum: ';

// document select section
$_lang['DM_select_submit'] = 'Verzenden';
$_lang['DM_select_range'] = 'Ga terug om een bereik van document ID\'s aan te geven';
$_lang['DM_select_range_text'] = '<p><strong>Toets (waarbij n een document ID nummer is):</strong><br /><br />
							  n* - Wijzig de instelling voor dit document en direkte kinderen<br /> 
							  n** - Wijzig de instelling voor dit document en ALLE kinderen<br /> 
							  n-n2 - Wijzig de instelling voor dit bereik van documenten<br /> 
							  n - Wijzig de instelling voor een enkel document</p> 
							  <p>Voorbeeld: 1*,4**,2-20,25 - Dit zal de geselecteerde instelling wijzigen
						      voor documenten 1 en direkte kinderen, document 4 en alle kinderen, documenten
						      2 t/m 20 en document 25.</p>';
$_lang['DM_select_tree'] = 'Bekijk en selecteer documenten door de structuur te gebruiken';

// process tree/range messages
$_lang['DM_process_noselection'] = 'Er is geen selectie gemaakt. ';
$_lang['DM_process_novalues'] = 'Er zijn geen waardes aangegeven.';
$_lang['DM_process_limits_error'] = 'Hoogste waarde lager dan laagste waarde:';
$_lang['DM_process_invalid_error'] = 'Ongeldige waarde:';
$_lang['DM_process_update_success'] = 'Bijwerken succesvol voltooid, zonder fouten.';
$_lang['DM_process_update_error'] = 'Bijwerken voltooid, maar met de volgende fouten:';
$_lang['DM_process_back'] = 'Terug';

// manager access logging
$_lang['DM_log_template'] = 'Document Manager: Templates gewijzigd.';
$_lang['DM_log_templatevariables'] = 'Document Manager: Template Variabelen gewijzigd.';
$_lang['DM_log_docpermissions'] = 'Document Manager: Document rechten gewijzigd.';
$_lang['DM_log_sortmenu'] = 'Document Manager: Menu-index bewerking voltooid.';
$_lang['DM_log_publish'] = 'Document Manager: Document Manager: Documentinstellingen Publiceren/Niet publiceren gewijzigd.';
$_lang['DM_log_hidemenu'] = 'Document Manager: Documentinstellingen Tonen/Vverbergen gewijzigd.';
$_lang['DM_log_search'] = 'Document Manager: Documentinstellingen Doorzoekbaar/Niet doorzoekbaar gewijzigd.';
$_lang['DM_log_cache'] = 'Document Manager: Documentinstellingen Cachebaar/Niet cachebaar gewijzigd.';
$_lang['DM_log_richtext'] = 'Document Manager: Documents Use Richtext Editor settings changed.';
$_lang['DM_log_delete'] = 'Document Manager: Documentinstellingen Verwijderen/Herstellen gewijzigd.';
$_lang['DM_log_dates'] = 'Document Manager: Documentinstellingen Datum gewijzigd.';
$_lang['DM_log_authors'] = 'Document Manager: Documentinstellingen Auteur gewijzigd.';
?>
