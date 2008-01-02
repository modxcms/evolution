<?php

/*
AjaxSearch.php
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 01/03/2007
Description: This code is called from the ajax request.  It returns the search resluts.

Updated: 01/22/07 - Added templating/language/mootools support
Updated: 01/03/07 - Added fixes/updates from forum
Updated: 09/18/06 - Added user permissions to searching
Updated: 03/20/06 - All variables are set in the main snippet & snippet call
*/

require_once(MODX_MANAGER_PATH . '/includes/protect.inc.php');

$stripHTML = $_POST['stripHTML'];
$stripSnip = $_POST['stripSnip'];
$stripSnippets = $_POST['stripSnippets'];
$useAllWords = $_POST['useAllWords'];
$searchStyle = $_POST['searchStyle'];
$minChars = $_POST['minChars'];
$maxResults = $_POST['maxResults'];
$showMoreResults = $_POST['showMoreResults'];
$moreResultsPage = $_POST['moreResultsPage'];
$as_language = basename($_POST['as_language']);
$extract = $_POST['extract'];
$docgrp = $_POST['docgrp'];
$highlightResult = $_POST['highlightResult'];

include_once MODX_BASE_PATH . 'assets/snippets/AjaxSearch/includes/AjaxSearch.inc.php';
include MODX_BASE_PATH . 'assets/snippets/AjaxSearch/includes/templates.inc.php';

#include default language file
include(MODX_BASE_PATH . "assets/snippets/AjaxSearch/lang/english.inc.php");

#include other language file if set.
if($as_language!="english" && $as_language != '') {
	if(file_exists(MODX_BASE_PATH . "assets/snippets/AjaxSearch/lang/".$as_language.".inc.php"))
		include MODX_BASE_PATH . "assets/snippets/AjaxSearch/lang/".$as_language.".inc.php";
}

$ajaxSearch = 1;
if (extension_loaded('mbstring')) {
	$needsConvert = (strtoupper(mb_internal_encoding()) == "UTF-8") ? false : true;
} else {
	$needsConvert = false;
}
if ($needsConvert && (ini_get('mbstring.encoding_translation') == '' || strtolower(ini_get('mbstring.http_input')) == 'pass')) {
	$searchString = mb_convert_encoding($_POST['search'], ini_get('mbstring.internal_encoding'), "UTF-8");
} else {
	$searchString = $_POST['search'];
	if ($needsConvert) {
		$resultsIntroFailure = mb_convert_encoding($resultsIntroFailure, "UTF-8");
		$moreResultsText = mb_convert_encoding($moreResultsText, "UTF-8");
	}
}

//Clean the searchString
$searchString =  initSearchString($searchString,$stripHTML,$stripSnip,$stripSnippets,$useAllWords,$searchStyle,$minChars,$ajaxSearch);

// check querystring
$validSearch = ($searchString != '')? true : false ;

