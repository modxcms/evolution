<?php
/* -----------------------------------------------------------------------------
:: Snippet: AjaxSearch
--------------------------------------------------------------------------------
  Short Description: 
        Ajax-driven & Flexible Search form

  Version:
        1.8.1

  Date: 02/10/2008
  
  Created by:
	    Coroico (coroico@wangba.fr)
	    Jason Coward (opengeek - jason@opengeek.com)
	    Kyle Jaebker (kylej - kjaebker@muddydogpaws.com)
	    Ryan Thrash  (rthrash - ryan@vertexworks.com)
	    
	    Live Search by Thomas (Shadock)
	    Fixes & Additions by identity/Perrine/mikkelwe
	    Document selection from Ditto by Mark Kaplan
	    
  Copyright & Licencing:
  ----------------------
  GNU General Public License (GPL) (http://www.gnu.org/copyleft/gpl.html)

  Originally based on the FlexSearchForm snippet created by jaredc (jaredc@honeydewdesign.com)

--------------------------------------------------------------------------------
:: Description
--------------------------------------------------------------------------------

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

----------------------------------------------------------------------------- */
global $modx;

// ajaxSearch version being executed
define('AS_VERSION', '1.8.1');

// Path where ajaxSearch is installed
define('AS_SPATH', 'assets/snippets/ajaxSearch/');

//include snippet file
define ('AS_PATH', $modx->config['base_path'].AS_SPATH);

//------------------------------------------------------------------------------
// Configure - general AjaxSearch snippet setup options
//------------------------------------------------------------------------------
$cfg = array();

// &config [config_name | "default"] (optional)
// Load a custom configuration
// config_name - Other configs installed in the configs folder or in any folder within the MODx base path via @FILE
// Configuration files are named in the form: <config_name>.config.php
$config = (isset($config)) ? $config : "default";
$cfg['config'] = $config;
$as_config = (substr($config, 0, 5) != "@FILE") ? AS_PATH."configs/$config.config.php" : $modx->config['base_path'].trim(substr($config, 5));
if (file_exists($as_config)) include $as_config;
else return  "<h3>" .$as_config . " not found !<br />Check your config parameter or your config file name!</h3>";

// ajax Search version - Don't change!
$cfg['version'] = AS_VERSION;

// &debug = [ 0 | 1 | 2 | 3 | -1 | -2 | -3 ]
// by default: 0 - no logs
// 1,2,3 : File mode - Output logged into a file named ajaxSearch_log.txt in the ajaxSearch folder
// -1,-2,-3 : FireBug mode. The trace is logged into the Firebug console of Mozilla.
$cfg['debug'] = isset($debug)? $debug : (isset($__debug)? $__debug : 0);

// &language [ language_name | manager_language ] (optional)
// with manager_language = $modx->config['manager_language'] by default 
$cfg['language'] = isset($language) ? $language : (isset($__language) ? $__language : $modx->config['manager_language']);

// &ajaxSearch [1 | 0] (as passed in snippet variable ONLY)
// Use this to display the search results using ajax You must include the Mootools library in your template
$cfg['ajaxSearch'] = isset($ajaxSearch) ? $ajaxSearch : (isset($__ajaxSearch) ? $__ajaxSearch : 1);

// &advSearch [ 'exactphrase' | 'allwords' | 'nowords' | 'oneword' ]
// Advanced search    
// - exactphrase : provides the documents which contain the exact phrase 
// - allwords : provides the documents which contain all the words
// - nowords : provides the documents which do not contain the words
// - oneword : provides the document which contain at least one word [default]
$cfg['advSearch'] = isset($advSearch) ? $advSearch : (isset($__advSearch) ? $__advSearch : 'oneword');

// &whereSearch     
// Define where should occur the search
// a separated list of keywords describing the tables where to search
// keywords allowed : 
// "content" for site_content, "tv" for site_tmplvar_contentvalues, "jot" for jot_content, "maxigallery" for maxigallery
// you could add your own keywords. But the keyword should be a user function which describes the tables to use
// by default all the text fields are searchable but you could specify the fields like this:
// whereSearch=`content:pagetitle,introtext,content|tv:tv_value|maxigallery:gal_title`
$cfg['whereSearch'] = isset($whereSearch) ? $whereSearch : (isset($__whereSearch) ? $__whereSearch : 'content|tv');

