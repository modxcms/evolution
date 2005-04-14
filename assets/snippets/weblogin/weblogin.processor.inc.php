<?php
# WebLogin 1.0
# Created By Raymond Irving 2004
#::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$dbase = $modx->dbConfig['dbase'];
$table_prefix = $modx->dbConfig['table_prefix'];

# process password activation
	if ($isPWDActivate==1){
		$id = $_REQUEST['wli'];
		$pwdkey = $_REQUEST['wlk'];

		$sql = "SELECT wu.*
				FROM $dbase.".$table_prefix."web_users wu 
				WHERE wu.id='".mysql_escape_string($id)."'";				
		$ds = $modx->dbQuery($sql);
		$limit = $modx->recordCount($ds);
		if($limit==1) {
			$row = $modx->fetchRow($ds);
			$username = $row["username"];
			list($newpwd,$newpwdkey) = explode("|",$row['cachepwd']);
			if($newpwdkey!=$pwdkey) {
				$output = jsAlert("Invalid password activation key. Your password was NOT activated.");
				return;
			}
			// activate new password
			$newpwd = md5($newpwd);
			$sql="UPDATE $dbase.".$table_prefix."web_users 
				  SET password = '".$newpwd."', cachepwd='' 
				  WHERE id=".$row['id'];
			$ds = $modx->dbQuery($sql);

			// invoke OnWebChangePassword event
			if(!$ds) 
				$modx->invokeEvent("OnWebChangePassword",
								array(
									"userid"		=> $id,
									"username"		=> $username,
									"userpassword"	=> $newpwd
								));
			
			if(!$ds) $output = jsAlert("Error while activating password.");
			else if(!$pwdActId) $output = jsAlert("Your new password was successfully activated.");
			else {
				// redirect to password activation notification page
				$url = $modx->makeURL($pwdActId);
				$modx->sendRedirect($url);
			}
		}
		else {
			// error
			$output = jsAlert("Error while loading user account. Please contact the Site Administrator");
		}
		return;
	}


# process password reminder
	if ($isPWDReminder==1){
		global $mailto;
		$email = $_POST['txtwebemail'];		
		$webpwdreminder_message = $modx->config['webpwdreminder_message'];
		$emailsubject = $modx->config['emailsubject'];
		$emailsender = $modx->config['emailsender'];
		$site_name = $modx->config['site_name'];
		// lookup account
		$sql = "SELECT wu.*, wua.fullname 
				FROM $dbase.".$table_prefix."web_users wu 
				INNER JOIN $dbase.".$table_prefix."web_user_attributes wua ON wua.internalkey=wu.id 
				WHERE wua.email='".mysql_escape_string($email)."'";
				
		$ds = $modx->dbQuery($sql);
		$limit = $modx->recordCount($ds);
		if($limit==1) {
			$newpwd = generatePassword(8);
			$newpwdkey = generatePassword(8); // activation key
			$row = $modx->fetchRow($ds);
			//save new password
			$sql="UPDATE $dbase.".$table_prefix."web_users 
				  SET cachepwd='".$newpwd."|".$newpwdkey."' 
				  WHERE id=".$row['id'];
			$modx->dbQuery($sql);
			// built activation url
			$url = "http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].(strlen($_SERVER["QUERY_STRING"])>0 ? "?".$_SERVER["QUERY_STRING"]:"");
			$url.= (strpos($url,"?")===false ? "?":"&")."webloginmode=actp&wli=".$row['id']."&wlk=".$newpwdkey;
			// replace placeholders and send email
			$message = str_replace("[+uid+]",$row['username'],$webpwdreminder_message);
			$message = str_replace("[+pwd+]",$newpwd,$message);
			$message = str_replace("[+ufn+]",$row['fullname'],$message);
			$message = str_replace("[+sname+]",$site_name,$message);
			$message = str_replace("[+semail+]",$emailsender,$message);
			$message = str_replace("[+surl+]",$url,$message);
			if(!mail($email, "New Password Activation for $site_name", $message, "From: ".$emailsender."\r\n"."X-Mailer: MODx Content Manager - PHP/".phpversion())) {
				// error 
				$output =  jsAlert("Error while sending mail to $email. Please contact the Site Administrator");
				return;
			}	
			if(!$pwdReqId) $output = jsAlert("Please check your email account ($email) for login instructions.");
			else {
				// redirect to password request notification page
				$url = $modx->makeURL($pwdReqId);
				$modx->sendRedirect($url);
			}
		}
		else {
			$output = jsAlert("We are sorry! We cannot locate an account using that email.");
		}

		return;
	
	}	


