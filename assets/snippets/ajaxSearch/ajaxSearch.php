<?php
/*
AjaxSearch.php
Version: 1.7.1 - refactored by coroico
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 01/03/2007
Description: This code is called from the ajax request.  It returns the search results.

Updated: 06/06/2008 - Added Hidden from menu and advanced search
Updated: 01/02/2008 - Added several fixes and a security patch
Updated: 17/11/2007 - Added IDs document selection
Updated: 06/11/2007 - Encoding troubles corrected

Updated: 01/22/07 - Added templating/language/mootools support
Updated: 01/03/07 - Added fixes/updates from forum
Updated: 09/18/06 - Added user permissions to searching
Updated: 03/20/06 - All variables are set in the main snippet & snippet call
*/

define('VERSION' , '1.7.1');
define('AS_PATH' , MODX_BASE_PATH . 'assets/snippets/ajaxSearch/');

require_once(MODX_MANAGER_PATH . '/includes/protect.inc.php');

if (!isset($_POST['as_version']) || ($_POST['as_version'] != VERSION )) {
  $output = "AjaxSearch version obsolete. <br />Please check the snippet code in MODx manager.";
  echo $output;
  return;
}

// Setup the MODx API
define('MODX_API_MODE', true);
// initiate a new document parser
include_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');
$modx = new DocumentParser;

$modx->db->connect();
$modx->getSettings();

$as_version = VERSION; 
$debug = $_POST['debug'];
$as_language = basename($_POST['as_language']); // security patch
$ajaxSearch = 1;
$stripHtml = $_POST['stripHtml'];
$stripSnip = $_POST['stripSnip'];
$stripSnippets = $_POST['stripSnippets'];
$searchStyle = $_POST['searchStyle'];
$advSearch = $_POST['advSearch'];
$minChars = $_POST['minChars'];
$ajaxMax = $_POST['ajaxMax'];
$showMoreResults = $_POST['showMoreResults'];
$moreResultsPage = $_POST['moreResultsPage'];
$extract = $_POST['extract'];
$extractLength = $_POST['extractLength'];
$docgrp = $_POST['docgrp'];
$idgrp = $_POST['idgrp'];
$idType = $_POST['idType'];
$depth = $_POST['depth'];
$highlightResult = $_POST['highlightResult'];
$hideMenu = $_POST['hideMenu'];

// conversion code name between html page character encoding and Mysql character encoding
// Some others conversions should be added if needed
$pageCharset = array(
'utf8' => 'UTF-8',
'latin1' => 'ISO-8859-1',
'latin2' => 'ISO-8859-2'
);

global $database_connection_charset; // database charset

// include templates and include files
include_once AS_PATH . 'includes/ajaxSearch.inc.php';
include AS_PATH . 'includes/templates.inc.php';

// include default language file
include AS_PATH . "lang/english.inc.php";

#include other language file if set.
if($as_language != "english" && $as_language != '') {
  if(file_exists(AS_PATH . "lang/".$as_language.".inc.php"))
    include AS_PATH . "lang/".$as_language.".inc.php";
}

$result = '';
// Ajax window charset = UTF-8 and should to be coherent with database
if (isset($database_connection_charset) && isset($pageCharset[$database_connection_charset])) {
  $pgCharset = $pageCharset[$database_connection_charset];
} elseif (!isset($database_connection_charset)){
  $result = "AjaxSearch: database_connection_charset not set. Check your config file"; 
} elseif (!strlen($database_connection_charset)){
  $result = "AjaxSearch: database_connection_charset is null. Check your config file";
} else {
  $result = "AjaxSearch: unknown database_connection_charset = {$database_connection_charset}<br />Add the appropriate Html charset mapping in the ajaxSearch.php file";
  // if you get this message, simply update the $pageCharset array above with the appropriate mapping between Mysql Charset and Html charset
  // eg: 'latin2' => 'ISO-8859-2' and send me a email to update the source code
}
    
