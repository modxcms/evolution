<?php
/**
 * Filename:       assets/plugins/tinymce/lang/francais.inc.php
 * Function:       French language file for TinyMCE
 * Encoding:       ISO-8859-1
 * Author:         French community
 * Date:           2013-03-10
 * Version:        3.5.8
 * MODX version:   0.9.5-1.0.9
*/
include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['mce_editor_theme_title'] = "Th�me:";
$_lang['mce_editor_theme_message'] = "Vous pouvez s�lectionner quel th�me ou template utiliser avec la barre d'outils TinyMCE.";
$_lang['mce_editor_custom_plugins_title'] = "Plugins :";
$_lang['mce_editor_custom_plugins_message'] = "Indiquez les plugins � utiliser pour le th�me 'personnalis�', en les s�parant par une virgule.";
$_lang['mce_editor_custom_buttons_title'] = "Boutons :";
$_lang['mce_editor_custom_buttons_message'] = "Indiquez les boutons � utiliser pour le th�me 'personnalis�', en les s�parant par une virgule. Chaque champ correspond � une ligne dans la barre d'outils. Assurez-vous que pour chacun des boutons s�lectionn�s, le plugin correspondant est indiqu� dans le champ de saisie 'Plugins'.";
$_lang['mce_editor_css_selectors_title'] = "S�lecteurs CSS:";
$_lang['mce_editor_css_selectors_message'] = "Vous pouvez sp�cifier une liste de s�lecteurs disponibles depuis la barre d'outils. D�finissez-les de la mani�re suivante :<br /> 'Nom de la classe 1=class1;Nom de la classe 2=class2'<br />Prenons l'exemple de la classe <b>.mono</b> et <b>.smallText</b> dans votre feuille de style. Vous pouvez les appeler de la fa�on suivante : <br />'Monospaced text=mono;Small text=smallText'<br />La derni�re entr�e de la ligne ne doit pas �tre suivie du point-virgule ( ; ).";
$_lang['mce_settings'] = "Configuration de TinyMCE";
$_lang['mce_theme_simple'] = "Simple";
$_lang['mce_theme_advanced'] = "Avanc�";
$_lang['mce_theme_editor'] = "Content Editor";
$_lang['mce_theme_custom'] = "Personnalis�";
$_lang['mce_theme_creative'] = 'Creative';
$_lang['mce_theme_logic'] = 'xhtml';
$_lang['mce_theme_legacy'] = 'legacy style';
$_lang['mce_theme_global_settings'] = "Utilisez le param�tre global";
$_lang['mce_editor_skin_title'] = 'Skin';
$_lang['mce_editor_skin_message'] = 'Design of toolbar. see tinymce/tiny_mce/themes/advanced/skins/<br />';
$_lang['mce_editor_entermode_title'] = 'Enter key mode';
$_lang['mce_editor_entermode_message'] = 'Operation when the enter key is pressed is set up.';
$_lang['mce_entermode_opt1'] = 'Wrap &lt;p&gt;&lt;/p&gt;';
$_lang['mce_entermode_opt2'] = 'Insert &lt;br /&gt;';

$_lang['mce_element_format_title'] = 'Element format';
$_lang['mce_element_format_message'] = 'This option enables control if elements should be in html or xhtml mode. xhtml is the default state for this option. This means that for example &lt;br /&gt; will be &lt;br&gt; if you set this option to &quot;html&quot;.';
$_lang['mce_schema_title'] = 'Schema';
$_lang['mce_schema_message'] = 'The schema option enables you to switch between the HTML4 and HTML5 schema. This controls the valid elements and attributes that can be placed in the HTML. This value can either be the default html4 or html5.';

$_lang['mce_toolbar1_msg'] = 'Default : undo,redo,|,bold,forecolor,backcolor,strikethrough,formatselect,fontsizeselect, pastetext,pasteword,code,|,fullscreen,help';
$_lang['mce_toolbar2_msg'] = 'Default : image,media,link,unlink,anchor,|,justifyleft,justifycenter,justifyright,|,bullist, numlist,|,blockquote,outdent,indent,|,table,hr,|,template,visualblocks,styleprops,removeformat';

$_lang['mce_tpl_title'] = 'Template button';
$_lang['mce_tpl_msg'] = 'You can insert the HTML block which you registered beforehand from toolbar. You make HTML block as resource or a chunk, and can appoint plural number with a comma.';
$_lang['mce_tpl_docid'] = 'Resource IDs';
$_lang['mce_tpl_chunkname'] = 'Chunk names';