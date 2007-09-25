<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

// (un)publishing of documents, version 2!
// first, publish document waiting to be published
$ctime = time();
$sctable = $modx->getFullTableName('site_content');

$sql = "UPDATE $sctable SET published=1 WHERE pub_date < ".$ctime." AND pub_date!=0 AND unpub_date > ".$ctime;
$rs = mysql_query($sql);
$num_rows_pub = mysql_affected_rows($modxDBConn);

$sql = "UPDATE $sctable SET published=0 WHERE unpub_date < ".$ctime." AND unpub_date!=0 AND published=1";
$rs = mysql_query($sql);
$num_rows_unpub = mysql_affected_rows($modxDBConn);

?>
<br />
<div class="sectionHeader"><?php echo $_lang['refresh_title']; ?></div><div class="sectionBody">
<?php printf($_lang["refresh_published"], $num_rows_pub) ?><br />
<?php printf($_lang["refresh_unpublished"], $num_rows_unpub) ?><br />
<?php
include_once "./processors/cache_sync.class.processor.php";
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(true);
$sync->emptyCache();

// invoke OnSiteRefresh event
$modx->invokeEvent("OnSiteRefresh");

?>
</div>
