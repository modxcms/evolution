<?php

/*
AjaxSearch.php
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 03/14/06
Description: This code is called from the ajax request.  It returns the search resluts.

Updated: 09/18/06 - Added user permissions to searching
Updated: 03/20/06 - All variables are set in the main snippet & snippet call
*/

$stripHTML = $_GET['stripHTML'];
$stripSnip = $_GET['stripSnip'];
$stripSnippets = $_GET['stripSnippets'];
$useAllWords = $_GET['useAllWords'];
$searchStyle = $_GET['searchStyle'];
$minChars = $_GET['minChars'];
$maxResults = $_GET['maxResults'];
$showMoreResults = $_GET['showMoreResults'];
$moreResultsPage = $_GET['moreResultsPage'];
$moreResultsText = $_GET['moreResultsText'];
$resultsIntroFailure = $_GET['resultsIntroFailure'];
$extract = $_GET['extract'];
$docgrp = $_GET['docgrp'];

include_once MODX_BASE_PATH . 'assets/snippets/AjaxSearch/AjaxSearch.inc.php';

$ajaxSearch = 1;
if (extension_loaded('mbstring')) {
	$needsConvert = (strtoupper(mb_internal_encoding()) == "UTF-8") ? false : true;
} else {
	$needsConvert = false;
}
if ($needsConvert && (ini_get('mbstring.encoding_translation') == '' || strtolower(ini_get('mbstring.http_input')) == 'pass')) {
	$searchString = mb_convert_encoding($_GET['search'], ini_get('mbstring.internal_encoding'), "UTF-8");
} else {
	$searchString = $_GET['search'];
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
          if ($extract) {
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
          }
          $result.='<div class="AS_ajax_result">'.$newline;
          
          if ($extract) {
            $result .='  <a class="AS_ajax_resultLink" href="index.php?id='.strval($row['id']).'&searched='.urlencode($searchString).'&highlight='.urlencode($highlightClass).'" title="' . $row['longtitle'] . '">' . $row['pagetitle'] . "</a>";
          } else {
            $result .='  <a class="AS_ajax_resultLink" href="index.php?id='.strval($row['id']).'" title="' . $row['longtitle'] . '">' . $row['pagetitle'] . "</a>";
          }
          
          $result.=$row['description']!='' ? ' <span class="AS_ajax_resultDescription">' . $row['description'] . "</span>" : "" ;
          if ($extract) {
            $result.='<div class="AS_ajax_extract">'. $text . '</div>';
          }
          $result.=$newline;
          $result.='</div>'.$newline;
          if (++$i == $maxResults) {
            //If more than max results so link to all results
            if ($showMoreResults) {
                $result .= '<div class="AS_ajax_more"><a href="index.php?id='.$moreResultsPage.'&AS_search='.urlencode($_GET['search']).'"/>'.$moreResultsText.'</a></div>'.$newline;
            }
            break;
          }
        }
        if ($needsConvert) {
            $result = mb_convert_encoding($result, "UTF-8");
        }
    } else {
        $result = '<p class="AS_ajax_resultsIntroFailure">'.$resultsIntroFailure.'</p>'.$newline;
    }
} else {
  $result = '<p class="AS_ajax_resultsIntroFailure">'.$resultsIntroFailure.'</p>'.$newline;
}

echo rawurlencode($result);
?>
