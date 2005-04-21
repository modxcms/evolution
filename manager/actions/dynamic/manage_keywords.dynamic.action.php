<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if($_SESSION['permissions']['new_document']!=1) {	
	$e->setError(3);
	$e->dumpError();	
}
?>
<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['keywords'] ;?></span>
</div>


<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['keywords'] ;?></div><div class="sectionBody">
<?php echo $_lang['keywords_intro'] ;?><p />
<?php
$sql = "SELECT * FROM $dbase.".$table_prefix."site_keywords ORDER BY keyword ASC";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit < 1) {
	echo $_lang['keywords_no_keywords'];
?>
	<form name="keywordsFrm" method="post" action="index.php" onsubmit="return checkForm();">
	<input type="hidden" name="a" value="82" />
<table border=0 cellpadding=2 cellspacing=0> 
		<tr>
			<td colspan="3" align="right">
				<i><?php echo $_lang['new_keyword']; ?></i>
			</td>
			<td>&nbsp;</td>
			<td>
				<input type="text" name="new_keyword" value="" />
			</td>
		</tr>
	</table>	
<br />
<input type=submit value="<?php echo $_lang['save_all_changes']; ?>" onsubmit="return checkForm();" />
</form>

<?php
} else {
	?>
	<script type="text/javascript">
	function checkForm() {
	var requireConfirm=false;
	var deleteList="";
	<?php for($i=0;$i<$limit;$i++) { 
		$row=mysql_fetch_assoc($rs);
		?>
		if(document.getElementById('delete<?php echo $row['id']; ?>').checked==true) {
			requireConfirm = true;
			deleteList = deleteList + "\n - <?php echo addslashes($row['keyword']); ?>";
			
		}
	<?php }	?>
		if(requireConfirm) {
			var agree=confirm("<?php echo $_lang['confirm_delete_keywords']; ?>\n" + deleteList);
			if(agree) {
				return true;
			} else {
				return false;
			}
		}
		return true;
	}
	
	</script>

	<form name="keywordsFrm" method="post" action="index.php" onsubmit="return checkForm();">
	<input type="hidden" name="a" value="82" />
<table border=0 cellpadding=2 cellspacing=0> 
		<tr class="fancyRow">
			<td>
				<b><?php echo $_lang['delete']; ?></b>
			</td>
			<td>&nbsp;</td>
			<td>
				<b><?php echo $_lang['keyword']; ?></b>
			</td>
			<td>&nbsp;</td>
			<td>
				<b><?php echo $_lang['rename']; ?></b>
			</td>
		</tr>
	<?php
	mysql_data_seek($rs, 0);
	for($i=0;$i<$limit;$i++) {
		$row = mysql_fetch_assoc($rs);
		?>
		<tr>
			<td>
				<input type="checkbox" name="delete_keywords[<?php echo $row['id']; ?>]" id="delete<?php echo $row['id']; ?>">
			</td>
			<td>&nbsp;</td>
			<td>
				<a onclick="if(document.getElementById('delete<?php echo $row['id']; ?>').checked==true) { document.getElementById('delete<?php echo $row['id']; ?>').checked=false; } else { document.getElementById('delete<?php echo $row['id']; ?>').checked=true; }; return false;" style="cursor:pointer"><b><?php echo $row['keyword']; ?></b></a>
			</td>
			<td>&nbsp;</td>
			<td>
				<input type="hidden" name="orig_keywords[keyword<?php echo $row['id']; ?>]" value="<?php echo $row['keyword']; ?>" /><input type="text" name="rename_keywords[keyword<?php echo $row['id']; ?>]" value="<?php echo $row['keyword']; ?>" />
			</td>
		</tr>
		<?php
	}
	?>
		<tr><td colspan="5">&nbsp;</td></tr>
		<tr>
			<td colspan="3" align="right">
				<i><?php echo $_lang['new_keyword']; ?></i>
			</td>
			<td>&nbsp;</td>
			<td>
				<input type="text" name="new_keyword" value="" />
			</td>
		</tr>
	</table>	
<br />
<input type=submit value="<?php echo $_lang['save_all_changes']; ?>" onsubmit="return checkForm();" />
</form>
	<?php
}
?>
</div>
	