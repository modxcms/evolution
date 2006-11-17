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
<script src="media/script/mootools/mooPrompt.js" type="text/javascript"></script>
<script type="text/javascript">
Window.onDomReady(function() {
			var sysAlert = new Element('div').setProperties({
				'class': 'sysAlert'
			});
			sysAlert.innerHTML = '<?php echo mysql_escape_string($sysMsgs);?>';
			var boxHtml = new MooPrompt('<?php echo $_lang['sys_alert']; ?>', sysAlert, {
				buttons: 1,
				button1: 'Ok',
				width: 500
			});
});
</script>

<?php
	}
?>