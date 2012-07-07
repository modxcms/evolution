<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

?>
<h1><?php echo $_lang['cleaningup']; ?></h1>

<div class="sectionBody">

<p><?php echo $_lang['actioncomplete']; ?></p>
<script type="text/javascript">
function goHome() {
<?php if($_REQUEST['r']==10) {?>
	top.location.startrefresh(10);
<?php } elseif($_REQUEST['dv']==1 && $_REQUEST['id']!='') { ?>
	document.location.href="index.php?a=3&id=<?php echo $_REQUEST['id']; ?>";
<?php } else { ?>
	document.location.href="index.php?a=2";
<?php } ?>
}
x=window.setTimeout('goHome()',2000);
</script>
</div>
