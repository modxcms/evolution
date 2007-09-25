<?php
/**
 *  Tree Nodes
 *  Build and return document tree view nodes
 *
 */
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

    // save folderstate
    if (isset($_GET['opened'])) $_SESSION['openedArray'] = $_GET['opened'];
    if (isset($_GET['savestateonly'])) {
        echo 'send some data'; //??
        exit;
    }

    $indent    = $_GET['indent'];
    $parent    = $_GET['parent'];
    $expandAll = $_GET['expandAll'];
    $output    = "";
    $theme = $manager_theme ? "$manager_theme/":"";

    // setup sorting
    if(isset($_REQUEST['tree_sortby'])) {
        $_SESSION['tree_sortby'] = $_REQUEST['tree_sortby'];
    }
    if(isset($_REQUEST['tree_sortdir'])) {
        $_SESSION['tree_sortdir'] = $_REQUEST['tree_sortdir'];
    }

    // icons by content type
    $icons = array(
        'application/pdf' => 'page-pdf',
        'image/gif' => 'page-images',
        'image/jpg' => 'page-images',
        'text/css' => 'page-css',
        'text/html' => 'page-html',
        'text/xml' => 'page-xml',
        'text/javascript' => 'page-js'
    );

    if (isset($_SESSION['openedArray'])) {
            $opened = explode("|", $_SESSION['openedArray']);
    } else {
            $opened = array();
    }
    $opened2 = array();
    $closed2 = array();

    makeHTML($indent,$parent,$expandAll,$theme);
    echo $output;

    // check for deleted documents on reload
    if ($expandAll==2) {
        $sql = "SELECT COUNT(*) FROM $dbase.`".$table_prefix."site_content` WHERE deleted=1";
        $rs = mysql_query($sql);
        $row = mysql_fetch_row($rs);
        $count = $row[0];
        if ($count>0) echo '<span id="binFull"></span>'; // add a special element to let system now that the bin is full
    }

    function makeHTML($indent,$parent,$expandAll,$theme) {
    	global $modx;
        global $icons, $theme, $_style;
        global $modxDBConn, $output, $dbase, $table_prefix, $_lang, $opened, $opened2, $closed2; //added global vars

        $pad = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        // setup spacer
        $spacer = "";
        for ($i = 1; $i <= $indent; $i++){
            $spacer .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }

	if (!isset($_SESSION['tree_sortby']) && !isset($_SESSION['tree_sortdir'])) {
		// This is the first startup, set default sort order
		$_SESSION['tree_sortby'] = 'menuindex';
		$_SESSION['tree_sortdir'] = 'ASC';
	}
	$orderby = $_SESSION['tree_sortby']." ".$_SESSION['tree_sortdir'];

	// Folder sorting gets special setup ;) Add menuindex and pagetitle
	if($_SESSION['tree_sortby'] == 'isfolder') $orderby .= ", menuindex ASC, pagetitle";

        $tblsc = $dbase.".`".$table_prefix."site_content`";
        $tbldg = $dbase.".`".$table_prefix."document_groups`";
        $tbldgn = $dbase.".`".$table_prefix."documentgroup_names`";
        // get document groups for current user
        if($_SESSION['mgrDocgroups']) $docgrp = implode(",",$_SESSION['mgrDocgroups']);
        $showProtected= false;
        if (isset ($modx->config['tree_show_protected'])) {
            $showProtected= (boolean) $modx->config['tree_show_protected'];
        }
        $mgrRole= (isset ($_SESSION['mgrRole']) && (string) $_SESSION['mgrRole']==='1') ? '1' : '0';
        if ($showProtected == false) {
            $access = "AND (1={$mgrRole} OR sc.privatemgr=0".
                      (!$docgrp ? ")":" OR dg.document_group IN ({$docgrp}))");
        }
        $sql = "SELECT DISTINCT sc.id, pagetitle, parent, isfolder, published, deleted, type, menuindex, hidemenu, alias, contentType, privateweb, privatemgr,
                IF(1={$mgrRole} OR sc.privatemgr=0" . (!$docgrp ? "":" OR dg.document_group IN ({$docgrp})") . ", 1, 0) AS has_access
                FROM {$tblsc} AS sc
                LEFT JOIN {$tbldg} dg on dg.document = sc.id
                WHERE (parent={$parent})
                $access
                GROUP BY sc.id
                ORDER BY {$orderby}";
        $result = mysql_query($sql, $modxDBConn);
        if(mysql_num_rows($result)==0) {
            $output .= '<div style="white-space: nowrap;">'.$spacer.$pad.'<img align="absmiddle" src="'.$_style["tree_deletedpage"].'" width="18" height="18">&nbsp;<span class="emptyNode">'.$_lang['empty_folder'].'</span></div>';
        }

        while(list($id,$pagetitle,$parent,$isfolder,$published,$deleted,$type,$menuindex,$hidemenu,$alias,$contenttype,$privateweb,$privatemgr,$hasAccess) = mysql_fetch_row($result))
        {
            $pagetitle = htmlspecialchars($pagetitle);
            $protectedClass = $hasAccess==0 ? ' protectedNode' : '';
            $pagetitleDisplay = $published==0 ? "<span class=\"unpublishedNode\">$pagetitle</span>" : ($hidemenu==1 ? "<span class=\"notInMenuNode$protectedClass\">$pagetitle</span>":"<span class=\"publishedNode$protectedClass\">$pagetitle</span>");
            $pagetitleDisplay = $deleted==1 ? "<span class=\"deletedNode\">$pagetitle</span>" : $pagetitleDisplay;
            $weblinkDisplay = $type=="reference" ? '&nbsp;<img src="'.$_style["tree_linkgo"].'" width="16" height="16">' : '' ;

            $alt = !empty($alias) ? $_lang['alias'].": ".$alias : $_lang['alias'].": - ";
            $alt.= "\n".$_lang['document_opt_menu_index'].": ".$menuindex;
            $alt.= "\n".$_lang['document_opt_show_menu'].": ".($hidemenu==1 ? $_lang['no']:$_lang['yes']);
            $alt.= "\n".$_lang['page_data_web_access'].": ".($privateweb ? $_lang['private']:$_lang['public']);
            $alt.= "\n".$_lang['page_data_mgr_access'].": ".($privatemgr ? $_lang['private']:$_lang['public']);

            if (!$isfolder) {
                $icon='page';
                if($privateweb||$privatemgr) $icon='page-secure';
                else if(isset($icons[$contenttype])) $icon = $icons[$contenttype];
                $output .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.$pad.'<img id="p'.$id.'" align="absmiddle" title="'.$_lang['click_to_context'].'" style="cursor: pointer" src="media/style/'.$theme.'images/tree/'.$icon.'.gif" width="18" height="18" onclick="showPopup('.$id.',\''.addslashes($pagetitle).'\',event);return false;" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.';" />&nbsp;';
                $output .= '<span p="'.$parent.'" onclick="treeAction('.$id.', \''.addslashes($pagetitle).'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.';" oncontextmenu="document.getElementById(\'p'.$id.'\').onclick(event);return false;" title="'.addslashes($alt).'">'.$pagetitleDisplay.$weblinkDisplay.'</span> <small>'.($modx->config['manager_direction'] == 'rtl' ? '&rlm;' : '').'('.$id.')</small></div>';
            }
            else {
                // expandAll: two type for partial expansion
                if ($expandAll ==1 || ($expandAll == 2 && in_array($id, $opened)))
                {
                    if ($expandAll == 1) {
                       array_push($opened2, $id);
                    }
                    $output .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.'<img id="s'.$id.'" align="absmiddle" style="cursor: pointer" src="'.$_style["tree_minusnode"].'" width="18" height="18" onclick="toggleNode(this,'.($indent+1).','.$id.',0,'. (($privateweb == 1 || $privatemgr == 1) ? '1' : '0') .'); return false;" oncontextmenu="this.onclick(event); return false;" />&nbsp;<img id="f'.$id.'" align="absmiddle" title="'.$_lang['click_to_context'].'" style="cursor: pointer" src="'.(($privateweb == 1 || $privatemgr == 1) ? $_style["tree_folderopen_secure"] : $_style["tree_folderopen"]).'" width="18" height="18" onclick="showPopup('.$id.',\''.addslashes($pagetitle).'\',event);return false;" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.';" />&nbsp;';
                    $output .= '<span onclick="treeAction('.$id.', \''.addslashes($pagetitle).'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.';" oncontextmenu="document.getElementById(\'f'.$id.'\').onclick(event);return false;" title="'.addslashes($alt).'">'.$pagetitleDisplay.$weblinkDisplay.'</span> <small>'.($modx->config['manager_direction'] == 'rtl' ? '&rlm;' : '').'('.$id.')</small><div style="display:block">';
                    makeHTML($indent+1,$id,$expandAll,$theme);
                    $output .= '</div></div>';
                }
                else {
                    $output .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.'<img id="s'.$id.'" align="absmiddle" style="cursor: pointer" src="'.$_style["tree_plusnode"].'" width="18" height="18" onclick="toggleNode(this,'.($indent+1).','.$id.',0,'. (($privateweb == 1 || $privatemgr == 1) ? '1' : '0') .'); return false;" oncontextmenu="this.onclick(event); return false;" />&nbsp;<img id="f'.$id.'" title="'.$_lang['click_to_context'].'" align="absmiddle" style="cursor: pointer" src="'.(($privateweb == 1 || $privatemgr == 1) ? $_style["tree_folder_secure"] : $_style["tree_folder"]).'" width="18" height="18" onclick="showPopup('.$id.',\''.addslashes($pagetitle).'\',event);return false;" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.';" />&nbsp;';
                   	$output .= '<span onclick="treeAction('.$id.', \''.addslashes($pagetitle).'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.';" oncontextmenu="document.getElementById(\'f'.$id.'\').onclick(event);return false;" title="'.addslashes($alt).'">'.$pagetitleDisplay.$weblinkDisplay.'</span> <small>'.($modx->config['manager_direction'] == 'rtl' ? '&rlm;' : '').'('.$id.')</small><div style="display:none"></div></div>';
                    array_push($closed2, $id);
                }
            }
            // store vars in Javascript
            if ($expandAll == 1) {
                echo '<script type="text/javascript"> ';
                foreach ($opened2 as $item) {
                         printf("parent.openedArray[%d] = 1; ", $item);
                }
                echo '</script> ';
            } elseif ($expandAll == 0) {
                echo '<script type="text/javascript"> ';
                foreach ($closed2 as $item) {
                         printf("parent.openedArray[%d] = 0; ", $item);
                }
                echo '</script> ';
            }
        }
    }

?>
