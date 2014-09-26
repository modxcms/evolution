<?php
/**
 * Document Manager Module
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Pontus Ågren (Pont)
 * Language: Swedish
 * Date: 2014/02/24
 */
// titles
$_lang['DM_module_title'] = 'Dokumenthanterare';
$_lang['DM_action_title'] = 'Välj en åtgärd';
$_lang['DM_range_title'] = 'Ange ett intervall av dokument-IDn';
$_lang['DM_tree_title'] = 'Välj dokument från dokumentträdet';
$_lang['DM_update_title'] = 'Uppdateringen är klar';
$_lang['DM_sort_title'] = 'Redigerare för menyindex';

// tabs
$_lang['DM_doc_permissions'] = 'Dokumenträttigheter';
$_lang['DM_template_variables'] = 'Mallvariabler';
$_lang['DM_sort_menu'] = 'Sortera menyposter';
$_lang['DM_change_template'] = 'Ändra mall';
$_lang['DM_publish'] = 'Publicera/Avpublicera';
$_lang['DM_other'] = 'Andra egenskaper';

// buttons
$_lang['DM_close'] = 'Stäng dokumenthanteraren';
$_lang['DM_cancel'] = 'Gå tillbaka';
$_lang['DM_go'] = 'Utför';
$_lang['DM_save'] = 'Spara';
$_lang['DM_sort_another'] = 'Sortera en annan';

// templates tab
$_lang['DM_tpl_desc'] = 'Välj den avsedda mallen i nedanstående tabell och ange sedan IDn på de dokument som ska ändras. Ange ett intervall av IDn eller använd trädfunktionen nedan.';
$_lang['DM_tpl_no_templates'] = 'Inga mallar hittades';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Namn';
$_lang['DM_tpl_column_description'] = 'Beskrivning';
$_lang['DM_tpl_blank_template'] = 'Tom mall';
$_lang['DM_tpl_results_message'] = 'Använd Tillbaka-knappen om du behöver göra fler ändringar. Webbplatsens cache har rensats automatiskt.';

// template variables tab
$_lang['DM_tv_desc'] = 'Specificera IDn på de dokument som ska ändras genom att ange ett intervall av IDn eller genom att använda trädfunktionen nedan. Välj sedan den önskade mallen i tabellen så laddas de tillhörande mallvariablerna. Ändra därefter de värden på mallvariablerna som önskas och klicka på Skicka för att utföra ändringarna.';
$_lang['DM_tv_template_mismatch'] = 'Detta dokument använder inte den valda mallen.';
$_lang['DM_tv_doc_not_found'] = 'Dokumentet finns inte i databasen.';
$_lang['DM_tv_no_tv'] = 'Inga mallvariabler kunde hittas för mallen.';
$_lang['DM_tv_no_docs'] = 'Inga dokument har valts för uppdatering.';
$_lang['DM_tv_no_template_selected'] = 'Ingen mall har valts.';
$_lang['DM_tv_loading'] = 'Mallvariablerna laddas...';
$_lang['DM_tv_ignore_tv'] = 'Ignorera dessa mallvariabler (separera värden med kommatecken):';
$_lang['DM_tv_ajax_insertbutton'] = 'Infoga';

// document permissions tab
$_lang['DM_doc_desc'] = 'Markera den avsedda dokumentgruppen i tabellen nedan och välj om du vill lägga till eller ta bort den. Specificera sedan de dokument som ska ändras. Det görs antingen genom att specificera IDn i ett intervall eller genom att använda trädfunktionen nedan.';
$_lang['DM_doc_no_docs'] = 'Inga dokumentgrupper hittades';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Namn';
$_lang['DM_doc_radio_add'] = 'Lägg till en dokumentgrupp';
$_lang['DM_doc_radio_remove'] = 'Ta bort en dokumentgrupp';

$_lang['DM_doc_skip_message1'] = 'Dokument med ID';
$_lang['DM_doc_skip_message2'] = 'är redan en del av den valda dokumentgruppen (hoppar över)';

// sort menu tab
$_lang['DM_sort_pick_item'] = 'Klicka på webbplatsens rotdokument eller det föräldradokument som du vill sortera i dokumentträdet till vänster.';
$_lang['DM_sort_updating'] = 'Uppdaterar...';
$_lang['DM_sort_updated'] = 'Uppdaterad';
$_lang['DM_sort_nochildren'] = 'Föräldern har inga barn';
$_lang['DM_sort_noid'] = 'Inga dokument har markerats. Gå tillbaka och välj ett dokument.';

// other tab
$_lang['DM_other_header'] = 'Övriga dokumentinställningar';
$_lang['DM_misc_label'] = 'Tillgängliga inställningar:';
$_lang['DM_misc_desc'] = 'Välj en inställning från rullgardinsmenyn och sedan den förändring som önskas. Notera att det bara går att ändra en inställning i taget.';

$_lang['DM_other_dropdown_publish'] = 'Publicera/Avpublicera';
$_lang['DM_other_dropdown_show'] = 'Visa/Dölj i menyn';
$_lang['DM_other_dropdown_search'] = 'Sökbar/Ej sökbar';
$_lang['DM_other_dropdown_cache'] = 'Cachebar/Ej cachebar';
$_lang['DM_other_dropdown_richtext'] = 'Richtext-/Ej Richtexteditor';
$_lang['DM_other_dropdown_delete'] = 'Ta bort/Återställ';

