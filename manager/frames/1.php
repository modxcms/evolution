<?php
if (IN_MANAGER_MODE != "true")
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
include_once ("browsercheck.inc.php");
$browser = $client->property('browser');
$_SESSION['browser'] = $browser;
$version = $client->property('version');
$_SESSION['browser_version'] = $version;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html <?php echo $modx->config['manager_direction'] == 'rtl' ? 'dir="rtl"' : '';?> lang="<?php echo $modx->config['manager_lang_attribute'];?>" xml:lang="<?php echo $modx->config['manager_lang_attribute'];?>">
<head>
    <title><?php echo $site_name." - (MODx CMS Manager)"; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_charset; ?>" />
    <script type="text/javascript" src="media/script/session.js"></script>
</head>
<frameset rows="70,*" border="0">
    <frame name="mainMenu" src="index.php?a=1&amp;f=menu" scrolling="no" frameborder="0" noresize="noresize">
		<?php
		if ($modx->config['manager_direction'] == 'ltr') {
		?>
		<frameset cols="260,*" border="3" frameborder="3" framespacing="3" bordercolor="#ffffff">
		<frame name="tree" src="index.php?a=1&amp;f=tree"  scrolling="no" frameborder="0" onresize="top.tree.resizeTree();">
        <frame name="main" src="index.php?a=2"  scrolling="auto" frameborder="0" onload="if (top.mainMenu.stopWork()) top.mainMenu.stopWork();">
    	<?php } else { ?>
    	<frameset cols="*,260" border="3" frameborder="3" framespacing="3" bordercolor="#ffffff">
        <frame name="main" src="index.php?a=2"  scrolling="auto" frameborder="0" onload="if (top.mainMenu.stopWork()) top.mainMenu.stopWork();">
        <frame name="tree" src="index.php?a=1&amp;f=tree"  scrolling="no" frameborder="0" onresize="top.tree.resizeTree();">
    	<?php } ?>
    </frameset>
</frameset>
<noframes>This software requires a browser with support for frames.</noframes>
</html>
