<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
include_once("browsercheck.inc.php");
$browser = $client->property('browser');
$_SESSION['browser']=$browser;
$version = $client->property('version');
$_SESSION['browser_version']=$version;


if (!isset($manager_layout) || $manager_layout==1 || ($manager_layout!=2 && $browser!='ie')) {
// Basic layout #1
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
	<html>
	<head>
	<title><?php echo $site_name." - (MODx Content Manager)"; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
	</head>
	<frameset rows="0,24,*" frameborder="NO" border="0">
		<frame src="index.php?a=1&f=5" name="scripter" scrolling="NO" noresize>
		<frame src="index.php?a=1&f=12" name="topFrame">
		<frameset cols="280,*" border="<?php echo ($browser=='mz' || $browser=='fb') ? 6 : 0 ;?>" frameborder="1" FRAMESPACING="<?php echo ($browser=='mz' || $browser=='fb') ? 1 : 6 ;?>" bordercolor="#4791C5">
			<frameset rows="200,*" name="menuFrame" border="<?php echo ($browser=='mz' || $browser=='fb') ? 6 : 0 ;?>" frameborder="1" FRAMESPACING="<?php echo ($browser=='mz' || $browser=='fb') ? 1 : 6 ;?>" bordercolor="#4791C5">
				<frame src="index.php?a=1&f=2" name="mainMenu" scrolling="NO" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
				<frame src="index.php?a=1&f=3" name="menu" FRAMEBORDER="no" BORDER="0" bordercolor="#4791C5" scrolling="AUTO">
			</frameset>
			<frameset rows="23,*" frameborder="0" border="0" framespacing="0">
				<frame src="index.php?a=1&f=ftbar" scrolling="no" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
				<frame src="index.php?a=2" name="main" scrolling="auto" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
			</frameset>
		</frameset>
	</frameset><noframes>This software requires a browser with support for frames.</noframes>
	</html>
<?php
}
else if ($manager_layout==2) {
// Advance Layout
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
	<html>
	<head>
	<title><?php echo $site_name." - (MODx Content Manager)"; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
	</head>
	<frameset rows="0,24,*" frameborder="NO" border="0">
		<frame src="index.php?a=1&f=5" name="scripter" scrolling="NO" noresize>
		<frame src="index.php?a=1&f=12" name="topFrame">
		<frameset cols="370,*" border="<?php echo ($browser=='mz' || $browser=='fb') ? 6 : 0 ;?>" frameborder="1" FRAMESPACING="<?php echo ($browser=='mz' || $browser=='fb') ? 1 : 6 ;?>" bordercolor="#4791C5">
			<frameset cols="170,*" name="menuFrame" border="<?php echo ($browser=='mz' || $browser=='fb') ? 6 : 0 ;?>" frameborder="1" FRAMESPACING="<?php echo ($browser=='mz' || $browser=='fb') ? 1 : 6 ;?>" bordercolor="#4791C5">
				<frame src="index.php?a=1&f=f3nbar" name="mainMenu" scrolling="NO" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
				<frame src="index.php?a=1&f=3" name="menu" FRAMEBORDER="no" BORDER="0" bordercolor="#4791C5" scrolling="AUTO">
			</frameset>
			<frameset rows="23,*" frameborder="0" border="0" framespacing="0">
				<frame src="index.php?a=1&f=ftbar" scrolling="no" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
				<frame src="index.php?a=2" name="main" scrolling="auto" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
			</frameset>
		</frameset>
	</frameset><noframes>This software requires a browser with support for frames.</noframes>
	</html>
<?php
}
else {
// IE Top Menu Layout
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
	<html>
	<head>
	<title><?php echo $site_name." - (MODx Content Manager)"; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
	</head>
	<frameset rows="0,21,24,*" frameborder="NO" border="0">
		<frame src="index.php?a=1&f=5" name="scripter" scrolling="NO" noresize>
		<frame src="index.php?a=1&f=8" name="mainMenu" scrolling="No" noresize>	
		<frame src="index.php?a=1&f=12" name="topFrame">
		<frameset cols="250,*" border="<?php echo ($browser=='mz' || $browser=='fb') ? 6 : 0 ;?>" frameborder="1" framespacing="<?php echo ($browser=='mz' || $browser=='fb') ? 1 : 6 ;?>" bordercolor="#4791C5">
			<frame src="index.php?a=1&f=3" name="menu" FRAMEBORDER="no" BORDER="0" bordercolor="#4791C5" scrolling="auto">
			<frameset rows="23,*" frameborder="0" border="0" framespacing="0">
				<frame src="index.php?a=1&f=ftbar" scrolling="no" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
				<frame src="index.php?a=2" name="main" scrolling="auto" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
			</frameset>
		</frameset>
	</frameset><noframes>This software requires a browser with support for frames.</noframes>
	</html>
<?php } ?>