<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>
<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang["visitor_online"]; ?></span>
</div>
<?php
	$track_period = time()-(20*60)+$server_offset_time;
?>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang["visitor_online"]; ?></div><div class="sectionBody">
<?php printf($_lang["visitor_online_message"], strftime("%H:%M:%S", $track_period)); ?>
<p />

<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
		<tr>
			<td class='row3'>&nbsp;</td>
			<td class='row3'><b><?php echo $_lang['document']; ?></b></td>
			<td class='row3'><b><?php echo $_lang['hostname']; ?></b></td>
			<td class='row3'><b><?php echo $_lang['ua']; ?></b></td>
			<td class='row3'><b><?php echo $_lang['os']; ?></b></td>
		</tr>
<?php
	// get page titles
	$sql = "SELECT id, pagetitle FROM $dbase.".$table_prefix."site_content";
	$rs = mysql_query($sql);
	$pagetitles = array();
	while ($row = mysql_fetch_row($rs)) {
		$pagetitles[$row[0]] = $row[1];
	}

	$sql = "SELECT DISTINCT(visitor) AS visitor, MAX(timestamp) AS lasthit FROM $dbase.".$table_prefix."log_access WHERE timestamp > $track_period GROUP BY visitor ORDER BY lasthit DESC";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	for($i=0; $i<$limit; $i++) {
		$tmp = mysql_fetch_assoc($rs);
		$visitor = $tmp['visitor'];
		
		$sql = "SELECT document, referer, timestamp AS lasthit FROM $dbase.".$table_prefix."log_access WHERE timestamp > $track_period AND visitor=$visitor ORDER BY timestamp DESC LIMIT 1";
		$rs2 = mysql_query($sql);
		$tmp2 = mysql_fetch_assoc($rs2);
		$document = $tmp2['document'];
		$lasthit = $tmp2['lasthit'];
		$referer = $tmp2['referer'];

		$sql = "SELECT t1.data AS ua FROM $dbase.".$table_prefix."log_user_agents AS t1, $dbase.".$table_prefix."log_visitors AS t2 WHERE t2.id=$visitor AND t1.id=t2.ua_id";
		$rs2 = mysql_query($sql);
		$tmp2 = mysql_fetch_assoc($rs2);
		$ua = $tmp2['ua'];

		$sql = "SELECT t1.data AS os FROM $dbase.".$table_prefix."log_operating_systems AS t1, $dbase.".$table_prefix."log_visitors AS t2 WHERE t2.id=$visitor AND t1.id=t2.os_id";
		$rs2 = mysql_query($sql);
		$tmp2 = mysql_fetch_assoc($rs2);
		$os = $tmp2['os'];		

		$sql = "SELECT t1.data AS hostname FROM $dbase.".$table_prefix."log_hosts AS t1, $dbase.".$table_prefix."log_visitors AS t2 WHERE t2.id=$visitor AND t1.id=t2.host_id";
		$rs2 = mysql_query($sql);
		$tmp2 = mysql_fetch_assoc($rs2);
		$host = $tmp2['hostname'];		

		$sql = "SELECT data AS referer FROM $dbase.".$table_prefix."log_referers WHERE id=$referer";
		$rs2 = mysql_query($sql);
		$tmp2 = mysql_fetch_assoc($rs2);
		$referer = $tmp2['referer'];	
		$refererString = $referer!='Internal' && $referer!='Unknown' ? "<span style='font-size:9px;'>".$_lang['referrer']." "."<a href='$referer' target='_blank' style='font-size:9px;'>$referer</a></span>" : "-";
?>
		<tr>
			<td class='row3' rowspan="2"><b><?php echo strftime('%H:%M:%S', $lasthit) ?></b></td>
			<td class='row1'><a href="index.php?a=3&id=<?php echo $document; ?>"><?php echo $pagetitles[$document]; ?><a/></td>
			<td class='row1'><?php echo $host; ?></td>
			<td class='row1'><?php echo $ua; ?></td>
			<td class='row1'><?php echo $os; ?></td>
		</tr>
		<tr>
			<td class='row1' colspan="4" align="right"><?php echo $refererString; ?></td>
		</tr>
<?php
	}
	if($limit==0) {
?>
		<tr>
			<td class='row1' colspan="5"><?php echo $_lang['no_online_users']; ?></td>
		</tr>
<?php
	}
?>
</table>

</div>
