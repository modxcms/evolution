<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!$modx->hasPermission('new_template')) {
    $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id == 0) {
    $modx->webAlertAndQuit($_lang["error_no_id"]);
}

// count duplicates
$name = EvolutionCMS\Models\SiteTemplate::select('templatename')->findOrFail($id)->templatename;
$count = EvolutionCMS\Models\SiteTemplate::where('templatename', 'LIKE', "{$name} {$_lang['duplicated_el_suffix']}%'")->count();
if ($count >= 1) $count = ' ' . ($count + 1);
else $count = '';

// duplicate template
$template = EvolutionCMS\Models\SiteTemplate::select("templatename", "description", "content", "category")
    ->findOrFail($id);
$templateNew = $template->replicate();
$templateNew->templatename .= " {$_lang['duplicated_el_suffix']}{$count}";
$templateNew->save();
$newid = $templateNew->id;

// duplicate TV values
EvolutionCMS\Models\SiteTmplvarTemplate::select("tmplvarid", "templateid", "rank")
    ->where('templateid', $id)->get()
    ->each(function ($item, $key) use ($newid) {
        $item->templateid = $newid;
        $item->replicate()->save();
    });

// Set the item name for logger
$name = EvolutionCMS\Models\SiteTemplate::select('templatename')->findOrFail($newid)->templatename;
$_SESSION['itemname'] = $name;

// finish duplicating - redirect to new template
$header="Location: index.php?r=2&a=16&id=$newid";
header($header);
