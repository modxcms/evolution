<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
$hasPermission = 0;
if($modx->hasPermission('edit_plugin') || $modx->hasPermission('new_plugin')) {
    $displayInfo['plugin'] = array('table'=>'site_plugins','action'=>102,'name'=>$_lang['manage_plugins']);
    $hasPermission = 1;
}
if($modx->hasPermission('edit_snippet') || $modx->hasPermission('new_snippet')) {
    $displayInfo['snippet'] = array('table'=>'site_snippets','action'=>22,'name'=>$_lang['manage_snippets']);
    $displayInfo['htmlsnippet'] = array('table'=>'site_htmlsnippets','action'=>78,'name'=>$_lang['manage_htmlsnippets']);
    $hasPermission = 1;
}
if($modx->hasPermission('edit_template') || $modx->hasPermission('new_template')) {
    $displayInfo['templates'] = array('table'=>'site_templates','action'=>16,'name'=>$_lang['manage_templates']);
    $displayInfo['tmplvars'] = array('table'=>'site_tmplvars','action'=>301,'name'=>$_lang['tmplvars']);
    $hasPermission = 1;
}
if($modx->hasPermission('edit_module') || $modx->hasPermission('new_module')) {
    $displayInfo['modules'] = array('table'=>'site_modules','action'=>108,'name'=>$_lang['modules']);
    $hasPermission = 1;
}
if ($hasPermission) {
    $catId = intval($_GET['catId']);
    include_once "categories.inc.php";
    deleteCategory($catId);
}
$header="Location: index.php?a=76";
header($header);
?>
