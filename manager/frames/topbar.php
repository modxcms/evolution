<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

	$enable_debug=false;

	// close the session as it is not used here
	// this should speed up frame loading, does it?
	session_write_close();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Top bar</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_charset; ?>" />
    <link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css" />
    <script type="text/javascript">var MODX_MEDIA_PATH = "<?php echo IN_MANAGER_MODE ? "media":"manager/media"; ?>";</script>
</head>
<body id="topbar">

<div id="topbar-container">
    <span id="tocText"> </span>
    <span id="buildText"><img src="<?php echo $_style['icons_loading_doc_tree']; ?>" /><?php echo $_lang['loading_doc_tree']; ?></span>
    <span id="workText"><img src="<?php echo $_style['icons_working']; ?>" /><?php echo $_lang['working']; ?></span>

    <div id="supplementalNav">
        <?php echo $site_name ;?> - <?php echo $full_appname; ?> | <img src="<?php echo $_style['icons_user_current']; ?>" />
        <?php 
            echo ($modx->hasPermission('change_password'))? '<a onclick="this.blur();" href="index.php?a=28" target="main">'.$modx->getLoginUserName().'</a>': $modx->getLoginUserName();
        ?>
        <?php if($modx->hasPermission('messages')) { ?>
            | <span id="newMail"><a href="index.php?a=10" title="<?php echo $_lang["you_got_mail"]; ?>" target="main"><img src="<?php echo $_style['icons_mail']; ?>" /></a></span>
            <a onclick="this.blur();" href="index.php?a=10" target="main"><?php echo $_lang["messages"]; ?> <span id="msgCounter">( ? / ? )</span></a>
        <?php } ?> 
        <?php if($modx->hasPermission('help')) { ?>
            &nbsp;|&nbsp;<a href="index.php?a=9" target="main"><?php echo $_lang["help"]; ?></a>
        <?php } ?>
            &nbsp;|&nbsp;<a href="index.php?a=8" target="_top"><?php echo $_lang["logout"]; ?></a>
    </div>
    <!-- close #supplementalNav -->
</div>

</body>
</html>