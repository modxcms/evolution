<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_template')) {	
	$e->setError(3);
	$e->dumpError();	
}
?>
<?php

	$id = isset($_GET['id'])? intval($_GET['id']):0;
	$forced = isset($_GET['force'])? $_GET['force']:0;

	// check for relations
	if(!$forced) {
		$sql="SELECT sc.id, sc.pagetitle,sc.description FROM $dbase.`".$table_prefix."site_content` sc INNER JOIN $dbase.`".$table_prefix."site_tmplvar_contentvalues` stcv ON stcv.contentid=sc.id WHERE stcv.tmplvarid=".$id.";";
		$drs = mysql_query($sql);
		$count = mysql_num_rows($drs);
		if($count>0){
			include_once "header.inc.php";
		?>	
			<script>
				function deletedocument() {
					document.location.href="index.php?id=<?php echo $id;?>&a=303&force=1";
				}
			</script>
			<div class="subTitle">
			<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['tmplvars']; ?></span>
				<table cellpadding="0" cellspacing="0" class="actionButtons">
					<td id="cmdDelete"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle"> <?php echo $_lang["delete"]; ?></a></td>
					<td id="cmdCancel"><a href="index.php?a=301&id=<?php echo $id;?>"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang["cancel"]; ?></a></td>
				</table>
			</div>
			<div class="sectionHeader"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['tmplvars']; ?></div><div class="sectionBody">
		<?php
			echo "<p>".$_lang['tmplvar_inuse']."</p>";
			echo "<ul>";
			for($i=0;$i<$count;$i++) {
				$row = mysql_fetch_assoc($drs);
				echo '<li><span style="width: 200px"><a href="index.php?id='.$row['id'].'&a=27">'.$row['pagetitle'].'</a></span>'.($row['description']!='' ? ' - '.$row['description'] : '').'</li>';
			}
			echo "</ul>";
			echo '</div>';		
			include_once "footer.inc.php";
			exit;
		}	
	}

	// invoke OnBeforeTVFormDelete event
	$modx->invokeEvent("OnBeforeTVFormDelete",
							array(
								"id"	=> $id
							));
						
	// delete variable
	$sql = "DELETE FROM $dbase.`".$table_prefix."site_tmplvars` WHERE id=".$id.";";
	$rs = mysql_query($sql);
	if(!$rs) {
		echo "Something went wrong while trying to delete the field...";
		exit;
	} else {		
		$header="Location: index.php?a=76&r=2";
		header($header);
	}

	// delete variable's content values
	mysql_query("DELETE FROM $dbase.`".$table_prefix."site_tmplvar_contentvalues` WHERE tmplvarid=".$id.";");
	
	// delete variable's template access
	mysql_query("DELETE FROM $dbase.`".$table_prefix."site_tmplvar_templates` WHERE tmplvarid=".$id.";");
	
	// delete variable's access permissions
	mysql_query("DELETE FROM $dbase.`".$table_prefix."site_tmplvar_access` WHERE tmplvarid=".$id.";");

	// invoke OnTVFormDelete event
	$modx->invokeEvent("OnTVFormDelete",
							array(
								"id"	=> $id
							));	
							
?>
