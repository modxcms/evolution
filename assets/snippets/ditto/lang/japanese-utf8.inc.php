<?php

/**
 * Filename:       assets/snippets/ditto/japanese-utf8.inc.php
 * Function:       Default Japanese language file for Ditto.
 * Encoding:       utf-8
 * Author:         The MODx Project
 * Date:           2006/07/9
 * Version:        1.0.2
 * MODx version:   0.9.2.1
*/

// NOTE: New language keys should added at the bottom of this page

$_lang['file_does_not_exist'] = " がありません。ファイルを確認してください。";

$_lang['default_template'] = '
    <div class="ditto_summaryPost">
        <h3><a href="[~[+id+]~]">[+title+]</a></h3>
        <div>[+summary+]</div>
        <p>[+link+]</p>
        <div style="text-align:right;">投稿者：<strong>[+author+]</strong> [+date+]</div>
    </div>
';

$_lang['blank_tpl'] = "指定したDittoテンプレート(chunk)の名前が違うか、中身がありません。";

$_lang['missing_placeholders_tpl'] = '指定したDittoテンプレート(chunk)にプレースホルダが含まれていません。下記のテンプレートの内容を確認してください。：<br /><br /><hr /><br /><br />';

$_lang['missing_placeholders_tpl_2'] = '<br /><br /><hr /><br />';

$_lang['default_splitter'] = "<!-- splitter -->";

$_lang['more_text'] = "続きを読む．．．";

$_lang['no_entries'] = '<p>記事はありません。</p>';

$_lang['date_format'] = "%Y/%m/%d %H:%M";

$_lang['archives'] = "アーカイブ";

$_lang['prev'] = "&lt; 戻る";

$_lang['next'] = "次へ &gt;";

$_lang['button_splitter'] = "｜";

$_lang['default_copyright'] = "[(site_name)] 2007";	

$_lang['rss_lang'] = "ja";

$_lang['debug_summarized'] = "Number supposed to be summarized (summarize):";

$_lang['debug_returned'] = "<br />Total supposed to be returned:";

$_lang['debug_retrieved_from_db'] = "Count of total in db:";

$_lang['debug_sort_by'] = "Sort by (sortBy):";

$_lang['debug_sort_dir'] = "Sort direction (sortDir):";

$_lang['debug_start_at'] = "Start at";

$_lang['debug_stop_at'] = "and stop at";

$_lang['debug_out_of'] = "out of";

$_lang['debug_document_data'] = "Document Data for ";

$_lang['default_archive_template'] = "<a href=\"[~[+id+]~]\">[+title+]</a> (<span class=\"ditto_date\">[+date+]</span>)";

$_lang['invalid_class'] = "The Ditto class is invalid. Please check it.";

// New language key added 2-July-2006 to 5-July-2006

// Keys deprecated : $_lang['api_method'] and $_lang['GetAllSubDocs_method'] 

$_lang['tvs'] = "TVs:";

$_lang['api'] = "Using the new MODx 0.9.2.1 API";

?>