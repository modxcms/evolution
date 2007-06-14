<?php

/*
AjaxSearch.inc.php
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 01/03/2007
Description: Helper functions for AjaxSearch

Updated: 06/03/07 - Added utf8 fix from atma
Updated: 01/22/07 - Added Template/Lang/Mootools support
Updated: 01/03/2007 - Added fixes/additions from forums
Updated: 09/18/06 - Added user permissions to searching
*/

// The connection settings must be set for the ajax call
function connectForAjax() {
    global $database_server;
    global $database_user;
    global $database_password;
    global $dbase;
    global $table_prefix;
    $database = str_replace("`","",$dbase);
    $db = mysql_connect($database_server, $database_user, $database_password) or die("Cannot connect to database (connectForAjax)");
    $selected = mysql_select_db($database, $db) or die ("Cannot select database (connectForAjax)");
	mysql_query("SET CHARACTER SET utf8;");
    return $table_prefix;
}


function initSearchString($searchString,$stripHTML,$stripSnip,$stripSnippets,$useAllWords,$searchStyle,$minChars,$ajaxSearch) {
  if ($ajaxSearch) {
    $table_prefix = connectForAjax();
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
        $tbl = $table_prefix . "site_snippets";
        $snippetSql = "SELECT `$tbl`.name FROM `$tbl`;";
        $snippetRs = mysql_query($snippetSql) or die ("Cannot query the database (initSearchString)");
    } else {
        $tbl = $modx->dbConfig['dbase'] . ".`" . $modx->dbConfig['table_prefix'] . "site_snippets`";
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
            ((!$useAllWords) && (strlen($word) >= $minChars) ||
			($searchStyle == 'partial') && (strlen($word) >= $minChars) ||
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

function doSearch($searchString,$searchStyle,$useAllWords,$ajaxSearch,$docgrp) {
    $searchString = mysql_real_escape_string($searchString); // (netnoise)

    if ($ajaxSearch) {
		$table_prefix = connectForAjax();
    } else {
		global $modx;
    }
    $search = explode(" ", $searchString);
    if ($ajaxSearch) {
        $tbl_sc = "`{$table_prefix}site_content`";
        $tbl_dg = "`{$table_prefix}document_groups`";
		$tbl_stc = "`{$table_prefix}site_tmplvar_contentvalues`";
    } else {
        $tbl_sc = "{$modx->dbConfig['dbase']}.`{$modx->dbConfig['table_prefix']}site_content`";
        $tbl_dg = "{$modx->dbConfig['dbase']}.`{$modx->dbConfig['table_prefix']}document_groups`";
		$tbl_stc = "{$modx->dbConfig['dbase']}.`{$modx->dbConfig['table_prefix']}site_tmplvar_contentvalues`";
    }

    if ($docgrp) {
		$tbl_sql = " LEFT JOIN $tbl_stc stc ON sc.id = stc.contentid LEFT JOIN $tbl_dg dg ON sc.id = dg.document";
        $qry_sql = "(ISNULL(dg.document_group) OR dg.document_group IN ({$docgrp})) AND ";
    } else {
        $tbl_sql = " LEFT JOIN $tbl_stc stc ON sc.id = stc.contentid ";
        $qry_sql = "sc.privateweb = 0 AND ";
    }

	$numTerms = count($search);
    if ($searchStyle == 'partial'){
		$sql = "SELECT DISTINCT sc.id, sc.pagetitle, sc.description, sc.content, sc.introtext, sc.longtitle ";
		$sql .= "FROM $tbl_sc sc" . $tbl_sql . " WHERE ";
		if ($numTerms>1 && $useAllWords){
			$sql .= "(sc.pagetitle LIKE '%{$searchString}%' OR sc.description LIKE '%{$searchString}%' OR ";
			$sqlCounter = 1;
			$sqlContent = '(';
			$sqlIntro = '(';
			$sqlTv = '(';
			foreach ($search as $searchTerm){
				$sqlContent .= "sc.content LIKE '%{$searchTerm}%'";
				$sqlIntro .= "sc.introtext LIKE '%{$searchTerm}%'";
				$sqlTv .= "stc.value LIKE '%{$searchTerm}%'";
				$sqlCounter++;
				if ($sqlCounter > $numTerms) {
					$sqlContent .= ')';
					$sqlIntro .= ')';
					$sqlTv .= ')';
				} else {
					$sqlContent .= ' AND ';
					$sqlIntro .= ' AND ';
					$sqlTv .= ' AND ';
				}
			}
			$sql .= "{$sqlContent} OR {$sqlIntro} OR {$sqlTv}) AND ";
		} else {
			$sql .= "(";
			foreach ($search as $searchTerm){
			    $sql .= "(sc.pagetitle LIKE '%{$searchString}%' OR sc.description LIKE '%{$searchString}%' OR sc.content LIKE '%{$searchTerm}%' OR sc.introtext LIKE '%{$searchTerm}%' OR stc.value LIKE '%{$searchTerm}%') OR ";
			}
			$sql = substr_replace($sql, ') AND ', -4);
		}
		$sql .= $qry_sql . "sc.published = 1 AND sc.searchable=1 AND sc.deleted=0;";
    } else {
		$sql = "SELECT DISTINCT sc.id, sc.pagetitle, sc.description, sc.content, sc.introtext ";
		$sql .= "FROM $tbl_sc" . $tbl_sql . " sc WHERE ";
		if ($numTerms>1 && $useAllWords){
	        foreach ($search as $searchTerm){
				$sql .= "MATCH (sc.pagetitle, sc.longtitle, sc.introtext, sc.description, sc.content, stc.value) AGAINST ('{$searchTerm}') AND ";
	        }
		} else {
			$sql .= "MATCH (sc.pagetitle, sc.longtitle, sc.introtext, sc.description, sc.content, stc.value) AGAINST ('{$searchString}') AND ";
		}
		$sql .= "$qry_sql sc.published = 1 AND sc.searchable=1 AND sc.deleted=0;";
    }

    if ($ajaxSearch) {
        $rs = mysql_query($sql) or die ("Cannot query the database ({$sql})");
    } else {
        $rs = $modx->dbQuery($sql);
    }
    return $rs;
}

function PrepareSearchContent( $text, $length=200, $search ) {
    // Regular expressions of things to remove from search string
    $modRegExArray[] = '~\[\[(.*?)\]\]~';   // [[snippets]]
    $modRegExArray[] = '~\[!(.*?)!\]~';     // [!noCacheSnippets!]
    $modRegExArray[] = '!\[\~(.*?)\~\]!is'; // [~links~]
    $modRegExArray[] = '~\[\((.*?)\)\]~';   // [(settings)]
    $modRegExArray[] = '~{{(.*?)}}~';       // {{chunks}}
    $modRegExArray[] = '~\[\*(.*?)\*\]~';   // [*attributes*]

    // Remove modx sensitive tags
    foreach ($modRegExArray as $mReg){
        $text = preg_replace($mReg,'',$text);
    }
	// strips tags won't remove the actual jscript
	$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
	$text = preg_replace( '/{.+?}/', '', $text);
	// $text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2', $text );
	// replace line breaking tags with whitespace
	$text = preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text );

	return SmartSubstr( strip_tags( $text ), $length, $search );
}

/**
* returns substring of characters around a searchword
* @param string The source string
* @param int Number of chars to return
* @param string The searchword to select around
* @return string
*/
function SmartSubstr($text, $length=200, $search) {
    if (extension_loaded('mbstring')) {
        $wordpos = mb_strpos(mb_strtolower($text), mb_strtolower($search));
        $halfside = intval($wordpos - $length/2 - mb_strlen($search));
        if ($wordpos && $halfside > 0) {
           return '...' . mb_substr($text, $halfside, $length) . '...';
        } else {
           return mb_substr( $text, 0, $length) . '...';
        }
    } else {
        $wordpos = strpos(strtolower($text), strtolower($search));
        $halfside = intval($wordpos - $length/2 - strlen($search));
        if ($wordpos && $halfside > 0) {
            return '...' . substr($text, $halfside, $length) . '...';
        } else {
            return substr( $text, 0, $length) . '...';
        }
    }
}

?>
