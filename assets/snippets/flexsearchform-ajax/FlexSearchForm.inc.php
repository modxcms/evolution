<?php

/*
FlexSearchForm.inc.php
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 03/14/06
Description: This code is from the FlexSearchForm Snippet
    I removed it from the snippet code so I can call it from multiple locations.
*/

// The connection settings must be set for the ajax call
function connectForAjax() {
    global $database_server;
    global $database_user;
    global $database_password;
    global $dbase;
    global $table_prefix;      
    $database = str_replace("`","",$dbase);
    $db = mysql_connect($database_server, $database_user, $database_password, true) or die("Cannot connect to database (connectForAjax)");
    $selected = mysql_select_db($database, $db) or die ("Cannot select database (connectForAjax)");
    return $table_prefix;
}


function initSearchString($searchString,$stripHTML,$stripSnip,$stripSnippets,$useAllWords,$searchStyle,$minChars,$ajaxSearch) {
  if ($ajaxSearch) {
    $tblPrefix = connectForAjax();
  } else {
    global $modx;
  }
  // block sensitive search patterns
  $searchString =
  (
  $searchString != "{{" &&
  $searchString != "[[" &&
  $searchString != "[!" &&
  $searchString != "[(" &&
  $searchString != "[~" &&
  $searchString != "[*"
  )
  ?
  $searchString : "" ;

  // Remove dangerous tags and such

  // Strip HTML too
  if ($stripHTML){
    $searchString = strip_tags($searchString);
  }

  // Regular expressions of things to remove from search string
  $modRegExArray[] = '~\[\[(.*?)\]\]~';   // [[snippets]]
  $modRegExArray[] = '~\[!(.*?)!\]~';     // [!noCacheSnippets!]
  $modRegExArray[] = '!\[\~(.*?)\~\]!is'; // [~links~]
  $modRegExArray[] = '~\[\((.*?)\)\]~';   // [(settings)]
  $modRegExArray[] = '~{{(.*?)}}~';       // {{chunks}}
  $modRegExArray[] = '~\[\*(.*?)\*\]~';   // [*attributes*]

  // Remove modx sensitive tags
  if ($stripSnip){
    foreach ($modRegExArray as $mReg){
      $searchString = preg_replace($mReg,'',$searchString);
    }
  }

  // Remove snippet names
  if ($stripSnippets && $searchString != ''){
    // get all the snippet names
    if ($ajaxSearch) {
        $tbl = $tblPrefix . "site_snippets";
        $snippetSql = "SELECT $tbl.name FROM $tbl;";
        $snippetRs = mysql_query($snippetSql) or die ("Cannot query the database (initSearchString)");
    } else {
        $tbl = $modx->dbConfig['dbase'] . "." . $modx->dbConfig['table_prefix'] . "site_snippets";
        $snippetSql = "SELECT $tbl.name FROM $tbl;";
        $snippetRs = $modx->dbQuery($snippetSql);
        $snippetCount = $modx->recordCount($snippetRs);
    }
    $snippetNameArray = array();
    
    if ($ajaxSearch) {
      while ($thisSnippetRow = mysql_fetch_assoc($snippetRs)) {
          $snippetNameArray[] = strtolower($thisSnippetRow['name']);
      }
    } else {
      for ($s = 0; $s < $snippetCount; $s++){
          $thisSnippetRow = $modx->fetchRow($snippetRs);
          $snippetNameArray[] = strtolower($thisSnippetRow['name']);
      }
    }
    // Split search into strings
    $searchWords = explode(' ',$searchString);
    $cleansedWords = '';
    foreach($searchWords as $word){
      if ($word != '' &&
          !in_array(strtolower($word),$snippetNameArray) &&
            ((!$useAllWords) ||
            ($searchStyle == 'partial') ||
            (strlen($word) >= $minChars && $useAllWords && $searchStyle == 'relevance'))
         ){
        $cleansedWords .= $word.' ';
      }
    }
    // Remove last space
    $cleansedWords = substr($cleansedWords,0,(strlen($cleansedWords)-1));

    $searchString = $cleansedWords;
  }

  return $searchString;
}

function doSearch($searchString,$searchStyle,$useAllWords,$ajaxSearch) {
    if ($ajaxSearch) {
      $tblPrefix = connectForAjax();
    } else {
      global $modx;
    }
    $search = explode(" ", $searchString);
    if ($ajaxSearch) {
        $tbl = $tblPrefix . "site_content";
    } else {
        $tbl = $modx->dbConfig['dbase'] . "." . $modx->dbConfig['table_prefix'] . "site_content";
    }

    if ($searchStyle == 'partial'){
      $sql = "SELECT id, pagetitle, description ";
      $sql .= "FROM $tbl ";
      $sql .= "WHERE ";
      if (count($search)>1 && $useAllWords){
        foreach ($search as $searchTerm){
          $sql .= "(pagetitle LIKE '%$searchString%' OR description LIKE '%$searchString%' OR content LIKE '%$searchTerm%') AND ";
        }
      } else {
        $sql .= "(pagetitle LIKE '%$searchString%' OR description LIKE '%$searchString%' OR content LIKE '%$searchString%') AND ";
      }
      $sql .= "$tbl.published = 1 AND $tbl.searchable=1 AND $tbl.deleted=0;";
    } else {
      $sql = "SELECT id, pagetitle, description ";
      $sql .= "FROM $tbl WHERE ";
      if (count($search)>1 && $useAllWords){
        foreach ($search as $searchTerm){
          $sql .= "MATCH (pagetitle, description, content) AGAINST ('$searchTerm') AND ";
        }
      } else {
        $sql .= "MATCH (pagetitle, description, content) AGAINST ('$searchString') AND ";
      }
      $sql .= "$tbl.published = 1 AND $tbl.searchable=1 AND $tbl.deleted=0;";
    }

    if ($ajaxSearch) {
        $rs = mysql_query($sql) or die ("Cannot query the database (doSearch)");
    } else {
        $rs = $modx->dbQuery($sql);
    }
    return $rs;
}

?>
