/* -----------------------------------------------------------------------------
:: Snippet: AjaxSearch
--------------------------------------------------------------------------------
  Short Description: 
        Ajax-driven & Flexible Search form

  Version:
        1.8.3

  Date: 08/06/2009

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
    - Available link to view all results in a new page when only a subset is retuned
    - Customize the number of results returned
    - Uses the MooTools js library for AJAX and visual effects

    non-ajaxSearch mode :
    - Search results displayed in a new page
    - customize the paginating of results
    - works without JS enabled as FlexSearchForm
    - designed to load only the required FSF code


MORE : See the ajaxSearch.readme.txt file for more informations

----------------------------------------------------------------------------- */
global $modx;

// ajaxSearch version being executed
define('AS_VERSION', '1.8.3');

// Path where ajaxSearch is installed
define('AS_SPATH', 'assets/snippets/ajaxSearch/');

//include snippet file
define ('AS_PATH', $modx->config['base_path'].AS_SPATH);

//------------------------------------------------------------------------------
// Configure - general AjaxSearch snippet setup options
//------------------------------------------------------------------------------

// Load the default configuration $dcfg to get the default values
$as_default = AS_PATH . 'configs/default.config.php';
if (file_exists($as_default)) include $as_default;
else return  "<h3> $as_default not found !<br />Check the existing of this file!</h3>";
if (!isset($dcfg)) return  "<h3> default configuration array not defined in $as_default!<br /> Check the content of this file!</h3>";

$cfg = array();  // final configuration

// Load a custom configuration file if required
// config_name - Other config installed in the configs folder or in any folder within the MODx base path via @FILE
// Configuration files should be named in the form: <config_name>.config.php
// Default: '' - no custom config
$cfg['config'] = isset($config) ? $config : $dcfg['config'];
if ($cfg['config']){
  $config = $cfg['config'];
  $as_config = (substr($config, 0, 5) != "@FILE") ? AS_PATH."configs/$config.config.php" : $modx->config['base_path'].trim(substr($config, 5));
  if (file_exists($as_config)) include $as_config;
  else return  "<h3>" .$as_config . " not found !<br />Check your config parameter or your config file name!</h3>";
}

// &debug = [ 0 | 1 | 2 | 3 | -1 | -2 | -3 ]
// 1,2,3 : File mode - Output logged into a file named ajaxSearch_log.txt in the ajaxSearch folder
// -1,-2,-3 : FireBug mode. The trace is logged into the Firebug console of Mozilla.
// Default: 0 - no logs
$cfg['debug'] = isset($debug)? $debug : (isset($__debug)? $__debug : $dcfg['debug']);

// &language [ language_name | manager_language ] (optional)
// Default: $modx->config['manager_language'] - manager language used 
$cfg['language'] = isset($language) ? $language : (isset($__language) ? $__language : $dcfg['language']);

// &ajaxSearch [1 | 0] (as passed in snippet variable ONLY)
// Use this to display the search results using ajax You must include the Mootools library in your template
// Default: 1 - ajax mode selected
$cfg['ajaxSearch'] = isset($ajaxSearch) ? $ajaxSearch : (isset($__ajaxSearch) ? $__ajaxSearch : $dcfg['ajaxSearch']);

// &advSearch [ 'exactphrase' | 'allwords' | 'nowords' | 'oneword' ]
// Advanced search:    
// - exactphrase : provides the documents which contain the exact phrase 
// - allwords : provides the documents which contain all the words
// - nowords : provides the documents which do not contain the words
// - oneword : provides the document which contain at least one word
// Default: 'oneword'
$cfg['advSearch'] = isset($advSearch) ? $advSearch : (isset($__advSearch) ? $__advSearch : $dcfg['advSearch']);

// &whereSearch     
// Define where should occur the search
// a separated list of keywords describing the tables where to search
// keywords allowed : 
// "content" for site_content, "tv" for site_tmplvar_contentvalues, "jot" for jot_content, "maxigallery" for maxigallery
// you could add your own keywords. But the keyword should be a user function which describes the tables to use
// all the text fields are searchable but you could specify the fields like this:
// whereSearch=`content:pagetitle,introtext,content|tv:tv_value|maxigallery:gal_title`
// Default: 'content|tv' 
$cfg['whereSearch'] = isset($whereSearch) ? $whereSearch : (isset($__whereSearch) ? $__whereSearch : $dcfg['whereSearch']);

