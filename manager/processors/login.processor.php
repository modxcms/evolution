<?php
include_once(dirname(__FILE__)."/../../assets/cache/siteManager.php");
require_once(strtr(realpath(dirname(__FILE__)), '\\', '/').'/../includes/protect.inc.php');

    set_include_path(get_include_path() . PATH_SEPARATOR . "../includes/");

define("IN_MANAGER_MODE", "true");  // we use this to make sure files are accessed through
                                    // the manager instead of seperately.
// include the database configuration file
include_once "config.inc.php";

// start session
startCMSSession();


include_once "document.parser.class.inc.php";
$modx = new DocumentParser;
$modx->loadExtension("ManagerAPI");
$modx->getSettings();
$etomite = &$modx;

// get the settings from the database
include_once "settings.inc.php";

/*
 * include_once "version.inc.php"; //include version info. Use $modx->getVersionData()
 */

// include_once the language file
if(!isset($manager_language) || !file_exists(MODX_MANAGER_PATH."includes/lang/".$manager_language.".inc.php")) {
    $manager_language = "english"; // if not set, get the english language file.
}
$_lang = array();
include_once "lang/english.inc.php";
$length_eng_lang = count($_lang);

if($manager_language!="english" && file_exists(MODX_MANAGER_PATH."includes/lang/".$manager_language.".inc.php")) {
    include_once "lang/".$manager_language.".inc.php";
}

// include the logger
include_once "log.class.inc.php";

// include the crypto thing
include_once "crypt.class.inc.php";

// Initialize System Alert Message Queque
if (!isset($_SESSION['SystemAlertMsgQueque'])) $_SESSION['SystemAlertMsgQueque'] = array();
$SystemAlertMsgQueque = &$_SESSION['SystemAlertMsgQueque'];

// initiate the content manager class
 // for backward compatibility

$tbl_user_settings   = $modx->getFullTableName('user_settings');
$tbl_manager_users   = $modx->getFullTableName('manager_users');
$tbl_user_attributes = $modx->getFullTableName('user_attributes');

$username = $modx->db->escape(htmlspecialchars($_REQUEST['username'], ENT_NOQUOTES, $modx->config['modx_charset']));
$givenPassword = htmlspecialchars($_REQUEST['password'], ENT_NOQUOTES, $modx->config['modx_charset']);
$captcha_code = $_REQUEST['captcha_code'];
$rememberme= $_REQUEST['rememberme'];
$failed_allowed = $modx->config["failed_login_attempts"];

// invoke OnBeforeManagerLogin event
$modx->invokeEvent("OnBeforeManagerLogin",
                        array(
                            "username"      => $username,
                            "userpassword"  => $givenPassword,
                            "rememberme"    => $rememberme
                        ));
$fields = 'mu.*, ua.*';
$from   = "{$tbl_manager_users} AS mu, {$tbl_user_attributes} AS ua";
$where  = "BINARY mu.username='{$username}' and ua.internalKey=mu.id";
$rs = $modx->db->select($fields, $from,$where);
$limit = $modx->db->getRecordCount($rs);

if($limit==0 || $limit>1) {
    jsAlert($_lang["login_processor_unknown_user"]);
    return;
}

$row = $modx->db->getRow($rs);

$internalKey            = $row['internalKey'];
$dbasePassword          = $row['password'];
$failedlogins           = $row['failedlogincount'];
$blocked                = $row['blocked'];
$blockeduntildate       = $row['blockeduntil'];
$blockedafterdate       = $row['blockedafter'];
$registeredsessionid    = $row['sessionid'];
$role                   = $row['role'];
$lastlogin              = $row['lastlogin'];
$nrlogins               = $row['logincount'];
$fullname               = $row['fullname'];
$email                  = $row['email'];

// get the user settings from the database
$rs = $modx->db->select('setting_name, setting_value', $tbl_user_settings, "user='{$internalKey}' AND setting_value!=''");
while ($row = $modx->db->getRow($rs)) {
    ${$row['setting_name']} = $row['setting_value'];
}
// blocked due to number of login errors.
if($failedlogins>=$failed_allowed && $blockeduntildate>time()) {
    $modx->db->update(array('blocked'=>1),$tbl_user_attributes,"internalKey='{$internalKey}'");
    @session_destroy();
    session_unset();
    jsAlert($_lang["login_processor_many_failed_logins"]);
    return;
}

