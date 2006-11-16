<?php

	/**
	 *	System Alert Message Queque Display file
	 *	Written By Raymond Irving, April, 2005
	 *
	 *	Used to display system alert messages inside the browser 
	 *
	 */
	
	$sysMsgs = "";
	$limit = count($SystemAlertMsgQueque);
	for($i=0;$i<$limit;$i++) {
		$sysMsgs .= $SystemAlertMsgQueque[$i]."<hr>";
	}
	// reset message queque
	unset($_SESSION['SystemAlertMsgQueque']);
	$_SESSION['SystemAlertMsgQueque'] = array();
	$SystemAlertMsgQueque = &$_SESSION['SystemAlertMsgQueque'];

	if($sysMsgs!="") {
?>	

<?php // fetch the styles
if (file_exists($modx->config['base_path'].'manager/media/style/'.$manager_theme.'/sysalert_style.php')) {
	include_once ($modx->config['base_path'].'manager/media/style/'.$manager_theme.'/sysalert_style.php');
	echo '<style type="text/css">';
	echo $sysalert_style;
	echo '</style>';
} 
?>

<div id="sysAlertWrapper">
	<div id="sysAlertWindow">
		<div class="evtMsgHeading">
			<div id="closeSysAlert"><a href="#" onclick="closeSystemAlerts();return false;"><img border="0" src="media/style/<?php echo ($manager_theme ? "$manager_theme/":"") ?>images/icons/close.gif" width="16" height="16" alt="<?php echo $_lang['close'] ?>" /></a></div> 
			<?php echo $_lang['sys_alert'] ?> 
		</div>
		<div class="evtMsg"><?php echo $sysMsgs;?></div>
	</div>
</div>
	<script type="text/javascript">
	function closeSystemAlerts() {
		$("sysAlertWrapper").style.display = "none";
	};
	</script>

<?php
	}
?>