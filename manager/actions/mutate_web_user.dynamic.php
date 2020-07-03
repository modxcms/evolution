<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}


switch($modx->getManagerApi()->action) {
	case 88:
		if(!$modx->hasPermission('edit_web_user')) {
			$modx->webAlertAndQuit($_lang["error_no_privileges"]);
		}
		break;
	case 87:
		if(!$modx->hasPermission('new_web_user')) {
			$modx->webAlertAndQuit($_lang["error_no_privileges"]);
		}
		break;
	default:
		$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$user = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;


// check to see the snippet editor isn't locked
$username = \EvolutionCMS\Models\ActiveUser::query()->where('action', 12)
    ->where('id', $user)
    ->where('internalKey', '!=', $modx->getLoginUserID('mgr'))
    ->first();
if(!is_null($username)) {
    $username = $username->username;
	$modx->webAlertAndQuit(sprintf($_lang["lock_msg"], $username, "web user"));
}
// end check for lock
$userdata = [
    'fullname' => '',
    'verified' => 0,
    'blocked' => 0,
    'blockeduntil' => 0,
    'blockedafter' => 0,
    'failedlogins' => 0,
    'email' => '',
    'phone' => '',
    'mobilephone' => '',
    'dob' => 0,
    'gender' => 3,
    'country' => '',
    'street' => '',
    'city' => '',
    'state' => '',
    'zip' => '',
    'fax' => '',
    'photo' => '',
    'comment' => ''
];
$usersettings = [
    'allowed_days' => '',
    'login_home' => '',
    'allowed_ip' => '',
];
$usernamedata = [];

if($modx->getManagerApi()->action == '88') {
	// get user attributes
	$userdatatmp = \EvolutionCMS\Models\WebUserAttribute::query()->where('internalKey', $user)->first()->toArray();
	if(!$userdatatmp) {
		$modx->webAlertAndQuit("No user returned!");
	}
	$userdata = array_merge($userdata, $userdatatmp);
	unset($userdatatmp);

	// get user settings
    $usersettings = \EvolutionCMS\Models\WebUserSetting::where('webuser', $user)->pluck('setting_value', 'setting_name')->toArray();
	extract($usersettings, EXTR_OVERWRITE);

	// get user name
	$usernamedata = \EvolutionCMS\Models\WebUser::find($user)->toArray();
	if(!$usernamedata) {
		$modx->webAlertAndQuit("No user returned while getting username!");
	}
	$_SESSION['itemname'] = $usernamedata['username'];
} else {
	$_SESSION['itemname'] = $_lang["new_web_user"];
}

// avoid doubling htmlspecialchars (already encoded in DB)
foreach($userdata as $key => $val) {
	$userdata[$key] = html_entity_decode($val, ENT_NOQUOTES, $modx->getConfig('modx_charset'));
};
$usernamedata['username'] = html_entity_decode(
    get_by_key($usernamedata, 'username', ''),
    ENT_NOQUOTES,
    $modx->getConfig('modx_charset')
);

// restore saved form
$formRestored = false;
if($modx->getManagerApi()->hasFormValues()) {
	$modx->getManagerApi()->loadFormValues();
	// restore post values
	$userdata = array_merge($userdata, $_POST);
	$userdata['dob'] = $modx->toTimeStamp($userdata['dob']);
	$usernamedata['username'] = $userdata['newusername'];
	$usernamedata['oldusername'] = $_POST['oldusername'];
	$usersettings = array_merge($usersettings, $userdata);
	$usersettings['allowed_days'] = is_array($_POST['allowed_days']) ? implode(",", $_POST['allowed_days']) : "";
	extract($usersettings, EXTR_OVERWRITE);
}

// include the country list language file
$_country_lang = array();
if($manager_language != "english" && file_exists(MODX_MANAGER_PATH . "includes/lang/country/" . $manager_language . "_country.inc.php")) {
	include_once MODX_MANAGER_PATH . "includes/lang/country/" . $manager_language . "_country.inc.php";
} else {
	include_once MODX_MANAGER_PATH . "includes/lang/country/english_country.inc.php";
}
asort($_country_lang);

$displayStyle = ($_SESSION['browser'] === 'modern') ? 'table-row' : 'block';
?>
<script type="text/javascript">

	function changestate(el) {
		documentDirty = true;
		if(parseInt(el.value) === 1) {
			el.value = 0;
		} else {
			el.value = 1;
		}
	}

	function changePasswordState(el) {
		if(parseInt(el.value) === 1) {
			document.getElementById("passwordBlock").style.display = "block";
		} else {
			document.getElementById("passwordBlock").style.display = "none";
		}
	}

	function changeblockstate(el, checkelement) {
		if(parseInt(el.value) === 1) {
			if(confirm("<?php echo $_lang['confirm_unblock']; ?>") === true) {
				document.userform.blocked.value = 0;
				document.userform.blockeduntil.value = "";
				document.userform.blockedafter.value = "";
				document.userform.failedlogincount.value = 0;
				blocked.innerHTML = "<b><?php echo $_lang['unblock_message']; ?></b>";
				blocked.className = "TD";
				el.value = 0;
			} else {
				checkelement.checked = true;
			}
		} else {
			if(confirm("<?php echo $_lang['confirm_block']; ?>") === true) {
				document.userform.blocked.value = 1;
				blocked.innerHTML = "<b><?php echo $_lang['block_message']; ?></b>";
				blocked.className = "warning";
				el.value = 1;
			} else {
				checkelement.checked = false;
			}
		}
	}

	function resetFailed() {
		document.userform.failedlogincount.value = 0;
		document.getElementById("failed").innerHTML = "0";
	}

	// change name
	function changeName() {
		if(confirm("<?php echo $_lang['confirm_name_change']; ?>") === true) {
			var e1 = document.getElementById("showname");
			var e2 = document.getElementById("editname");
			e1.style.display = "none";
			e2.style.display = "<?php echo $displayStyle; ?>";
		}
	};

	// showHide - used by custom settings
	function showHide(what, onoff) {
		var all = document.getElementsByTagName("*");
		var l = all.length;
		var buttonRe = what;
		var id, el, stylevar;

		if(onoff === 1) {
			stylevar = "<?php echo $displayStyle; ?>";
		} else {
			stylevar = "none";
		}

		for(var i = 0; i < l; i++) {
			el = all[i];
			id = el.id;
			if(!id) continue;
			if(buttonRe.test(id)) {
				el.style.display = stylevar;
			}
		}
	};

	var actions = {
		save: function() {
			documentDirty = false;
			document.userform.save.click();
		},
		delete: function() {
			if(confirm("<?php echo $_lang['confirm_delete_user']; ?>") === true) {
				window.location.href = "index.php?id=" + document.userform.id.value + "&a=90";
			}
		},
		cancel: function() {
			documentDirty = false;
			window.location.href = 'index.php?a=99';
		}
	}

</script>

<form name="userform" method="post" action="index.php">
	<?php
	// invoke OnWUsrFormPrerender event
	$evtOut = $modx->invokeEvent("OnWUsrFormPrerender", array("id" => $user));
	if(is_array($evtOut)) {
		echo implode("", $evtOut);
	}
	?>
    <input type="hidden" name="a" value="89">
	<input type="hidden" name="mode" value="<?php echo $modx->getManagerApi()->action; ?>" />
	<input type="hidden" name="id" value="<?php echo $user ?>" />
	<input type="hidden" name="blockedmode" value="<?php echo ($userdata['blocked'] == 1 || ($userdata['blockeduntil'] > time() && $userdata['blockeduntil'] != 0) || ($userdata['blockedafter'] < time() && $userdata['blockedafter'] != 0) || $userdata['failedlogins'] > $modx->getConfig('failed_login_attempts')) ? "1" : "0" ?>" />

	<h1>
        <i class="<?= $_style['icon_web_user'] ?>"></i><?= ($usernamedata['username'] ? $usernamedata['username'] . '<small>(' . $usernamedata['id'] . ')</small>' : $_lang['web_user_title']) ?>
    </h1>

	<?= ManagerTheme::getStyle('actionbuttons.dynamic.user') ?>

	<!-- Tab Start -->
	<div class="sectionBody">

		<div class="tab-pane" id="webUserPane">
			<script type="text/javascript">
				tpUser = new WebFXTabPane(document.getElementById("webUserPane"), <?php echo $modx->getConfig('remember_last_tab') == 1 ? 'true' : 'false'; ?> );
			</script>
			<div class="tab-page" id="tabGeneral">
				<h2 class="tab"><?php echo $_lang["settings_general"] ?></h2>
				<script type="text/javascript">tpUser.addTabPage(document.getElementById("tabGeneral"));</script>
				<table border="0" cellspacing="0" cellpadding="3" class="table table--edit table--editUser">
					<?php if($userdata['blocked'] == 1 || ($userdata['blockeduntil'] > time() && $userdata['blockeduntil'] != 0) || ($userdata['blockedafter'] < time() && $userdata['blockedafter'] != 0) || $userdata['failedlogins'] > 3) { ?>
					<tr>
						<td colspan="3"><span id="blocked" class="warning">
								<b><?php echo $_lang['user_is_blocked']; ?></b>
							</span>
						<br /></td>
					</tr>
					<?php } ?>
					<?php if(!empty($userdata['id'])) { ?>
						<tr id="showname" style="display: <?php echo ($modx->getManagerApi()->action == '88' && (!isset($usernamedata['oldusername']) || $usernamedata['oldusername'] == $usernamedata['username'])) ? $displayStyle : 'none'; ?> ">
                            <th><?php echo $_lang['username']; ?>:</th>
                            <td>&nbsp;</td>
                            <td><i class="<?php echo $_style["icon_web_user"] ?>"></i>&nbsp;<b><?php echo $modx->getPhpCompat()->htmlspecialchars(!empty($usernamedata['oldusername']) ? $usernamedata['oldusername'] : $usernamedata['username']); ?></b> - <span class="comment"><a href="javascript:;" onClick="changeName();return false;"><?php echo $_lang["change_name"]; ?></a></span>
                            	<input type="hidden" name="oldusername" value="<?php echo $modx->getPhpCompat()->htmlspecialchars(!empty($usernamedata['oldusername']) ? $usernamedata['oldusername'] : $usernamedata['username']); ?>" />
							</td>
						</tr>
					<?php } ?>
					<tr id="editname" style="display:<?php echo $modx->getManagerApi()->action == '87' || (isset($usernamedata['oldusername']) && $usernamedata['oldusername'] != $usernamedata['username']) ? $displayStyle : 'none'; ?>">
						<th><?php echo $_lang['username']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" name="newusername" class="inputBox" value="<?php echo $modx->getPhpCompat()->htmlspecialchars(isset($_POST['newusername']) ? $_POST['newusername'] : $usernamedata['username']); ?>" onChange='documentDirty=true;' maxlength="100" /></td>
					</tr>
					<tr>
						<th><?php echo $modx->getManagerApi()->action == '87' ? $_lang['password'] . ":" : $_lang['change_password_new'] . ":"; ?></th>
						<td>&nbsp;</td>
						<td><input name="newpasswordcheck" type="checkbox" onClick="changestate(document.userform.newpassword);changePasswordState(document.userform.newpassword);"<?php echo $modx->getManagerApi()->action == "87" ? " checked disabled" : ""; ?>>
							<input type="hidden" name="newpassword" value="<?php echo $modx->getManagerApi()->action == "87" ? 1 : 0; ?>" onChange="documentDirty=true;" />
							<br />
							<span style="display:<?php echo $modx->getManagerApi()->action == "87" ? "block" : "none"; ?>" id="passwordBlock">
							<fieldset style="width:300px">
								<legend><?php echo $_lang['password_gen_method']; ?></legend>
								<input type=radio name="passwordgenmethod" value="g" <?php echo get_by_key($_POST, 'passwordgenmethod') === 'spec' ? '' : 'checked="checked"'; ?> />
								<?php echo $_lang['password_gen_gen']; ?>
								<br />
								<input type=radio name="passwordgenmethod" value="spec" <?php echo get_by_key($_POST, 'passwordgenmethod') === 'spec' ? 'checked="checked"' : ""; ?>>
								<?php echo $_lang['password_gen_specify']; ?>
								<br />
								<div>
									<label for="specifiedpassword" style="width:120px"><?php echo $_lang['change_password_new']; ?>:</label>
									<input type="password" name="specifiedpassword" onChange="documentdirty=true;" onKeyPress="document.userform.passwordgenmethod[1].checked=true;" size="20" />
									<br />
									<label for="confirmpassword" style="width:120px"><?php echo $_lang['change_password_confirm']; ?>:</label>
									<input type="password" name="confirmpassword" onChange="documentdirty=true;" onKeyPress="document.userform.passwordgenmethod[1].checked=true;" size="20" />
									<br />
									<small><span class="warning" style="font-weight:normal"><?php echo $_lang['password_gen_length']; ?></span></small> </div>
							</fieldset>
							<br />
							<fieldset style="width:300px">
								<legend><?php echo $_lang['password_method']; ?></legend>
								<input type=radio name="passwordnotifymethod" value="e" <?php echo get_by_key($_POST, 'passwordnotifymethod') === 'e' ? 'checked="checked"' : ""; ?> />
								<?php echo $_lang['password_method_email']; ?>
								<br />
								<input type=radio name="passwordnotifymethod" value="s" <?php echo get_by_key($_POST, 'passwordnotifymethod') === 'e' ? '' : 'checked="checked"'; ?> />
								<?php echo $_lang['password_method_screen']; ?>
							</fieldset>
							</span></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_full_name']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" name="fullname" class="inputBox" value="<?php echo $modx->getPhpCompat()->htmlspecialchars(isset($_POST['fullname']) ? $_POST['fullname'] : $userdata['fullname']); ?>" onChange="documentDirty=true;" /></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_email']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" name="email" class="inputBox" value="<?php echo isset($_POST['email']) ? $_POST['email'] : $userdata['email']; ?>" onChange="documentDirty=true;" />
							<input type="hidden" name="oldemail" value="<?php echo $modx->getPhpCompat()->htmlspecialchars(!empty($userdata['oldemail']) ? $userdata['oldemail'] : $userdata['email']); ?>" /></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_phone']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" name="phone" class="inputBox" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : $userdata['phone']; ?>" onChange="documentDirty=true;" /></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_mobile']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" name="mobilephone" class="inputBox" value="<?php echo isset($_POST['mobilephone']) ? $_POST['mobilephone'] : $userdata['mobilephone']; ?>" onChange="documentDirty=true;" /></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_fax']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" name="fax" class="inputBox" value="<?php echo isset($_POST['fax']) ? $_POST['fax'] : $userdata['fax']; ?>" onChange="documentDirty=true;" /></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_street']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" name="street" class="inputBox" value="<?php echo $modx->getPhpCompat()->htmlspecialchars($userdata['street']); ?>" onChange="documentDirty=true;" /></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_city']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" name="city" class="inputBox" value="<?php echo $modx->getPhpCompat()->htmlspecialchars($userdata['city']); ?>" onChange="documentDirty=true;" /></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_state']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" name="state" class="inputBox" value="<?php echo isset($_POST['state']) ? $_POST['state'] : $userdata['state']; ?>" onChange="documentDirty=true;" /></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_zip']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" name="zip" class="inputBox" value="<?php echo isset($_POST['zip']) ? $_POST['zip'] : $userdata['zip']; ?>" onChange="documentDirty=true;" /></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_country']; ?>:</th>
						<td>&nbsp;</td>
						<td><select name="country" onChange="documentDirty=true;">
								<?php $chosenCountry = isset($_POST['country']) ? $_POST['country'] : $userdata['country']; ?>
								<option value="" <?php (!isset($chosenCountry) ? ' selected' : '') ?> >&nbsp;</option>
								<?php
								foreach($_country_lang as $key => $country) {
									echo "<option value=\"$key\"" . (isset($chosenCountry) && $chosenCountry == $key ? ' selected' : '') . ">$country</option>";
								}
								?>
							</select></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_dob']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="text" id="dob" name="dob" class="DatePicker" value="<?php echo isset($_POST['dob']) ? $_POST['dob'] : ($userdata['dob'] ? $modx->toDateFormat($userdata['dob'], 'dateOnly') : ""); ?>" onBlur='documentDirty=true;' readonly />
							<i onClick="document.userform.dob.value=''; return true;" class="clearDate <?php echo $_style["icon_calendar_close"] ?>" data-tooltip="<?php echo $_lang['remove_date']; ?>"></i></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_gender']; ?>:</th>
						<td>&nbsp;</td>
						<td><select name="gender" onChange="documentDirty=true;">
								<option value=""></option>
								<option value="1" <?php echo (get_by_key($_POST, 'gender') === '1' || $userdata['gender'] == '1') ? "selected='selected'" : ""; ?>><?php echo $_lang['user_male']; ?></option>
								<option value="2" <?php echo (get_by_key($_POST, 'gender') === '2' || $userdata['gender'] == '2') ? "selected='selected'" : ""; ?>><?php echo $_lang['user_female']; ?></option>
								<option value="3" <?php echo (get_by_key($_POST, 'gender') === '3' || $userdata['gender'] == '3') ? "selected='selected'" : ""; ?>><?php echo $_lang['user_other']; ?></option>
							</select></td>
					</tr>
					<tr>
						<th><?php echo $_lang['comment']; ?>:</th>
						<td>&nbsp;</td>
						<td><textarea type="text" name="comment" class="inputBox" rows="5" onChange="documentDirty=true;"><?php echo $modx->getPhpCompat()->htmlspecialchars(isset($_POST['comment']) ? $_POST['comment'] : $userdata['comment']); ?></textarea></td>
					</tr>
					<tr>
						<th><?php echo $_lang['user_verification']; ?>:</th>
						<td>&nbsp;</td>
						<td><input type="checkbox" name="verified" value="1" <?php echo ($userdata['verified'] == 1 || $modx->getManagerApi()->action == 87 ? 'checked ' : ''); ?><?php echo ($modx->getManagerApi()->action == 87 ? 'disabled' : ''); ?>></td>
					</tr>
					<?php if($modx->getManagerApi()->action == '88') { ?>
						<tr>
							<th><?php echo $_lang['user_logincount']; ?>:</th>
							<td>&nbsp;</td>
							<td><?php echo $userdata['logincount'] ?></td>
						</tr>
						<tr>
							<th><?php echo $_lang['user_prevlogin']; ?>:</th>
							<td>&nbsp;</td>
							<td><?php echo $modx->toDateFormat($userdata['thislogin'] + $modx->getConfig('server_offset_time')) ?></td>
						</tr>
						<tr>
							<th><?php echo $_lang['user_failedlogincount']; ?>:</th>
							<td>&nbsp;
								<input type="hidden" name="failedlogincount" onChange='documentDirty=true;' value="<?php echo $userdata['failedlogincount']; ?>"></td>
							<td><span id='failed'><?php echo $userdata['failedlogincount'] ?></span>&nbsp;&nbsp;&nbsp;[<a href="javascript:resetFailed()"><?php echo $_lang['reset_failedlogins']; ?></a>]</td>
						</tr>
						<tr>
							<th><?php echo $_lang['user_block']; ?>:</th>
							<td>&nbsp;</td>
							<td><input name="blockedcheck" type="checkbox" onClick="changeblockstate(document.userform.blockedmode, document.userform.blockedcheck);"<?php echo ($userdata['blocked'] == 1 || ($userdata['blockeduntil'] > time() && $userdata['blockeduntil'] != 0) || ($userdata['blockedafter'] < time() && $userdata['blockedafter'] != 0)) ? " checked='checked'" : ""; ?> />
								<input type="hidden" name="blocked" value="<?php echo ($userdata['blocked'] == 1 || ($userdata['blockeduntil'] > time() && $userdata['blockeduntil'] != 0)) ? 1 : 0; ?>"></td>
						</tr>
						<tr>
							<th><?php echo $_lang['user_blockeduntil']; ?>:</th>
							<td>&nbsp;</td>
							<td><input type="text" id="blockeduntil" name="blockeduntil" class="DatePicker" value="<?php echo isset($_POST['blockeduntil']) ? $_POST['blockeduntil'] : ($userdata['blockeduntil'] ? $modx->toDateFormat($userdata['blockeduntil']) : ""); ?>" onBlur='documentDirty=true;' readonly />
								<i onClick="document.userform.blockeduntil.value=''; return true;" class="clearDate <?php echo $_style["icon_calendar_close"] ?>" data-tooltip="<?php echo $_lang['remove_date']; ?>"></i></td>
						</tr>
						<tr>
							<th><?php echo $_lang['user_blockedafter']; ?>:</th>
							<td>&nbsp;</td>
							<td><input type="text" id="blockedafter" name="blockedafter" class="DatePicker" value="<?php echo isset($_POST['blockedafter']) ? $_POST['blockedafter'] : ($userdata['blockedafter'] ? $modx->toDateFormat($userdata['blockedafter']) : ""); ?>" onBlur='documentDirty=true;' readonly />
								<i onClick="document.userform.blockedafter.value=''; return true;" class="clearDate <?php echo $_style["icon_calendar_close"] ?>" data-tooltip="<?php echo $_lang['remove_date']; ?>"></i></td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>

			<!-- Settings -->
			<div class="tab-page" id="tabSettings">
				<h2 class="tab"><?php echo $_lang["settings_users"] ?></h2>
				<script type="text/javascript">tpUser.addTabPage(document.getElementById("tabSettings"));</script>
				<table border="0" cellspacing="0" cellpadding="3" class="table table--edit table--editUser">
					<tr>
						<th><?php echo $_lang["login_homepage"] ?></th>
						<td><input onChange="documentDirty=true;" type='text' maxlength='50' name="login_home" value="<?php echo isset($_POST['login_home']) ? $_POST['login_home'] : (isset($usersettings['login_home']) ? $usersettings['login_home'] : ""); ?>"></td>
					</tr>
					<tr>
						<td width="200">&nbsp;</td>
						<td class='comment'><?php echo $_lang["login_homepage_message"] ?></td>
					</tr>
					<tr>
						<th><?php echo $_lang["login_allowed_ip"] ?></th>
						<td><input onChange="documentDirty=true;" type="text" maxlength='255' style="width: 300px;" name="allowed_ip" value="<?php echo isset($_POST['allowed_ip']) ? $_POST['allowed_ip'] : (isset($usersettings['allowed_ip']) ? $usersettings['allowed_ip'] : ""); ?>" /></td>
					</tr>
					<tr>
						<td width="200">&nbsp;</td>
						<td class='comment'><?php echo $_lang["login_allowed_ip_message"] ?></td>
					</tr>
					<tr>
						<th><?php echo $_lang["login_allowed_days"] ?></th>
						<td><label><?php if(!isset($usersettings['allowed_days'])) $usersettings['allowed_days'] = ''; ?>
								<input onChange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="1" <?php echo strpos($usersettings['allowed_days'], '1') !== false ? "checked='checked'" : ""; ?> />
								<?php echo $_lang['sunday']; ?></label>
							<br />
							<label>
								<input onChange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="2" <?php echo strpos($usersettings['allowed_days'], '2') !== false ? "checked='checked'" : ""; ?> />
								<?php echo $_lang['monday']; ?></label>
							<br />
							<label>
								<input onChange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="3" <?php echo strpos($usersettings['allowed_days'], '3') !== false ? "checked='checked'" : ""; ?> />
								<?php echo $_lang['tuesday']; ?></label>
							<br />
							<label>
								<input onChange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="4" <?php echo strpos($usersettings['allowed_days'], '4') !== false ? "checked='checked'" : ""; ?> />
								<?php echo $_lang['wednesday']; ?></label>
							<br />
							<label>
								<input onChange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="5" <?php echo strpos($usersettings['allowed_days'], '5') !== false ? "checked='checked'" : ""; ?> />
								<?php echo $_lang['thursday']; ?></label>
							<br />
							<label>
								<input onChange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="6" <?php echo strpos($usersettings['allowed_days'], '6') !== false ? "checked='checked'" : ""; ?> />
								<?php echo $_lang['friday']; ?></label>
							<br />
							<label>
								<input onChange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="7" <?php echo strpos($usersettings['allowed_days'], '7') !== false ? "checked='checked'" : ""; ?> />
								<?php echo $_lang['saturday']; ?></label>
							<br /></td>
					</tr>
					<tr>
						<td width="200">&nbsp;</td>
						<td class='comment'><?php echo $_lang["login_allowed_days_message"] ?></td>
					</tr>
				</table>
			</div>

			<!-- Photo -->
			<div class="tab-page" id="tabPhoto">
				<h2 class="tab"><?php echo $_lang["settings_photo"] ?></h2>
				<script type="text/javascript">tpUser.addTabPage(document.getElementById("tabPhoto"));</script>
				<script type="text/javascript">
					function OpenServerBrowser(url, width, height) {
						var iLeft = (screen.width - width) / 2;
						var iTop = (screen.height - height) / 2;

						var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes";
						sOptions += ",width=" + width;
						sOptions += ",height=" + height;
						sOptions += ",left=" + iLeft;
						sOptions += ",top=" + iTop;

						var oWindow = window.open(url, "FCKBrowseWindow", sOptions);
					}

					function BrowseServer() {
						var w = screen.width * 0.7;
						var h = screen.height * 0.7;
						OpenServerBrowser("<?php echo MODX_MANAGER_URL;?>media/browser/<?php echo $modx->getConfig('which_browser');?>/browser.php?Type=images", w, h);
					}

					function SetUrl(url, width, height, alt) {
						document.userform.photo.value = url;
						document.images['iphoto'].src = "<?php echo MODX_BASE_URL; ?>" + url;
					}
				</script>
				<table border="0" cellspacing="0" cellpadding="3" class="table table--edit table--editUser">
					<tr>
						<th><?php echo $_lang["user_photo"] ?></th>
						<td><input onChange="documentDirty=true;" type='text' maxlength='255' name="photo" value="<?php echo $modx->getPhpCompat()->htmlspecialchars(isset($_POST['photo']) ? $_POST['photo'] : $userdata['photo']); ?>" />
							<input type="button" value="<?php echo $_lang['insert']; ?>" onClick="BrowseServer();" /></td>
					</tr>
					<tr>
						<td width="200">&nbsp;</td>
						<td class='comment'><?php echo $_lang["user_photo_message"] ?></td>
					</tr>
					<tr>
                        <?php
                        $out = '';
                            if (isset($_POST['photo'])) {
                                if((strpos($_POST['photo'], "http://") === false)){
                                    $out = MODX_SITE_URL;
                                }
                                $out.=$_POST['photo'];
                            }else {
                                if(!empty($userdata['photo'])){
                                    if((strpos($_POST['photo'], "http://") === false)){
                                    $out = MODX_SITE_URL;
                                }
                                $out.=$userdata['photo'];

                                }else {
                                    $out = $_style["tx"];
                                }
                            }
                        ?>
						<td colspan="2" align="center"><img name="iphoto" src="<?php echo $out;  ?>" /></td>
					</tr>
				</table>
			</div>
			<?php
			if($modx->getConfig('use_udperms')) {

			$groupsarray = array();

			if($modx->getManagerApi()->action == '88') { // only do this bit if the user is being edited
				$groupsarray = \EvolutionCMS\Models\WebGroup::query()->where('webuser', $user)->pluck('webgroup');
			}
			// retain selected user groups between post
			if(isset($_POST['user_groups']) && is_array($_POST['user_groups'])) {
				foreach($_POST['user_groups'] as $n => $v) $groupsarray[] = $v;
			}
			?>
			<div class="tab-page" id="tabPermissions">
				<h2 class="tab"><?php echo $_lang['web_access_permissions'] ?></h2>
				<script type="text/javascript">tpUser.addTabPage(document.getElementById("tabPermissions"));</script>
				<p><?php echo $_lang['access_permissions_user_message'] ?></p>
				<?php
				$webgroupnames = \EvolutionCMS\Models\WebgroupName::query()->orderBy('name')->get();
				foreach ($webgroupnames->toArray() as $row) {
					echo '<label><input type="checkbox" name="user_groups[]" value="' . $row['id'] . '"' . (in_array($row['id'], $groupsarray) ? ' checked="checked"' : '') . ' />' . $row['name'] . '</label><br />';
				}
				}
				?>
			</div>
			<?php
			// invoke OnWUsrFormRender event
			$evtOut = $modx->invokeEvent("OnWUsrFormRender", array(
				"id" => $user
			));
			if(is_array($evtOut)) {
				echo implode("", $evtOut);
			}
			?>
		</div>
	</div>
	<input type="submit" name="save" style="display:none">
</form>
