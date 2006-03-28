<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>
<html>
	<head>
		<title>Scripter</title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
	<script type="text/javascript" language="JavaScript" src="media/script/bin/webelm.js"></script>
	<script type="text/javascript" src="media/script/error.js"></script>
	<script type="text/javascript">
	// TREE FUNCTIONS - FRAME
	// These functions affect the tree frame and any items that may be pointing to the tree.
	var currentFrameState = 'open';
	var defaultFrameWidth = '280,*';
	var userDefinedFrameWidth = '280,*';
	
	var workText;
	var buildText;

	document.setIncludePath("media/script/bin/");

	function document_oninit(){
		document.include("dynelement");
	}	
	
	
	function hideTreeFrame() {
		userDefinedFrameWidth = parent.document.getElementsByTagName("FRAMESET").item(1).cols;
		currentFrameState = 'closed';
		try {
			var elm = new DynElement('tocText@topFrame');
			if(elm) elm.setInnerHTML("<a href='javascript:parent.scripter.defaultTreeFrame();'><img src='media/images/icons/inet1-15.gif' border=0 align='absmiddle' alt='<?php echo $_lang['show_tree']; ?>'></a>");
			parent.document.getElementsByTagName("FRAMESET").item(1).cols = '0,*';
		} catch(oException) {
			x=window.setTimeout('hideTreeFrame()', 1000);
		}
	}
	
	function defaultTreeFrame() {
		userDefinedFrameWidth = defaultFrameWidth;
		currentFrameState = 'open';
		try {
			var elm = new DynElement('tocText@topFrame')
			if(elm)elm.setInnerHTML("");
			parent.document.getElementsByTagName("FRAMESET").item(1).cols = defaultFrameWidth;
		} catch(oException) {
			z=window.setTimeout('defaultTreeFrame()', 1000);
		}
	}
	
	// TREE FUNCTIONS - Expand/ Collapse
	// These functions affect the expanded/collapsed state of the tree and any items that may be pointing to it
	function expandTree() {
		try {
			parent.menu.d.openAll();  // dtree
		} catch(oException) {
			zz=window.setTimeout('expandTree()', 1000);
		}
	}
	
	function collapseTree() {
		try {
			parent.menu.d.closeAll();  // dtree
		} catch(oException) {
			yy=window.setTimeout('collapseTree()', 1000);
		}
	}
	
	// GENERAL FUNCTIONS - Messages
	// These functions are used for the messaging system
	function updateMsgCount(nrnewmessages, nrtotalmessages, messagesallowed) {
		messagestr = "(" + nrmessages +"/ " + nrtotalmessages +")";
		try {
			var elm = new DynElement('msgCounter@mainMenu');
			/* message count disabled in opera, let's solve it when Opera 9.0 final will be released */
      if(navigator.userAgent.toLowerCase().indexOf("opera")==-1) elm.setInnerHTML(messagestr);
			elm = new DynElement('newMail@topFrame');
			if (elm) elm.style.display = (nrnewmessages>0) ? "inline":"none";
		} 
		catch(oException) {
			nrmessages = nrmessages; messagesallowed = messagesallowed;
			xx=window.setTimeout('updateMsgCount(nrmessages,nrtotalmessages,messagesallowed)', 1000);
		}
	}
	
	function startmsgcount(nr, nrtotal, allow){
		nrmessages = nr; nrtotalmessages=nrtotal; messagesallowed = allow;
		x=window.setTimeout('updateMsgCount(nrmessages,nrtotalmessages,messagesallowed)',1000);
	}
	
	// GENERAL FUNCTIONS - Refresh
	// These functions are used for refreshing the tree or menu
	function reloadtree() {
		var elm = new DynElement('buildText@topFrame');
		if (elm) elm.setInnerHTML("&nbsp;&nbsp;<img src='media/images/icons/b02.gif' align='absmiddle' width='16' height='16'>&nbsp;<?php echo $_lang['loading_doc_tree']; ?>");
		parent.menu.saveFolderState(); // save folder state
		parent.menu.location.reload();
	}
	
	function reloadmenu() {
<?php if($manager_layout==0) { ?>
		var elm = new DynElement('buildText@topFrame')
		if (elm) elm.setInnerHTML("&nbsp;&nbsp;<img src='media/images/icons/b021.gif' align='absmiddle' width='16' height='16'>&nbsp;<?php echo $_lang['loading_menu']; ?>");
		parent.mainMenu.location.reload(); 
<?php } ?>
	}
	
	function startrefresh(rFrame){
		if(rFrame==1){
			var elm = new DynElement('buildText@topFrame');
			if(elm) elm.setInnerHTML("&nbsp;&nbsp;<img src='media/images/icons/b02.gif' align='absmiddle' width='16' height='16'>&nbsp;<b><?php echo $_lang['loading_doc_tree']; ?></b>");
			x=window.setTimeout('reloadtree()',500);
		}
		if(rFrame==2) {
			x=window.setTimeout('reloadmenu()',500);
		}
		if(rFrame==9) {
			x=window.setTimeout('reloadmenu()',500);
			y=window.setTimeout('reloadtree()',500);
		}
	}
	
	// GENERAL FUNCTIONS - Work
	// These functions are used for showing the user the system is working
	function work() {
		var elm = new DynElement('workText@topFrame');
		if (elm) elm.setInnerHTML("&nbsp;<img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif' align='absmiddle' width='16' height='16'>&nbsp;<b><?php echo $_lang['working']; ?></b>");
		else w=window.setTimeout('work()', 50);
	}
	
	function stopWork() {
		var elm = new DynElement('workText@topFrame');
		if (elm) elm.setInnerHTML("");
		else  ww=window.setTimeout('stopWork()', 50);
	}
	
	// GENERAL FUNCTIONS - Remove locks
	// This function removes locks on documents, templates, parsers, and snippets
	function removeLocks() {
		if(confirm("<?php echo $_lang['confirm_remove_locks']; ?>")==true) {
			top.main.document.location.href="index.php?a=67";
		}
	}
	
	</script>
	</head>
	<body>
		Document container for main scripting functions.
		<input type="text" name="focusStealer">
	</body>
</html>
