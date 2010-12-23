<?php
/**
 * Filename:       assets/plugins/tinymce/lang/francais-utf8.inc.php
 * Function:       French language file for TinyMCE
 * Encoding:       UTF-8
 * Author:         French community
 * Date:           2007/04/13, corrected on 28/07/09
 * Version:        2.1.0
 * MODx version:   Evolution 1.0
*/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['tinymce_editor_theme_title'] = "Thﾃｨme:";
$_lang['tinymce_editor_theme_message'] = "Vous pouvez sﾃｩlectionner quel thﾃｨme ou template utiliser avec la barre d'outils TinyMCE.";
$_lang['tinymce_editor_custom_plugins_title'] = "Plugins:";
$_lang['tinymce_editor_custom_plugins_message'] = "Indiquez les Plugins ﾃ utiliser pour le thﾃｨme 'personnalisﾃｩ', en les sﾃｩparant par une virgule.";
$_lang['tinymce_editor_custom_buttons_title'] = "Boutons:";
$_lang['tinymce_editor_custom_buttons_message'] = "Indiquez les boutons ﾃ utiliser pour le thﾃｨme 'personnalisﾃｩ', en les sﾃｩparant par une virgule. Chaque champ correspond ﾃ une ligne dans la barre d'outils. Assurez-vous que pour chacun des boutons sﾃｩlectionnﾃｩs, le plugin correspondant est indiquﾃｩ dans le champ de saisie 'Plugins'.";
$_lang["tinymce_editor_css_selectors_title"] = "Sﾃｩlecteurs CSS:";
$_lang["tinymce_editor_css_selectors_message"] = "Vous pouvez spﾃｩcifier une liste de sﾃｩlecteurs disponibles depuis la barre d'outils. Dﾃｩfinissez-les de la maniﾃｨre suivante :<br /> 'Nom de la classe 1=class1;Nom de la classe 2=class2'<br />Prenons l'exemple de la classe <b>.mono</b> et <b>.smallText</b> dans votre feuille de style. Vous pouvez les appeler de la faﾃｧon suivante: <br />'Monospaced text=mono;Small text=smallText'<br />La derniﾃｨre entrﾃｩe de la ligne ne doit pas ﾃｪtre suivie du point-virgule ( ; ).";
$_lang['tinymce_editor_relative_urls_title'] = "Chemin d'accﾃｨs aux fichiers:";
$_lang['tinymce_editor_relative_urls_message'] = "Cette option vous permet de dﾃｩfinir comment gﾃｩrer les chemins d'accﾃｨs pour les liens internes. NOTE: Les liens relatifs peuvent ne pas fonctionner correctement avec les alias simples. De plus, si vos liens sont relatifs ﾃ la racine du site ou dﾃｩcrivent un chemin complet, vous devrez peut-ﾃｪtre les modifier en cas de dﾃｩplacement de votre site sur un nom de domaine diffﾃｩrent.";
$_lang["tinymce_compressor_title"] = "Compression:";
$_lang["tinymce_compressor_message"] = "Cette option active/dﾃｩsactive la compression Gzip de TinyMCE afin de rﾃｩduire le temps de chargement de la barre d'outils. Si votre serveur ne supporte pas la compression Gzip, laissez cette option sur disable.";
$_lang['tinymce_settings'] = "Configuration de TinyMCE";
$_lang['tinymce_theme_simple'] = "Simple";
$_lang['tinymce_theme_advanced'] = "Avancﾃｩ";
$_lang['tinymce_theme_editor'] = "Content Editor";
$_lang['tinymce_theme_custom'] = "Personnalisﾃｩ";
$_lang['tinymce_theme_global_settings'] = "Utilisez le paramﾃｨtre global";
?>