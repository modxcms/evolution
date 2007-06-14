<?php if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly."); 

    $theme = $manager_theme ? "$manager_theme/":"";

    function constructLink($action, $img, $text, $allowed, $theme) {
        if($allowed==1) { ?>
            <div class="menuLink" onmouseover="this.className='menuLinkOver';" onmouseout="this.className='menuLink';" onclick="this.className='menuLink'; menuHandler(<?php echo $action ; ?>); hideMenu();">
                <img src='media/style/<?php echo $theme; ?>images/icons/<?php echo $img; ?>.gif' /><?php echo $text; ?>
            </div>
        <?php
        } 
        else { ?>
            <div class="menuLinkDisabled">
                <img src='media/style/<?php echo $theme; ?>images/icons/<?php echo $img; ?>.gif' /><?php echo $text; ?>
            </div>
        <?php
        }
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html <?php echo $modx->config['manager_direction'] == 'rtl' ? 'dir="rtl"' : '';?> lang="<?php echo $modx->config['manager_lang_attribute'];?>" xml:lang="<?php echo $modx->config['manager_lang_attribute'];?>">
<head>
    <title>Document Tree</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_charset; ?>" />
    <link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css" />
    <script src="media/script/mootools/mootools.js" type="text/javascript"></script>
    <script src="media/script/mootools/moodx.js" type="text/javascript"></script>
    <script type="text/javascript">
    /* including (for the really important bits) code devised and written yb patv */
	window.addEvent('load', function(){
        resizeTree();    
        restoreTree();
        window.addEvent('resize', resizeTree);
    });

    // preload images
    var i = new Image(18,18);
    i.src="<?php echo $_style["tree_page"]?>";
    i = new Image(18,18);
    i.src="<?php echo $_style["tree_globe"]?>";
    i = new Image(18,18);
    i.src="<?php echo $_style["tree_minusnode"]?>";
    i = new Image(18,18);
    i.src="<?php echo $_style["tree_plusnode"]?>";
    i = new Image(18,18);
    i.src="<?php echo $_style["tree_folderopen"]?>";
    i = new Image(18,18);
    i.src="<?php echo $_style["tree_folder"]?>";


    var rpcNode = null;
    var ca = "open";
    var selectedObject = 0;
    var selectedObjectDeleted = 0;
    var selectedObjectName = "";

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
    
    // return window dimensions in array
    function getWindowDimension() {
        var width  = 0;
        var height = 0;

        if ( typeof( window.innerWidth ) == 'number' ){
            width  = window.innerWidth;
            height = window.innerHeight;
        }else if ( document.documentElement && 
                 ( document.documentElement.clientWidth ||
                   document.documentElement.clientHeight ) ){
            width  = document.documentElement.clientWidth;
            height = document.documentElement.clientHeight;
        }
        else if ( document.body && 
                ( document.body.clientWidth || document.body.clientHeight ) ){
            width  = document.body.clientWidth;
            height = document.body.clientHeight;
        }
        
        return {'width':width,'height':height};
    }

    function resizeTree() {
        
        // get window width/height
        var win = getWindowDimension();
        
        // set tree height
        var tree = $('treeHolder');
        var tmnu = $('treeMenu');
        tree.style.width = (win['width']-20)+'px';
        tree.style.height = (win['height']-tree.offsetTop-6)+'px';
        tree.style.overflow = 'auto';
    }

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
        var mnu = $('mx_contextmenu');
        var bodyHeight = parseInt(document.body.offsetHeight);
        x = e.clientX>0 ? e.clientX:e.pageX;
        y = e.clientY>0 ? e.clientY:e.pageY;
        y = getScrollY()+(y/2);
        if (y+mnu.offsetHeight > bodyHeight) {
            // make sure context menu is within frame
            y = y - ((y+mnu.offsetHeight)-bodyHeight+5);
        }
        itemToChange=id;
        selectedObjectName= title;
        dopopup(x+5,y);
        e.cancelBubble=true;
        return false;
    };

    function dopopup(x,y) {
        if(selectedObjectName.length>20) {
            selectedObjectName = selectedObjectName.substr(0, 20) + "...";
        }
        var h,context = $('mx_contextmenu');
        context.style.left= x<?php echo $modx->config['manager_direction']=='rtl' ? '-190' : '';?>+"px"; //offset menu to the left if rtl is selected
        context.style.top = y+"px";
        var elm = $("nameHolder");
        elm.innerHTML = selectedObjectName;

        context.style.visibility = 'visible';

    }

    function hideMenu() {
        $('mx_contextmenu').style.visibility = 'hidden';
    }
    
    function toggleNode(node,indent,parent,expandAll,privatenode) {
		privatenode = (!privatenode || privatenode == '0') ? privatenode = '0' : privatenode = '1';
        rpcNode = $(node.parentNode.lastChild);
        
        var rpcNodeText;
        var loadText = "<?php echo $_lang['loading_doc_tree'];?>";

        var signImg = document.getElementById("s"+parent);
        var folderImg = document.getElementById("f"+parent);

        if (rpcNode.style.display != 'block') {
            // expand
            if(signImg && signImg.src.indexOf('media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/plusnode.gif')>-1) {
                signImg.src = '<?php echo $_style["tree_minusnode"]; ?>';
                folderImg.src = (privatenode == '0') ? '<?php echo $_style["tree_folderopen"]; ?>' :'<?php echo $_style["tree_folderopen_secure"]; ?>';
            }

            // Raymond: snippet interface
            //if (node && node.src.indexOf('media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/snippetfolder.gif')>-1) {node.src = 'media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/snippetfolderopen.gif'}

            rpcNodeText = rpcNode.innerHTML;

            if (rpcNodeText=="" || rpcNodeText.indexOf(loadText)>0) {
                var i, spacer='';
                for(i=0;i<=indent+1;i++) spacer+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                rpcNode.style.display = 'block';
                //Jeroen set opened
                openedArray[parent] = 1 ;
                //Raymond:added getFolderState()
                var folderState = getFolderState();
                rpcNode.innerHTML = "<span class='emptyNode' style='white-space:nowrap;'>"+spacer+"&nbsp;&nbsp;&nbsp;"+loadText+"...<\/span>";
        		new Ajax('index.php?a=1&f=nodes&indent='+indent+'&parent='+parent+'&expandAll='+expandAll+folderState, {method: 'get',onComplete:rpcLoadData}).request();
            } else {
                rpcNode.style.display = 'block';
                //Jeroen set opened
                openedArray[parent] = 1 ;
            }
        }
        else {
            // collapse
            if(signImg && signImg.src.indexOf('media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/minusnode.gif')>-1) {
                signImg.src = '<?php echo $_style["tree_plusnode"]; ?>';
                folderImg.src = (privatenode == '0') ? '<?php echo $_style["tree_folder"]; ?>' : '<?php echo $_style["tree_folder_secure"]; ?>';
            }
            //Raymond: snippet interface
            if (node.src.indexOf('media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/snippetfolderopen.gif')>-1) {node.src = 'media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/snippetfolder.gif'}
            //rpcNode.innerHTML = '';
            rpcNode.style.display = 'none';
            //Jeroen set closed
            openedArray[parent] = 0 ;
        }
    }

    function rpcLoadData(response) {
        if(rpcNode != null){
            rpcNode.innerHTML = typeof response=='object' ? response.responseText : response ; 
            rpcNode.style.display = 'block';
            rpcNode.loaded = true;
            var elm = top.mainMenu.$("buildText");
            if (elm) {
                elm.innerHTML = "";
                elm.style.display = 'none';
            }
            // check if bin is full
            if(rpcNode.id=='treeRoot') {
                var e = $('binFull');
                if(e) showBinFull();
                else showBinEmpty();
            }
            
            // check if our payload contains the login form :)
            e = $('mx_loginbox');
            if(e) {
                // yep! the seession has timed out
                rpcNode.innerHTML = '';
                top.location = 'index.php';
            }
        }
    }

    function expandTree() {
        rpcNode = $('treeRoot'); 
        new Ajax('index.php?a=1&f=nodes&indent=1&parent=0&expandAll=1', {method: 'get',onComplete:rpcLoadData}).request();
    }

    function collapseTree() {
        rpcNode = $('treeRoot'); 
        new Ajax('index.php?a=1&f=nodes&indent=1&parent=0&expandAll=0', {method: 'get',onComplete:rpcLoadData}).request();
    }

    //
    // Jeroen makes new function used in body onload
    //
    function restoreTree() {
        rpcNode = $('treeRoot'); 
        new Ajax('index.php?a=1&f=nodes&indent=1&parent=0&expandAll=2', {method: 'get',onComplete:rpcLoadData}).request();
    }
    //
    // Jeroen end
    //

    function setSelected(elSel) {
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
        rpcNode = $('treeRoot'); 
        treeParams = 'a=1&f=nodes&indent=1&parent=0&expandAll=2&dt=' + document.sortFrm.dt.value + '&tree_sortby=' + document.sortFrm.sortby.value + '&tree_sortdir=' + document.sortFrm.sortdir.value;
        new Ajax('index.php?'+treeParams, {method: 'get',onComplete:rpcLoadData}).request();
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
    	var folderState = getFolderState();
        new Ajax('index.php?a=1&f=nodes&savestateonly=1'+folderState, {method: 'get'}).request();		
    }
    //Raymond:added getFolderState,saveFolderState


    // show state of recycle bin
    function showBinFull() {
        var a = $('Button10');
        var title = '<?php echo $_lang['empty_recycle_bin']; ?>';
        if(!a.setAttibute) a.title = title;
        else a.setAttribute('title',title); 
        a.innerHTML = '<?php echo $_style['empty_recycle_bin']; ?>';
        a.className = 'treeButton';
        a.onclick = emptyTrash;
    }
    
    function showBinEmpty() {
        var a = $('Button10');
        var title = '<?php echo $_lang['empty_recycle_bin_empty']; ?>';
        if(!a.setAttibute) a.title = title;
        else a.setAttribute('title',title); 
        a.innerHTML = '<?php echo $_style['empty_recycle_bin_empty']; ?>';    
        a.className = 'treeButtonDisabled';
        a.onclick = '';
    }
    
