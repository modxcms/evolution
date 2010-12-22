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
        'application/rss+xml' => $_style["tree_page_rss"],
        'application/pdf' => $_style["tree_page_pdf"],
        'application/vnd.ms-word' => $_style["tree_page_word"],
        'application/vnd.ms-excel' => $_style["tree_page_excel"],
        'text/css' => $_style["tree_page_css"],
        'text/html' => $_style["tree_page_html"],
        'text/plain' => $_style["tree_page"],
        'text/xml' => $_style["tree_page_xml"],
        'text/javascript' => $_style["tree_page_js"],
        'image/gif' => $_style["tree_page_gif"],
        'image/jpg' => $_style["tree_page_jpg"],
        'image/png' => $_style["tree_page_png"]
    );
	$iconsPrivate = array(
	    'application/rss+xml' => $_style["tree_page_rss_secure"],
	    'application/pdf' => $_style["tree_page_pdf_secure"],
	    'application/vnd.ms-word' => $_style["tree_page_word_secure"],
	    'application/vnd.ms-excel' => $_style["tree_page_excel_secure"],
	    'text/css' => $_style["tree_page_css_secure"],
	    'text/html' => $_style["tree_page_html_secure"],
	    'text/plain' => $_style["tree_page_secure"],
	    'text/xml' => $_style["tree_page_xml_secure"],
	    'text/javascript' => $_style["tree_page_js_secure"],
	    'image/gif' => $_style["tree_page_gif_secure"],
	    'image/jpg' => $_style["tree_page_jpg_secure"],
	    'image/png' => $_style["tree_page_png_secure"]
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
        global $icons, $iconsPrivate, $theme, $_style;
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
                MAX(IF(1={$mgrRole} OR sc.privatemgr=0" . (!$docgrp ? "":" OR dg.document_group IN ({$docgrp})") . ", 1, 0)) AS has_access
                FROM {$tblsc} AS sc
                LEFT JOIN {$tbldg} dg on dg.document = sc.id
                WHERE (parent={$parent})
                $access
                GROUP BY sc.id
                ORDER BY {$orderby}";
        $result = mysql_query($sql, $modxDBConn);
        if(mysql_num_rows($result)==0) {
            $output .= '<div style="white-space: nowrap;">'.$spacer.$pad.'<img align="absmiddle" src="'.$_style["tree_deletedpage"].'">&nbsp;<span class="emptyNode">'.$_lang['empty_folder'].'</span></div>';
        }

		// Make sure to pass in the $modx_textdir variable to the node builder
		global $modx_textdir;

        while(list($id,$pagetitle,$parent,$isfolder,$published,$deleted,$type,$menuindex,$hidemenu,$alias,$contenttype,$privateweb,$privatemgr,$hasAccess) = mysql_fetch_row($result))
        {
            $pagetitle = htmlspecialchars(str_replace(array("\r\n", "\n", "\r"), '', $pagetitle));
            $protectedClass = $hasAccess==0 ? ' protectedNode' : '';
            $pagetitleDisplay = $published==0 ? "<span class=\"unpublishedNode\">$pagetitle</span>" : ($hidemenu==1 ? "<span class=\"notInMenuNode$protectedClass\">$pagetitle</span>":"<span class=\"publishedNode$protectedClass\">$pagetitle</span>");
            $pagetitleDisplay = $deleted==1 ? "<span class=\"deletedNode\">$pagetitle</span>" : $pagetitleDisplay;
            $weblinkDisplay = $type=="reference" ? '&nbsp;<img src="'.$_style["tree_linkgo"].'">' : '' ;
			$pageIdDisplay = '<small>('.($modx_textdir ? '&rlm;':'').$id.')</small>';
			$url = $modx->makeUrl($id);

            $alt = !empty($alias) ? $_lang['alias'].": ".$alias : $_lang['alias'].": -";
            $alt.= " ".$_lang['resource_opt_menu_index'].": ".$menuindex;
            $alt.= " ".$_lang['resource_opt_show_menu'].": ".($hidemenu==1 ? $_lang['no']:$_lang['yes']);
            $alt.= " ".$_lang['page_data_web_access'].": ".($privateweb ? $_lang['private']:$_lang['public']);
            $alt.= " ".$_lang['page_data_mgr_access'].": ".($privatemgr ? $_lang['private']:$_lang['public']);

            if (!$isfolder) {
                $icon = ($privateweb||$privatemgr) ? $_style["tree_page_secure"] : $_style["tree_page"];
				
				if ($privateweb||$privatemgr) {
                    if (isset($iconsPrivate[$contenttype])) {
                        $icon = $iconsPrivate[$contenttype];
                    }
                } else {
                    if (isset($icons[$contenttype])) {
                        $icon = $icons[$contenttype];
                    }
                }
                if($id == $modx->config['site_start'])                $icon = $_style["tree_page_home"];
                elseif($id == $modx->config['error_page'])            $icon = $_style["tree_page_404"];
                elseif($id == $modx->config['site_unavailable_page']) $icon = $_style["tree_page_hourglass"];
                elseif($id == $modx->config['unauthorized_page'])     $icon = $_style["tree_page_info"];
                $output .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.$pad.'<img id="p'.$id.'" align="absmiddle" title="'.$_lang['click_to_context'].'" style="cursor: pointer" src="'.$icon.'" onclick="showPopup('.$id.',\''.addslashes($pagetitle).'\',event);return false;" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\'" />&nbsp;';
                $output .= '<span p="'.$parent.'" onclick="treeAction('.$id.', \''.addslashes($pagetitle).'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\';" oncontextmenu="document.getElementById(\'p'.$id.'\').onclick(event);return false;" title="'.addslashes($alt).'">'.$pagetitleDisplay.$weblinkDisplay.'</span> '.$pageIdDisplay.'</div>';
            }
            else {
                // expandAll: two type for partial expansion
                if ($expandAll ==1 || ($expandAll == 2 && in_array($id, $opened)))
                {
                    if ($expandAll == 1) {
                       array_push($opened2, $id);
                    }
                    if($id == $modx->config['site_start'])                $icon = $_style["tree_page_home"];
                    elseif($id == $modx->config['error_page'])            $icon = $_style["tree_page_404"];
                    elseif($id == $modx->config['site_unavailable_page']) $icon = $_style["tree_page_hourglass"];
                    elseif($id == $modx->config['unauthorized_page'])     $icon = $_style["tree_page_info"];
                    $output .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.'<img id="s'.$id.'" align="absmiddle" style="cursor: pointer" src="'.$_style["tree_minusnode"].'" onclick="toggleNode(this,'.($indent+1).','.$id.',0,'. (($privateweb == 1 || $privatemgr == 1) ? '1' : '0') .'); return false;" oncontextmenu="this.onclick(event); return false;" />&nbsp;<img id="f'.$id.'" align="absmiddle" title="'.$_lang['click_to_context'].'" style="cursor: pointer" src="'.(($privateweb == 1 || $privatemgr == 1) ? $_style["tree_folderopen_secure"] : $_style["tree_folderopen"]).'" onclick="showPopup('.$id.',\''.addslashes($pagetitle).'\',event);return false;" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\';" />&nbsp;';
                    $output .= '<span onclick="treeAction('.$id.', \''.addslashes($pagetitle).'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\';" oncontextmenu="document.getElementById(\'f'.$id.'\').onclick(event);return false;" title="'.addslashes($alt).'">'.$pagetitleDisplay.$weblinkDisplay.'</span> '.$pageIdDisplay.'<div style="display:block">';
                    makeHTML($indent+1,$id,$expandAll,$theme);
                    $output .= '</div></div>';
                }
                else {
                    $output .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.'<img id="s'.$id.'" align="absmiddle" style="cursor: pointer" src="'.$_style["tree_plusnode"].'" onclick="toggleNode(this,'.($indent+1).','.$id.',0,'. (($privateweb == 1 || $privatemgr == 1) ? '1' : '0') .'); return false;" oncontextmenu="this.onclick(event); return false;" />&nbsp;<img id="f'.$id.'" title="'.$_lang['click_to_context'].'" align="absmiddle" style="cursor: pointer" src="'.(($privateweb == 1 || $privatemgr == 1) ? $_style["tree_folder_secure"] : $_style["tree_folder"]).'" onclick="showPopup('.$id.',\''.addslashes($pagetitle).'\',event);return false;" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\';" />&nbsp;';
                   	$output .= '<span onclick="treeAction('.$id.', \''.addslashes($pagetitle).'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\';" oncontextmenu="document.getElementById(\'f'.$id.'\').onclick(event);return false;" title="'.addslashes($alt).'">'.$pagetitleDisplay.$weblinkDisplay.'</span> '.$pageIdDisplay.'<div style="display:none"></div></div>';
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