// check if the mbstring extension is required and loaded
if (!strlen($result) && $database_connection_charset != 'utf8' && !extension_loaded('mbstring')) {
  $result = "php_mbstring extension required";
}

if (!strlen($result)){ // no errors

  if (($pgCharset != 'UTF-8') && (ini_get('mbstring.encoding_translation') == '' || strtolower(ini_get('mbstring.http_input')) == 'pass')) {
    $searchString = mb_convert_encoding($_POST['search'],$pgCharset , "UTF-8");
    $needsConvert = true;
  } 
  else {
    $searchString = $_POST['search'];
    $needsConvert = false;
  }
  
  //Clean the searchString
  $searchString =  initSearchString($searchString,$stripHtml,$stripSnip,$stripSnippets,$searchStyle,$advSearch,$minChars);
  
  // Check querystring
  $validSearch = ($searchString != '')? true : false ;

  if ($validSearch) {
    // get the Ids
    $listIDs = getListIDs($idgrp, $idType, $depth);

    //Do the search
    $rs = doSearch($searchString,$searchStyle,$advSearch,$docgrp,$listIDs,$hideMenu);

    $num=mysql_numrows($rs);
    $search = explode(" ", $searchString);

    if ($num > 0) {
      $result = '';
      $i = 0;
      //Output the results
      while ($row = mysql_fetch_assoc($rs)) {
          if ($extract) {
          $highlightClass = 'ajaxSearch_highlight';
          $text=$row['content'];
          $count=1;
          $summary='';
          $text = PrepareSearchContent($text);
          foreach ($search as $searchTerm) {
            if (preg_match('/' . preg_quote($searchTerm) . '/i', $text)) {
              $toAdd = SmartSubstr( $text , $extractLength, $searchTerm );
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
          
          $resultLink = 'index.php?id='.strval($row['id']).'&amp;searched='.urlencode(htmlentities($searchString,ENT_QUOTES,$pgCharset)).'&amp;highlight='.urlencode(htmlentities($highlightClass,ENT_QUOTES,$pgCharset));
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

        $desc = stripHtml($row['description']);
        if ($desc != '') {
          $descPlaceholders = array(
            '[+as.descriptionClass+]' => 'AS_ajax_resultDescription',
            '[+as.description+]' => $desc,
          );
          $resultDesc = str_replace(array_keys($descPlaceholders),array_values($descPlaceholders),$asTemplates['descriptionWrapper']);
        } else {
          $resultDesc = '';
        }

        $resultPlaceholders = array(
          '[+as.resultClass+]' => 'AS_ajax_result',
          '[+as.resultLinkClass+]' => 'AS_ajax_resultLink',
          '[+as.resultLink+]' => $resultLink,
          '[+as.longtitle+]' => stripHtml($row['longtitle']),
          '[+as.pagetitle+]' => stripHtml($row['pagetitle']),
          '[+as.description+]' => $resultDesc,
          '[+as.extract+]' => $resultExtract,          
        );
        $result .= str_replace(array_keys($resultPlaceholders),array_values($resultPlaceholders),$asTemplates['result']);

        if (++$i == $ajaxMax) {
          //If more than max results so link to all results
          if ($showMoreResults) {
            $morePlaceholders = array(
              '[+as.moreClass+]' => 'AS_ajax_more',
              '[+as.moreLink+]' => 'index.php?id='.$moreResultsPage.'&amp;AS_search='.urlencode(htmlentities($searchString,ENT_QUOTES,$pgCharset)),
              '[+as.moreTitle+]' => $_lang['as_moreResultsTitle'],
              '[+as.moreText+]' => $_lang['as_moreResultsText'],
            );
          
            $result .= str_replace(array_keys($morePlaceholders),array_values($morePlaceholders),$asTemplates['ajax_more_results']);
          }
          break;
        }
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
  
  // UTF-8 conversion is required if mysql character set is different of 'utf8'
  if ($needsConvert) $result = mb_convert_encoding($result,"UTF-8",$pgCharset);
  
}

echo $result;
?>
