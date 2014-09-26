<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_template')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

$tbl_site_templates         = $modx->getFullTableName('site_templates');
$tbl_site_tmplvar_templates = $modx->getFullTableName('site_tmplvar_templates');
$tbl_site_tmplvars          = $modx->getFullTableName('site_tmplvars');

$siteURL = $modx->config['site_url'];

$updateMsg = '';

if(isset($_POST['listSubmitted'])) {
    $updateMsg .= '<span class="warning" id="updated">Updated!<br /><br /></span>';
    foreach ($_POST as $listName=>$listValue) {
        if ($listName == 'listSubmitted') continue;
        $orderArray = explode(';', rtrim($listValue, ';'));
        foreach($orderArray as $key => $item) {
            if (strlen($item) == 0) continue; 
            $tmplvar = ltrim($item, 'item_');
            $modx->db->update(array('rank'=>$key), $tbl_site_tmplvar_templates, "tmplvarid='{$tmplvar}' AND templateid='{$id}'");
        }
    }
    // empty cache
    $modx->clearCache('full');
}

$rs = $modx->db->select(
	"tv.name AS name, tv.id AS id, tr.templateid, tr.rank, tm.templatename",
	"{$tbl_site_tmplvar_templates} AS tr
		INNER JOIN {$tbl_site_tmplvars} AS tv ON tv.id = tr.tmplvarid
		INNER JOIN {$tbl_site_templates} AS tm ON tr.templateid = tm.id",
	"tr.templateid='{$id}'",
	"tr.rank, tv.rank, tv.id"
	);
$limit = $modx->db->getRecordCount($rs);

if($limit>1) {
    $i = 0;
    while ($row = $modx->db->getRow($rs)) {
        if ($i++ == 0) $evtLists .= '<strong>'.$row['templatename'].'</strong><br /><ul id="sortlist" class="sortableList">';
        $evtLists .= '<li id="item_'.$row['id'].'" class="sort">'.$row['name'].'</li>';
    }
    $evtLists .= '</ul>';
}


$header = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>MODX</title>
    <meta http-equiv="Content-Type" content="text/html; charset='.$modx_manager_charset.'" />
    <link rel="stylesheet" type="text/css" href="media/style/'.$modx->config['manager_theme'].'/style.css" />
    <script type="text/javascript" src="media/script/mootools/mootools.js"></script>';

$header .= '
    <style type="text/css">
        .topdiv {
            border: 0;
        }

        .subdiv {
            border: 0;
        }

        li {list-style:none;}

        ul.sortableList {
            padding-left: 20px;
            margin: 0px;
            width: 300px;
            font-family: Arial, sans-serif;
        }

        ul.sortableList li {
            font-weight: bold;
            cursor: move;
            color: #444444;
            padding: 3px 5px;
            margin: 4px 0px;
            border: 1px solid #CCCCCC;
            background-image: url("'.$_style['fade'].'");
            background-repeat: repeat-x;
        }
    </style>
    <script type="text/javascript">
        function save() {
            setTimeout("document.sortableListForm.submit()",1000);
        }
            
        window.addEvent(\'domready\', function() {
            new Sortables($(\'sortlist\'),
            {
                initialize: function()
                {
                    $$(\'li.sort\').each(function(el, i)
                    {
                        el.setStyle(\'padding\', \'3px 5px\');
                        el.setStyle(\'font-weight\', \'bold\');
                        el.setStyle(\'width\', \'300px\');
                        el.setStyle(\'background-color\', \'#ccc\');
                        el.setStyle(\'cursor\', \'move\');
                    });
                },
                onComplete: function()
                {
                   var list = \'\';
                    $$(\'li.sort\').each(function(el, i)
                    {
                       list += el.id + \';\';
                   });
                   $(\'list\').value = list;
               }
           });
        });
    </script>';

$header .= '</head>
<body ondragstart="return false;">

<h1>'.$_lang["template_tv_edit_title"].'</h1>

<div id="actions">
    <ul class="actionButtons">
        <li><a href="#" onclick="save();"><img src="'.$_style["icons_save"].'" /> '.$_lang['save'].'</a></li>
        <li><a href="#" onclick="document.location.href=\'index.php?a=16&amp;id='.$id.'\';"><img src="'.$_style["icons_cancel"].'"> '.$_lang['cancel'].'</a></li>
    </ul>
</div>

<div class="section">
<div class="sectionHeader">'.$_lang['template_tv_edit'].'</div>
<div class="sectionBody">
<p>'.$_lang["template_tv_edit_message"].'</p>';

echo $header;

echo $updateMsg . "<span class=\"warning\" style=\"display:none;\" id=\"updating\">Updating...<br /><br /> </span>";

echo $evtLists;

echo '
</div>
</div>
<form action="" method="post" name="sortableListForm" style="display: none;">
            <input type="hidden" name="listSubmitted" value="true" />
            <input type="text" id="list" name="list" value="" />
</form>';


?>