// &subSearch  [ int , int ]
// Define the maximum number of choice and the default choice selected
// Default: '5,1' - 5 choices and default choice 1 selected
$cfg['subSearch'] = isset($subSearch) ? $subSearch : (isset($__subSearch) ? $__subSearch : $dcfg['subSearch']);

// &withTvs - Define which Tvs are used for the search in Tvs
// a comma separated list of TV names
// Default: '' - all TVs are used (empty list)
$cfg['withTvs'] = isset($withTvs) ? $withTvs : (isset($__withTvs) ? $__withTvs : $dcfg['withTvs']);

// &order - Define the sort order of results
// Comma separated list of fields defined as searchable in the table definition
// to suppress the sorting, use &order=``
// Default: 'pub_date,pagetitle'
$cfg['order'] = isset($order) ? $order : (isset($__order) ? $__order : $dcfg['order']);

// &rank - Define the rank of search results. Results are sorted by rank value
// Comma separated list of fields with optionally user defined weight
// Default: 'pagetitle:100,extract'
// to suppress the rank sorting, use &rank=``; 
// &rank sort occurs after the &order sort
$cfg['rank'] = isset($rank) ? $rank : (isset($__rank) ? $__rank : $dcfg['rank']);

// &maxWords [ 1 < int < 10 ]
// Maximum number of words for searching
// Default: 5
$cfg['maxWords'] = isset($maxWords) ? intval($maxWords) : (isset($__maxWords) ? intval($__maxWords) : $dcfg['maxWords']);

// &minChars [  2 < int < 100 ]
// Minimum number of characters to require for a word to be valid for searching.
// length of each word with $advSearch = 'allwords', 'oneword' or 'nowords'
// length of the search string with possible spaces with $advSearch = 'exactphrase'
// Default: 3
$cfg['minChars'] = isset($minChars) ? intval($minChars) : (isset($__minChars) ? intval($__minChars) : $dcfg['minChars']);

// &AS_showForm [0 | 1]
// If you would like to turn off the search form when showing results you can set this to false.(1=true, 0=false)
// Default: 1
$cfg['AS_showForm'] = isset($AS_showForm ) ? $AS_showForm : (isset($__AS_showForm ) ? $__AS_showForm : $dcfg['AS_showForm']);

// &resultsPage [int]
// The default behavior is to show the results on the current page, but you may define the results page any way you like. The priority is:
// 1- snippet variable - set in page template like this: [[AjaxSearch? AS_landing=int]]
//    where int is the page id number of the page you want your results on
// 2- querystring variable AS_form
// 3- variable set here
// 4- use current page
// This is VERY handy when you want to put the search form in a discrete and/or small place 
// on your page - like a side column, but don't want all your results to show up there!
// Set to results page or leave 0 as default
$cfg['resultsPage'] = $dcfg['resultsPage'];

// &grabMax [ int ]
// Set to the max number of records you would like on each page. Set to 0 if unlimited.
// Default: 10
$cfg['grabMax'] = isset($grabMax)? intval($grabMax) : (isset($__grabMax)? intval($__grabMax) : $dcfg['grabMax']);

// &extract [ n:searchable fields list | 1:content,description,introtext,tv_content]
// show the search terms highlighted in a little extract
// n : maximum number of extracts displayed
// ordered searchable fields list : separated list of fields define as searchable in the table definition
// Default: '1:content,description,introtext,tv_value' - One extract from content then description,introtext,tv_value 
$cfg['extract'] = isset($extract) ? $extract : (isset($__extract) ? $__extract : $dcfg['extract']);

// &extractLength [ 50 < int < 800]
// Length of extract around the search words found - between 50 and 800 characters
// Default: 200
$cfg['extractLength'] = isset($extractLength) ? intval($extractLength) : (isset($__extractLength) ? intval($__extractLength) : $dcfg['extractLength']);

// &extractEllips [ string ]
// Ellipside to mark the star and the end of  an extract when the sentence is cutting
// Default: '...'
$cfg['extractEllips'] = isset($extractEllips) ? $extractEllips : (isset($__extractEllips) ? $__extractEllips : $dcfg['extractEllips']);

