<?php if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly."); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>Document Tree</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>" />
    <link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css" />
    <script type="text/javascript" language="JavaScript" src="media/script/bin/webelm.js"></script>
    <script type="text/javascript" src="media/script/cb2.js"></script>
    <script type="text/javascript">
/* including (for the really important bits) code devised and written yb patv */

	document.setIncludePath("media/script/bin/");

	function document_oninit(){
		document.include("dynelement");
	}

	function document_onload(){
		restoreTree();
	}

	document.addEventListener("onclick",function(){
		hideMenu();
	})

	// preload images
	var i = new Image(18,18);
	i.src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/page.gif";
	i = new Image(18,18);
	i.src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/minusnode.gif";
	i = new Image(18,18);
	i.src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/plusnode.gif";
	i = new Image(18,18);
	i.src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/folderopen.gif";
	i = new Image(18,18);
	i.src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/folder.gif";


	var rpcNode = null;
	var	ca = "open";
	var	selectedObject = 0;
	var selectedObjectDeleted = 0;
	var	selectedObjectName = "";

<?php
	//
	// Jeroen adds an array
	//
	echo  "var openedArray = new Array();\n";
	if (isset($_SESSION['openedArray'])) {
			$opened = explode("|", $_SESSION['openedArray']);

			foreach ($opened as $item) {
				 printf("openedArray[%d] = 1;\n", $item);
			}
	}
	//
	// Jeroen end
	//
