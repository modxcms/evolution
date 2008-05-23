<?php

/*日本語の文字化け防止
english.inc.php - for AjaxSearch 1.5
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 01/22/07
Description: Language strings for AjaxSearch
*/

//-- JAPANESE LANGUAGE FILE ENCODED IN UTF-8
include_once(dirname(__FILE__).'/english.inc.php'); // fall back to English defaults if needed
/* Set locale to Japanese */
setlocale (LC_ALL, 'ja_JP');

$_lang['as_resultsIntroFailure'] = 'There were no search results. Please try using more general terms to get more results.';
$_lang['as_searchButtonText'] = '検索';
$_lang['as_boxText'] = 'Search here...';
$_lang['as_introMessage'] = 'Please enter a search term to begin your search.';
$_lang['as_resultsFoundTextSingle'] = '%d result found for "%s".';
$_lang['as_resultsFoundTextMultiple'] = '%d results found for "%s".';
$_lang['as_paginationTextSinglePage'] = '';
$_lang['as_paginationTextMultiplePages'] = 'Result pages: ';
$_lang['as_moreResultsText'] = 'Click here to view all results.';
$_lang['as_moreResultsTitle'] = 'More Results';

?>