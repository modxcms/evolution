<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

if(!$modx->hasPermission('delete_plugin')) {	
	$e->setError(3);
	$e->dumpError();	
}

$tbl_site_plugins = $modx->getFullTablename('site_plugins');
$tbl_site_plugin_events = $modx->getFullTablename('site_plugin_events');

$rs = $modx->db->query("SELECT id FROM {$tbl_site_plugins} t1 WHERE disabled = 1 AND name IN (SELECT name FROM {$tbl_site_plugins} t2 WHERE t1.name = t2.name AND t1.id != t2.id)");

while($row = $modx->db->getRow($rs)) {

    $id = $row['id'];

    // invoke OnBeforePluginFormDelete event
    $modx->invokeEvent('OnBeforePluginFormDelete', array('id'=> $id));

    // delete the plugin.
    if (!$modx->db->delete($tbl_site_plugins, "id={$id}")) {
        echo "Something went wrong while trying to delete plugin {$id}";
        exit;
    } else {
        // delete the plugin events.
        if (!$modx->db->delete($tbl_site_plugin_events, "pluginid={$id}")) {
            echo "Something went wrong while trying to delete the plugin events for plugin {$id}";
            exit;
        } else {		
            // invoke OnPluginFormDelete event
            $modx->invokeEvent('OnPluginFormDelete', array('id'=>$id));
        }
    }
}

// empty cache
include_once "cache_sync.class.processor.php";
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(false);
$sync->emptyCache(); // first empty the cache		

header('Location: index.php?a=76');
