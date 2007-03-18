<?php
/**
 * session_keepalive.php
 *
 * This page is requested once in awhile to keep the session alive and kicking.
 */
require_once(dirname(__FILE__).'/protect.inc.php');

if ($rt = @ include_once('config.inc.php')) {
// Keep it alive
  startCMSSession(); 

  header('Location: ' . MODX_BASE_URL . 'manager/media/script/_session.gif?rnd=' . intval($_REQUEST['rnd']));
}
