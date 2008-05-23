<?php
/* -------------------------------------------------------------
:: Snippet: ajaxSearch
----------------------------------------------------------------
  Short Description: 
        Ajax-driven & Flexible Search form

  Version:
        1.7.1

  Created by:
      Jason Coward (opengeek - jason@opengeek.com)
      Kyle Jaebker (kylej - kjaebker@muddydogpaws.com)
      Ryan Thrash  (rthrash - ryan@vertexworks.com)
      
      Live Search by Thomas (Shadock)
      Fixes & Additions by identity/Perrine/mikkelwe
      Document selection from Ditto by Mark Kaplan

      Parts refactored and new features/fixes added by Coroico (coroico@wangba.fr)

  Copyright & Licencing:
  ----------------------
  GNU General Public License (GPL) (http://www.gnu.org/copyleft/gpl.html)

  Originally based on the FlexSearchForm snippet created by jaredc (jaredc@honeydewdesign.com)

----------------------------------------------------------------
:: Description
----------------------------------------------------------------

    The AjaxSearch snippet is an enhanced version of the original FlexSearchForm
    snippet for MODx. This snippet adds AJAX functionality on top of the robust 
    content searching.

    - search in title, description, content and TVs of documents
    - search in a subset of documents
    - highlighting of searchword in the results returned

    It could works in two modes:

    ajaxSearch mode : 
    - Search results displayed in current page through AJAX request
    - Multiple search options including live search and non-AJAX option
    - Available link to view all results in a new page (FSF) when only a subset is retuned
    - Customize the number of results returned
    - Uses the MooTools js library for AJAX and visual effects

    non-ajaxSearch mode (FSF) :
    - Search results displayed in a new page
    - customize the paginating of results
    - works without JS enabled as FlexSearchForm
    - designed to load only the required FSF code


MORE : See the ajaxSearch.readme.txt file for more informations

---------------------------------------------------------------- */

global $modx;

define('VERSION', "1.7.1");

// check version files
if (!defined ('AS_VERSION')){
  $output = '<h3>AjaxSearch version is not defined. <br />Please check the snippet code in MODx manager.</h3>'; 
  return;
}
elseif (AS_VERSION != VERSION) {
  $output = '<h3>AjaxSearch version is obsolete. <br />Please check the snippet code in MODx manager.</h3>';
  return;
}
elseif (!defined ('AS_PATH')){
  $output = 'AjaxSearch setup path is not defined. <br />Please check the snippet code in MODx manager.</h3>'; 
  return;
}

//-------------------------------------------------------------------------------------------------
// CONFIGURE - GENERAL SNIPPET SETUP OPTIONS
//-------------------------------------------------------------------------------------------------

// ajax Search version - Don't change!
$as_version = VERSION;

// $debug [1 | 0 ] (optional) - Output debugging information -- FOR FUTURE USAGE
$debug = isset($debug)? $debug : 0;

// $language [ language_name | manager_language ] (optional)
// with manager_language = $modx->config['manager_language'] by default 
$language = isset($language) ? $language : $modx->config['manager_language'];

// $ajaxSearch [1 | 0] (as passed in snippet variable ONLY)
// Use this to display the search results using ajax You must include the Mootools library in your template
$ajaxSearch = isset($ajaxSearch) ? $ajaxSearch : 1;

// $searchStyle [ 'relevance' | 'partial' ]
// This option allows you to decide to use a faster, relevance sorted search ('relevance')
// which WILL NOT inlclude partial matches. Or use a slower, but more inclusive search ('partial') 
// that will include partial matches. Results will NOT be sorted based on relevance.
$searchStyle = isset($searchStyle) ? $searchStyle : 'partial';

// $advSearch [ 'exactphrase' | 'allwords' | 'nowords' | 'oneword' ]
// Advanced search    
// - exactphrase : provides the documents which contain the exact phrase 
// - allwords : provides the documents which contain all the words
// - nowords : provides the documents which do not contain the words
// - oneword : provides the document which contain at least one word [default]
$advSearch = isset($advSearch) ? $advSearch : 'oneword';

// $stripHtml [ 0 | 1 ]
// Allow HTML characters in the search? Probably not.
$stripHtml = 1;

// $stripSnip [ 0 | 1 ]
// Strip out snippet calls etc from the search string?
$stripSnip = 1;

// $stripSnippets [ 0 | 1 ]
// Strip out snippet names so users will not be able to "search" to see what snippets
// are used in your content. This is a security benefit, as users will not be able
// to search for what pages use specific snippets.
$stripSnippets = 1;

