<?php

if(!defined('MODX_BASE_PATH')) die('What are you doing? Get out of here!');

function renderLockIcon($resourceTable, $id)
{
    global $modx, $_lang, $_style;
    
    switch($resourceTable) {
        case 'site_templates': $lockType = 1; break;
        case 'site_tmplvars': $lockType = 2; break;
        case 'site_htmlsnippets': $lockType = 3; break;
        case 'site_snippets': $lockType = 4; break;
        case 'site_plugins': $lockType = 5; break;
        case 'site_modules': $lockType = 6; break;
    }
    
    if(!isset($lockType)) return '';
    
    $lockedByUser = '';
    $rowLock = $modx->elementIsLocked($lockType, $id, true);
    if($rowLock && $modx->hasPermission('display_locks')) {
        if($rowLock['internalKey'] == $modx->getLoginUserID()) {
            $title = $modx->parseText($_lang['lock_element_editing'], array('element_type'=>$_lang['lock_element_type_'.$lockType],'firsthit_df'=>$rowLock['firsthit_df']));
            $lockedByUser = '<span title="'.$title.'" class="editResource" style="cursor:context-menu;"><img src="'.$_style['icons_preview_resource'].'" /></span>&nbsp;';
        } else {
            $title = $modx->parseText($_lang['lock_element_locked_by'], array('element_type'=>$_lang['lock_element_type_'.$lockType], 'username'=>$rowLock['username'], 'firsthit_df'=>$rowLock['firsthit_df']));
            if($modx->hasPermission('remove_locks')) {
                $lockedByUser = '<a href="#" onclick="unlockElement('.$lockType.', '.$id.', this);return false;" title="'.$title.'" class="lockedResource"><img src="'.$_style['icons_secured'].'" /></a>';
            } else {
                $lockedByUser = '<span title="'.$title.'" class="lockedResource" style="cursor:context-menu;"><img src="'.$_style['icons_secured'].'" /></span>';
            }
        }
    }
    return '<span id="lock'.$lockType.'_'.$id.'">'.$lockedByUser.'</span>';
}

