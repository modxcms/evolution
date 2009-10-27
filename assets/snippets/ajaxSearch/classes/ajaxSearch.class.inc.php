<?php
/*
 * Title: AjaxSearch Class
 * Purpose:
 *    The AjaxSearch class contains all variables and functions
 *    used to display search form and results
 *
 *    Version: 1.8.4  - Coroico (coroico@wangba.fr)
 *
 *    20/10/2009
 *
 *    Jason Coward (opengeek - jason@opengeek.com)
 *    Kyle Jaebker (kylej - kjaebker@muddydogpaws.com)
 *    Ryan Thrash (rthrash - ryan@vertexworks.com)
 *
 * 12/07/2009 - check of the advSearch parameter added
 * 29/03/2009 - mootools1.2, jquery, maxWords, mbstring parameters, search logs
 * 02/10/2008 - whereSearch, withTvs, new sql query, debug, subSearch
 * 24/07/2008 - Added rank, order & filter, breadcrumbs, tvPhx, cleardefault parameters
 * O2/07/2008 - New extract algorithm, search in tv, jot and maxygallery
 * O2/07/2008 - Added Phx templating & chunk parameters
 * 06/03/2008 - Added Hidden from menu and advanced search
 * 01/02/2008 - Added several fixes and a security patch
 * 17/11/2007 - Added IDs document selection
 * 06/11/2007 - Encoding troubles corrected
 *
 * 01/22/07 - Added templating/language/mootools support
 * 01/03/07 - Added fixes/updates from forum
 * 09/18/06 - Added user permissions to searching
 * 03/20/06 - All variables are set in the main snippet & snippet call
*/

define('FORM_ID','id="ajaxSearch_form" ');    // ajaxSearch form id
define('OUTPUT_ID','<div id="ajaxSearch_output"></div>'); // output id

// some usefull class definition
define('HIGHLIGHT_CLASS','ajaxSearch_highlight');             // token used for highlighting
define('PREFIX_AJAX_RESULT_CLASS','AS_ajax_result');          // ajax result prefix class
define('PREFIX_RESULT_CLASS','ajaxSearch_result');            // non-ajax result prefix class
define('INTROFAILURE_CLASS','AS_ajax_resultsIntroFailure');   // intro failure class


include_once dirname(__FILE__)."/search.class.inc.php";

class AjaxSearch extends Search{

// public
  var $version;         // AS snippet version
  var $cfg = array();   // final configuration parameters
  var $dcfg = array();  // default configuration parameters

// private
  var $ucfg;            // non default user configuration string
  var $asCall;          // AjaxSearch snippet call

  var $language;        // language name
  var $_lang;           // language labels

  var $searchString;    // term searched
  var $advSearch;       // advanced search option

  var $subSearch;       // search in a subdomain
  var $subSearchName;   // sub search function name
  var $subSearchSel;    // choice selected
  var $subSearchNb;     // max number of choices

  var $searchFormId;    // ajaxSearch form id
  var $offset;          // paging offset
  var $withContent;     // content as main table
  var $listIDs;         // list of Id allowed

  var $breadcrumbs = array();     // breadcrumbs infos
  var $tvphx = array();           // tvPhx infos

  var $extractNb;                 // maximum number of extracts per document
  var $extractFields = array();   // fields to use for extraction

  // Search context
  var $main = array();       // main content table
  var $joined = array();     // joined tables

  // chunkie variables
  var $chkResults;
  var $varResults = array();
  var $chkResult;
  var $varResult = array();
  var $tplRes;
  var $chkLayout;
  var $varLayout = array();

  // class variables
  var $asClass = array();

  // search results
  var $searchResults = array();