// $minChars [ int ]
// Minimum number of characters to require for a word to be valid for searching.
// MySQL will typically NOT search for words with less than 4 characters (relevance mode). 
// If you have $advSearch = 'allwords', 'oneword' or 'nowords' and a three or 
// fewer letter words appears in the search string, the results will always be 0. 
// Setting this drops those words from the search in THAT CIRCUMSTANCE ONLY 
// (relevance mode, advsearch = 'allwords', 'oneword' or 'nowords')
$minChars = isset($minChars) ? $minChars : 4;

// $AS_showForm [0 | 1]
// If you would like to turn off the search form when showing results you can set this to false.(1=true, 0=false)
$AS_showForm = isset($AS_showForm ) ? $AS_showForm : 1;

// $resultsPage [int]
// The default behavior is to show the results on the current page, but you may define the results page any way you like. The priority is:
// 1- snippet variable - set in page template like this: [[AjaxSearch? AS_landing=int]]
//    where int is the page id number of the page you want your results on
// 2- querystring variable AS_form
// 3- variable set here
// 4- use current page
// This is VERY handy when you want to put the search form in a discrete and/or small place on your page- like a side column, but don't want all your results to show up there!
// Set to results page or leave 0 as default
$resultsPage = 0;

// $grabMax [ int ]
// Set to the max number of records you would like on each page. Set to 0 if unlimited.
$grabMax = isset($grabMax)? $grabMax : 10;

// $extract [1 | 0]
// show the search terms highlighted in a little extract (like Google)
$extract = isset($extract) ? $extract : 1;

// $extractLength [int]
// Length of extract around the search words found - between 50 and 800 characters
$extractLength = isset($extractLength) ? $extractLength : 200;

// $highlightResult [1 | 0]
// create links so that search terms will be highlighted when linked page clicked
$highlightResult = isset($highlightResult) ? $highlightResult : 1;

// $pageLinkSeparator [ string ]
// What you want, if anything, between your page link numbers
$pageLinkSeparator = " | ";

// set the page to show the results page (non Ajax search) 
$AS_landing = isset($AS_landing) ? $AS_landing : 0;

// establish whether to show the results or not
$AS_showResults = isset($AS_showResults) ? $AS_showResults : true;

// type of IDs - parents or documents
$idType = isset($documents) ? "documents" : "parents";

// IDs of documents to retrieve their children to &depth depth  where to do the search
$parents = isset($parents) ? $parents : false;
 
// IDs of documents where to do the search
$documents = isset($documents) ? $documents : false;

// Number of levels deep to retrieve documents
$depth = isset($depth) ? $depth : 10;

// Search in hidden documents from menu. [0 | 1 | 2]
// 0 - search only in documents visible from menu
// 1 - search only in documents hidden from menu
// 2 - search in hidden or visible documents from menu [default]
$hideMenu = isset($hideMenu) ? $hideMenu : 2;

//-------------------------------------------------------------------------------------------------
// CONFIGURE - Ajax SNIPPET SETUP OPTIONS
//-------------------------------------------------------------------------------------------------

// $ajaxSearchType [1 | 0] (as passed in snippet variable ONLY)
// Use this to display the search results using ajax
// Set this to 1 if you would like to use the live search (i.e. results as you type)
$ajaxSearchType = isset($ajaxSearchType) ? $ajaxSearchType : 0;

// $ajaxMax [int] - The maximum number of results to show for the ajaxsearch
$ajaxMax = isset($ajaxMax) ? $ajaxMax : 6;

// $showMoreResults [1 | 0]
// Set this to 1 if you would like a link to show all of the search results
$showMoreResults = isset($showMoreResults) ? $showMoreResults : 0;

// $moreResultsPage [int]
// The document id of the page you want the more results link to point to
$moreResultsPage = isset($moreResultsPage ) ? $moreResultsPage : 0;

// set the opacity of the div ajaxSearch_output 
$opacity = isset($opacity) ? $opacity : 1.;

// $addJscript [1 | 0]
// Set this to 1 if you would like to include the mootool librairy in the
// header of your pages automatically.
$addJscript = isset($addJscript ) ? $addJscript : 1;

//Location of the mootools javascript library
$jsMooTools = 'manager/media/script/mootools/mootools.js';

// $config [config_name | "default"] (optional)
// Load a custom configuration
// config_name - Other configs installed in the configs folder or in any folder within the MODx base path via @FILE
// Configuration files are named in the form: <config_name>.config.php
$config = (isset($config)) ? $config : "default";
$config = (substr($config, 0, 5) != "@FILE") ? AS_PATH."configs/$config.config.php" : $modx->config['base_path'].trim(substr($config, 5));
if (file_exists($config))include_once $config;

