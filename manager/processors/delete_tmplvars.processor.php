<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('delete_template')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id == 0) {
	$modx->webAlertAndQuit($_lang["error_no_id"]);
}

$forced = isset($_GET['force']) ? $_GET['force'] : 0;

// check for relations
if(!$forced) {
	$drs = $modx->db->select('sc.id, sc.pagetitle,sc.description', $modx->getFullTableName('site_content') . " AS sc
			INNER JOIN " . $modx->getFullTableName('site_tmplvar_contentvalues') . " AS stcv ON stcv.contentid=sc.id", "stcv.tmplvarid='{$id}'");
	$count = $modx->db->getRecordCount($drs);
	if($count > 0) {
		include_once "header.inc.php";
		?>
		<script>
			var actions = {
				delete: function() {
					document.location.href = "index.php?id=<?= $id ?>&a=303&force=1";
				},
				cancel: function() {
					window.location.href = 'index.php?a=301&id=<?= $id ?>';
				}
			};
		</script>

		<h1><?= $_lang['tmplvars'] ?></h1>

		<?= $_style['actionbuttons']['dynamic']['canceldelete'] ?>

		<div class="section">
			<div class="sectionHeader"><?= $_lang['tmplvars'] ?></div>
			<div class="sectionBody">
				<p><?= $_lang['tmplvar_inuse'] ?></p>
				<ul>
					<?php
					while($row = $modx->db->getRow($drs)) {
						echo '<li><span style="width: 200px"><a href="index.php?id=' . $row['id'] . '&a=27">' . $row['pagetitle'] . '</a></span>' . ($row['description'] != '' ? ' - ' . $row['description'] : '') . '</li>';
					}
					?>
				</ul>
			</div>
		</div>
		<?php
		include_once "footer.inc.php";
		exit;
	}
}

// Set the item name for logger
$name = $modx->db->getValue($modx->db->select('name', $modx->getFullTableName('site_tmplvars'), "id='{$id}'"));
$_SESSION['itemname'] = $name;

// invoke OnBeforeTVFormDelete event
$modx->invokeEvent("OnBeforeTVFormDelete", array(
	"id" => $id
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
$modx->invokeEvent("OnTVFormDelete", array(
	"id" => $id
));

// empty cache
$modx->clearCache('full');

// finished emptying cache - redirect
$header = "Location: index.php?a=76&r=2";
header($header);
