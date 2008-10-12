<?php

/*日本語の文字化け防止
english.inc.php - for AjaxSearch 1.8
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 01/22/07.
Modified by: MODx Japanese moderators
Modified on:07/22/08
Description: Language strings for AjaxSearch
Modified by PMS on 21/09/08 to include translations for as_resultsFoundTextSingle and as_resultsFoundTextMultiple.
  $2 is the search text and $1 is the number of search results.
*/

//-- JAPANESE LANGUAGE FILE ENCODED IN UTF-8
include_once(dirname(__FILE__).'/english.inc.php'); // fall back to English defaults if needed
/* Set locale to Japanese */
setlocale (LC_ALL, 'ja_JP');

$_lang['as_resultsIntroFailure'] = '一致する検索結果がありませんでした。より一般的な単語で再度検索してみてください';
$_lang['as_searchButtonText'] = '検索';
$_lang['as_boxText'] = '検索語を入力してください';
$_lang['as_introMessage'] = '検索語を入力してください';
$_lang['as_resultsFoundTextSingle'] = '<q>%2$s</q>で検索した結果、%1$d件見つかりました。';
$_lang['as_resultsFoundTextMultiple'] = '<q>%2$s</q>で検索した結果、%1$d件見つかりました。';
$_lang['as_paginationTextSinglePage'] = '';
$_lang['as_paginationTextMultiplePages'] = '検索結果ページ: ';
$_lang['as_moreResultsText'] = 'すべての結果を見る';
$_lang['as_moreResultsTitle'] = 'もっと見る';
$_lang['as_minChars'] = '検索には、最低 %d 文字以上の入力が必要です。';
$_lang['oneword'] = '最低ひとつの単語を含む';
$_lang['allwords'] = 'すべての単語を含む';
$_lang['exactphrase'] = '完全一致';
$_lang['nowords'] = '単語を含まない';

?>