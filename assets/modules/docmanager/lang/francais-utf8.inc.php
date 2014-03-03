<?php
/**
 * Document Manager Module
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: David Mollière, Jean-Christophe Brebion
 * Language: French
 * Date: 2014/02/24
 */
// titles
$_lang['DM_module_title'] = 'Doc Manager';
$_lang['DM_action_title'] = 'Sélectionnez une opération';
$_lang['DM_range_title'] = 'Spécifiez une plage d\'ID';
$_lang['DM_tree_title'] = 'Sélectionnez les Ressources dans l\'Arbre du Site';
$_lang['DM_update_title'] = 'Mise à jour effectuée';
$_lang['DM_sort_title'] = 'Éditeur d\'index de menu';

// tabs
$_lang['DM_doc_permissions'] = 'Permissions des Ressources';
$_lang['DM_template_variables'] = 'Variables de Modèle';
$_lang['DM_sort_menu'] = 'Trier les index de menu';
$_lang['DM_change_template'] = 'Modifier le Modèle';
$_lang['DM_publish'] = 'Publier/Dépublier';
$_lang['DM_other'] = 'Autres propriétés';

// buttons
$_lang['DM_close'] = 'Fermer Doc Manager';
$_lang['DM_cancel'] = 'Retour';
$_lang['DM_go'] = 'Exécuter';
$_lang['DM_save'] = 'Sauvegarder';
$_lang['DM_sort_another'] = 'Trier un autre';

// templates tab
$_lang['DM_tpl_desc'] = 'Choisissez le Modèle à partir de la liste ci-dessous et spécifiez les ID des Ressources qui doivent être modifiées. Pour ce faire, vous pouvez spécifier une plage d\'ID ou utiliser directement l\'Arbre du Site.';
$_lang['DM_tpl_no_templates'] = 'Modèle introuvable';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Nom';
$_lang['DM_tpl_column_description'] = 'Description';
$_lang['DM_tpl_blank_template'] = 'Modèle vide (_blank)';
$_lang['DM_tpl_results_message'] = 'Utilisez le bouton «Retour» si vous souhaitez faire d\'autres modifications. Le cache du site a été vidé automatiquement.';

// template variables tab
$_lang['DM_tv_desc'] = 'Précisez l\'ID de la(des) Ressource(s) qui doit(doivent) être modifiée(s), soit en spécifiant une plage d\'ID ou via l\'Arbre du Site, puis choisissez le Modèle dans la liste (les Variables de Modèle associées seront chargées). Saisissez les Variables de Modèles souhaitées puis validez.';
$_lang['DM_tv_template_mismatch'] = 'Cette Ressource n\'utilise pas le Modèle sélectionné.';
$_lang['DM_tv_doc_not_found'] = 'Cette Ressource n\'est pas dans la base de données.';
$_lang['DM_tv_no_tv'] = 'Pas de Variable de Modèle pour ce Modèle.';
$_lang['DM_tv_no_docs'] = 'Aucune Ressource sélectionnée pour la mise à jour.';
$_lang['DM_tv_no_template_selected'] = 'Pas de Modèle sélectionné.';
$_lang['DM_tv_loading'] = 'Variables de Modèle en cours de chargement...';
$_lang['DM_tv_ignore_tv'] = 'Ignorer ces Variables de Modèle (liste séparée par des virgules):';
$_lang['DM_tv_ajax_insertbutton'] = 'Insérer';

// document permissions tab
$_lang['DM_doc_desc'] = 'Choisir le Groupe de Ressources à partir de la liste ci-dessous et préciser si celui-ci doit être ajouté ou supprimé du groupe. Ensuite, précisez les ID des Ressources qui doivent être modifiées. Vous pouvez soit spécifier une plage d\'ID, soit utiliser l\'Arbre du Site.';
$_lang['DM_doc_no_docs'] = 'Ce Groupe de Ressources n\'existe pas.';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Nom';
$_lang['DM_doc_radio_add'] = 'Ajouter un Groupe de Ressources';
$_lang['DM_doc_radio_remove'] = 'Supprimer un Groupe de Ressources';

$_lang['DM_doc_skip_message1'] = 'La Ressource dont l\'ID est';
$_lang['DM_doc_skip_message2'] = 'fait déjà partie du Groupe de Ressources sélectionné (non pris en compte)';

// sort menu tab
$_lang['DM_sort_pick_item'] = 'Veuillez cliquer, dans l\'Arbre du Site, sur la racine du site ou sur la Ressource parente que vous souhaitez trier.';
$_lang['DM_sort_updating'] = 'Mise à jour ...';
$_lang['DM_sort_updated'] = 'Mise à jour effectuée.';
$_lang['DM_sort_nochildren'] = 'Cette Ressource parente n\'a aucun enfant';
$_lang['DM_sort_noid'] = 'Aucune Ressource sélectionnée. Merci de revenir en arrière et de sélectionner une Ressource.';

// other tab
$_lang['DM_other_header'] = 'Réglages divers de Ressources';
$_lang['DM_misc_label'] = 'Réglages disponibles:';
$_lang['DM_misc_desc'] = 'Veuillez sélectionner un paramètre dans le menu déroulant, ainsi que l\'option requise. Vous ne pouvez modifier qu\'un seul paramètre à la fois.';

$_lang['DM_other_dropdown_publish'] = 'Publier/Dépublier';
$_lang['DM_other_dropdown_show'] = 'Montrer/Masquer dans le menu';
$_lang['DM_other_dropdown_search'] = 'Recherchable/Non recherchable';
$_lang['DM_other_dropdown_cache'] = 'À mettre en cache/À ne pas mettre en cache';
$_lang['DM_other_dropdown_richtext'] = 'Éditeur/Sans éditeur';
$_lang['DM_other_dropdown_delete'] = 'Effacer/Restaurer';

