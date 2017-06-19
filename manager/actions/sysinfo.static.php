<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('logs')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$res = $modx->db->query("show variables like 'character_set_database'");
$charset = $modx->db->getRow($res, 'num');
$res = $modx->db->query("show variables like 'collation_database'");
$collation = $modx->db->getRow($res, 'num');

$serverArr = array(
	$_lang['modx_version']       => $modx->getVersionData('version'). ' '.$newversiontext,
	$_lang['release_date']       => $modx->getVersionData('release_date'),
	'PHP Version'                => phpversion(),
	'phpInfo()'            		 => '<a href="#" onclick="viewPHPInfo();return false;">'.$_lang['view'].'</a>',
	$_lang['access_permissions'] =>  ($use_udperms==1 ? $_lang['enabled'] : $_lang['disabled']),
	$_lang['servertime']		 => strftime('%H:%M:%S', time()),
	$_lang['localtime']          => strftime('%H:%M:%S', time()+$server_offset_time),
	$_lang['serveroffset']	     => $server_offset_time/(60*60) . ' h',
	$_lang['database_name']      => trim($dbase,'`'),
	$_lang['database_server']    => $database_server,
	$_lang['database_version']   => $modx->db->getVersion(),
	$_lang['database_charset']   => $charset[1],
	$_lang['database_collation'] => $collation[1],
	$_lang['table_prefix']       => $modx->db->config['table_prefix'],
	$_lang['cfg_base_path']      => MODX_BASE_PATH,
	$_lang['cfg_base_url']       => MODX_BASE_URL,
	$_lang['cfg_manager_url']    => MODX_MANAGER_URL,
	$_lang['cfg_manager_path']   => MODX_MANAGER_PATH,
	$_lang['cfg_site_url']       => MODX_SITE_URL
);
?>
<h1 class="pagetitle">
  <span class="pagetitle-icon">
    <i class="fa fa-info-circle"></i>
  </span>
  <span class="pagetitle-text">
    <?php echo $_lang['view_sysinfo']; ?>
  </span>
</h1>

<script type="text/javascript">
	function viewPHPInfo() {
		dontShowWorker = true; // prevent worker from being displayed
		window.location.href="index.php?a=200";
	};
</script>

<!-- server -->
<div class="section">
<div class="sectionHeader">Server</div><div class="sectionBody" id="lyr2">
		<table border="0" cellspacing="2" cellpadding="2">
		<?php foreach ($serverArr as $key => $value){
			echo '<tr>
					<td>'.$key.'</td>
					<td>&nbsp;</td>
					<td><b>'.$value.'</b></td>
				  </tr>';
		}	
		?>	 
		</table>
   </div>
</div>

<!-- database -->
<div class="section">
<div class="sectionHeader"><?php echo $_lang['database_tables']; ?></div><div class="sectionBody" id="lyr4">
		<p><?php echo $_lang['table_hoverinfo']; ?></p>
		<table border="0" cellpadding="1" cellspacing="1" width="100%" bgcolor="#ccc">
		 <thead>
		 <tr>
			<td width="160"><b><?php echo $_lang["database_table_tablename"]; ?></b></td>
			<td width="40" align="right"><b><?php echo $_lang["database_table_records"]; ?></b></td>
			<td width="120" align="right"><b><?php echo $_lang["database_table_datasize"]; ?></b></td>
			<td width="120" align="right"><b><?php echo $_lang["database_table_overhead"]; ?></b></td>
			<td width="120" align="right"><b><?php echo $_lang["database_table_effectivesize"]; ?></b></td>
			<td width="120" align="right"><b><?php echo $_lang["database_table_indexsize"]; ?></b></td>
			<td width="120" align="right"><b><?php echo $_lang["database_table_totalsize"]; ?></b></td>
		  </tr>
		  </thead>
		  <tbody>
<?php

	$sql = "SHOW TABLE STATUS FROM $dbase LIKE '".$modx->db->escape($modx->db->config['table_prefix'])."%';";
	$rs = $modx->db->query($sql);
	$i = 0;
	while ($log_status = $modx->db->getRow($rs)) {
		$bgcolor = ($i++ % 2) ? '#EEEEEE' : '#FFFFFF';
?>
		  <tr bgcolor="<?php echo $bgcolor; ?>" title="<?php echo $log_status['Comment']; ?>" style="cursor:default">
			<td><b style="color:#009933"><?php echo $log_status['Name']; ?></b></td>
			<td align="right"><?php echo $log_status['Rows']; ?></td>

<?php
	$truncateable = array(
		$modx->db->config['table_prefix'].'event_log',
		$modx->db->config['table_prefix'].'manager_log',
	);
	if($modx->hasPermission('settings') && in_array($log_status['Name'], $truncateable)) {
		echo "<td dir='ltr' align='right'>";
		echo "<a href='index.php?a=54&mode=$action&u=".$log_status['Name']."' title='".$_lang['truncate_table']."'>".$modx->nicesize($log_status['Data_length']+$log_status['Data_free'])."</a>";
		echo "</td>";
	}
	else {
		echo "<td dir='ltr' align='right'>".$modx->nicesize($log_status['Data_length']+$log_status['Data_free'])."</td>";
	}

	if($modx->hasPermission('settings')) {
		echo  "<td align='right'>".($log_status['Data_free']>0 ? "<a href='index.php?a=54&mode=$action&t=".$log_status['Name']."' title='".$_lang['optimize_table']."' ><span dir='ltr'>".$modx->nicesize($log_status['Data_free'])."</span></a>" : "-")."</td>";
	}
	else {
		echo  "<td dir='ltr' align='right'>".($log_status['Data_free']>0 ? $modx->nicesize($log_status['Data_free']) : "-")."</td>";
	}
?>
			<td dir='ltr' align="right"><?php echo $modx->nicesize($log_status['Data_length']-$log_status['Data_free']); ?></td>
			<td dir='ltr' align="right"><?php echo $modx->nicesize($log_status['Index_length']); ?></td>
			<td dir='ltr' align="right"><?php echo $modx->nicesize($log_status['Index_length']+$log_status['Data_length']+$log_status['Data_free']); ?></td>
		  </tr>
<?php
		$total = $total+$log_status['Index_length']+$log_status['Data_length'];
		$totaloverhead = $totaloverhead+$log_status['Data_free'];
	}
?>
		  <tr bgcolor="#CCCCCC">
			<td valign="top"><b><?php echo $_lang['database_table_totals']; ?></b></td>
			<td colspan="2">&nbsp;</td>
			<td dir='ltr' align="right" valign="top"><?php echo $totaloverhead>0 ? "<b style='color:#990033'>".$modx->nicesize($totaloverhead)."</b><br />(".number_format($totaloverhead)." B)" : "-"; ?></td>
			<td colspan="2">&nbsp;</td>
			<td dir='ltr' align="right" valign="top"><?php echo "<b>".$modx->nicesize($total)."</b><br />(".number_format($total)." B)"; ?></td>
		  </tr>
		  </tbody>
		</table>
<?php
	if($totaloverhead>0) { ?>
		<p><?php echo $_lang['database_overhead']; ?></p>
		<?php } ?>
</div></div>
