<?php
if (IN_MANAGER_MODE != "true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if (!$modx->hasPermission('save_web_user')) {
	$e->setError(3);
	$e->dumpError();
}

$tbl_web_users           = $modx->getFullTableName('web_users');
$tbl_web_user_attributes = $modx->getFullTableName('web_user_attributes');
$tbl_web_groups          = $modx->getFullTableName('web_groups');

$input = $_POST;
foreach($input as $k=>$v) {
    if($k!=='comment') {
        $v = sanitize($v);
    }
    $input[$k] = $v;
}

$id                   = intval($input['id']);
$oldusername          = $input['oldusername'];
$newusername          = !empty ($input['newusername']) ? trim($input['newusername']) : "New User";
$esc_newusername      = $modx->db->escape($newusername);
$fullname             = $input['fullname'];
$genpassword          = $input['newpassword'];
$passwordgenmethod    = $input['passwordgenmethod'];
$passwordnotifymethod = $input['passwordnotifymethod'];
$specifiedpassword    = $input['specifiedpassword'];
$email                = $input['email'];
$esc_email            = $modx->db->escape($email);
$oldemail             = $input['oldemail'];
$phone                = $input['phone'];
$mobilephone          = $input['mobilephone'];
$fax                  = $input['fax'];
$dob                  = !empty ($input['dob']) ? $modx->toTimeStamp($input['dob']) : 0;
$country              = $input['country'];
$street               = $input['street'];
$city                 = $input['city'];
$state                = $input['state'];
$zip                  = $input['zip'];
$gender               = !empty($input['gender']) ? $input['gender'] : 0;
$photo                = $input['photo'];
$comment              = $input['comment'];
$role                 = !empty($input['role']) ? $input['role'] : 0;
$failedlogincount     = !empty($input['failedlogincount']) ? $input['failedlogincount'] : 0;
$blocked              = !empty($input['blocked']) ? $input['blocked'] : 0;
$blockeduntil         = !empty($input['blockeduntil']) ? $modx->toTimeStamp($input['blockeduntil']) : 0;
$blockedafter         = !empty($input['blockedafter']) ? $modx->toTimeStamp($input['blockedafter']) : 0;
$user_groups          = $input['user_groups'];

// verify password
if ($passwordgenmethod == "spec" && $input['specifiedpassword'] != $input['confirmpassword']) {
	webAlert("Password typed is mismatched");
	exit;
}

// verify email
if ($email == '' || !preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i", $email)) {
	webAlert("E-mail address doesn't seem to be valid!");
	exit;
}

