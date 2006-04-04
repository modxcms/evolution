<?php

/*
ajaxSearch.php
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 03/14/06
Description: This code is called from the ajax request.  It returns the search resluts.

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

include_once $base_path . 'assets/snippets/AjaxSearch/AjaxSearch.inc.php';

$ajaxSearch = 1;
$searchString = $_GET['search'];

//Clean the searchString
$searchString =  initSearchString($searchString,$stripHTML,$stripSnip,$stripSnippets,$useAllWords,$searchStyle,$minChars,$ajaxSearch);

// check querystring
$validSearch = ($searchString != '')? true : false ;

if ($validSearch) {
    //Do the search
    $rs = doSearch($searchString,$searchStyle,$useAllWords,$ajaxSearch);

    $num=mysql_numrows($rs);

    if ($num > 0) {
        $result = '';
        $i = 0;
        //Output the results
        while ($row = mysql_fetch_assoc($rs)) {
          $result.='<div class="AS_ajax_result">'.$newline;
          $result.='  <a class="AS_ajax_resultLink" href="index.php?id='.strval($row['id']).'" title="' . $row['longtitle'] . '">' . $row['pagetitle'] . "</a>";
          $result.=$row['description']!='' ? ' &ndash; <span class="AS_ajax_resultDescription">' . $row['description'] . "</span>" : "" ;
          $result.=$newline;
          $result.='</div>'.$newline;
          if (++$i == $maxResults) {
            //If more than max results so link to all results
            if ($showMoreResults) {
                $result .= '<div class="AS_ajax_more"><a href="index.php?id='.$moreResultsPage.'&AS_search='.$_GET['search'].'"/>'.$moreResultsText.'</a></div>'.$newline;
            }
            break;
          }
        }
        echo $result;
    } else {
        echo '<p class="AS_ajax_resultsIntroFailure">'.$resultsIntroFailure.'</p>'.$newline;
    }
} else {
  echo '<p class="AS_ajax_resultsIntroFailure">'.$resultsIntroFailure.'</p>'.$newline;
}

?>
