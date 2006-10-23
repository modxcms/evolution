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

$_lang['tinymce_editor_theme_title'] = "�ơ���:";
$_lang['tinymce_editor_theme_message'] = "�ơ��ޤ�j�򤷡��ġ���С���������Υ��åȤ���ӥ��ǥ����Υǥ�������ѹ��Ǥ��ޤ���";
$_lang['tinymce_editor_css_selectors_title'] = "CSS�������륻�쥯��:";
$_lang['tinymce_editor_css_selectors_message'] = "class=xxxxx�Ȥ����d�Ǥ�դΥ����˳����Ƥ��CSS���쥯���פ򤳤�������Ǥ��ޤ���<br />�񼰡�'����ե����=mono;������ʸ��=smallText'<br />�嵭�Τ褦�ˡ�ʣ��Υ�������򥻥ߥ����Ƕ��ڤäƻ��ꤷ�ޤ����Ǹ�ι��ܤθ��ˤϥ��ߥ������դ��ʤ��Ǥ��$�����";
$_lang['tinymce_editor_relative_urls_title'] = "�ѥ�������:";
$_lang['tinymce_editor_relative_urls_message'] = "TinyMCE������Υ�󥯤ʤɤ��Ѥ�������ѥ��η|�����ꤷ�ޤ������: �֥ɥ�����Ȱ��֤�������л���פϡ��ºݤ�MODx���󥹥ȡ���ǥ��쥯�ȥ��index.php��������л���Ǥ����ե��ɥ꡼URL��ɽ�������ѥ��Ȥ�Ϣư���ޤ���";
$_lang["tinymce_compressor_title"] = "Compressor:";
$_lang["tinymce_compressor_message"] = "This setting enables/disables the TinyMCE GZip Compressor to reduce overall download size which makes TinyMCE 75% smaller and a lot faster to load.  If your server does not support serverside GZip then keep this setting set at disabled.";
$_lang['tinymce_settings'] = "TinyMCE������";
?>