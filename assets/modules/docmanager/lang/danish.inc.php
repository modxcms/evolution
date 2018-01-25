<?php
/**
 * Document Manager Module
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Garry Nutting
 * Language: Danish
 * Date: 2014/02/24
 */
// titles
$_lang['DM_module_title'] = 'Dokument administration';
$_lang['DM_action_title'] = 'Vӕlg en handling';
$_lang['DM_range_title'] = 'Angiv en rӕkke af dokument IDer';
$_lang['DM_tree_title'] = 'Vӕlg Dokumenter fra filtrӕet';
$_lang['DM_update_title'] = 'Opdatering fӕrdiggjort';
$_lang['DM_sort_title'] = 'Menu Indeks redigering';

// tabs
$_lang['DM_doc_permissions'] = 'Dokument tilladelser';
$_lang['DM_template_variables'] = 'Skabelon Variabler';
$_lang['DM_sort_menu'] = 'Sorter Menuelementer';
$_lang['DM_change_template'] = 'Ӕndre skabelon';
$_lang['DM_publish'] = 'Publisér/afpublisér';
$_lang['DM_other'] = 'Andre egenskaber';

// buttons
$_lang['DM_close'] = 'Luk Dok administration';
$_lang['DM_cancel'] = 'Gǻ tilbage';
$_lang['DM_go'] = 'Fortsӕt';
$_lang['DM_save'] = 'Gem';
$_lang['DM_sort_another'] = 'Sortér en anden';

// templates tab
$_lang['DM_tpl_desc'] = 'Vӕlg den skabelon du har brug for i tabellen nedenunder og angiv dokument IDerne som skal ӕndres. Enten ved at angive en rӕkke af IDer eller ved at bruge Trӕ muligheden nedenunder.';
$_lang['DM_tpl_no_templates'] = 'Ingen skabeloner fundet';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Navn';
$_lang['DM_tpl_column_description'] = 'Beskrivelse';
$_lang['DM_tpl_blank_template'] = 'Tom skabelon';
$_lang['DM_tpl_results_message'] = 'Brug tilbage knappen hvis du har behov for at ӕndre flere ting. Website Cachen er automatisk blevet tømt.';

// template variables tab
$_lang['DM_tv_desc'] = 'Angiv dokument IDerne som skal ӕndres, enten ved at angive en rӕkke af IDer eller ved at bruge Trӕstruktur muligheden nedenfor, udvӕlg derefter den skabelon du har behov for fra tabellen og den associerede skabelon variabel vil blive indlӕst. Indtast din ønskede skabelon variabel vӕrdier og tryk send for at udføre.';
$_lang['DM_tv_template_mismatch'] = 'Dette dokument bruger ikke den valgte skabelon.';
$_lang['DM_tv_doc_not_found'] = 'Dette dokument blev ikke fundet i databasen.';
$_lang['DM_tv_no_tv'] = 'Ingen skabelon variabler fundet for denne skabelon.';
$_lang['DM_tv_no_docs'] = 'Ingen dokumenter valgt til ar blive opdateret.';
$_lang['DM_tv_no_template_selected'] = 'Ingen skabeloner er blevet valgt.';
$_lang['DM_tv_loading'] = 'Skabelon variabler indlӕses ...';
$_lang['DM_tv_ignore_tv'] = 'Ignorerer disse skabelon variabler (komma-separeret vӕrdier):';
$_lang['DM_tv_ajax_insertbutton'] = 'Indsӕt';

// document permissions tab
$_lang['DM_doc_desc'] = 'Vӕlg den ønskede dokument gruppe fra tabellen nedenfor og enten om du ønsker at tilføje eller fjerne en gruppe. Sǻ angiv dokument IDet som skal ӕndres. Enten ved at angive en rӕkke af IDer eller ved at bruge filtrӕ muligheden nedenfor.';
$_lang['DM_doc_no_docs'] = 'Ingen Dokument Grupper Fundet';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Navn';
$_lang['DM_doc_radio_add'] = 'Tilføj en Dokument Gruppe';
$_lang['DM_doc_radio_remove'] = 'Fjern en Dokument Gruppe';

$_lang['DM_doc_skip_message1'] = 'Dokument med ID';
$_lang['DM_doc_skip_message2'] = 'er allerede en del af den valgte dokument gruppe (Springer over)';

// sort menu tab
$_lang['DM_sort_pick_item'] = 'Klik venligst websitets rod eller ejer dokumentet fra hoved dokument Trӕet som du ønsker at sorterer.';
$_lang['DM_sort_updating'] = 'Opdaterer ...';
$_lang['DM_sort_updated'] = 'Opdateret';
$_lang['DM_sort_nochildren'] = 'Ejer har ingen underdokumenter';
$_lang['DM_sort_noid'] = 'Inget dokument er blevet valgt. Gǻ tilbage og vӕlg et dokument.';

// other tab
$_lang['DM_other_header'] = 'Diverse Dokument indstillinger';
$_lang['DM_misc_label'] = 'Tilgӕngelige indstillinger:';
$_lang['DM_misc_desc'] = 'Udvӕlg en indstilling fra dropdown menuen og derefter det ønskede valg. Vӕr opmӕrksom pǻ at kun en indstilling kan blive ӕndret per gang.';

$_lang['DM_other_dropdown_publish'] = 'Publiser/afpubliser';
$_lang['DM_other_dropdown_show'] = 'Vis/Skjul i Menuen';
$_lang['DM_other_dropdown_search'] = 'Søgbar/ikke-søgbar';
$_lang['DM_other_dropdown_cache'] = 'Cacherbar/ikke-cacherbar';
$_lang['DM_other_dropdown_richtext'] = 'Richtext/Ingen Richtext Editor';
$_lang['DM_other_dropdown_delete'] = 'Slet/fortryd slet';