// blocked due to number of login errors, but get to try again
if($failedlogins>=$failed_allowed && $blockeduntildate<time()) {
	$fields = array();
	$fields['failedlogincount'] = '0';
	$fields['blockeduntil']     = time()-1;
    $modx->db->update($fields,$tbl_user_attributes,"internalKey='{$internalKey}'");
}

// this user has been blocked by an admin, so no way he's loggin in!
if($blocked=="1") { 
    @session_destroy();
    session_unset();
    jsAlert($_lang["login_processor_blocked1"]);
    return;
}

// blockuntil: this user has a block until date
if($blockeduntildate>time()) {
    @session_destroy();
    session_unset();
    jsAlert($_lang["login_processor_blocked2"]);
    return;
}

// blockafter: this user has a block after date
if($blockedafterdate>0 && $blockedafterdate<time()) {
    @session_destroy();
    session_unset();
    jsAlert($_lang["login_processor_blocked3"]);
    return;
}

// allowed ip
if ($allowed_ip) {
        if(($hostname = gethostbyaddr($_SERVER['REMOTE_ADDR'])) && ($hostname != $_SERVER['REMOTE_ADDR'])) {
          if(gethostbyname($hostname) != $_SERVER['REMOTE_ADDR']) {
            jsAlert($_lang["login_processor_remotehost_ip"]);
            return;
          }
        }
        if(!in_array($_SERVER['REMOTE_ADDR'], array_filter(array_map('trim', explode(',', $allowed_ip))))) {
          jsAlert($_lang["login_processor_remote_ip"]);
          return;
        }
}

// allowed days
if ($allowed_days) {
    $date = getdate();
    $day = $date['wday']+1;
    if (strpos($allowed_days,"$day")===false) {
        jsAlert($_lang["login_processor_date"]);
        return;
    }
}

// invoke OnManagerAuthentication event
$rt = $modx->invokeEvent("OnManagerAuthentication",
                        array(
                            "userid"        => $internalKey,
                            "username"      => $username,
                            "userpassword"  => $givenPassword,
                            "savedpassword" => $dbasePassword,
                            "rememberme"    => $rememberme
                        ));

// check if plugin authenticated the user

if (!isset($rt)||!$rt||(is_array($rt) && !in_array(TRUE,$rt)))
{
	// check user password - local authentication
	$tbl_manager_users = $modx->getFullTableName('manager_users');
	if(strpos($dbasePassword,'>')!==false)
	{
		if(!isset($modx->config['pwd_hash_algo']) || empty($modx->config['pwd_hash_algo'])) $modx->config['pwd_hash_algo'] = 'UNCRYPT';
		$user_algo = $modx->manager->getUserHashAlgorithm($internalKey);
		
		if($user_algo !== $modx->config['pwd_hash_algo'])
		{
			$bk_pwd_hash_algo = $modx->config['pwd_hash_algo'];
			$modx->config['pwd_hash_algo'] = $user_algo;
		}
		
		if($dbasePassword != $modx->manager->genHash($givenPassword, $internalKey))
		{
			jsAlert($_lang["login_processor_wrong_password"]);
			$newloginerror = 1;
		}
		elseif(isset($bk_pwd_hash_algo))
		{
			$modx->config['pwd_hash_algo'] = $bk_pwd_hash_algo;
			$field = array();
			$field['password'] = $modx->manager->genHash($givenPassword, $internalKey);
			$modx->db->update($field, $tbl_manager_users, "username='{$username}'");
		}
	}
	else
	{
		if($dbasePassword != md5($givenPassword))
		{
			jsAlert($_lang["login_processor_wrong_password"]);
			$newloginerror = 1;
		}
		else
		{
			$field = array();
			$field['password'] = $modx->manager->genHash($givenPassword, $internalKey);
			$modx->db->update($field, $tbl_manager_users, "username='{$username}'");
		}
	}
}

if($use_captcha==1) {
	if (!isset ($_SESSION['veriword'])) {
        jsAlert($_lang["login_processor_captcha_config"]);
		return;
	}
	elseif ($_SESSION['veriword'] != $captcha_code) {
        jsAlert($_lang["login_processor_bad_code"]);
        $newloginerror = 1;
    }
}