// &extractSeparator [ string ]
// Any html tag to mark the separation between extracts
// Default: '<br />' - but you could also choose for instance '<hr />'
$cfg['extractSeparator'] = isset($extractSeparator) ? $extractSeparator : (isset($__extractSeparator) ? $__extractSeparator : $dcfg['extractSeparator']);

// &formatDate [ string ]
// The format of outputted dates. See http://www.php.net/manual/en/function.date.php
// Default: 'd/m/y : H:i:s' - e.g: 21/01/08 : 23:09:22
$cfg['formatDate'] = isset($formatDate) ? $formatDate : (isset($__formatDate) ? $__formatDate : $dcfg['formatDate']);

// &highlightResult [1 | 0]
// create links so that search terms will be highlighted when linked page clicked
// Default: 1 - Results highlighted
$cfg['highlightResult'] = isset($highlightResult) ? $highlightResult : (isset($__highlightResult) ? $__highlightResult : $dcfg['highlightResult']);

// &pageLinkSeparator [ string ]
// What you want, if anything, between your page link numbers
// Default: ' | ' 
$cfg['pageLinkSeparator'] = isset($pageLinkSeparator) ? $pageLinkSeparator : (isset($__pageLinkSeparator) ? $__pageLinkSeparator : $dcfg['pageLinkSeparator']);

// &showPagingAlways[1 | 0]
// Determine whether or not to always show paging
$cfg['showPagingAlways'] = isset($showPagingAlways) ? $showPagingAlways : (isset($__showPagingAlways) ? $__showPagingAlways : $dcfg['showPagingAlways']);

// &AS_landing  [int] set the page to show the results page (non Ajax search)
// Default: false
$cfg['AS_landing'] = isset($AS_landing) ? $AS_landing : (isset($__AS_landing) ? $__AS_landing : $dcfg['AS_landing']);

// &AS_showResults  [1 | 0]  establish whether to show the results or not
// Default: 1
$cfg['AS_showResults'] = isset($AS_showResults) ? $AS_showResults : (isset($__AS_showResults) ? $__AS_showResults : $dcfg['AS_showResults']);

// &parents [ comma separated list of IDs | '' ]  
// IDs of documents to retrieve their children to &depth depth  where to do the search
// Default: '' - empty list
$cfg['parents'] = isset($parents) ? $parents : (isset($__parents) ? $__parents : $dcfg['parents']);

// &documents [ comma separated list of IDs | '' ]  
// IDs of documents where to do the search
// Default: '' - empty list
$cfg['documents'] = isset($documents) ? $documents : (isset($__documents) ? $__documents : $dcfg['documents']);

// &depth [ 0 < int ] Number of levels deep to retrieve documents
// Default: 10
$cfg['depth'] = isset($depth) ? intval($depth): (isset($__depth) ? intval($__depth) : $dcfg['depth']);

// &hideMenu [0 | 1| 2]  Search in hidden documents from menu.
// 0 - search only in documents visible from menu
// 1 - search only in documents hidden from menu
// 2 - search in hidden or visible documents from menu
// Default: 2
$cfg['hideMenu'] = isset($hideMenu) ? $hideMenu : (isset($__hideMenu) ? $__hideMenu : $dcfg['hideMenu']);

// &hideLink [0 | 1 ]   Search in content of type reference (link) 
// 0 - search only in content of type document
// 1 - search in content of type document AND reference
// Default: 1
$cfg['hideLink'] = isset($hideLink) ? $hideLink : (isset($__hideLink) ? $__hideLink : $dcfg['hideLink']);

// &filter - Basic filtering : remove unwanted documents that meets the criteria of the filter
// See Ditto 2 Basic filtering for more information
// Default: '' - empty list
$cfg['filter'] = isset($filter) ? $filter : (isset($__filter) ? $__filter : $dcfg['filter']);

// &tplLayout - Chunk to style the ajaxSearch input form and layout
// Default: '@FILE:' . AS_SPATH . 'templates/layout.tpl.html'
$cfg['tplLayout'] = isset($tplLayout) ? $tplLayout : (isset($__tplLayout) ? $__tplLayout : $dcfg['tplLayout']);

