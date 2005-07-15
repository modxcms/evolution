<?php

// set the include_once path
if(version_compare(phpversion(), "4.3.0")>=0) {
	set_include_path("../includes/"); // include path the new way
} else {
	ini_set("include_path", "../includes/"); // include path the old way
}

define("IN_MANAGER_MODE", "true"); 	// we use this to make sure files are accessed through
									// the manager instead of seperately.
// include the database configuration file
include_once "config.inc.php";

// connect to the database
if(@!$modxDBConn = mysql_connect($database_server, $database_user, $database_password)) {
	die("Failed to create the database connection!");
} else {
	mysql_select_db($dbase);
}

// get the settings from the database
include_once "settings.inc.php";

// include version info
include_once "version.inc.php";

// include the logger
include_once "log.class.inc.php";

// include the crypto thing
include_once "crypt.class.inc.php";


session_start();

// Initialize System Alert Message Queque
if (!isset($_SESSION['SystemAlertMsgQueque'])) $_SESSION['SystemAlertMsgQueque'] = array();
$SystemAlertMsgQueque = &$_SESSION['SystemAlertMsgQueque'];

// include_once the error handler
include_once "error.class.inc.php";
$e = new errorHandler;

$cookieKey = substr(md5($site_id."Admin-User"),0,15);

// initiate the content manager class
include_once "document.parser.class.inc.php";
$modx = new DocumentParser;
$modx->loadExtension("ManagerAPI");
$modx->getSettings();
$etomite = &$modx; // for backward compatibility


$username = htmlspecialchars($_POST['username']);
$givenPassword = htmlspecialchars($_POST['password']);
$captcha_code = $_POST['captcha_code'];

// invoke OnBeforeManagerLogin event
$modx->invokeEvent("OnBeforeManagerLogin",
						array(
							"username"		=> $username,
							"userpassword"	=> $givenPassword,
							"rememberme"	=> $_POST['rememberme']
						));

$sql = "SELECT $dbase.".$table_prefix."manager_users.*, $dbase.".$table_prefix."user_attributes.* FROM $dbase.".$table_prefix."manager_users, $dbase.".$table_prefix."user_attributes WHERE $dbase.".$table_prefix."manager_users.username REGEXP BINARY '^".$username."$' and $dbase.".$table_prefix."user_attributes.internalKey=$dbase.".$table_prefix."manager_users.id;";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);

if($limit==0 || $limit>1) {
		$e->setError(900);
		$e->dumpError();
}	

$row = mysql_fetch_assoc($rs);
	
$internalKey 			= $row['internalKey'];
$dbasePassword 			= $row['password'];
$failedlogins 			= $row['failedlogincount'];
$blocked 				= $row['blocked'];
$blockeduntildate		= $row['blockeduntil'];
$blockedafterdate		= $row['blockedafter'];
$registeredsessionid	= $row['sessionid'];
$role					= $row['role'];
$lastlogin				= $row['lastlogin'];
$nrlogins				= $row['logincount'];
$fullname				= $row['fullname'];
//$sessionRegistered 		= checkSession();
$email 					= $row['email'];

// get the user settings from the database
include_once "user_settings.inc.php";

if($failedlogins>=3 && $blockeduntildate>time()) {	// blocked due to number of login errors.
		session_destroy();
		session_unset();
		$e->setError(902);
		$e->dumpError();
}

if($failedlogins>=3 && $blockeduntildate<time()) {	// blocked due to number of login errors, but get to try again
	$sql = "UDPATE $dbase.".$table_prefix."user_attributes SET failedlogincount='0', blockeduntil='".(time()-1)."' where internalKey=$internalKey";
	$rs = mysql_query($sql);
}

if($blocked=="1") { // this user has been blocked by an admin, so no way he's loggin in!
	session_destroy();
	session_unset();
	$e->setError(903);
	$e->dumpError();
}

// blockuntil
if($blockeduntildate>time()) { // this user has a block until date
	session_destroy();
	session_unset();
	$output = jsAlert("You are blocked and cannot log in! Please try again later.");
	return;
}

// blockafter
if($blockedafterdate>0 && $blockedafterdate<time()) { // this user has a block after date
	session_destroy();
	session_unset();
	$output = jsAlert("You are blocked and cannot log in! Please try again later.");
	return;
}

// allowed ip
if ($allowed_ip) {
	if (strpos($allowed_ip,$_SERVER['REMOTE_ADDR'])===false) {
		$output = jsAlert("You are not allowed to login from this location.");
		return;
	}
}

