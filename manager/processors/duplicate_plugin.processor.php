<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!$modx->hasPermission('new_plugin')) {
    $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id == 0) {
    $modx->webAlertAndQuit($_lang["error_no_id"]);
}

// count duplicates
$name = EvolutionCMS\Models\SitePlugin::select('name')->findOrFail($id)->name;
$count = EvolutionCMS\Models\SitePlugin::where('name', 'LIKE', "{$name} {$_lang['duplicated_el_suffix']}%'")->count();
if ($count >= 1) $count = ' ' . ($count + 1);
else $count = '';

// duplicate Plugin
$plugin = EvolutionCMS\Models\SitePlugin::select("name", "description", "disabled", "moduleguid", "plugincode", "properties", "category")
    ->findOrFail($id);

$pluginNew = $plugin->replicate();
$pluginNew->name .= " {$_lang['duplicated_el_suffix']}{$count}";
$pluginNew->disabled = 1;
$pluginNew->save();
$newid = $pluginNew->id;

// duplicate Plugin Event Listeners
EvolutionCMS\Models\SitePluginEvent::select('pluginid', "evtid", "priority")
    ->where('pluginid', $id)->get()
    ->each(function ($item, $key) use ($newid) {
        $item->pluginid = $newid;
        $item->replicate()->save();
    });

// Set the item name for logger
$name = EvolutionCMS\Models\SitePlugin::select('name')->findOrFail($newid)->name;
$_SESSION['itemname'] = $name;

// finish duplicating - redirect to new plugin
$header = "Location: index.php?r=2&a=102&id=$newid";
header($header);
