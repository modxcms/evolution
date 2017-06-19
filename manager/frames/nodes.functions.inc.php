<?php
if(IN_MANAGER_MODE!='true') die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');

function makeHTML($indent,$parent,$expandAll,$theme) {
    global $modx;
    global $icons, $iconsPrivate, $_style;
    global $output, $_lang, $opened, $opened2, $closed2; //added global vars
    global $modx_textdir;

    $pad = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

    // setup spacer
    $spacer = '';
    for ($i = 1; $i <= $indent; $i++){
        $spacer .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }

    // manage order-by
    if (!isset($_SESSION['tree_sortby']) && !isset($_SESSION['tree_sortdir'])) {
        // This is the first startup, set default sort order
        $_SESSION['tree_sortby'] = 'menuindex';
        $_SESSION['tree_sortdir'] = 'ASC';
    }

    switch($_SESSION['tree_sortby']) {
        case 'createdon':
        case 'editedon':
        case 'publishedon':
        case 'pub_date':
        case 'unpub_date':
            $sortby = sprintf('CASE WHEN %s IS NULL THEN 1 ELSE 0 END, %s', $_SESSION['tree_sortby'], $_SESSION['tree_sortby']);
            break;
        default:
            $sortby = $_SESSION['tree_sortby'];
    };

    $orderby = $modx->db->escape($sortby.' '.$_SESSION['tree_sortdir']);

    // Folder sorting gets special setup ;) Add menuindex and pagetitle
    if($_SESSION['tree_sortby'] == 'isfolder') $orderby .= ', menuindex ASC, pagetitle';

    $tblsc = $modx->getFullTableName('site_content');
    $tbldg = $modx->getFullTableName('document_groups');
    $tblst = $modx->getFullTableName('site_templates');
    // get document groups for current user
    $docgrp = (isset($_SESSION['mgrDocgroups']) && is_array($_SESSION['mgrDocgroups'])) ? implode(',',$_SESSION['mgrDocgroups']) : '';
    $showProtected= false;
    if (isset ($modx->config['tree_show_protected'])) {
        $showProtected= (boolean) $modx->config['tree_show_protected'];
    }
    $mgrRole= (isset ($_SESSION['mgrRole']) && (string) $_SESSION['mgrRole']==='1') ? '1' : '0';
    if ($showProtected == false) {
        $access = "AND (1={$mgrRole} OR sc.privatemgr=0".
                  (!$docgrp ? ')':" OR dg.document_group IN ({$docgrp}))");
    } else {
        $access = '';
    }
    $docgrp_cond = $docgrp ? "OR dg.document_group IN ({$docgrp})" : '';
    $field = "DISTINCT sc.id, pagetitle, longtitle, menutitle, parent, isfolder, published, pub_date, unpub_date, richtext, searchable, cacheable, deleted, type, template, templatename, menuindex, donthit, hidemenu, alias, contentType, privateweb, privatemgr,
        MAX(IF(1={$mgrRole} OR sc.privatemgr=0 {$docgrp_cond}, 1, 0)) AS hasAccess";
    $from  = "{$tblsc} AS sc LEFT JOIN {$tbldg} dg on dg.document = sc.id LEFT JOIN {$tblst} st on st.id = sc.template";
    $where = "(parent={$parent}) {$access} GROUP BY sc.id";
    $result = $modx->db->select($field,$from,$where,$orderby);
    if($modx->db->getRecordCount($result)==0) {
        $output .= sprintf('<div>%s%s<img align="absmiddle" src="%s">&nbsp;<span class="emptyNode">%s</span></div>',$spacer,$pad,$_style['tree_deletedpage'],$_lang['empty_folder']);
    }

    $nodeNameSource = $_SESSION['tree_nodename'] == 'default' ? $modx->config['resource_tree_node_name'] : $_SESSION['tree_nodename'];
    while($row = $modx->db->getRow($result))
    {
        extract($row);
        
        $nodetitle      = getNodeTitle($nodeNameSource,$row);
        $nodetitle_esc  = addslashes($nodetitle);
        $protectedClass = $hasAccess==0 ? ' protectedNode' : '';
        
        if($deleted==1)       $nodetitleDisplay = sprintf('<span class="deletedNode">%s</span>'    ,$nodetitle);
        elseif($published==0) $nodetitleDisplay = sprintf('<span class="unpublishedNode">%s</span>',$nodetitle);
        elseif($hidemenu==1)  $nodetitleDisplay = sprintf('<span class="notInMenuNode%s">%s</span>',$protectedClass,$nodetitle);
        else                  $nodetitleDisplay = sprintf('<span class="publishedNode%s">%s</span>',$protectedClass,$nodetitle);
        
        $weblinkDisplay = $type=='reference' ? sprintf('&nbsp;<img src="%s">',$_style['tree_linkgo']) : '' ;
        $pageIdDisplay = '<small>('.($modx_textdir ? '&rlm;':'').$id.')</small>';
        
        // Prepare displaying user-locks
        $lockedByUser = '';
        $rowLock = $modx->elementIsLocked(7, $id, true);
        if($rowLock && $modx->hasPermission('display_locks')) {
            if($rowLock['sid'] == $modx->sid) {
                $title = $modx->parseText($_lang["lock_element_editing"], array('element_type'=>$_lang["lock_element_type_7"], 'lasthit_df'=>$rowLock['lasthit_df']));
                $lockedByUser = '<span title="'.$title.'" class="editResource" style="cursor:context-menu;"><img src="'.$_style['icons_preview_resource'].'" /></span>&nbsp;';
            } else {
                $title = $modx->parseText($_lang["lock_element_locked_by"], array('element_type'=>$_lang["lock_element_type_7"], 'username'=>$rowLock['username'], 'lasthit_df'=>$rowLock['lasthit_df']));
                if($modx->hasPermission('remove_locks')) {
                    $lockedByUser = '<a href="#" onclick="unlockElement(7, '.$id.', this);return false;" title="'.$title.'" class="lockedResource"><img src="'.$_style['icons_secured'].'" /></a>';
                } else {
                    $lockedByUser = '<span title="'.$title.'" class="lockedResource" style="cursor:context-menu;"><img src="'.$_style['icons_secured'].'" /></span>';
                }
            }
        }
        
        $url = $modx->makeUrl($id);

        $alt = '';
        if(isDateNode($nodeNameSource)) $alt = $_lang['pagetitle']  .': '.$pagetitle.'[+lf+]';
        $alt.= $_lang['resource_opt_menu_title']          .': '.$menutitle;
        $alt.= '[+lf+]'.$_lang['resource_opt_menu_index'] .': '.$menuindex;
        $alt.= '[+lf+]'.$_lang['alias']                   .': '.(!empty($alias) ? $alias : '-');
        $alt.= '[+lf+]'.$_lang['template']                .': '.$templatename;
        $alt.= '[+lf+]'.$_lang['publish_date']            .': '.$modx->toDateFormat($pub_date);
        $alt.= '[+lf+]'.$_lang['unpublish_date']          .': '.$modx->toDateFormat($unpub_date);
        $alt.= '[+lf+]'.$_lang['page_data_web_access']    .': '.($privateweb ? $_lang['private']:$_lang['public']);
        $alt.= '[+lf+]'.$_lang['page_data_mgr_access']    .': '.($privatemgr ? $_lang['private']:$_lang['public']);
        $alt.= '[+lf+]'.$_lang['resource_opt_richtext']   .': '.($richtext==0   ? $_lang['no'] : $_lang['yes']);
        $alt.= '[+lf+]'.$_lang['page_data_searchable']    .': '.($searchable==0 ? $_lang['no'] : $_lang['yes']);
        $alt.= '[+lf+]'.$_lang['page_data_cacheable']     .': '.($cacheable==0  ? $_lang['no'] : $_lang['yes']);
        $alt = $modx->htmlspecialchars($alt);
        $alt = str_replace('[+lf+]', ' &#13;', $alt);   // replace line-breaks with empty space as fall-back

        $data = array('id' => $id, 'pagetitle' => $pagetitle, 'longtitle' => $longtitle, 'menutitle' => $menutitle,'parent' =>$parent,
            'isfolder' =>$isfolder,'published' =>$published,'deleted' =>$deleted,'type' =>$type,'menuindex' =>$menuindex,
            'donthit' =>$donthit,'hidemenu' =>$hidemenu,'alias' =>$alias,'contenttype' =>$contentType,'privateweb' =>$privateweb,
            'privatemgr' =>$privatemgr,'hasAccess' => $hasAccess, 'template' => $template,
            'nodetitle' => $nodetitle, 'spacer' => $spacer, 'pad' => $pad, 'url' => $url, 'alt' => $alt,
            'nodetitleDisplay' => $nodetitleDisplay,'weblinkDisplay' => $weblinkDisplay,'pageIdDisplay' => $pageIdDisplay,
            'lockedByUser'=>$lockedByUser
        );
        // invoke OnManagerNodePrerender event
        
        $evtOut = $modx->invokeEvent('OnManagerNodePrerender',$data);
        if (is_array($evtOut)) $evtOut = implode("\n", $evtOut);
        
        $node = '';
        $ph = $data;
        $ph['nodetitle_esc'] = $nodetitle_esc;
        $ph['indent']        = $indent+1;
        $ph['expandAll']     = $expandAll;
        
        if (!$isfolder)
        {
            switch($id) {
                case $modx->config['site_start']            : $icon = $_style['tree_page_home']; break;
                case $modx->config['error_page']            : $icon = $_style['tree_page_404']; break;
                case $modx->config['site_unavailable_page'] : $icon = $_style['tree_page_hourglass']; break;
                case $modx->config['unauthorized_page']     : $icon = $_style['tree_page_info']; break;
                default:
                    if ($privateweb||$privatemgr) {
                        if (isset($iconsPrivate[$contentType])) $icon = $iconsPrivate[$contentType];
                        else                                    $icon = $_style['tree_page_secure'];
                    } elseif (isset($icons[$contentType]))      $icon = $icons[$contentType];
                    else                                        $icon = $_style['tree_page'];
            }
            $ph['icon'] = $icon;
            $tpl = getTplSingleNode();
            $node = $modx->parseText($tpl,$ph);
            $node = $modx->parseText($node,$_lang,'[%','%]');
        }
        else
        {
            $isPrivate = ($privateweb==1||$privatemgr==1) ? '1' : '0';
            $ph['isPrivate'] = $isPrivate;
            // expandAll: two type for partial expansion
            if ($expandAll ==1 || ($expandAll == 2 && in_array($id, $opened)))
            {
                if ($expandAll == 1) $opened2[] = $id;
                $tpl = getTplOpenFolderNode();
                $ph['src'] = $isPrivate ? $_style['tree_folderopen_secure'] : $_style['tree_folderopen'];
                $node = $modx->parseText($tpl,$ph);
                $node = $modx->parseText($node,$_lang, '[%','%]');
                $node = $modx->parseText($node,$_style,'[&','&]');
                $output .= $node;
                makeHTML($indent+1,$id,$expandAll,$theme);
                $node = '</div></div>';
            }
            else
            {
                $tpl = getTplClosedFolderNode();
                $ph['src'] = $isPrivate ? $_style['tree_folder_secure'] : $_style['tree_folder'];
                $node = $modx->parseText($tpl,$ph);
                $node = $modx->parseText($node,$_lang, '[%','%]');
                $node = $modx->parseText($node,$_style,'[&','&]');
                $closed2[] = $id;
            }
        }
        $node = $evtOut.$node;
        
        // invoke OnManagerNodeRender event
        $data['node'] = $node;
        $evtOut = $modx->invokeEvent('OnManagerNodeRender',$data);
        if (is_array($evtOut)) $evtOut = implode("\n", $evtOut);
        if ($evtOut != '') $node = $evtOut;

        $output .= $node;
        // store vars in Javascript
        $scr = '';
        if ($expandAll == 1) {
            $scr .= '<script type="text/javascript"> ';
            foreach ($opened2 as $item) {
                 $scr .= sprintf('parent.openedArray[%d] = 1; ', $item);
            }
            $scr .= '</script> ';
        } elseif ($expandAll == 0) {
            $scr .= '<script type="text/javascript"> ';
            foreach ($closed2 as $item) {
                 $scr .= sprintf('parent.openedArray[%d] = 0; ', $item);
            }
            $scr .= '</script> ';
        }
        $output = $scr . $output;
    }
}

