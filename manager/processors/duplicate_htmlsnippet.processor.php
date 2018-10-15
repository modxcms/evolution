<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('new_chunk')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
if($id==0) {
	$modx->webAlertAndQuit($_lang["error_no_id"]);
}

// count duplicates
$htmlsnippet = EvolutionCMS\Models\SiteHtmlsnippet::findOrFail($id);
$name = $htmlsnippet->name;
$count = EvolutionCMS\Models\SiteHtmlsnippet::where('name', 'like', $name.' '.$_lang['duplicated_el_suffix'].'%')->count();
if($count>=1) $count = ' '.($count+1);
else $count = '';

// duplicate htmlsnippet
$newHtmlsnippet = $htmlsnippet->replicate();
$newHtmlsnippet->name = $htmlsnippet->name.' '.$_lang['duplicated_el_suffix'].$count;
$newHtmlsnippet->push();

$_SESSION['itemname'] = $newHtmlsnippet->name;

// finish duplicating - redirect to new chunk
$header="Location: index.php?r=2&a=78&id=".$newHtmlsnippet->getKey();
header($header);
