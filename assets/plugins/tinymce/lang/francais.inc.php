<?php
/**
 * Filename:       assets/plugins/tinymce/lang/francais.inc.php
 * Function:       French language file for TinyMCE
 * Encoding:       ISO
 * Author:         French community
 * Date:           2007/04/13
 * Version:        2.1.0
 * MODx version:   0.9.6
*/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['tinymce_editor_theme_title'] = "Thème:";
$_lang['tinymce_editor_theme_message'] = "Vous pouvez sélectionner quel thème ou template utiliser avec la barre d'outils TinyMCE.";
$_lang['tinymce_editor_custom_plugins_title'] = "Plugins :";
$_lang['tinymce_editor_custom_plugins_message'] = "Indiquez les plugins à utiliser pour le thème 'personnalisé', en les séparant par une virgule.";
$_lang['tinymce_editor_custom_buttons_title'] = "Boutons :";
$_lang['tinymce_editor_custom_buttons_message'] = "Indiquez les boutons à utiliser pour le thème 'personnalisé', en les séparant par une virgule. Chaque champ correspond à une ligne dans la barre d'outils. Assurez-vous que pour chacun des boutons sélectionnés, le plugin correspondant est indiqué dans le champ de saisie 'Plugins'.";
$_lang["tinymce_editor_css_selectors_title"] = "Sélecteurs CSS:";
$_lang["tinymce_editor_css_selectors_message"] = "Vous pouvez spécifier une liste de sélecteurs disponibles depuis la barre d'outils. Définissez-les de la manière suivante :<br /> 'Nom de la classe 1=class1;Nom de la classe 2=class2'<br />Prenons l'exemple de la classe <b>.mono</b> et <b>.smallText</b> dans votre feuille de style. Vous pouvez les appeler de la façon suivante : <br />'Monospaced text=mono;Small text=smallText'<br />La dernière entrée de la ligne ne doit pas être suivie du point-virgule ( ; ).";
$_lang['tinymce_editor_relative_urls_title'] = "Chemin d'accès aux fichiers:";
$_lang['tinymce_editor_relative_urls_message'] = "Cette option vous permet de définir comment gérer les chemins d'accès pour les liens internes. Note: Document relative links may not work right with friendly alias paths. Also, links might need to be changed if you move your site to a different domain or subdirectory if links are root relative or full path.";
$_lang["tinymce_compressor_title"] = "Compression:";
$_lang["tinymce_compressor_message"] = "Cette option active/désactive la compression Gzip de TinyMCE afin de réduire le temps de chargement de la barre d'outils. Si votre serveur ne supporte pas la compression Gzip, laissez cette option sur disable.";
$_lang['tinymce_settings'] = "Configuration de TinyMCE";
$_lang['tinymce_theme_simple'] = "Simple";
$_lang['tinymce_theme_advanced'] = "Avancé";
$_lang['tinymce_theme_editor'] = "Content Editor";
$_lang['tinymce_theme_custom'] = "Personnalisé";
$_lang['tinymce_theme_global_settings'] = "Utilisez le paramètre global";
?>
