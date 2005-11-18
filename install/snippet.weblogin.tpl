# WebLogin 1.0
# Created By Raymond Irving 2004
#::::::::::::::::::::::::::::::::::::::::
# Usage: 	
#	Allows a web user to login to the website
#
# Params:	
#	&loginhomeid 	- (Optional)
#		redirects the user to first authorized page in the list.
#		If no id was specified then the login home page id or 
#		the current document id will be used
#
#	&logouthomeid 	- (Optional)
#		document id to load when user logs out	
#
#	&pwdreqid 	- (Optional)
#		document id to load after the user has submited
#		a request for a new password
#
#	&pwdactid 	- (Optional)
#		document id to load when the after the user has activated
#		their new password
#
#	&logintext		- (Optional) 
#		Text to be displayed inside login button (for built-in form)
#
#	&logouttext 	- (Optional)
#		Text to be displayed inside logout link (for built-in form)
#	
#	&tpl			- (Optional)
#		Chunk name or document id to as a template
#				  
#	Note: Templats design:
#			section 1: login template
#			section 2: logout template 
#			section 3: password reminder template 
#
#			See weblogin.tpl for more information
#
# Examples:
#
#	[[WebLogin? &loginhomeid=`8` &logouthomeid=`1`]] 
#
#	[[WebLogin? &loginhomeid=`8,18,7,5` &tpl=`Login`]] 

# Set Snippet Paths 
$snipPath = $modx->config['base_path'] . "assets/snippets/";

# check if inside manager
if ($m = $modx->insideManager()) {
	return ''; # don't go any further when inside manager
}

# deprecated params - only for backward compatibility
if(isset($loginid)) $loginhomeid=$loginid;
if(isset($logoutid)) $logouthomeid = $logoutid;
if(isset($template)) $tpl = $template;

# Snippet customize settings
$liHomeId	= isset($loginhomeid)? explode(",",$loginhomeid):array($modx->config['login_home'],$modx->documentIdentifier);
$loHomeId	= isset($logouthomeid)? $logouthomeid:$modx->documentIdentifier;
$pwdReqId	= isset($pwdreqid)? $pwdreqid:0;
$pwdActId	= isset($pwdactid)? $pwdactid:0;
$loginText	= isset($logintext)? $logintext:'Login';
$logoutText	= isset($logouttext)? $logouttext:'Logout';
$tpl		= isset($tpl)? $tpl:"";

# System settings
$webLoginMode = isset($_REQUEST['webloginmode'])? $_REQUEST['webloginmode']: '';
$isLogOut		= $webLoginMode=='lo' ? 1:0;
$isPWDActivate	= $webLoginMode=='actp' ? 1:0;
$isPostBack		= count($_POST) && isset($_POST['cmdweblogin']);
$isPWDReminder	= $isPostBack && $_REQUEST['txtpwdrem']=='1' ? 1:0;

$site_id = isset($site_id)? $site_id: '';
$cookieKey = substr(md5($site_id."Web-User"),0,15);

# Start processing
include_once $snipPath."weblogin/weblogin.common.inc.php";
include_once ($modx->config['base_path'] . "manager/includes/crypt.class.inc.php");

if ($isPWDActivate || $isPWDReminder || $isLogOut || $isPostBack) {
	# include the logger class
	include_once $modx->config['base_path'] . "manager/includes/log.class.inc.php";
	include_once $snipPath."weblogin/weblogin.processor.inc.php";
}

include_once $snipPath."weblogin/weblogin.inc.php";

# Return
return $output;
