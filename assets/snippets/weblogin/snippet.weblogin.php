<?php

// Set Snippet Paths 
$snipPath = $modx->config['base_path'] . "assets/snippets/";
$wl_base_path = str_replace('\\', '/', dirname(__FILE__)) . '/';

// check if inside manager
if ($m = $modx->isBackend()) {
	return ''; // don't go any further when inside manager
}

// deprecated params - only for backward compatibility
if(isset($loginid)) $loginhomeid=$loginid;
if(isset($logoutid)) $logouthomeid = $logoutid;
if(isset($template)) $tpl = $template;

// Snippet customize settings
$liHomeId	= isset($loginhomeid)? array_filter(array_map('intval', explode(',', $loginhomeid))):array($modx->config['login_home'],$modx->documentIdentifier);
$loHomeId	= isset($logouthomeid)? $logouthomeid:$modx->documentIdentifier;
$pwdReqId	= isset($pwdreqid)? $pwdreqid:0;
$pwdActId	= isset($pwdactid)? $pwdactid:0;
$loginText	= isset($logintext) && $logintext!='' ? $logintext:'Login';
$logoutText	= isset($logouttext) && $logouttext!='' ? $logouttext:'Logout';
$tpl		= isset($tpl)? $tpl:"";
$focusInput = isset($focusInput)? $focusInput : 1;

// System settings
$webLoginMode = isset($_REQUEST['webloginmode'])? $_REQUEST['webloginmode']: '';
$isLogOut		= $webLoginMode=='lo' ? 1:0;
$isPWDActivate	= $webLoginMode=='actp' ? 1:0;
$isPostBack		= count($_POST) && (isset($_POST['cmdweblogin']) || isset($_POST['cmdweblogin_x']));
$txtPwdRem 		= isset($_REQUEST['txtpwdrem'])? $_REQUEST['txtpwdrem']: 0;
$isPWDReminder	= $isPostBack && $txtPwdRem=='1' ? 1:0;

$site_id = isset($site_id)? $site_id: '';
$cookieKey = substr(md5($site_id."Web-User"),0,15);

// Start processing
include_once $wl_base_path."weblogin.common.inc.php";

if ($isPWDActivate || $isPWDReminder || $isLogOut || $isPostBack) {
	// include the logger class
	include_once $modx->config['site_manager_path'] . "includes/log.class.inc.php";
	include_once $wl_base_path."weblogin.processor.inc.php";
}

include_once $wl_base_path."weblogin.inc.php";

// Return
return $output;
