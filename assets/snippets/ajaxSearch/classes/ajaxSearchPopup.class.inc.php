<?php
/*
 * Title: AjaxSearchPopup Class
 * Purpose:
 *    The ajaxSearchPopup class contains all variables and functions
 *    used to display search results in a popup window througth an ajax request
 *
 *    Version: 1.8.5  - Coroico (coroico@wangba.fr)
 *
 *    18/03/2010
 *
 *    Jason Coward (opengeek - jason@opengeek.com)
 *    Kyle Jaebker (kylej - kjaebker@muddydogpaws.com)
 *    Ryan Thrash (rthrash - ryan@vertexworks.com)
 *
 * 29/03/2009 - mootools1.2, jquery, maxWords, mbstring parameters, search logs
 * 02/10/2008 - whereSearch, withTvs, new sql query, debug, subSearch
 * 24/07/2008 - Added rank, order & filter, breadcrumbs, tvPhx parameters
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

// some usefull class definition
define('HIGHLIGHT_CLASS','ajaxSearch_highlight');             // token used for highlighting
define('PREFIX_AJAX_RESULT_CLASS','AS_ajax_result');          // ajax result prefix class
define('PREFIX_RESULT_CLASS','ajaxSearch_result');            // non-ajax result prefix class
define('INTROFAILURE_CLASS','AS_ajax_resultsIntroFailure');   // intro failure class
define('ASPHX','||-AJAXSEARCH-||');                           // internal place holder

include_once dirname(__FILE__)."/search.class.inc.php";

class AjaxSearchPopup extends Search{

// public
  var $version;         // AS snippet version
  var $dcfg = array();  // default configuration parameters
  var $ucfg;            // non default user configuration string

// private
  var $cfg = array();   // final configuration parameters
  var $asCall;          // AjaxSearch snippet call

  var $language;        // language name
  var $_lang;           // language labels

  var $searchString;    // term searched
  var $advSearch;       // advanced search option

  var $subSearch;       // search in a subdomain
  var $subSearchName;   // sub search function name

  var $withContent;     // content as main table
  var $listIDs;         // list of Id allowed

  var $breadcrumbs = array();     // breadcrumbs infos
  var $tvphx = array();           // tvPhx infos

  var $extractNb;       // maximum number of extracts per document
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

  // class variables
  var $asClass = array();

  // search results
  var $searchResults = array();

  function AjaxSearchPopup($version,$dcfg,$ucfg) {
    // set the AS snippet version
    $this->version = $version;
    // load the default configuration
    $this->dcfg = $dcfg;
    // load the non default user configuration string
    $this->ucfg = $ucfg;
    // set the final configuration
    $this->cfg = $this->setConfiguration($dcfg,$ucfg);
  }

/**
 * run : run the search
 */
  function run(){

    global $modx;

    if ($this->cfg['config']) {
      if (!$this->loadConfig($msg)) return $msg;  // load configuration file for user functions
    }
    $this->setDebug();    // set debug levels
    $this->setLog();      // set log level

    if ($this->dbg) {
      if ($this->cfg['config']) $this->asDebug->dbgLog($this->readConfigFile($this->cfg['config']),
                                    "AjaxSearchPopup - Configuration file " . $this->cfg['config']);   // configuration file
      $this->asDebug->dbgLog($this->cfg,"AjaxSearchPopup - User configuration - Before parameter checking");   // user parameters
    }

    $this->loadLang();    // load language labels

    if (!$this->setCharset($msg)) return $msg;  // set page and database charset

    if (!$this->checkAjaxSearchParams($msg)) return $msg;  // Check user parameters

    $validSearch = $this->validSearchString($msg); // Initialize search string & advanced search mode

    $this->initVariables(); // initialize some variables and functions

    if ($validSearch) {

      $this->initClassVariables(); // initialize class variables

      // get the Ids
      $this->getListIDs();

      if ($this->dbg) $this->asDebug->dbgLog($this->cfg,"AjaxSearchPopup - User configuration - Before doSearch");   // user parameters

      // Do the search and get the results
      $rs = $this->doSearch();

      $nbrs = $modx->recordCount($rs);
      $found = '';
      if ($nbrs > 0) {

        $this->initExtractVariables(); // initialize extractNb and extractFields

        // output results in searchResults array
        while ($row = mysql_fetch_assoc($rs)) {
          $result = $this->addExtractToRow($row);
          $this->searchResults[] = $result;
          if ($this->dbgRes) $this->asDebug->dbgLog($result,"AjaxSearchPopup - Output result before ranking");   // search results
        }

        // sort search results by rank if needed
        $this->sortResultsByRank($this->searchString,$this->advSearch);

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
          $this->setResultNumber($i+1);

          // parse the template and output the result
          $this->chkResult->AddVar("as", $this->varResult);
          $results .= $this->chkResult->Render()."\n";
          unset($this->varResult);
          unset($this->chkResult);

          if ($i == $this->cfg['ajaxMax']-1) {
            //If more than max results so link to all results
            $this->setMoreResultsLink();
            $i++;
            break;
          }
        }
        // output results
        $nbrs = $i ;
        $this->varResults['noResults'] = 0;
        $this->varResults['listResults'] = ASPHX;
      } else {
        // no results found
        $this->varResults['noResults'] = 1;
        $this->varResults['noResultClass'] = INTROFAILURE_CLASS;
        $this->varResults['noResultText'] = $this->_lang['as_resultsIntroFailure'];
      }

      $this->setLogInfos($nbrs,$found); // set the log infos
      $this->setComment(); // set the comment form

    } else {
      // message to show if search was performed but for something invalid
      $this->varResults['showCmt'] = 0;
      $this->varResults['noResults'] = 1;
      $this->varResults['noResultClass'] = INTROFAILURE_CLASS;
      $this->varResults['noResultText'] = $msg;
    }

    $this->chkResults->AddVar("as", $this->varResults);
    $output = $this->chkResults->Render()."\n";
    $output = str_replace(ASPHX, $results, $output);
    unset($this->varResults);
    unset($this->chkResults);

    // UTF-8 conversion is required if mysql character set is different of 'utf8'
    if ($this->needsConvert) $output = mb_convert_encoding($output,"UTF-8",$this->pgCharset);

    return $output;
  }

