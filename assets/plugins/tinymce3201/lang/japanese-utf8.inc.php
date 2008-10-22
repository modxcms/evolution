<?php
/*
 * Filename:       assets/plugins/tinymce/lang/japanese-utf8.inc.php
 * Function:       Japanese language file for TinyMCE.
 * Encoding:       UTF-8
 * Author:         Japanese community
 * Date:           2007/04/24
 * Version:        2.1.0
 * MODx version:   0.9.6
*/
include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions
$_lang['tinymce_editor_theme_title'] = "テーマ:";
$_lang['tinymce_editor_theme_message'] = "テーマを選択し、ツールバーアイコンのセットおよびエディタのデザインを変更できます。";
$_lang['tinymce_editor_custom_plugins_title'] = "カスタムテーマのプラグイン設定:";
$_lang['tinymce_editor_custom_plugins_message'] = "カスタムテーマを選択したときに利用するプラグインをカンマ（,）で区切って記述します。";
$_lang['tinymce_editor_custom_buttons_title'] = "カスタムボタン:";
$_lang['tinymce_editor_custom_buttons_message'] = "カスタムテーマを選択したときに利用するボタンをカンマ（,）で区切ってそれぞれの行に記述します。各ボタンは、カスタムプラグイン設定で、そのボタンを含むプラグインを指定していなければなりません。";
$_lang['tinymce_editor_css_selectors_title'] = "CSSスタイルセレクタ:";
$_lang['tinymce_editor_css_selectors_message'] = "class=xxxxxという形で任意のタグに割り当てる「CSSクラス」をここで設定できます。<br />書式：本のタイトル=booktitle;著者=author<br />上記のように、複数のクラスをセミコロンで区切って指定します。最後の項目の後ろにはセミコロンを付けないでください。";
$_lang['tinymce_settings'] = "TinyMCEの設定";
$_lang['tinymce_theme_simple'] = "Simple";
$_lang['tinymce_theme_advanced'] = "Advanced";
$_lang['tinymce_theme_editor'] = "Content Editor";
$_lang['tinymce_theme_custom'] = "Custom";
?>