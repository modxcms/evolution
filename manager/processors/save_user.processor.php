<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!$modx->hasPermission('save_user')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$input = $_POST;

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

// verify password
if ($passwordgenmethod == "spec" && $input['specifiedpassword'] != $input['confirmpassword']) {
	webAlertAndQuit("Password typed is mismatched", 12);
}

// verify email
if ($email == '' || !preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,24}$/i", $email)) {
	webAlertAndQuit("E-mail address doesn't seem to be valid!", 12);
}

// verify admin security
if ($_SESSION['mgrRole'] != 1) {
	// Check to see if user tried to spoof a "1" (admin) role
    if (!$modx->hasPermission('save_user')) {
		webAlertAndQuit("Illegal attempt to create/modify administrator by non-administrator!", 12);
	}
	// Verify that the user being edited wasn't an admin and the user ID got spoofed
	if (EvolutionCMS\Models\ManagerUser::where('role', '=', 1)->where('internalKey', '=', $id)->first()) {
		webAlertAndQuit("You cannot alter an administrative user.", 12);
	}

}

switch ($input['mode']) {
	case '11' : // new user
		// check if this user name already exist
		if (EvolutionCMS\Models\ManagerUser::where('username', '=', $newusername)->first()) {
			webAlertAndQuit("User name is already in use!", 12);
		}

		// check if the email address already exist
		if (EvolutionCMS\Models\UserAttribute::where('internalKey', '!=', $id)->where('email', '=', $email)->first()) {
			webAlertAndQuit("Email is already in use!", 12);
		}

		// generate a new password for this user
        if ($specifiedpassword != "" && $passwordgenmethod == "spec") {
            if (strlen($specifiedpassword) < 6) {
				webAlertAndQuit("Password is too short!", 12);
			} else {
				$newpassword = $specifiedpassword;
			}
        } elseif ($specifiedpassword == "" && $passwordgenmethod == "spec") {
			webAlertAndQuit("You didn't specify a password for this user!", 12);
        } elseif ($passwordgenmethod == 'g') {
			$newpassword = generate_password(8);
		} else {
			webAlertAndQuit("No password generation method specified!", 12);
		}

		// invoke OnBeforeUserFormSave event
		$modx->invokeEvent("OnBeforeUserFormSave", array(
			"mode" => "new",
		));

		// create the user account
		$field = array();
		$field['password'] = $modx->getPasswordHash()->HashPassword($newpassword);
		$field['username'] = $newusername;
		$managerUser= EvolutionCMS\Models\ManagerUser::create($field);
		$internalKey = $managerUser->getKey();
		$verified = 1;
		$field = compact( 'fullname', 'role', 'email', 'phone', 'mobilephone', 'fax', 'zip', 'street', 'city', 'state', 'country', 'gender', 'dob', 'photo', 'comment', 'blocked', 'blockeduntil', 'blockedafter', 'verified');
		$managerUser->attributes()->create($field);

		// Save user settings
        saveManagerUserSettings($internalKey);

		// invoke OnManagerSaveUser event
		$modx->invokeEvent("OnManagerSaveUser", array(
			"mode" => "new",
			"userid" => $internalKey,
			"username" => $newusername,
			"userpassword" => $newpassword,
			"useremail" => $email,
			"userfullname" => $fullname,
			"userroleid" => $role
		));

		// invoke OnUserFormSave event
		$modx->invokeEvent("OnUserFormSave", array(
			"mode" => "new",
			"id" => $internalKey
		));

		// Set the item name for logger
		$_SESSION['itemname'] = $newusername;

		/*******************************************************************************/
		// put the user in the user_groups he/ she should be in
		// first, check that up_perms are switched on!
		if($modx->getConfig('use_udperms') == 1) {
			if(!empty($user_groups)) {
				for($i = 0; $i < count($user_groups); $i++) {
					$field = array();
					$field['user_group'] = (int)$user_groups[$i];
					$field['member'] = $id;
					$managerUser->memberGroups()->create($field);
				}
			}
		}
		// end of user_groups stuff!

        if ($passwordnotifymethod == 'e') {
            sendMailMessageForUser($email, $newusername, $newpassword, $fullname, $signupemail_message, MODX_MANAGER_URL);
            if ($input['stay'] != '') {
				$a = ($input['stay'] == '2') ? "12&id={$internalKey}" : "11";
				$header = "Location: index.php?a={$a}&r=2&stay=" . $input['stay'];
				header($header);
			} else {
				$header = "Location: index.php?a=75&r=2";
				header($header);
			}
		} else {
            if ($input['stay'] != '') {
				$a = ($input['stay'] == '2') ? "12&id={$internalKey}" : "11";
				$stayUrl = "index.php?a={$a}&r=2&stay=" . $input['stay'];
			} else {
				$stayUrl = "index.php?a=75&r=2";
			}

			include_once MODX_MANAGER_PATH . "includes/header.inc.php";
			?>

			<h1><?php echo $_lang['user_title']; ?></h1>

			<div id="actions">
                <div class="btn-group">
                    <a class="btn" href="<?php echo $stayUrl ?>"><i class="<?php echo $_style["icon_save"] ?>"></i> <?php echo $_lang['edit']; ?>
                    </a>
			</div>
            </div>

            <div class="sectionBody">
				<div class="tab-page">
					<div class="container container-body" id="disp">
						<p>
							<?php echo sprintf($_lang["password_msg"], $modx->getPhpCompat()->htmlspecialchars($newusername), $modx->getPhpCompat()->htmlspecialchars($newpassword)); ?>
						</p>
					</div>
				</div>
			</div>
			<?php

			include_once MODX_MANAGER_PATH . "includes/footer.inc.php";
		}
		break;
	case '12' : // edit user
		// generate a new password for this user
        if ($genpassword == 1) {
            if ($specifiedpassword != "" && $passwordgenmethod == "spec") {
                if (strlen($specifiedpassword) < 6) {
					webAlertAndQuit("Password is too short!", 12);
				} else {
					$newpassword = $specifiedpassword;
				}
            } elseif ($specifiedpassword == "" && $passwordgenmethod == "spec") {
				webAlertAndQuit("You didn't specify a password for this user!", 12);
            } elseif ($passwordgenmethod == 'g') {
				$newpassword = generate_password(8);
			} else {
				webAlertAndQuit("No password generation method specified!", 12);
			}
		}
        if ($passwordnotifymethod == 'e') {
            sendMailMessageForUser($email, $newusername, $newpassword, $fullname, $signupemail_message, MODX_MANAGER_URL);
		}

		// check if the username already exist
		if (EvolutionCMS\Models\ManagerUser::where('username', '=', $newusername)->where('id', '!=', $id)->first()) {
			webAlertAndQuit("User name is already in use!", 12);
		}

		// check if the email address already exists
		if (EvolutionCMS\Models\UserAttribute::where('internalKey', '!=', $id)->where('email', '=', $email)->first()) {
			webAlertAndQuit("Email is already in use!", 12);
		}

		// invoke OnBeforeUserFormSave event
		$modx->invokeEvent("OnBeforeUserFormSave", array(
			"mode" => "upd",
			"id" => $id
		));

		// update user name and password
		$field = array();
		$field['username'] = $newusername;
        if ($genpassword == 1) {
			$field['password'] = $modx->getPasswordHash()->HashPassword($newpassword);
		}
		$managerUser = EvolutionCMS\Models\ManagerUser::find($id);
		$managerUser->update($field);
        $field = compact('fullname', 'role', 'email', 'phone', 'mobilephone', 'fax', 'zip', 'street', 'city', 'state',
            'country', 'gender', 'dob', 'photo', 'comment', 'failedlogincount', 'blocked', 'blockeduntil',
            'blockedafter', 'verified');
		$managerUser->attributes->update($field);

		// Save user settings
        saveManagerUserSettings($id);

		// Set the item name for logger
		$_SESSION['itemname'] = $newusername;

		// invoke OnManagerSaveUser event
		$modx->invokeEvent("OnManagerSaveUser", array(
			"mode" => "upd",
			"userid" => $id,
			"username" => $newusername,
			"userpassword" => $newpassword,
			"useremail" => $email,
			"userfullname" => $fullname,
			"userroleid" => $role,
			"oldusername" => (($oldusername != $newusername) ? $oldusername : ""),
			"olduseremail" => (($oldemail != $email) ? $oldemail : "")
		));

		// invoke OnManagerChangePassword event
        if ($genpassword == 1) {
			$modx->invokeEvent("OnManagerChangePassword", array(
				"userid" => $id,
				"username" => $newusername,
				"userpassword" => $newpassword
			));
		}

		// invoke OnUserFormSave event
		$modx->invokeEvent("OnUserFormSave", array(
			"mode" => "upd",
			"id" => $id
		));

		/*******************************************************************************/
		// put the user in the user_groups he/ she should be in
		// first, check that up_perms are switched on!
		if($modx->getConfig('use_udperms') == 1) {
			// as this is an existing user, delete his/ her entries in the groups before saving the new groups
			$managerUser->memberGroups()->delete();
            if (!empty($user_groups)) {
                for ($i = 0; $i < count($user_groups); $i++) {
					$field = array();
					$field['user_group'] = (int)$user_groups[$i];
					$field['member'] = $id;
					$managerUser->memberGroups()->create($field);
				}
			}
		}
		// end of user_groups stuff!
		/*******************************************************************************/
        if ($id == $modx->getLoginUserID('mgr') && ($genpassword !== 1 && $passwordnotifymethod != 's')) {
			$modx->webAlertAndQuit($_lang["user_changeddata"], 'javascript:top.location.href="index.php?a=8";');
		}
        if ($genpassword == 1 && $passwordnotifymethod == 's') {
            if ($input['stay'] != '') {
				$a = ($input['stay'] == '2') ? "12&id={$id}" : "11";
				$stayUrl = "index.php?a={$a}&r=2&stay=" . $input['stay'];
			} else {
				$stayUrl = "index.php?a=75&r=2";
			}

			include_once MODX_MANAGER_PATH . "includes/header.inc.php";
			?>

			<h1><?php echo $_lang['user_title']; ?></h1>

			<div id="actions">
                <div class="btn-group">
                    <a class="btn" href="<?php echo ($id == $modx->getLoginUserID('mgr')) ? 'index.php?a=8' : $stayUrl;
                    ?>"><i
                            class="<?php echo $_style["icon_save"] ?>"></i> <?php echo ($id == $modx->getLoginUserID('mgr')) ? $_lang['logout'] : $_lang['edit']; ?>
                    </a>
			</div>
			</div>

				<div class="sectionBody">
                <div class="tab-page">
                    <div class="container container-body" id="disp">
                        <p><?php echo sprintf($_lang["password_msg"], $modx->getPhpCompat()->htmlspecialchars($newusername),
                                    $modx->getPhpCompat()->htmlspecialchars($newpassword)) . (($id == $modx->getLoginUserID('mgr')) ? ' ' . $_lang['user_changeddata'] : ''); ?></p>
					</div>
				</div>
			</div>
			<?php

			include_once MODX_MANAGER_PATH . "includes/footer.inc.php";
		} else {
            if ($input['stay'] != '') {
				$a = ($input['stay'] == '2') ? "12&id={$id}" : "11";
				$header = "Location: index.php?a={$a}&r=2&stay=" . $input['stay'];
				header($header);
			} else {
				$header = "Location: index.php?a=75&r=2";
				header($header);
			}
		}
		break;
	default:
		webAlertAndQuit("No operation set in request.", 12);
}