</script>

<!--[if lt IE 7]>
    <style type="text/css">
      body { behavior: url(media/script/forIE/htcmime.php?file=csshover.htc) }
      img { behavior: url(media/script/forIE/htcmime.php?file=pngbehavior.htc); }
    </style>
<![endif]-->


</head>
<!-- Raymond: add onbeforeunload -->
<body onclick="hideMenu();" class="treeframebody">

<!-- to be improved -->
<div id="treeSplitter"></div>

<table id="treeMenu" width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
            <td><a href="#" class="treeButton" id="Button1" onclick="expandTree();" title="<?php echo $_lang['expand_tree']; ?>"><?php echo $_style['expand_tree']; ?></a></td>
            <td><a href="#" class="treeButton" id="Button2" onclick="collapseTree();" title="<?php echo $_lang['collapse_tree']; ?>"><?php echo $_style['collapse_tree']; ?></a></td>
            <?php if ($modx->hasPermission('new_document')) { ?>
	            <td><a href="#" class="treeButton" id="Button3a" onclick="top.main.document.location.href='index.php?a=4';" title="<?php echo $_lang['add_document']; ?>"><?php echo $_style['add_doc_tree']; ?></a></td>
	            <td><a href="#" class="treeButton" id="Button3c" onclick="top.main.document.location.href='index.php?a=72';" title="<?php echo $_lang['add_weblink']; ?>"><?php echo $_style['add_weblink_tree']; ?></a></td>
            <?php } ?>
            <td><a href="#" class="treeButton" id="Button4" onclick="top.mainMenu.reloadtree();" title="<?php echo $_lang['refresh_tree']; ?>"><?php echo $_style['refresh_tree']; ?></a></td>
            <td><a href="#" class="treeButton" id="Button5" onclick="showSorter();" title="<?php echo $_lang['sort_tree']; ?>"><?php echo $_style['sort_tree']; ?></a></td>
            <td><a href="#" id="Button10" class="treeButtonDisabled"' title="<?php echo $_lang['empty_recycle_bin_empty'] ; ?>"><?php echo $_style['empty_recycle_bin_empty'] ; ?></a></td>
            </tr>
        </table>
    </td>
    <td align="right">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
            <td><a href="#" class="treeButton" id="Button6" onclick="top.mainMenu.hideTreeFrame();" title="<?php echo $_lang['hide_tree']; ?>"><?php echo $_style['hide_tree']; ?></a></td>
            </tr>
        </table>
    </td>
  </tr>
