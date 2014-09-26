<?php
/**
 * Document Manager Module
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Thomas Steinberg, www.elbwiese.de
 * Language: German
 * Date: 2014/02/24
 */
// titles
$_lang['DM_module_title'] = 'Dokumenten-Manager';
$_lang['DM_action_title'] = 'Wählen Sie eine Funktion';
$_lang['DM_range_title'] = 'Definieren Sie die IDs der Dokumente';
$_lang['DM_tree_title'] = 'Wählen Sie ein Dokument aus dem Dokumentenbaum';
$_lang['DM_update_title'] = 'Update erledigt';
$_lang['DM_sort_title'] = 'Menüindex-Editor';

// tabs
$_lang['DM_doc_permissions'] = 'Zugriffsberechtigungen';
$_lang['DM_template_variables'] = 'Template Variablen';
$_lang['DM_sort_menu'] = 'Menüeinträge sortieren';
$_lang['DM_change_template'] = 'Template wechseln';
$_lang['DM_publish'] = 'Veröffentlichen/Zurückziehen';
$_lang['DM_other'] = 'Weitere Einstellungen';

// buttons
$_lang['DM_close'] = 'Doc Manager schließen';
$_lang['DM_cancel'] = 'Zurück';
$_lang['DM_go'] = 'Los';
$_lang['DM_save'] = 'Speichern';
$_lang['DM_sort_another'] = 'Noch eine Sortierung';

// templates tab
$_lang['DM_tpl_desc'] = 'Wählen Sie das gewünschte Template. Legen Sie dann die IDs der Dokumente fest, die geändert werden sollen.';
$_lang['DM_tpl_no_templates'] = 'Keine Templates gefunden';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Name';
$_lang['DM_tpl_column_description'] = 'Beschreibung';
$_lang['DM_tpl_blank_template'] = 'Leeres Template';
$_lang['DM_tpl_results_message'] = 'Nutzen Sie den Zurück-Button, wenn Sie noch mehr Änderungen durchfüren wollen. Der Cache wurde geleert.';

// template variables tab
$_lang['DM_tv_desc'] = 'Definieren Sie die IDs der zu ändernden Dokumente, in dem Sie das Dokument entweder aus dem Dokumentenbaum auswählen oder mit Hilfe der untenstehenden Funktion. Wälen Sie dann aus der Tabelle das passende Template. Die zugeordneten Template Variables werden geladen. Geben Sie die gewünschten Template Variablen ein und klicken Sie auf los.';
$_lang['DM_tv_template_mismatch'] = 'Dieses Dokument benutzt nicht das gewählte Template.';
$_lang['DM_tv_doc_not_found'] = 'Das Dokument wurde nicht in der Datenbank gefunden.';
$_lang['DM_tv_no_tv'] = 'Keine Template Variablen für dieses Dokument gefunden.';
$_lang['DM_tv_no_docs'] = 'Sie haben keine Dokumente ausgewählt, die aktualisiert werden könnten.';
$_lang['DM_tv_no_template_selected'] = 'Sie haben kein Template ausgewählt..';
$_lang['DM_tv_loading'] = 'Die Template Variablen werden geladen...';
$_lang['DM_tv_ignore_tv'] = 'Diese Template Variables ignorieren (Werte mit Kommas getrennt eintragen):';
$_lang['DM_tv_ajax_insertbutton'] = 'Einfügen';

// document permissions tab
$_lang['DM_doc_desc'] = 'Wählen Sie eine Dokumentengruppe und legen Sie fest, ob Dokumente hinzugefügt oder daraus gelöscht werden sollen. Tragen Sie dann ins das untere Feld die IDs der zu ändernden Dokumente ein.';
$_lang['DM_doc_no_docs'] = 'Keine Dokumenten-Gruppe gefunden';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Name';
$_lang['DM_doc_radio_add'] = 'Dokumente hinzufüen';
$_lang['DM_doc_radio_remove'] = 'Dokumente entfernen';

$_lang['DM_doc_skip_message1'] = 'Das Dokument mit der ID';
$_lang['DM_doc_skip_message2'] = 'gehört schon zur gewählten Dokumentengruppe (Überspringen)';

// sort menu tab
$_lang['DM_sort_pick_item'] = 'Wählen Sie einen Ordner, dessen Dokumente Sie im Menü neu sortieren wollen.';
$_lang['DM_sort_updating'] = 'Aktualisieren...';
$_lang['DM_sort_updated'] = 'Aktualisiert';
$_lang['DM_sort_nochildren'] = 'Dieses Dokument ist kein Ordner.';
$_lang['DM_sort_noid'] = 'Es wurden kein Dokument ausgewählt. Bitten gehen Sie zurück und wählen Sie ein Dokument.';

// other tab
$_lang['DM_other_header'] = 'Diverse Einstellungen';
$_lang['DM_misc_label'] = 'Mögliche Einstellungen:';
$_lang['DM_misc_desc'] = 'Wählen Sie die gewüschten Einstellungen und die zugehörige Option. Es kann jedesmal nur eine Einstellung geändert werden.';

$_lang['DM_other_dropdown_publish'] = 'Veröffentlichen/Zurückziehen';
$_lang['DM_other_dropdown_show'] = 'Im Menü anzeigen/verbergen';
$_lang['DM_other_dropdown_search'] = 'Durchsuchbar/Nicht durchsuchbar';
$_lang['DM_other_dropdown_cache'] = 'Cachen/Nicht cachen';
$_lang['DM_other_dropdown_richtext'] = 'Rich-Text-Editor/Kein Rich-Text-Editor';
$_lang['DM_other_dropdown_delete'] = 'Löschen/Wiederherstellen';

