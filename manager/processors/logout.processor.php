<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

$internalKey = $modx->getLoginUserID();
$username = $_SESSION['mgrShortname'];


// invoke OnBeforeManagerLogout event
$modx->invokeEvent("OnBeforeManagerLogout",
						array(
							"userid"		=> $internalKey,
							"username"		=> $username
						));

//// Unset all of the session variables.
//$_SESSION = array();
// destroy session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', 0, MODX_BASE_URL);
}
//// now destroy the session
@session_destroy(); // this sometimes generate an error in iis
//$sessionID = md5(date('d-m-Y H:i:s'));
//session_id($sessionID);
//startCMSSession();
//session_destroy();

// invoke OnManagerLogout event
$modx->invokeEvent("OnManagerLogout",
						array(
							"userid"		=> $internalKey,
							"username"		=> $username
						));

// show login screen
header('Location: ' . MODX_BASE_URL . 'manager/');
?>
