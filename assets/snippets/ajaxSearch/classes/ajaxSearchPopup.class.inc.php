<?php
/*
 * Title: AjaxSearchPopup Class
 * Purpose:
 *    The ajaxSearchPopup class contains all variables and functions 
 *    used to display search results in a popup window througth an ajax request
 *
 *    Version: 1.8.1  - Coroico (coroico@wangba.fr) 
 *
 *    Jason Coward (opengeek - jason@opengeek.com)
 *    Kyle Jaebker (kylej - kjaebker@muddydogpaws.com)
 *    Ryan Thrash (rthrash - ryan@vertexworks.com) 
 *
 * Updated: 02/10/2008 - whereSearch, withTvs, new sql query, debug, subSearch
 * Updated: 24/07/2008 - Added rank, order & filter, breadcrumbs, tvPhx parameters 
 * Updated: O2/07/2008 - New extract algorithm, search in tv, jot and maxygallery
 * Updated: O2/07/2008 - Added Phx templating & chunk parameters
 * Updated: 06/03/2008 - Added Hidden from menu and advanced search
 * Updated: 01/02/2008 - Added several fixes and a security patch
 * Updated: 17/11/2007 - Added IDs document selection
 * Updated: 06/11/2007 - Encoding troubles corrected
 * 
 * Updated: 01/22/07 - Added templating/language/mootools support
 * Updated: 01/03/07 - Added fixes/updates from forum
 * Updated: 09/18/06 - Added user permissions to searching
 * Updated: 03/20/06 - All variables are set in the main snippet & snippet call  
*/

// some usefull class definition
define('HIGHLIGHT_CLASS','ajaxSearch_highlight');             // token used for highlighting
define('PREFIX_AJAX_RESULT_CLASS','AS_ajax_result');          // ajax result prefix class
define('PREFIX_RESULT_CLASS','ajaxSearch_result');            // non-ajax result prefix class
define('INTROFAILURE_CLASS','AS_ajax_resultsIntroFailure');   // intro failure class

include_once dirname(__FILE__)."/search.class.inc.php";

class AjaxSearchPopup extends Search{

// public
  var $cfg = array();  // configuration parameters

// private

  // conversion code name between html page character encoding and Mysql character encoding
  // Some others conversions should be added if needed
  var $pageCharset = array(
    'utf8' => 'UTF-8',
    'latin1' => 'ISO-8859-1',
    'latin2' => 'ISO-8859-2'
    );

  var $language;        // language name
  var $_lang;           // language labels

  var $searchString;    // term searched
  var $advSearch;       // advanced search option

  var $subSearch;       // search in a subdomain
  var $subSearchName;   // sub search function name

  var $pgCharset;       // page charset
  var $dbCharset;       // database charset
  var $needsConvert;    // charset conversion boolean
  var $pcreModifier;    // PCRE modifier
  var $isPhp5;          // Php version >= 5.0.0

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

  
  function AjaxSearchPopup($cfg) {
    // load configuration snippet options
    $this->cfg = $cfg;
  }

/**
 * run : run the search
 */
  function run(){

    global $modx;

    if (!$this->loadConfig($msg)) return $msg;  // load configuration file for user functions
    $this->setDebug();    // set debug levels
    
    if ($this->dbg) {
      $this->asDebug->dbgLog($this->readConfigFile(),"AjaxSearch - Configuration file " . $this->cfg['config']);   // configuration file
      $this->asDebug->dbgLog($this->cfg,"AjaxSearch - User configuration - Before parameter checking");   // user parameters
    }
        
    $this->loadLang();    // load language labels

    // set page and database charset
    if (!$this->setCharset($msg)) return $msg;

    if (!$this->checkAjaxSearchParams($msg)) return $msg;  // Check user parameters
    
    $this->initVariables(); // initialize some variables and functions
    
    if ($this->validSearchString($msg)) {

      $this->initClassVariables(); // initialize class variables

      // get the Ids
      $this->getListIDs();

      if ($this->dbg) $this->asDebug->dbgLog($this->cfg,"AjaxSearchPopup - User configuration - Before doSearch");   // user parameters

      // Do the search and get the results
      $rs = $this->doSearch();

      $nbrs = $modx->recordCount($rs);
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
          $this->setResultSearchable($this->searchResults[$i]);

          // parse the template and output the result
          $this->chkResult->AddVar("as", $this->varResult);
          $results .= $this->chkResult->Render()."\n";
          unset($this->varResult);
          unset($this->chkResult);

          if ($i == $this->cfg['ajaxMax']-1) {
            //If more than max results so link to all results
            $this->setMoreResultsLink();
            break;
          }
        }
        // output results
        $this->varResults['noResults'] = 0;
        $this->varResults['listResults'] = $results;
      } else {
        // no results found
        $this->varResults['noResults'] = 1;
        $this->varResults['noResultClass'] = INTROFAILURE_CLASS;
        $this->varResults['noResultText'] = $this->_lang['as_resultsIntroFailure'];
      }
    } else {
      // message to show if search was performed but for something invalid
      $this->varResults['noResults'] = 1;
      $this->varResults['noResultClass'] = INTROFAILURE_CLASS;
      $this->varResults['noResultText'] = $msg;
    }

    $this->chkResults->AddVar("as", $this->varResults);
    $results = $this->chkResults->Render()."\n";
    unset($this->varResults);
    unset($this->chkResults);

    // UTF-8 conversion is required if mysql character set is different of 'utf8'
    if ($this->needsConvert) $results = mb_convert_encoding($results,"UTF-8",$this->pgCharset);

    return $results;
  }

