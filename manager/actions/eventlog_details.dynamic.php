<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('view_eventlog')) {	
	$e->setError(3);
	$e->dumpError();	
}

function isNumber($var) {
	if(strlen($var)==0) {
		return false;
	}
	for ($i=0;$i<strlen($var);$i++) {
		if ( substr_count ("0123456789", substr ($var, $i, 1) ) == 0 ) {
			return false;
		}
    }
	return true;
}

// get id
if(isset($_REQUEST['id'])) {
	$id = intval($_REQUEST['id']);
}
else {
	$id=0;
}

// make sure the id's a number
if(!isNumber($id)) {
	echo "Passed ID is NaN!";
	exit;
}


$sql = "SELECT el.*, IFNULL(wu.username,mu.username) as 'username' " .
		"FROM ".$modx->getFullTableName("event_log")." el ".
		"LEFT JOIN ".$modx->getFullTableName("manager_users")." mu ON mu.id=el.user AND el.usertype=0 ".
		"LEFT JOIN ".$modx->getFullTableName("web_users")." wu ON wu.id=el.user AND el.usertype=1 ".
		" WHERE el.id=$id";			
$ds = mysql_query($sql);
if(!$ds) {
	echo "Error while load event log";
	exit;
}
else{
	$content = $modx->fetchRow($ds);	
}

?>

<div class="subTitle">
	<span class="right"><?php echo $_lang['eventlog']; ?></span>
	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<tr>
<?php if($modx->hasPermission('delete_eventlog')) { ?>
		<td id="Button3"><a href="#" onclick="deletelog();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle"> <?php echo $_lang['delete']; ?></a></td>
<?php } ?>
		<td id="Button4"><a href="index.php?a=114"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?></a></td>
		</tr>
	</table>
</div>
<script language="JavaScript" type="text/javascript">
	function deletelog() {
		if(confirm("<?php echo $_lang['confirm_delete_eventlog']; ?>")==true) {
			document.location.href="index.php?id=" + document.resource.id.value + "&a=116";
		}
	}
</script> 
<form name="resource" method="get">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="a" value="<?php echo $_REQUEST['a']; ?>" />
<input type="hidden" name="listmode" value="<?php echo $_REQUEST['listmode']; ?>" />
<input type="hidden" name="op" value="" />
<div class="sectionHeader"><?php echo $content['source']." - ".$_lang['eventlog_viewer']; ?></div><div class="sectionBody">
<?php
$date = strftime("%d-%b-%Y %I:%M %p",$content["createdon"]);
if($content["type"]==1) $msgtype = $_lang["information"];
else if($content["type"]==2) $msgtype = $_lang["warning"];
else if($content["type"]==3) $msgtype = $_lang["error"];
$useTheme = $manager_theme ? "$manager_theme/":"";
echo <<<HTML
	<table border="0" width="100%">
	  <tr><td colspan="4">
		<div class="warning"><img src="media/style/{$useTheme}images/icons/event{$content["type"]}.gif" align="absmiddle" /> {$msgtype}</div><br />
	  </td></tr>
	  <tr>
		<td width="25%" valign="top">{$_lang["event_id"]}:</td>
		<td width="25%" valign="top">{$content["eventid"]}</td>
		<td width="25%" valign="top">{$_lang["source"]}:</td>
		<td width="25%" valign="top">{$content["source"]}</td>
	  </tr>
	  <tr><td colspan="4"><div class='split'>&nbsp;</div></td></tr>
	  <tr>
		<td width="25%" valign="top" >{$_lang["date"]}:</td>
		<td width="25%" valign="top" >$date</td>
		<td width="25%" valign="top" >{$_lang["user"]}:</td>
		<td width="25%" valign="top" >{$content["username"]}</td>
	  </tr>
	  <tr><td colspan="4"><div class='split'>&nbsp;</div></td></tr>
	  <tr>
		<td width="100%" colspan="4"><br />
		{$content["description"]}
		</td>
	  </tr>
	</table>
HTML;
?>
</div>
</form>
