<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('web_access_permissions')) {
	$e->setError(3);
	$e->dumpError();
}


// find all document groups, for the select :)
$sql = "SELECT * FROM $dbase.`".$table_prefix."documentgroup_names`";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit<1) {
	$docgroupselector = "[no groups to add]";
} else {
	$docgroupselector = '<select name="docgroup">';
	for($i=0; $i<$limit; $i++) {
		$row = mysql_fetch_assoc($rs);
		$docgroupselector .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
	}
	$docgroupselector .= '</select>';
}

?>
<br />
<div class="sectionHeader"><?php echo $_lang['web_access_permissions'] ;?></div><div class="sectionBody">
<?php echo $_lang['access_permissions_introtext'];?> <?php echo $use_udperms!=1 ? "<p />".$_lang['access_permissions_off'] : "" ;?><p />

<link type="text/css" rel="stylesheet" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css<?php echo "?$theme_refresher";?>" />
<script type="text/javascript" src="media/script/tabpane.js"></script>
<div class="tab-pane" id="tabPane1">
  <script type="text/javascript">
	tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
</script>

  <div class="tab-page" id="tabPage1">
	<h2 class="tab"><?php echo $_lang['web_access_permissions_user_groups'] ?></h2>
	<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage1" ) );</script>
	<?php echo $_lang['access_permissions_users_tab']; ?> <p />
	<table width="300" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
		<thead>
		<tr>
			<td>
				<b><?php echo $_lang['access_permissions_add_user_group'] ?></b>
			</td>
		</tr>
		</thead>
		<tr class="row1">
			<td>
			<form method="post" action="index.php" name="accesspermissions" style="margin: 0px;">
				<input type="hidden" name="a" value="92" />
				<input type="hidden" name="operation" value="add_user_group" />
				<input type="text" value="" name="newusergroup" />&nbsp;
				<input type="submit" value="<?php echo $_lang['submit']; ?>">
			</form>
			</td>
		</tr>
	</table>
<br />
	<table width="600" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
		<thead>
		<tr>
			<td colspan="3">
				<b><?php echo $_lang['web_access_permissions_user_groups'] ?></b>
			</td>
		</tr>
		</thead>
<?php
		$sql = "SELECT * FROM $dbase.`".$table_prefix."webgroup_names`";
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		if($limit<1) {
			echo '<tr><td class="row1"><span class="warning">'.$_lang['no_groups_found'].'</span></td></tr>';
		} else {
			for($i=0; $i<$limit; $i++) {
			$row = mysql_fetch_assoc($rs);
			?>
			<tr class="row3">
				<td width="350">
					<?php echo $row['name'] ;?>
				</td>
				<td align="right" width="50">
					<form method="post" action="index.php" name="accesspermissions" style="margin: 0px;">
						<input type="hidden" name="a" value="92" />
						<input type="hidden" name="usergroup" value="<?php echo $row['id'];?>" />
						<input type="hidden" name="operation" value="delete_user_group" />
						<input type="submit" value="<?php echo $_lang['delete']; ?>">
					</form>
				</td>
				<td align="right" width="200">
					<form method="post" action="index.php" name="accesspermissions" style="margin: 0px;">
						<input type="hidden" name="a" value="92" />
						<input type="hidden" name="groupid" value="<?php echo $row['id'];?>" />
						<input type="hidden" name="operation" value="rename_user_group" />
						<input type="text" name="newgroupname" value="<?php echo $row['name'] ;?>">&nbsp;
						<input type="submit" value="<?php echo $_lang['rename']; ?>">
					</form>
				</td>
			</tr>
			<tr>
				<td class="row2" colspan="3">&nbsp;&raquo;&nbsp;
					<span style="font-size: 9px">
					<?php echo $_lang['access_permissions_users_in_group']; ?>
					<?php
					$sql = "SELECT tbl1.username AS user, tbl1.id AS internalKey FROM
					$dbase.`".$table_prefix."web_users` AS tbl1,
					$dbase.`".$table_prefix."web_groups` AS tbl2
					WHERE tbl2.webgroup=".$row['id']." AND
					tbl1.id = tbl2.webuser
					ORDER BY tbl1.username ASC";
					$rs2 = mysql_query($sql);
					$limit2 = mysql_num_rows($rs2);
					if($limit2<1) {
						echo $_lang['access_permissions_no_users_in_group'];
					} else {
						$users = array();
						for($y=0; $y<$limit2; $y++) {
							$row2 = mysql_fetch_assoc($rs2);
							$users[] = '<a href="index.php?id='.$row2['internalKey'].'&a=88" style="font-size: 9px">'.$row2['user'].'</a>';
						}
						echo "\n\t".join($users, ",\n\t");
					}
					?>
					</span>
				</td>
			</tr>
<?php
			}
		}
?>
	</table>


</div>


  <div class="tab-page" id="tabPage2">
	<h2 class="tab"><?php echo $_lang['access_permissions_document_groups'] ?></h2>
	<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage2" ) );</script>
	<?php echo $_lang['access_permissions_documents_tab']; ?> <p />
	<table width="300" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
		<thead>
		<tr>
			<td>
				<b><?php echo $_lang['access_permissions_add_document_group'] ?></b>
			</td>
		</tr>
		</thead>
		<tr class="row1">
			<td>
			<form method="post" action="index.php" name="accesspermissions" style="margin: 0px;">
				<input type="hidden" name="a" value="92" />
				<input type="hidden" name="operation" value="add_document_group" />
				<input type="text" value="" name="newdocgroup" />&nbsp;
				<input type="submit" value="<?php echo $_lang['submit']; ?>">
			</form>
			</td>
		</tr>
	</table>
