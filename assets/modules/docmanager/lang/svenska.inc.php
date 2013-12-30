<?php
/**
 * Document Manager Module - svenska.inc.php
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Garry Nutting
 * For: MODX CMS (www.modx.com)
 * Date:29/09/2006 Version: 1.6
 *
 * Translation: Pontus �gren (Pont)
 * Date: 2010-04-12
 * 
 */

//-- SWEDISH LANGUAGE FILE

//-- titles
$_lang['DM_module_title'] = 'Dokumenthanterare';
$_lang['DM_action_title'] = 'V�lj en �tg�rd';
$_lang['DM_range_title'] = 'Ange ett intervall av dokument-IDn';
$_lang['DM_tree_title'] = 'V�lj dokument fr�n dokumenttr�det';
$_lang['DM_update_title'] = 'Uppdateringen �r klar';
$_lang['DM_sort_title'] = 'Redigerare f�r menyindex';

//-- tabs
$_lang['DM_doc_permissions'] = 'Dokumentr�ttigheter';
$_lang['DM_template_variables'] = 'Mallvariabler';
$_lang['DM_sort_menu'] = 'Sortera menyposter';
$_lang['DM_change_template'] = '�ndra mall';
$_lang['DM_publish'] = 'Publicera/Avpublicera';
$_lang['DM_other'] = 'Andra egenskaper';
 
//-- buttons
$_lang['DM_close'] = 'St�ng dokumenthanteraren';
$_lang['DM_cancel'] = 'G� tillbaka';
$_lang['DM_go'] = 'Utf�r';
$_lang['DM_save'] = 'Spara';
$_lang['DM_sort_another'] = 'Sortera en annan';

//-- templates tab
$_lang['DM_tpl_desc'] = 'V�lj den avsedda mallen i nedanst�ende tabell och ange sedan IDn p� de dokument som ska �ndras. Ange ett intervall av IDn eller anv�nd tr�dfunktionen nedan.';
$_lang['DM_tpl_no_templates'] = 'Inga mallar hittades';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Namn';
$_lang['DM_tpl_column_description'] ='Beskrivning';
$_lang['DM_tpl_blank_template'] = 'Tom mall';

$_lang['DM_tpl_results_message'] = 'Anv�nd Tillbaka-knappen om du beh�ver g�ra fler �ndringar. Webbplatsens cache har rensats automatiskt.';

//-- template variables tab
$_lang['DM_tv_desc'] = 'Specificera IDn p� de dokument som ska �ndras genom att ange ett intervall av IDn eller genom att anv�nda tr�dfunktionen nedan. V�lj sedan den �nskade mallen i tabellen s� laddas de tillh�rande mallvariablerna. �ndra d�refter de v�rden p� mallvariablerna som �nskas och klicka p� Skicka f�r att utf�ra �ndringarna.';
$_lang['DM_tv_template_mismatch'] = 'Detta dokument anv�nder inte den valda mallen.';
$_lang['DM_tv_doc_not_found'] = 'Dokumentet finns inte i databasen.';
$_lang['DM_tv_no_tv'] = 'Inga mallvariabler kunde hittas f�r mallen.';
$_lang['DM_tv_no_docs'] = 'Inga dokument har valts f�r uppdatering.';
$_lang['DM_tv_no_template_selected'] = 'Ingen mall har valts.';
$_lang['DM_tv_loading'] = 'Mallvariablerna laddas...';
$_lang['DM_tv_ignore_tv'] = 'Ignorera dessa mallvariabler (separera v�rden med kommatecken):';
$_lang['DM_tv_ajax_insertbutton'] = 'Infoga';

