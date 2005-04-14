<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>
<div class="subTitle">
<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['cleaningup']; ?></span>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['cleaningup']; ?></div><div class="sectionBody">
<p><?php echo $_lang['actioncomplete']; ?></p>
<?php if($_REQUEST['r']==10) { ?>
<script language="JavaScript">
function goHome() {
	top.location.reload();
}
x=window.setTimeout('goHome()',2000);
</script>
<?php } elseif($_REQUEST['dv']==1 && $_REQUEST['id']!='') { ?>
<script language="JavaScript">
function goHome() {
	document.location.href="index.php?a=3&id=<?php echo $_REQUEST['id']; ?>";
}
x=window.setTimeout('goHome()',2000);
</script>
<?php } else { ?>
<script language="JavaScript">
function goHome() {
	document.location.href="index.php?a=2";
}
x=window.setTimeout('goHome()',2000);
</script>
<?php } ?>
</div>