// &subSearch  [ int , int ]
// Define the maximum number of choice and the default choice selected
// by default 5 choices and default choice 1 selected
$cfg['subSearch'] = isset($subSearch) ? $subSearch : (isset($__subSearch) ? $__subSearch : '5,1');

// &withTvs - Define which Tvs are used for the search in Tvs
// a comma separated list of TV names
// by default all TVs are used (empty list)
$cfg['withTvs'] = isset($withTvs) ? $withTvs : (isset($__withTvs) ? $__withTvs : '');

// &order - Define the sort order of results
// Comma separated list of fields defined as searchable in the table definition
// by default : 'pub_date,pagetitle'
// to suppress the sorting, use &order=``
$cfg['order'] = isset($order) ? $order : (isset($__order) ? $__order : 'publishedon,pagetitle');

// &rank - Define the rank of search results. Results are sorted by rank value
// Comma separated list of fields with optionally user defined weight
// by default : 'pagetitle:100,extract'
// to suppress the rank sorting, use &rank=``; 
// &rank sort occurs after the &order sort
$cfg['rank'] = isset($rank) ? $rank : (isset($__rank) ? $__rank : 'pagetitle:100,extract');

// &minChars [ int ]
// Minimum number of characters to require for a word to be valid for searching.
// MySQL will typically NOT search for words with less than 4 characters (relevance mode). 
// If you have $advSearch = 'allwords', 'oneword' or 'nowords' and a three or 
// fewer letter words appears in the search string, the results will always be 0. 
// Setting this drops those words from the search in THAT CIRCUMSTANCE ONLY 
// (relevance mode, advsearch = 'allwords', 'oneword' or 'nowords')
$cfg['minChars'] = isset($minChars) ? intval($minChars) : (isset($__minChars) ? intval($__minChars) : 3);

// &AS_showForm [0 | 1]
// If you would like to turn off the search form when showing results you can set this to false.(1=true, 0=false)
$cfg['AS_showForm'] = isset($AS_showForm ) ? $AS_showForm : (isset($__AS_showForm ) ? $__AS_showForm : 1);

// &resultsPage [int]
// The default behavior is to show the results on the current page, but you may define the results page any way you like. The priority is:
// 1- snippet variable - set in page template like this: [[AjaxSearch? AS_landing=int]]
//    where int is the page id number of the page you want your results on
// 2- querystring variable AS_form
// 3- variable set here
// 4- use current page
// This is VERY handy when you want to put the search form in a discrete and/or small place on your page- like a side column, but don't want all your results to show up there!
// Set to results page or leave 0 as default
$cfg['resultsPage'] = 0;

// &grabMax [ int ]
// Set to the max number of records you would like on each page. Set to 0 if unlimited.
$cfg['grabMax'] = isset($grabMax)? intval($grabMax) : (isset($__grabMax)? intval($__grabMax) : 10);

// &extract [ n:searchable fields list | 1:content,description,introtext,tv_content]
// show the search terms highlighted in a little extract
// n : maximum number of extracts displayed
// ordered searchable fields list : separated list of fields define as searchable in the table definition
// by default : 1:content,description,introtext,tv_content - One extract from content then description,introtext,tv_content 
$cfg['extract'] = isset($extract) ? $extract : (isset($__extract) ? $__extract : '1:content,description,introtext,tv_content');

// &extractLength [int]
// Length of extract around the search words found - between 50 and 800 characters
$cfg['extractLength'] = isset($extractLength) ? intval($extractLength) : (isset($__extractLength) ? intval($__extractLength) : 200);

// &extractEllips [ string ]
// Ellipside to mark the star and the end of  an extract when the sentence is cutting
// by default : '...'
$cfg['extractEllips'] = isset($extractEllips) ? $extractEllips : (isset($__extractEllips) ? $__extractEllips : '...');

// &extractSeparator [ string ]
// Any html tag to mark the separation between extracts
// by default : '<br />' but you could also choose for instance '<hr />'
$cfg['extractSeparator'] = isset($extractSeparator) ? $extractSeparator : (isset($__extractSeparator) ? $__extractSeparator : '<br />');

// &formatDate [ string ]
// The format of outputted dates. See http://www.php.net/manual/en/function.date.php
// by default : "d/m/y : H:i:s" e.g: 21/01/08 : 23:09:22
$cfg['formatDate'] = isset($formatDate) ? $formatDate : (isset($__formatDate) ? $__formatDate : "d/m/y : H:i:s");