// radio button text
$_lang['DM_other_publish_radio1'] = 'Veröffentlichen';
$_lang['DM_other_publish_radio2'] = 'Veröffentlichung zurückziehen';
$_lang['DM_other_show_radio1'] = 'Nicht im Menü anzeigen';
$_lang['DM_other_show_radio2'] = 'Im Menü anzeigen';
$_lang['DM_other_search_radio1'] = 'Durchsuchbar';
$_lang['DM_other_search_radio2'] = 'Nicht durchsuchbar';
$_lang['DM_other_cache_radio1'] = 'Cachebar';
$_lang['DM_other_cache_radio2'] = 'Nicht cachebar';
$_lang['DM_other_richtext_radio1'] = 'Richtext';
$_lang['DM_other_richtext_radio2'] = 'Kein Rich-Text-Editor';
$_lang['DM_other_delete_radio1'] = 'Löschen';
$_lang['DM_other_delete_radio2'] = 'Wiederherstellen';

// adjust dates
$_lang['DM_adjust_dates_header'] = 'Daten der Dokumente einstellen';
$_lang['DM_adjust_dates_desc'] = 'Die folgenden Einstellungen können geändert werden. Nutzen Sie den Kalender.';
$_lang['DM_view_calendar'] = 'Kalender anzeigen';
$_lang['DM_clear_date'] = 'Datum löschen';

// adjust authors
$_lang['DM_adjust_authors_header'] = 'Autor festlegen';
$_lang['DM_adjust_authors_desc'] = 'Einen Autor aus der Liste wählen.';
$_lang['DM_adjust_authors_createdby'] = 'Angelegt von:';
$_lang['DM_adjust_authors_editedby'] = 'Bearbeitet von:';
$_lang['DM_adjust_authors_noselection'] = 'Keine Änderung';

// labels
$_lang['DM_date_pubdate'] = 'Veröffentlicht am:';
$_lang['DM_date_unpubdate'] = 'Zurückgezogen am:';
$_lang['DM_date_createdon'] = 'Angelegt am:';
$_lang['DM_date_editedon'] = 'Bearbeitet am:';
$_lang['DM_date_notset'] = ' (nicht gesetzt)';
$_lang['DM_date_dateselect_label'] = 'Datum wählen: ';

// document select section
$_lang['DM_select_submit'] = 'Ausführen';
$_lang['DM_select_range'] = 'Gehen Sie zurück, um die Dokumenten-ID zu definieren';
$_lang['DM_select_range_text'] = '<p><strong>Schlüssel (n enspricht der ID eines Dokuments):</strong><br /><br />
                                                          n* - Die Einstellungen für diesen Ordner und für alle unmittelbar darunter liegenden Dokumente ändern<br />
                                                          n** - Die Einstellungen für diesen Ordner und für alle in ihm  enthaltenen Dokumente und Ordner sowie deren Inhalte ändern<br />
                                                          n-n2 - Einstellungen für alle Dokumente in diesem Bereich anpassen<br />
                                                          n - Einstellungen für ein einzelnes Dokument anpassen</p>
                                                          <p>Beipiel: 1*,4**,2-20,25 - Ändert die Einstellungen für Ordner 1 und die dort abgelegten Dokumente, von Dokument 4 und allen darin enthaltenen Dokumente und Ordner nebst deren Inhalt, die Dokumente 2 bis 20 und das Dokument 25.</p>';
$_lang['DM_select_tree'] = 'Wählen Sie ein Dokument aus dem Dokumentenbaum';

// process tree/range messages
$_lang['DM_process_noselection'] = 'Sie haben keine Auswahl getroffen. ';
$_lang['DM_process_novalues'] = 'Sie haben keine Werte angegeben.';
$_lang['DM_process_limits_error'] = 'Der obere Grenzwert ist kleiner als der untere:';
$_lang['DM_process_invalid_error'] = 'Ungültiger Wert:';
$_lang['DM_process_update_success'] = 'Aktualisierung erfolgreich und fehlerfrei.';
$_lang['DM_process_update_error'] = 'Die Aktualisierung war erfolgreich, aber es gab einige Fehler:';
$_lang['DM_process_back'] = 'Zurück';

// manager access logging
$_lang['DM_log_template'] = 'Document Manager: Templates geändert.';
$_lang['DM_log_templatevariables'] = 'Document Manager: Template Variablen geändert.';
$_lang['DM_log_docpermissions'] = 'Document Manager: Zugriffsberechtigungen geändert.';
$_lang['DM_log_sortmenu'] = 'Document Manager: Menü neu strukturiert.';
$_lang['DM_log_publish'] = 'Document Manager: Einstellungen Veröffentlicht/Zurückgezogen geändert.';
$_lang['DM_log_hidemenu'] = 'Document Manager: Im Menü anzeigen/verbergen geändert.';
$_lang['DM_log_search'] = 'Document Manager: Durchsuchbar/Nicht durchsuchbar geändert.';
$_lang['DM_log_cache'] = 'Document Manager: Cachen/Nicht cachen geändert.';
$_lang['DM_log_richtext'] = 'Document Manager: Nutzung des Rich-Text-Editors geändert.';
$_lang['DM_log_delete'] = 'Document Manager: Löschen/Wiederherstellen geändert.';
$_lang['DM_log_dates'] = 'Document Manager: Datumseinstellungen geändert.';
$_lang['DM_log_authors'] = 'Document Manager: Autoren geändert.';
?>
