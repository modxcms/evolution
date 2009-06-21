<?php
/**
 * Document Manager Module - francais.inc.php
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Garry Nutting   Traduction : David Mollière
 * For: MODx CMS (www.modxcms.com)
 * Date:29/09/2006 Version: 1.6
 * 
 */
 
//-- ENGLISH LANGUAGE FILE
 
//-- titles
$_lang['DM_module_title'] = 'DocManager';
$_lang['DM_action_title'] = 'Selectionnez une opération';
$_lang['DM_range_title'] = 'Spécifiez une plage d\'ID';
$_lang['DM_tree_title'] = 'Selectionnez les documents dans l\'arbre';
$_lang['DM_update_title'] = 'Mise à jour effectuée';
$_lang['DM_sort_title'] = 'Editeur d\'index de Menu';

//-- tabs
$_lang['DM_doc_permissions'] = 'Permissions des documents';
$_lang['DM_template_variables'] = 'Variables de modèle';
$_lang['DM_sort_menu'] = 'Trier les items de menu';
$_lang['DM_change_template'] = 'Modifier le modèle';
$_lang['DM_publish'] = 'Publier/Dépublier';
$_lang['DM_other'] = 'Autres propriétés';
 
//-- buttons
$_lang['DM_close'] = 'Fermer DocManager';
$_lang['DM_cancel'] = 'Retour';
$_lang['DM_go'] = 'Exécuter';
$_lang['DM_save'] = 'Sauvegarder';
$_lang['DM_sort_another'] = 'Trier un autre';

//-- templates tab
$_lang['DM_tpl_desc'] = 'Choisissez le modèle à partir de la liste ci-dessous et spécifiez les ID de documents qui doivent être modifiés. Vous pouvez spécifier soit une plage d\'ID, soit en utilisant l\'arbre des documents.';
$_lang['DM_tpl_no_templates'] = 'Modèle introuvable';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Nom';
$_lang['DM_tpl_column_description'] ='Description';
$_lang['DM_tpl_blank_template'] = 'Modèle vide (_blank)';

$_lang['DM_tpl_results_message']= 'Utilisez le bouton "Retour" si vous souhaitez faire d\'autres modifications. Le cache du site a été automatiquement vidé.';

//-- template variables tab
$_lang['DM_tv_desc'] = 'Précisez l\'ID du(des) document(s) qui doit(doivent) être modifié(s), soit en spécifiant une plage d\'ID ou via l\'arbre des document, puis choisissez le modèle dans la liste (les variables de modèle associées seront chargées). Saisissez les variables de modèles souhaitées puis validez.';
$_lang['DM_tv_template_mismatch'] = 'Ce document n\'utilise pas le modèle sélectionné.';
$_lang['DM_tv_doc_not_found'] = 'Ce document n\'est pas dans la base de données.';
$_lang['DM_tv_no_tv'] = 'Pas de variable de modèle pour ce modèle.';
$_lang['DM_tv_no_docs'] = 'Aucun document sélectionné pour la mise à jour.';
$_lang['DM_tv_no_template_selected'] = 'Pas de modèle sélectionné.';
$_lang['DM_tv_loading'] = 'Variables de modèle en cours de chargement...';
$_lang['DM_tv_ignore_tv'] = 'Ignorer ces variables de modèle (liste séparée par des virgules):';
$_lang['DM_tv_ajax_insertbutton'] = 'Insérer';

//-- document permissions tab
$_lang['DM_doc_desc'] = 'Choisir le groupe de document à partir de la liste ci-dessous et si celuci doit être ajouté ou supprimer du groupe. Ensuite, précisez l\'ID des documents qui doivent être modifiées. Vous pouvez spécifier soit une plage d\'ID, soit en utilisant l\'arbre des documents.';
$_lang['DM_doc_no_docs'] = 'Ce groupe de document n\'existe pas.';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Nom';
$_lang['DM_doc_radio_add'] = 'Ajouter un groupe de documents';
$_lang['DM_doc_radio_remove'] = 'Supprimer un groupe de documents';

$_lang['DM_doc_skip_message1'] = 'Le document dont l\'ID est';
$_lang['DM_doc_skip_message2'] = 'fait déjà partie du groupe de document sélectionné (non pris en compte)';

//-- sort menu tab
$_lang['DM_sort_pick_item'] = 'Merci de cliquer sur l\'item de l\'arborescence du document que vous souhaitez trier.'; 
$_lang['DM_sort_updating'] = 'Mise à jour ...';
$_lang['DM_sort_updated'] = 'Mis à jour.';
$_lang['DM_sort_nochildren'] = 'Ce parent n\'a aucun enfant';
$_lang['DM_sort_noid']='Aucun document selectionné. Merci de revenir en arrière et de sélectionner un document.';

//-- other tab
$_lang['DM_other_header'] = 'Réglages divers de document';
$_lang['DM_misc_label'] = 'Réglages disponibles:';
$_lang['DM_misc_desc'] = 'Merci de choisir un item du menu déroulant ainsi que l\'option requise. Un seul item peut être modifié à la fois.';

$_lang['DM_other_dropdown_publish'] = 'Publier/Dépublier';
$_lang['DM_other_dropdown_show'] = 'Montrer/Masquer dans le menu';
$_lang['DM_other_dropdown_search'] = 'Recherchable/Non recherchable';
$_lang['DM_other_dropdown_cache'] = 'A mettre en cache/A ne pas mettre en cache';
$_lang['DM_other_dropdown_richtext'] = 'Editeur/Sans Editeur';
$_lang['DM_other_dropdown_delete'] = 'Effacer/Restaurer';