if($newloginerror) {
	//increment the failed login counter
    $failedlogins += 1;

    $fields = array('failedlogincount' => $failedlogins);
    if($failedlogins>=$failed_allowed) //block user for too many fail attempts
        $fields['blockeduntil'] = time()+($blocked_minutes*60);

    $modx->db->update($fields, $tbl_user_attributes, "internalKey='{$internalKey}'");

    if($failedlogins<$failed_allowed) { 
		//sleep to help prevent brute force attacks
        $sleep = (int)$failedlogins/2;
        if($sleep>5) $sleep = 5;
        sleep($sleep);
    }
	@session_destroy();
	session_unset();
    return;
}

$currentsessionid = session_id();

$_SESSION['usertype'] = 'manager'; // user is a backend user

// get permissions
$_SESSION['mgrShortname']=$username;
$_SESSION['mgrFullname']=$fullname;
$_SESSION['mgrEmail']=$email;
$_SESSION['mgrValidated']=1;
$_SESSION['mgrInternalKey']=$internalKey;
$_SESSION['mgrFailedlogins']=$failedlogins;
$_SESSION['mgrLastlogin']=$lastlogin;
$_SESSION['mgrLogincount']=$nrlogins; // login count
$_SESSION['mgrRole']=$role;
$rs = $modx->db->select('*', $modx->getFullTableName('user_roles'), "id='{$role}'");
$_SESSION['mgrPermissions'] = $modx->db->getRow($rs);

// successful login so reset fail count and update key values
if (isset($_SESSION['mgrValidated'])) {
	$modx->db->update(
			'failedlogincount=0, '
			. 'logincount=logincount+1, '
			. 'lastlogin=thislogin, '
			. 'thislogin=' . time() . ', '
			. "sessionid='{$currentsessionid}'", $tbl_user_attributes, "internalKey='{$internalKey}'"
	);
}

// get user's document groups
$i=0;
$rs = $modx->db->select(
	'uga.documentgroup',
	$modx->getFullTableName('member_groups')." ug
		INNER JOIN ".$modx->getFullTableName('membergroup_access')." uga ON uga.membergroup=ug.user_group",
	"ug.member='{$internalKey}'"
	);
$_SESSION['mgrDocgroups'] = $modx->db->getColumn('documentgroup', $rs);

if($rememberme == '1') {
    $_SESSION['modx.mgr.session.cookie.lifetime']= intval($modx->config['session.cookie.lifetime']);
	
	// Set a cookie separate from the session cookie with the username in it. 
	// Are we using secure connection? If so, make sure the cookie is secure
	global $https_port;
	
	$secure = (  (isset ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || $_SERVER['SERVER_PORT'] == $https_port);
	if ( version_compare(PHP_VERSION, '5.2', '<') ) {
		setcookie('modx_remember_manager', $_SESSION['mgrShortname'], time()+60*60*24*365, MODX_BASE_URL, '; HttpOnly' , $secure );
	} else {
		setcookie('modx_remember_manager', $_SESSION['mgrShortname'], time()+60*60*24*365, MODX_BASE_URL, NULL, $secure, true);
	}
} else {
    $_SESSION['modx.mgr.session.cookie.lifetime']= 0;
	
	// Remove the Remember Me cookie
	setcookie ('modx_remember_manager', "", time() - 3600, MODX_BASE_URL);
}

$log = new logHandler;
$log->initAndWriteLog("Logged in", $modx->getLoginUserID(), $_SESSION['mgrShortname'], "58", "-", "MODX");

// invoke OnManagerLogin event
$modx->invokeEvent("OnManagerLogin",
                        array(
                            "userid"        => $internalKey,
                            "username"      => $username,
                            "userpassword"  => $givenPassword,
                            "rememberme"    => $rememberme
                        ));

// check if we should redirect user to a web page
$id = intval($modx->db->getValue($modx->db->select('setting_value', $tbl_user_settings, "user='{$internalKey}' AND setting_name='manager_login_startup'")));
if($id>0) {
    $header = 'Location: '.$modx->makeUrl($id,'','','full');
    if($_POST['ajax']==1) echo $header;
    else header($header);
}
else {
    $header = 'Location: '.MODX_MANAGER_URL;
    if($_POST['ajax']==1) echo $header;
    else header($header);
}

// show javascript alert
function jsAlert($msg){
	global $modx;
    if($_POST['ajax']==1) echo $msg."\n";
    else {
        echo "<script>window.setTimeout(\"alert('".addslashes($modx->db->escape($msg))."')\",10);history.go(-1)</script>";
    }
}
