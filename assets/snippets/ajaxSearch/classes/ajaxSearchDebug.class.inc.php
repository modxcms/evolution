<?php
/*
 * Title: AjaxSearchDebug
 * Purpose:
 *    The AjaxSearchDebug class contains all functions used to debug AjaxSearch
 *    Based on FirePHP. See http://www.firephp.org 
 *
 *    Version: 1.8.1  - Coroico (coroico@wangba.fr) 
 *    
 *    02/10/2008  
 *      
*/

define('AS_DBGFILE', dirname(__FILE__) . '/../ajaxSearch_log.txt');   // Name of debug file
define('AS_DBGFIREPHP', dirname(__FILE__) . '/FirePHPCore/FirePHP.class.php');   // FirePHP library location

class AjaxSearchDebug{

  var $typeDbg;    // 1: file  2: fireBug console

  var $asFirePhp;  // firePhp instance
  var $asDbgFd;    // file descriptor

  function AjaxSearchDebug($version,$level) {
  
    if (!(version_compare(phpversion(), "5.0.0", ">=")) && ($level < 0 )) $level = abs($level);
    $this->dbg = $level;
    $header = "AjaxSearch ".$version." - Php".phpversion()." - MySql ".mysql_get_server_info();

    if ($level > 0 && $level < 4) {  // debug trace in a file    
        $this->asDbgFd = fopen(AS_DBGFILE,'w+');
        $this->dbgLog($header);
        fclose($this->asDbgFd);
        $this->asDbgFd = fopen(AS_DBGFILE,'a+');
    }
    else if ($level > -4 && $level < 0) {  // debug trace in the firebug console
        include_once(AS_DBGFIREPHP);
        ob_start();
        $this->dbgLog($header);
    }
  }

/**
 *  set Debug log record
 */
  function dbgLog(){

    $args = func_get_args();

    if ($this->dbg > 0) {
      // write trace in a file 
      $when = date('[j-M-y h:i:s]  ');
      $nba = count($args);
      $result = $when;
      if ($nba > 1){
          $result .= $args[1] . " : ";
      }
      if (is_array($args[0])) {
        $result .= print_r($args[0], true)."\n";   
      }
      else $result .= $args[0] . "\n";
      fwrite($this->asDbgFd,$result);
      return true;
    }
    else {
      // write in Firebug console
      $args[] = 'INFO';
      //$instance = FirePHP::getInstance(true);
      require_once(AS_DBGFIREPHP);
      $instance = FirePHP::getInstance(true);
      return call_user_func_array(array($instance,'fb'),$args);
    }
    return;
  }
}
?>