function getIconInfo($_style) {
    if(!isset($_style['tree_page_gif'])) $_style['tree_page_gif'] = $_style['tree_page'];
    if(!isset($_style['tree_page_jpg'])) $_style['tree_page_jpg'] = $_style['tree_page'];
    if(!isset($_style['tree_page_png'])) $_style['tree_page_png'] = $_style['tree_page'];
    $icons = array(
        'text/html'                => $_style['tree_page_html'],
        'text/plain'               => $_style['tree_page'],
        'text/xml'                 => $_style['tree_page_xml'],
        'text/css'                 => $_style['tree_page_css'],
        'text/javascript'          => $_style['tree_page_js'],
        'application/rss+xml'      => $_style['tree_page_rss'],
        'application/pdf'          => $_style['tree_page_pdf'],
        'application/vnd.ms-word'  => $_style['tree_page_word'],
        'application/vnd.ms-excel' => $_style['tree_page_excel'],
        'image/gif'                => $_style['tree_page_gif'],
        'image/jpg'                => $_style['tree_page_jpg'],
        'image/png'                => $_style['tree_page_png']
    );
    return $icons;
}

function getPrivateIconInfo($_style) {
    if(!isset($_style['tree_page_gif_secure'])) $_style['tree_page_gif_secure'] = $_style['tree_page_secure'];
    if(!isset($_style['tree_page_jpg_secure'])) $_style['tree_page_jpg_secure'] = $_style['tree_page_secure'];
    if(!isset($_style['tree_page_png_secure'])) $_style['tree_page_png_secure'] = $_style['tree_page_secure'];
    $iconsPrivate = array(
        'text/html'                => $_style['tree_page_html_secure'],
        'text/plain'               => $_style['tree_page_secure'],
        'text/xml'                 => $_style['tree_page_xml_secure'],
        'text/css'                 => $_style['tree_page_css_secure'],
        'text/javascript'          => $_style['tree_page_js_secure'],
        'application/rss+xml'      => $_style['tree_page_rss_secure'],
        'application/pdf'          => $_style['tree_page_pdf_secure'],
        'application/vnd.ms-word'  => $_style['tree_page_word_secure'],
        'application/vnd.ms-excel' => $_style['tree_page_excel_secure'],
        'image/gif'                => $_style['tree_page_gif_secure'],
        'image/jpg'                => $_style['tree_page_jpg_secure'],
        'image/png'                => $_style['tree_page_png_secure']
    );
    return $iconsPrivate;
}