// End configure

//-------------------------------------------------------------------------------------------------
//  SNIPPET LOGIC CODE STARTS HERE
//-------------------------------------------------------------------------------------------------

Define('EXTRACT_MIN',50);
Define('EXTRACT_MAX',800);

// include include and templates files
include_once AS_PATH."includes/ajaxSearch.inc.php";
include AS_PATH."includes/templates.inc.php";

// include default language file
$as_language = 'english';
include AS_PATH."lang/{$as_language}.inc.php";

// include other language file if set
if(($language != '') && ($language != $as_language)) {
  if(file_exists(AS_PATH."lang/{$language}.inc.php"))
    include AS_PATH."lang/".$language.".inc.php";
    $as_language = $language;
}

// check some parameters
if ($extractLength < EXTRACT_MIN) $extractLength = EXTRACT_MIN;
if ($extractLength > EXTRACT_MAX) $extractLength = EXTRACT_MAX;
if ($opacity < 0.) $opacity = 0.;
if ($opacity > 1.) $opacity = 1.;
if (($hideMenu != 0) and ($hideMenu != 1) and ($hideMenu != 2)) $hideMenu = 2;

$liveSearch = $ajaxSearchType;

// Internal variable which holds the set of IDs where to look for
$idgrp = ($idType == "parents") ? $parents : $documents;

// establish results page
if (isset($AS_landing)) { // set in snippet
  $searchAction = "[~".$AS_landing."~]";
} elseif ($resultsPage > 0) { // locally set
  $searchAction = "[~".$resultsPage."~]";
} else { //otherwise
  $searchAction = "[~".$modx->documentIdentifier."~]";
}

// Initialize search string
$searchString = '';

// CLEAN SEARCH STRING
if ( isset($_POST['search']) || isset($_GET['AS_search']) || isset($_GET['FSF_search'])) {
  // Prefer post to get
  if (isset($_POST['search'])) {
    $searchString = $_POST['search'];
  } elseif (isset($_GET['AS_search'])) {
    $searchString = html_entity_decode($_GET['AS_search']);
  } else {
    // Code to make tag cloud snippet work with this search
    $searchString = html_entity_decode($_GET['FSF_search']);
  }

  //**********************************************************************************************************************
  $searchString = initSearchString($searchString,$stripHtml,$stripSnip,$stripSnippets,$searchStyle,$advSearch,$minChars);
  //**********************************************************************************************************************
} // End cleansing search string

// check querystring
$validSearch = ($searchString != '')? true : false ;

//check for offset
$offset = (isset($_GET['AS_offset']))? $_GET['AS_offset'] : 0;

// initialize output
$SearchForm = '';
$introMessage = '';

if ($docgrp = $modx->getUserDocGroups()) {
  $docgrp = implode(",", $docgrp);
}

if ($ajaxSearch) {
  $searchFormId = 'id="ajaxSearch_form" ';
  //Adding the javascript libraries to the header
  if ($addJscript) {
    $modx->regClientStartupScript($jsMooTools);
  }
  $jsInclude = AS_SPATH.'js/ajaxSearch.js';
  $modx->regClientStartupScript($jsInclude);

  $jsVars = <<<EOD
<!-- start AjaxSearch header -->
<script type="text/javascript">
as_version = '$as_version';
debug = $debug;
as_language = '$as_language';
opacity = $opacity;
stripHtml = $stripHtml;
stripSnip = $stripSnip;
stripSnippets = $stripSnippets;
searchStyle = '$searchStyle';
advSearch = '$advSearch';
minChars = $minChars;
ajaxMax = $ajaxMax;
showMoreResults = $showMoreResults;
moreResultsPage = $moreResultsPage;
extract = $extract;
extractLength = $extractLength;
liveSearch = $liveSearch;
docgrp = '$docgrp';
idgrp = '$idgrp';
idType = '$idType';
depth = '$depth';
highlightResult = $highlightResult;
hideMenu = $hideMenu;
</script>
<!-- end AjaxSearch header -->
EOD;

  $modx->regClientStartupScript($jsVars);
} else {
  $searchFormId = '';
}