// &tplResults - Chunk to style the non-ajax output results outer
// Default: '@FILE:' . AS_SPATH . 'templates/results.tpl.html'
$cfg['tplResults'] = isset($tplResults) ? $tplResults : (isset($__tplResults) ? $__tplResults : $dcfg['tplResults']);

// &tplResult - Chunk to style each output result
// Default: "@FILE:" . AS_SPATH . 'templates/result.tpl.html'
$cfg['tplResult'] = isset($tplResult) ? $tplResult : (isset($__tplResult) ? $__tplResult : $dcfg['tplResult']);

// &tplComment - Chunk to style the comment form (Also used with the ajax mode)
// Default: '@FILE:' . AS_SPATH . 'templates/comment.tpl.html'
$cfg['tplComment'] = isset($tplComment) ? $tplComment : (isset($__tplComment) ? $__tplComment : $dcfg['tplComment']);

// &tplPaging - Chunk to style the paging links
// Default: '@FILE:' . AS_SPATH . 'templates/paging.tpl.html'
$cfg['tplPaging'] = isset($tplPaging) ? $tplPaging : (isset($__tplPaging) ? $__tplPaging : $dcfg['tplPaging']);

// &stripInput - stripInput user function name
// Default: 'defaultStripInput'
$cfg['stripInput'] = isset($stripInput) ? $stripInput : (isset($__stripInput) ? $__stripInput : $dcfg['stripInput']);

// &stripOutput - stripOutput user function name
// Default: 'defaultStripOutput'
$cfg['stripOutput'] = isset($stripOutput) ? $stripOutput : (isset($__stripOutput) ? $__stripOutput : $dcfg['stripOutput']);

// &searchWordList - searchWordList user function name
// [user_function_name,params] where params is an optional array of parameters
// Default: '' - empty string
$cfg['searchWordList'] = isset($searchWordList) ? $searchWordList : (isset($__searchWordList) ? $__searchWordList : $dcfg['searchWordList']);

// &breadcrumbs
// 0 : disallow the breadcrumbs link
// Name of the breadcrumbs function : allow the breadcrumbs link
// The function name could be followed by some parameter initialization
// e.g: &breadcrumbs=`Breadcrumbs,showHomeCrumb:0,showCrumbsAtHome:1`
// Default: '' - empty string
$cfg['breadcrumbs'] = isset($breadcrumbs) ? $breadcrumbs : (isset($__breadcrumbs) ? $__breadcrumbs : $dcfg['breadcrumbs']);

// &tvPhx - Set placeHolders for TV (template variables)
// 0 : disallow the feature (default)
// 'tv:displayTV' : set up a placeholder named [+as.tvName.+] for each TV (named tvName) linked to the documents found
// displayTV is a provided ajaxSearch function which render the TV output
// tvPhx could also be used with custom tables
// Default: 0 - tvPhx disallowed
$cfg['tvPhx'] = isset($tvPhx) ? $tvPhx : (isset($__tvPhx) ? $__tvPhx : $cfg['tvPhx']);

// &jsClearDefault - Clearing default text
// Set this to 1 if you would like to include the clear default js function
// add the class "cleardefault" to your input text form and set this parameter
// Default: 0 
$cfg['clearDefault'] = isset($clearDefault) ? $clearDefault : (isset($__clearDefault) ? $__clearDefault : $dcfg['clearDefault']);

// &jsClearDefault - Location of the js library
// Default: AS_SPATH . 'js/clearDefault.js'
$cfg['jsClearDefault'] = $dcfg['jsClearDefault'];

// &mbstring - php_mbstring extension available [0 | 1]
// Default: 1 - extension available
$cfg['mbstring'] = isset($mbstring) ? $mbstring : (isset($__mbstring) ? $__mbstring : $dcfg['mbstring']);

//  &asLog - ajaxSearch log [ level [: comment [: purge]]]
//  level:
//        0 : disallow the ajaxSearch log (Default)
//        1 : failed search requests are logged
//        2 : all ajaxSearch requests are logged
//  comment:
//        0 : user comment not allowed (Default)
//        1 : user comment allowed
//  purge: number of logs allowed before to do an automatic purge of the table
//        Default: 200 
$cfg['asLog'] = isset($asLog) ? $asLog : (isset($__asLog) ? $__asLog : $dcfg['asLog']);