// &highlightResult [1 | 0]
// create links so that search terms will be highlighted when linked page clicked
$cfg['highlightResult'] = isset($highlightResult) ? $highlightResult : (isset($__highlightResult) ? $__highlightResult : 1);

// &pageLinkSeparator [ string ]
// What you want, if anything, between your page link numbers
$cfg['pageLinkSeparator'] = isset($pageLinkSeparator) ? $pageLinkSeparator : (isset($__pageLinkSeparator) ? $__pageLinkSeparator : " | ");

// &AS_landing  [int] set the page to show the results page (non Ajax search)
$cfg['AS_landing'] = isset($AS_landing) ? $AS_landing : (isset($__AS_landing) ? $__AS_landing : false);

// &AS_showResults  [1 | 0]  establish whether to show the results or not
$cfg['AS_showResults'] = isset($AS_showResults) ? $AS_showResults : (isset($__AS_showResults) ? $__AS_showResults : true);

// type of IDs - (INTERNAL USE)
$cfg['idType'] = isset($documents) ? "documents" : "parents";

// &parents [ comma separated list of IDs | '' ]  
// IDs of documents to retrieve their children to &depth depth  where to do the search - - empty list by default
$cfg['parents'] = isset($parents) ? $parents : (isset($__parents) ? $__parents : '');

// &documents [ comma separated list of IDs | '' ]  
// IDs of documents where to do the search - empty list by default
$cfg['documents'] = isset($documents) ? $documents : (isset($__documents) ? $__documents : '');

// &depth [ int | 10 ] Number of levels deep to retrieve documents
$cfg['depth'] = isset($depth) ? intval($depth): (isset($__depth) ? intval($__depth) : 10);

// &hideMenu [0 | 1| 2]  Search in hidden documents from menu.
// 0 - search only in documents visible from menu
// 1 - search only in documents hidden from menu
// 2 - search in hidden or visible documents from menu [default]
$cfg['hideMenu'] = isset($hideMenu) ? $hideMenu : (isset($__hideMenu) ? $__hideMenu : 2);

// &hideLink [0 | 1 ]   Search in content of type reference (link) 
// 0 - search only in content of type document
// 1 - search in content of type document AND reference (default)
$cfg['hideLink'] = isset($hideLink) ? $hideLink : (isset($__hideLink) ? $__hideLink : 1);

// &filter - Basic filtering : remove unwanted documents that meets the criteria of the filter
// See Ditto 2 Basic filtering for more information : http://ditto.modxcms.com/tutorials/basic_filtering.html
$cfg['filter'] = isset($filter) ? $filter : (isset($__filter) ? $__filter : '');

// &tplLayout - Chunk to style the ajaxSearch input form and layout
$cfg['tplLayout'] = isset($tplLayout) ? $tplLayout : (isset($__tplLayout) ? $__tplLayout : "@FILE:".AS_SPATH.'templates/layout.tpl.html');

// &tplResults - Chunk to style the non-ajax output results outer
$cfg['tplResults'] = isset($tplResults) ? $tplResults : (isset($__tplResults) ? $__tplResults : "@FILE:".AS_SPATH.'templates/results.tpl.html');

// &tplResult - Chunk to style each output result
$cfg['tplResult'] = isset($tplResult) ? $tplResult : (isset($__tplResult) ? $__tplResult : "@FILE:".AS_SPATH.'templates/result.tpl.html');

// &tplPaging - Chunk to style the paging links
$cfg['tplPaging'] = isset($tplPaging) ? $tplPaging : (isset($__tplPaging) ? $__tplPaging : "@FILE:".AS_SPATH.'templates/paging.tpl.html');

// &stripInput - stripInput user function name
$cfg['stripInput'] = isset($stripInput) ? $stripInput : (isset($__stripInput) ? $__stripInput : 'defaultStripInput');

// &stripOutput - stripOutput user function name
$cfg['stripOutput'] = isset($stripOutput) ? $stripOutput : (isset($__stripOutput) ? $__stripOutput : 'defaultStripOutput');

// &searchWordList - searchWordList user function name
// [user_function_name,params] where params is an optional array of parameters
$cfg['searchWordList'] = isset($searchWordList) ? $searchWordList : (isset($__searchWordList) ? $__searchWordList : '');