switch ($input['mode']) {
	case '87' : // new user
		// check if this user name already exist
		$rs = $modx->db->select('id', $tbl_web_users, "username='{$esc_newusername}'");
		$limit = $modx->db->getRecordCount($rs);
		if ($limit > 0) {
			webAlert("User name is already in use!");
			exit;
		}

		// check if the email address already exist
		$rs = $modx->db->select('id', $tbl_web_user_attributes, "email='{$esc_email}'");
		$limit = $modx->db->getRecordCount($rs);
		if ($limit > 0) {
			$row = $modx->db->getRow($rs);
			if ($row['id'] != $id) {
				webAlert("Email is already in use!");
				exit;
			}
		}

		// generate a new password for this user
		if ($specifiedpassword != "" && $passwordgenmethod == "spec") {
			if (strlen($specifiedpassword) < 6) {
				webAlert("Password is too short!");
				exit;
			} else {
				$newpassword = $specifiedpassword;
			}
		}
		elseif ($specifiedpassword == "" && $passwordgenmethod == "spec") {
			webAlert("You didn't specify a password for this user!");
			exit;
		}
		elseif ($passwordgenmethod == 'g') {
			$newpassword = generate_password(8);
		} else {
			webAlert("No password generation method specified!");
			exit;
		}

		// invoke OnBeforeWUsrFormSave event
		$modx->invokeEvent("OnBeforeWUsrFormSave", array (
			"mode" => "new",
			"id" => $id
		));

		// create the user account
		$field = array();
		$field['username'] = $newusername;
		$field = $modx->db->escape($field);
		$field['password'] = md5($newpassword);
		$internalKey = $modx->db->insert($field, $tbl_web_users);
		if (!$internalKey) {
			webAlert("An error occurred while attempting to save the user.");
			exit;
		}

        $field = compact('internalKey','fullname','role','email','phone','mobilephone','fax','zip','street','city','state','country','gender','dob','photo','comment','blocked','blockeduntil','blockedafter');
        $field = $modx->db->escape($field);
		$rs = $modx->db->insert($field, $tbl_web_user_attributes);
		if (!$rs) {
			webAlert("An error occurred while attempting to save the user's attributes.");
			exit;
		}

		// Save user settings
		saveUserSettings($internalKey);

		// invoke OnWebSaveUser event
		$modx->invokeEvent("OnWebSaveUser", array (
			"mode" => "new",
			"userid" => $internalKey,
			"username" => $newusername,
			"userpassword" => $newpassword,
			"useremail" => $email,
			"userfullname" => $fullname
		));

		// invoke OnWUsrFormSave event
		$modx->invokeEvent("OnWUsrFormSave", array (
			"mode" => "new",
			"id" => $internalKey
		));

		// Set the item name for logger
		$_SESSION['itemname'] = $newusername;

		/*******************************************************************************/
		// put the user in the user_groups he/ she should be in
		// first, check that up_perms are switched on!
		if ($use_udperms == 1) {
			if (count($user_groups) > 0) {
				for ($i = 0; $i < count($user_groups); $i++) {
					$f = array();
					$f['webgroup'] = intval($user_groups[$i]);
					$f['webuser']  = $internalKey;
					$f = $modx->db->escape($f);
					$rs = $modx->db->insert($f, $tbl_web_groups);
					if (!$rs) {
						webAlert("An error occurred while attempting to add the user to a web group.");
						exit;
					}
				}
			}
		}
		// end of user_groups stuff!

		if ($passwordnotifymethod == 'e') {
			sendMailMessage($email, $newusername, $newpassword, $fullname);
			if ($input['stay'] != '') {
				$a = ($input['stay'] == '2') ? "88&id={$id}" : "87";
				$header = "Location: index.php?a={$a}&r=2&stay=" . $input['stay'];
				header($header);
			} else {
				$header = "Location: index.php?a=99&r=2";
				header($header);
			}
		} else {
			if ($input['stay'] != '') {
				$a = ($input['stay'] == '2') ? "88&id={$internalKey}" : "87";
				$stayUrl = "index.php?a={$a}&r=2&stay=" . $input['stay'];
			} else {
				$stayUrl = "index.php?a=99&r=2";
			}
			
			include_once "header.inc.php";
?>
			<h1><?php echo $_lang['web_user_title']; ?></h1>

			<div id="actions">
			<ul class="actionButtons">
				<li><a href="<?php echo $stayUrl ?>"><img src="<?php echo $_style["icons_save"] ?>" /> <?php echo $_lang['close']; ?></a></li>
			</ul>
			</div>
            <div class="section">
			<div class="sectionHeader"><?php echo $_lang['web_user_title']; ?></div>
			<div class="sectionBody">
			<div id="disp">
			<p>
			<?php echo sprintf($_lang["password_msg"], $newusername, $newpassword); ?>
			</p>
			</div>
			</div>
            </div>
		<?php

			include_once "footer.inc.php";
		}
		break;

	case '88' : // edit user
		// generate a new password for this user
		if ($genpassword == 1) {
			if ($specifiedpassword != "" && $passwordgenmethod == "spec") {
				if (strlen($specifiedpassword) < 6) {
					webAlert("Password is too short!");
					exit;
				} else {
					$newpassword = $specifiedpassword;
				}
			}
			elseif ($specifiedpassword == "" && $passwordgenmethod == "spec") {
				webAlert("You didn't specify a password for this user!");
				exit;
			}
			elseif ($passwordgenmethod == 'g') {
				$newpassword = generate_password(8);
			} else {
				webAlert("No password generation method specified!");
				exit;
			}
		}
		if ($passwordnotifymethod == 'e') {
			sendMailMessage($email, $newusername, $newpassword, $fullname);
		}

		// check if the username already exist
		$rs = $modx->db->select('id', $tbl_web_users, "username='{$esc_newusername}'");
		$limit = $modx->db->getRecordCount($rs);
		if ($limit > 0) {
			$row = $modx->db->getRow($rs);
			if ($row['id'] != $id) {
				webAlert("User name is already in use!");
				exit;
			}
		}

		// check if the email address already exists
		$rs = $modx->db->select('internalKey', $tbl_web_user_attributes, "email='{$esc_email}'");
		$limit = $modx->db->getRecordCount($rs);
		if ($limit > 0) {
			$row = $modx->db->getRow($rs);
			if ($row['internalKey'] != $id) {
				webAlert("Email is already in use!");
				exit;
			}
		}

		// invoke OnBeforeWUsrFormSave event
		$modx->invokeEvent("OnBeforeWUsrFormSave", array (
			"mode" => "upd",
			"id" => $id
		));

		// update user name and password
		$field = array();
		$field['username'] = $newusername;
		$field = $modx->db->escape($field);
		if($genpassword == 1) {
		    $field['password'] = md5($newpassword);
		}
		$rs = $modx->db->update($field, $tbl_web_users, "id='{$id}'");
		if (!$rs) {
			webAlert("An error occurred while attempting to update the user's data.");
			exit;
		}
		$field = array();
		$field = compact('fullname','role','email','phone','mobilephone','fax','zip','street','city','state','country','gender','dob','photo','comment','failedlogincount','blocked','blockeduntil','blockedafter');
		$field = $modx->db->escape($field);
		$rs = $modx->db->update($field, $tbl_web_user_attributes, "internalKey='{$id}'");
		if (!$rs) {
			webAlert("An error occurred while attempting to update the user's attributes.");
			exit;
		}

		// Save user settings
		saveUserSettings($id);

		// invoke OnWebSaveUser event
		$modx->invokeEvent("OnWebSaveUser", array (
			"mode" => "upd",
			"userid" => $id,
			"username" => $newusername,
			"userpassword" => $newpassword,
			"useremail" => $email,
			"userfullname" => $fullname,
			"oldusername" => (($oldusername != $newusername
		) ? $oldusername : ""), "olduseremail" => (($oldemail != $email) ? $oldemail : "")));

		// invoke OnWebChangePassword event
		if ($genpassword == 1) {
			$modx->invokeEvent("OnWebChangePassword", array(
				"userid" => $id,
				"username" => $newusername,
				"userpassword" => $newpassword
			));
		}

		// invoke OnWUsrFormSave event
		$modx->invokeEvent("OnWUsrFormSave", array (
			"mode" => "upd",
			"id" => $id
		));

		// Set the item name for logger
		$_SESSION['itemname'] = $newusername;

		/*******************************************************************************/
		// put the user in the user_groups he/ she should be in
		// first, check that up_perms are switched on!
		if ($use_udperms == 1) {
			// as this is an existing user, delete his/ her entries in the groups before saving the new groups
			$rs = $modx->db->delete($tbl_web_groups, "webuser='{$id}'");
			if (!$rs) {
				webAlert("An error occurred while attempting to delete previous user_groups entries.");
				exit;
			}
			if (count($user_groups) > 0) {
				for ($i = 0; $i < count($user_groups); $i++) {
					$f = array();
					$f['webgroup'] = intval($user_groups[$i]);
					$f['webuser']  = $id;
					$f = $modx->db->escape($f);
					$rs = $modx->db->insert($f, $tbl_web_groups);
					if (!$rs) {
						webAlert("An error occurred while attempting to add the user to a user_group.<br />$sql;");
						exit;
					}
				}
			}
		}
		// end of user_groups stuff!
		/*******************************************************************************/

		if ($genpassword == 1 && $passwordnotifymethod == 's') {
			if ($input['stay'] != '') {
				$a = ($input['stay'] == '2') ? "88&id={$id}" : "87";
				$stayUrl = "index.php?a={$a}&r=2&stay=" . $input['stay'];
			} else {
				$stayUrl = "index.php?a=99&r=2";
			}
			
			include_once "header.inc.php";
?>
			<h1><?php echo $_lang['web_user_title']; ?></h1>

			<div id="actions">
			<ul class="actionButtons">
				<li><a href="<?php echo $stayUrl ?>"><img src="<?php echo $_style["icons_save"] ?>" /> <?php echo $_lang['close']; ?></a></li>
			</ul>
			</div>
            <div class="section">
			<div class="sectionHeader"><?php echo $_lang['web_user_title']; ?></div>
			<div class="sectionBody">
			<div id="disp">
				<p><?php echo sprintf($_lang["password_msg"], $newusername, $newpassword); ?></p>
			</div>
			</div>
            </div>
		<?php

			include_once "footer.inc.php";
		} else {
			if ($input['stay'] != '') {
				$a = ($input['stay'] == '2') ? "88&id={$id}" : "87";
				$header = "Location: index.php?a={$a}&r=2&stay=" . $input['stay'];
				header($header);
			} else {
				$header = "Location: index.php?a=99&r=2";
				header($header);
			}
		}
		break;
	default :
		webAlert("Unauthorized access");
		exit;
}

