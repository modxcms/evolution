<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!$modx->hasPermission('save_web_user')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$input = $_POST;
foreach ($input as $k => $v) {
    if ($k !== 'comment' && $k !== 'user_groups') {
		$v = $modx->getPhpCompat()->htmlspecialchars($v, ENT_NOQUOTES);
	}
	$input[$k] = $v;
}

$id = (int)$input['id'];
$oldusername = $input['oldusername'];
$newusername = !empty ($input['newusername']) ? trim($input['newusername']) : "New User";
$fullname = $input['fullname'];
$genpassword = $input['newpassword'];
$passwordgenmethod = $input['passwordgenmethod'];
$passwordnotifymethod = $input['passwordnotifymethod'];
$specifiedpassword = $input['specifiedpassword'];
$email = trim($input['email']);
$oldemail = $input['oldemail'];
$phone = $input['phone'];
$mobilephone = $input['mobilephone'];
$fax = $input['fax'];
$dob = !empty ($input['dob']) ? $modx->toTimeStamp($input['dob']) : 0;
$country = $input['country'];
$street = $input['street'];
$city = $input['city'];
$state = $input['state'];
$zip = $input['zip'];
$gender = !empty($input['gender']) ? $input['gender'] : 0;
$photo = $input['photo'];
$comment = $input['comment'];
$role = !empty($input['role']) ? $input['role'] : 0;
$verified = !empty($input['verified']) ? (int)!!$input['verified'] : 0;
$failedlogincount = !empty($input['failedlogincount']) ? $input['failedlogincount'] : 0;
$blocked = !empty($input['blocked']) ? $input['blocked'] : 0;
$blockeduntil = !empty($input['blockeduntil']) ? $modx->toTimeStamp($input['blockeduntil']) : 0;
$blockedafter = !empty($input['blockedafter']) ? $modx->toTimeStamp($input['blockedafter']) : 0;
$user_groups = $input['user_groups'];

$websignupemail_message = $modx->config['websignupemail_message'];
$site_url = $modx->config['site_url'];

// verify password
if ($passwordgenmethod == "spec" && $input['specifiedpassword'] != $input['confirmpassword']) {
	webAlertAndQuit("Password typed is mismatched", 88);
}

// verify email
if ($email == '' || !preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,24}$/i", $email)) {
	webAlertAndQuit("E-mail address doesn't seem to be valid!", 88);
}

