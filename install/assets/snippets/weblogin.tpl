//<?php
/**
 * WebLogin
 * 
 * Allows webusers to login to protected pages in the website, supporting multiple user groups
 *
 * @category 	snippet
 * @version 	1.1.3
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties &loginhomeid=Login Home Id;string; &logouthomeid=Logout Home Id;string; &logintext=Login Button Text;string; &logouttext=Logout Button Text;string; &tpl=Template;string;
 * @internal	@modx_category Login
 * @internal    @installset base, sample
 * @documentation [+site_url+]assets/snippets/weblogin/docs/weblogin.html
 * @documentation http://www.opensourcecms.com/news/details.php?newsid=660
 * @reportissues https://github.com/modxcms/evolution
 * @author      Created By Raymond Irving April, 2005
 * @author      Ryan Thrash http://thrash.me
 * @author      Jason Coward http://opengeek.com
 * @author      Shaun McCormick, garryn, Dmi3yy
 * @lastupdate  19/04/2016
 */

# Set Snippet Paths 
$snipPath = $modx->config['base_path'] . "assets/snippets/";

# check if inside manager
if ($m = $modx->isBackend()) {
	return ''; # don't go any further when inside manager
}

# deprecated params - only for backward compatibility
if(isset($loginid)) $loginhomeid=$loginid;
if(isset($logoutid)) $logouthomeid = $logoutid;
if(isset($template)) $tpl = $template;

# Snippet customize settings
$liHomeId	= isset($loginhomeid)? array_filter(array_map('intval', explode(',', $loginhomeid))):array($modx->config['login_home'],$modx->documentIdentifier);
$loHomeId	= isset($logouthomeid)? $logouthomeid:$modx->documentIdentifier;
$pwdReqId	= isset($pwdreqid)? $pwdreqid:0;
$pwdActId	= isset($pwdactid)? $pwdactid:0;
$loginText	= isset($logintext) && $logintext!='' ? $logintext:'Login';
$logoutText	= isset($logouttext) && $logouttext!='' ? $logouttext:'Logout';
$tpl		= isset($tpl)? $tpl:"";
$focusInput = isset($focusInput)? $focusInput : 1;

# System settings
$webLoginMode = isset($_REQUEST['webloginmode'])? $_REQUEST['webloginmode']: '';
$isLogOut		= $webLoginMode=='lo' ? 1:0;
$isPWDActivate	= $webLoginMode=='actp' ? 1:0;
$isPostBack		= count($_POST) && (isset($_POST['cmdweblogin']) || isset($_POST['cmdweblogin_x']));
$txtPwdRem 		= isset($_REQUEST['txtpwdrem'])? $_REQUEST['txtpwdrem']: 0;
$isPWDReminder	= $isPostBack && $txtPwdRem=='1' ? 1:0;

$site_id = isset($site_id)? $site_id: '';
$cookieKey = substr(md5($site_id."Web-User"),0,15);

# Start processing
include_once $snipPath."weblogin/weblogin.common.inc.php";
include_once ($modx->config['site_manager_path'] . "includes/crypt.class.inc.php");

if ($isPWDActivate || $isPWDReminder || $isLogOut || $isPostBack) {
	# include the logger class
	include_once $modx->config['site_manager_path'] . "includes/log.class.inc.php";
	include_once $snipPath."weblogin/weblogin.processor.inc.php";
}

include_once $snipPath."weblogin/weblogin.inc.php";

# Return
return $output;
