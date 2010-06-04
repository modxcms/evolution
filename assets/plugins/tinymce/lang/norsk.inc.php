<?php/* * Filename:       assets/plugins/tinymce/lang/norwegian.inc.php * Function:       Norwegian language file for TinyMCE.  Needs to be translated and re-encoded! * Encoding:       ISO-Latin-1 * Author:         Jeff Whitfield * Date:           2007/01/06
 * Version:        2.0.9
 * MODx version:   0.9.5
*/
include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions$_lang['tinymce_editor_theme_title'] = "Theme:";
$_lang['tinymce_editor_theme_message'] = "Here you can select which theme or skin to use with the editor.";
$_lang['tinymce_editor_custom_plugins_title'] = "Custom Plugins:";
$_lang['tinymce_editor_custom_plugins_message'] = "Enter the plugins to use for the 'custom' theme as a comma separated list.";
$_lang['tinymce_editor_custom_buttons_title'] = "Custom Buttons:";
$_lang['tinymce_editor_custom_buttons_message'] = "Enter the buttons to use for the 'custom' theme as a comma separated list for each row. Be sure that each button has the required plugin enabled in the 'Custom Plugins' setting.";
$_lang['tinymce_editor_css_selectors_title'] = "CSS selectors:";
$_lang['tinymce_editor_css_selectors_message'] = "Here you can enter a list of selectors that should be available in the editor. Enter them as follows:<br />'displayName=selectorName;displayName2=selectorName2'<br />For instance, say you have <b>.mono</b> and <b>.smallText</b> selectors in your CSS file, you could add them here as:<br />'Monospaced text=mono;Small text=smallText'<br />Note that the last entry should not have a semi-colon after it.";
$_lang['tinymce_settings'] = "TinyMCE Settings";
$_lang['tinymce_theme_simple'] = "Simple";
$_lang['tinymce_theme_advanced'] = "Advanced";
$_lang['tinymce_theme_editor'] = "Content Editor";
$_lang['tinymce_theme_custom'] = "Custom";$_lang['tinymce_theme_creative'] = 'Creative';
$_lang['tinymce_theme_logic'] = 'xhtml';
$_lang['tinymce_theme_global_settings'] = "Use the global setting";
?>