// create elements list function
function createElementsList($resourceTable,$action,$tablePre,$nameField = 'name') {
    global $modx, $_lang;
    
    $output  = '
        <form class="filterElements-form filterElements-form--eit" style="margin-top: 0;">
          <input class="form-control" type="text" placeholder="Type here to filter list" id="tree_'.$resourceTable.'_search">
        </form>';
        
    $output .= '<div class="panel-group"><div class="panel panel-default" id="tree_'.$resourceTable.'">';
    $pluginsql = $resourceTable == 'site_plugins' ? $tablePre.$resourceTable.'`.disabled, ' : '';
    $tvsql = $resourceTable == 'site_tmplvars' ? $tablePre.$resourceTable.'`.caption, ' : '';
    //$orderby = $resourceTable == 'site_plugins' ? '6,2' : '5,1';

    if ($resourceTable == 'site_plugins' || $resourceTable == 'site_tmplvars') {
        $orderby= '6,2';
    }
    
    else{
        $orderby= '5,1';
    }

    $sql = 'SELECT '.$pluginsql.$tvsql.$tablePre.$resourceTable.'`.'.$nameField.' as name, '.$tablePre.$resourceTable.'`.id, '.$tablePre.$resourceTable.'`.description, '.$tablePre.$resourceTable.'`.locked, if(isnull('.$tablePre.'categories`.category),\''.$_lang['no_category'].'\','.$tablePre.'categories`.category) as category, '.$tablePre.'categories`.id  as catid FROM '.$tablePre.$resourceTable.'` left join '.$tablePre.'categories` on '.$tablePre.$resourceTable.'`.category = '.$tablePre.'categories`.id ORDER BY '.$orderby;
    
    $rs = $modx->db->query($sql);
    $limit = $modx->db->getRecordCount($rs);
    
    if($limit<1){
        return '';
    }
    
    $preCat = '';
    $insideUl = 0;
    
    for($i=0; $i<$limit; $i++) {
        $row = $modx->db->getRow($rs);
        $row['category'] = stripslashes($row['category']);
        if ($preCat !== $row['category']) {
            $output .= $insideUl? '</div>': '';
            $row['catid'] = intval($row['catid']);
            $output .= '<div class="panel-heading"><span class="panel-title"><a class="accordion-toggle" id="toggle'.$resourceTable.$row['catid'].'" href="#collapse'.$resourceTable.$row['catid'].'" data-cattype="'.$resourceTable.'" data-catid="'.$row['catid'].'" title="Click to toggle collapse. Shift+Click to toggle all."> '.$row['category'].'</a></span></div><div class="panel-collapse in '.$resourceTable.'"  id="collapse'.$resourceTable.$row['catid'].'"><ul>';
            $insideUl = 1;
        }
        if ($resourceTable == 'site_plugins') $class = $row['disabled'] ? ' class="disabledPlugin"' : '';
        $lockIcon = renderLockIcon($resourceTable, $row['id']);
        $output .= '<li class="eltree">'.$lockIcon.'<span'.$class.'><a href="index.php?id='.$row['id'].'&amp;a='.$action.'" title="'.strip_tags($row['description']).'" target="main" class="context-menu" data-type="'.$resourceTable.'" data-id="'.$row['id'].'"><span class="elementname">'.$row['name'].'</span><small> (' . $row['id'] . ')</small></a>
          <a class="ext-ico" href="#" title="Open in new window" onclick="window.open(\'index.php?id='.$row['id'].'&a='.$action.'\',\'gener\',\'width=800,height=600,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')"> <small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>'.($modx_textdir ? '&rlm;' : '').'</span>';
        
        $output .= $row['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
        $output .= '</li>';
        $preCat = $row['category'];
    }
    $output .= $insideUl? '</ul></div></div>': '';
    $output .= '</div>';
    $output .= '

<script>
  initQuicksearch(\'tree_'.$resourceTable.'_search\', \'tree_'.$resourceTable.'\');
  jQuery(\'#tree_'.$resourceTable.'_search\').on(\'focus\', function () {
    searchFieldCache = elementsInTreeParams.cat_collapsed;
    jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("collapsed");
    jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').addClass("no-events");
    jQuery(\'.'.$resourceTable.'\').collapse(\'show\');
  }).on(\'blur\', function () {
    setRememberCollapsedCategories(searchFieldCache);
    jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("no-events");
  });
</script>';
    return $output;
}

// end createElementsList function

// createModulesList function

function createModulesList($resourceTable,$action,$tablePre,$nameField = 'name') {

    global $modx, $_lang;
    
    $output  = '
        <form class="filterElements-form filterElements-form--eit" style="margin-top: 0;">
          <input class="form-control" type="text" placeholder="Type here to filter list" id="tree_'.$resourceTable.'_search">
        </form>';
        
    $output .= '<div class="panel-group"><div class="panel panel-default" id="tree_'.$resourceTable.'">';

    if ($_SESSION['mgrRole'] != 1) {
        $rs = $modx->db->query('SELECT sm.id, sm.name, sm.description, sm.category, sm.disabled, cats.category AS catname, cats.id AS catid, mg.member
        FROM ' . $modx->getFullTableName('site_modules') . ' AS sm
        LEFT JOIN ' . $modx->getFullTableName('site_module_access') . ' AS sma ON sma.module = sm.id
        LEFT JOIN ' . $modx->getFullTableName('member_groups') . ' AS mg ON sma.usergroup = mg.user_group
        LEFT JOIN ' . $modx->getFullTableName('categories') . ' AS cats ON sm.category = cats.id
        WHERE (mg.member IS NULL OR mg.member = ' . $modx->getLoginUserID() . ') AND sm.disabled != 1 AND sm.locked != 1
        ORDER BY 5,1');
    } 
    
    else {
        $rs = $modx->db->query('SELECT sm.id, sm.name, sm.description, sm.category, sm.disabled, cats.category AS catname, cats.id AS catid
        FROM ' . $modx->getFullTableName('site_modules') . ' AS sm
        LEFT JOIN ' . $modx->getFullTableName('categories') . ' AS cats ON sm.category = cats.id
        WHERE sm.disabled != 1
        ORDER BY 5,1');
    }
    
    $limit = $modx->db->getRecordCount($rs);
    
    if($limit<1){
        return '';
    }
    
    $preCat   = '';
    $insideUl = 0;
    
    for($i=0; $i<$limit; $i++) {
        $row = $modx->db->getRow($rs);
        if($row['catid'] > 0) {
            $row['catid'] = stripslashes($row['catid']);
        } else {
            $row['catname'] = $_lang["no_category"];
        }
        if ($preCat !== $row['category']) {
            $output .= $insideUl? '</div>': '';
            $row['catid'] = intval($row['catid']);
            $output .= '<div class="panel-heading"><span class="panel-title"><a class="accordion-toggle" id="toggle'.$resourceTable.$row['catid'].'" href="#collapse'.$resourceTable.$row['catid'].'" data-cattype="'.$resourceTable.'" data-catid="'.$row['catid'].'" title="Click to toggle collapse. Shift+Click to toggle all."> '.$row['catname'].'</a></span></div><div class="panel-collapse in '.$resourceTable.'"  id="collapse'.$resourceTable.$row['category'].'"><ul>';
            $insideUl = 1;
        }
        $output .= '<li class="eltree"><span><a href="index.php?id='.$row['id'].'&amp;a='.$action.'" title="'.strip_tags($row['description']).'" target="main" class="context-menu" data-type="'.$resourceTable.'" data-id="'.$row['id'].'"><span class="elementname">'.$row['name'].'</span><small> (' . $row['id'] . ')</small></a>
          <a class="ext-ico" href="#" title="Open in new window" onclick="window.open(\'index.php?id='.$row['id'].'&a='.$action.'\',\'gener\',\'width=800,height=600,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')"> <small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>'.($modx_textdir ? '&rlm;' : '').'</span>';
        $output .= $row['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
        $output .= '</li>';
        $preCat  = $row['category'];
    }
    $output .= $insideUl? '</ul></div></div>': '';
    $output .= '</div>';
    $output .= '

<script>
  initQuicksearch(\'tree_'.$resourceTable.'_search\', \'tree_'.$resourceTable.'\');
  jQuery(\'#tree_'.$resourceTable.'_search\').on(\'focus\', function () {
    searchFieldCache = elementsInTreeParams.cat_collapsed;
    jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').addClass("no-events");
    jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("collapsed");
    jQuery(\'.'.$resourceTable.'\').collapse(\'show\');
  }).on(\'blur\', function () {
    jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("no-events");
    setRememberCollapsedCategories(searchFieldCache);
  });
</script>';
    return $output;
}

// end createModulesList function

function hasAnyPermission() {
	global $modx;
	$_ = explode(',', 'edit_template,edit_snippet,edit_chunk,edit_plugin,exec_module');
	foreach($_ as $v) {
		if($modx->hasPermission($v)) return true;
	}
	return false;
}