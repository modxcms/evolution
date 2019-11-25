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
    $siteTmlvarTemplates = EvolutionCMS\Models\SiteTmplvarContentvalue::with('resource')->where('tmplvarid', '=', $id)->get();
    $count = $siteTmlvarTemplates->count();
    if($count > 0) {
		include_once MODX_MANAGER_PATH . "includes/header.inc.php";
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
                    foreach ($siteTmlvarTemplates as $siteTmlvarTemplate) {
                        echo '<li><span style="width: 200px"><a href="index.php?id=' . $siteTmlvarTemplate->resource->id . '&a=27">' . $siteTmlvarTemplate->resource->pagetitle . '</a></span>' . ($siteTmlvarTemplate->resource->description != '' ? ' - ' . $siteTmlvarTemplate->resource->description : '') . '</li>';
                    }
					?>
				</ul>
			</div>
		</div>
		<?php
		include_once MODX_MANAGER_PATH . "includes/footer.inc.php";
		exit;
	}
}

// Set the item name for logger
$name = EvolutionCMS\Models\SiteTmplvar::findOrFail($id)->name;
$_SESSION['itemname'] = $name;

// invoke OnBeforeTVFormDelete event
$modx->invokeEvent("OnBeforeTVFormDelete", array(
	"id" => $id
));

// delete variable
EvolutionCMS\Models\SiteTmplvar::destroy($id);

// invoke OnTVFormDelete event
$modx->invokeEvent("OnTVFormDelete", array(
	"id" => $id
));

// empty cache
$modx->clearCache('full');

// finished emptying cache - redirect
$header = "Location: index.php?a=76&r=2";
header($header);
