<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('edit_document')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : NULL;
$reset = isset($_POST['reset']) && $_POST['reset'] == 'true' ? 1 : 0;
$items = isset($_POST['list']) ? $_POST['list'] : '';
$ressourcelist = '';
$updateMsg = '';

// check permissions on the document
include_once MODX_MANAGER_PATH . "processors/user_documents_permissions.class.php";
$udperms = new udperms();
$udperms->user = $modx->getLoginUserID();
$udperms->document = $id;
$udperms->role = $_SESSION['mgrRole'];

if(!$udperms->checkPermissions()) {
    $modx->webAlertAndQuit($_lang["access_permission_denied"]);
}

if(isset($_POST['listSubmitted'])) {
    $updateMsg .= '<span class="warning" id="updated">Updated!</span>';
    if (strlen($items) > 0) {
        $items = explode(';', $items);
        foreach ($items as $key => $value) {
            $docid = ltrim($value, 'item_');
            $key = $reset ? 0 : $key;
            if (is_numeric($docid)) {
                $modx->db->update(array('menuindex'=>$key), $modx->getFullTableName('site_content'), "id='{$docid}'");
            }
        }
    }
}

$limit = 0;
$disabled = 'true';
$pagetitle = '';
if ($id !== NULL) {
    $rs = $modx->db->select('pagetitle', $modx->getFullTableName('site_content'), "id='{$id}'");
    $pagetitle = $modx->db->getValue($rs);
    
    $rs = $modx->db->select('id, pagetitle, parent, menuindex, published, hidemenu, deleted', $modx->getFullTableName('site_content'), "parent='{$id}'", 'menuindex ASC');
    $resource = $modx->db->makeArray($rs);
    $limit = count($resource);
    if ($limit < 1) {
        $updateMsg = $_lang['sort_nochildren'];
    } else {
        $disabled = 0;
        foreach ($resource as $item) {
            // Add classes to determine whether it's published, deleted, not in the menu
            // or has children.
            // Use class names which match the classes in the document tree
            $classes = '';
            $classes .= ($item['hidemenu']) ? ' notInMenuNode ' : ' inMenuNode' ;
            $classes .= ($item['published']) ? ' publishedNode ' : ' unpublishedNode ' ;
            $classes = ($item['deleted']) ? ' deletedNode ' : $classes ;
            $classes .= (count($modx->getChildIds($item['id'], 1)) > 0) ? ' hasChildren ' : ' noChildren ';
            $ressourcelist .= '<li id="item_' . $item['id'] . '" class="sort '.$classes.'" title="">'. $item['pagetitle'] . ' <small>('.$item['id'].')</small></li>';
        }
    }
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
        
        ul.sortableList {
            margin: 2em 0 0 0;
            clear:both;
        }

        li {
            cursor: move;
            border: 1px solid #CCCCCC;
            background: #eee no-repeat 2px center;
            margin: 2px 0;
            list-style: none;
            padding: 1px 4px 1px 24px;
            min-height: 20px;
        }
        li.noChildren {
            background-image: url('.$_style["tree_page"].');
        }
        li.hasChildren {
            background-image: url('.$_style["tree_folder"].');
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
	       new Sortables($(\'sortlist\'), {
                   initialize: function()
                    {                    
                        renderList();
                    },
	           onComplete: function() {
	               renderList();
	           }
	       });
	       
	       if ('.$disabled.' == true) {
	           parent.tree.ca = \'\';
	       }
	    });
	    
	    parent.tree.updateTree();
        
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


$pagetitle = $id == 0 ? $site_name : $pagetitle;
    
$header .= '</head>
<body ondragstart="return false;">

<h1 class="pagetitle">
  <span class="pagetitle-icon">
    <i class="fa fa-sort-numeric-asc"></i>
  </span>
  <span class="pagetitle-text">
    '.$_lang["sort_menuindex"].'
  </span>
</h1>

<div id="actions">
    <ul class="actionButtons">
        '.(!$disabled ? '<li><a href="#" onclick="save();"><img src="'.$_style["icons_save"].'" /> '.$_lang['save'].'</a></li>' : '').'
        <li class="transition"><a href="#" onclick="document.location.href=\'index.php?a=2\';"><img src="'.$_style["icons_cancel"].'"> '.$_lang['cancel'].'</a></li>
    </ul>
</div>

<div class="section">
<div class="sectionHeader">'.$pagetitle.' ('.$id.')</div>
<div class="sectionBody">';

if(!$disabled) {
    $header .= '<br/><p>' . $_lang["sort_elements_msg"] . '</p>
    <ul class="actionButtons">
	    <li><a href="#" onclick="resetSortOrder();return false;">' . $_lang['reset_sort_order'] . '</a></li>
	    <li><a href="#" onclick="sort();return false;">' . $_lang['sort_alphabetically'] . '</a></li>
	</ul>';
};

echo $header;

echo $updateMsg . "<span class=\"warning\" style=\"display:none;\" id=\"updating\">Updating...</span>";

if(!$disabled) {
    echo '
        <ul id="sortlist" class="sortableList">
            '.$ressourcelist.'
        </ul>
	<form action="" method="post" name="sortableListForm" style="display: none;">
            <input type="hidden" name="listSubmitted" value="true" />
            <input type="text" id="list" name="list" value="" />
        </form>';
}

echo '
</div>
</div>';
?>