</table>

<div id="floater">
<?php
if(isset($_REQUEST['tree_sortby'])) {
    $_SESSION['tree_sortby'] = $_REQUEST['tree_sortby'];
}

if(isset($_REQUEST['tree_sortdir'])) {
    $_SESSION['tree_sortdir'] = $_REQUEST['tree_sortdir'];
}
?>
<form name="sortFrm" id="sortFrm" action="menu.php">
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
    <td width="1%"><a href="#" class="treeButton" id="button7" style="text-align:right" onclick="updateTree();showSorter();" title="<?php echo $_lang['sort_tree']; ?>"><?php echo $_lang['sort_tree']; ?></a></td>
  </tr>
</table>
</form>
</div>

<div id="treeHolder">
    <div><?php echo $_style['tree_showtree']; ?>&nbsp;<span class="rootNode" onclick="treeAction(0, '<?php echo addslashes($site_name); ?>');"><b><?php echo $site_name; ?></b></span><div id="treeRoot"></div></div>
</div>

<script type="text/javascript">
// pixelchutes - 6/4/2007
// Set 'treeNodeSelected' class on document node when editing via Context Menu
function setActiveFromContextMenu( doc_id ){
	$$('.treeNodeSelected').removeClass('treeNodeSelected');
	$$('#node'+doc_id+' span')[0].className='treeNodeSelected';
}