# process logout
	if ($isLogOut==1){
		$internalKey = $_SESSION['internalKey'];
		$username = $_SESSION['shortname'];

		// invoke OnBeforeWebLogout event
		$modx->invokeEvent("OnBeforeWebLogout",
								array(
									"userid"		=> $internalKey,
									"username"		=> $username
								));

		session_destroy();
		$sessionID = md5(date('d-m-Y H:i:s'));
		session_id($sessionID);
		session_start();
		session_destroy();

		// invoke OnWebLogout event
		$modx->invokeEvent("OnWebLogout",
								array(
									"userid"		=> $internalKey,
									"username"		=> $username
								));

		// redirect to first authorized login page
		$url = $modx->makeURL($loHomeId);
		$modx->sendRedirect($url);
		return;

	}


# process login

	$username = htmlspecialchars($_POST['username']);
	$givenPassword = htmlspecialchars($_POST['password']);
	$captcha_code = $_POST['captcha_code'];

	$sql = "SELECT $dbase.".$table_prefix."web_users.*, $dbase.".$table_prefix."web_user_attributes.* FROM $dbase.".$table_prefix."web_users, $dbase.".$table_prefix."web_user_attributes WHERE $dbase.".$table_prefix."web_users.username REGEXP BINARY '^".$username."$' and $dbase.".$table_prefix."web_user_attributes.internalKey=$dbase.".$table_prefix."web_users.id;";
	$ds = $modx->dbQuery($sql);
	$limit = mysql_num_rows($ds);

	if($limit==0 || $limit>1) {
		$output = jsAlert("Incorrect username or password entered!");
		return;
	}	

	$row = mysql_fetch_assoc($ds);

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
	$sessionRegistered 		= checkSession();
	$email 					= $row['email'];

	// load user settings
	if($internalKey){
		$result = $modx->dbQuery("SELECT setting_name, setting_value FROM ".$dbase.".".$table_prefix."web_user_settings WHERE webuser='$internalKey'");
		while ($row = $modx->fetchRow($result, 'both')) $modx->config[$row[0]] = $row[1];
	}		

	if($failedlogins>=3 && $blockeduntildate>time()) {	// blocked due to number of login errors.
		session_destroy();
		session_unset();
		$output = jsAlert("Due to three or more failed logins, you have been blocked!");
		return;
	}

	if($failedlogins>=3 && $blockeduntildate<time()) {	// blocked due to number of login errors, but get to try again
		$sql = "UDPATE $dbase.".$table_prefix."user_attributes SET failedlogincount='0', blockeduntil='".(time()-1)."' where internalKey=$internalKey";
		$ds = $modx->dbQuery($sql);
	}

	if($blocked=="1") { // this user has been blocked by an admin, so no way he's loggin in!
		session_destroy();
		session_unset();
		$output = jsAlert("You are blocked and cannot log in!");
		return;
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
	if ($modx->config['allowed_ip']) {
		if (strpos($modx->config['allowed_ip'],$_SERVER['REMOTE_ADDR'])===false) {
			$output = jsAlert("You are not allowed to login from this location.");
			return;
		}
	}

	// allowed days
	if ($modx->config['allowed_days']) {
		$date = getdate();
		$day = $date['wday']+1;
		if (strpos($modx->config['allowed_days'],"$day")===false) {
			$output = jsAlert("You are not allowed to login at this time. Please try again later.");
			return;
		}		
	}
	
	if($dbasePassword != md5($givenPassword)) {
		$output = jsAlert("Incorrect username or password entered!");
		$newloginerror = 1;
	}

	if($use_captcha==1) {
		if($_SESSION['veriword']!=$captcha_code) {
			$output = jsAlert("The security code you entered didn't validate! Please try to login again!");
			$newloginerror = 1;
		}
	}

	if($newloginerror==1) {
		$failedlogins += $newloginerror;
		if($failedlogins>=3) { //increment the failed login counter, and block!
			$sql = "update $dbase.".$table_prefix."web_user_attributes SET failedlogincount='$failedlogins', blockeduntil='".(time()+(1*60*60))."' where internalKey=$internalKey";
			$ds = $modx->dbQuery($sql);
		} else { //increment the failed login counter
			$sql = "update $dbase.".$table_prefix."web_user_attributes SET failedlogincount='$failedlogins' where internalKey=$internalKey";
			$ds = $modx->dbQuery($sql);
		}
		session_destroy();
		session_unset();
		return;
	}

	$currentsessionid = session_id();

	if(!isset($_SESSION['validated'])) {
		$sql = "update $dbase.".$table_prefix."web_user_attributes SET failedlogincount=0, logincount=logincount+1, lastlogin=thislogin, thislogin=".time().", sessionid='$currentsessionid' where internalKey=$internalKey";
		$ds = $modx->dbQuery($sql);
	}

	// set user type
	$_SESSION['usertype'] = 'web'; // user is a web (frontend) user  

	$_SESSION['shortname']=$username; 
	$_SESSION['fullname']=$fullname; 
	$_SESSION['email']=$email; 
	$_SESSION['validated']=1; 
	$_SESSION['internalKey']=$internalKey; 
	$_SESSION['valid']=base64_encode($givenPassword); 
	$_SESSION['user']=base64_encode($username); 
	$_SESSION['failedlogins']=$failedlogins; 
	$_SESSION['lastlogin']=$lastlogin; 
	$_SESSION['sessionRegistered']=$sessionRegistered; 
	$_SESSION['role']=$role; 
	$_SESSION['lastlogin']=$lastlogin; 
	$_SESSION['nrlogins']=$nrlogins;
	// web user does not use roles
	$_SESSION['permissions'] = '';

	// get user's document groups
	$dg='';$i=0;
	$tblug = $dbase.".".$table_prefix."web_groups";
	$tbluga = $dbase.".".$table_prefix."webgroup_access";
	$sql = "SELECT uga.documentgroup
			FROM $tblug ug
			INNER JOIN $tbluga uga ON uga.webgroup=ug.webgroup
			WHERE ug.webuser =".$internalKey;
	$ds = $modx->dbQuery($sql); 
	while ($row = mysql_fetch_row($ds)) $dg[$i++]=$row[0];
	$_SESSION['docgroups'] = $dg;

	if($_POST['rememberme']==1) {
		$username = $_POST['username'];
		$thepasswd = substr($site_id,-5)."crypto"; // create a password based on site id
		$rc4 = new rc4crypt;
		$thestring = $rc4->endecrypt($thepasswd,$username);
		setcookie($cookieKey, $thestring,time()+604800, "/", "", 0);
	} else {
		setcookie($cookieKey, "",time()-604800, "/", "", 0);
	}

	$log = new logHandler;
	$log->initAndWriteLog("Logged in", $_SESSION['internalKey'], $_SESSION['shortname'], "58", "-", "WebLogin");
								
	// get login home page
	$ok=false;
	if($id=$modx->config['login_home']) {
		if ($modx->getPageInfo($id)) $ok = true;
	}
	if (!$ok) {
		foreach($liHomeId as $id) {
			$id = trim($id);
			if ($modx->getPageInfo($id)) {$ok=true; break;}
		}
	}

	// update active users list if redirectinq to another page
	if($id!=$modx->documentIdentifier) {
		if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP");else if(getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR");else if(getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR");else $ip = "UNKNOWN";$_SESSION['ip'] = $ip;
		$itemid = isset($_REQUEST['id']) ? $_REQUEST['id'] : 'NULL' ;$lasthittime = time();$a = 998;
		if($a!=1) {
			// web users are stored with negative id
			$sql = "REPLACE INTO $dbase.".$table_prefix."active_users(internalKey, username, lasthit, action, id, ip) values(-".$_SESSION['internalKey'].", '".$_SESSION['shortname']."', '".$lasthittime."', '".$a."', '".$itemid."', '$ip')";
			if(!$ds = $modx->dbQuery($sql)) {
				$output = "error replacing into active users! SQL: ".$sql;
				return;
			}
		}
	}

	// invoke OnWebLogin event
	$modx->invokeEvent("OnWebLogin",
							array(
								"userid"		=> $internalKey,
								"username"		=> $username,
								"userpassword"	=> $givenPassword,
								"rememberme"	=> $_POST['rememberme']
							));

	// redirect to login home page
	$url = $modx->makeURL($id);
	$modx->sendRedirect($url);
	return;


?>