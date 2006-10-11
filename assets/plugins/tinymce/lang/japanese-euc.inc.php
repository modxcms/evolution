<?php
/**
 * Filename:       assets/plugins/tinymce/lang/japanese-euc.inc.php
 * Function:       Japanese language file for TinyMCE.  Needs to be translated and re-encoded!
 * Encoding:       ISO-Latin-1
 * Author:         yamamoto
 * Date:           2006/07/03
 * Version:        2.0.6.1
 * MODx version:   0.9.2
**/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['tinymce_editor_theme_title'] = "テーマ:";
$_lang['tinymce_editor_theme_message'] = "テーマを選択し、ツールバーアイコンのセットおよびエディタのデザインを変更できます。";
$_lang['tinymce_editor_css_selectors_title'] = "CSSスタイルセレクタ:";
$_lang['tinymce_editor_css_selectors_message'] = "class=xxxxxという形で任意のタグに割り当てる「CSSセレクタ」をここで設定できます。<br />書式：'等幅フォント=mono;小さい文字=smallText'<br />上記のように、複数のスタイルをセミコロンで区切って指定します。最後の項目の後ろにはセミコロンを付けないでください。";
$_lang['tinymce_editor_relative_urls_title'] = "パスの設定:";
$_lang['tinymce_editor_relative_urls_message'] = "TinyMCEが画像のリンクなどに用いる内部パスの形式を指定します。注意: 「ドキュメント位置からの相対指定」は、実際はMODxインストールディレクトリのindex.phpからの相対指定です。フレンドリーURLで表現されるパスとは連動しません。";
$_lang['tinymce_settings'] = "TinyMCEの設定";
?>