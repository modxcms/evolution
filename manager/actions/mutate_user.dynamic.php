<?php
if (IN_MANAGER_MODE != "true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

switch($_REQUEST['a']) {
  case 12:
    if (!$modx->hasPermission('edit_user')) {
      $e->setError(3);
      $e->dumpError();
    }
    break;
  case 11:
    if (!$modx->hasPermission('new_user')) {
      $e->setError(3);
      $e->dumpError();
    }
    break;
  default:
    $e->setError(3);
    $e->dumpError();  
}

$user = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

// check to see the snippet editor isn't locked
$sql = "SELECT internalKey, username FROM $dbase.`" . $table_prefix . "active_users` WHERE $dbase.`" . $table_prefix . "active_users`.action=12 AND $dbase.`" . $table_prefix . "active_users`.id=$user";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if ($limit > 1) {
	for ($i = 0; $i < $limit; $i++) {
		$lock = mysql_fetch_assoc($rs);
		if ($lock['internalKey'] != $modx->getLoginUserID()) {
			$msg = sprintf($_lang["lock_msg"], $lock['username'], "user");
			$e->setError(5, $msg);
			$e->dumpError();
		}
	}
}
// end check for lock

if ($_REQUEST['a'] == 12) {
	// get user attribute
	$sql = "SELECT * FROM $dbase.`" . $table_prefix . "user_attributes` WHERE $dbase.`" . $table_prefix . "user_attributes`.internalKey = " . $user . ";";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if ($limit > 1) {
		echo "More than one user returned!<p>";
		exit;
	}
	if ($limit < 1) {
		echo "No user returned!<p>";
		exit;
	}
	$userdata = mysql_fetch_assoc($rs);

	// get user settings
	$sql = "SELECT us.* FROM $dbase.`" . $table_prefix . "user_settings` us WHERE us.user = " . $user . ";";
	$rs = mysql_query($sql);
	$usersettings = array ();
	while ($row = mysql_fetch_assoc($rs))
		$usersettings[$row['setting_name']] = $row['setting_value'];
	// manually extract so that user display settings are not overwritten
	foreach ($usersettings as $k => $v) {
		if ($k != 'manager_language' && $k!='manager_theme') {
			${$k} = $v;
		}	
	}
	
	// get user name
	$sql = "SELECT * FROM $dbase.`" . $table_prefix . "manager_users` WHERE $dbase.`" . $table_prefix . "manager_users`.id = " . $user . ";";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if ($limit > 1) {
		echo "More than one user returned while getting username!<p>";
		exit;
	}
	if ($limit < 1) {
		echo "No user returned while getting username!<p>";
		exit;
	}
	$usernamedata = mysql_fetch_assoc($rs);
	$_SESSION['itemname'] = $usernamedata['username'];
} else {
	$userdata = array ();
	$usersettings = array ();
	$usernamedata = array ();
	$_SESSION['itemname'] = "New user";
}

// restore saved form
$formRestored = false;
if ($modx->manager->hasFormValues()) {
	$modx->manager->loadFormValues();
	// restore post values
	$userdata = array_merge($userdata, $_POST);
	$userdata['dob'] = ConvertDate($userdata['dob']);
	$usernamedata['username'] = $userdata['newusername'];
	$usernamedata['oldusername'] = $_POST['oldusername'];
	$usersettings = array_merge($usersettings, $userdata);
	$usersettings['allowed_days'] = is_array($_POST['allowed_days']) ? implode(",", $_POST['allowed_days']) : "";
	extract($usersettings, EXTR_OVERWRITE);
}

// converts date format dd-mm-yyyy to php date
function ConvertDate($date) {
	if ($date == "")
		return "0";
	list ($d, $m, $Y, $H, $M, $S) = sscanf($date, "%2d-%2d-%4d %2d:%2d:%2d");
	if (!$H && !$M && !$S)
		return strtotime("$m/$d/$Y");
	else
		return strtotime("$m/$d/$Y $H:$M:$S");
}

// include the country list language file
$_country_lang = array();
include_once "lang/country/english_country.inc.php";
if($manager_language!="english" && file_exists($modx->config['base_path']."manager/includes/lang/country/".$manager_language."_country.inc.php")){
    include_once "lang/country/".$manager_language."_country.inc.php";
}

$displayStyle = (($_SESSION['browser'] == 'mz') || ($_SESSION['browser'] == 'op')) ? "table-row" : "block";
?>
<script language="JavaScript">

function changestate(element) {
	documentDirty=true;
	currval = eval(element).value;
	if(currval==1) {
		eval(element).value=0;
	} else {
		eval(element).value=1;
	}
}

function changePasswordState(element) {
	currval = eval(element).value;
	if(currval==1) {
		document.getElementById("passwordBlock").style.display="block";
	} else {
		document.getElementById("passwordBlock").style.display="none";
	}
}

function changeblockstate(element, checkelement) {
	currval = eval(element).value;
	if(currval==1) {
		if(confirm("<?php echo $_lang['confirm_unblock']; ?>")==true){
			document.userform.blocked.value=0;
			document.userform.blockeduntil.value="";
			document.userform.blockedafter.value="";
			document.userform.failedlogincount.value=0;
			blocked.innerHTML="<b><?php echo $_lang['unblock_message']; ?></b>";
			blocked.className="TD";
			eval(element).value=0;
		} else {
			eval(checkelement).checked=true;
		}
	} else {
		if(confirm("<?php echo $_lang['confirm_block']; ?>")==true){
			document.userform.blocked.value=1;
			blocked.innerHTML="<b><?php echo $_lang['block_message']; ?></b>";
			blocked.className="warning";
			eval(element).value=1;
		} else {
			eval(checkelement).checked=false;
		}
	}
}

function resetFailed() {
	document.userform.failedlogincount.value=0;
	document.getElementById("failed").innerHTML="0";
}

function deleteuser() {
<?php if($_GET['id']==$modx->getLoginUserID()) { ?>
	alert("<?php echo $_lang['alert_delete_self']; ?>");
<?php } else { ?>
	if(confirm("<?php echo $_lang['confirm_delete_user']; ?>")==true) {
		document.location.href="index.php?id=" + document.userform.id.value + "&a=33";
	}
<?php } ?>
}

// change name
function changeName(){
	if(confirm("<?php echo $_lang['confirm_name_change']; ?>")==true) {
		var e1 = document.getElementById("showname");
		var e2 = document.getElementById("editname");
		e1.style.display = "none";
		e2.style.display = "<?php echo $displayStyle; ?>";
	}
};

// showHide - used by custom settings
function showHide(what, onoff){
	var all = document.getElementsByTagName( "*" );
	var l = all.length;
	var buttonRe = what;
	var id, el, stylevar;

	if(onoff==1) {
		stylevar = "<?php echo $displayStyle; ?>";
	} else {
		stylevar = "none";
	}

	for ( var i = 0; i < l; i++ ) {
		el = all[i]
		id = el.id;
		if ( id == "" ) continue;
		if (buttonRe.test(id)) {
			el.style.display = stylevar;
		}
	}
};

</script>


<form action="index.php?a=32" method="post" name="userform">
<?php

// invoke OnUserFormPrerender event
$evtOut = $modx->invokeEvent("OnUserFormPrerender", array (
	"id" => $id
));
if (is_array($evtOut))
	echo implode("", $evtOut);
?>
<input type="hidden" name="mode" value="<?php echo $_GET['a'] ?>">
<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
<input type="hidden" name="blockedmode" value="<?php echo ($userdata['blocked']==1 || ($userdata['blockeduntil']>time() && $userdata['blockeduntil']!=0)|| ($userdata['blockedafter']<time() && $userdata['blockedafter']!=0) || $userdata['failedlogins']>3) ? "1":"0" ?>" />

<!-- Navrbar -->
<div class="subTitle">
<span class="right"><?php echo $_lang['user_title']; ?></span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<tr>
			<td id="Button1"><a href="#" onclick="documentDirty=false; document.userform.save.click();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang['save']; ?></a></td>
			<td id="Button2"><a href="#" onclick="deleteuser();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle"> <?php echo $_lang['delete']; ?></a></td>
				<?php if($_GET['a']!='12') { ?>
					<script type="text/javascript">document.getElementById("Button2").className='disabled';</script>
				<?php } ?>
			<td id="Button3"><a href="#" onclick="documentDirty=false;document.location.href='index.php?a=75';"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?></a></td>
		</tr>
	</table>
	<div class="stay">
	<table border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td><span class="comment">&nbsp;<?php echo $_lang["after_saving"];?>:</span></td>
		<td><input name="stay" type="radio" class="radio" value="1"  <?php echo $_GET['stay']=='1' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['stay_new']; ?></span></td>
		<td><input name="stay" type="radio" class="radio" value="2"  <?php echo $_GET['stay']=='2' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['stay']; ?></span></td>
		<td><input name="stay" type="radio" class="radio" value=""  <?php echo $_GET['stay']=='' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['close']; ?></span></td>
	</tr>
	</table>
	</div>
</div>

<!-- Tab Start -->
<div class="sectionHeader"><?php echo $_lang['user_title']; ?></div><div class="sectionBody">
<link type="text/css" rel="stylesheet" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css<?php echo "?$theme_refresher";?>" />
<script type="text/javascript" src="media/script/tabpane.js"></script>
<div class="tab-pane" id="userPane">
	<script type="text/javascript">
		tpUser = new WebFXTabPane(document.getElementById( "userPane" ),false);
	</script>
    <div class="tab-page" id="tabGeneral">
    	<h2 class="tab"><?php echo $_lang["settings_general"] ?></h2>
    	<script type="text/javascript">tpUser.addTabPage( document.getElementById( "tabGeneral" ) );</script>
		<table border="0" cellspacing="0" cellpadding="3">
		  <tr>
			<td colspan="3">
				<span id="blocked" class="warning"><?php if($userdata['blocked']==1 || ($userdata['blockeduntil']>time() && $userdata['blockeduntil']!=0) || $userdata['failedlogins']>3) { ?><b><?php echo $_lang['user_is_blocked']; ?></b><?php } ?></span><br />
			</td>
		  </tr>
		  <?php if(!empty($userdata['id'])) { ?>
		  <tr id="showname" style="display: <?php echo ($_GET['a']=='12' && (!isset($usernamedata['oldusername'])||$usernamedata['oldusername']==$usernamedata['username'])) ? $displayStyle : 'none';?> ">
			<td colspan="3">
				<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/user.gif" alt="." />&nbsp;<b><?php echo !empty($usernamedata['oldusername']) ? $usernamedata['oldusername']:$usernamedata['username']; ?></b> - <span class="comment"><a href="#" onclick="changeName();return false;"><?php echo $_lang["change_name"]; ?></a></span>
				<input type="hidden" name="oldusername" value="<?php echo htmlspecialchars(!empty($usernamedata['oldusername']) ? $usernamedata['oldusername']:$usernamedata['username']); ?>" />
				<hr />
			</td>
		  </tr>
		  <?php } ?>
		  <tr id="editname" style="display:<?php echo $_GET['a']=='11'||(isset($usernamedata['oldusername']) && $usernamedata['oldusername']!=$usernamedata['username']) ? $displayStyle : 'none' ; ?>">
			<td><?php echo $_lang['username']; ?>:</td>
			<td>&nbsp;</td>
			<td><input type="text" name="newusername" class="inputBox" style="width:300px" value="<?php echo htmlspecialchars($usernamedata['username']); ?>" onchange='documentDirty=true;' maxlength="100" /></td>
		  </tr>
		  <tr>
			<td valign="top"><?php echo $_GET['a']=='11' ? $_lang['password'].":" : $_lang['change_password_new'].":" ; ?></td>
			<td>&nbsp;</td>
			<td><input name="newpasswordcheck" type="checkbox" onclick="changestate(document.userform.newpassword);changePasswordState(document.userform.newpassword);"<?php echo $_REQUEST['a']=="11" ? " checked disabled": "" ; ?>><input type="hidden" name="newpassword" value="<?php echo $_REQUEST['a']=="11" ? 1 : 0 ; ?>" onchange='documentDirty=true;'><br>
				<span style="display:<?php echo $_REQUEST['a']=="11" ? "block": "none" ; ?>" id="passwordBlock">
				<fieldset style="width:300px">
				<legend><b><?php echo $_lang['password_gen_method']; ?></b></legend>
				<input type=radio name="passwordgenmethod" value="g" <?php echo $_POST['passwordgenmethod']=="spec" ? "" : 'checked="checked"'; ?> /><?php echo $_lang['password_gen_gen']; ?><br>
				<input type=radio name="passwordgenmethod" value="spec" <?php echo $_POST['passwordgenmethod']=="spec" ? 'checked="checked"' : ""; ?>><?php echo $_lang['password_gen_specify']; ?> <br />
				<div style="padding-left:20px">
				<label for="specifiedpassword" style="width:120px"><?php echo $_lang['change_password_new']; ?>:</label>
				<input type="password" name="specifiedpassword" onchange="documentdirty=true;" onkeypress="document.userform.passwordgenmethod[1].checked=true;" size="20" /><br />
				<label for="confirmpassword" style="width:120px"><?php echo $_lang['change_password_confirm']; ?>:</label>
				<input type="password" name="confirmpassword" onchange="documentdirty=true;" onkeypress="document.userform.passwordgenmethod[1].checked=true;" size="20" /><br />
				<small><span class="warning" style="font-weight:normal"><?php echo $_lang['password_gen_length']; ?></span></small>
				</div>
				</fieldset>
				<br />
				<fieldset style="width:300px">
				<legend><b><?php echo $_lang['password_method']; ?></b></legend>
				<input type=radio name="passwordnotifymethod" value="e" <?php echo $_POST['passwordnotifymethod']=="e" ? 'checked="checked"' : ""; ?> /><?php echo $_lang['password_method_email']; ?><br>
				<input type=radio name="passwordnotifymethod" value="s" <?php echo $_POST['passwordnotifymethod']=="e" ? "" : 'checked="checked"'; ?> /><?php echo $_lang['password_method_screen']; ?>
				</fieldset>
				</span>
			</td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_full_name']; ?>:</td>
			<td>&nbsp;</td>
			<td><input type="text" name="fullname" class="inputBox" style="width:300px" value="<?php echo htmlspecialchars($userdata['fullname']); ?>" onchange='documentDirty=true;'></td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_email']; ?>:</td>
			<td>&nbsp;</td>
			<td>
			<input type="text" name="email" class="inputBox" style="width:300px" value="<?php echo htmlspecialchars($userdata['email']); ?>" onchange='documentDirty=true;'>
			<input type="hidden" name="oldemail" value="<?php echo htmlspecialchars(!empty($userdata['oldemail']) ? $userdata['oldemail']:$userdata['email']); ?>" />
			</td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_role']; ?>:</td>
			<td>&nbsp;</td>
			<td>
		<?php

$notAdmin = ($_SESSION['mgrRole'] == 1) ? "" : "WHERE id != 1";
$sql = "select name, id from $dbase.`" . $table_prefix . "user_roles` $notAdmin";
$rs = mysql_query($sql);
?>
		<select name="role" class="inputBox" onchange='documentDirty=true;' style="width:300px">
		<?php

while ($row = mysql_fetch_assoc($rs)) {
	$selectedtext = $row['id'] == $userdata['role'] ? "selected='selected'" : "";
?>
			<option value="<?php echo $row['id']; ?>" <?php echo $selectedtext; ?>><?php echo $row['name']; ?></option>
		<?php

}
?>
		</select>
			</td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_phone']; ?>:</td>
			<td>&nbsp;</td>
			<td><input type="text" name="phone" class="inputBox" style="width:300px" value="<?php echo htmlspecialchars($userdata['phone']); ?>" onchange='documentDirty=true;'></td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_mobile']; ?>:</td>
			<td>&nbsp;</td>
			<td><input type="text" name="mobilephone" class="inputBox" style="width:300px" value="<?php echo htmlspecialchars($userdata['mobilephone']); ?>" onchange='documentDirty=true;'></td>
		  </tr>		  
		  <tr>	  
			<td><?php echo $_lang['user_fax']; ?>:</td>
			<td>&nbsp;</td>
			<td><input type="text" name="fax" class="inputBox" style="width:300px" value="<?php echo htmlspecialchars($userdata['fax']); ?>" onchange='documentDirty=true;'></td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_state']; ?>:</td>
			<td>&nbsp;</td>
			<td><input type="text" name="state" class="inputBox" style="width:300px" value="<?php echo htmlspecialchars($userdata['state']); ?>" onchange='documentDirty=true;'></td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_zip']; ?>:</td>
			<td>&nbsp;</td>
			<td><input type="text" name="zip" class="inputBox" style="width:300px" value="<?php echo htmlspecialchars($userdata['zip']); ?>" onchange='documentDirty=true;'></td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_country']; ?>:</td>
			<td>&nbsp;</td>
			<td>
			<select size="1" name="country" style="width:300px" onChange='documentDirty=true;'>
            <?php $chosenCountry = isset($_POST['country']) ? $_POST['country'] : $userdata['country']; ?>
			<option value="" <?php (!isset($chosenCountry) ? ' selected' : '') ?> >&nbsp;</option>
				<?php
				foreach ($_country_lang as $key => $country) {
				 echo "<option value=\"$key\"".(isset($chosenCountry) && $chosenCountry == $key ? ' selected' : '') .">$country</option>";
				}
				?>
            </select>
            </td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_dob']; ?>:</td>
			<td>&nbsp;</td>
			<td>
				<input type="text" name="dob" class="inputBox" style="width:260px" value="<?php echo ($userdata['dob'] ? strftime("%d-%m-%Y", $userdata['dob']):""); ?>" onblur='documentDirty=true;' readonly="readonly">
				<a onclick="documentDirty=false; calDOB.popup();" onmouseover="window.status='<?php echo $_lang['select_date']; ?>'; return true;" onmouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal.gif" width="16" height="16" border="0" alt="<?php echo $_lang['select_date']; ?>"></a>
				<a onclick="document.userform.dob.value=''; return true;" onmouseover="window.status='<?php echo $_lang['remove_date']; ?>'; return true;" onmouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="<?php echo $_lang['remove_date']; ?>"></a>
			</td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_gender']; ?>:</td>
			<td>&nbsp;</td>
			<td><select name="gender" style="width:300px" onchange='documentDirty=true;'>
				<option value=""></option>
				<option value="1" <?php echo ($userdata['gender']=='1')? "selected='selected'":""; ?>><?php echo $_lang['user_male']; ?></option>
				<option value="2" <?php echo ($userdata['gender']=='2')? "selected='selected'":""; ?>><?php echo $_lang['user_female']; ?></option>
				</select>
			</td>
		  </tr>
		  <tr>
			<td valign="top"><?php echo $_lang['comment']; ?>:</td>
			<td>&nbsp;</td>
			<td>
				<textarea type="text" name="comment" class="inputBox"  rows="5" style="width:300px" onchange='documentDirty=true;'><?php echo htmlspecialchars($userdata['comment']); ?></textarea>
			</td>
		  </tr>
		<?php if($_GET['a']=='12') { ?>
		  <tr>
			<td><?php echo $_lang['user_logincount']; ?>:</td>
			<td>&nbsp;</td>
			<td><?php echo $userdata['logincount'] ?></td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_prevlogin']; ?>:</td>
			<td>&nbsp;</td>
			<td><?php echo strftime('%d-%m-%y %H:%M:%S', $userdata['lastlogin']+$server_offset_time) ?></td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_failedlogincount']; ?>:</td>
			<td>&nbsp;<input type="hidden" name="failedlogincount"  onchange='documentDirty=true;' value="<?php echo $userdata['failedlogincount']; ?>"></td>
			<td><span id='failed'><?php echo $userdata['failedlogincount'] ?></span>&nbsp;&nbsp;&nbsp;[<a href="javascript:resetFailed()"><?php echo $_lang['reset_failedlogins']; ?></a>]</td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_block']; ?>:</td>
			<td>&nbsp;</td>
			<td><input name="blockedcheck" type="checkbox" onclick="changeblockstate(document.userform.blocked, document.userform.blockedcheck);"<?php echo ($userdata['blocked']==1||($userdata['blockeduntil']>time() && $userdata['blockeduntil']!=0)) ? " checked": "" ; ?>><input type="hidden" name="blocked" value="<?php echo ($userdata['blocked']==1||($userdata['blockeduntil']>time() && $userdata['blockeduntil']!=0))?1:0; ?>"></td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_blockeduntil']; ?>:</td>
			<td>&nbsp;</td>
			<td>
				<input type="text" name="blockeduntil" class="inputBox" style="width:260px" value="<?php echo ($userdata['blockeduntil'] ? strftime("%d-%m-%Y %H:%M:%S", $userdata['blockeduntil']):""); ?>" onblur='documentDirty=true;' readonly="readonly">
				<a onclick="documentDirty=false; calBUntil.popup();" onmouseover="window.status='<?php echo $_lang['select_date']; ?>'; return true;" onmouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal.gif" width="16" height="16" border="0" alt="<?php echo $_lang['select_date']; ?>" /></a>
				<a onclick="document.userform.blockeduntil.value=''; return true;" onmouseover="window.status='<?php echo $_lang['remove_date']; ?>'; return true;" onmouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="<?php echo $_lang['remove_date']; ?>" /></a>
			</td>
		  </tr>
		  <tr>
			<td><?php echo $_lang['user_blockedafter']; ?>:</td>
			<td>&nbsp;</td>
			<td>
				<input type="text" name="blockedafter" class="inputBox" style="width:260px" value="<?php echo ($userdata['blockedafter'] ? strftime("%d-%m-%Y %H:%M:%S", $userdata['blockedafter']):""); ?>" onblur='documentDirty=true;' readonly="readonly">
				<a onclick="documentDirty=false; calBAfter.popup();" onmouseover="window.status='<?php echo $_lang['select_date']; ?>'; return true;" onmouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal.gif" width="16" height="16" border="0" alt="<?php echo $_lang['select_date']; ?>" /></a>
				<a onclick="document.userform.blockedafter.value=''; return true;" onmouseover="window.status='<?php echo $_lang['remove_date']; ?>'; return true;" onmouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="<?php echo $_lang['remove_date']; ?>" /></a>
			</td>
		  </tr>
		<?php

}
?>
		</table>
		<?php if($_GET['id']==$modx->getLoginUserID()) { ?><b><?php echo $_lang['user_edit_self_msg']; ?><br><?php } ?>
	</div>
	<!-- Settings -->
    <div class="tab-page" id="tabSettings">
    	<h2 class="tab"><?php echo $_lang["settings_users"] ?></h2>
    	<script type="text/javascript">tpUser.addTabPage( document.getElementById( "tabSettings" ) );</script>
        <table border="0" cellspacing="0" cellpadding="3">
	  <tr>
	    <td class='warning'><?php echo $_lang["language_title"] ?></td>
	    <td> <select name="manager_language" size="1" class="inputBox" onChange="documentDirty=true">
	    <option value=""> </option>
	    <?php
$activelang = !empty($usersettings['manager_language']) ? $usersettings['manager_language'] : $manager_language;
$dir = dir("includes/lang");
while ($file = $dir->read()) {
	if (strpos($file, ".inc.php") > 0) {
		$endpos = strpos($file, ".");
		$languagename = substr($file, 0, $endpos);
		$selectedtext = $languagename == $activelang ? "selected='selected'" : "";
?> 
                <option value="<?php echo $languagename; ?>" <?php echo $selectedtext; ?>><?php echo ucwords(str_replace("_", " ", $languagename)); ?></option> 
                <?php

	}
}
$dir->close();
?> 
              </select>
         </td>
	  </tr>
	   <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["language_message"] ?></td>
          </tr>
	  	  <tr> 
            <td colspan="2"><div class='split'></div></td> 
          </tr>  
           <tr>
            <td nowrap class="warning"><b><?php echo $_lang["manager_direction_title"] ?></b></td>
            <td><select name="manager_direction" size="1" class="inputBox" style="width:100px;" onchange="documentDirty=true;">
                <option value="ltr" <?php echo (isset($usersettings['manager_direction']) && $usersettings['manager_direction']=='ltr') ? 'selected' : ''?>>ltr</option>
                <option value="rtl" <?php echo (isset($usersettings['manager_direction']) && $usersettings['manager_direction']=='rtl') ? 'selected' : ''?>>rtl</option>
              </select></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["manager_direction_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["manager_lang_attribute_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='20' size='10' name="manager_lang_attribute" value="<?php echo isset($usersettings['manager_lang_attribute']) ? $usersettings['manager_lang_attribute'] : 'en' ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["manager_lang_attribute_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td class="warning"><b><?php echo $_lang["mgr_login_start"] ?></b></td>
            <td ><input onchange="documentDirty=true;" type='text' maxlength='50' style="width: 100px;" name="manager_login_startup" value="<?php echo isset($_POST['manager_login_startup']) ? $_POST['manager_login_startup'] : $usersettings['manager_login_startup']; ?>"></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["mgr_login_start_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>

          <tr>
            <td class="warning"valign="top"><b><?php echo $_lang["allow_mgr_access"] ?></b></td>
            <td>
            	<input onchange="documentDirty=true;"  type="radio" name="allow_manager_access" value="1" <?php echo !isset($usersettings['allow_manager_access'])||$usersettings['allow_manager_access']==1 ? ' checked="checked"':'' ; ?> /> <?php echo $_lang['yes']; ?> <br />
            	<input onchange="documentDirty=true;"  type="radio" name="allow_manager_access" value="0" <?php echo isset($usersettings['allow_manager_access']) && $usersettings['allow_manager_access']==0 ? ' checked="checked"':'' ; ?> /> <?php echo $_lang['no']; ?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["allow_mgr_access_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>

          <tr>
            <td nowrap class="warning"valign="top"><b><?php echo $_lang["login_allowed_ip"] ?></b></td>
            <td ><input onchange="documentDirty=true;"  type="text" maxlength='255' style="width: 300px;" name="allowed_ip" value="<?php echo $usersettings['allowed_ip']; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["login_allowed_ip_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"valign="top"><b><?php echo $_lang["login_allowed_days"] ?></b></td>
            <td>
            	<input onchange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="1" <?php echo strpos($usersettings['allowed_days'],'1')!==false ? "checked='checked'":""; ?> /> <?php echo $_lang['sunday']; ?><br />
            	<input onchange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="2" <?php echo strpos($usersettings['allowed_days'],'2')!==false ? "checked='checked'":""; ?> /> <?php echo $_lang['monday']; ?><br />
            	<input onchange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="3" <?php echo strpos($usersettings['allowed_days'],'3')!==false ? "checked='checked'":""; ?> /> <?php echo $_lang['tuesday']; ?><br />
            	<input onchange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="4" <?php echo strpos($usersettings['allowed_days'],'4')!==false ? "checked='checked'":""; ?> /> <?php echo $_lang['wednesday']; ?><br />
            	<input onchange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="5" <?php echo strpos($usersettings['allowed_days'],'5')!==false ? "checked='checked'":""; ?> /> <?php echo $_lang['thursday']; ?><br />
            	<input onchange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="6" <?php echo strpos($usersettings['allowed_days'],'6')!==false ? "checked='checked'":""; ?> /> <?php echo $_lang['friday']; ?><br />
            	<input onchange="documentDirty=true;" type="checkbox" name="allowed_days[]" value="7" <?php echo strpos($usersettings['allowed_days'],'7')!==false ? "checked='checked'":""; ?> /> <?php echo $_lang['saturday']; ?><br />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["login_allowed_days_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <td nowrap class="warning"><b><?php echo $_lang["manager_theme"]?></b></td>
            <td> <select name="manager_theme" size="1" class="inputBox" onchange="documentDirty=true;document.userform.theme_refresher.value = Date.parse(new Date())">
             <option value=""> </option>
             <?php
$dir = dir("media/style/");
while ($file = $dir->read()) {
	if ($file != "." && $file != ".." && is_dir("media/style/$file") && substr($file,0,1) != '.') {
		$themename = $file;
        if (isset ($usersettings['manager_theme'])) {
    		$selectedtext = $themename == $usersettings['manager_theme'] ? "selected='selected'" : "";
        }
		echo "<option value='$themename' $selectedtext>" . ucwords(str_replace("_", " ", $themename)) . "</option>";
	}
}
$dir->close();
?>
             </select><input type="hidden" name="theme_refresher" value=""></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["manager_theme_message"]?></td>
          </tr>
		  <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["filemanager_path_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 300px;" name="filemanager_path" value="<?php echo htmlspecialchars(isset($usersettings['filemanager_path']) ? $usersettings['filemanager_path']:""); ?>">
              </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["filemanager_path_message"]?></td>
          </tr>
          <tr>
          <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["uploadable_images_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="upload_images" value="<?php echo isset($usersettings['upload_images']) ? $usersettings['upload_images'] : "" ; ?>">
              &nbsp;&nbsp; <input onchange="documentDirty=true;" type="checkbox" name="default_upload_images" value="1" <?php echo isset($usersettings['upload_images']) ? '' : 'checked' ; ?>  /> <?php echo $_lang["user_use_config"]; ?><br />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["uploadable_images_message"].$_lang["user_upload_message"]?></td>
          </tr>
          <tr>
          <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["uploadable_media_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="upload_media" value="<?php echo isset($usersettings['upload_media']) ? $usersettings['upload_media'] : "" ; ?>">
				&nbsp;&nbsp; <input onchange="documentDirty=true;" type="checkbox" name="default_upload_media" value="1" <?php echo isset($usersettings['upload_media']) ? '' : 'checked' ; ?>  /> <?php echo $_lang["user_use_config"]; ?><br />            
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["uploadable_media_message"].$_lang["user_upload_message"]?></td>
          </tr>
          <tr>
          <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["uploadable_flash_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="upload_flash" value="<?php echo isset($usersettings['upload_flash']) ? $usersettings['upload_flash'] : "" ; ?>">
            &nbsp;&nbsp; <input onchange="documentDirty=true;" type="checkbox" name="default_upload_flash" value="1" <?php echo isset($usersettings['upload_flash']) ? '' : 'checked' ; ?>  /> <?php echo $_lang["user_use_config"]; ?><br />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["uploadable_flash_message"].$_lang["user_upload_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["uploadable_files_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="upload_files" value="<?php echo isset($usersettings['upload_files']) ? $usersettings['upload_files'] : "" ; ?>">
            &nbsp;&nbsp; <input onchange="documentDirty=true;" type="checkbox" name="default_upload_files" value="1" <?php echo isset($usersettings['upload_files']) ? '' : 'checked' ; ?>  /> <?php echo $_lang["user_use_config"]; ?><br />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["uploadable_files_message"].$_lang["user_upload_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class='row2'>
            <td nowrap class="warning"><b><?php echo $_lang["upload_maxsize_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 300px;" name="upload_maxsize" value="<?php echo isset($usersettings['upload_maxsize']) ? $usersettings['upload_maxsize'] : "" ; ?>">
            </td>
          </tr>
          <tr class='row2'>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["upload_maxsize_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='editorRow0' style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["which_editor_title"]?></b></td>
            <td>
				<select name="which_editor" onchange="documentDirty=true;">
				<option value=""> </option>
					<?php

$edt = isset ($usersettings["which_editor"]) ? $usersettings["which_editor"] : '';
// invoke OnRichTextEditorRegister event
$evtOut = $modx->invokeEvent("OnRichTextEditorRegister");
echo "<option value='none'" . ($edt == 'none' ? " selected='selected'" : "") . ">" . $_lang["none"] . "</option>\n";
if (is_array($evtOut))
	for ($i = 0; $i < count($evtOut); $i++) {
		$editor = $evtOut[$i];
		echo "<option value='$editor'" . ($edt == $editor ? " selected='selected'" : "") . ">$editor</option>\n";
	}
?>
				</select>
			</td>
          </tr>
          <tr id='editorRow1' style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["which_editor_message"]?></td>
          </tr>
          <tr id='editorRow2' style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='rbRow1' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["rb_base_dir_title"]?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 300px;" name="rb_base_dir" value="<?php echo isset($usersettings["rb_base_dir"]) ? $usersettings["rb_base_dir"]:""; ?>" />
              </td>
          </tr>
          <tr id='rbRow2' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["rb_base_dir_message"]?></td>
          </tr>
          <tr id='rbRow3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='rbRow4' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["rb_base_url_title"]?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 300px;" name="rb_base_url" value="<?php echo isset($usersettings["rb_base_url"]) ? $usersettings["rb_base_url"]:""; ?>" />
              </td>
          </tr>
          <tr id='rbRow5' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["rb_base_url_message"]?></td>
          </tr>
          <tr id='rbRow6' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
		  <tr class='row1'>
            <td colspan="2">
		        <?php

// invoke OnInterfaceSettingsRender event
$evtOut = $modx->invokeEvent("OnInterfaceSettingsRender");
if (is_array($evtOut))
	echo implode("", $evtOut);
?>
            </td>
          </tr>
		</table>
	</div>
	<!-- Photo -->
    <div class="tab-page" id="tabPhoto">
    	<h2 class="tab"><?php echo $_lang["settings_photo"] ?></h2>
    	<script type="text/javascript">tpUser.addTabPage( document.getElementById( "tabPhoto" ) );</script>
    	<script type="text/javascript">
			function OpenServerBrowser(url, width, height ) {
				var iLeft = (screen.width  - width) / 2 ;
				var iTop  = (screen.height - height) / 2 ;

				var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes" ;
				sOptions += ",width=" + width ;
				sOptions += ",height=" + height ;
				sOptions += ",left=" + iLeft ;
				sOptions += ",top=" + iTop ;

				var oWindow = window.open( url, "FCKBrowseWindow", sOptions ) ;
			}
			function BrowseServer() {
				var w = screen.width * 0.7;
				var h = screen.height * 0.7;
				OpenServerBrowser("<?php echo $base_url; ?>manager/media/browser/mcpuk/browser.html?Type=images&Connector=<?php echo $base_url; ?>manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath=<?php echo $base_url; ?>", w, h);
			}
			function SetUrl(url, width, height, alt){
				document.userform.photo.value = url;
				document.images['iphoto'].src = url;
			}
		</script>
        <table border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["user_photo"] ?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 150px;" name="photo" value="<?php echo htmlspecialchars($userdata['photo']); ?>" /> <input type="button" value="<?php echo $_lang['insert']; ?>" onclick="BrowseServer();" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["user_photo_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
              <td colspan="2" align="center"><img name="iphoto" src="<?php echo $userdata['photo'] ? $userdata['photo']: "media/style/$manager_theme/images/_tx_.gif"; ?>" /></td>
          </tr>
		</table>
	</div>
</div>
</div>

<?php

if ($use_udperms == 1) {
	$groupsarray = array ();

	if ($_GET['a'] == '12') { // only do this bit if the user is being edited
		$sql = "SELECT * FROM $dbase.`" . $table_prefix . "member_groups` where member=" . $_GET['id'] . "";
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		for ($i = 0; $i < $limit; $i++) {
			$currentgroup = mysql_fetch_assoc($rs);
			$groupsarray[$i] = $currentgroup['user_group'];
		}
	}

	// retain selected doc groups between post
	if (is_array($_POST['user_groups'])) {
		foreach ($_POST['user_groups'] as $n => $v)
			$groupsarray[] = $v;
	}
?>

<div class="sectionHeader"><?php echo $_lang['access_permissions']; ?></div><div class="sectionBody">
<?php

	echo $_lang['access_permissions_user_message'] . "<p />";
	$sql = "SELECT name, id FROM $dbase.`" . $table_prefix . "membergroup_names` ORDER BY name";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	for ($i = 0; $i < $limit; $i++) {
		$row = mysql_fetch_assoc($rs);
		echo "<input type='checkbox' name='user_groups[]' value='" . $row['id'] . "'" . (in_array($row['id'], $groupsarray) ? " checked='checked'" : "") . " />" . $row['name'] . "<br />";
	}
?>
</div>
<?php

}
?>
<input type="submit" name="save" style="display:none">
<?php

// invoke OnUserFormRender event
$evtOut = $modx->invokeEvent("OnUserFormRender", array (
	"id" => $id
));
if (is_array($evtOut))
	echo implode("", $evtOut);
?>
</form>
<script type="text/javascript" src="media/script/datefunctions.js"></script>
<script type="text/javascript">
	// dob
	var calDOB = new calendar1(document.userform.dob, new Object);
	calDOB.path="<?php echo str_replace("index.php", "media/", $_SERVER["PHP_SELF"]); ?>";
	calDOB.year_scroll = true;
	calDOB.time_comp = false;

	if (document.userform.blockeduntil) {
		// block until
		var calBUntil = new calendar1(document.userform.blockeduntil, new Object);
		calBUntil.path="<?php echo str_replace("index.php", "media/", $_SERVER["PHP_SELF"]); ?>";
		calBUntil.year_scroll = true;
		calBUntil.time_comp = true;

		// block after
		var calBAfter = new calendar1(document.userform.blockedafter, new Object);
		calBAfter.path="<?php echo str_replace("index.php", "media/", $_SERVER["PHP_SELF"]); ?>";
		calBAfter.year_scroll = true;
		calBAfter.time_comp = true;
	}
</script>