// &breadcrumbs
// 0 : disallow the breadcrumbs link
// Name of the breadcrumbs function : allow the breadcrumbs link
// The function name could be followed by some parameter initialization
// e.g: &breadcrumbs=`Breadcrumbs,showHomeCrumb:0,showCrumbsAtHome:1`
$cfg['breadcrumbs'] = isset($breadcrumbs) ? $breadcrumbs : (isset($__breadcrumbs) ? $__breadcrumbs : 0);

// &tvPhx - Set placeHolders for TV (template variables)
// 0 : disallow the feature (default)
// 'tv:displayTV' : set up a placeholder named [+as.tvName.+] for each TV (named tvName) linked to the documents found
// displayTV is a provided ajaxSearch function which render the TV output
// tvPhx could also be used with custom tables (see examples on www.modx.wangba.fr)
$cfg['tvPhx'] = isset($tvPhx) ? $tvPhx : (isset($__tvPhx) ? $__tvPhx : 0);

// &jsClearDefault - Clearing default text
// Set this to 1 if you would like to include the clear default js function
// add the class "cleardefault" to your input text form and set this parameter
$cfg['clearDefault'] = isset($clearDefault) ? $clearDefault : (isset($__clearDefault) ? $__clearDefault : 0);

// &jsSearchInput - Location of the js library
// mandatory to protect the site against JS cross scripting attacks
$cfg['jsClearDefault'] = AS_SPATH . 'js/clearDefault.js';


//------------------------------------------------------------------------------
// Configure - Ajax mode snippet setup options
//------------------------------------------------------------------------------

if ($cfg['ajaxSearch']){  // ajax mode
    // $ajaxSearchType [1 | 0] (as passed in snippet variable ONLY)
    // Use this to display the search results using ajax
    // Set this to 1 if you would like to use the live search (i.e. results as you type)
    $cfg['ajaxSearchType'] = isset($ajaxSearchType) ? $ajaxSearchType : (isset($__ajaxSearchType) ? $__ajaxSearchType : 0);
    
    // &ajaxMax [int] - The maximum number of results to show for the ajaxsearch
    $cfg['ajaxMax'] = isset($ajaxMax) ? $ajaxMax : (isset($__ajaxMax) ? $__ajaxMax : 6);
    
    // &showMoreResults [1 | 0]
    // Set this to 1 if you would like a link to show all of the search results
    $cfg['showMoreResults'] = isset($showMoreResults) ? $showMoreResults : (isset($__showMoreResults) ? $__showMoreResults : 0);
    
    // &moreResultsPage [int]
    // The document id of the page you want the more results link to point to
    $cfg['moreResultsPage'] = isset($moreResultsPage ) ? $moreResultsPage : (isset($__moreResultsPage ) ? $__moreResultsPage : 0);
    
    // &opacity - set the opacity of the div ajaxSearch_output 
    $cfg['opacity'] = isset($opacity) ? $opacity : (isset($__opacity) ? $__opacity : 1.);
    
    // &tplAjaxResults - Chunk to style the ajax output results outer
    $cfg['tplAjaxResults'] = isset($tplAjaxResults) ? $tplAjaxResults : (isset($__tplAjaxResults) ? $__tplAjaxResults : '');
    
    // &tplAjaxResult - Chunk to style each output result
    $cfg['tplAjaxResult'] = isset($tplAjaxResult) ? $tplAjaxResult : (isset($__tplAjaxResult) ? $__tplAjaxResult : '');

    // &jScript ['jquery'|'mootools']
    // Set this to jquery if you would like use the jquery library
    // Default: mootools
    $cfg['jscript'] = isset($jscript ) ? $jscript : (isset($__jscript ) ? $__jscript : 'mootools');
    
    // &addJscript [1 | 0]
    // Set this to 1 if you would like to include or not the mootool/jquery library
    // in the header of your pages automatically.
    $cfg['addJscript'] = isset($addJscript ) ? $addJscript : (isset($__addJscript ) ? $__addJscript : 1);
    
    // &jsMootools - Location of the mootools javascript library
    $cfg['jsMooTools'] = 'manager/media/script/mootools/mootools.js';
        
    // &jsQuery - Location of the jquery javascript library
    $cfg['jsJquery'] = AS_SPATH . 'js/jQuery/jquery.js';
}

include_once AS_PATH."classes/ajaxSearch.class.inc.php";
  
if (class_exists('AjaxSearch')) {
  $as = new ajaxSearch($cfg);
  //Process ajaxSearch
  $output = $as->run();
} else {
  $output = "<h3>error: AjaxSearch class not found</h3>";
}
return $output;
?>