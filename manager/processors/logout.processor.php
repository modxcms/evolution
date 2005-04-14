<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

$internalKey = $_SESSION['internalKey'];
$username = $_SESSION['shortname'];

// invoke OnBeforeManagerLogout event
$modx->invokeEvent("OnBeforeManagerLogout",
						array(
							"userid"		=> $internalKey,
							"username"		=> $username
						));

@session_destroy(); // Raymond:suppress error generation on first destroy
$sessionID = md5(date('d-m-Y H:i:s'));
session_id($sessionID);
session_start();
session_destroy();

// invoke OnManagerLogout event
$modx->invokeEvent("OnManagerLogout",
						array(
							"userid"		=> $internalKey,
							"username"		=> $username
						));

// show login screen
header("Location: ./");
?>