// radio button text
$_lang['DM_other_publish_radio1'] = 'Publicera';
$_lang['DM_other_publish_radio2'] = 'Avpublicera';
$_lang['DM_other_show_radio1'] = 'Dölj i menyn';
$_lang['DM_other_show_radio2'] = 'Visa i menyn';
$_lang['DM_other_search_radio1'] = 'Sökbar';
$_lang['DM_other_search_radio2'] = 'Ej sökbar';
$_lang['DM_other_cache_radio1'] = 'Cachebar';
$_lang['DM_other_cache_radio2'] = 'Ej cachebar';
$_lang['DM_other_richtext_radio1'] = 'Richtext';
$_lang['DM_other_richtext_radio2'] = 'Ej Richtext';
$_lang['DM_other_delete_radio1'] = 'Ta bort';
$_lang['DM_other_delete_radio2'] = 'Återställ';

// adjust dates
$_lang['DM_adjust_dates_header'] = 'Ange dokumentdatum';
$_lang['DM_adjust_dates_desc'] = 'Alla de följande dokumentdatumen kan ändras. Använd "Visa kalender" för att ange datumen.';
$_lang['DM_view_calendar'] = 'Visa kalender';
$_lang['DM_clear_date'] = 'Radera datum';

// adjust authors
$_lang['DM_adjust_authors_header'] = 'Ange författare';
$_lang['DM_adjust_authors_desc'] = 'Använd rullgardinsmenyerna för att välja nya författare till dokumentet.';
$_lang['DM_adjust_authors_createdby'] = 'Skapad av:';
$_lang['DM_adjust_authors_editedby'] = 'Redigerad av:';
$_lang['DM_adjust_authors_noselection'] = 'Ingen ändring';

// labels
$_lang['DM_date_pubdate'] = 'Publiceringsdatum:';
$_lang['DM_date_unpubdate'] = 'Avpubliceringsdatum:';
$_lang['DM_date_createdon'] = 'Skapad:';
$_lang['DM_date_editedon'] = 'Redigerad:';
$_lang['DM_date_notset'] = ' (ej angivet)';
$_lang['DM_date_dateselect_label'] = 'Välj ett datum: ';

// document select section
$_lang['DM_select_submit'] = 'Utför';
$_lang['DM_select_range'] = 'Växla tillbaka till att specificera ett dokumentintervall';
$_lang['DM_select_range_text'] = '<p><strong>Nyckel (där n är ett dokumentID):</strong><br /><br />
                              n* - Ändra inställning på detta dokument och dess närmaste barn<br /> 
                              n** - Ändra inställning på detta dokument och ALLA dess barn<br /> 
                              n-n2 - Ändra inställning på detta intervall av dokument<br /> 
                              n - Ändra inställning på ett enstaka dokument</p> 
                              <p>Exempel: 1*, 4**, 2-20, 25 - Det här ändrar den valda inställningen
                              för dokument 1 och dess närmaste barn, dokument 4 och alla dess barn,
                              dokument 2-20 och dokument 25.</p>';
$_lang['DM_select_tree'] = 'Visa dokumentträdet och välj dokument';

// process tree/range messages
$_lang['DM_process_noselection'] = 'Inget val har gjorts. ';
$_lang['DM_process_novalues'] = 'Inga värden har angetts.';
$_lang['DM_process_limits_error'] = 'Övre gränsen lägre än den undre gränsen:';
$_lang['DM_process_invalid_error'] = 'Ogiltligt värde:';
$_lang['DM_process_update_success'] = 'Uppdateringen har genomförts utan några fel.';
$_lang['DM_process_update_error'] = 'Uppdateringen har genomförts, men det uppstog fel:';
$_lang['DM_process_back'] = 'Tillbaka';

// manager access logging
$_lang['DM_log_template'] = 'Dokumenthanterare: Mallar ändrade.';
$_lang['DM_log_templatevariables'] = 'Dokumenthanterare: Mallvariabler ändrade.';
$_lang['DM_log_docpermissions'] = 'Dokumenthanterare: Dokumenträttigheter ändrade.';
$_lang['DM_log_sortmenu'] = 'Dokumenthanterare: Menyindexoperationer klara.';
$_lang['DM_log_publish'] = 'Dokumenthanterare: Dokumentinställningar för publicering/avpublicering ändrade.';
$_lang['DM_log_hidemenu'] = 'Dokumenthanterare: Inställningar för visa/dölj i menyn ändrade.';
$_lang['DM_log_search'] = 'Dokumenthanterare: Inställningar för sökbarhet ändrade.';
$_lang['DM_log_cache'] = 'Dokumenthanterare: Inställningar för cachebarhet ändrade.';
$_lang['DM_log_richtext'] = 'Dokumenthanterare: Inställningar för användning av Richtexteditor ändrade.';
$_lang['DM_log_delete'] = 'Dokumenthanterare: Inställningar för ta bort/återställ ändrade.';
$_lang['DM_log_dates'] = 'Dokumenthanterare: Datuminställningar för dokument ändrade.';
$_lang['DM_log_authors'] = 'Dokumenthanterare: Författarinställningar för dokument ändrade.';
?>