function getNodeTitle($nodeNameSource,$row) {
    global $modx;
    
    extract($row);
    switch($nodeNameSource)
    {
        case 'menutitle':
            $nodetitle = $menutitle ? $menutitle : $pagetitle;
            break;
        case 'alias':
            $nodetitle = $alias ? $alias : $id;
            if(strpos($alias, '.') === false)
            {
                if($isfolder!=1 || $modx->config['make_folders']!=1)
                    $nodetitle .= $modx->config['friendly_url_suffix'];
            }
            $nodetitle = $modx->config['friendly_url_prefix'] . $nodetitle;
            break;
        case 'pagetitle':
            $nodetitle = $pagetitle;
            break;
        case 'longtitle':
            $nodetitle = $longtitle ? $longtitle : $pagetitle;
            break;
        case 'createdon':
        case 'editedon':
        case 'publishedon':
        case 'pub_date':
        case 'unpub_date':
            $doc = $modx->getDocumentObject('id',$id);
            $date = $doc[$nodeNameSource];
            if(!empty($date)) $nodetitle = $modx->toDateFormat($date);
            else              $nodetitle = '- - -';
            break;
        default:
            $nodetitle = $pagetitle;
    }
    $nodetitle = $modx->htmlspecialchars(str_replace(array("\r\n", "\n", "\r"), ' ', $nodetitle), ENT_COMPAT);
    return $nodetitle;
}

