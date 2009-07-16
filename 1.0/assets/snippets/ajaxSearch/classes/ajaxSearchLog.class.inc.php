<?php
/*
 * Title: AjaxSearchLog
 * Purpose:
 *    The AjaxSearchLog class contains all functions used to Log AjaxSearch requests 
 *
 *    Version: 1.8.3  - Coroico (coroico@wangba.fr) 
 *    
 *    08/06/2009  
 *      
*/

// maximum length allowed for the comment. Otherwise the comment is rejected
define('CMT_MAX_LENGTH',100);

// maximum number of links allowed for the comment. Otherwise the comment is rejected
define('CMT_MAX_LINKS',3);    

define('LOG_TABLE_NAME','ajaxsearch_log');     // Name of the log table without modx prefix

class AjaxSearchLog{

  var $tbName;   // log table name
  var $purge;    // max number of search logged before a purge

  function AjaxSearchLog($purge) {
    global $modx;
    $this->tbName = $modx->getFullTableName(LOG_TABLE_NAME);
    $this->purge = $purge;
  }

/**
 *  initLogTable - Create the ajaxSearch log table if needed
 */
  function initLogTable(){
    global $modx;
    $db = $modx->db->config['dbase'];
    $tbn = $modx->db->config['table_prefix'] . LOG_TABLE_NAME;

    if (!$this->existLogTable($db,$tbn)){
      // creation of the log table
      $SQL_CREATE_TABLE = "CREATE TABLE " . $this->tbName . " (
          `id` smallint(5) NOT NULL auto_increment,          
          `searchstring` varchar(128) NOT NULL,
          `nb_results` smallint(5) NOT NULL,
          `results` mediumtext,
          `comment` mediumtext,
          `as_call` mediumtext,
          `as_select` mediumtext,
          `date` timestamp(12) NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
          `ip` varchar(255) NOT NULL,
          PRIMARY KEY  (`id`)
          ) ENGINE=MyISAM;";

      if  (!$modx->db->query($SQL_CREATE_TABLE)){
          return false;
      }
      return true;
    }
  }
/**
 *  existLogTable - check if the table exists or not
 */
  function existLogTable($db,$tbName){
    global $modx;
    $SHOW_TABLES = "SHOW TABLES FROM $db LIKE '$tbName';";
    $exec = $modx->db->query($SHOW_TABLES);
    return $modx->db->getRecordCount($exec);
  }
/**
 *  setLogRecord - write a log record in database
 *  
 *  return the id of the record logged  
 */
  function setLogRecord($rs){
    global $modx;

    if ($this->purge) $this->purgeLogs(); // purge the log table if needed
    
    $asString = $rs['searchString'];
    $asNbResults = $rs['nbResults'];
    $asResults = trim($rs['results']);
    $asCmt = ''; // record created without comment
    $asCall = $rs['asCall'];
    $asSelect = $rs['asSelect'];
    $asIp = $_SERVER['REMOTE_ADDR'];
    
    $INSERT_RECORD = "INSERT INTO " . $this->tbName . " (
      searchstring, nb_results, results, comment, as_call, as_select, ip
      ) VALUES ('$asString','$asNbResults','$asResults','$asCmt','$asCall','$asSelect','$asIp')";
    $modx->db->query($INSERT_RECORD);
    
    $lastid = $modx->db->getInsertId();
    return $lastid;
  }
/**
 *  purgeLogs - purge the log table  
 */  
  function purgeLogs() {
    global $modx;
    // get the number of logs
    $sql = "SELECT COUNT(*) AS count FROM " . $this->tbName;
    $rs = $modx->db->query($sql);
    $row = $modx->db->getRow($rs);
    $nbLogs = $row['count'];
    // purge the table
    if ($nbLogs+1 > $this->purge){
      $sql = "DELETE LOW_PRIORITY FROM " . $this->tbName;
      $rs = $modx->db->query($sql);
    }
  }
/**
 *  updateComment - update a comment of a search record in database
 */
  function updateComment($logid,$ascmt){
    global $modx;
    
    $fields['comment'] = $ascmt;
    $where = "id='" . $logid . "'";
    $modx->db->update($fields,$this->tbName,$where);
    return true;
  }
}

//==============================================================================

/* The code below handles comment sent if the $_POST variables are set. 
   Used when the user post comment from the ajaxSearch results window  */

if ( $_POST['logid'] && $_POST['ascmt'] ) {

  $ascmt = $_POST['ascmt'];
  $logid = $_POST['logid'];

  $safeCmt = (strlen($ascmt) < CMT_MAX_LENGTH) && (substr_count($ascmt,'http') < CMT_MAX_LINKS); 
  
  if (($ascmt != '') && ($logid > 0) && $safeCmt){
    // Setup the MODx API
    define('MODX_API_MODE', true);
    // initiate a new document parser
    include_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');
    $modx = new DocumentParser;
  
    $modx->db->connect();
    $modx->getSettings();

    $asLog = new AjaxSearchLog();
    $asLog->updateComment($logid,$ascmt);
    
    echo "comment about record " . $logid . " registered";
  }
  else {
    echo "ERROR: comment rejected";
  }
}
?>
