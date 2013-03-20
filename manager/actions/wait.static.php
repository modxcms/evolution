<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>

<h1><?php echo $_lang['cleaningup']; ?></h1>

<div class="sectionBody">

<p><?php echo $_lang['actioncomplete']; ?></p>
<script type="text/javascript">
function goHome() {
<?php if($_REQUEST['r']==10) {?>
	top.mainMenu.startrefresh(10);
<?php } elseif($_REQUEST['dv']==1 && $_REQUEST['id']!='') { ?>
	document.location.href="index.php?a=3&id=<?php echo $_REQUEST['id']; ?>";
<?php } else { ?>
	document.location.href="index.php?a=2";
<?php } ?>
}
x=window.setTimeout('goHome()',2000);
</script>
</div>
