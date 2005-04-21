<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>

<div class="subTitle">
<ul id="navlist">
	<li><span class="etomiteButton" onClick="document.location.href='index.php?a=2';"><img src="media/images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?></span></li>
</ul>

<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['personal_prefs_title']; ?></span>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['personal_prefs_title']; ?></div><div class="sectionBody">
<?php echo $_lang['personal_prefs_message']; ?>
</div>