//-- document permissions tab
$_lang['DM_doc_desc'] = 'Markera den avsedda dokumentgruppen i tabellen nedan och v�lj om du vill l�gga till eller ta bort den. Specificera sedan de dokument som ska �ndras. Det g�rs antingen genom att specificera IDn i ett intervall eller genom att anv�nda tr�dfunktionen nedan.';
$_lang['DM_doc_no_docs'] = 'Inga dokumentgrupper hittades';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Namn';
$_lang['DM_doc_radio_add'] = 'L�gg till en dokumentgrupp';
$_lang['DM_doc_radio_remove'] = 'Ta bort en dokumentgrupp';

$_lang['DM_doc_skip_message1'] = 'Dokument med ID';
$_lang['DM_doc_skip_message2'] = '�r redan en del av den valda dokumentgruppen (hoppar �ver)';

//-- sort menu tab
$_lang['DM_sort_pick_item'] = 'Klicka p� webbplatsens rotdokument eller det f�r�ldradokument som du vill sortera i dokumenttr�det till v�nster.'; 
$_lang['DM_sort_updating'] = 'Uppdaterar...';
$_lang['DM_sort_updated'] = 'Uppdaterad';
$_lang['DM_sort_nochildren'] = 'F�r�ldern har inga barn';
$_lang['DM_sort_noid']='Inga dokument har markerats. G� tillbaka och v�lj ett dokument.';

//-- other tab
$_lang['DM_other_header'] = '�vriga dokumentinst�llningar';
$_lang['DM_misc_label'] = 'Tillg�ngliga inst�llningar:';
$_lang['DM_misc_desc'] = 'V�lj en inst�llning fr�n rullgardinsmenyn och sedan den f�r�ndring som �nskas. Notera att det bara g�r att �ndra en inst�llning i taget.';

$_lang['DM_other_dropdown_publish'] = 'Publicera/Avpublicera';
$_lang['DM_other_dropdown_show'] = 'Visa/D�lj i menyn';
$_lang['DM_other_dropdown_search'] = 'S�kbar/Ej s�kbar';
$_lang['DM_other_dropdown_cache'] = 'Cachebar/Ej cachebar';
$_lang['DM_other_dropdown_richtext'] = 'Richtext-/Ej Richtexteditor';
$_lang['DM_other_dropdown_delete'] = 'Ta bort/�terst�ll';

//-- radio button text
$_lang['DM_other_publish_radio1'] = 'Publicera'; 
$_lang['DM_other_publish_radio2'] = 'Avpublicera';
$_lang['DM_other_show_radio1'] = 'D�lj i menyn'; 
$_lang['DM_other_show_radio2'] = 'Visa i menyn';
$_lang['DM_other_search_radio1'] = 'S�kbar'; 
$_lang['DM_other_search_radio2'] = 'Ej s�kbar';
$_lang['DM_other_cache_radio1'] = 'Cachebar'; 
$_lang['DM_other_cache_radio2'] = 'Ej cachebar';
$_lang['DM_other_richtext_radio1'] = 'Richtext'; 
$_lang['DM_other_richtext_radio2'] = 'Ej Richtext';
$_lang['DM_other_delete_radio1'] = 'Ta bort'; 
$_lang['DM_other_delete_radio2'] = '�terst�ll';

//-- adjust dates 
$_lang['DM_adjust_dates_header'] = 'Ange dokumentdatum';
$_lang['DM_adjust_dates_desc'] = 'Alla de f�ljande dokumentdatumen kan �ndras. Anv�nd "Visa kalender" f�r att ange datumen.';
$_lang['DM_view_calendar'] = 'Visa kalender';
$_lang['DM_clear_date'] = 'Radera datum';

//-- adjust authors
$_lang['DM_adjust_authors_header'] = 'Ange f�rfattare';
$_lang['DM_adjust_authors_desc'] = 'Anv�nd rullgardinsmenyerna f�r att v�lja nya f�rfattare till dokumentet.';
$_lang['DM_adjust_authors_createdby'] = 'Skapad av:';
$_lang['DM_adjust_authors_editedby'] = 'Redigerad av:';
$_lang['DM_adjust_authors_noselection'] = 'Ingen �ndring';

 //-- labels
