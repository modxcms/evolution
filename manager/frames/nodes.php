<?php
/**
 *  Tree Nodes
 *  Build and return document tree view nodes
 *
 */
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

    // save folderstate
    if (isset($_GET['opened'])) $_SESSION['openedArray'] = $_GET['opened'];
    if (isset($_GET['savestateonly'])) {
        echo 'send some data'; //??
        exit;
    }

    $indent    = intval($_GET['indent']);
    $parent    = intval($_GET['parent']);
    $expandAll = intval($_GET['expandAll']);
    $output    = "";
    $theme = $manager_theme ? "$manager_theme/":"";

    // setup sorting
    $sortParams = array('tree_sortby','tree_sortdir','tree_nodename');
    foreach($sortParams as $param)
        if(isset($_REQUEST[$param])) { $_SESSION[$param] = $_REQUEST[$param]; $modx->manager->saveLastUserSetting($param, $_REQUEST[$param]); }

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
        'image/gif' => isset($_style["tree_page_gif"]) ? $_style["tree_page_gif"] : $_style["tree_page"],
        'image/jpg' => isset($_style["tree_page_jpg"]) ? $_style["tree_page_jpg"] :  $_style["tree_page"],
        'image/png' => isset($_style["tree_page_png"]) ? $_style["tree_page_png"] : $_style["tree_page"]
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
        'image/gif' => isset($_style["tree_page_gif_secure"]) ? $_style["tree_page_gif_secure"] : $_style["tree_page_secure"],
        'image/jpg' => isset($_style["tree_page_jpg_secure"]) ? $_style["tree_page_jpg_secure"] : $_style["tree_page_secure"],
        'image/png' => isset($_style["tree_page_png_secure"]) ? $_style["tree_page_png_secure"] : $_style["tree_page_secure"]
    );

    if (isset($_SESSION['openedArray'])) {
            $opened = array_filter(array_map('intval', explode('|', $_SESSION['openedArray'])));
    } else {
            $opened = array();
    }
    $opened2 = array();
    $closed2 = array();

    makeHTML($indent,$parent,$expandAll,$theme);
    echo $output;

    // check for deleted documents on reload
    if ($expandAll==2) {
        $rs = $modx->db->select('COUNT(*)', $modx->getFullTableName('site_content'), 'deleted=1');
        $count = $modx->db->getValue($rs);
        if ($count>0) echo '<span id="binFull"></span>'; // add a special element to let system now that the bin is full
    }

    function makeHTML($indent,$parent,$expandAll,$theme) {
        global $modx;
        global $icons, $iconsPrivate, $_style;
        global $output, $_lang, $opened, $opened2, $closed2; //added global vars

        $pad = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        // setup spacer
        $spacer = "";
        for ($i = 1; $i <= $indent; $i++){
            $spacer .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
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
                $sortby = 'CASE WHEN '.$_SESSION['tree_sortby'].' IS NULL THEN 1 ELSE 0 END, '.$_SESSION['tree_sortby'];
                break;
            default:
                $sortby = $_SESSION['tree_sortby'];
        };

        $orderby = $modx->db->escape($sortby." ".$_SESSION['tree_sortdir']);

    // Folder sorting gets special setup ;) Add menuindex and pagetitle
    if($_SESSION['tree_sortby'] == 'isfolder') $orderby .= ", menuindex ASC, pagetitle";

        $tblsc = $modx->getFullTableName('site_content');
        $tbldg = $modx->getFullTableName('document_groups');
        $tblst = $modx->getFullTableName('site_templates');
        // get document groups for current user
        $docgrp = (isset($_SESSION['mgrDocgroups']) && is_array($_SESSION['mgrDocgroups'])) ? implode(",",$_SESSION['mgrDocgroups']) : '';
        $showProtected= false;
        if (isset ($modx->config['tree_show_protected'])) {
            $showProtected= (boolean) $modx->config['tree_show_protected'];
        }
        $mgrRole= (isset ($_SESSION['mgrRole']) && (string) $_SESSION['mgrRole']==='1') ? '1' : '0';
        if ($showProtected == false) {
            $access = "AND (1={$mgrRole} OR sc.privatemgr=0".
                      (!$docgrp ? ")":" OR dg.document_group IN ({$docgrp}))");
        } else {
            $access = '';
        }
        $result = $modx->db->select(
			"DISTINCT sc.id, pagetitle, longtitle, menutitle, parent, isfolder, published, pub_date, unpub_date, richtext, searchable, cacheable, deleted, type, template, templatename, menuindex, donthit, hidemenu, alias, contentType, privateweb, privatemgr,
				MAX(IF(1={$mgrRole} OR sc.privatemgr=0" . (!$docgrp ? "":" OR dg.document_group IN ({$docgrp})") . ", 1, 0)) AS has_access",
			"{$tblsc} AS sc
			 LEFT JOIN {$tbldg} dg on dg.document = sc.id
			 LEFT JOIN {$tblst} st on st.id = sc.template",
			"(parent={$parent}) {$access} GROUP BY sc.id",
			$orderby
			);
        if($modx->db->getRecordCount($result)==0) {
            $output .= '<div style="white-space: nowrap;">'.$spacer.$pad.'<img align="absmiddle" src="'.$_style["tree_deletedpage"].'">&nbsp;<span class="emptyNode">'.$_lang['empty_folder'].'</span></div>';
        }

        // Make sure to pass in the $modx_textdir variable to the node builder
        global $modx_textdir;

        $node_name_source = $_SESSION['tree_nodename'] == 'default' ? $modx->config['resource_tree_node_name'] : $_SESSION['tree_nodename'];
        while(list($id,$pagetitle,$longtitle,$menutitle,$parent,$isfolder,$published,$pub_date,$unpub_date,$richtext,$searchable,$cacheable,$deleted,$type,$template,$templatename,$menuindex,$donthit,$hidemenu,$alias,$contenttype,$privateweb,$privatemgr,$hasAccess) = $modx->db->getRow($result,'num'))
        {
            $dateNode = false;
            switch($node_name_source)
            {
                case 'menutitle':
                    $nodetitle = $menutitle ? $menutitle : $pagetitle;
                    break;
                case 'alias':
                    $nodetitle = $alias ? $alias : $id;
                    if(strpos($alias, '.') === false)
                    {
                        if($isfolder!=1 || $modx->config['make_folders']!=='1')
                            $nodetitle .= $modx->config['friendly_url_suffix'];
                    }
                    $nodetitle = $modx->config['friendly_url_prefix'] . $nodetitle;
                    break;
                case 'pagetitle':
                    $nodetitle = $pagetitle;
                    break;
                case 'longtitle':
                    $nodetitle = $longtitle;
                    break;
                case 'createdon':
                case 'editedon':
                case 'publishedon':
                case 'pub_date':
                case 'unpub_date':
                    $dateNode = true;
                    $doc = $modx->getDocumentObject('id',$id);
                    $date = $doc[$node_name_source];
                    if(!empty($date)) $nodetitle = $modx->toDateFormat($date);
                    else              $nodetitle = '- - -';
                    break;
                default:
                    $nodetitle = $pagetitle;
            }
            $nodetitle = $modx->htmlspecialchars(str_replace(array("\r\n", "\n", "\r"), ' ', $nodetitle), ENT_COMPAT);
            $nodetitle_esc = addslashes($nodetitle);
            $protectedClass = $hasAccess==0 ? ' protectedNode' : '';
            $nodetitleDisplay = $published==0 ? "<span class=\"unpublishedNode\">$nodetitle</span>" : ($hidemenu==1 ? "<span class=\"notInMenuNode$protectedClass\">$nodetitle</span>":"<span class=\"publishedNode$protectedClass\">$nodetitle</span>");
            $nodetitleDisplay = $deleted==1 ? "<span class=\"deletedNode\">$nodetitle</span>" : $nodetitleDisplay;
            $weblinkDisplay = $type=="reference" ? '&nbsp;<img src="'.$_style["tree_linkgo"].'">' : '' ;
            $pageIdDisplay = '<small>('.($modx_textdir ? '&rlm;':'').$id.')</small>';
            $url = $modx->makeUrl($id);

            $alt = $dateNode == true ? $_lang["pagetitle"].": ".$pagetitle."[+lf+]" : "";
            $alt.= $_lang['resource_opt_menu_title'].": ".$menutitle;
            $alt.= "[+lf+]".$_lang['resource_opt_menu_index'].": ".$menuindex;
            $alt.= "[+lf+]".$_lang['alias'].": ".(!empty($alias) ? $alias : "-");
            $alt.= "[+lf+]".$_lang['template'].": ".$templatename;
            $alt.= "[+lf+]".$_lang['publish_date'].": ".$modx->toDateFormat($pub_date);
            $alt.= "[+lf+]".$_lang['unpublish_date'].": ".$modx->toDateFormat($unpub_date);
            $alt.= "[+lf+]".$_lang['page_data_web_access'].": ".($privateweb ? $_lang['private']:$_lang['public']);
            $alt.= "[+lf+]".$_lang['page_data_mgr_access'].": ".($privatemgr ? $_lang['private']:$_lang['public']);
            $alt.= "[+lf+]".$_lang['resource_opt_richtext'].": ".($richtext==0 ? $_lang['no']:$_lang['yes']);
            $alt.= "[+lf+]".$_lang['page_data_searchable'].": ".($searchable==0 ? $_lang['no']:$_lang['yes']);
            $alt.= "[+lf+]".$_lang['page_data_cacheable'].": ".($cacheable==0 ? $_lang['no']:$_lang['yes']);
            $alt = $modx->htmlspecialchars($alt);
            $alt = str_replace('[+lf+]', ' &#13;', $alt);   // replace line-breaks with empty space as fall-back

            $data = array('id' => $id, 'pagetitle' => $pagetitle, 'longtitle' => $longtitle, 'menutitle' => $menutitle,'parent' =>$parent,
                'isfolder' =>$isfolder,'published' =>$published,'deleted' =>$deleted,'type' =>$type,'menuindex' =>$menuindex,
                'donthit' =>$donthit,'hidemenu' =>$hidemenu,'alias' =>$alias,'contenttype' =>$contenttype,'privateweb' =>$privateweb,
                'privatemgr' =>$privatemgr,'hasAccess' => $hasAccess, 'template' => $template,
                'nodetitle' => $nodetitle, 'spacer' => $spacer, 'pad' => $pad, 'url' => $url, 'alt' => $alt,
                'nodetitleDisplay' => $nodetitleDisplay,'weblinkDisplay' => $weblinkDisplay,'pageIdDisplay' => $pageIdDisplay
            );
            // invoke OnManagerNodePrerender event
            
            $evtOut = $modx->invokeEvent('OnManagerNodePrerender',$data);
            if (is_array($evtOut)) $evtOut = implode("\n", $evtOut);
            
            $node = $evtOut;
            
            if ($replace =='') {
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
                    $node .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.$pad.'<img id="p'.$id.'" align="absmiddle" title="'.$_lang['click_to_context'].'" style="cursor: pointer" src="'.$icon.'" onclick="showPopup('.$id.',\''.$nodetitle_esc.'\',event);return false;" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.$nodetitle_esc.'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\'" />&nbsp;';
                    $node .= '<span p="'.$parent.'" onclick="treeAction('.$id.', \''.$nodetitle_esc.'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.$nodetitle_esc.'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\';" oncontextmenu="document.getElementById(\'p'.$id.'\').onclick(event);return false;" title="'.$alt.'">'.$nodetitleDisplay.$weblinkDisplay.'</span> '.$pageIdDisplay.'</div>';
                }
                else {
                    // expandAll: two type for partial expansion
                    if ($expandAll ==1 || ($expandAll == 2 && in_array($id, $opened)))
                    {
                        if ($expandAll == 1) {
                           array_push($opened2, $id);
                        }
                        $node .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.'<img id="s'.$id.'" align="absmiddle" style="margin-left:-3px;cursor: pointer" src="'.$_style["tree_minusnode"].'" onclick="toggleNode(this,'.($indent+1).','.$id.','.$expandAll.','. (($privateweb == 1 || $privatemgr == 1) ? '1' : '0') .'); return false;" oncontextmenu="this.onclick(event); return false;" />&nbsp;<img id="f'.$id.'" align="absmiddle" title="'.$_lang['click_to_context'].'" style="cursor: pointer;margin-left:-3px;" src="'.(($privateweb == 1 || $privatemgr == 1) ? $_style["tree_folderopen_secure"] : $_style["tree_folderopen"]).'" onclick="showPopup('.$id.',\''.$nodetitle_esc.'\',event);return false;" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.$nodetitle_esc.'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\';" />&nbsp;';
                        $node .= '<span onclick="treeAction('.$id.', \''.$nodetitle_esc.'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.$nodetitle_esc.'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\';" oncontextmenu="document.getElementById(\'f'.$id.'\').onclick(event);return false;" title="'.$alt.'">'.$nodetitleDisplay.$weblinkDisplay.'</span> '.$pageIdDisplay.'<div style="display:block">';
                        $output .= $node;
                        makeHTML($indent+1,$id,$expandAll,$theme);
                        $node = '</div></div>';
                    }
                    else {
                        $node .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.'<img id="s'.$id.'" align="absmiddle" style="margin-left:-3px;cursor: pointer" src="'.$_style["tree_plusnode"].'" onclick="toggleNode(this,'.($indent+1).','.$id.','.$expandAll.','. (($privateweb == 1 || $privatemgr == 1) ? '1' : '0') .'); return false;" oncontextmenu="this.onclick(event); return false;" />&nbsp;<img id="f'.$id.'" title="'.$_lang['click_to_context'].'" align="absmiddle" style="cursor: pointer;margin-left:-3px;" src="'.(($privateweb == 1 || $privatemgr == 1) ? $_style["tree_folder_secure"] : $_style["tree_folder"]).'" onclick="showPopup('.$id.',\''.$nodetitle_esc.'\',event);return false;" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.$nodetitle_esc.'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\';" />&nbsp;';
                        $node .= '<span onclick="treeAction('.$id.', \''.$nodetitle_esc.'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.$nodetitle_esc.'\'; selectedObjectDeleted='.$deleted.'; selectedObjectUrl=\''.$url.'\';" oncontextmenu="document.getElementById(\'f'.$id.'\').onclick(event);return false;" title="'.$alt.'">'.$nodetitleDisplay.$weblinkDisplay.'</span> '.$pageIdDisplay.'<div style="display:none"></div></div>';
                        array_push($closed2, $id);
                    }
                }
            } else {
                $node = $evtOut;
            }
            
            // invoke OnManagerNodeRender event
            $data['node'] = $node;
            $evtOut = $modx->invokeEvent('OnManagerNodeRender',$data);
            if (is_array($evtOut)) $evtOut = implode("\n", $evtOut);
            if ($evtOut != '') $node = $evtOut;

            $output .= $node;
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
