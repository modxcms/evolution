<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

if(!$modx->hasPermission('delete_snippet')) {
	$e->setError(3);
	$e->dumpError();
}
?>
<?php
$id=intval($_GET['id']);

// invoke OnBeforeSnipFormDelete event
$modx->invokeEvent("OnBeforeSnipFormDelete",
						array(
							"id"	=> $id
						));

//ok, delete the snippet.
$sql = "DELETE FROM $dbase.`".$table_prefix."site_snippets` WHERE $dbase.`".$table_prefix."site_snippets`.id=".$id.";";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the snippet...";
	exit;
} else {
		// invoke OnSnipFormDelete event
		$modx->invokeEvent("OnSnipFormDelete",
								array(
									"id"	=> $id
								));

		// empty cache
		include_once "cache_sync.class.processor.php";
		$sync = new synccache();
		$sync->setCachepath("../assets/cache/");
		$sync->setReport(false);
		$sync->emptyCache(); // first empty the cache
		// finished emptying cache - redirect

	$header="Location: index.php?a=76&r=2";
	header($header);
}

?>