  function AjaxSearch($version,$cfg,$dcfg) {
    // set the AS snippet version
    $this->version = $version;
    // load configuration snippet options
    $this->cfg = $cfg;
    // load default configuration values
    $this->dcfg = $dcfg;
  }

/**
 * run : run the search
 */
  function run(){

    global $modx;

    $this->setDebug();    // set debug levels
    $this->setLog();      // set log level

    if ($this->dbg) {
      if ($this->cfg['config']) $this->asDebug->dbgLog($this->readConfigFile($this->cfg['config']),
                                  "AjaxSearch - Custom configuration file " . $this->cfg['config']);   // configuration file
      $this->asDebug->dbgLog($this->cfg,"AjaxSearch - User configuration - Before parameter checking");   // user parameters
    }

    $this->loadLang();    // load language labels

    if (!$this->setCharset($msg)) return $msg;  // set page and database charset

    if (!$this->checkAjaxSearchParams($msg)) return $msg;  // Check user parameters

    $validSearch = $this->validSearchString($msg);  // Initialize search string & advanced search mode

    $this->initVariables(); // initialize some variables and functions

    $searchAction = $this->setResultsPage();    // set searchAction
    $this->setAjaxSearchHeader();               // set header

    // set input form if needed
    $this->setInputForm($validSearch,$searchAction);

    if ($this->cfg['AS_showResults']) {
      if ($validSearch) {
        // ------------------------------------------- non-ajax mode. Display of results
        $results = '';

        $this->initClassVariables(); // initialize class variables

        // get the IDs
        $this->getListIDs();

        if ($this->dbg) $this->asDebug->dbgLog($this->cfg,"AjaxSearch - User configuration - Before doSearch");   // user parameters

        // Do the search and get the results
        $rs = $this->doSearch();

        $nbrs = $modx->recordCount($rs);
        $found = '';
        if ($nbrs > 0) {

          $this->setResultsPagination($nbrs);  // set pagination if needed

          $this->initExtractVariables(); // initialize extractNb and extractFields

          // output results in searchResults array
          while ($row = mysql_fetch_assoc($rs)) {
            $result = $this->addExtractToRow($row);
            $this->searchResults[] = $result;
            if ($this->dbgRes) $this->asDebug->dbgLog($result,"AjaxSearch - Output results before ranking");   // search results
          }

          // sort search results by rank if needed
          $this->sortResultsByRank($this->searchString,$this->advSearch);
          // limit the search results to the current page
          $length = ($this->cfg['grabMax'] > 0)? $this->cfg['grabMax'] : $nbrs;
          $this->searchResults = array_slice($this->searchResults,$this->offset,$length);

          $nbResults = count($this->searchResults);
          for($i=0;$i<$nbResults;$i++){
            $this->chkResult = new asChunkie($this->tplRes);
            $this->varResult = array();

            // set result link as PHx
            $this->setResultLink($this->searchResults[$i]);

            // set result extract as PHx
            $this->setResultExtract($this->searchResults[$i]);

            // set result breadcrumbs as PHx
            $this->setResultBreadcrumbs($this->searchResults[$i]);

            // set result TvPhx as PHx
            $this->setResultTvPhx($this->searchResults[$i]);

            // set result fields like id, searchable, date, rank as PHx
            $found .= $this->setResultSearchable($this->searchResults[$i]) . " ";

            //set result number as PHx
            $this->setResultNumber($this->offset+$i+1);

            // parse the template and output the result
            $this->chkResult->AddVar("as", $this->varResult);
            $results .= $this->chkResult->Render()."\n";
            unset($this->varResult);
            unset($this->chkResult);
          }
          // output results
          $this->varResults['noResults'] = 0;
          $this->varResults['listResults'] = $results;
        } else {
          // no results found
          $this->varResults['noResults'] = 1;
          $this->varResults['noResultClass'] = 'AS_ajax_resultsIntroFailure';
          $this->varResults['noResultText'] = $this->_lang['as_resultsIntroFailure'];
        }

        $this->setLogInfos($nbrs,$found); // set the log infos
        $this->setComment(); // set the comment form

        $this->chkResults->AddVar("as", $this->varResults);
        $this->varLayout['showResults'] = 1;
        $this->varLayout['results'] = $this->chkResults->Render()."\n";
        unset($this->varResults);
        unset($this->chkResults);
      } // end if validSearch

      else if (!$validSearch && isset($_POST['sub'])) {
        // message to show if search was performed but for something invalid
        $this->varResults['showCmt'] = 0;
        $this->varResults['noResults'] = 1;
        $this->varResults['noResultClass'] = 'AS_ajax_resultsIntroFailure';
        $this->varResults['noResultText'] = $msg;

        $this->chkResults->AddVar("as", $this->varResults);
        $this->varLayout['showResults'] = 1;
        $this->varLayout['results'] = $this->chkResults->Render()."\n";
        unset($this->varResults);
        unset($this->chkResults);
      }

      else { // init the input field
        $this->varLayout['showResults'] = 0;
        $this->varLayout['showIntro'] = 1;
        $this->varLayout['introMessage'] = $this->_lang['as_introMessage'];
      } // end if not validSearch
    } // end if showResults

    if ($this->cfg['ajaxSearch']) {
      $this->varLayout['showIntro'] = 0;
      $this->varLayout['showResults'] = 1;
      $this->varLayout['results'] = OUTPUT_ID;
    }
    $this->chkLayout->AddVar("as", $this->varLayout);
    $output = $this->chkLayout->Render()."\n";
    unset($this->varLayout);
    unset($this->chkLayout);

    return $output;
  }

/**
 * setCharset : Set the Page and database charset
 *
 * @param string $msgErr error message
 * @return validity (true/false)
 */
  function setCharset(& $msgErr){

    $valid = true;
    $msgErr = '';
    $this->setDatabaseCharset();  // initialize the database charset
    $this->setPageCharset(); // initialize the page charset

    // check if the page charset exists
    if (!isset($this->pageCharset[$this->dbCharset])){
      // if you get this message, simply update the $pageCharset array in search.class.inc.php file
      // with the appropriate mapping between Mysql Charset and Html charset
      // eg: 'latin2' => 'ISO-8859-2'
      $msgErr = "AjaxSearch: unknown database_connection_charset = {$this->dbCharset}<br />Add the appropriate Html charset mapping in the search.class.inc.php file";
      $valid = false;
    }
    elseif (($this->dbCharset == 'utf8') && ($this->cfg['mbstring']) && (!extension_loaded('mbstring'))) {
      // check if the mbstring extension is required and loaded
      $msgErr = "<br /><h3>AjaxSearch: php_mbstring extension required</h3><br />";
      $valid = false;
    }
    return $valid;
  }

/**
 * Check AjaxSearch user params
 *
 * @param string $msgErr error message
 * @return validity (true/false)
 */
  function checkAjaxSearchParams(& $msgErr){

    $msgErr = '';

    if (isset($_POST['subSearch']) || isset($_GET['subsearch'])) {
      // catch the new parameters from config file and overwrite pre-existing parameters
      if (isset($_POST['subSearch'])) $this->subSearch = $_POST['subSearch'];
      else $this->subSearch = urldecode($_GET['subsearch']);
      // subsearch function name and radio button index
      $sbsch_array = explode(',',$this->subSearch);
      $this->subSearchName = $sbsch_array[0];
      if (isset($sbsch_array[1])) $this->subSearchSel = $sbsch_array[1];
      else $this->subSearchSel = 1;
      // existing function ?
      if (!function_exists($this->subSearchName)) {
          $msgErr = "<br /><h3>AjaxSearch error: search function $this->subSearchName not defined in the configuration file: ".$this->cfg['config']." !</h3><br />";
          return false;
      }
      else {
        $subSearchName = $this->subSearchName;
        $newcfg = $subSearchName();    // call to the subSearch function to set up new parameters
        $this->updateConfig($newcfg);
      }
    }

    //check subSearch definition parameter (from snippet call)
    if (isset($this->cfg['subSearch'])){
      $sbsch_array = explode(',',$this->cfg['subSearch']);
      $sbschNb = (int) $sbsch_array[0];
      if (isset($sbsch_array[1])) $sbschSel = (int) $sbsch_array[1];
      else $sbschSel = 1;
      if ($sbschSel > $sbschNb) $sbschSel = $sbschNb;
      if ($sbschSel < 1) $sbschSel = 1;
      $this->cfg['subSearch'] = $sbschNb . ',' . $sbschSel;
    }
    //check for paging offset
    $this->offset = (isset($_GET['AS_offset'])) ? intval($_GET['AS_offset']) : 0;
    $this->offset = ($this->offset >0) ? $this->offset : 0;

    if (!$this->checkParams($this->cfg,$msgErr)) return false;  // Check other user parameters

    return true;
  }

/**
 * initVariables : Initialize some variables used
 */
  function initVariables(){

    global $modx;

    $this->initIdGroup();         // Initialize Id groups

    $this->initDocGroup();        // Initialize Documents group

    $this->initASCall();          // Initialize AS Call

    $this->initTvPhx();           // Initialize tvPhx

    $this->initBreadcrumbs();     // Initialize breadcrumbs

    $this->initChkVariables();    // Initialize chunkie variables

    $this->initClearDefault();    // Initialize the clear default function

    $this->initSubSearchVariables(); // Initialize the subSearch variables

    $this->initFormId();          // Initialize Form Id
  }

/**
 * validSearchString : valid the input search term
 */
  function validSearchString(& $msgErr){

    global $modx;

    $valid = false;
    $msgErr = '';
    $this->searchString = '';
    $this->advSearch = $this->cfg['advSearch']; // init from snippet call

    if ( isset($_POST['search']) || isset($_GET['AS_search']) || isset($_GET['FSF_search'])) {
      // Prefer post to get
      if (isset($_POST['search'])) {
        $this->searchString = $_POST['search'];
        if (is_array($this->searchString)) $this->searchString = implode(' ',array_values($this->searchString));   // use of searchWordList
      } elseif (isset($_GET['AS_search'])) {
        $this->searchString = urldecode($_GET['AS_search']);
      } else {
        // Code to make tag cloud snippet work with this search
        $this->searchString = $_GET['FSF_search'];
      }
      if (isset($_POST['advSearch'])) $this->advSearch = $_POST['advSearch'];
      else if (isset($_GET['advsearch'])) $this->advSearch = urldecode($_GET['advsearch']);
    }

  // check advSearch parameter
  $this->advSearch = (in_array($this->advSearch,$this->advSearchType)) ? $this->advSearch : $this->advSearchType[0];

    // check searchString
    $valid = $this->checkSearchString($this->searchString,$msgErr);
    if (!$valid) return false;

    // Clean the searchString
    $valid = $this->stripSearchString($this->searchString,$this->cfg['stripInput'],$this->advSearch);
    if (!$valid) $msgErr = $this->_lang['as_resultsIntroFailure'];

    // init searchString as Phx [+as.searchString+]
    if ($valid) $modx->setPlaceholder("as.searchString", $this->searchString);

    return $valid;
  }

/**
 * setAjaxSearchHeader : set the ajax header with the appropriate variables
 */
  function setAjaxSearchHeader(){

    global $modx;

    // add the clearDefault js library if needed
    if ($this->cfg['clearDefault']) $modx->regClientStartupScript($this->cfg['jsClearDefault']);

    if ($this->cfg['ajaxSearch']) {
      //Adding the javascript libraries & variables to the header
      if ($this->cfg['jscript'] == 'jquery') {
        if ($this->cfg['addJscript']) $modx->regClientStartupScript($this->cfg['jsJquery']);
        $jsInclude = AS_SPATH.'js/ajaxSearch-jquery.js';
      }
      elseif ($this->cfg['jscript'] == 'mootools1.2') {
        if ($this->cfg['addJscript']) $modx->regClientStartupScript($this->cfg['jsMooTools1.2']);
        $jsInclude = AS_SPATH.'js/ajaxSearch-mootools1.2.js';
      }
      else {
        if ($this->cfg['addJscript']) $modx->regClientStartupScript($this->cfg['jsMooTools']);
        $jsInclude = AS_SPATH.'js/ajaxSearch.js';
      }
      $modx->regClientStartupScript($jsInclude);

      $jsVars =<<<EOD
<!-- start AjaxSearch header -->
<script type="text/javascript">
//<![CDATA[
as_version = '{$this->version}';
advSearch = '{$this->advSearch}';
subSearch = {$this->subSearchNb};
ucfg = '{$this->ucfg}';
//]]>
</script>
<!-- end AjaxSearch header -->
EOD;

      $modx->regClientStartupScript($jsVars);
    }
  }
/**
 * initASCall : Initialize AjaxSearch Call
 */
  function initASCall(){
    // get the non default configuration keys
    $this->ucfg = $this->getUcfg();
    // get the AS snippet call
    $this->asCall = $this->getAsCall($this->ucfg);
    if ($this->dbg) $this->asDebug->dbgLog($this->asCall,"AjaxSearch - Snippet call");   // snippet call
  }
/**
 * initChkVariables : Initialize the chunkie variables used
 */
  function initChkVariables(){

    // include chunkie class and read templates once
    if (!class_exists('asChunkie')) include_once AS_PATH . "classes/chunkie.class.inc.php";
    $tplResults = $this->cfg['tplResults'];
    if ($tplResults == '') $tplResults = "@FILE:" . AS_SPATH . 'templates/results.tpl.html';
    $this->chkResults = new asChunkie($tplResults);       // results outer

    $tplResult = $this->cfg['tplResult'];
    if ($tplResult == '') $tplResult = "@FILE:" . AS_SPATH . 'templates/result.tpl.html';
    $this->chkResult = new asChunkie($tplResult);     // result
    $this->tplRes = "@CODE:" . $this->chkResult->template;

    $this->chkLayout = new asChunkie($this->cfg['tplLayout']);   // layout

    if ($this->dbgTpl) {
      $this->asDebug->dbgLog($this->chkResults->getTemplate($tplResult),"AjaxSearch - tplResult template " . $tplResult);
      $this->asDebug->dbgLog($this->chkResult->getTemplate($tplResults),"AjaxSearch - tplResults template" . $tplResults);
      $this->asDebug->dbgLog($this->chkLayout->getTemplate($this->cfg['tplLayout']),"AjaxSearch - tplResult template" . $this->cfg['tplLayout']);
    }
  }

/**
 * initClearDefault : initialize the clear default js function
 */
  function initClearDefault(){
    global $modx;
    if ($this->cfg['clearDefault']) {
      //Adding the clearDefault javascript library
      $modx->regClientStartupScript($this->cfg['jsClearDefault']);
    }
  }

/**
 * initFormId : initialize the Form Id
 */
  function initFormId(){
    if (isset($this->cfg['ajaxSearch'])) $this->searchFormId = FORM_ID;
    else $this->searchFormId = '';
  }

/**
 * initSubSearchVariables : Initialize the subSearch variables from definition
 */
  function initSubSearchVariables(){

    $sbsch_array = explode(',',$this->cfg['subSearch']);
    $this->subSearchNb = $sbsch_array[0];
    if (!isset($this->subSearch)){
      // init choice selection from snippet call rather than POST or GET variable
      if (isset($sbsch_array[1])) $this->subSearchSel = $sbsch_array[1];
      else $this->subSearchSel = 1;
    }
    return;
  }

/**
 * setResultsPagination : Set the pagination of results
 */
  function setResultsPagination($nbrs){

    global $modx;

    $showPagingAlways = (int)$this->cfg['showPagingAlways'];
    $grabMax = $this->cfg['grabMax'];

    if ($grabMax > 0){

      $chkPaging = new asChunkie($this->cfg['tplPaging']);   // paging
      if ($this->dbgTpl) $this->asDebug->dbgLog($chkPaging->getTemplate($this->cfg['tplPaging']),"AjaxSearch - tplPaging template " . $this->cfg['tplPaging']);

      $tplPgg = "@CODE:" . $chkPaging->template;
      unset($chkPaging);

      $numResultPages = ceil($nbrs/$grabMax);
      $maxOffset = ($numResultPages-1) * $grabMax;
      $this->offset = ($this->offset > $maxOffset) ? $maxOffset : $this->offset;

      $resultPagingText = (($nbrs>$grabMax) || $showPagingAlways) ? $this->_lang['as_paginationTextMultiplePages'] : $this->_lang['as_paginationTextSinglePage'] ;
      $resultPageLinkNumber = 1;
      $resultPageLinks = '';

      for ( $nrp = 0; $nrp < $nbrs && (($nbrs > $grabMax) || $showPagingAlways); $nrp += $grabMax ){
        $chkPaging = new asChunkie($tplPgg);
        $varLink = array();
        if ($this->offset == ($resultPageLinkNumber-1)*$grabMax){
          $varLink['tpl'] = 'pagingLinksCurrent';
        } else {
          $varLink['tpl'] = 'pagingLinks';
          if (!isset($this->subSearch)) $varLink['pagingLink'] = $modx->makeUrl($modx->documentIdentifier,'','AS_offset='.$nrp.'&amp;AS_search='.urlencode($this->searchString).'&amp;advsearch='.urlencode($this->advSearch));
          else $varLink['pagingLink'] = $modx->makeUrl($modx->documentIdentifier,'','AS_offset='.$nrp.'&amp;AS_search='.urlencode($this->searchString).'&amp;advsearch='.urlencode($this->advSearch).'&amp;subsearch='.urlencode($this->subSearch));
        }
        $varLink['pagingSeparator'] = ($nrp + $grabMax < $nbrs) ? $this->cfg['pageLinkSeparator'] : '' ;
        $varLink['pagingText'] = $resultPageLinkNumber;
        $resultPageLinkNumber++;

        // parse the template and output the paging link
        $chkPaging->AddVar("as", $varLink);
        $resultPageLinks .= $chkPaging->Render()."\n";
        unset($varLink);
        unset($chkPaging);
      }

      $varPaging = array();
      $varPaging['tpl'] = 'paging';
      $varPaging['pagingText'] = $resultPagingText;
      $varPaging['pagingLinks'] = $resultPageLinks;

      // parse the template and output the paging links
      $chkPaging = new asChunkie($tplPgg);
      $chkPaging->AddVar("as", $varPaging);
      $this->varResults['paging'] = $chkPaging->Render()."\n";
      unset($varPaging);
      unset($chkPaging);

      $resultsFoundText = ($nbrs > 1)? $this->_lang['as_resultsFoundTextMultiple'] : $this->_lang['as_resultsFoundTextSingle'] ;
      if ($this->extractNb) {
        $searchList = $this->getSearchWords($this->searchString, $this->advSearch);
        $hits = 1;
        $searchwords = '';
        foreach ($searchList as $words) {
          $searchwords .= '<span class="ajaxSearch_highlight ajaxSearch_highlight'.$hits.'">'.$words.'</span>&nbsp;';
          $hits++;
        }
        // Remove trailing '&nbsp;'
        $searchwords = substr($searchwords, 0, strlen($searchwords) -6);
        $this->varResults['resultInfoText'] = sprintf($resultsFoundText,$nbrs,$searchwords);
      } else {
        $this->varResults['resultInfoText'] = sprintf($resultsFoundText,$nbrs,$this->searchString);
      }
    } // end if grabMax
  }

/**
 * setResultsPage : Set the result page
 */
  function setResultsPage(){
    global $modx;
      // establish results page
    if (isset($this->cfg['AS_landing']) && !is_bool($this->cfg['AS_landing'])) { // set in snippet
      $searchAction = "[~" . $this->cfg['AS_landing'] . "~]";
    } elseif ($this->cfg['resultsPage'] > 0) { // locally set
      $searchAction = "[~" . $this->cfg['resultsPage'] . "~]";
    } else { //otherwise
      $searchAction = "[~" . $modx->documentIdentifier . "~]";
    }
    return $searchAction;
  }

/**
 * setInputForm : Set the input form
 */
  function setInputForm($validSearch, $searchAction){

    // establish input form

    if (($validSearch && $this->cfg['AS_showForm']) || $this->cfg['AS_showForm']){
      $this->varLayout['showForm'] = '1';
      $this->varLayout['formId'] = $this->searchFormId;
      $this->varLayout['formAction'] = $searchAction;

      // set up a drop dow select list from the searchWordList parameter
      if ($this->cfg['searchWordList']){
        $searchList = explode (',',$this->getSelectList($this->cfg['searchWordList']));
        $slist = '';
        foreach($searchList as $searchTerm){
          $slist .= '<option value="'.$searchTerm.'">' . $searchTerm . '</option>'."\n";
        }
        $this->varLayout['selectList'] = $slist;
      }
      else  {
        $this->varLayout['inputValue'] = ((!$validSearch) || (($this->searchString == '') && ($this->_lang['as_boxText'] != ''))) ? $this->_lang['as_boxText'] : $this->searchString;
        $this->varLayout['inputOptions'] = ($this->_lang['as_boxText']) ? ' onfocus="this.value=(this.value==\''.$this->_lang['as_boxText'].'\')? \'\' : this.value ;"' : '';
      }
      $this->varLayout['submitText'] = $this->_lang['as_searchButtonText'];

      // propagate the hidden input advSearch parameter
      $this->varLayout['advSearch'] = $this->advSearch;

      // or let the end user use radio buttons to choose
      $this->varLayout['oneword'] = 'oneword';
      $this->varLayout['onewordText'] = $this->_lang['oneword'];
      $this->varLayout['onewordChecked'] = ($this->advSearch == 'oneword' ? 'checked ="checked"' : '');
      $this->varLayout['allwords'] = 'allwords';
      $this->varLayout['allwordsText'] = $this->_lang['allwords'];
      $this->varLayout['allwordsChecked'] = ($this->advSearch == 'allwords' ? 'checked ="checked"' : '');
      $this->varLayout['exactphrase'] = 'exactphrase';
      $this->varLayout['exactphraseText'] = $this->_lang['exactphrase'];
      $this->varLayout['exactphraseChecked'] = ($this->advSearch == 'exactphrase' ? 'checked ="checked"' : '');
      $this->varLayout['nowords'] = 'nowords';
      $this->varLayout['nowordsText'] = $this->_lang['nowords'];
      $this->varLayout['nowordsChecked'] = ($this->advSearch == 'nowords' ? 'checked ="checked"' : '');

      // subSearch
      for ($i=1;$i<$this->subSearchNb+1;$i++) {
        $iChecked = "subSearch{$i}Checked";
        $this->varLayout[$iChecked] = ($this->subSearchSel == $i ? 'checked ="checked"' : '');
      }
    } else {
      $this->varLayout['showForm'] = '0';
    }
  }
}

?>