// radio button text
$_lang['DM_other_publish_radio1'] = 'Publisér';
$_lang['DM_other_publish_radio2'] = 'Afpublisér';
$_lang['DM_other_show_radio1'] = 'Skjul i Menu';
$_lang['DM_other_show_radio2'] = 'Vis i Menu';
$_lang['DM_other_search_radio1'] = 'Søgbar';
$_lang['DM_other_search_radio2'] = 'Ikke søgbar';
$_lang['DM_other_cache_radio1'] = 'Cacherbar';
$_lang['DM_other_cache_radio2'] = 'ikke-cacherbar';
$_lang['DM_other_richtext_radio1'] = 'Richtext';
$_lang['DM_other_richtext_radio2'] = 'Ingen Richtext';
$_lang['DM_other_delete_radio1'] = 'Slet';
$_lang['DM_other_delete_radio2'] = 'Fortryd slet';

// adjust dates
$_lang['DM_adjust_dates_header'] = 'Sӕt Dokument Datoer';
$_lang['DM_adjust_dates_desc'] = 'Enhver af de følgende dokument datoindstillinger kan ӕndres. Brug "Vis kalender" valgmuligheden til at sӕtte datoer.';
$_lang['DM_view_calendar'] = 'Vis kalender';
$_lang['DM_clear_date'] = 'Slet dato';

// adjust authors
$_lang['DM_adjust_authors_header'] = 'Sӕt forfattere';
$_lang['DM_adjust_authors_desc'] = 'Brug dropdown listen for at tilføje nye forfattere for dokumentet.';
$_lang['DM_adjust_authors_createdby'] = 'Oprettet af:';
$_lang['DM_adjust_authors_editedby'] = 'Redigeret af:';
$_lang['DM_adjust_authors_noselection'] = 'Ingen ӕndring';

// labels
$_lang['DM_date_pubdate'] = 'Publiseringsdato:';
$_lang['DM_date_unpubdate'] = 'Afpubliseringsdato:';
$_lang['DM_date_createdon'] = 'Oprettet pǻ datoen:';
$_lang['DM_date_editedon'] = 'Redigeret pǻ datoen:';
$_lang['DM_date_notset'] = ' (Ikke sat)';
$_lang['DM_date_dateselect_label'] = 'Vӕlg en dato: ';

// document select section
$_lang['DM_select_submit'] = 'Send';
$_lang['DM_select_range'] = 'Skift tilbage til indstilling af document ID rӕkker';
$_lang['DM_select_range_text'] = '<p><strong>Nøgle (hvor n er et dokument ID nummer):</strong><br /><br />
							  n* - Ӕndre indstilling for dette dokument og det umiddelbare underdokumenter<br />
							  n** - Ӕndre indstilling for dette dokument og ALLE underdokumenter<br />
							  n-n2 - Ӕndre indstilling for denne rӕkke af dokumenter<br />
							  n - Ӕndre indstilling for et enkelt dokument</p>
							  <p>Eksempel: 1*,4**,2-20,25 - Dette vil ӕndre den valgte indstilling
						      for dokumenter 1 og dets underdokumenter, dokument 4 og alle underdokumenter, dokumenterne 2 
						      til 20 og dokument 25.</p>';
$_lang['DM_select_tree'] = 'Vis og vӕlg dokumenter ved hjӕlp af Dokument Trӕet';

// process tree/range messages
$_lang['DM_process_noselection'] = 'Ingen valg er blevet foretaget. ';
$_lang['DM_process_novalues'] = 'Ingen vӕrdier er blevet angivet.';
$_lang['DM_process_limits_error'] = 'Øverste grӕnse mindre end mindste grӕnse:';
$_lang['DM_process_invalid_error'] = 'Ulovlig Vӕrdi:';
$_lang['DM_process_update_success'] = 'Opdatering blev fӕrdiggjort succesfuldt, uden fejl.';
$_lang['DM_process_update_error'] = 'Opdatering has completed but encountered errors:';
$_lang['DM_process_back'] = 'tilbage';

// manager access logging
$_lang['DM_log_template'] = 'Dokument administration: Skabloner ӕndret.';
$_lang['DM_log_templatevariables'] = 'Dokument administration: Skabelon variabler ӕndret.';
$_lang['DM_log_docpermissions'] = 'Dokument administration: Dokumentets tilladelser ӕndret.';
$_lang['DM_log_sortmenu'] = 'Dokument administration: Menu Index operation fӕrdiggjort.';
$_lang['DM_log_publish'] = 'Dokument administration: Dokument administration: Dokumentets Publiseret/afpubliseret indstillinger ӕndret.';
$_lang['DM_log_hidemenu'] = 'Dokument administration: Dokumentets Skjul/vis i Menu indstillinger ӕndret.';
$_lang['DM_log_search'] = 'Dokument administration: Dokumentets Søgbar/ikke-søgbar indstillinger ӕndret.';
$_lang['DM_log_cache'] = 'Dokument administration: Dokumentets Cacherbar/ikke cacherbar indstillinger ӕndret.';
$_lang['DM_log_richtext'] = 'Dokument administration: Dokumentets brug Richtext Editor indstillinger ӕndret.';
$_lang['DM_log_delete'] = 'Dokument administration: Dokumentets Slet/Slet-ikke indstillinger ӕndret.';
$_lang['DM_log_dates'] = 'Dokument administration: Dokumentets dato indstillinger ӕndret.';
$_lang['DM_log_authors'] = 'Dokument administration: Dokumentets forfatter indstillinger ӕndret.';
?>
