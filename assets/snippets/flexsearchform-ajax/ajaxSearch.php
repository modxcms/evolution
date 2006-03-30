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

include_once 'FlexSearchForm.inc.php';

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
          $result.='<div class="FSF_ajax_result">';
          $result.='<a class="FSF_ajax_resultLink" href="index.php?id='.strval($row['id']).'" title="' . $row['pagetitle'] . '">' . $row['pagetitle'] . "</a>";
          $result.=$row['description']!='' ? '<span class="FSF_ajax_resultDescription">' . $row['description'] . "</span>" : "" ;
          $result.='</div>';
          if (++$i == $maxResults) {
            //If more than max results so link to all results
            if ($showMoreResults) {
                $result .= '<div class="FSF_ajax_more"><a href="index.php?id='.$moreResultsPage.'&FSF_search='.$_GET['search'].'"/>'.$moreResultsText.'</a></div>';
            }
            break;
          }
        }
        echo $result;
    } else {
        echo '<p class="FSF_ajax_resultsIntroFailure">'.$resultsIntroFailure.'</p>';
    }
} else {
  echo '<p class="FSF_ajax_resultsIntroFailure">'.$resultsIntroFailure.'</p>';
}

?>