if ($validSearch) {
    //Do the search
    $rs = doSearch($searchString,$searchStyle,$useAllWords,$ajaxSearch,$docgrp);

    $num=mysql_numrows($rs);
    $search = explode(" ", $searchString);

    if ($num > 0) {
        $result = '';
        $i = 0;
        //Output the results
        while ($row = mysql_fetch_assoc($rs)) {
			/*if ($extract) {
				$highlightClass = 'AS_ajax_highlight';
				$text=$row['content'];
				if (count($search)>1){
					$count=1;
					$summary='';
					foreach ($search as $searchTerm){
						$summary .= PrepareSearchContent( $text, $length=200, $searchTerm );
						$summary = preg_replace( '/' . preg_quote( $searchTerm, '/' ) . '/i', '<span class="AS_ajax_highlight AS_ajax_highlight'.$count.'">\0</span>', $summary );
						$highlightClass .= ' AS_ajax_highlight'.$count;
						$count++;
					}
					$text=$summary;
				} else {
					$search=$searchString;
					$text=PrepareSearchContent( $text, $length=200, $search );
					$text = preg_replace( '/' . preg_quote( $searchString, '/' ) . '/i', '<span class="AS_ajax_highlight AS_ajax_highlight1">\0</span>', $text );
					$highlightClass .= ' AS_ajax_highlight1';
				}
			}*/

			if ($extract) {
				$highlightClass = 'ajaxSearch_highlight';
				$text=$row['content'];
				$count=1;
				$summary='';
				$toAdd = PrepareSearchContent( $text, $length=200, $search[0] );
				strip_tags( $text );
				foreach ($search as $searchTerm) {
					if (preg_match('/' . preg_quote($searchTerm) . '/i', $text)) {
						if ($count > 1) { // The first summary was already extracted above
							$toAdd = SmartSubstr( $text , $length=200, $searchTerm );
						}
						$summary .= preg_replace( '/' . preg_quote( $searchTerm, '/' ) . '/i', '<span class="AS_ajax_highlight AS_ajax_highlight'.$count.'">\0</span>', $toAdd ) . ' ';
					}
					$highlightClass .= ' AS_ajax_highlight'.$count;
					$count++;
				}
				$text=$summary;
			}

			if ($highlightResult) {
				if (!$extract) {
					$highlightClass = 'AS_ajax_highlight';
					$count=1;
					foreach ($search as $searchTerm) {
						$highlightClass .= ' AS_ajax_highlight'.$count;
						$count++;
					}
				}

				$resultLink = 'index.php?id='.strval($row['id']).'&amp;searched='.urlencode($searchString).'&amp;highlight='.urlencode($highlightClass);
			} else {
				$resultLink = 'index.php?id='.strval($row['id']);
			}

			if ($extract) {
				$extractPlaceholders = array(
					'[+as.extractClass+]' => 'AS_ajax_extract',
					'[+as.extract+]' => $text,
				);
				$resultExtract = str_replace(array_keys($extractPlaceholders),array_values($extractPlaceholders),$asTemplates['extractWrapper']);
			} else {
				$resultExtract = '';
			}

			if ($row['description'] != '') {
				$descPlaceholders = array(
					'[+as.descriptionClass+]' => 'AS_ajax_resultDescription',
					'[+as.description+]' => $row['description'],
				);
				$resultDesc = str_replace(array_keys($descPlaceholders),array_values($descPlaceholders),$asTemplates['descriptionWrapper']);
			} else {
				$resultDesc = '';
			}

			$resultPlaceholders = array(
				'[+as.resultClass+]' => 'AS_ajax_result',
				'[+as.resultLinkClass+]' => 'AS_ajax_resultLink',
				'[+as.resultLink+]' => $resultLink,
				'[+as.longtitle+]' => $row['longtitle'],
				'[+as.pagetitle+]' => $row['pagetitle'],
				'[+as.description+]' => $resultDesc,
				'[+as.extract+]' => $resultExtract,
			);

			$result .= str_replace(array_keys($resultPlaceholders),array_values($resultPlaceholders),$asTemplates['result']);

			if (++$i == $maxResults) {
				//If more than max results so link to all results
				if ($showMoreResults) {
					$morePlaceholders = array(
						'[+as.moreClass+]' => 'AS_ajax_more',
						'[+as.moreLink+]' => 'index.php?id='.$moreResultsPage.'&amp;AS_search='.urlencode($_POST['search']),
						'[+as.moreTitle+]' => $_lang['as_moreResultsTitle'],
						'[+as.moreText+]' => $_lang['as_moreResultsText'],
					);

					$result .= str_replace(array_keys($morePlaceholders),array_values($morePlaceholders),$asTemplates['ajax_more_results']);
				}
				break;
			}
        }
        if ($needsConvert) {
            $result = mb_convert_encoding($result, "UTF-8");
        }
    } else {
		$noResultsPlaceholder = array(
			'[+as.noResultClass+]' => 'AS_ajax_resultsIntroFailure',
			'[+as.noResultText+]' => $_lang['as_resultsIntroFailure'],
		);
		$result .= str_replace(array_keys($noResultsPlaceholder),array_values($noResultsPlaceholder),$asTemplates['noResults']);
    }
} else {
	$noResultsPlaceholder = array(
		'[+as.noResultClass+]' => 'AS_ajax_resultsIntroFailure',
		'[+as.noResultText+]' => $_lang['as_resultsIntroFailure'],
	);
	$result .= str_replace(array_keys($noResultsPlaceholder),array_values($noResultsPlaceholder),$asTemplates['noResults']);
}

echo $result;
?>
