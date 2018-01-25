<?php
/**
 * active_user_locks.php
 *
 * This page is requested by several actions to keep elements/resources locked
 */
include_once(dirname(__FILE__).'/../../assets/cache/siteManager.php');
require_once(dirname(__FILE__).'/protect.inc.php');

$ok = false;
if ($rt = @ include_once('config.inc.php')) {
// Keep it alive
  startCMSSession();
  if($_GET['tok'] == md5(session_id())) {
	define('IN_MANAGER_MODE','true');
	include_once(dirname(__FILE__).'/document.parser.class.inc.php');
	$modx = new DocumentParser;
	$modx->db->connect();
	
	if($modx->elementIsLocked($_GET['type'], $_GET['id'], true)) {
		$modx->lockElement($_GET['type'], $_GET['id'], true);
		$ok = true;
	}
  }
}

header('Content-type: application/json');
if($ok) {
    echo '{status:"ok"}';
} else {
  echo '{status:"null"}';
}