//------------------------------------------------------------------------------
// Configure - Ajax mode snippet setup options
//------------------------------------------------------------------------------

if ($cfg['ajaxSearch']){  // ajax mode

    // $liveSearch [1 | 0] (as passed in snippet variable ONLY)
    // Set this to 1 if you would like to use the live search (i.e. results as you type)
    // Default: 0 - livesearch mode inactivated
    $cfg['liveSearch'] = isset($liveSearch) ? $liveSearch : (isset($__liveSearch) ? $__liveSearch : $dcfg['liveSearch']);

    // &ajaxMax [int] - The maximum number of results to show for the ajaxsearch
    // Default: 6
    $cfg['ajaxMax'] = isset($ajaxMax) ? $ajaxMax : (isset($__ajaxMax) ? $__ajaxMax : $dcfg['ajaxMax']);
    
    // &showMoreResults [1 | 0]
    // Set this to 1 if you would like a link to show all of the search results
    // Default: 0
    $cfg['showMoreResults'] = isset($showMoreResults) ? $showMoreResults : (isset($__showMoreResults) ? $__showMoreResults : $dcfg['showMoreResults']);

    // &moreResultsPage [int]
    // The document id of the page you want the more results link to point to
    // Default: 0
    $cfg['moreResultsPage'] = isset($moreResultsPage ) ? $moreResultsPage : (isset($__moreResultsPage ) ? $__moreResultsPage : $dcfg['moreResultsPage']);

    // &opacity - set the opacity of the div ajaxSearch_output 
    // Should be a float value: [ 0. < float <= 1. ]
    // Default: 1.
    $cfg['opacity'] = isset($opacity) ? $opacity : (isset($__opacity) ? $__opacity : $dcfg['opacity']);

    // &tplAjaxResults - Chunk to style the ajax output results outer
    // Default: '' - empty string
    $cfg['tplAjaxResults'] = isset($tplAjaxResults) ? $tplAjaxResults : (isset($__tplAjaxResults) ? $__tplAjaxResults : $dcfg['tplAjaxResults']);

    // &tplAjaxResult - Chunk to style each output result
    // Default: '' - empty string
    $cfg['tplAjaxResult'] = isset($tplAjaxResult) ? $tplAjaxResult : (isset($__tplAjaxResult) ? $__tplAjaxResult : $dcfg['tplAjaxResult']);

    // &jscript ['jquery'|'mootools1.2'|'mootools']
    // Set this to jquery if you would like use the jquery library
    // set mootools1.2 to use the version 1.2 of mootools (limited to JS functions used by AS)
    // Default: 'mootools'
    $cfg['jscript'] = isset($jscript ) ? $jscript : (isset($__jscript ) ? $__jscript : $dcfg['jscript']);

    // &addJscript [1 | 0]
    // Set this to 1 if you would like to include or not the mootool/jquery library in the header of your pages automatically
    // Default: 1
    $cfg['addJscript'] = isset($addJscript ) ? $addJscript : (isset($__addJscript ) ? $__addJscript : $dcfg['addJscript']);

    // &jsMooTools - Location of the mootools javascript library (current version of MODx)
    // Default: 'manager/media/script/mootools/mootools.js'
    $cfg['jsMooTools'] = $dcfg['jsMooTools'];
        
    // &jsMooTools1.2 - Location of the mootools javascript library (version 1.2) 
    // contains only the mootools functions needed by AjaxSearch
    // Default: AS_SPATH . 'js/mootools1.2/mootools.js'
    $cfg['jsMooTools1.2'] = $dcfg['jsMooTools1.2'];
        
    // &jsQuery - Location of the jquery javascript library
    // Default: AS_SPATH . 'js/jquery/jquery.js'
    $cfg['jsJquery'] = $dcfg['jsJquery'];
}

include_once AS_PATH."classes/ajaxSearch.class.inc.php";

if (class_exists('AjaxSearch')) {
  $as = new ajaxSearch(AS_VERSION,$cfg,$dcfg);
  //Process ajaxSearch
  $output = $as->run();
} else {
  $output = "<h3>error: AjaxSearch class not found</h3>";
}
return $output;