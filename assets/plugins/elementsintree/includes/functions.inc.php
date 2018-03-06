<?php

if(!defined('MODX_BASE_PATH')) die('What are you doing? Get out of here!');

function renderLockIcon($elmTable, $id)
{
    global $modx;

    switch($elmTable) {
        case 'site_templates': $lockType = 1; break;
        case 'site_tmplvars': $lockType = 2; break;
        case 'site_htmlsnippets': $lockType = 3; break;
        case 'site_snippets': $lockType = 4; break;
        case 'site_plugins': $lockType = 5; break;
        case 'site_modules': $lockType = 6; break;
        default : return '';
    }

    $rowLock = $modx->elementIsLocked($lockType, $id, true);
    $lockedByUser = '';

    if($rowLock && $modx->hasPermission('display_locks')) {
        $lockedByUser = getLockedByUser($lockType,$rowLock,$id);
    }
    return sprintf('<span id="lock%s_%s">%s</span>', $lockType, $id, $lockedByUser);
}

function getLockedByUser($lockType,$rowLock,$id) {
    global $modx,$_lang,$_style;

    $ph = array();
    $ph['element_type'] = $_lang['lock_element_type_'.$lockType];
    $ph['lasthit_df']  = $rowLock['lasthit_df'];

    if($rowLock['sid'] == $modx->sid) {
        $title = $modx->parseText($_lang['lock_element_editing'], $ph);
        $tpl = '<span title="%s" class="editResource" style="cursor:context-menu;"><i class="%s"></i></span>&nbsp;';
        $params = array($title, $_style['actions_preview']);
        return vsprintf($tpl, $params);
    } else {
        $ph['username'] = $rowLock['username'];
        $title = $modx->parseText($_lang['lock_element_locked_by'], $ph);
            if($modx->hasPermission('remove_locks')) {
            $tpl = '<a href="#" onclick="unlockElement(%s,%s,this);return false;" title="%s" class="lockedResource">%s </a>';
            $params = array($lockType, $id, $title, $_style['icons_secured']);
            return vsprintf($tpl, $params);
            } else {
            $tpl = '<span title="%s" class="lockedResource" style="cursor:context-menu;">%s </span>';
            $params = array($title, $_style['icons_secured']);
            return vsprintf($tpl, $params);
            }
        }
    }

