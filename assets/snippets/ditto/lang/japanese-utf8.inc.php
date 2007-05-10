<?php

/**
 * Filename:       assets/snippets/ditto/lang/japanese-utf-8.inc.php
 * Function:       Default Japanese language file for Ditto.
 * Encoding:       UTF-8
 * Author:         The MODx Project
 * Date:           2007/04/24
 * Version:        2.0.2
 * MODx version:   0.9.6
 */

//-- JAPANESE LANGUAGE FILE ENCODED IN UTF-8
include_once(dirname(__FILE__).'/english.inc.php'); // fall back to English defaults if needed
/* Set locale to Japanese */
setlocale (LC_ALL, 'ja_JP');

$_lang['language'] = "japanese-utf8";

$_lang['abbr_lang'] = "ja";

$_lang['file_does_not_exist'] = "ファイルがありません。ファイルの存在をチェックしてみてください。";

$_lang['extender_does_not_exist'] = "extender does not exist. Please check it.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">投稿者：<strong>[+author+]</strong>[+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>

TPL;

$_lang["bad_tpl"] = "<p>&[+tpl+] <br />指定したDittoテンプレート(chunk)にプレースホルダが含まれていません。上記のテンプレートの内容を確認してください。</p>";

$_lang['no_documents'] = '<p>記事がありません。</p>';

$_lang['resource_array_error'] = 'Resource Array Error';
 
$_lang['prev'] = "&lt; 戻る";

$_lang['next'] = "次へ &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2007";

$_lang['invalid_class'] = "The Ditto class is invalid. Please check it.";

$_lang['none'] = "None";

$_lang['edit'] = "Edit";

$_lang['dateFormat'] = "%d-%b-%y %H:%M";

// Debug Tab Names

$_lang['info'] = "Info";

$_lang['modx'] = "MODx";

$_lang['fields'] = "Fields";

$_lang['templates'] = "Templates";

$_lang['filters'] = "Filters";

$_lang['prefetch_data'] = "Prefetch Data";

$_lang['retrieved_data'] = "Retreived Data";

// Debug Text

$_lang['placeholders'] = "Placeholders";

$_lang['params'] = "Parameters";

$_lang['basic_info'] = "Basic Info";

$_lang['document_info'] = "Document Info";

$_lang['debug'] = "Debug";

$_lang['version'] = "Version";

$_lang['summarize'] = "Summarize";

$_lang['total'] = "Total";	 

$_lang['sortBy'] = "Sort By";

$_lang['sortDir'] = "Sort Direction";

$_lang['start'] = "Start";
	 
$_lang['stop'] = "Stop";

$_lang['ditto_IDs'] = "IDs";

$_lang['ditto_IDs_selected'] = "Selected IDs";

$_lang['ditto_IDs_all'] = "All IDs";

$_lang['open_dbg_console'] = "Open Debug Console";

$_lang['save_dbg_console'] = "Save Debug Console";

?>