//-- radio button text
$_lang['DM_other_publish_radio1'] = 'Publier'; 
$_lang['DM_other_publish_radio2'] = 'Dépublier';
$_lang['DM_other_show_radio1'] = 'Masquer dans le menu'; 
$_lang['DM_other_show_radio2'] = 'Afficher dans le menu';
$_lang['DM_other_search_radio1'] = 'Recherchable'; 
$_lang['DM_other_search_radio2'] = 'Non recherchable';
$_lang['DM_other_cache_radio1'] = 'A mettre en cache'; 
$_lang['DM_other_cache_radio2'] = 'A ne pas mettre en cache';
$_lang['DM_other_richtext_radio1'] = 'Editeur WYSIWYG'; 
$_lang['DM_other_richtext_radio2'] = 'Pas d\'éditeur WYSIWYG';
$_lang['DM_other_delete_radio1'] = 'Effacer'; 
$_lang['DM_other_delete_radio2'] = 'Restaurer';

//-- adjust dates 
$_lang['DM_adjust_dates_header'] = 'Définir les dates des documents';
$_lang['DM_adjust_dates_desc'] = 'N\'importe lequel des option de date peuvent être modifiés. Utiliser "Voir le calendrier" pour définir les dates.';
$_lang['DM_view_calendar'] = 'Voir le calendrier';
$_lang['DM_clear_date'] = 'Remettre les dates à zéro';

//-- adjust authors
$_lang['DM_adjust_authors_header'] = 'Redéfinir les auteurs';
$_lang['DM_adjust_authors_desc'] = 'Utiliser la liste déroulante pour définir le nouvel auteur du document.';
$_lang['DM_adjust_authors_createdby'] = 'Créé par:';
$_lang['DM_adjust_authors_editedby'] = 'Edité par:';
$_lang['DM_adjust_authors_noselection'] = 'Aucune modification';

 //-- labels
$_lang['DM_date_pubdate'] = 'Date de publication:';
$_lang['DM_date_unpubdate'] = 'Date de dépublication:';
$_lang['DM_date_createdon'] = 'Date de création:';
$_lang['DM_date_editedon'] = 'Date de modification:';
//$_lang['DM_date_deletedon'] = 'Deleted On Date';

$_lang['DM_date_notset'] = ' (indéfini)';
//deprecated
$_lang['DM_date_dateselect_label'] = 'Sélectionner une date: ';

//-- document select section
$_lang['DM_select_submit'] = 'Envoi';
$_lang['DM_select_range'] = 'Revenir à la définition de la plage de document';
$_lang['DM_select_range_text'] = '<p><strong>Clé (ou n est une ID de document):</strong><br /><br />
							  n* - Modifier le réglage pour ce document et ses enfants immédiats<br /> 
							  n** - Modifier le réglage pour ce document et tous ses enfants<br /> 
							  n-n2 - Modifier le réglage pour cette plage de documents<br /> 
							  n - Modifier le réglage pour un document</p> 
							  <p>Exemple: 1*,4**,2-20,25 - Cela modifiera le réglage sélectionné pour le document 1 et ses enfants, le document 4 et tous ses enfants, et les documents 2 à 20, ainsi que le document 25</p>';
$_lang['DM_select_tree'] ='Afficher et sélectionner les documents en utilisant l\'Arbre des documents';

//-- process tree/range messages
$_lang['DM_process_noselection'] = 'Aucune sélection effectuée. ';
$_lang['DM_process_novalues'] = 'Aucune valeur définie.';
$_lang['DM_process_limits_error'] = 'Limite supérieure plus petite que la limite inférieure:';
$_lang['DM_process_invalid_error'] = 'Valeur incorrecte:';
$_lang['DM_process_update_success'] = 'La mise à jour s\'est correctement déroulée, sans erreurs.';
$_lang['DM_process_update_error'] = 'La mise à jour a été effectuée mais a généré des erreurs:';
$_lang['DM_process_back'] = 'Retour';

//-- manager access logging
$_lang['DM_log_template'] = 'Document Manager: Modèle(s) modifié(s).';
$_lang['DM_log_templatevariables'] = 'Document Manager: Variable(s) de modèle modifiée(s).';
$_lang['DM_log_docpermissions'] ='Document Manager: Permission(s) du(des) document(s) modidifiée(s).';
$_lang['DM_log_sortmenu']='Document Manager: Modification de l\'index de menu effectuée.';
$_lang['DM_log_publish']='Document Manager: Réglages de publication/dépublication modifiés.';
$_lang['DM_log_hidemenu']='Document Manager: Option(s) de masquage/affichage du(des) document(s) dans le menu modifiée(s).';
$_lang['DM_log_search']='Document Manager:Option(s) de recherche du(des) document(s) dans le menu modifiée(s).';
$_lang['DM_log_cache']='Document Manager: Option(s) de cache du(des) document(s) dans le menu modifiée(s)..';
$_lang['DM_log_richtext']='Document Manager: Option(s) d\'édition du(des) document(s) dans le menu modifiée(s)..';
$_lang['DM_log_delete']='Document Manager: Option(s) d\'effacement/de restauration du(des) document(s) dans le menu modifiée(s).';
$_lang['DM_log_dates']='Document Manager: Date(s) de création/édition du(des) document(s) modifiée(s).';
$_lang['DM_log_authors']='Document Manager: Auteur du(des) document(s) modifié(s).';

?>
