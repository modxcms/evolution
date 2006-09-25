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
<body>

<div id="topbar"><table width="100%"  border="0" cellspacing="0" cellpadding="0" style="height:20px;">
  <tr>
    <td width="10">&nbsp;</td>
    <td valign="middle">
        <span id="tocText"> </span>
        <span id="buildText">&nbsp;&nbsp;<img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif' width='16' height='16' />&nbsp;<b><?php echo $_lang['loading_doc_tree']; ?></b></span>
        <span id="workText">&nbsp;<img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif' width='16' height='16' />&nbsp;<b><?php echo $_lang['working']; ?></b></span>
    </td>
    <td>&nbsp;</td>
    <td align='right' nowrap="nowrap">
        <b><?php echo $site_name ;?></b> - <b><?php echo $full_appname; ?> | <img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/user.gif' width='16' height='16'>
        <?php 
            echo ($modx->hasPermission('change_password'))? '<a onclick="this.blur();" href="index.php?a=28" target="main">'.$modx->getLoginUserName().'</a>': $modx->getLoginUserName();
            echo '</b>';
        ?>
        <?php if($modx->hasPermission('messages')) { ?>
            <b>|</b> <span id="newMail" style="display:none;font-size:11px;"><a href="index.php?a=10" title="<?php echo $_lang["you_got_mail"]; ?>" target="main"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/mailalert.gif" border="0" width='16' height='16' /></a></span>
            <a onclick="this.blur();" href="index.php?a=10" target="main"><?php echo $_lang["messages"]; ?> <span id="msgCounter">(? / ? )</span></a>
        <?php } ?> 
        <?php if($modx->hasPermission('help')) { ?>
            &nbsp;|&nbsp;<a href="index.php?a=9" target="main"><?php echo $_lang["help"]; ?></a>
        <?php } ?>
            &nbsp;|&nbsp;<a href="index.php?a=8" target="_top"><?php echo $_lang["logout"]; ?></a>
    </td>
    <td width="20">&nbsp;</td>
  </tr>
</table>
</div>

</body>
</html>