function isDateNode($nodeNameSource) {
    switch($nodeNameSource) {
        case 'createdon':
        case 'editedon':
        case 'publishedon':
        case 'pub_date':
        case 'unpub_date':
            return true;
        default:
            return false;
    }
}

function getTplSingleNode() {
    return '<div
    id="node[+id+]"
    p="[+parent+]"
    >[+spacer+][+pad+]<img
        id="p[+id+]"
        align="absmiddle"
        title="[%click_to_context%]"
        src="[+icon+]"
        onclick="showPopup([+id+],\'[+nodetitle_esc+]\',[+published+],[+deleted+],[+isfolder+],event);return false;"
        oncontextmenu="this.onclick(event);return false;"
        onmouseover="setCNS(this, 1)"
        onmouseout="setCNS(this, 0)"
        onmousedown="itemToChange=[+id+]; selectedObjectName=\'[+nodetitle_esc+]\'; selectedObjectDeleted=[+deleted+]; selectedObjectUrl=\'[+url+]\'"
    />&nbsp;[+lockedByUser+]<span
        p="[+parent+]"
        onclick="treeAction(event,[+id+],\'[+nodetitle_esc+]\'); setSelected(this);"
        onmouseover="setHoverClass(this,1);"
        onmouseout="setHoverClass(this, 0);"
        class="treeNode"
        onmousedown="itemToChange=[+id+]; selectedObjectName=\'[+nodetitle_esc+]\'; selectedObjectDeleted=[+deleted+]; selectedObjectUrl=\'[+url+]\';"
        oncontextmenu="document.getElementById(\'p[+id+]\').onclick(event);return false;"
        title="[+alt+]">[+nodetitleDisplay+][+weblinkDisplay+]</span>[+pageIdDisplay+]</div>';
}

