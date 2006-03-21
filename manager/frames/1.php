<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
include_once("browsercheck.inc.php");
$browser = $client->property('browser');
$_SESSION['browser']=$browser;
$version = $client->property('version');
$_SESSION['browser_version']=$version;

<head>
<title><?php echo $site_name." - (MODx CMS Manager)"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
<script language="JavaScript" src="../media/script/session.js"></script>
</head>
<frameset rows="0,20,50,*" frameborder="0" border="0">
	<frame src="index.php?a=1&f=scripter" name="scripter" scrolling="no" noresize>
	<frame src="index.php?a=1&f=topbar" name="topFrame" scrolling="no" noresize>
	<frame name="mainMenu" src="index.php?a=1&f=l4mnu" scrolling="no" noresize>
	<frameset cols="280,*" border="<?php echo !($browser=='ie') ? 6 : 0 ;?>" frameborder="yes" FRAMESPACING="<?php echo !($browser=='ie') ? 1 : 6 ;?>"  resize>
		<frame src="index.php?a=1&f=3" name="menu" FRAMEBORDER="no" BORDER="0" bordercolor="#FFFFFF" scrolling="AUTO">
		<frame src="index.php?a=2" name="main" scrolling="auto" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#FFFFFF">
	</frameset>
</frameset>
<noframes>This software requires a browser with support for frames.</noframes>
</html>