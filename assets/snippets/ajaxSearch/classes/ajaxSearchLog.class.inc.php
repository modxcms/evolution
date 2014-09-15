<?php
/* -----------------------------------------------------------------------------
* Snippet: AjaxSearch
* -----------------------------------------------------------------------------
* @package  AjaxSearchLog
*
* @author       Coroico - www.evo.wangba.fr
* @version      1.10.1
* @date         05/06/2014
*
* Purpose:
*    The AjaxSearchLog class contains all functions used to Log AjaxSearch requests
*
*/

define('CMT_MAX_LENGTH', 100);

define('CMT_MAX_LINKS', 3);

define('LOG_TABLE_NAME', 'ajaxsearch_log');

define('PURGE', 200);

define('COMMENT_JSDIR', 'js/comment');

class AjaxSearchLog {

    // public variables
    var $log = '0:0';
    var $logcmt;

    // private variables
    var $_tbName;
    var $_purge;

    /*
    *  Constructs the ajaxSearchLog object
    *
    *  @access public
    *  @param string $log log parameter
    */
    function AjaxSearchLog($log='0:0') {
        global $modx;
        $this->_tbName = $modx->getFullTableName(LOG_TABLE_NAME);
        $asLog_array = explode(':', $log);
        $this->log = (int)$asLog_array[0];
        if ($this->log > 0 && $this->log < 3) {
            $this->_purge = isset($asLog_array[2]) ? (int)$asLog_array[2] : PURGE;
            if ($this->_purge < 0) $this->_purge = PURGE;
            $this->_initLogTable();

            $this->logcmt = isset($asLog_array[1]) ? (int)$asLog_array[1] : 0;
            if ($this->logcmt) {
                $jsInclude = AS_SPATH . COMMENT_JSDIR . '/ajaxSearchCmt.js';
                $modx->regClientStartupScript($jsInclude);
            }
        } else {
            $this->log = 0;
        }
    }
    /*
    *  Create the ajaxSearch log table if needed
    */
    function _initLogTable() {
        global $modx;
        $db = $modx->db->config['dbase'];
        $tbn = $modx->db->config['table_prefix'] . LOG_TABLE_NAME;
        if (!$this->_existLogTable($db, $tbn)) {

            $SQL_CREATE_TABLE = "CREATE TABLE " . $this->_tbName . " (
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
            if (!$modx->db->query($SQL_CREATE_TABLE)) {
                return false;
            }
            return true;
        }
    }
    /*
    *  Check if the table exists or not
    */
    function _existLogTable($db, $tbName) {
        global $modx;
        $SHOW_TABLES = "SHOW TABLES FROM $db LIKE '$tbName';";
        $exec = $modx->db->query($SHOW_TABLES);
        return $modx->db->getRecordCount($exec);
    }
    /*
    *  Write a log record in database
    *
    *  @access public
    *  @param array $rs record set
    *  return the id of the record logged
    */
    function setLogRecord($rs) {
        global $modx;
        if ($this->_purge) $this->_purgeLogs();
        $lastid = $modx->db->insert(
			array(
				'searchstring' => $modx->db->escape($rs['searchString']),
				'nb_results' => $rs['nbResults'],
				'results' => trim($rs['results']),
				'comment' => '',
				'as_call' => $rs['asCall'],
				'as_select' => $rs['asSelect'],
				'ip' => $_SERVER['REMOTE_ADDR'],
			), $this->_tbName);
        return $lastid;
    }
    /*
    *  Purge the log table
    */
    function _purgeLogs() {
        global $modx;

        $rs = $modx->db->select('count(*) AS count', $this->_tbName);
        $nbLogs = $modx->db->getValue($rs);

        if ($nbLogs + 1 > $this->_purge) {
            $modx->db->delete($this->_tbName);
        }
    }
    /*
    * Update a comment of a search record in database
    *
    * @access public
    * @param int $logid id of the log
    * @param string $ascmt comment
    */
    function updateComment($logid, $ascmt) {
        global $modx;
        $fields['comment'] = $modx->db->escape($ascmt);
        $where = "id='" . $logid . "'";
        $modx->db->update($fields, $this->_tbName, $where);
        return true;
    }
}
//==============================================================================
/* The code below handles comment sent if the $_POST variables are set.
Used when the user post comment from the ajaxSearch results window  */
if ($_POST['logid'] && $_POST['ascmt']) {
    $ascmt = strip_tags($_POST['ascmt']);
    $logid = intval($_POST['logid']);
    $safeCmt = (strlen($ascmt) < CMT_MAX_LENGTH) && (substr_count($ascmt, 'http') < CMT_MAX_LINKS);
    if (($ascmt != '') && ($logid > 0) && $safeCmt) {

        define('MODX_API_MODE', true);

        include_once (MODX_MANAGER_PATH . 'includes/document.parser.class.inc.php');
        $modx = new DocumentParser;
        $modx->db->connect();
        $modx->getSettings();
        $asLog = new AjaxSearchLog();
        $asLog->updateComment($logid, $ascmt);
        echo "comment about record " . $logid . " registered";
    } else {
        echo "ERROR: comment rejected";
    }
}