$_lang['DM_date_pubdate'] = 'Publiceringsdatum:';
$_lang['DM_date_unpubdate'] = 'Avpubliceringsdatum:';
$_lang['DM_date_createdon'] = 'Skapad:';
$_lang['DM_date_editedon'] = 'Redigerad:';
//$_lang['DM_date_deletedon'] = 'Borttagen';

$_lang['DM_date_notset'] = ' (ej angivet)';
//deprecated
$_lang['DM_date_dateselect_label'] = 'V�lj ett datum: ';

//-- document select section
$_lang['DM_select_submit'] = 'Utf�r';
$_lang['DM_select_range'] = 'V�xla tillbaka till att specificera ett dokumentintervall';
$_lang['DM_select_range_text'] = '<p><strong>Nyckel (d�r n �r ett dokumentID):</strong><br /><br />
							  n* - �ndra inst�llning p� detta dokument och dess n�rmaste barn<br /> 
							  n** - �ndra inst�llning p� detta dokument och ALLA dess barn<br /> 
							  n-n2 - �ndra inst�llning p� detta intervall av dokument<br /> 
							  n - �ndra inst�llning p� ett enstaka dokument</p> 
							  <p>Exempel: 1*, 4**, 2-20, 25 - Det h�r �ndrar den valda inst�llningen
						      f�r dokument 1 och dess n�rmaste barn, dokument 4 och alla dess barn,
						      dokument 2-20 och dokument 25.</p>';
$_lang['DM_select_tree'] ='Visa dokumenttr�det och v�lj dokument';

//-- process tree/range messages
$_lang['DM_process_noselection'] = 'Inget val har gjorts. ';
$_lang['DM_process_novalues'] = 'Inga v�rden har angetts.';
$_lang['DM_process_limits_error'] = '�vre gr�nsen l�gre �n den undre gr�nsen:';
$_lang['DM_process_invalid_error'] = 'Ogiltligt v�rde:';
$_lang['DM_process_update_success'] = 'Uppdateringen har genomf�rts utan n�gra fel.';
$_lang['DM_process_update_error'] = 'Uppdateringen har genomf�rts, men det uppstog fel:';
$_lang['DM_process_back'] = 'Tillbaka';

//-- manager access logging
$_lang['DM_log_template'] = 'Dokumenthanterare: Mallar �ndrade.';
$_lang['DM_log_templatevariables'] = 'Dokumenthanterare: Mallvariabler �ndrade.';
$_lang['DM_log_docpermissions'] = 'Dokumenthanterare: Dokumentr�ttigheter �ndrade.';
$_lang['DM_log_sortmenu'] = 'Dokumenthanterare: Menyindexoperationer klara.';
$_lang['DM_log_publish'] = 'Dokumenthanterare: Dokumentinst�llningar f�r publicering/avpublicering �ndrade.';
$_lang['DM_log_hidemenu'] = 'Dokumenthanterare: Inst�llningar f�r visa/d�lj i menyn �ndrade.';
$_lang['DM_log_search'] = 'Dokumenthanterare: Inst�llningar f�r s�kbarhet �ndrade.';
$_lang['DM_log_cache'] = 'Dokumenthanterare: Inst�llningar f�r cachebarhet �ndrade.';
$_lang['DM_log_richtext'] = 'Dokumenthanterare: Inst�llningar f�r anv�ndning av Richtexteditor �ndrade.';
$_lang['DM_log_delete'] = 'Dokumenthanterare: Inst�llningar f�r ta bort/�terst�ll �ndrade.';
$_lang['DM_log_dates'] = 'Dokumenthanterare: Datuminst�llningar f�r dokument �ndrade.';
$_lang['DM_log_authors'] = 'Dokumenthanterare: F�rfattarinst�llningar f�r dokument �ndrade.';

?>