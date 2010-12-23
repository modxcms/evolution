<?php
/**
 * Document Manager Module - english.inc.php
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Garry Nutting
 * For: MODx CMS (www.modxcms.com)
 * Date:29/09/2006 Version: 1.6
 * 
 */
 
//-- ENGLISH LANGUAGE FILE
 
//-- titles
$_lang['DM_module_title'] = 'Dokument administration';
$_lang['DM_action_title'] = 'V&aelig;lg en handling';
$_lang['DM_range_title'] = 'Angiv en r&aelig;kke af dokument IDer';
$_lang['DM_tree_title'] = 'V&aelig;lg Dokumenter fra filtr&aelig;et';
$_lang['DM_update_title'] = 'Opdatering f&aelig;rdiggjort';
$_lang['DM_sort_title'] = 'Menu Indeks redigering';

//-- tabs
$_lang['DM_doc_permissions'] = 'Dokument tilladelser';
$_lang['DM_template_variables'] = 'Skabelon Variabler';
$_lang['DM_sort_menu'] = 'Sorter Menuelementer';
$_lang['DM_change_template'] = '&AElig;ndre skabelon';
$_lang['DM_publish'] = 'Publis&eacute;r/afpublis&eacute;r';
$_lang['DM_other'] = 'Andre egenskaber';
 
//-- buttons
$_lang['DM_close'] = 'Luk Dok administration';
$_lang['DM_cancel'] = 'G&aring; tilbage';
$_lang['DM_go'] = 'Forts&aelig;t';
$_lang['DM_save'] = 'Gem';
$_lang['DM_sort_another'] = 'Sort&eacute;r en anden';

//-- templates tab
$_lang['DM_tpl_desc'] = 'V&aelig;lg den skabelon du har brug for i tabellen nedenunder og angiv dokument IDerne som skal &aelig;ndres. Enten ved at angive en r&aelig;kke af IDer eller ved at bruge Tr&aelig; muligheden nedenunder.';
$_lang['DM_tpl_no_templates'] = 'Ingen skabeloner fundet';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Navn';
$_lang['DM_tpl_column_description'] ='Beskrivelse';
$_lang['DM_tpl_blank_template'] = 'Tom skabelon';

$_lang['DM_tpl_results_message']= 'Brug tilbage knappen hvis du har behov for at &aelig;ndre flere ting. Website Cachen er automatisk blevet t&oslash;mt.';

//-- template variables tab
$_lang['DM_tv_desc'] = 'Angiv dokument IDerne som skal &aelig;ndres, enten ved at angive en r&aelig;kke af IDer eller ved at bruge Tr&aelig;struktur muligheden nedenfor, udv&aelig;lg derefter den skabelon du har behov for fra tabellen og den associerede skabelon variabel vil blive indl&aelig;st. Indtast din &oslash;nskede skabelon variabel v&aelig;rdier og tryk send for at udf&oslash;re.';
$_lang['DM_tv_template_mismatch'] = 'Dette dokument bruger ikke den valgte skabelon.';
$_lang['DM_tv_doc_not_found'] = 'Dette dokument blev ikke fundet i databasen.';
$_lang['DM_tv_no_tv'] = 'Ingen skabelon variabler fundet for denne skabelon.';
$_lang['DM_tv_no_docs'] = 'Ingen dokumenter valgt til ar blive opdateret.';
$_lang['DM_tv_no_template_selected'] = 'Ingen skabeloner er blevet valgt.';
$_lang['DM_tv_loading'] = 'Skabelon variabler indl&aelig;ses ...';
$_lang['DM_tv_ignore_tv'] = 'Ignorerer disse skabelon variabler (komma-separeret v&aelig;rdier):';
$_lang['DM_tv_ajax_insertbutton'] = 'Inds&aelig;t';