<br />
	<table width="600" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
		<thead>
		<tr>
			<td colspan="3">
				<b><?php echo $_lang['access_permissions_document_groups'] ?></b>
			</td>
		</tr>
		</thead>
<?php
		$sql = "SELECT * FROM $dbase.`".$table_prefix."documentgroup_names` ORDER BY name";
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		if($limit<1) {
			echo '<tr><td class="row1"><span class="warning">'.$_lang['no_groups_found'].'</span></td></tr>';
		} else {
			for($i=0; $i<$limit; $i++) {
			$row = mysql_fetch_assoc($rs);
			?>
			<tr class="row3">
				<td width="350">
					<?php echo $row['name'] ;?>
				</td>
				<td align="right" width="50">
					<form method="post" action="index.php" name="accesspermissions" style="margin: 0px;">
						<input type="hidden" name="a" value="92" />
						<input type="hidden" name="documentgroup" value="<?php echo $row['id'];?>" />
						<input type="hidden" name="operation" value="delete_document_group" />
						<input type="submit" value="<?php echo $_lang['delete']; ?>">
					</form>
				</td>
				<td align="right" width="200">
					<form method="post" action="index.php" name="accesspermissions" style="margin: 0px;">
						<input type="hidden" name="a" value="92" />
						<input type="hidden" name="groupid" value="<?php echo $row['id'];?>" />
						<input type="hidden" name="operation" value="rename_document_group" />
						<input type="text" name="newgroupname" value="<?php echo $row['name'] ;?>">&nbsp;
						<input type="submit" value="<?php echo $_lang['rename']; ?>">
					</form>
				</td>
			</tr>
			<tr>
				<td class="row2" colspan="3">&nbsp;&raquo;&nbsp;
					<span style="font-size: 9px">
					<?php echo $_lang['access_permissions_documents_in_group']; ?>
					<?php
					$sql = "SELECT tbl1.pagetitle AS document, tbl1.id AS id FROM
					$dbase.`".$table_prefix."site_content` AS tbl1,
					$dbase.`".$table_prefix."document_groups` AS tbl2
					WHERE tbl2.document_group=".$row['id']." AND
					tbl1.id = tbl2.document
					ORDER BY tbl1.id ASC";
					$rs2 = mysql_query($sql);
					$limit2 = mysql_num_rows($rs2);
					if($limit2<1) {
						echo $_lang['access_permissions_no_documents_in_group'];
					} else {
						$users = array();
						for($y=0; $y<$limit2; $y++) {
							$row2 = mysql_fetch_assoc($rs2);
							$users[] = '<a href="index.php?id='.$row2['id'].'&a=3" style="font-size: 9px" title="'.htmlspecialchars($row2['document']).'">'.$row2['id'].'</a>';
						}
						echo "\n\t".join($users, ",\n\t");
					}
					?>
					</span>
				</td>
			</tr>
<?php
			}
		}
?>
	</table>

</div>



<div class="tab-page" id="tabPage3">
<h2 class="tab"><?php echo $_lang['access_permissions_links'] ?></h2>
<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage3" ) );</script>
	<?php echo $_lang['access_permissions_links_tab']; ?> <p />
<?php
		$sql = "SELECT * FROM $dbase.`".$table_prefix."webgroup_names` ORDER BY name";
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		if($limit<1) {
			echo '<span class="warning">'.$_lang['no_groups_found'].'</span><br />';
		} else {
			?>
			<table width="95%" border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
			<thead><tr><td><b><?php echo $_lang['web_access_permissions_user_group']; ?></b></td><td><b><?php echo $_lang['web_access_permissions_user_group_access']; ?></b></td></tr></thead>
			<?php
			for($i=0; $i<$limit; $i++) {
			$row = mysql_fetch_assoc($rs);
			?>
			<tr class="row3">
				<td><b><?php echo $row['name'] ;?></b></td>
				<td>&nbsp;</td>
			</tr>
			<?php
				$sql = "
				SELECT * FROM
				$dbase.`".$table_prefix."documentgroup_names` AS tbl1,
				$dbase.`".$table_prefix."webgroup_access` AS tbl2
				WHERE tbl2.webgroup = ".$row['id']." AND
				tbl1.id = tbl2.documentgroup
				ORDER BY tbl1.name ASC
				";
				$rs2 = mysql_query($sql);
				$limit2 = mysql_num_rows($rs2);
				if($limit2<1) {
					?>
					<tr class="row1"><td>&nbsp;</td><td><i><?php echo $_lang['no_groups_found']; ?></i></td></tr>
					<?php
				} else {
					for($y=0; $y<$limit2; $y++) {
						$row2 = mysql_fetch_assoc($rs2);
						?>
						<tr class="row1">
							<td align="right">
								<form method="post" action="index.php" name="accesspermissions" style="margin: 0px;">
									<input type="hidden" name="a" value="92" />
									<input type="hidden" name="coupling" value="<?php echo $row2['id'];?>" />
									<input type="hidden" name="operation" value="remove_document_group_from_user_group" />
									<input type="submit" value="<?php echo $_lang['remove'];?> ->">
								</form>
							</td>
							<td>
									<?php echo $row2['name'] ;?>
							</td>
						</tr>
						<?php
					}
				}
				?>
				<tr class="row1"><td>&nbsp;
				</td><td>
				<form method="post" action="index.php" name="accesspermissions" style="margin: 0px;">
						<input type="hidden" name="a" value="92" />
						<input type="hidden" name="usergroup" value="<?php echo $row['id'];?>" />
						<input type="hidden" name="operation" value="add_document_group_to_user_group" />
						<?php echo $docgroupselector; ?>&nbsp;
						<input type="submit" value="<?php echo $_lang['add'];?>">
				</form>
				</td></tr>
				<?php
			}
			?>
		</table>
		<?php
		}
?>
</div>
