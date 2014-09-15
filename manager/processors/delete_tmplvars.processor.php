<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_template')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id'])? intval($_GET['id']) : 0;
if($id==0) {
	$modx->webAlertAndQuit($_lang["error_no_id"]);
}

$forced = isset($_GET['force'])? $_GET['force'] : 0;

// check for relations
if(!$forced) {
	$drs = $modx->db->select(
		'sc.id, sc.pagetitle,sc.description',
		$modx->getFullTableName('site_content')." AS sc
			INNER JOIN ".$modx->getFullTableName('site_tmplvar_contentvalues')." AS stcv ON stcv.contentid=sc.id",
		"stcv.tmplvarid='{$id}'"
		);
	$count = $modx->db->getRecordCount($drs);
	if($count>0){
		include_once "header.inc.php";
?>	
<script>
	function deletedocument() {
		document.location.href="index.php?id=<?php echo $id;?>&a=303&force=1";
	}
</script>
<h1><?php echo $_lang['tmplvars']; ?></h1>

<div id="actions">
	<ul class="actionButtons">
		<li id="cmdDelete"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete"] ?>" /> <?php echo $_lang["delete"]; ?></a></td>
		<li id="cmdCancel"><a href="index.php?a=301&id=<?php echo $id;?>"><img src="<?php echo $_style["icons_cancel"] ?>" /> <?php echo $_lang["cancel"]; ?></a></li>
	</ul>
</div>

<div class="section">
<div class="sectionHeader"><?php echo $_lang['tmplvars']; ?></div>
<div class="sectionBody">
<?php
		echo "<p>".$_lang['tmplvar_inuse']."</p>";
		echo "<ul>";
		while ($row = $modx->db->getRow($drs)) {
			echo '<li><span style="width: 200px"><a href="index.php?id='.$row['id'].'&a=27">'.$row['pagetitle'].'</a></span>'.($row['description']!='' ? ' - '.$row['description'] : '').'</li>';
		}
		echo "</ul>";
		echo '</div>';
		echo '</div>';		
		include_once "footer.inc.php";
		exit;
	}	
}

// Set the item name for logger
$name = $modx->db->getValue($modx->db->select('name', $modx->getFullTableName('site_tmplvars'), "id='{$id}'"));
$_SESSION['itemname'] = $name;

// invoke OnBeforeTVFormDelete event
$modx->invokeEvent("OnBeforeTVFormDelete",
	array(
		"id"	=> $id
	));

// delete variable
$modx->db->delete($modx->getFullTableName('site_tmplvars'), "id='{$id}'");

// delete variable's content values
$modx->db->delete($modx->getFullTableName('site_tmplvar_contentvalues'), "tmplvarid='{$id}'");

// delete variable's template access
$modx->db->delete($modx->getFullTableName('site_tmplvar_templates'), "tmplvarid='{$id}'");

// delete variable's access permissions
$modx->db->delete($modx->getFullTableName('site_tmplvar_access'), "tmplvarid='{$id}'");

// invoke OnTVFormDelete event
$modx->invokeEvent("OnTVFormDelete",
	array(
		"id"	=> $id
	));

// empty cache
$modx->clearCache('full');

// finished emptying cache - redirect
$header="Location: index.php?a=76&r=2";
header($header);
?>