/**
 * setPageCharset : Set the Page and database charset
 */
  function setCharset(& $msgErr){
  
    $valid = false;
    $msgErr = '';
    $this->setDatabaseCharset();  // initialize the database charset

    // Ajax window charset = UTF-8 and should to be coherent with database
    if (isset($this->dbCharset) && isset($this->pageCharset[$this->dbCharset])) {
      // check if the mbstring extension is required and loaded
      if ($this->dbCharset != 'utf8' && !extension_loaded('mbstring')) {
        $msgErr = "php_mbstring extension required";
      }
      else {
        mb_internal_encoding("UTF-8");
        $this->pgCharset = $this->pageCharset[$this->dbCharset];
        $valid = true;
      }
    } elseif (!isset($this->dbCharset)){
      $msgErr = "AjaxSearch: database_connection_charset not set. Check your config file"; 
    } elseif (!strlen($this->dbCharset)){
      $msgErr = "AjaxSearch: database_connection_charset is null. Check your config file";
    } else {
      // if you get this message, simply update the $pageCharset array above with the appropriate mapping between Mysql Charset and Html charset
      // eg: 'latin2' => 'ISO-8859-2' and send me a email to update the source code
      $msgErr = "AjaxSearch: unknown database_connection_charset = {$this->dbCharset}<br />Add the appropriate Html charset mapping in the ajaxSearch.php file";
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

    // load some new parameters from configration file if needed
    if ($this->cfg['subSearch'] != ''){
      $sbsch_array = explode(',',$this->cfg['subSearch']);
      $this->subSearchName = $sbsch_array[0];
      $this->subSearchSel = $sbsch_array[1];
      if (!function_exists($this->subSearchName)) {
        $msgErr = "<br /><h3>Error: search function $this->subSearchName not defined in the configuration file: $this->config !</h3><br />";
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

    $this->initChkVariables();   // Initialize chunkie variables

    $this->initBreadcrumbs();    // Initialize breadcrumbs

    $this->initTvPhx();          // Initialize tvPhx
  }

/**
 * validSearchString : Validation of input search term
 */
  function validSearchString(& $msgErr){

    $valid = false;
    $msgErr = '';

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

    if (mb_strlen($this->searchString) >= $this->cfg['minChars']){
      //Clean the searchString
      $valid = $this->stripSearchString($this->searchString,$this->cfg['stripInput'],$this->advSearch);
      if (!$valid) $msgErr = $this->_lang['as_resultsIntroFailure'];
    }
    else {
      $valid = false;
      $msgErr = sprintf($this->_lang['as_minChars'],$this->cfg['minChars']);
    }
    return $valid;
  }

/**
 * initChkVariables : Initialize the Chunkie variables
 */
  function initChkVariables(){

    // include chunckie class and read templates once
    if (!class_exists('asChunkie')) include_once AS_PATH . "classes/chunkie.class.inc.php";
    $tplResults = $this->cfg['tplAjaxResults'];
    if ($tplResults == '') $tplResults = "@FILE:" . AS_SPATH . 'templates/ajaxResults.tpl.html'; 
    $this->chkResults = new asChunkie($tplResults);   // results outer

    $tplResult = $this->cfg['tplAjaxResult'];
    if ($tplResult == '') $tplResult = "@FILE:" . AS_SPATH . 'templates/ajaxResult.tpl.html';         
    $this->chkResult = new asChunkie($tplResult);     // result
    $this->tplRes = "@CODE:" . $this->chkResult->template;

    if ($this->dbgTpl) {
      $this->asDebug->dbgLog($this->chkResults->getTemplate($tplResult),"AjaxSearch - tplResult template " . $tplResult);
      $this->asDebug->dbgLog($this->chkResult->getTemplate($tplResults),"AjaxSearch - tplResults template" . $tplResults);
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