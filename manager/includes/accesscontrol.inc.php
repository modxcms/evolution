<?php

// start session
startCMSSession();
if (isset($_SESSION['mgrValidated']) && $_SESSION['usertype']!='manager'){
	unset($_SESSION['mgrValidated']);
	session_destroy();
	// start session
	startCMSSession();
}
if(!isset($_SESSION['mgrValidated'])){
	include_once("browsercheck.inc.php");

	if(isset($manager_language)) {
		include_once "lang/".$manager_language.".inc.php";
	}
	else {
		include_once "lang/english.inc.php";
	}

	$cookieKey = substr(md5($site_id."Admin-User"),0,15);

	include_once ("crypt.class.inc.php");
	if(isset($_COOKIE[$cookieKey])) {
		$cookieSet = 1;
		$username = $_COOKIE[$cookieKey];
	}
	$thepasswd = substr($site_id,-5)."crypto"; // create a password based on site id
	$rc4 = new rc4crypt;
	$thestring = $rc4->endecrypt($thepasswd,$username,'de');
	$uid = $thestring;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>MODx CMF Manager Login</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $etomite_charset; ?>" />
<meta name="robots" content="noindex, nofollow" />
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css<?php echo "?$theme_refresher";?>" />

<script type="text/javascript">var MODX_MEDIA_PATH = "<?php echo IN_MANAGER_MODE ? "media":"manager/media"; ?>";</script>

<script type="text/javascript" src="media/script/modx.js"></script>

<script type="text/javascript">
<![CDATA[
	document.setIncludePath("media/script/bin/");

	document.addEventListener("oninit",function() {
		document.include("cookie");
		document.include("animate");
		document.include("dynelement");
	})

	function checkRemember () {
		if(document.loginfrm.rememberme.value==1) {
			document.loginfrm.rememberme.value=0;
		} else {
			document.loginfrm.rememberme.value=1;
		}
	}

	if (top.frames.length!=0) {
		top.location=self.document.location;
	}

	function enter(nextfield,event) {
		if(event && event.keyCode == 13) {
			if(nextfield.id=='Button1') {
				document.loginfrm.submit();
				return false;
			}
			else {
				nextfield.focus();
				return false;
			}
		} else {
			return true;
		}
	}
]]>
</script>
</head>
<body onload="javascript:document.loginfrm.username.focus();" style="background: #eee">
<form method="post" name="loginfrm" action="processors/login.processor.php">

<input type="hidden" value="<?php echo isset($cookieSet) ? 1 : 0; ?>" name="rememberme" />

<table class="loginBg" width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td align="center" valign="middle" width="100%" height="100%">
	<!-- intro text, logo and login box -->
		<div id="splash" style="width:600px">
		<table border="0" width="600" cellspacing="0" cellpadding="10" class="loginTbl" style="margin:50px auto 15px;border:1px solid #bbb;background: #fff;padding: 30px;">
      <tr>
        <td colspan="2">
<?php
	// invoke OnManagerLoginFormPrerender event
	$evtOut = $modx->invokeEvent('OnManagerLoginFormPrerender');
	if(is_array($evtOut)) echo implode('',$evtOut);
?>
        </td>
      </tr>
		  <tr>
			<td rowspan="2"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/logoaccess.jpg' alt='<?php echo $_lang["logo_slogan"]; ?>' />
			  <p />
			  <?php
				$pth = dirname(__FILE__);
				$file = "$pth/support.inc.php";
				$ov_file = "$pth/override.support.inc.php";	// detect override file
				if(file_exists($ov_file)) $inc = include_once($ov_file);
				else if(file_exists($file)) $inc = include_once($file);
				if($inc)showSupportLink();
			?></td><td><?php echo "<p  align='right'><span class='siteName'>".$site_name."</span></p>"; echo $_lang["login_message"]; echo $use_captcha==1 ? "<p />".$_lang["login_captcha_message"] : "" ; ?></td>
		  </tr>
		  <tr>
		  	<td style="padding-left:50px;">
				<table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<?php if($use_captcha==1) { ?>
					<td>
						<a href="<?php echo $_SERVER['PHP_SELF'];?>"><img src="includes/veriword.php?rand=<?php echo rand(); ?>" width="148" height="60" alt="<?php echo $_lang["login_captcha_message"]; ?>" style="border: 1px solid #003399" /></a>
					</td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<?php } ?>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td><b><?php echo $_lang["username"]; ?>:</b></td>
							<td><input type="text" name="username" tabindex="1" onkeypress="return enter(document.loginfrm.password,event);" size="8" style="width: 150px;" value="<?php echo $uid ?>" /></td>
						  </tr>
						  <tr>
							<td><b><?php echo $_lang["password"]; ?>:</b></td>
							<td><input type="password" name="password" tabindex="2" onkeypress="return enter(<?php echo $use_captcha==1 ? "document.loginfrm.captcha_code" : "document.getElementById('Button1')" ;?>,event);" style="width: 150px;" value="" /></td>
						  </tr>
						  <?php if($use_captcha==1) { ?>
						  <tr>
							<td><b><?php echo $_lang["captcha_code"]; ?>:</b></td>
							<td><input type="text" name="captcha_code" tabindex="3" style="width: 150px;" onkeypress="return enter(document.getElementById('Button1'),event);" value="" /></td>
						  </tr>
						  <?php } ?>
						  <tr>
							<td><label for="thing" style="cursor:pointer"><?php echo $_lang["remember_username"]; ?>:&nbsp; </label></td>
							<td>
								<input type="checkbox" id="thing" name="thing" tabindex="4" size="1" value="" <?php echo isset($cookieSet) ? "checked" : ""; ?> onclick="checkRemember()" />
								<input type="submit" value="<?php echo $_lang["login_button"]; ?>" onclick="document.loginfrm.submit();" />
							</td>
						  </tr>
						</table>
					  </td>
				  </tr>
				</table>
			</td>
		  </tr>
      <tr>
        <td colspan="2">
<?php
	// invoke OnManagerLoginFormRender event
	$evtOut = $modx->invokeEvent('OnManagerLoginFormRender');
	if(is_array($evtOut)) echo implode('',$evtOut);
?>
   
        </td>
      </tr>
		</table>
		<table border="0" width="600" cellspacing="0" cellpadding="10" class="loginLicense">
		  <tr>
			<td>
				<b>MODx</b>&trade; is licensed under the GPL license. &copy; 2005 by the <a href="http://modxcms.com/" target="_blank">MODx CMF Team</a>.
			</td>
		  </tr>
		</table>
		</div>
		<!-- end of intro text and login box -->
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

</form>
</body>
</html>
<?php
	exit;
}

// log user action
if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP");else if(getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR");else if(getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR");else $ip = "UNKNOWN";$_SESSION['ip'] = $ip;
$itemid = isset($_REQUEST['id']) ? $_REQUEST['id'] : NULL ;$lasthittime = time();$a = isset($_REQUEST['a']) ? $_REQUEST['a'] : "" ;
if($a!=1) {
	$sql = "REPLACE INTO $dbase.".$table_prefix."active_users (internalKey, username, lasthit, action, id, ip) values('".$modx->getLoginUserID()."', '".$_SESSION['mgrShortname']."', '".$lasthittime."', '".$a."', ".($itemid==null?"'$itemid'":"NULL").", '$ip')";
	if(!$rs = mysql_query($sql)) {
		echo "error replacing into active users! SQL: ".$sql;
		exit;
	}
}
?>