/**
 * setPageCharset : Set the Page and database charset
 */
  function setConfiguration($dcfg,$ucfg){
    $ucfg_array = array();
    $cfg = array();
    $matches = array();

    preg_match_all("/ &([^=]*)=`([^`]*)`/",$ucfg,$matches);
    $nbc = count($matches[0]);
    if ($nbc > 0){
      for($i=0;$i<$nbc;$i++) $ucfg_array[$matches[1][$i]] = $matches[2][$i];
    }

    foreach($dcfg as $key=>$value){
      $cfg[$key] = $value;
      if (isset($ucfg_array[$key])) $cfg[$key] = $ucfg_array[$key]; // overwrite the default value
    }

    $cfg['subSearch'] = $_POST['subSearch'];
    $cfg['advSearch'] = $_POST['advSearch'];

    return $cfg;
  }
/**
 * setPageCharset : Set the Page and database charset
 */
  function setCharset(& $msgErr){

    $valid = false;
    $msgErr = '';
    $this->setDatabaseCharset();  // initialize the database charset
    $this->setPageCharset(); // initialize the page charset

    // Ajax window charset = UTF-8 and should to be coherent with database
    if (isset($this->dbCharset) && isset($this->pageCharset[$this->dbCharset])) {
      // if the charset of database is different of utf8 a mbstring conversion will be required
      if ($this->dbCharset != 'utf8' && !extension_loaded('mbstring')) {
        $msgErr = "php_mbstring extension required";
      }
      else {
        if ($this->cfg['mbstring']) mb_internal_encoding("UTF-8");
        $this->pgCharset = $this->pageCharset[$this->dbCharset];
        $valid = true;
      }
    } elseif (!isset($this->dbCharset)){
      $msgErr = "AjaxSearch: database_connection_charset not set. Check your config file";
    } elseif (!strlen($this->dbCharset)){
      $msgErr = "AjaxSearch: database_connection_charset is null. Check your config file";
    } else {
      // if you get this message, simply update the $pageCharset array in search.class.inc.php file
      // with the appropriate mapping between Mysql Charset and Html charset
      // eg: 'latin2' => 'ISO-8859-2'
      $msgErr = "AjaxSearch: unknown database_connection_charset = {$this->dbCharset}<br />Add the appropriate Html charset mapping in the search.class.inc.php file";
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

    // load some new parameters from configuration file if needed
    if ($this->cfg['subSearch'] != ''){
      $sbsch_array = explode(',',$this->cfg['subSearch']);
      $this->subSearchName = $sbsch_array[0];
      $this->subSearchSel = $sbsch_array[1];
      if (!function_exists($this->subSearchName)) {
        $msgErr = "<br /><h3>Error: search function $this->subSearchName not defined in the configuration file: ".$this->cfg['config']."!</h3><br />";
        return false;
      }
      else {
        $subSearchName = $this->subSearchName;
        $newcfg = $subSearchName();    // call to the subSearch function to set up new parameters
        $this->updateConfig($newcfg);
      }
    }

    if (!$this->checkParams($this->cfg,$msgErr)) return false;  // Check other user parameters

    return true;
  }

/**
 * initVariables : Initialize some variables used
 */
  function initVariables(){

    $this->initIdGroup();         // Initialize Id groups

    $this->initDocGroup();        // Initialize Documents group

    $this->asCall = $this->getAsCall($this->ucfg);  // get the AS snippet call
    if ($this->dbg) $this->asDebug->dbgLog($this->asCall,"AjaxSearchPopup - Snippet call");

    $this->initChkVariables();   // Initialize chunkie variables

    $this->initBreadcrumbs();    // Initialize breadcrumbs

    $this->initTvPhx();          // Initialize tvPhx
  }

/**
 * validSearchString : Validation of input search term
 */
  function validSearchString(& $msgErr){

    global $modx;

    $valid = false;
    $msgErr = '';

  // check advSearch parameter
  $this->cfg['advSearch'] = (in_array($this->cfg['advSearch'],$this->advSearchType)) ? $this->cfg['advSearch'] : $this->advSearchType[0];
    $this->advSearch = $this->cfg['advSearch']; // initialize advanced search option

    // Initialize search string
    $this->searchString = $_POST['search'];
    if (is_array($this->searchString)) $this->searchString = implode(' ',array_values($this->searchString));   // use of searchWordList

    if (($this->pgCharset != 'UTF-8') && (ini_get('mbstring.encoding_translation') == '' || strtolower(ini_get('mbstring.http_input')) == 'pass')) {
      $this->searchString = mb_convert_encoding($this->searchString, $this->pgCharset , "UTF-8");
      $this->needsConvert = true;
    }
    else {
      $this->needsConvert = false;
    }

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
 * initChkVariables : Initialize the Chunkie variables
 */
  function initChkVariables(){

    // include chunckie class and read templates once
    if (!class_exists('asChunkie')) include_once AS_PATH . "classes/chunkie.class.inc.php";
    $tplResults = $this->cfg['tplAjaxResults'];
    $this->chkResults = new asChunkie($tplResults);   // results outer

    $tplResult = $this->cfg['tplAjaxResult'];
    $this->chkResult = new asChunkie($tplResult);     // result
    $this->tplRes = "@CODE:" . $this->chkResult->template;

    if ($this->dbgTpl) {
      $this->asDebug->dbgLog($this->chkResults->getTemplate($tplResult),"AjaxSearchPopup - tplResult template " . $tplResult);
      $this->asDebug->dbgLog($this->chkResult->getTemplate($tplResults),"AjaxSearchPopup - tplResults template" . $tplResults);
    }
  }

/**
 * setMoreResultsLink : Set the more results link
 */
  function setMoreResultsLink(){
    //If more than max results so link to all results
    if ($this->cfg['showMoreResults']) {
      $this->varResults['moreResults'] = 1;
      $this->varResults['moreClass'] = 'AS_ajax_more';
      if ($this->cfg['subSearch'] != '')
        $this->varResults['moreLink'] = 'index.php?id='.$this->cfg['moreResultsPage'].'&amp;AS_search='.urlencode($this->searchString).'&amp;advsearch='.urlencode($this->advSearch).'&amp;subsearch='.urlencode($this->cfg['subSearch']);
      else
        $this->varResults['moreLink'] = 'index.php?id='.$this->cfg['moreResultsPage'].'&amp;AS_search='.urlencode($this->searchString).'&amp;advsearch='.urlencode($this->advSearch);
      $this->varResults['moreTitle'] = $this->_lang['as_moreResultsTitle'];
      $this->varResults['moreText'] = $this->_lang['as_moreResultsText'];
    }
  }
}
?>