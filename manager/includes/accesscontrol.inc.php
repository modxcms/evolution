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
<meta http-equiv="content-type" content="text/html; charset=<?php echo $modx_charset; ?>" />
<meta name="robots" content="noindex, nofollow" />
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css<?php echo "?$theme_refresher";?>" />

<script type="text/javascript">var MODX_MEDIA_PATH = "<?php echo IN_MANAGER_MODE ? "media":"manager/media"; ?>";</script>

<script type="text/javascript" src="media/script/modx.js"></script>

<script type="text/javascript">
/* <![CDATA[ */
	document.setIncludePath("media/script/bin/");

	document.addEventListener("oninit",function() {
		document.include("cookie");
		document.include("dynelement");
	})

	if (top.frames.length!=0) {
		top.location=self.document.location;
	}
/* ]]> */
</script>
</head>
<body onload="javascript:document.loginfrm.username.focus();" id="login">

<!-- start the login box -->
<div id="mx_loginbox">
    
<form method="post" name="loginfrm" id="loginfrm" action="processors/login.processor.php">

    <!-- anything to output before the login box via a plugin? -->
    <?php
        // invoke OnManagerLoginFormPrerender event
        $evtOut = $modx->invokeEvent('OnManagerLoginFormPrerender');
        if(is_array($evtOut)) echo implode('',$evtOut);
    ?>

    <!-- the logo -->
    <div id="mx_logobox">
        <img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/logoaccess.jpg' alt='<?php echo $_lang["logo_slogan"]; ?>' />

        <!-- override support link here -->
        <?php
            $pth = dirname(__FILE__);
            $file = "$pth/support.inc.php";
            $ov_file = "$pth/override.support.inc.php";	// detect override file
            if(file_exists($ov_file)) $inc = include_once($ov_file);
            else if(file_exists($file)) $inc = include_once($file);
            if($inc)showSupportLink();
        ?>
    </div>
    <!-- end #mx_logobox -->

    <div id="mx_loginArea">
        <h1 class="siteName"><?php echo $site_name; ?></h1>

        <div class="loginMessage"><?php echo $_lang["login_message"]; echo $use_captcha==1 ? "<p>".$_lang["login_captcha_message"]."</p>" : "" ; ?></div>

        <?php if($use_captcha==1) { ?>
            <a href="<?php echo $_SERVER['PHP_SELF'];?>" class="loginCaptcha"><img src="includes/veriword.php?rand=<?php echo rand(); ?>" alt="<?php echo $_lang["login_captcha_message"]; ?>" /></a>
        <?php } ?>

        <label><?php echo $_lang["username"]; ?> </label>
        <input type="text" class="text" name="username" id="username" tabindex="1" value="<?php echo $uid ?>" /></td>

        <label><?php echo $_lang["password"]; ?> </label>
        <input type="password" class="text" name="password" id="password" tabindex="2" value="" />

        <?php if($use_captcha==1) { ?>
        <label><?php echo $_lang["captcha_code"]; ?></label>
        <input type="text" name="captcha_code" tabindex="3" value="" />
        <?php } ?>

        <input type="checkbox" id="rememberme" tabindex="4" value="" class="checkbox" <?php echo isset($cookieSet) ? "checked=\"checked\"" : ""; ?> /><label for="rememberme" style="cursor:pointer"><?php echo $_lang["remember_username"]; ?><input type="submit" class="login" id="submitButton" value="<?php echo $_lang["login_button"]; ?>" onclick="document.loginfrm.submit();" />
        </label>
        

        <!-- anything to output before the login box via a plugin ... like the forgot password link? -->
        <?php
            // invoke OnManagerLoginFormRender event
            $evtOut = $modx->invokeEvent('OnManagerLoginFormRender');
            if(is_array($evtOut)) echo '<div id="onManagerLoginFormRender">'.implode('',$evtOut).'</div>';
        ?>

    </div>

    
    <br style="clear:both;height: 1px"/>

</form>
</div>
<!-- close #mx_loginbox -->


<!-- convert this to a language include -->
<p class="loginLicense">
	<strong>MODx</strong>&trade; is licensed under the GPL license. &copy; 2005-2006 by the <a href="http://modxcms.com/" target="_blank">MODx CMF Team</a>.
</p>



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