// allowed days
if ($allowed_days) {
	$date = getdate();
	$day = $date['wday']+1;
	if (strpos($allowed_days,"$day")===false) {
		$output = jsAlert("You are not allowed to login at this time. Please try again later.");
		return;
	}		
}

// invoke OnManagerAuthentication event
$rt = $modx->invokeEvent("OnManagerAuthentication",
						array(
							"userid"		=> $internalKey,
							"username"		=> $username,
							"userpassword"	=> $givenPassword,
							"savedpassword"	=> $dbasePassword,
							"rememberme"	=> $_POST['rememberme']
						));
// check if plugin authenticated the user

if (is_array($rt) && !in_array(TRUE,$rt)) {
	// check user password - local authentication
	if($dbasePassword != md5($givenPassword)) {
			$e->setError(901);
			$newloginerror = 1;
	}
}

if($use_captcha==1) {
	if($_SESSION['veriword']!=$captcha_code) {
		$e->setError(905);
		$newloginerror = 1;
	}
}

if($newloginerror==1) {
	$failedlogins += $newloginerror;
	if($failedlogins>=3) { //increment the failed login counter, and block!
		$sql = "update $dbase.".$table_prefix."user_attributes SET failedlogincount='$failedlogins', blockeduntil='".(time()+(1*60*60))."' where internalKey=$internalKey";
		$rs = mysql_query($sql);
	} else { //increment the failed login counter
		$sql = "update $dbase.".$table_prefix."user_attributes SET failedlogincount='$failedlogins' where internalKey=$internalKey";
		$rs = mysql_query($sql);
	}
	session_destroy();
	session_unset();
	$e->dumpError();
}

$currentsessionid = session_id();

if(!isset($_SESSION['mgrValidated'])) {
	$sql = "update $dbase.".$table_prefix."user_attributes SET failedlogincount=0, logincount=logincount+1, lastlogin=thislogin, thislogin=".time().", sessionid='$currentsessionid' where internalKey=$internalKey";
	$rs = mysql_query($sql);
}

# Added by Raymond: 
$_SESSION['usertype'] = 'manager'; // user is a backend user  

// get permissions
//$_SESSION['mgrValid']=base64_encode($givenPassword); //??
//$_SESSION['mgrUser']=base64_encode($username);		// ??
//$_SESSION['sessionRegistered']=$sessionRegistered; // to be removed
$_SESSION['mgrShortname']=$username;
$_SESSION['mgrFullname']=$fullname;
$_SESSION['mgrEmail']=$email;
$_SESSION['mgrValidated']=1;
$_SESSION['mgrInternalKey']=$internalKey;
$_SESSION['mgrFailedlogins']=$failedlogins;
$_SESSION['mgrLastlogin']=$lastlogin;
$_SESSION['mgrLogincount']=$nrlogins; // login count
$_SESSION['mgrRole']=$role;
$sql="SELECT * FROM $dbase.".$table_prefix."user_roles where id=".$role.";";
$rs = mysql_query($sql); 
$row = mysql_fetch_assoc($rs);
$_SESSION['mgrPermissions'] = $row;

// get user's document groups
$dg='';$i=0;
$tblug = $dbase.".".$table_prefix."member_groups";
$tbluga = $dbase.".".$table_prefix."membergroup_access";
$sql = "SELECT uga.documentgroup
		FROM $tblug ug
		INNER JOIN $tbluga uga ON uga.membergroup=ug.user_group
		WHERE ug.member =".$internalKey;
$rs = mysql_query($sql); 
while ($row = mysql_fetch_row($rs)) $dg[$i++]=$row[0];
$_SESSION['mgrDocgroups'] = $dg;


if($_POST['rememberme']==1) {
	$rc4 = new rc4crypt;
	$username = $_POST['username'];
	$thepasswd = substr($site_id,-5)."crypto"; // create a password based on site id
	$cookieString = $rc4->endecrypt($thepasswd,$username);
	setcookie($cookieKey, $cookieString, time()+604800, "/", "", 0);
} else {
	setcookie($cookieKey, "",time()-604800, "/", "", 0);
}

$log = new logHandler;
$log->initAndWriteLog("Logged in", $modx->getLoginUserID(), $_SESSION['mgrShortname'], "58", "-", "Etomite");

// invoke OnManagerLogin event
$modx->invokeEvent("OnManagerLogin",
						array(
							"userid"		=> $internalKey,
							"username"		=> $username,
							"userpassword"	=> $givenPassword,
							"rememberme"	=> $_POST['rememberme']
						));

header("Location: ../");

// show javascript alert	
function jsAlert($msg){
	echo "<script>window.setTimeout(\"alert('".addslashes(mysql_escape_string($msg))."')\",10);history.go(-1)</script>";
}

?>