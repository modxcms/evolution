<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if($_SESSION['permissions']['edit_document']!=1) {	
	$e->setError(3);
	$e->dumpError();	
}

// figure out the base of the server, so we know where to get the documents in order to export them
$base = 'http://'.$_SERVER['SERVER_NAME'].str_replace("/manager/index.php", "", $_SERVER["PHP_SELF"]);


?>

<script>
function reloadTree() {
	// redirect to welcome
	document.location.href = "index.php?r=1&a=7";
}
</script>

<div class="subTitle">
<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['export_site']; ?></span>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['export_site']; ?></div><div class="sectionBody">
<?php 

if(!isset($_POST['export'])) {
echo $_lang['export_site_message']; 
?>
<fieldset style="padding:10px"><legend><?php echo $_lang['export_site']; ?></legend>
<form action="index.php" method="post" name="exportFrm">
<input type="hidden" name="export" value="export" />
<input type="hidden" name="a" value="83" />
<table border="0" cellspacing="0" cellpadding="2" width="400">
  <tr>
    <td valign="top"><b><?php echo $_lang['export_site_cacheable']; ?></b></td>
    <td width="30">&nbsp;</td>
    <td><input type="radio" name="includenoncache" value="1" checked="checked"><?php echo $_lang['yes'];?><br />
		<input type="radio" name="includenoncache" value="0"><?php echo $_lang['no'];?></td>
  </tr>
  <tr>
    <td><b><?php echo $_lang['export_site_prefix']; ?></b></td>
    <td>&nbsp;</td>
    <td><input type="text" name="prefix" value="<?php echo $friendly_url_prefix; ?>" /></td>
  </tr>
  <tr>
    <td><b><?php echo $_lang['export_site_suffix']; ?></b></td>
    <td>&nbsp;</td>
    <td><input type="text" name="suffix" value="<?php echo $friendly_url_suffix; ?>" /></td>
  </tr>
  <tr>
    <td valign="top"><b><?php echo $_lang['export_site_maxtime']; ?></b></td>
    <td>&nbsp;</td>
    <td><input type="text" name="maxtime" value="30" />
		<br />
		<small><?php echo $_lang['export_site_maxtime_message']; ?></small>
	</td>
  </tr>
</table>
<p />
<table cellpadding="0" cellspacing="0">
	<td id="Button1" onclick="document.exportFrm.submit();"><img src="media/images/icons/save.gif" align="absmiddle"> <?php echo $_lang["export_site_start"]; ?></td>
		<script>createButton(document.getElementById("Button1"));</script>
</table>
</form>
</fieldset>

<?php
} else {

	$maxtime = $_POST['maxtime'];
	if(!is_numeric($maxtime)) {
		$maxtime = 30;
	}
	
	set_time_limit($maxtime);
	$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $exportstart = $mtime; 
	
	$filepath = "../assets/export/";
	if(!is_writable($filepath)) {
		echo $_lang['export_site_target_unwritable'];
		include "footer.inc.php";
		exit;
	}
	
	$prefix = $_POST['prefix'];
	$suffix = $_POST['suffix'];

	$noncache = $_POST['includenoncache']==1 ? "" : "AND $dbase.".$table_prefix."site_content.cacheable=1";
	
	$sql = "SELECT id, alias, pagetitle FROM $dbase.".$table_prefix."site_content WHERE $dbase.".$table_prefix."site_content.deleted=0 AND $dbase.".$table_prefix."site_content.published=1 AND $dbase.".$table_prefix."site_content.type='document' $noncache";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	printf($_lang['export_site_numberdocs'], $limit);
	
	for($i=0; $i<$limit; $i++) {
		
		$row=mysql_fetch_assoc($rs);
		
		$id = $row['id'];
		printf($_lang['export_site_exporting_document'], $i, $limit, $row['pagetitle'], $id);
		$alias = $row['alias'];
		
		$filename = !empty($alias) ? $prefix.$alias.$suffix : $prefix.$id.$suffix ;
		
		// get the file
		if(@$handle = fopen("$base/index.php?id=$id", "r")) {
			$buffer = "";
			while (!feof ($handle)) {
			   $buffer .= fgets($handle, 4096);
			}
			fclose ($handle);
		
			// save it
			$filename = "$filepath$filename";
			$somecontent = $buffer;
			
			if(!$handle = fopen($filename, 'w')) {
				 echo $_lang['export_site_failed']." Cannot open file ($filename)<br />";
				 exit;
			} else {
				// Write $somecontent to our opened file.
				if(fwrite($handle, $somecontent) === FALSE) {
				   echo $_lang['export_site_failed']." Cannot write file.<br />";
				   exit;
				}
				fclose($handle);
			echo $_lang['export_site_success']."<br />";	
			}
		} else {
			echo $_lang['export_site_failed']." Could not retrieve document.<br />";
		}
	}

	$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $exportend = $mtime; 
	$totaltime = ($exportend - $exportstart);
	printf ("<p />".$_lang['export_site_time'], round($totaltime, 3));
?>
<p />
<table cellpadding="0" cellspacing="0">
	<td id="Button2" onclick="reloadTree();"><img src="media/images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang["close"]; ?></td>
		<script>createButton(document.getElementById("Button2"));</script>
</table>
<?php
}
?>