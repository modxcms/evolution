<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
include_once("browsercheck.inc.php");
$browser = $client->property('browser');
$_SESSION['browser']=$browser;
$version = $client->property('version');
$_SESSION['browser_version']=$version;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
    <title><?php echo $site_name." - (MODx CMS Manager)"; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>" />
    <script type="text/javascript" src="media/script/session.js"></script>
</head>
<frameset rows="0,20,50,*" border="0">
	<frame src="index.php?a=1&amp;f=scripter" name="scripter" scrolling="no" frameborder="0">
	<frame src="index.php?a=1&amp;f=topbar" name="topFrame" scrolling="no" frameborder="0">
	<frame name="mainMenu" src="index.php?a=1&amp;f=l4mnu" scrolling="no" frameborder="0">
	<frameset cols="260,*" border="3">
		<frame src="index.php?a=1&amp;f=3" name="menu" scrolling="auto" frameborder="0">
		<frame src="index.php?a=2" name="main" scrolling="auto" frameborder="0">
	</frameset>
</frameset>
<noframes>This software requires a browser with support for frames.</noframes>
</html>