//-- document permissions tab
$_lang['DM_doc_desc'] = 'V&aelig;lg den &oslash;nskede dokument gruppe fra tabellen nedenfor og enten om du &oslash;nsker at tilf&oslash;je eller fjerne en gruppe. S&aring; angiv dokument IDet som skal &aelig;ndres. Enten ved at angive en r&aelig;kke af IDer eller ved at bruge filtr&aelig; muligheden nedenfor.';
$_lang['DM_doc_no_docs'] = 'Ingen Dokument Grupper Fundet';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Navn';
$_lang['DM_doc_radio_add'] = 'Tilf&oslash;j en Dokument Gruppe';
$_lang['DM_doc_radio_remove'] = 'Fjern en Dokument Gruppe';

$_lang['DM_doc_skip_message1'] = 'Dokument med ID';
$_lang['DM_doc_skip_message2'] = 'er allerede en del af den valgte dokument gruppe (Springer over)';

//-- sort menu tab
$_lang['DM_sort_pick_item'] = 'Klik venligst websitets rod eller ejer dokumentet fra hoved dokument Tr&aelig;et som du &oslash;nsker at sorterer.'; 
$_lang['DM_sort_updating'] = 'Opdaterer ...';
$_lang['DM_sort_updated'] = 'Opdateret';
$_lang['DM_sort_nochildren'] = 'Ejer har ingen underdokumenter';
$_lang['DM_sort_noid']='Inget dokument er blevet valgt. G&aring; tilbage og v&aelig;lg et dokument.';

//-- other tab
$_lang['DM_other_header'] = 'Diverse Dokument indstillinger';
$_lang['DM_misc_label'] = 'Tilg&aelig;ngelige indstillinger:';
$_lang['DM_misc_desc'] = 'Udv&aelig;lg en indstilling fra dropdown menuen og derefter det &oslash;nskede valg. V&aelig;r opm&aelig;rksom p&aring; at kun en indstilling kan blive &aelig;ndret per gang.';

$_lang['DM_other_dropdown_publish'] = 'Publiser/afpubliser';
$_lang['DM_other_dropdown_show'] = 'Vis/Skjul i Menuen';
$_lang['DM_other_dropdown_search'] = 'S&oslash;gbar/ikke-s&oslash;gbar';
$_lang['DM_other_dropdown_cache'] = 'Cacherbar/ikke-cacherbar';
$_lang['DM_other_dropdown_richtext'] = 'Richtext/Ingen Richtext Editor';
$_lang['DM_other_dropdown_delete'] = 'Slet/fortryd slet';

//-- radio button text
$_lang['DM_other_publish_radio1'] = 'Publis&eacute;r'; 
$_lang['DM_other_publish_radio2'] = 'Afpublis&eacute;r';
$_lang['DM_other_show_radio1'] = 'Skjul i Menu'; 
$_lang['DM_other_show_radio2'] = 'Vis i Menu';
$_lang['DM_other_search_radio1'] = 'S&oslash;gbar'; 
$_lang['DM_other_search_radio2'] = 'Ikke s&oslash;gbar';
$_lang['DM_other_cache_radio1'] = 'Cacherbar'; 
$_lang['DM_other_cache_radio2'] = 'ikke-cacherbar';
$_lang['DM_other_richtext_radio1'] = 'Richtext'; 
$_lang['DM_other_richtext_radio2'] = 'Ingen Richtext';
$_lang['DM_other_delete_radio1'] = 'Slet'; 
$_lang['DM_other_delete_radio2'] = 'Fortryd slet';

//-- adjust dates 
$_lang['DM_adjust_dates_header'] = 'S&aelig;t Dokument Datoer';
$_lang['DM_adjust_dates_desc'] = 'Enhver af de f&oslash;lgende dokument datoindstillinger kan &aelig;ndres. Brug "Vis kalender" valgmuligheden til at s&aelig;tte datoer.';
$_lang['DM_view_calendar'] = 'Vis kalender';
$_lang['DM_clear_date'] = 'Slet dato';

//-- adjust authors
$_lang['DM_adjust_authors_header'] = 'S&aelig;t forfattere';
$_lang['DM_adjust_authors_desc'] = 'Brug dropdown listen for at tilf&oslash;je nye forfattere for dokumentet.';
$_lang['DM_adjust_authors_createdby'] = 'Oprettet af:';
$_lang['DM_adjust_authors_editedby'] = 'Redigeret af:';
$_lang['DM_adjust_authors_noselection'] = 'Ingen &aelig;ndring';

 //-- labels
