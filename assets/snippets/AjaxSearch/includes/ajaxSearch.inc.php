<?php
/*
AjaxSearch.inc.php
Version: 1.7.0.2
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 01/03/2007
Description: Helper functions for AjaxSearch

Parts refactored and new features/fixes added by Coroico (coroico@wangba.fr)

Updated: 06/01/2008 - New version of getChildIDS from Mark Kaplan (Ditto)
Updated: 03/01/2008 - fixes : listIDs ='' means all documents
Updated: 29/12/2007 - Added fixes (1.6.2e - the strip-tags don't run for multiple searchwords)
Updated: 17/11/2007 - Added IDs document selection - 1.6.2
Updated: 06/11/2007 - Added use of character set and non-deprecated API - 1.6.1

Updated: 01/22/07 - Added Template/Lang/Mootools support
Updated: 09/18/06 - Added user permissions to searching
Updated: 01/03/2007 - Added fixes/additions from forums
*/

// The connection settings must be set for the ajax call
function connectForAjax() {
global $database_server;
  global $database_user;
  global $database_password;
  global $dbase;
  global $table_prefix;
  global $database_connection_charset;

  $database = str_replace("`","",$dbase);
  $db = mysql_connect($database_server, $database_user, $database_password) or die("Cannot connect to database (connectForAjax)");
  $selected = mysql_select_db($database, $db) or die ("Cannot select database (connectForAjax)");

  @mysql_query("SET CHARACTER SET {$database_connection_charset}");
  return $table_prefix;
}

function initSearchString($searchString,$stripHtml,$stripSnip,$stripSnippets,$useAllWords,$searchStyle,$minChars,$ajaxSearch) {
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

  // Strip HTML too
  if ($stripHtml) $searchString = stripHtml($searchString);

  // Remove modx sensitive tags
  if ($stripSnip) $searchString = stripSnip($searchString);

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
        $snippetRs = $modx->db->query($snippetSql);
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

function doSearch($searchString,$searchStyle,$useAllWords,$ajaxSearch,$docgrp,$listIDs) {
    
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

  $qry_sql =""; // listIDs ='' means all documents
  if (validListIDs($listIDs)) $qry_sql = "sc.id IN ({$listIDs}) AND ";
  
  if (validListIDs($docgrp)) {
		$tbl_sql = " LEFT JOIN $tbl_stc stc ON sc.id = stc.contentid LEFT JOIN $tbl_dg dg ON sc.id = dg.document";
    $qry_sql .= " (ISNULL(dg.document_group) OR dg.document_group IN ({$docgrp})) AND ";
  } else {
    $tbl_sql = " LEFT JOIN $tbl_stc stc ON sc.id = stc.contentid ";
    $qry_sql .= " sc.privateweb = 0 AND ";
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
    $rs = $modx->db->query($sql);
  }
  return $rs;
}

function PrepareSearchContent( $text ) {
  // Remove modx sensitive tags
  $text = stripSnip($text);
  
	// strips tags won't remove the actual jscript
	$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
	$text = preg_replace( '/{.+?}/', '', $text);
	// $text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2', $text );
	// replace line breaking tags with whitespace
	$text = preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text );
	
  $text = stripHtml($text); // strip html tags. Tags should be correctly ended

	return $text;
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
    $halfside = intval($wordpos - $length/2 + mb_strlen($search)/2);
    if ($wordpos && $halfside > 0) {
      return '...' . mb_substr($text, $halfside, $length) . '...';
    } else {
      return mb_substr( $text, 0, $length) . '...';
    }
  } else {
    $wordpos = strpos(strtolower($text), strtolower($search));
    $halfside = intval($wordpos - $length/2 + strlen($search)/2);
    if ($wordpos && $halfside > 0) {
      return '...' . substr($text, $halfside, $length) . '...';
    } else {
      return substr( $text, 0, $length) . '...';
    }
 }
}

/**
 *	stripSnip : Remove modx sensitive tags
 */
  function stripSnip($text){
    // Regular expressions of things to remove from search string
    $modRegExArray[] = '~\[\[(.*?)\]\]~';   // [[snippets]]
    $modRegExArray[] = '~\[!(.*?)!\]~';     // [!noCacheSnippets!]
    $modRegExArray[] = '!\[\~(.*?)\~\]!is'; // [~links~]
    $modRegExArray[] = '~\[\((.*?)\)\]~';   // [(settings)]
    $modRegExArray[] = '~{{(.*?)}}~';       // {{chunks}}
    $modRegExArray[] = '~\[\*(.*?)\*\]~';   // [*attributes*]
  
    // Remove modx sensitive tags
    foreach ($modRegExArray as $mReg)$text = preg_replace($mReg,'',$text);
    return $text;
}

/**
 *	stripHtml : Remove HTML sensitive tags
 */
  function stripHtml($text){
    return strip_tags($text);
}

/**
 *	validListIDs : check the validity of a value separated list of Ids
 */
function validListIDs($IDs){
  if (preg_match('/^([0-9]+,)*[0-9]+$/',$IDs) == 0) return false;
  return true;
}

// ---------------------------------------------------
// Function: getListIDs
// Get the IDs where to search
// ---------------------------------------------------

function getListIDs($IDs, $IDType, $depth) {

  if (!strlen($IDs)) return $IDs;     // listIDs ='' means all documents

    switch($IDType) {
      case "parents":
        $IDs = explode(",",$IDs);
        $listIDs = implode(',',getChildIDs($IDs, $depth));
      break;
      case "documents":
        $listIDs = $IDs;
      break;
    }
    return $listIDs;
  }

// ---------------------------------------------------
// Function: getChildIDs - From Ditto snippet by Mark Kaplan
// Get the IDs ready to be processed
// Similar to the modx version by the same name but much faster
// ---------------------------------------------------

function getChildIDs($IDs, $depth) {
	global $modx;
	$depth = intval($depth);
	$kids = array();
	$docIDs = array();
		
	if ($depth == 0 && $IDs[0] == 0 && count($IDs) == 1) {
		foreach ($modx->documentMap as $null => $document) {
			foreach ($document as $parent => $id) {
				$kids[] = $id;
			}
		}
		return $kids;
	} else if ($depth == 0) {
		$depth = 10000;
		// Impliment unlimited depth...
	}
		
	foreach ($modx->documentMap as $null => $document) {
		foreach ($document as $parent => $id) {
			$kids[$parent][] = $id;
		}
	}

	foreach ($IDs AS $seed) {
		if (!empty($kids[intval($seed)])) {
			$docIDs = array_merge($docIDs,$kids[intval($seed)]);
			unset($kids[intval($seed)]);
		}
	}
	$depth--;

	while($depth != 0) {
		$valid = $docIDs;
		foreach ($docIDs as $child=>$id) {
			if (!empty($kids[intval($id)])) {
				$docIDs = array_merge($docIDs,$kids[intval($id)]);
				unset($kids[intval($id)]);
			}
		}
		$depth--;
		if ($valid == $docIDs) $depth = 0;
	}

	return array_unique($docIDs);
}

// ---------------------------------------------------
// Function: cleanIDs - from Ditto snippet
// Clean the IDs of any dangerous characters
// ---------------------------------------------------
	
function cleanIDs($IDs) {
	//Define the pattern to search for
	$pattern = array (
		'`(,)+`', //Multiple commas
		'`^(,)`', //Comma on first position
		'`(,)$`' //Comma on last position
	);

	//Define replacement parameters
	$replace = array (
		',',
		'',
		''
	);

	//Clean startID (all chars except commas and numbers are removed)
	$IDs = preg_replace($pattern, $replace, $IDs);

	return $IDs;
}

?>