?>

	function getScrollY() {
	  var scrOfY = 0;
	  if( typeof( window.pageYOffset ) == 'number' ) {
		//Netscape compliant
		scrOfY = window.pageYOffset;
	  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
		//DOM compliant
		scrOfY = document.body.scrollTop;
	  } else if( document.documentElement &&
		  (document.documentElement.scrollTop ) ) {
		//IE6 standards compliant mode
		scrOfY = document.documentElement.scrollTop;
	  }
	  return scrOfY;
	}

	function showPopup(id,title,e){
		var x,y
		x = e.clientX>0 ? e.clientX:e.pageX;
		y = e.clientY>0 ? e.clientY:e.pageY;
		y = getScrollY()+(y/2);
		if (y+260>parseInt(document.body.offsetHeight)) y = getScrollY();
		itemToChange=id;
		selectedObjectName= title;
		dopopup(x+5,y);
		e.cancelBubble=true;
		return false;
	};

	function toggleNode(node,indent,parent,expandAll) {

		rpcNode = new DynElement(node.parentNode.lastChild);

		var rpcNodeText;
		var loadText = "<?php echo $_lang['loading_doc_tree'];?>";

		var signImg = document.getElementById("s"+parent);
		var folderImg = document.getElementById("f"+parent);

		if (rpcNode.style.display != 'block') {
			// expand
			if(signImg && signImg.src.indexOf('media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/plusnode.gif')>-1) {
				signImg.src = 'media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/minusnode.gif';
				folderImg.src = 'media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/folderopen.gif';
			}
			// Raymond: snippet interface
			if (node && node.src.indexOf('media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/snippetfolder.gif')>-1) {node.src = 'media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/snippetfolderopen.gif'}

			rpcNodeText = rpcNode.getInnerHTML();

			if (rpcNodeText=="" || rpcNodeText.indexOf(loadText)>0) {
				var i, spacer='';
				for(i=0;i<=indent+1;i++) spacer+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				rpcNode.style.display = 'block';
				//Jeroen set opened
				openedArray[parent] = 1 ;
				//Raymond:added getFolderState()
				frames['rpcFrame'].location.href ='index.php?a=1&f=3ldr&indent='+indent+'&parent='+parent+'&expandAll='+expandAll+getFolderState();
				rpcNode.setInnerHTML("<span class='emptyNode' style='white-space:nowrap;'>"+spacer+"&nbsp;&nbsp;&nbsp;"+loadText+"...<\/span>");
			} else {
				rpcNode.style.display = 'block';
				//Jeroen set opened
				openedArray[parent] = 1 ;
			}
		}
		else {
			// collapse
			if(signImg && signImg.src.indexOf('media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/minusnode.gif')>-1) {
				signImg.src = 'media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/plusnode.gif';
				folderImg.src = 'media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/folder.gif';
			}
			//Raymond: snippet interface
			if (node.src.indexOf('media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/snippetfolderopen.gif')>-1) {node.src = 'media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/snippetfolder.gif'}
			//rpcNode.innerHTML = '';
			rpcNode.style.display = 'none';
			//Jeroen set closed
			openedArray[parent] = 0 ;
		}
	}

	function rpcLoadData(html)
	{
		if(rpcNode != null){
			rpcNode.setInnerHTML(html); // modx
			rpcNode.style.display = 'block';
			rpcNode.loaded = true;
		}
	}

	function expandTree()
	{
		//rpcNode = document.getElementById('treeRoot');
		rpcNode = new DynElement('treeRoot'); //modx
		frames['rpcFrame'].location.href ='index.php?a=1&f=3ldr&indent=1&parent=0&expandAll=1';
	}

	function collapseTree()
	{
		//rpcNode = document.getElementById('treeRoot');
		rpcNode = new DynElement('treeRoot'); //modx
		frames['rpcFrame'].location.href ='index.php?a=1&f=3ldr&indent=1&parent=0&expandAll=0';
	}

	//
	// Jeroen makes new function used in body onload
	//
	function restoreTree()
	{
			//rpcNode = document.getElementById('treeRoot');
			rpcNode = new DynElement('treeRoot'); // modx
			frames['rpcFrame'].location.href ='index.php?a=1&f=3ldr&indent=1&parent=0&expandAll=2';
	}
	//
	// Jeroen end
	//

	function setSelected(elSel) {
		//alert(el.className);
		var all = document.getElementsByTagName( "SPAN" );
		var l = all.length;

		for ( var i = 0; i < l; i++ ) {
			el = all[i]
			cn = el.className;
			if (cn=="treeNodeSelected") {
				el.className="treeNode";
			}
		}
		elSel.className="treeNodeSelected";
	};

	function setHoverClass(el, dir) {
		if(el.className!="treeNodeSelected") {
			if(dir==1) {
				el.className="treeNodeHover";
			} else {
				el.className="treeNode";
			}
		}
	};

	// set Context Node State
	function setCNS(n, b) {
		if(b==1) {
			n.style.backgroundColor="beige";
		} else {
			n.style.backgroundColor="";
		}
	};

	function updateTree() {
		treeUrl = 'index.php?a=1&f=3&dt=' + document.sortFrm.dt.value + '&tree_sortby=' + document.sortFrm.sortby.value + '&tree_sortdir=' + document.sortFrm.sortdir.value;
		document.location.href=treeUrl;
	}

	function emptyTrash() {
		if(confirm("<?php echo $_lang['confirm_empty_trash']; ?>")==true) {
			top.main.document.location.href="index.php?a=64";
		}
	}

	currSorterState="none";
	function showSorter() {
		if(currSorterState=="none") {
			currSorterState="block";
			document.getElementById('floater').style.display=currSorterState;
		} else {
			currSorterState="none";
			document.getElementById('floater').style.display=currSorterState;
		}
	}

	function treeAction(id, name) {
		if(ca=="move") {
			try {
				parent.main.setMoveValue(id, name);
			} catch(oException) {
				alert('<?php echo $_lang['unable_set_parent']; ?>');
			}
		}
		if(ca=="open" || ca=="") {
			if(id==0) {
				// do nothing?
				parent.main.location.href="index.php?a=2";
			} else {
				//
				//Jeoren added the parentarray, Modified by Raymond: added getFolderState()
				//
				parent.main.location.href="index.php?a=3&id=" + id + getFolderState(); //just added the getvar &opened=
				//
				// Jeroen added the parentarray
				//
			}
		}
		if(ca=="parent") {
			try {
				parent.main.setParent(id, name);
			} catch(oException) {
				alert('<?php echo $_lang['unable_set_parent']; ?>');
			}
		}
		if(ca=="link") {
			try {
				parent.main.setLink(id);
			} catch(oException) {
				alert('<?php echo $_lang['unable_set_link']; ?>');
			}
		}
	}

	//Raymond: added getFolderState,saveFolderState
	function getFolderState(){
		//
		//Jeoren added the parentarray
		//
		if (openedArray != [0]) {
				oarray = "&opened=";
				for (key in openedArray) {
				   if (openedArray[key] == 1) {
					  oarray += key+"|";
				   }
				}
		} else {
				oarray = "&opened=";
		}
		//
		// Jeroen added the parentarray
		//
		return oarray;
	}
	function saveFolderState() {
		frames['rpcFrame'].location.href ='index.php?a=1&f=3ldr&savestateonly=1'+getFolderState();
	}
	//Raymond:added getFolderState,saveFolderState

</script>
<?php
$sql = "SELECT COUNT(*) FROM $dbase.".$table_prefix."site_content WHERE deleted=1";
$rs = mysql_query($sql);
$row = mysql_fetch_row($rs);
$count = $row[0];

?>
<!--[if lt IE 7]>
    body { behavior: url(../assets/js/htcmime.php?file=csshover.htc) }
    img { behavior: url(../assets/js/htcmime.php?file=pngbehavior.htc); }
<![endif]-->


