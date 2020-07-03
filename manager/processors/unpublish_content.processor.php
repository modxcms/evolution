<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('save_document')||!$modx->hasPermission('publish_document')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id'])? (int)$_REQUEST['id'] : 0;
if($id==0) {
	$modx->webAlertAndQuit($_lang["error_no_id"]);
}

/************webber ********/
$content=\EvolutionCMS\Models\SiteContent::query()->select('parent', 'pagetitle')->where('id', $id)->first()->toArray();
$pid=($content['parent']==0?$id:$content['parent']);

/************** webber *************/
$sd=isset($_REQUEST['dir'])?'&dir='.$_REQUEST['dir']:'&dir=DESC';
$sb=isset($_REQUEST['sort'])?'&sort='.$_REQUEST['sort']:'&sort=createdon';
$pg=isset($_REQUEST['page'])?'&page='.(int)$_REQUEST['page']:'';
$add_path=$sd.$sb.$pg;

/***********************************/



// check permissions on the document
$udperms = new EvolutionCMS\Legacy\Permissions();
$udperms->user = $modx->getLoginUserID('mgr');
$udperms->document = $id;
$udperms->role = $_SESSION['mgrRole'];

if(!$udperms->checkPermissions()) {
	$modx->webAlertAndQuit($_lang["access_permission_denied"]);
}

// update the document
\EvolutionCMS\Models\SiteContent::query()->find($id)->update(array(
    'published'   => 0,
    'pub_date'    => 0,
    'unpub_date'  => 0,
    'editedby'    => $modx->getLoginUserID('mgr'),
    'editedon'    => time(),
    'publishedby' => 0,
    'publishedon' => 0,
));

// invoke OnDocUnPublished  event
$modx->invokeEvent("OnDocUnPublished",array("docid"=>$id));

// Set the item name for logger
$_SESSION['itemname'] = $content['pagetitle'];

// empty cache
$modx->clearCache('full');

$header="Location: index.php?a=3&id=$pid&r=1".$add_path;

header($header);