// radio button text
$_lang['DM_other_publish_radio1'] = 'Publier';
$_lang['DM_other_publish_radio2'] = 'Dépublier';
$_lang['DM_other_show_radio1'] = 'Masquer dans le menu';
$_lang['DM_other_show_radio2'] = 'Afficher dans le menu';
$_lang['DM_other_search_radio1'] = 'Recherchable';
$_lang['DM_other_search_radio2'] = 'Non recherchable';
$_lang['DM_other_cache_radio1'] = 'À mettre en cache';
$_lang['DM_other_cache_radio2'] = 'À ne pas mettre en cache';
$_lang['DM_other_richtext_radio1'] = 'Éditeur WYSIWYG';
$_lang['DM_other_richtext_radio2'] = 'Pas d\'éditeur WYSIWYG';
$_lang['DM_other_delete_radio1'] = 'Effacer';
$_lang['DM_other_delete_radio2'] = 'Restaurer';

// adjust dates
$_lang['DM_adjust_dates_header'] = 'Définir les dates des Ressources';
$_lang['DM_adjust_dates_desc'] = 'Toutes les options de date peuvent être modifiées. Utilisez «Voir le calendrier» pour définir les dates.';
$_lang['DM_view_calendar'] = 'Voir le calendrier';
$_lang['DM_clear_date'] = 'Remettre les dates à zéro';

// adjust authors
$_lang['DM_adjust_authors_header'] = 'Redéfinir les auteurs';
$_lang['DM_adjust_authors_desc'] = 'Utilisez la liste déroulante pour définir le nouvel auteur da la Ressource.';
$_lang['DM_adjust_authors_createdby'] = 'Créé par:';
$_lang['DM_adjust_authors_editedby'] = 'Édité par:';
$_lang['DM_adjust_authors_noselection'] = 'Aucune modification';

// labels
$_lang['DM_date_pubdate'] = 'Date de publication:';
$_lang['DM_date_unpubdate'] = 'Date de dépublication:';
$_lang['DM_date_createdon'] = 'Date de création:';
$_lang['DM_date_editedon'] = 'Date de modification:';
$_lang['DM_date_notset'] = ' (indéfini)';
$_lang['DM_date_dateselect_label'] = 'Sélectionnez une date: ';

// document select section
$_lang['DM_select_submit'] = 'Envoi';
$_lang['DM_select_range'] = 'Revenir à la sélection de la plage de Ressources';
$_lang['DM_select_range_text'] = '<p><strong>Clé (ou «n» est un ID de Ressource):</strong><br /><br />
							  n* - Modifier le réglage pour cette Ressource et ses enfants immédiats<br /> 
							  n** - Modifier le réglage pour cette Ressource et tous ses enfants<br /> 
							  n-n2 - Modifier le réglage pour cette plage de Ressources<br /> 
							  n - Modifier le réglage pour une Ressource</p> 
							  <p>Exemple: 1*,4**,2-20,25 - Cela modifiera le réglage sélectionné pour la Ressource 1 et ses enfants, la Ressource 4 et tous ses enfants, et les Ressources 2 à 20, ainsi que la Ressource 25.</p>';
$_lang['DM_select_tree'] = 'Afficher et sélectionner les Ressources en utilisant l\'Arbre du Site';

// process tree/range messages
$_lang['DM_process_noselection'] = 'Aucune sélection effectuée. ';
$_lang['DM_process_novalues'] = 'Aucune valeur définie.';
$_lang['DM_process_limits_error'] = 'Limite supérieure plus petite que la limite inférieure:';
$_lang['DM_process_invalid_error'] = 'Valeur incorrecte:';
$_lang['DM_process_update_success'] = 'La mise à jour a été effectuée correctement, sans erreurs.';
$_lang['DM_process_update_error'] = 'La mise à jour a été effectuée, mais a généré des erreurs:';
$_lang['DM_process_back'] = 'Retour';

// manager access logging
$_lang['DM_log_template'] = 'Document Manager: Modèle(s) modifié(s).';
$_lang['DM_log_templatevariables'] = 'Document Manager: Variable(s) de Modèle modifiée(s).';
$_lang['DM_log_docpermissions'] = 'Document Manager: Permission(s) de la(des) Ressource(s) modifiée(s).';
$_lang['DM_log_sortmenu'] = 'Document Manager: Modification de l\'index de menu effectuée.';
$_lang['DM_log_publish'] = 'Document Manager: Réglages de publication/dépublication effectués.';
$_lang['DM_log_hidemenu'] = 'Document Manager: Option(s) de masquage/affichage de la(des) Ressource(s) dans le menu modifiée(s).';
$_lang['DM_log_search'] = 'Document Manager:Option(s) de recherche de la(des) Ressource(s) dans le menu modifiée(s).';
$_lang['DM_log_cache'] = 'Document Manager: Option(s) de cache de la(des) Ressource(s) dans le menu modifiée(s).';
$_lang['DM_log_richtext'] = 'Document Manager: Option(s) d\'édition de la(des) Ressource(s) dans le menu modifiée(s).';
$_lang['DM_log_delete'] = 'Document Manager: Option(s) d\'effacement/de restauration de la(des) Ressource(s) dans le menu modifiée(s).';
$_lang['DM_log_dates'] = 'Document Manager: Date(s) de création/édition de la(des) Ressource(s) modifiée(s).';
$_lang['DM_log_authors'] = 'Document Manager: Auteur de la(des) Ressource(s) modifié(s).';
?>