</head>
<!-- Raymond: add onbeforeunload -->
<body onclick="hideMenu();" class="treeframebody">
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
		<table cellpadding="0" cellspacing="0">
		    <tr>
			<td id="Button1" onclick="expandTree();" title="<?php echo $_lang['expand_tree']; ?>"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/arrow_down.png' /></td>
			<td id="Button2" onclick="collapseTree();" title="<?php echo $_lang['collapse_tree']; ?>"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/arrow_up.png' /></td>
			<td id="Button3a" onclick="top.main.document.location.href='index.php?a=4';" title="<?php echo $_lang['add_document']; ?>"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/page_white_add.png' /></td>
			<td id="Button3b" onclick="top.main.document.location.href='index.php?a=85';" title="<?php echo $_lang['add_folder']; ?>"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/folder_add.png' /></td>
			<td id="Button3c" onclick="top.main.document.location.href='index.php?a=72';" title="<?php echo $_lang['add_weblink']; ?>"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/link_add.png' /></td>
			<td id="Button4" onclick="updateTree();" title="<?php echo $_lang['refresh_tree']; ?>"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/refresh.png' /></td>
			<td id="Button5" onclick="showSorter();" title="<?php echo $_lang['sort_tree']; ?>"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/sort.png' /></td>
			<td id="Button10" onclick="emptyTrash();" title="<?php echo $count>0 ? $_lang['empty_recycle_bin'] : $_lang['empty_recycle_bin_empty'] ; ?>"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/trash<?php echo $count>0 ? "_full" : ""; ?>.png' /></td>
			</tr>
		</table>
	</td>
    <td width="23" align="right">
		<table cellpadding="0" cellspacing="0">
		    <tr>
			<td id="Button6" onclick="top.scripter.hideTreeFrame();" title="<?php echo $_lang['hide_tree']; ?>"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/application_side_contract.png'  /></td>
            </tr>
		</table>
	</td>
    <td width="16" align="right">&nbsp;</td>
  </tr>
</table>
<script type="text/javascript">
    createButton(document.getElementById("Button1"));
    createButton(document.getElementById("Button2"));
    createButton(document.getElementById("Button3a"));
    createButton(document.getElementById("Button3b"));
    createButton(document.getElementById("Button3c"));
    createButton(document.getElementById("Button4"));
    createButton(document.getElementById("Button5"));
    createButton(document.getElementById("Button10"));
    
    createButton(document.getElementById("Button6"));
</script>
<?php if($count==0) { ?><script type="text/javascript">document.getElementById("Button10").setEnabled(false);</script><?php } ?>

<div id="floater">
<?php
if(isset($_REQUEST['tree_sortby'])) {
    $_SESSION['tree_sortby'] = $_REQUEST['tree_sortby'];
}

if(isset($_REQUEST['tree_sortdir'])) {
    $_SESSION['tree_sortdir'] = $_REQUEST['tree_sortdir'];
}
?>
<form name="sortFrm" id="sortFrm" action="3.php">
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="padding-left: 10px;padding-top: 1px;" colspan="2">
        <select name="sortby" style="font-size: 9px;">
            <option value="isfolder" <?php echo $_SESSION['tree_sortby']=='isfolder' ? "selected='selected'" : "" ?>><?php echo $_lang['folder']; ?></option>
            <option value="pagetitle" <?php echo $_SESSION['tree_sortby']=='pagetitle' ? "selected='selected'" : "" ?>><?php echo $_lang['pagetitle']; ?></option>
            <option value="id" <?php echo $_SESSION['tree_sortby']=='id' ? "selected='selected'" : "" ?>><?php echo $_lang['id']; ?></option>
            <option value="menuindex" <?php echo $_SESSION['tree_sortby']=='menuindex' ? "selected='selected'" : "" ?>><?php echo $_lang['document_opt_menu_index'] ?></option>
            <option value="createdon" <?php echo $_SESSION['tree_sortby']=='createdon' ? "selected='selected'" : "" ?>><?php echo $_lang['createdon']; ?></option>
            <option value="editedon" <?php echo $_SESSION['tree_sortby']=='editedon' ? "selected='selected'" : "" ?>><?php echo $_lang['editedon']; ?></option>
            <option value="pagetitle" <?php echo $_SESSION['tree_sortby']=='pagetitle' ? "selected='selected'" : "" ?>><?php echo $_lang['pagetitle']; ?></option>
        </select>
    </td>
  </tr>
  <tr>
    <td width="99%" style="padding-left: 10px;padding-top: 1px;">
        <select name="sortdir" style="font-size: 9px;">
            <option value="DESC" <?php echo $_SESSION['tree_sortdir']=='DESC' ? "selected='selected'" : "" ?>><?php echo $_lang['sort_desc']; ?></option>
            <option value="ASC" <?php echo $_SESSION['tree_sortdir']=='ASC' ? "selected='selected'" : "" ?>><?php echo $_lang['sort_asc']; ?></option>
        </select>
        <input type='hidden' name='dt' value='<?php echo $_REQUEST['dt']; ?>' />
    </td>
    <td width="1%" id="button7" align="right" onclick="updateTree();" title="<?php echo $_lang['sort_tree']; ?>"><input type="button" value="Go!" />
    </td>
  </tr>
