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
<html>
<head>
<title>MODx Login</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $etomite_charset; ?>" />
<meta name="robots" content="noindex, nofollow" />
<link type="text/css" rel="StyleSheet" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css<?php echo "?$theme_refresher";?>" />
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>coolButtons2.css<?php echo "?$theme_refresher";?>" />
<script type="text/javascript">var MODX_MEDIA_PATH = "<?php echo IN_MANAGER_MODE ? "media":"manager/media"; ?>";</script>
<script type="text/javascript" language="JavaScript" src="media/script/modx.js"></script>
<script type="text/javascript" language="JavaScript">

	document.setIncludePath("media/script/bin/");

	document.addEventListener("oninit",function() { 
		document.include("cookie");
		document.include("animate");
		document.include("dynelement");
	})
</script>
<script type="text/javascript" language="JavaScript" src="media/script/cb2.js"></script>
<style>

body {
	background-color:			#5377A7;}
	
.loginBg {
	background-image: 			url('media/images/bg/login_bg.jpg'); 
	background-position: 		top; 
	background-color:			#5377A7;
	background-repeat: 			repeat-x; 
}
.loginTbl {
	background-color:			white; 
	border:						1px solid #5377A7; /*#003399; */
	background-image: 			url('media/images/bg/section.jpg'); 
	background-position: 		top right; 
	background-repeat: 			no-repeat; 
	padding: 					10px; 
	text-align: 				justify;
}
.loginLicense {
	background-color:			White; 
	border-right:				1px solid #5377A7; 
	border-bottom:				1px solid #5377A7;
	border-left:				1px solid #5377A7;
	background-image: 			url('media/images/bg/login_footer_bg.gif');
	background-position: 		bottom; 
	background-repeat: 			repeat-x; 
	padding: 					10px; 
	text-align: 				justify;
	color:white;
}
.loginLicense td{
	color:navy
}

.notice {
	width:100%;
	padding:5px;
	border:1px solid #eeeeee;
	background-color:#F4F4F4;
	font-size:10px;
	color:#707070;}
	
.siteName {
	font-weight:bold;
	font-family:verdana, arial;
	font-size:18px;
	color:#5377A7;}
	
</style>
<script language="JavaScript">
	
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
</script>
</head>
<body>
<form method="post" name="loginfrm" action="processors/login.processor.php" style="margin: 0px; padding: 0px;"> 
<input type="hidden" value="<?php echo isset($cookieSet) ? 1 : 0; ?>" name="rememberme"> 
<table class="loginBg" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle" width="100%" height="100%">
	<!-- intro text, logo and login box -->
		<div id="splash" style="width:600px">
		<table border="0" width="600" cellspacing="0" cellpadding="10" class="loginTbl">
		  <tr>
			<td rowspan="2"><img src='media/images/misc/logo.png' alt='<?php echo $_lang["logo_slogan"]; ?>'><p />
			<?php 				
				$pth = dirname(__FILE__);
				$file = "$pth/support.inc.php";
				$ov_file = "$pth/override.support.inc.php";	// detect override file
				if(file_exists($ov_file)) $inc = include_once($ov_file);
				else if(file_exists($file)) $inc = include_once($file);
				if($inc)showSupportLink();
			?></td>
			<td><?php echo "<p  align='right'><span class='siteName'>".$site_name."</span></p>"; echo $_lang["login_message"]; echo $use_captcha==1 ? "<p />".$_lang["login_captcha_message"] : "" ; ?></td>
		  </tr>
		  <tr>
		  	<td style="padding-left:50px;">
				<table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<?php if($use_captcha==1) { ?>
					<td>
						<a href="<?php echo $_SERVER['PHP_SELF'];?>"><img src="includes/veriword.php" width="148" height="60" alt="<?php echo $_lang["login_captcha_message"]; ?>" style="border: 1px solid #003399"></a>
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
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td valign="top"><input type="checkbox" id="thing" name="thing" tabindex="4" SIZE="1" value="" <?php echo isset($cookieSet) ? "checked" : ""; ?> onClick="checkRemember()"></td>
									<td align="right">						
											<div id="Button1" tabindex="5" style="width:60px; text-align: center;border:1px solid black;border-left-color:ButtonHighlight;
											border-right-color:ButtonShadow;border-top-color:ButtonHighlight;border-bottom-color:ButtonShadow;padding:3px 4px 3px 4px;" onclick="document.loginfrm.submit();">
											<img src="media/images/icons/save.gif" align="absmiddle"> <?php echo $_lang["login_button"]; ?></div>
											<script>createButton(document.getElementById("Button1"));</script>
									</td>
								  </tr>
								</table>
							</td>
						  </tr>
						</table>
					  </td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
		<table border="0" width="600" cellspacing="0" cellpadding="10" class="loginLicense">
		  <tr>
			<td>
				<b>MODx</b> is a modified version of Etomite 0.6 and is licensed under the GPL license. Etomite is © and ™ of the Etomite project (<a href="http://www.etomite.org" target="_blank">www.etomite.org</a>).<br/><br/><center><span class="notice">The removal of this notice is a breach of the terms and 
				conditions of this software.</span></center><p />
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				  <!--<tr>
					<td><input type="checkbox" id="licenseOK" name="licenseOK" checked='checked' tabindex="6" /></td>
					<td><label for='licenseOK'><i>"I agree to any limitations or freedoms imposed upon me by this license."</i></label></td>
				  </tr>-->
				</table>
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
$itemid = isset($_REQUEST['id']) ? $_REQUEST['id'] : 'NULL' ;$lasthittime = time();$a = isset($_REQUEST['a']) ? $_REQUEST['a'] : "" ;
if($a!=1) {
	$sql = "REPLACE INTO $dbase.".$table_prefix."active_users(internalKey, username, lasthit, action, id, ip) values('".$modx->getLoginUserID()."', '".$_SESSION['mgrShortname']."', '".$lasthittime."', '".$a."', '".$itemid."', '$ip')";
	if(!$rs = mysql_query($sql)) {
		echo "error replacing into active users! SQL: ".$sql;
		exit;
	}
}
?>