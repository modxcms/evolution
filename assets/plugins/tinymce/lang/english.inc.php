<?php
/**
 * Filename:       assets/plugins/tinymce/lang/english.inc.php
 * Function:       English language file for TinyMCE
 * Encoding:       ISO-Latin-1
 * Author:         Jeff Whitfield and yama
 * Date:           2014/02/01
 * Version:        3.5.10
 * MODX version:   0.9.5-1.0.15
*/

$_lang['mce_editor_theme_title'] = 'Theme';
$_lang['mce_editor_theme_message'] = 'Here you can select which theme or skin to use with the editor.';
$_lang['mce_editor_custom_plugins_title'] = 'Custom Plugins';
$_lang['mce_editor_custom_plugins_message'] = 'Enter the plugins to use for the \'custom\' theme as a comma separated list.<br />Default : template,visualblocks,autolink,inlinepopups,autosave,save,advlist,style,fullscreen, advimage,paste,advlink,media,contextmenu,table';
$_lang['mce_editor_custom_buttons_title'] = 'Custom Buttons';
$_lang['mce_editor_custom_buttons_message'] = 'Enter the buttons to use for the \'custom\' theme as a comma separated list for each row. Be sure that each button has the required plugin enabled in the \'Custom Plugins\' setting.';
$_lang['mce_editor_css_selectors_title'] = 'CSS Selectors';
$_lang['mce_editor_css_selectors_message'] = 'Here you can enter a list of selectors that should be available in the editor. Enter them as follows:<br />\'displayName=selectorName;displayName2=selectorName2\'<br />For instance, say you have <b>.mono</b> and <b>.smallText</b> selectors in your CSS file, you could add them here as:<br />\'Monospaced text=mono;Small text=smallText\'<br />Note that the last entry should not have a semi-colon after it.';
$_lang['mce_settings'] = 'TinyMCE Settings';
$_lang['mce_theme_simple'] = 'Simple';
$_lang['mce_theme_full'] = 'Full';
$_lang['mce_theme_advanced'] = 'Advanced';
$_lang['mce_theme_editor'] = 'MODX Style';
$_lang['mce_theme_custom'] = 'Custom';
$_lang['mce_theme_creative'] = 'Creative';
$_lang['mce_theme_logic'] = 'xhtml';
$_lang['mce_theme_legacy'] = 'legacy style';
$_lang['mce_theme_global_settings'] = 'Use the global setting';
$_lang['mce_editor_skin_title'] = 'Skin';
$_lang['mce_editor_skin_message'] = 'Design of toolbar. see tinymce/tiny_mce/themes/advanced/skins/<br />';
$_lang['mce_editor_entermode_title'] = 'Enter Key Mode';
$_lang['mce_editor_entermode_message'] = 'Operation when the enter key is pressed is set up.';
$_lang['mce_entermode_opt1'] = 'Wrap &lt;p&gt;&lt;/p&gt;';
$_lang['mce_entermode_opt2'] = 'Insert &lt;br /&gt;';

$_lang['mce_element_format_title'] = 'Element Format';
$_lang['mce_element_format_message'] = 'This option enables control if elements should be in html or xhtml mode. xhtml is the default state for this option. This means that for example &lt;br /&gt; will be &lt;br&gt; if you set this option to &quot;html&quot;.';
$_lang['mce_schema_title'] = 'Schema';
$_lang['mce_schema_message'] = 'The schema option enables you to switch between the HTML4 and HTML5 schema. This controls the valid elements and attributes that can be placed in the HTML. This value can either be the default html4 or html5.';

$_lang['mce_toolbar1_msg'] = 'Default: undo,redo,|,bold,forecolor,backcolor,strikethrough,formatselect,fontsizeselect, pastetext,pasteword,code,|,fullscreen,help';
$_lang['mce_toolbar2_msg'] = 'Default: image,media,link,unlink,anchor,|,justifyleft,justifycenter,justifyright,|,bullist, numlist,|,blockquote,outdent,indent,|,table,hr,|,template,visualblocks,styleprops,removeformat';

$_lang['mce_tpl_title'] = 'Template Button';
$_lang['mce_tpl_msg'] = 'You could define templates on chunk or ressource base for the template button in TinyMCE (won\'t be displayed by default). The content of the chunk/of the resource will be inserted at the cursor position as html code in TinyMCE. Multiple chunk names or ressource IDs have to be separated by comma.';
$_lang['mce_tpl_docid'] = 'Resource IDs';
$_lang['mce_tpl_chunkname'] = 'Chunk Names';