// Context menu stuff
function menuHandler(action) {
    switch (action) {
        case 1 :
			setActiveFromContextMenu( itemToChange ); //  pixelchutes
            top.main.document.location.href="index.php?a=3&id=" + itemToChange;
            break
        case 2 :
			setActiveFromContextMenu( itemToChange ); //  pixelchutes
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
        	if (itemToChange != <?php echo $modx->config['site_start']?>) {
            	if(confirm("'" + selectedObjectName + "' <?php echo $_lang['confirm_unpublish']; ?>")==true) {
            		top.main.document.location.href="index.php?a=62&id=" + itemToChange;
            	}
            } else {
            	alert('Document is linked to site_start variable and cannot be unpublished!');
            }
            break
        //case 11 : //Raymond: create folder (currently removed)
        //    top.main.document.location.href="index.php?a=85&pid=" + itemToChange;
        //    break
        case 12 : // TobyL: added  preview document in manager ... comment/uncomment preferred method
            window.open("../index.php?id=" + itemToChange,'previeWin'); //re-use 'new' window
            //window.open("../index.php?id=" + itemToChange); //open in new window each time
            //top.main.document.location.href="../index.php?id=" + itemToChange; //open in manager
            break
        
        default :
            alert('Unknown operation command.');
    }
}

</script>

<!-- Contextual Menu Popup Code -->
<div id="mx_contextmenu" onselectstart="return false;">
    <div id="nameHolder">&nbsp;</div>
    <?php
    constructLink(1, "context_view", $_lang["document_overview"], 1,$theme);
    constructLink(2, "save", $_lang["edit_document"], $modx->hasPermission('edit_document'),$theme);
    constructLink(5, "cancel", $_lang["move_document"], $modx->hasPermission('save_document'),$theme);
    constructLink(7, "copy", $_lang["duplicate_document"], $modx->hasPermission('new_document'),$theme);
    // constructLink(11, "folder", $_lang["create_folder_here"], $modx->hasPermission('new_document'),$theme);
    ?>
    <div class="seperator"></div>
    <?php
    constructLink(3, "newdoc", $_lang["create_document_here"], $modx->hasPermission('new_document'),$theme);
    constructLink(6, "weblink", $_lang["create_weblink_here"], $modx->hasPermission('new_document'),$theme);
    ?>
    <div class="seperator"></div>
    <?php
    constructLink(4, "delete", $_lang["delete_document"], $modx->hasPermission('delete_document'),$theme);
    constructLink(8, "b092", $_lang["undelete_document"], $modx->hasPermission('delete_document'),$theme);
    constructLink(9, "date", $_lang["publish_document"], $modx->hasPermission('publish_document'),$theme);
    constructLink(10, "date", $_lang["unpublish_document"], $modx->hasPermission('publish_document'),$theme);
    ?>
    <div class="seperator"></div>
    <?php
    constructLink(12, "context_view", $_lang["preview_document"], 1,$theme);
    ?>
</div>

</body>
</html>