// in case any plugins include a quoted_printable function
function save_user_quoted_printable($string) {
	$crlf = "\n" ;
	$string = preg_replace('!(\r\n|\r|\n)!', $crlf, $string) . $crlf ;
	$f[] = '/([\000-\010\013\014\016-\037\075\177-\377])/e' ;
	$r[] = "'=' . sprintf('%02X', ord('\\1'))" ; $f[] = '/([\011\040])' . $crlf . '/e' ;
	$r[] = "'=' . sprintf('%02X', ord('\\1')) . '" . $crlf . "'" ;
	$string = preg_replace($f, $r, $string) ;
	return trim(wordwrap($string, 70, ' =' . $crlf)) ;
}

// Send an email to the user
function sendMailMessage($email, $uid, $pwd, $ufn) {
	global $modx,$_lang,$websignupemail_message;
	global $emailsubject, $emailsender;
	global $site_name, $site_start, $site_url;
	$message = sprintf($websignupemail_message, $uid, $pwd); // use old method
	// replace placeholders
	$message = str_replace("[+uid+]", $uid, $message);
	$message = str_replace("[+pwd+]", $pwd, $message);
	$message = str_replace("[+ufn+]", $ufn, $message);
	$message = str_replace("[+sname+]", $site_name, $message);
	$message = str_replace("[+saddr+]", $emailsender, $message);
	$message = str_replace("[+semail+]", $emailsender, $message);
	$message = str_replace("[+surl+]", $site_url, $message);

	$param = array();
	$param['from']    = "{$site_name}<{$emailsender}>";
	$param['subject'] = $emailsubject;
	$param['body']    = $message;
	$param['to']      = $email;
	$rs = $modx->sendmail($param);
	if (!$rs) {
		webAlert("{$email} - {$_lang['error_sending_email']}");
		exit;
	}
}

