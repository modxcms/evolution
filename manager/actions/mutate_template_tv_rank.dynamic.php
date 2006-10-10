<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_template')) {
	$e->setError(3);
	$e->dumpError();
}

if($manager_theme) {
    $useTheme = $manager_theme . '/';
} else {
    $useTheme = '';
}

if (!is_numeric($_REQUEST['id'])) {
	echo 'Template ID is NaN';
	exit;
}

$basePath = $modx->config['base_path'];
$siteURL = $modx->config['site_url'];
$include = 'SLLists.class.php';

require_once($include);
$sortableLists = new SLLists($siteURL.'manager/media/script/scriptaculous/');
$sortableLists->debug = FALSE;
$updateMsg = '';

if(isset($_POST['sortableListsSubmitted'])) {
    $updateMsg .= "<span class=\"warning\" id=\"updated\">Updated!<br /><br /> </span>";
	$tbl = $dbase.'.'.$table_prefix.'site_tmplvar_templates';
	foreach ($_POST as $listName=>$listValue) {
        if ($listName == 'sortableListsSubmitted') continue;
    	$orderArray = $sortableLists->getOrderArray($listValue,$listName.'List');
    	foreach($orderArray as $item) {
    		$sql = "UPDATE $tbl set rank=".$item['order']." WHERE tmplvarid=".$item['element']." and templateid=".$_REQUEST['id'];
    		$modx->db->query($sql);
    	}
    }
    // empty cache
	include_once ($basePath.'manager/processors/cache_sync.class.processor.php');
	$sync = new synccache();
	$sync->setCachepath($basePath.'/assets/cache/');
	$sync->setReport(false);
	$sync->emptyCache(); // first empty the cache
}

$sql = "SELECT tv.name as 'name', tv.id as 'id', tr.templateid, tr.rank,tm.templatename
	FROM ".$modx->getFullTableName('site_tmplvar_templates')." tr
	INNER JOIN ".$modx->getFullTableName('site_tmplvars')." tv ON tv.id = tr.tmplvarid
	INNER JOIN ".$modx->getFullTableName('site_templates')." tm ON tr.templateid = tm.id
	WHERE tr.templateid='".$_REQUEST['id']."' ORDER BY tr.rank ASC";

$rs = $modx->db->query($sql);
$limit = $modx->db->getRecordCount($rs);

$sortableLists->addList($_REQUEST['id'].'List',$_REQUEST['id']);

if($limit>1) {
    for ($i=0;$i<$limit;$i++) {
        $row = $modx->db->getRow($rs);
        if ($i == 0 ) $evtLists .= '<strong>'.$row['templatename'].'</strong><br/><ul id="'.$row['templateid'].'List" class="sortableList">';
        $evtLists .= '<li id="item_'.$row['id'].'">'.$row['name'].'</li>';
    }
}

$evtLists .= '</ul>';

$header = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>MODx</title>
	<meta http-equiv="Content-Type" content="text/html; charset='.$modx_charset.'" />
	<link rel="stylesheet" type="text/css" href="media/style/'.$useTheme.'style.css" />';

$header .= $sortableLists->printTopJS();

$header .= '
    <style type="text/css">
        .topdiv {
			border: 0;
		}

		.subdiv {
			border: 0;
		}

		li {list-style:none;}

		.tplbutton {
			text-align: right;
		}

		ul.sortableList {
			padding-left: 20px;
			margin: 0px;
			width: 300px;
			font-family: Arial, sans-serif;
		}

		ul.sortableList li {
			font-weight: bold;
			cursor: move;
			color: grey;
			padding: 2px 2px;
			margin: 4px 0px;
			border: 1px solid #000000;
			background-image: url("media/style/'.$useTheme.'images/bg/grid_hdr.gif");
			background-repeat: repeat-x;
		}

		#bttn .bttnheight {
			height: 25px !important;
			padding: 0px;
			padding-top: 6px;
			float: left;
			vertical-align:		middle !important;

		}
		#bttn a{
			cursor: 			default !important;
			font: 				icon !important;
			color:				black !important;
			border:				0px !important;
			padding:			5px 5px 7px 5px!important;
			white-space:		nowrap !important;
			vertical-align:		middle !important;
			background:	transparent !important;
			text-decoration: none;
		}

		#bttn a:hover {
			border:		1px solid darkgreen !important;
			padding:			4px 4px 6px 4px !important;
			background-image:	url("media/style/'.$useTheme.'images/bg/button_dn.gif") !important;
			text-decoration: none;
		}

		#bttn a img {
			vertical-align: middle !important;
		}

        #sortableListForm {display:none;}
	</style>
    <script type="text/javascript" language="JavaScript">
        function save() {
            populateHiddenVars();
        	if (document.getElementById("updated")) {new Effect.Fade(\'updated\', {duration:0});}
        	new Effect.Appear(\'updating\',{duration:0.5});
        	setTimeout("document.sortableListForm.submit()",1000);
    	}
	</script>';

$header .= '</head>
<body ondragstart="return false;">

<div class="subTitle" id="bttn">
	<div class="bttnheight"><a id="Button1" onclick="save();"><img src="media/style/'.$useTheme.'images/icons/save.gif"> '.$_lang['save'].'</a></div>
	<div class="bttnheight"><a id="Button2" onclick="document.location.href=\'index.php?a=16&amp;id='.$_REQUEST['id'].'\';"><img src="media/style/'.$useTheme.'images/icons/cancel.gif"> '.$_lang['cancel'].'</a></div>
	<div class="stay">  </div>
</div>

<div class="sectionHeader"><img src="media/style/'.$useTheme.'images/misc/dot.gif" alt="." />&nbsp;';

$middle = '</div><div class="sectionBody">';

$footer = '
	</div>
';
echo $header.$_lang['template_tv_edit'].$middle;

echo $updateMsg . "<span class=\"warning\" style=\"display:none;\" id=\"updating\">Updating...<br /><br /> </span>";

echo $evtLists;

echo $footer;

echo $sortableLists->printForm('', 'POST', 'Submit', 'button');

echo '<br/>';

echo $sortableLists->printBottomJS();

?>
