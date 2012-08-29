<?php
/*
 * Filename:       assets/snippets/ditto/lang/japanese-utf-8.inc.php
 * Function:       Default Japanese language file for Ditto.
 * Encoding:       UTF-8
 * Author:         MODx CMS JAPAN and phize.net
 * Date:           2009/07/25
*/
$_lang['language'] = "japanese-utf8";
$_lang['abbr_lang'] = "ja";
$_lang['file_does_not_exist'] = "ファイルがありません。ファイルの存在を確認してください。";
$_lang['extender_does_not_exist'] = "extenderがありません。extendersディレクトリ内のファイルの存在を確認してください。";
$_lang['default_template'] = <<<TPL
    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">投稿者：<strong>[+author+]</strong>[+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>
TPL;
$_lang["bad_tpl"] = "<p>&[+tpl+] <br />指定したDittoテンプレート(チャンク)にプレースホルダが含まれていません。上記のテンプレートの内容を確認してください。</p>";
$_lang['no_documents'] = '<p>記事がありません。</p>';
$_lang['resource_array_error'] = 'リソース配列エラー';
 
$_lang['prev'] = "&lt; 戻る";
$_lang['next'] = "次へ &gt;";
$_lang['button_splitter'] = "|";
$_lang['default_copyright'] = "[(site_name)] 2009";
$_lang['invalid_class'] = "Dittoクラスが不正です。classesディレクトリ内のファイルを確認してください。";
$_lang['none'] = "なし";
$_lang['edit'] = "編集";
$_lang['dateFormat'] = "%Y/%m/%d %H:%M";

// Debug Tab Names
$_lang['info'] = "情報";
$_lang['modx'] = "MODx";
$_lang['fields'] = "フィールド";
$_lang['templates'] = "テンプレート";
$_lang['filters'] = "フィルタ";
$_lang['prefetch_data'] = "先読みデータ";
$_lang['retrieved_data'] = "取得済みデータ";

// Debug Text
$_lang['placeholders'] = "プレースホルダ";
$_lang['params'] = "パラメータ";
$_lang['basic_info'] = "基本情報";
$_lang['document_info'] = "ドキュメント情報";
$_lang['debug'] = "デバッグ";
$_lang['version'] = "バージョン";
$_lang['summarize'] = "出力件数";
$_lang['total'] = "総件数";	 
$_lang['sortBy'] = "並び替えフィールド";
$_lang['sortDir'] = "並び替え順";
$_lang['start'] = "開始位置";
	 
$_lang['stop'] = "停止位置";
$_lang['ditto_IDs'] = "ID";
$_lang['ditto_IDs_selected'] = "選択済みID";
$_lang['ditto_IDs_all'] = "全てのID";
$_lang['open_dbg_console'] = "デバッグコンソールを開く";
$_lang['save_dbg_console'] = "デバッグコンソールを保存";
?>