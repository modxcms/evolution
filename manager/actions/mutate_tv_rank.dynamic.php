<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_template')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$reset = isset($_POST['reset']) && $_POST['reset'] == 'true' ? 1 : 0;

$tbl_site_tmplvars          = $modx->getFullTableName('site_tmplvars');

$siteURL = $modx->config['site_url'];

$updateMsg = '';

if(isset($_POST['listSubmitted'])) {
    $updateMsg .= '<span class="warning" id="updated">Updated!<br /><br /></span>';
    foreach ($_POST as $listName=>$listValue) {
        if ($listName == 'listSubmitted' || $listName == 'reset') continue;
        $orderArray = explode(';', rtrim($listValue, ';'));
        foreach($orderArray as $key => $item) {
            if (strlen($item) == 0) continue;
            $key = $reset ? 0 : $key;
            $id = ltrim($item, 'item_');
            $modx->db->update(array('rank'=>$key), $tbl_site_tmplvars, "id='{$id}'");
        }
    }
    // empty cache
    $modx->clearCache('full');
}

$rs = $modx->db->select(
	"name, caption, id, rank",
        $tbl_site_tmplvars,
	"",
	"rank ASC, id ASC"
	);
$limit = $modx->db->getRecordCount($rs);

if($limit>1) {
    $tvsArr = array();
    while ($row = $modx->db->getRow($rs)) {
        $tvsArr[] = $row;
    }

    $i = 0;
    foreach($tvsArr as $row) {
        if ($i++ == 0) $evtLists .= '<strong>'.$row['templatename'].'</strong><br /><ul id="sortlist" class="sortableList">';
		$caption = $row['caption'] != '' ? $row['caption'] : $row['name'];
        $evtLists .= '<li id="item_'.$row['id'].'" class="sort">'.$caption.' <small class="protectedNode" style="float:right">[*'.$row['name'].'*]</small></li>';
    }
    $evtLists .= '</ul>';
}

$header = '
    <script>window.$j = jQuery.noConflict();</script>
    <style type="text/css">
        .topdiv {
            border: 0;
        }

        .subdiv {
            border: 0;
        }

        li {list-style:none;}

        ul.sortableList {
            margin: 0px;
            width: 500px;
            font-family: Arial, sans-serif;
        }

        ul.sortableList li {
            font-weight: bold;
            cursor: move;
            color: #444444;
            padding: 3px 5px;
            margin: 4px 0px;
            border: 1px solid #CCCCCC;
            background: url("'.$_style['fade'].'") center repeat-x;
            background-size: auto 100%;
            display:inline-block;
        }
    </style>
    <script type="text/javascript">
        function save() {
            $j("#updated").hide();
            $j("#updating").fadeIn();
            setTimeout("document.sortableListForm.submit()",1000);
        }
        
        function renderList() {
            var list = \'\';
            $$(\'li.sort\').each(function(el, i) {
                list += el.id + \';\';
            });
            $(\'list\').value = list;
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
                        el.setStyle(\'width\', \'500px\');
                        el.setStyle(\'background-color\', \'#ccc\');
                        el.setStyle(\'cursor\', \'move\');
                    });
                    renderList();
                },
                onComplete: function()
                {
                   renderList();
               }
           });
        });
        
        function sort() {
            var items = $j(\'.sort\').get();
            items.sort(function(a,b){
              var keyA = $j(a).text().toLowerCase();
              var keyB = $j(b).text().toLowerCase();
              return keyA.localeCompare(keyB);
            });
            var ul = $j(\'#sortlist\');
            var list = \'\';
            $j.each(items, function(i, li){
              ul.append(li);
              list += li.id + \';\';
            });
            $j(\'#list\').val(list);
        }
        
        function resetSortOrder() {
            if (confirm("'.$_lang["confirm_reset_sort_order"].'")==true) {
                documentDirty=false;
                var input = document.createElement("input");
                input.type = "hidden";
                input.name = "reset";
                input.value = "true";
                document.sortableListForm.appendChild(input);
                save();
            }
        }
    </script>';

$header .= '</head>
<body ondragstart="return false;">

<h1>'.$_lang["template_tv_edit_title"].'</h1>

<div id="actions">
    <ul class="actionButtons">
        <li class="transition"><a href="#" onclick="save();"><img src="'.$_style["icons_save"].'" /> '.$_lang['save'].'</a></li>
        <li class="transition"><a href="#" onclick="document.location.href=\'index.php?a=76\';"><img src="'.$_style["icons_cancel"].'"> '.$_lang['cancel'].'</a></li>
    </ul>
</div>

<div class="section">
<div class="sectionHeader">'.$_lang['template_tv_edit'].'</div>
<div class="sectionBody">
<br/><p>'.$_lang["tmplvars_rank_edit_message"].'</p>
<ul class="actionButtons">
	<li><a href="#" onclick="sort();return false;">'.$_lang['sort_alphabetically'].'</a></li>
    <li><a href="#" onclick="resetSortOrder();return false;">'.$_lang['reset_sort_order'].'</a></li>
</ul>';

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