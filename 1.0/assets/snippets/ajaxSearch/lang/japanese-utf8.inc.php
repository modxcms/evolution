<?php
/*
japanese-utf8.inc.php - for AjaxSearch 1.8.2
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 01/22/07
Description: Language strings for AjaxSearch

Modified by: MODx Japanese moderators
Modified on:07/22/08

Modified by: PMS to include translations for as_resultsFoundTextSingle and as_resultsFoundTextMultiple.
  $2 is the search text and $1 is the number of search results.
Modified on: 21/09/08

Modified by: PMS to include additional translations for version 1.8.2.
Modified on: 29/03/09
*/

//-- JAPANESE LANGUAGE FILE ENCODED IN UTF-8
include_once(dirname(__FILE__).'/english-utf8.inc.php'); // fall back to English defaults if needed
/* Set locale to Japanese */
setlocale (LC_ALL, 'ja_JP');

$_lang['as_resultsIntroFailure'] = '一致する検索結果がありませんでした。類似する別の単語で再度検索してください。';
$_lang['as_searchButtonText'] = '検索';
$_lang['as_boxText'] = '検索語を入力してください';
$_lang['as_introMessage'] = '検索する単語を入力してください';
$_lang['as_resultsFoundTextSingle'] = '<q>%2$s</q>で検索した結果、%1$d件見つかりました。';
$_lang['as_resultsFoundTextMultiple'] = '<q>%2$s</q>で検索した結果、%1$d件見つかりました。';
$_lang['as_paginationTextSinglePage'] = '';
$_lang['as_paginationTextMultiplePages'] = '検索結果ページ：　';
$_lang['as_moreResultsText'] = 'すべての結果を見る';
$_lang['as_moreResultsTitle'] = 'もっと見る';
$_lang['as_maxWords'] = '検索には、最大 %d 文字まで入力できます。';
$_lang['as_minChars'] = '検索には、最低 %d 文字以上の入力が必要です。';
$_lang['as_maxChars'] = '検索には、最大 %d 文字まで入力できます。';
$_lang['oneword'] = '最低ひとつの単語を含む';
$_lang['allwords'] = 'すべての単語を含む';
$_lang['exactphrase'] = '完全に一致する文章';
$_lang['nowords'] = '単語を含まない';
$_lang['as_cmtHiddenFieldIntro'] = 'この欄は空白のままにしてください。<br />何も入力しないでください。';
$_lang['as_cmtIntroMessage'] = '検索していたものが見つかりましたか？コメントをこちらへどうぞ。';
$_lang['as_cmtSubmitText'] = '送信';
$_lang['as_cmtResetText'] = '消去';
$_lang['as_cmtThksMessage'] = 'コメントをありがとうございました。';
?>
