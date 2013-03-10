<?php
/*
 * Filename:       assets/plugins/tinymce/lang/svenska-utf8.inc.php
 * Function:       Swedish language file for TinyMCE
 * Encoding:       UTF-8
 * Author:         Jeff Whitfield
 * Date:           2013-03-10
 * Version:        3.5.8
 * MODX version:   0.9.5-1.0.9
*/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['mce_editor_theme_title'] = "Tema";
$_lang['mce_editor_theme_message'] = "Här kan du välja vilket tema eller skal som editorn ska använda.";
$_lang['mce_editor_custom_plugins_title'] = "Anpassade plugins";
$_lang['mce_editor_custom_plugins_message'] = "Ange de plugins som ska användas i det \"anpassade\" temat som en komma-avgränsad lista.";
$_lang['mce_editor_custom_buttons_title'] = "Anpassade knappar";
$_lang['mce_editor_custom_buttons_message'] = "Ange de knappar som ska användas i det \"anpassade\" temat som en komma-avgränsad lista för varje rad. Kontrollera att varje knapp har den tillhörande pluginnen vald i inställningarna för \"Anpassade plugins\".";
$_lang['mce_editor_css_selectors_title'] = "CSS-selektorer";
$_lang['mce_editor_css_selectors_message'] = "Här kan du ange en lista med selektorer som ska finnas tillgängliga i editorn. Skriv in dom så här:<br />\"namnEtikett=selektorNamn;namnEtikett2=selektorNamn2\"<br />Om du till exempel har selektorerna <b>.mono</b> och <b>.litenText</b> i din css-fil, så kan du ange dom här som:<br />\"Jämnbred text=mono;Liten text=litenText\"<br />Notera att den sista definitionen inte ska följas av ett semikolon.";
$_lang['mce_settings'] = "Inställningar för TinyMCE";
$_lang['mce_theme_simple'] = "Enkelt";
$_lang['mce_theme_advanced'] = "Avancerat";
$_lang['mce_theme_editor'] = "Innehållseditor";
$_lang['mce_theme_custom'] = "Anpassat";
$_lang['mce_theme_creative'] = 'Creative';
$_lang['mce_theme_logic'] = 'xhtml';
$_lang['mce_theme_legacy'] = 'legacy style';
$_lang['mce_theme_global_settings'] = "Använd globalt";
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