// establish form
if (($validSearch && $AS_showForm) || $AS_showForm){
  $formPlaceholders = array(
    '[+as.formId+]' => $searchFormId,
    '[+as.formAction+]' => $searchAction,
    '[+as.inputValue+]' => ($searchString == '' && $_lang['as_boxText'] != '') ? $_lang['as_boxText'] : $searchString,
    '[+as.inputOptions+]' => ($_lang['as_boxText']) ? ' onfocus="this.value=(this.value==\''.$_lang['as_boxText'].'\')? \'\' : this.value ;"' : '',
    '[+as.submitText+]' => $_lang['as_searchButtonText'],
  );

  $finalSearchForm = str_replace( array_keys( $formPlaceholders ), array_values( $formPlaceholders ), $asTemplates['form'] );
} else {
  $finalSearchForm = '';
}

$finalResults = '';
if ($AS_showResults) {
  if ($validSearch) {
    // get the Ids
    $listIDs = getListIDs($idgrp, $idType, $depth);
    // get the record set with results
   $rs = doSearch($searchString,$searchStyle,$advSearch,$docgrp,$listIDs,$hideMenu);

   $limit = $modx->recordCount($rs);
   $search = explode(" ", $searchString);
    if($limit > 0) {
      // pagination
      if ($grabMax > 0){
        $numResultPages = ceil($limit/$grabMax);
        $resultPagingText = ($limit>$grabMax) ? $_lang['as_paginationTextMultiplePages'] : $_lang['as_paginationTextSinglePage'] ;
        $resultPageLinkNumber = 1;
        $resultPageLinks = '';
        for ( $nrp = 0; $nrp < $limit && $limit > $grabMax; $nrp += $grabMax ){
          if ($offset == ($resultPageLinkNumber-1)*$grabMax){
            $resultPageUrl = $resultPageLinkNumber;
            $usePageTemplate = 'pagingLinksCurrent';
          } else {
            $resultPageUrl = $modx->makeUrl($modx->documentIdentifier, '', 'AS_offset=' . $nrp . '&AS_search=' . urlencode($searchString));
            $usePageTemplate = 'pagingLinks';
          }

          $useSeperator = ($nrp + $grabMax < $limit) ? $pageLinkSeparator : '' ;

          $pageLinkPlaceholders = array(
            '[+as.pagingLink+]' => $resultPageUrl,
            '[+as.pagingText+]' => $resultPageLinkNumber,
            '[+as.pagingSeperator+]' => $useSeperator,
          );

          $resultPageLinks .= str_replace(array_keys($pageLinkPlaceholders),array_values($pageLinkPlaceholders),$asTemplates[$usePageTemplate]);
          $resultPageLinkNumber++;
        }

        $pageLinkPlaceholders = array(
          '[+as.pagingText+]' => $resultPagingText,
          '[+as.pagingLinks+]' => $resultPageLinks,
        );

        $resultPageLinksFinal = str_replace(array_keys($pageLinkPlaceholders),array_values($pageLinkPlaceholders),$asTemplates['pagingLinksOuter']);

        $resultsFoundText = ($limit > 1)? $_lang['as_resultsFoundTextMultiple'] : $_lang['as_resultsFoundTextSingle'] ;
        if ($extract) {
          $hits=1;
          $searchwords='';
          foreach ($search as $words) {
            $searchwords .= '<span class="ajaxSearch_highlight ajaxSearch_highlight'.$hits.'">'.$words.'</span>&nbsp;';
            $hits++;
          }
          // Remove trailing '&nbsp;'
          $searchwords = substr($searchwords, 0, strlen($searchwords) -6);
          $resultsFoundText = sprintf($resultsFoundText,$limit,$searchwords);
        } else {
          $resultsFoundText = sprintf($resultsFoundText,$limit,$searchString);
        }

        $resultInfoPlaceholders = array(
          '[+as.resultInfoText+]' => $resultsFoundText,
        );

        $resultInfo = str_replace(array_keys($resultInfoPlaceholders),array_values($resultInfoPlaceholders),$asTemplates['resultsInfo']);    
      } // end if grabMax

      // search results
      $useLimit = ($grabMax > 0)? $offset+$grabMax : $limit;
      $allResults = '';
      for ($y = $offset; ($y < $useLimit) && ($y<$limit); $y++) {
        $moveToRow = mysql_data_seek($rs,$y);
        $SearchFormsrc=$modx->db->getRow($rs);
        if ($extract) {
          $highlightClass = 'ajaxSearch_highlight';
          $text=$SearchFormsrc['content'];
          $count=1;
          $summary='';
          $text = PrepareSearchContent( $text );
          foreach ($search as $searchTerm) {
            if (preg_match('/' . preg_quote($searchTerm) . '/i', $text)) {
              $toAdd = SmartSubstr( $text , $extractLength, $searchTerm );
              $summary .= preg_replace( '/' . preg_quote( $searchTerm, '/' ) . '/i', '<span class="ajaxSearch_highlight ajaxSearch_highlight'.$count.'">\0</span>', $toAdd ) . ' ';
            }
            $highlightClass .= ' ajaxSearch_highlight'.$count;
            $count++;
          }
          $text=$summary;
        }
        
        if ($highlightResult) {
          if (!$extract) {
            $highlightClass = 'ajaxSearch_highlight';
            $count=1;
            foreach ($search as $searchTerm) {
              $highlightClass .= ' ajaxSearch_highlight'.$count;
              $count++;
            }
          }
        
          $searchFormLink = $modx->makeUrl($SearchFormsrc['id'],'','searched='.urlencode($searchString).'&amp;highlight='.urlencode($highlightClass));
        } else {
          $searchFormLink = $modx->makeUrl($SearchFormsrc['id']);
        }

        if ($extract) {
          $extractPlaceholders = array(
            '[+as.extractClass+]' => 'ajaxSearch_extract',
            '[+as.extract+]' => $text,
          );
          $resultExtract = str_replace(array_keys($extractPlaceholders),array_values($extractPlaceholders),$asTemplates['extractWrapper']);
        } else {
          $resultExtract = '';
        }

        $desc = stripHtml($SearchFormsrc['description']);
        if ($desc != '') {
          $descPlaceholders = array(
            '[+as.descriptionClass+]' => 'ajaxSearch_resultDescription',
            '[+as.description+]' => $desc,
          );
          $resultDesc = str_replace(array_keys($descPlaceholders),array_values($descPlaceholders),$asTemplates['descriptionWrapper']);
        } else {
          $resultDesc = '';
        }

        $resultPlaceholders = array(
          '[+as.resultClass+]' => 'ajaxSearch_result',
          '[+as.resultLinkClass+]' => 'ajaxSearch_resultLink',
          '[+as.resultLink+]' => $searchFormLink,
          '[+as.longtitle+]' => stripHtml($SearchFormsrc['longtitle']),
          '[+as.pagetitle+]' => stripHtml($SearchFormsrc['pagetitle']),
          '[+as.description+]' => $resultDesc,
          '[+as.extract+]' => $resultExtract,
        );
        
        $allResults .= str_replace(array_keys($resultPlaceholders),array_values($resultPlaceholders),$asTemplates['result']);
      }

      $finalPlaceholders = array(
        '[+as.results+]' => $allResults,
        '[+as.paging+]' => $resultPageLinksFinal,
        '[+as.resultInfo+]' => $resultInfo,
      );

      $finalResults .= str_replace(array_keys($finalPlaceholders),array_values($finalPlaceholders),$asTemplates['no_ajax_outer']);
    } else {
      $noResultsPlaceholder = array(
        '[+as.noResultClass+]' => 'ajaxSearch_resultsIntroFailure',
        '[+as.noResultText+]' => $_lang['as_resultsIntroFailure'],
      );
      $finalResults .= str_replace(array_keys($noResultsPlaceholder),array_values($noResultsPlaceholder),$asTemplates['noResults']);
    } // end if records found
  } // end if validSearch
  else if (!$validSearch && isset($_POST['sub'])) {
    // message to show if search was performed but for something invalid
    $noResultsPlaceholder = array(
      '[+as.noResultClass+]' => 'ajaxSearch_resultsIntroFailure',
      '[+as.noResultText+]' => $_lang['as_resultsIntroFailure'],
    );
    $finalResults .= str_replace(array_keys($noResultsPlaceholder),array_values($noResultsPlaceholder),$asTemplates['noResults']);
  }
  else { // init the input field
    $introMessage = str_replace('[+as.introMessage+]',$_lang['as_introMessage'],$asTemplates['introMessage']);
  } // end if not validSearch
} // end if showResults

if ($ajaxSearch) {
  $finalResults = '<div id="ajaxSearch_output" style="opacity:0;filter:alpha(opacity=0);-moz-opacity:0.;" > </div>';
  $introMessage = '';
}

$finalPlaceholders = array(
  '[+as.form+]' => $finalSearchForm,
  '[+as.intro+]' => $introMessage,
  '[+as.results+]' => $finalResults,
);

$output .= str_replace(array_keys($finalPlaceholders),array_values($finalPlaceholders),$asTemplates['layout']);
return;
?>