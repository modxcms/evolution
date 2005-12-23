<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if($_REQUEST['a']!=8 && isset($_SESSION['mgrValidated'])){
	$homeurl = '../'.$modx->makeUrl($manager_login_startup>0 ? $manager_login_startup:$site_start);
	$logouturl = './index.php?a=8';
	
?>
<html>
<head>
<title>MODx Content Manager</title>
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
			<td><?php echo "<p  align='right'><span class='siteName'>".$site_name."</span></p>"; echo $_lang["logout_message"]; echo $use_captcha==1 ? "<p />".$_lang["login_captcha_message"] : "" ; ?></td>
		  </tr>
		  <tr>
		  	<td style="padding-left:250px;">
				<table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td>
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td align="right">						
									<div id="hompage" tabindex="5" style="width:60px; text-align: center;border:1px solid black;border-left-color:ButtonHighlight;
									border-right-color:ButtonShadow;border-top-color:ButtonHighlight;border-bottom-color:ButtonShadow;padding:3px 4px 3px 4px;" onclick="javascript:window.location.href='<?php echo $homeurl; ?>';">
									<img src="media/images/icons/save.gif" align="absmiddle"> <?php echo $_lang["home"]; ?></div>
									<script>createButton(document.getElementById("hompage"));</script>
							</td>
							<td>&nbsp;</td>
							<td align="right">						
									<div id="Button1" tabindex="5" style="width:60px; text-align: center;border:1px solid black;border-left-color:ButtonHighlight;
									border-right-color:ButtonShadow;border-top-color:ButtonHighlight;border-bottom-color:ButtonShadow;padding:3px 4px 3px 4px;" onclick="javascript:window.location.href='<?php echo $logouturl; ?>';">
									<img src="media/images/icons/save.gif" align="absmiddle"> <?php echo $_lang["logout"]; ?></div>
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
		<table border="0" width="600" cellspacing="0" cellpadding="10" class="loginLicense">
		  <tr>
			<td>
				<b>MODx</b>&trade; is licensed under the GPL license. &copy; 2005 by the <a href="http://modxcms.com/" target="_blank">MODx Team</a>.
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

?>