function getTplOpenFolderNode() {
    return '<div
    id="node[+id+]"
    p="[+parent+]"
    >[+spacer+]<img
        id="s[+id+]"
        align="absmiddle"
        style="margin-left:4px;"
        src="[&tree_minusnode&]"
        onclick="toggleNode(this,[+indent+],[+id+],[+expandAll+],[+isPrivate+]); return false;"
        oncontextmenu="this.onclick(event); return false;"
        /><img
        id="f[+id+]"
        align="absmiddle"
        title="[%click_to_context%]"
        style="margin-top:-2px;"
        src="[+src+]"
        onclick="showPopup([+id+],\'[+nodetitle_esc+]\',[+published+],[+deleted+],[+isfolder+],event);return false;"
        oncontextmenu="this.onclick(event);return false;"
        onmouseover="setCNS(this, 1)"
        onmouseout="setCNS(this, 0)"
        onmousedown="itemToChange=[+id+]; selectedObjectName=\'[+nodetitle_esc+]\'; selectedObjectDeleted=[+deleted+]; selectedObjectUrl=\'[+url+]\';"
        />&nbsp;[+lockedByUser+]<span
        onclick="treeAction(event,[+id+],\'[+nodetitle_esc+]\'); setSelected(this);"
        onmouseover="setHoverClass(this, 1);"
        onmouseout="setHoverClass(this, 0);"
        class="treeNode"
        onmousedown="itemToChange=[+id+]; selectedObjectName=\'[+nodetitle_esc+]\'; selectedObjectDeleted=[+deleted+]; selectedObjectUrl=\'[+url+]\';"
        oncontextmenu="document.getElementById(\'f[+id+]\').onclick(event);return false;"
        title="[+alt+]">[+nodetitleDisplay+][+weblinkDisplay+]</span>[+pageIdDisplay+]<div style="display:block">';
}

function getTplClosedFolderNode() {
    return '<div
    id="node[+id+]"
    p="[+parent+]"
    >[+spacer+]<img
        id="s[+id+]"
        align="absmiddle"
        style="margin-left:4px;"
        src="[&tree_plusnode&]"
        onclick="toggleNode(this,[+indent+],[+id+],[+expandAll+],[+isPrivate+]); return false;"
        oncontextmenu="this.onclick(event); return false;"
        /><img
        id="f[+id+]"
        title="[%click_to_context%]"
        align="absmiddle"
        style="margin-top:-2px;"
        src="[+src+]"
        onclick="showPopup([+id+],\'[+nodetitle_esc+]\',[+published+],[+deleted+],[+isfolder+],event);return false;"
        oncontextmenu="this.onclick(event);return false;"
        onmouseover="setCNS(this, 1)"
        onmouseout="setCNS(this, 0)"
        onmousedown="itemToChange=[+id+]; selectedObjectName=\'[+nodetitle_esc+]\'; selectedObjectDeleted=[+deleted+]; selectedObjectUrl=\'[+url+]\';"
        />&nbsp;[+lockedByUser+]<span
        onclick="treeAction(event,[+id+],\'[+nodetitle_esc+]\'); setSelected(this);"
        onmouseover="setHoverClass(this, 1);"
        onmouseout="setHoverClass(this, 0);"
        class="treeNode"
        onmousedown="itemToChange=[+id+]; selectedObjectName=\'[+nodetitle_esc+]\'; selectedObjectDeleted=[+deleted+]; selectedObjectUrl=\'[+url+]\';"
        oncontextmenu="document.getElementById(\'f[+id+]\').onclick(event);return false;"
        title="[+alt+]">[+nodetitleDisplay+][+weblinkDisplay+]</span> [+pageIdDisplay+]<div style="display:none"></div></div>';
}