</table>
</form>
</div>

<div id="treeHolder">

	<div><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/sitemap.png' width="19" height="18" />&nbsp;<span class="rootNode" onclick="treeAction(0, '<?php echo addslashes($site_name); ?>');"><b><?php echo $site_name; ?></b></span><div id="treeRoot"></div></div>
	<div><iframe src="about:blank" id="rpcFrame" name="rpcFrame" style="width: 1px; height: 1px; visibility: hidden;"></iframe></div>

</div>

<script type="text/javascript">
try {
	var elm = new DynElement("buildText@topFrame");
	if (elm) elm.setInnerHTML("");
	//top.topFrame.document.getElementById('buildText').innerHTML = "";
} catch(oException) { }


// Context menu stuff
function menuHandler(action) {
	switch (action) {
		case 1 :
			top.main.document.location.href="index.php?a=3&id=" + itemToChange;
			break
		case 2 :
			top.main.document.location.href="index.php?a=27&id=" + itemToChange;
			break
		case 3 :
			top.main.document.location.href="index.php?a=4&pid=" + itemToChange;
			break
		case 4 :
			if(selectedObjectDeleted==0) {
				if(confirm("'" + selectedObjectName + "'\n\n<?php echo $_lang['confirm_delete_document']; ?>")==true) {
					top.main.document.location.href="index.php?a=6&id=" + itemToChange;
				}
			} else {
				alert("'" + selectedObjectName + "' <?php echo $_lang['already_deleted']; ?>");
			}
			break
		case 5 :
			top.main.document.location.href="index.php?a=51&id=" + itemToChange;
			break
		case 6 :
			top.main.document.location.href="index.php?a=72&pid=" + itemToChange;
			break
		case 7 : // Ryan: duplicate document
            if(confirm("<?php echo $_lang['confirm_duplicate_document'] ?>")==true) {
                   top.main.document.location.href="index.php?a=94&id=" + itemToChange;
               }
			break
		case 8 :
			if(selectedObjectDeleted==0) {
				alert("'" + selectedObjectName + "' <?php echo $_lang['not_deleted']; ?>");
			} else {
				if(confirm("'" + selectedObjectName + "' <?php echo $_lang['confirm_undelete']; ?>")==true) {
					top.main.document.location.href="index.php?a=63&id=" + itemToChange;
				}
			}
			break
		case 9 :
			if(confirm("'" + selectedObjectName + "' <?php echo $_lang['confirm_publish']; ?>")==true) {
				top.main.document.location.href="index.php?a=61&id=" + itemToChange;
			}
			break
		case 10 :
			if(confirm("'" + selectedObjectName + "' <?php echo $_lang['confirm_unpublish']; ?>")==true) {
			top.main.document.location.href="index.php?a=62&id=" + itemToChange;
			}
			break
		case 11 : //Raymond: create folder
			top.main.document.location.href="index.php?a=85&pid=" + itemToChange;
			break
		default :
			alert('Unknown operation command.');
	}
}

</script>

<!-- Contextual Menu Popup Code (NEEDS HELP!) -->
<script type="text/javascript">

	function dopopup(x,y) {
		if(selectedObjectName.length>20) {
			selectedObjectName = selectedObjectName.substr(0, 20) + "...";
		}
		var h,context = document.getElementById('contextMenu');
		context.style.left= x+"px";// (getScrollY()+20)+"px";
		context.style.top = y+"px";
		var elm = new DynElement("nameHolder@oPopup");
		elm.setInnerHTML(selectedObjectName);

		context.style.visibility = 'visible';
		context.style.display = 'block';

		// adjust context menu height
		frames['oPopup'].document.body.style.height='auto';
		h = frames['oPopup'].document.body.offsetHeight;
		context.style.height= h+"px";
	}

	function hideMenu() {
		document.getElementById('contextMenu').style.display = 'none';
	}

</script>
<div id="contextMenu" style="position: absolute; right: 20px; top: 20px; z-index:10000; width: 170px; height: auto;visibility: hidden;">
	<iframe name="oPopup" style="width:170px;height:100%" frameborder="0" src="index.php?a=1&amp;f=3c"></iframe>
</div>

</body>
</html>