// create elements list function
function createElementsList($elmTable,$action,$nameField = 'name') {
    global $modx, $_lang, $modx_textdir;

    $field = array();
    if    ($elmTable == 'site_htmlsnippets' || $elmTable == 'site_snippets' || $elmTable == 'site_plugins')  $field['disabled'] = "[+prefix+]{$elmTable}.disabled";
    elseif($elmTable == 'site_tmplvars') $field['caption']  = '[+prefix+]site_tmplvars.caption';

    $field['name']        = "[+prefix+]{$elmTable}.{$nameField}";
    $field['id']          = "[+prefix+]{$elmTable}.id";
    $field['description'] = "[+prefix+]{$elmTable}.description";
    $field['locked']      = "[+prefix+]{$elmTable}.locked";
    $field['category']    = sprintf("if(isnull([+prefix+]categories.category),'%s',[+prefix+]categories.category)", $_lang['no_category']);
    $field['catid']       = '[+prefix+]categories.id';

    $from[] = "[+prefix+]{$elmTable}";
    $from[] = "left join [+prefix+]categories on [+prefix+]{$elmTable}.category=[+prefix+]categories.id";

    $orderby = in_array($elmTable, array('site_tmplvars','site_htmlsnippets','site_snippets','site_plugins')) ? '6,2' : '5,1';

    $rs = $modx->db->select($field,$from,'',$orderby);

    if($modx->db->getRecordCount($rs)<1) return '';

    $output  = '
        <form class="filterElements-form filterElements-form--eit" style="margin-top: 0;">
          <input class="form-control" type="text" placeholder="'.$_lang['element_filter_msg'].'" id="tree_'.$elmTable.'_search">
        </form>';

    $output .= '<div class="panel-group"><div class="panel panel-default" id="tree_'.$elmTable.'">';

    $preCat = '';
    $insideUl = 0;

    while($row = $modx->db->getRow($rs)) {
        $row['category'] = stripslashes($row['category']);
        if ($preCat !== $row['category']) {
            $output .= $insideUl? '</div>': '';
            $row['catid'] = (int)$row['catid'];
            $output .= '<div class="panel-heading"><span class="panel-title"><a class="accordion-toggle" id="toggle'.$elmTable.$row['catid'].'" href="#collapse'.$elmTable.$row['catid'].'" data-cattype="'.$elmTable.'" data-catid="'.$row['catid'].'" title="Click to toggle collapse. Shift+Click to toggle all."> '.$row['category'].'</a></span></div><div class="panel-collapse in '.$elmTable.'"  id="collapse'.$elmTable.$row['catid'].'"><ul>';
            $insideUl = 1;
        }
        $class = !empty($row['disabled']) ? ' class="disabledPlugin"' : '';
        $lockIcon = renderLockIcon($elmTable, $row['id']);
        $output .= '<li class="eltree">'.$lockIcon.'<span'.$class.'><a href="index.php?id='.$row['id'].'&amp;a='.$action.'" title="'.strip_tags($row['description']).'" target="main" class="context-menu" data-type="'.$elmTable.'" data-id="'.$row['id'].'"><span class="elementname">'.$row['name'].'</span><small> (' . $row['id'] . ')</small></a>
          <a class="ext-ico" href="#" title="Open in new window" onclick="window.open(\'index.php?id='.$row['id'].'&a='.$action.'\',\'gener\',\'width=800,height=600,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')"> <small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>'.($modx_textdir ? '&rlm;' : '').'</span>';

        $output .= $row['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
        $output .= '</li>';
        $preCat = $row['category'];
    }
    $output .= $insideUl? '</ul></div></div>': '';
    $output .= '</div>';
    $output .= '

<script>
  initQuicksearch(\'tree_'.$elmTable.'_search\', \'tree_'.$elmTable.'\');
  jQuery(\'#tree_'.$elmTable.'_search\').on(\'focus\', function () {
    searchFieldCache = elementsInTreeParams.cat_collapsed;
    jQuery(\'#tree_'.$elmTable.' .accordion-toggle\').removeClass("collapsed");
    jQuery(\'#tree_'.$elmTable.' .accordion-toggle\').addClass("no-events");
    jQuery(\'.'.$elmTable.'\').collapse(\'show\');
  }).on(\'blur\', function () {
    setRememberCollapsedCategories(searchFieldCache);
    jQuery(\'#tree_'.$elmTable.' .accordion-toggle\').removeClass("no-events");
  });
</script>';
    return $output;
}

// end createElementsList function

// createModulesList function

function createModulesList($action) {

    global $modx, $_lang, $modx_textdir;

    $output  = '
        <form class="filterElements-form filterElements-form--eit" style="margin-top: 0;">
          <input class="form-control" type="text" placeholder="'.$_lang['element_filter_msg'].'" id="tree_site_modules_search">
        </form>';

    $output .= '<div class="panel-group"><div class="panel panel-default" id="tree_site_modules">';

    if ($_SESSION['mgrRole'] != 1) {
        $field = 'sm.id, sm.name, sm.description, sm.category, sm.disabled, cats.category as catname, cats.id as catid, mg.member';
        $from = array();
        $from[] = '[+prefix+]site_modules as sm';
        $from[] = 'LEFT JOIN [+prefix+]site_module_access as sma ON sma.module=sm.id';
        $from[] = 'LEFT JOIN [+prefix+]member_groups as mg ON sma.usergroup = mg.user_group';
        $from[] = 'LEFT JOIN [+prefix+]categories as cats ON sm.category = cats.id';
        $where  = sprintf( '(mg.member IS NULL OR mg.member=%s) AND sm.disabled!=1 AND sm.locked!=1', $modx->getLoginUserID() );
        $rs = $modx->db->select($field,$from,$where,'5,1');
    }

    else {
        $field = 'sm.id, sm.name, sm.description, sm.category, sm.disabled, cats.category as catname, cats.id as catid';
        $from = array();
        $from[] = '[+prefix+]site_modules as sm';
        $from[] = 'LEFT JOIN [+prefix+]categories as cats ON sm.category=cats.id';
        $rs = $modx->db->select($field,$from,'sm.disabled!=1','5,1');
    }

    if($modx->db->getRecordCount($rs)<1){
        return '';
    }

    $preCat   = '';
    $insideUl = 0;

    while($row = $modx->db->getRow($rs)) {

        if($row['catid'] > 0) $row['catid']   = stripslashes($row['catid']);
        else                  $row['catname'] = $_lang['no_category'];
        $row['action'] = $action;

        if ($preCat !== $row['category']) {
            $output .= $insideUl? '</div>': '';
            $row['catid'] = (int)$row['catid'];
            $output .= $modx->parseText('<div class="panel-heading"><span class="panel-title"><a class="accordion-toggle" id="togglesite_modules[+catid+]" href="#collapsesite_modules[+catid+]" data-cattype="site_modules" data-catid="[+catid+]" title="Click to toggle collapse. Shift+Click to toggle all."> [+catname+]</a></span></div><div class="panel-collapse in site_modules"  id="collapsesite_modules[+category+]"><ul>', $row);
            $insideUl = 1;
        }
        $row['window.open'] = $modx->parseText("'index.php?id=[+id+]&a=[+action+]','gener','width=800,height=600,top='+((screen.height-600)/2)+',left='+((screen.width-800)/2)+',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no'", $row);
        $row['textdir'] = $modx_textdir ? '&rlm;' : '';
        $row['description'] = strip_tags($row['description']);
        $row['locked'] = $row['locked'] ? ' <em>('.$_lang['locked'].')</em>' : '';
        $output .= $modx->parseText('<li class="eltree"><span><a href="index.php?id=[+id+]&amp;a=[+action+]" title="[+description+]" target="main" class="context-menu" data-type="site_modules" data-id="[+id+]"><span class="elementname">[+name+]</span><small> ([+id+])</small></a>
          <a class="ext-ico" href="#" title="Open in new window" onclick="window.open([+window.open+])"> <small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>[+textdir+]</span>[+locked+]</li>', $row);
        $preCat  = $row['category'];
    }
    $output .= $insideUl? '</ul></div></div>': '';
    $output .= '</div>';
    $output .= "

<script>
  initQuicksearch('tree_site_modules_search', 'tree_site_modules');
  jQuery('#tree_site_modules_search').on('focus', function () {
    searchFieldCache = elementsInTreeParams.cat_collapsed;
    jQuery('#tree_site_modules .accordion-toggle').addClass('no-events');
    jQuery('#tree_site_modules .accordion-toggle').removeClass('collapsed');
    jQuery('.site_modules').collapse('show');
  }).on('blur', function () {
    jQuery('#tree_site_modules .accordion-toggle').removeClass('no-events');
    setRememberCollapsedCategories(searchFieldCache);
  });
</script>";
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