// Save User Settings
function saveUserSettings($id) {
	global $modx;
    $tbl_web_user_settings = $modx->getFullTableName('web_user_settings');
    
	$settings = array (
		"login_home",
		"allowed_ip",
		"allowed_days"
	);

    $esc_id = $modx->db->escape($id);
	$modx->db->delete($tbl_web_user_settings,"webuser='{$esc_id}'");

	foreach ($settings as $n) {
		$vl = $_POST[$n];
		if (is_array($vl)) {
			$vl = implode(",", $vl);
		}
		if ($vl != '') {
		    $f = array();
		    $f['webuser']       = $id;
		    $f['setting_name']  = $n;
		    $f['setting_value'] = $vl;
		    $f = $modx->db->escape($f);
		    $rs = $modx->db->insert($f, $tbl_web_user_settings);
			if (!$rs) {
				webAlert("Failed to update user setting!<br />User: $id, Setting: '$n', Value: '$vl'");
				exit;
			}
		}
	}
}

// Web alert -  sends an alert to web browser
function webAlert($msg) {
	global $id, $modx;
	global $dbase, $table_prefix;
	$mode = intval($_POST['mode']);
	$url = "index.php?a={$mode}" . ($mode == '88' ? "&id={$id}" : '');
	$modx->manager->saveFormValues($mode);
	include_once "header.inc.php";
	$modx->webAlert($msg, $url);
	include_once "footer.inc.php";
}

// Generate password
function generate_password($length = 10) {
	$allowable_characters = "abcdefghjkmnpqrstuvxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";
	$ps_len = strlen($allowable_characters);
	mt_srand((double) microtime() * 1000000);
	$pass = "";
	for ($i = 0; $i < $length; $i++) {
		$pass .= $allowable_characters[mt_rand(0, $ps_len -1)];
	}
	return $pass;
}

function sanitize($str='',$safecount=0) {
	global $modx;
	$safecount++;
	if (1000 < $safecount) {
		exit("error too many loops '{$safecount}'");
	}
	if(is_array($str)) {
		foreach($str as $i=>$v) {
			$str[$i] = sanitize($v,$safecount);
		}
	}
	else {
		$str = strip_tags($str);
		$str = htmlspecialchars($str, ENT_NOQUOTES, $modx->config['modx_charset']);
	}
	return $str;
}
