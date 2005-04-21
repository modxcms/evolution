<?php
	//Raymond: save folderstate
	if (isset($_GET['opened'])) $_SESSION['openedArray'] = $_GET['opened'];
	if (isset($_GET['savestateonly'])) {
		echo 'send some data'; //??
		exit;
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
</head>
<body onload="javascript:parent.rpcLoadData(output)">
<!--body onload="javascript:parent.rpcLoadData(document.body.innerHTML)"-->
<?php
	$indent    = $_GET['indent'];
   	$parent    = $_GET['parent'];
	$expandAll = $_GET['expandAll'];
	$output    = "";

	//
	// Jeroen adds an array
	//
	if (isset($_SESSION['openedArray'])) {
			$opened = explode("|", $_SESSION['openedArray']);
			//print_r($opened);
	} else {
			$opened = array();
	}
	$opened2 = array();
	$closed2 = array();
	//
	// Jeroen end
	//
	$s = time();
	makeHTML($indent,$parent,$expandAll);
	
	// mod by Raymond
	echo "<script> var output;";
	echo " output='".mysql_escape_string($output)."';";
	echo "</script>";
	//echo $output;


	function makeHTML($indent,$parent,$expandAll)
	{
		global $etomiteDBConn, $output, $dbase, $table_prefix, $_lang, $opened, $opened2, $closed2; //added global vars

		$pad = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		
		// setup spacer
		$spacer = "";
		for ($i = 1; $i <= $indent; $i++){
			$spacer .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}


		// Raymond: query other documents,set default sort order 
		$orderby = "isfolder DESC";
		if(isset($_SESSION['tree_sortby']) && isset($_SESSION['tree_sortdir'])) {
			$orderby = $_SESSION['tree_sortby']." ".$_SESSION['tree_sortdir'];
		} else {
			$_SESSION['tree_sortby'] = 'isfolder';
			$_SESSION['tree_sortdir'] = 'DESC';
		}
		if($_SESSION['tree_sortby'] == 'isfolder') $orderby .= ", pagetitle";
		//Raymond: end


		$tblsc = $dbase.".".$table_prefix."site_content";
		$tbldg = $dbase.".".$table_prefix."document_groups";
		$tbldgn = $dbase.".".$table_prefix."documentgroup_names";
		// get document groups for current user
		if($_SESSION['docgroups']) $docgrp = implode(",",$_SESSION['docgroups']);
		$result = mysql_query("	SELECT DISTINCT sc.id, pagetitle, parent, isfolder, published, deleted, type, menuindex, alias 
								FROM $tblsc AS sc 
								LEFT JOIN $tbldg dg on dg.document = sc.id
								LEFT JOIN $tbldgn dgn ON dgn.id = dg.document_group
								WHERE (parent=$parent) 
								AND (1='".$_SESSION['role']."' OR NOT(dgn.private_memgroup<=>1)".(!$docgrp ? "":" OR dg.document_group IN ($docgrp)").") 
								ORDER BY $orderby", $etomiteDBConn);
		if(mysql_num_rows($result)==0) {
			$output .= '<div style="white-space: nowrap;">'.$spacer.$pad.'<img align="absmiddle" src="media/images/tree/deletedpage.gif" width="18" height="18">&nbsp;<span class="emptyNode">'.$_lang['empty_folder'].'</span></div>';
		}

		while(list($id,$pagetitle,$parent,$isfolder,$published,$deleted,$type,$menuindex,$alias) = mysql_fetch_row($result))
		{
			$pagetitleDisplay = $published==0 ? "<span class='unpublishedNode'>$pagetitle</span>" : "<span class='publishedNode'>$pagetitle</span>";
			$pagetitleDisplay = $deleted==1 ? "<span class='deletedNode'>$pagetitle</span>" : $pagetitleDisplay;
			$weblinkDisplay = $type=="reference" ? '&nbsp;<img align="absmiddle" src="media/images/tree/web.gif">' : '' ;


			$alt = !empty($alias) ? $_lang['alias'].": ".$alias : $_lang['alias'].": - ";
			$alt .= "\n".$_lang['document_opt_menu_index'].": ".$menuindex;

			if (!$isfolder) {
					$output .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.$pad.'<img id="p'.$id.'" align="absmiddle" title="'.$_lang['click_to_context'].'" style="cursor: pointer" src="media/images/tree/page.gif" width="18" height="18" onclick="return showPopup('.$id.',\''.addslashes($pagetitle).'\',event);" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" />&nbsp;';
					$output .= '<span p="'.$parent.'" onclick="treeAction('.$id.', \''.addslashes($pagetitle).'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.';" oncontextmenu="document.getElementById(\'p'.$id.'\').onclick(event);return false;" title="'.addslashes($alt).'">'.$pagetitleDisplay.$weblinkDisplay.'</span> <small>('.$id.')</small></div>';
			}
			else {
				//
				// Jeroen add the expandAll 2 type for partial expansion
				//				
				if ($expandAll ==1 || ($expandAll == 2 && in_array($id, $opened)))
				{
					if ($expandAll == 1) {
					   array_push($opened2, $id);
					}
					$output .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.'<img id="s'.$id.'" align="absmiddle" style="cursor: pointer" src="media/images/tree/minusnode.gif" width="18" height="18" onclick="toggleNode(this,'.($indent+1).','.$id.',0); return false;" oncontextmenu="this.onclick(event); return false;" />&nbsp;<img id="f'.$id.'" align="absmiddle" title="'.$_lang['click_to_context'].'" style="cursor: pointer" src="media/images/tree/folderopen.gif" width="18" height="18" onclick="return showPopup('.$id.',\''.addslashes($pagetitle).'\',event);" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" />&nbsp;';
					$output .= '<span onclick="treeAction('.$id.', \''.addslashes($pagetitle).'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.';" oncontextmenu="document.getElementById(\'f'.$id.'\').onclick(event);return false;" title="'.addslashes($alt).'">'.$pagetitleDisplay.$weblinkDisplay.'</span> <small>('.$id.')</small><div style="display:block">';
					makeHTML($indent+1,$id,$expandAll);
					$output .= '</div></div>';
				}
				else {
					$output .= '<div id="node'.$id.'" p="'.$parent.'" style="white-space: nowrap;">'.$spacer.'<img id="s'.$id.'" align="absmiddle" style="cursor: pointer" src="media/images/tree/plusnode.gif" width="18" height="18" onclick="toggleNode(this,'.($indent+1).','.$id.',0); return false;" oncontextmenu="this.onclick(event); return false;" />&nbsp;<img id="f'.$id.'" title="'.$_lang['click_to_context'].'" align="absmiddle" style="cursor: pointer" src="media/images/tree/folder.gif" width="18" height="18" onclick="return showPopup('.$id.',\''.addslashes($pagetitle).'\',event);" oncontextmenu="this.onclick(event);return false;" onmouseover="setCNS(this, 1)" onmouseout="setCNS(this, 0)" />&nbsp;';
					$output .= '<span onclick="treeAction('.$id.', \''.addslashes($pagetitle).'\'); setSelected(this);" onmouseover="setHoverClass(this, 1);" onmouseout="setHoverClass(this, 0);" class="treeNode" onmousedown="itemToChange='.$id.'; selectedObjectName=\''.addslashes($pagetitle).'\'; selectedObjectDeleted='.$deleted.';" oncontextmenu="document.getElementById(\'f'.$id.'\').onclick(event);return false;" title="'.addslashes($alt).'">'.$pagetitleDisplay.$weblinkDisplay.'</span> <small>('.$id.')</small><div style="display:none"></div></div>';
					array_push($closed2, $id);
				}
				//
				// Jeroen end
				//
			}
			//
			// Jeroen stores vars in Javascript
			//
						if ($expandAll == 1) {
							echo '<script language="JavaScript"> ';
							foreach ($opened2 as $item) {
									 printf("parent.openedArray[%d] = 1; ", $item);
							}
							echo '</script> ';
						} elseif ($expandAll == 0) {
							echo '<script language="JavaScript"> ';
							foreach ($closed2 as $item) {
									 printf("parent.openedArray[%d] = 0; ", $item);
							}
							echo '</script> ';
						}
			//
			// Jeroen end
			//
		}
    }

?>
</body>
</html>