switch ($input['mode']) {
	case '87' : // new user
		// check if this user name already exist
		if (EvolutionCMS\Models\WebUser::where('username', '=', $newusername)->first()) {
			webAlertAndQuit("User name is already in use!", 88);
		}

		// check if the email address already exist
		if ($modx->config['allow_multiple_emails'] != 1) {
			if (EvolutionCMS\Models\WebUserAttribute::where('internalKey', '!=', $id)->where('email', '=', $email)->first()) {
				webAlertAndQuit("Email is already in use!", 88);
			}
		}

		// generate a new password for this user
        if ($specifiedpassword != "" && $passwordgenmethod == "spec") {
            if (strlen($specifiedpassword) < 6) {
				webAlertAndQuit("Password is too short!", 88);
			} else {
				$newpassword = $specifiedpassword;
			}
        } elseif ($specifiedpassword == "" && $passwordgenmethod == "spec") {
			webAlertAndQuit("You didn't specify a password for this user!", 88);
        } elseif ($passwordgenmethod == 'g') {
			$newpassword = generate_password(8);
		} else {
			webAlertAndQuit("No password generation method specified!", 88);
		}

		// invoke OnBeforeWUsrFormSave event
		$modx->invokeEvent("OnBeforeWUsrFormSave", array(
			"mode" => "new",
		));

		// create the user account
		$field = array();
		$field['username'] = $newusername;
		$field['password'] = md5($newpassword);
		$webUser= EvolutionCMS\Models\WebUser::create($field);
		$internalKey = $webUser->getKey();
		$field = compact( 'fullname', 'role', 'email', 'phone', 'mobilephone', 'fax', 'zip', 'street', 'city', 'state', 'country', 'gender', 'dob', 'photo', 'comment', 'blocked', 'blockeduntil', 'blockedafter');
		$webUser->attributes()->create($field);

		// Save User Settings
        saveWebUserSettings($internalKey);

		// Set the item name for logger
		$_SESSION['itemname'] = $newusername;

		/*******************************************************************************/
		// put the user in the user_groups he/ she should be in
		// first, check that up_perms are switched on!
		if($modx->getConfig('use_udperms') == 1) {
			if(!empty($user_groups)) {
				for($i = 0; $i < count($user_groups); $i++) {
					$field = array();
					$field['webgroup'] = (int)$user_groups[$i];
					$webUser->memberGroups()->create($field);
				}
			}
		}
		// end of user_groups stuff!

        // invoke OnWebSaveUser event
        $modx->invokeEvent("OnWebSaveUser", array(
            "mode" => "new",
            "userid" => $internalKey,
            "username" => $newusername,
            "userpassword" => $newpassword,
            "useremail" => $email,
            "userfullname" => $fullname
        ));

        // invoke OnWUsrFormSave event
        $modx->invokeEvent("OnWUsrFormSave", array(
            "mode" => "new",
            "id" => $internalKey
        ));

        if ($passwordnotifymethod == 'e') {
            sendMailMessageForUser($email, $newusername, $newpassword, $fullname, $websignupemail_message, $site_url);
            if ($input['stay'] != '') {
				$a = ($input['stay'] == '2') ? "88&id={$internalKey}" : "87";
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

			include_once MODX_MANAGER_PATH . "includes/header.inc.php";
			?>

			<h1><?php echo $_lang['web_user_title']; ?></h1>

			<div id="actions">
                <div class="btn-group">
                    <a href="<?php echo $stayUrl ?>"><i class="<?php echo $_style["icon_save"] ?>"></i> <?php echo $_lang['edit']; ?>
                    </a>
                </div>
			</div>

			<div class="sectionBody">
				<div class="tab-page">
					<div class="container container-body" id="disp">
						<p>
							<?php echo sprintf($_lang["password_msg"], $newusername, $newpassword); ?>
						</p>
					</div>
				</div>
			</div>
			<?php

			include_once MODX_MANAGER_PATH . "includes/footer.inc.php";
		}
		break;
	case '88' : // edit user
		// generate a new password for this user
        if ($genpassword == 1) {
            if ($specifiedpassword != "" && $passwordgenmethod == "spec") {
                if (strlen($specifiedpassword) < 6) {
					webAlertAndQuit("Password is too short!", 88);
				} else {
					$newpassword = $specifiedpassword;
				}
            } elseif ($specifiedpassword == "" && $passwordgenmethod == "spec") {
				webAlertAndQuit("You didn't specify a password for this user!", 88);
            } elseif ($passwordgenmethod == 'g') {
				$newpassword = generate_password(8);
			} else {
				webAlertAndQuit("No password generation method specified!", 88);
			}
		}
        if ($passwordnotifymethod == 'e') {
            sendMailMessageForUser($email, $newusername, $newpassword, $fullname, $websignupemail_message, $site_url);
		}

		// check if the username already exist
		if (EvolutionCMS\Models\WebUser::where('id', '!=', $id)->where('username', '=', $newusername)->first()) {
			webAlertAndQuit("User name is already in use!", 88);
		}

		// check if the email address already exists
		if ($modx->config['allow_multiple_emails'] != 1) {
			if (EvolutionCMS\Models\WebUserAttribute::where('internalKey', '!=', $id)->where('email', '=', $email)->first()) {
				webAlertAndQuit("Email is already in use!", 88);
			}
		}

		// invoke OnBeforeWUsrFormSave event
		$modx->invokeEvent("OnBeforeWUsrFormSave", array(
			"mode" => "upd",
			"id" => $id
		));

		// update user name and password
		$field = array();
		$field['username'] = $newusername;
        if ($genpassword == 1) {
			$field['password'] = md5($newpassword);
		}
		$webUser = EvolutionCMS\Models\WebUser::find($id);
		$webUser->update($field);
        $field = compact('fullname', 'role', 'email', 'phone', 'mobilephone', 'fax', 'zip', 'street', 'city', 'state',
            'country', 'gender', 'dob', 'photo', 'comment', 'failedlogincount', 'blocked', 'blockeduntil',
            'blockedafter');
		$webUser->attributes->update($field);

		// Save User Settings
        saveWebUserSettings($id);

		// Set the item name for logger
		$_SESSION['itemname'] = $newusername;

		/*******************************************************************************/
		// put the user in the user_groups he/ she should be in
		// first, check that up_perms are switched on!
		if($modx->getConfig('use_udperms') == 1) {
			// as this is an existing user, delete his/ her entries in the groups before saving the new groups
			$webUser->memberGroups()->delete();
            if (!empty($user_groups)) {
                for ($i = 0; $i < count($user_groups); $i++) {
					$field = array();
					$field['webgroup'] = (int)$user_groups[$i];
					$webUser->memberGroups()->create($field);
				}
			}
		}
		// end of user_groups stuff!
		/*******************************************************************************/

        // invoke OnWebSaveUser event
        $modx->invokeEvent("OnWebSaveUser", array(
            "mode" => "upd",
            "userid" => $id,
            "username" => $newusername,
            "userpassword" => $newpassword,
            "useremail" => $email,
            "userfullname" => $fullname,
            "oldusername" => (($oldusername != $newusername) ? $oldusername : ""),
            "olduseremail" => (($oldemail != $email) ? $oldemail : "")
        ));

        // invoke OnWebChangePassword event
        if ($genpassword == 1) {
            $modx->invokeEvent("OnWebChangePassword", array(
                "userid" => $id,
                "username" => $newusername,
                "userpassword" => $newpassword
            ));
        }

        // invoke OnWUsrFormSave event
        $modx->invokeEvent("OnWUsrFormSave", array(
            "mode" => "upd",
            "id" => $id
        ));

        if ($genpassword == 1 && $passwordnotifymethod == 's') {
            if ($input['stay'] != '') {
				$a = ($input['stay'] == '2') ? "88&id={$id}" : "87";
				$stayUrl = "index.php?a={$a}&r=2&stay=" . $input['stay'];
			} else {
				$stayUrl = "index.php?a=99&r=2";
			}

			include_once MODX_MANAGER_PATH . "includes/header.inc.php";
			?>

			<h1><?php echo $_lang['web_user_title']; ?></h1>

            <div id="actions">
                <div class="btn-group">
                    <a href="<?php echo $stayUrl ?>" class="btn"><i class="<?php echo $_style["icon_save"] ?>"></i>
                        <?php echo $_lang['edit']; ?></a>
                </div>
            </div>

			<div class="sectionBody">
				<div class="tab-page">
					<div class="container container-body" id="disp">
						<p><?php echo sprintf($_lang["password_msg"], $newusername, $newpassword); ?></p>
					</div>
				</div>
			</div>
			<?php

			include_once MODX_MANAGER_PATH . "includes/footer.inc.php";
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
		webAlertAndQuit("No operation set in request.", 88);
}
