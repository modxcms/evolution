<?php
/*
 * Filename:       assets/plugins/tinymce/lang/japanese-utf8.inc.php
 * Function:       Japanese language file for TinyMCE.  Needs to be translated and re-encoded!
 * Encoding:       ISO-Latin-1
 * Author:         yamamoto
 * Date:           2006/07/03
 * Version:        2.0.6.1
 * MODx version:   0.9.2
*/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['tinymce_editor_theme_title'] = "テーマ:";
$_lang['tinymce_editor_theme_message'] = "テーマを選択し、ツールバーアイコンのセットおよびエディタのデザインを変更できます。";
$_lang['tinymce_editor_custom_plugins_title'] = "Custom Plugins:";
$_lang['tinymce_editor_custom_plugins_message'] = "Enter the plugins to use for the 'custom' theme as a comma separated list.";
$_lang['tinymce_editor_custom_buttons_title'] = "Custom Buttons:";
$_lang['tinymce_editor_custom_buttons_message'] = "Enter the buttons to use for the 'custom' theme as a comma separated list for each row. Be sure that each button has the required plugin enabled in the 'Custom Plugins' setting.";
$_lang['tinymce_editor_css_selectors_title'] = "CSSスタイルセレクタ:";
$_lang['tinymce_editor_css_selectors_message'] = "class=xxxxxという形で任意のタグに割り当てる「CSSセレクタ」をここで設定できます。<br />書式：'等幅フォント=mono;小さい文字=smallText'<br />上記のように、複数のスタイルをセミコロンで区切って指定します。最後の項目の後ろにはセミコロンを付けないでください。";
$_lang['tinymce_settings'] = "TinyMCEの設定";
$_lang['tinymce_theme_simple'] = "Simple";
$_lang['tinymce_theme_advanced'] = "Advanced";
$_lang['tinymce_theme_editor'] = "Content Editor";
$_lang['tinymce_theme_custom'] = "Custom";
?>