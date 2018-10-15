<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('new_snippet')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
if($id==0) {
	$modx->webAlertAndQuit($_lang["error_no_id"]);
}

// count duplicates
$snippet = EvolutionCMS\Models\SiteSnippet::findOrFail($id);
$name = $snippet ->name;
$count = EvolutionCMS\Models\SiteSnippet::where('name', 'like', $name.' '.$_lang['duplicated_el_suffix'].'%')->count();
if($count>=1) $count = ' '.($count+1);
else $count = '';

// duplicate Snippet
$newSnippet = $snippet->replicate();
$newSnippet->name = $snippet->name.' '.$_lang['duplicated_el_suffix'].$count;
$newSnippet->push();

// Set the item name for logger
$_SESSION['itemname'] = $newSnippet->name;

// finish duplicating - redirect to new snippet
$header="Location: index.php?r=2&a=22&id=".$newSnippet->getKey();
header($header);