$_lang['DM_date_pubdate'] = 'Publiseringsdato:';
$_lang['DM_date_unpubdate'] = 'Afpubliseringsdato:';
$_lang['DM_date_createdon'] = 'Oprettet p&aring; datoen:';
$_lang['DM_date_editedon'] = 'Redigeret p&aring; datoen:';
//$_lang['DM_date_deletedon'] = 'Deleted On dato';

$_lang['DM_date_notset'] = ' (Ikke sat)';
//deprecated
$_lang['DM_date_dateselect_label'] = 'V&aelig;lg en dato: ';

//-- document select section
$_lang['DM_select_submit'] = 'Send';
$_lang['DM_select_range'] = 'Skift tilbage til indstilling af document ID r&aelig;kker';
$_lang['DM_select_range_text'] = '<p><strong>N&oslash;gle (hvor n er et dokument ID nummer):</strong><br /><br />
							  n* - &AElig;ndre indstilling for dette dokument og det umiddelbare underdokumenter<br /> 
							  n** - &AElig;ndre indstilling for dette dokument og ALLE underdokumenter<br /> 
							  n-n2 - &AElig;ndre indstilling for denne r&aelig;kke af dokumenter<br /> 
							  n - &AElig;ndre indstilling for et enkelt dokument</p> 
							  <p>Eksempel: 1*,4**,2-20,25 - Dette vil &aelig;ndre den valgte indstilling
						      for dokumenter 1 og dets underdokumenter, dokument 4 og alle underdokumenter, dokumenterne 2 
						      til 20 og dokument 25.</p>';
$_lang['DM_select_tree'] ='Vis og v&aelig;lg dokumenter ved hj&aelig;lp af Dokument Tr&aelig;et';

//-- process tree/range messages
$_lang['DM_process_noselection'] = 'Ingen valg er blevet foretaget. ';
$_lang['DM_process_novalues'] = 'Ingen v&aelig;rdier er blevet angivet.';
$_lang['DM_process_limits_error'] = '&Oslash;verste gr&aelig;nse mindre end mindste gr&aelig;nse:';
$_lang['DM_process_invalid_error'] = 'Ulovlig V&aelig;rdi:';
$_lang['DM_process_update_success'] = 'Opdatering blev f&aelig;rdiggjort succesfuldt, uden fejl.';
$_lang['DM_process_update_error'] = 'Opdatering has completed but encountered errors:';
$_lang['DM_process_back'] = 'tilbage';

//-- manager access logging
$_lang['DM_log_template'] = 'Dokument administration: Skabloner &aelig;ndret.';
$_lang['DM_log_templatevariables'] = 'Dokument administration: Skabelon variabler &aelig;ndret.';
$_lang['DM_log_docpermissions'] ='Dokument administration: Dokumentets tilladelser &aelig;ndret.';
$_lang['DM_log_sortmenu']='Dokument administration: Menu Index operation f&aelig;rdiggjort.';
$_lang['DM_log_publish']='Dokument administration: Dokument administration: Dokumentets Publiseret/afpubliseret indstillinger &aelig;ndret.';
$_lang['DM_log_hidemenu']='Dokument administration: Dokumentets Skjul/vis i Menu indstillinger &aelig;ndret.';
$_lang['DM_log_search']='Dokument administration: Dokumentets S&oslash;gbar/ikke-s&oslash;gbar indstillinger &aelig;ndret.';
$_lang['DM_log_cache']='Dokument administration: Dokumentets Cacherbar/ikke cacherbar indstillinger &aelig;ndret.';
$_lang['DM_log_richtext']='Dokument administration: Dokumentets brug Richtext Editor indstillinger &aelig;ndret.';
$_lang['DM_log_delete']='Dokument administration: Dokumentets Slet/Slet-ikke indstillinger &aelig;ndret.';
$_lang['DM_log_dates']='Dokument administration: Dokumentets dato indstillinger &aelig;ndret.';
$_lang['DM_log_authors']='Dokument administration: Dokumentets forfatter indstillinger &aelig;ndret.';

?>
