<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
include_once("browsercheck.inc.php");
$browser = $client->property('browser');
$_SESSION['browser']=$browser;
$version = $client->property('version');
$_SESSION['browser_version']=$version;


if (!isset($manager_layout) || $manager_layout==1 || ($manager_layout==0 && $browser!='ie')) {
// Basic layout - layout 1
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
	<html>
	<head>
	<title><?php echo $site_name." - (MODx Content Manager)"; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
	</head>
	<frameset rows="0,24,*" frameborder="no" border="0">
		<frame src="index.php?a=1&f=scripter" name="scripter" scrolling="no" noresize>
		<frame src="index.php?a=1&f=topbar" name="topFrame">
		<frameset cols="280,*" border="<?php echo !($browser=='ie') ? 6 : 0 ;?>" frameborder="yes" FRAMESPACING="<?php echo !($browser=='ie') ? 1 : 6 ;?>" bordercolor="#4791C5" resize>
			<frameset rows="200,*" name="menuFrame" border="<?php echo ($browser=='mz' || $browser=='fb') ? 6 : 0 ;?>" frameborder="1" FRAMESPACING="<?php echo ($browser=='mz' || $browser=='fb') ? 1 : 6 ;?>" bordercolor="#4791C5">
				<frame src="index.php?a=1&f=l1mnu" name="mainMenu" scrolling="no" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
				<frame src="index.php?a=1&f=3" name="menu" FRAMEBORDER="no" BORDER="0" bordercolor="#4791C5" scrolling="AUTO">
			</frameset>
            <frame src="index.php?a=2" name="main" scrolling="auto" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
		</frameset>
	</frameset>
	<noframes>This software requires a browser with support for frames.</noframes>
	</html>
<?php
}
else if ($manager_layout==2) {
// Advance Layout -- layout 2
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
	<html>
	<head>
	<title><?php echo $site_name." - (MODx Content Manager)"; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
	</head>
	<frameset rows="0,24,*" frameborder="no" border="0">
		<frame src="index.php?a=1&f=scripter" name="scripter" scrolling="no" noresize>
		<frame src="index.php?a=1&f=topbar" name="topFrame">
		<frameset cols="370,*" border="<?php echo ($browser=='mz' || $browser=='fb') ? 6 : 0 ;?>" frameborder="1" FRAMESPACING="<?php echo ($browser=='mz' || $browser=='fb') ? 1 : 6 ;?>" bordercolor="#4791C5">
			<frameset cols="170,*" name="menuFrame" border="<?php echo ($browser=='mz' || $browser=='fb') ? 6 : 0 ;?>" frameborder="1" FRAMESPACING="<?php echo ($browser=='mz' || $browser=='fb') ? 1 : 6 ;?>" bordercolor="#4791C5">
				<frame src="index.php?a=1&f=l2mnu" name="mainMenu" scrolling="no" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
				<frame src="index.php?a=1&f=3" name="menu" FRAMEBORDER="no" BORDER="0" bordercolor="#4791C5" scrolling="AUTO">
			</frameset>
			<frame src="index.php?a=2" name="main" scrolling="auto" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
		</frameset>
	</frameset>
	<noframes>This software requires a browser with support for frames.</noframes>
	</html>
<?php
}
else if ($manager_layout==3) {
// Collapsible top bar -- layout 3
?>
	<!-- Added by Zaigham -->
	<script type="text/javascript">

	/***********************************************
	* Collapsible Frames script- © Dynamic Drive (www.dynamicdrive.com)
	* This notice must stay intact for use
	* Visit http://www.dynamicdrive.com/ for full source code
	***********************************************/

	var columntype=""
	var defaultsetting=""

	function getCurrentSetting(){
		if (document.body)
			return (document.body.cols)? document.body.cols : document.body.rows
	}

	function setframevalue(coltype, settingvalue){
		if (coltype=="rows")
			document.body.rows=settingvalue
		else if (coltype=="cols")
			document.body.cols=settingvalue
	}

	function resizeFrame(contractsetting){
		if (getCurrentSetting()!=defaultsetting)
			setframevalue(columntype, defaultsetting)
		else
			setframevalue(columntype, contractsetting)
	}

	function init(){
		if (!document.all && !document.getElementById) return;
		if (document.body!=null){
			columntype=(document.body.cols)? "cols" : "rows"
			defaultsetting=(document.body.cols)? document.body.cols : document.body.rows
		}
		else
			setTimeout("init()",100)
	}

	setTimeout("init()",100)

	</script>

	<!-- Addition by Zaigham ends -->

	<head>
	<title><?php echo $site_name." - (MODx Content Manager)"; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
	</head>
	<frameset rows="0,20,150,*" frameborder="0" border="0">
		<frame src="index.php?a=1&f=scripter" name="scripter" scrolling="no" noresize>
		<frame src="index.php?a=1&f=topbar" name="topFrame" scrolling="no" noresize>
		<frame name="mainMenu" src="index.php?a=1&f=l3mnu" scrolling="no" noresize>
		<frameset cols="280,*" border="<?php echo !($browser=='ie') ? 6 : 0 ;?>" frameborder="yes" FRAMESPACING="<?php echo !($browser=='ie') ? 1 : 6 ;?>"  resize>
			<frame src="index.php?a=1&f=3" name="menu" FRAMEBORDER="no" BORDER="0" bordercolor="#4791C5" scrolling="AUTO">
			<frame src="index.php?a=2" name="main" scrolling="auto" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
		</frameset>
	</frameset>
	<noframes>This software requires a browser with support for frames.</noframes>
	</html>
<?php
}
else {
// IE Top Menu Layout - 0
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
	<html>
	<head>
	<title><?php echo $site_name." - (MODx Content Manager)"; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
	</head>
	<frameset rows="0,21,24,*" frameborder="no" border="0">
		<frame src="index.php?a=1&f=scripter" name="scripter" scrolling="no" noresize>
		<frame src="index.php?a=1&f=l0mnu" name="mainMenu" scrolling="no" noresize>	
		<frame src="index.php?a=1&f=topbar" name="topFrame">
		<frameset cols="250,*" border="<?php echo ($browser=='mz' || $browser=='fb') ? 6 : 0 ;?>" frameborder="1" framespacing="<?php echo ($browser=='mz' || $browser=='fb') ? 1 : 6 ;?>" bordercolor="#4791C5">
			<frame src="index.php?a=1&f=3" name="menu" FRAMEBORDER="no" BORDER="0" bordercolor="#4791C5" scrolling="auto">
			<frame src="index.php?a=2" name="main" scrolling="auto" FRAMEBORDER="no" BORDER="0" BORDERCOLOR="#4791C5">
		</frameset>
	</frameset>
	<noframes>This software requires a browser with support for frames.</noframes>
	</html>
<?php } ?>