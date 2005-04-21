<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

// check for edit permissions
if($_SESSION['permissions']['edit_document']!=1 && $_REQUEST['a']==27) {	
	$e->setError(3);
	$e->dumpError();	
}

// check for create permissions
if($_SESSION['permissions']['new_document']!=1 && ($_REQUEST['a']==85 || $_REQUEST['a']==4 || $_REQUEST['a']==72)) {	
	$e->setError(3);
	$e->dumpError();	
}


function isNumber($var)
{
	if(strlen($var)==0) {
		return false;
	}
	for ($i=0;$i<strlen($var);$i++) {
		if ( substr_count ("0123456789", substr ($var, $i, 1) ) == 0 ) {
			return false;
		}
    }
	return true;
}

if(!isset($_REQUEST['id'])) {
	$id=0;
} else {
	$id = $_REQUEST['id'];
}

// make sure the id's a number
if(!isNumber($id)) {
	$e->setError(4);
	$e->dumpError();
}

if($action==27 ) { //editing an existing document

	// check permissions on the document
	include_once "./processors/user_documents_permissions.class.php";
	$udperms = new udperms();
	$udperms->user = $_SESSION['internalKey'];
	$udperms->document = $id;
	$udperms->role = $_SESSION['role'];

	if(!$udperms->checkPermissions()) {
		?><br /><br /><div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['access_permissions']; ?></div><div class="sectionBody">
		<p><?php echo $_lang['access_permission_denied']; ?></p>
		<?php
		include("footer.inc.php");
		exit;	
	}

} else { // new document, check the user is allowed to create a document here
	// check permissions on the parent of this document
	include_once "./processors/user_documents_permissions.class.php";
	$udperms = new udperms();
	$udperms->user = $_SESSION['internalKey'];
	$udperms->document = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0 ;
	$udperms->role = $_SESSION['role'];
	
	if(!$udperms->checkPermissions()) {
		?><br /><br /><div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['access_permissions']; ?></div><div class="sectionBody">
		<p><?php echo $_lang['access_permission_denied']; ?></p>
		<?php
		include("footer.inc.php");
		exit;	
	}
}

// check to see the document isn't locked
$sql = "SELECT internalKey, username FROM $dbase.".$table_prefix."active_users WHERE $dbase.".$table_prefix."active_users.action=27 AND $dbase.".$table_prefix."active_users.id=$id";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit>1) {
	for ($i=0;$i<$limit;$i++) {
		$lock = mysql_fetch_assoc($rs);
		if($lock['internalKey']!=$_SESSION['internalKey']) {		
			$msg = "The document is currently being edited by ".$lock['username']." and cannot be opened.";
			$e->setError(5, $msg);
			$e->dumpError();
		}
	}
} 
// end check for lock


// get document groups for current user
if($_SESSION['docgroups']) {
	$docgrp = implode(",",$_SESSION['docgroups']);
}

if(isset($_GET['id'])) {
	$tblsc = $dbase.".".$table_prefix."site_content";
	$tbldg = $dbase.".".$table_prefix."document_groups";
	$tbldgn = $dbase.".".$table_prefix."documentgroup_names";
	$sql = "SELECT DISTINCT sc.* 
			FROM $tblsc sc 
			LEFT JOIN $tbldg dg on dg.document = sc.id
			LEFT JOIN $tbldgn dgn ON dgn.id = dg.document_group
			WHERE sc.id = $id 
			AND (1='".$_SESSION['role']."' OR NOT(dgn.private_memgroup<=>1)".(!$docgrp ? "":" OR dg.document_group IN ($docgrp)").");";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit>1) {
			$e->setError(6);
			$e->dumpError();
	}
	if($limit<1) {
			$e->setError(7);
			$e->dumpError();
	}
	$content = mysql_fetch_assoc($rs);
} else {
	$content = array();
}

// get list of site keywords, code by stevew!
$sql = "SELECT * FROM $dbase.".$table_prefix."site_keywords";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit > 0) {
	for($i=0;$i<$limit;$i++) {
		$row = mysql_fetch_assoc($rs);
		$keywords[$row['id']] = $row['keyword'];
	}
} else {
	$keywords = array();
}

if(isset($content['id']) && count($keywords) > 0) {
	// get id of documents selected keywords
	$sql = "SELECT keyword_id FROM $dbase.".$table_prefix."keyword_xref WHERE content_id = ".$content['id'];
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	//echo "\n<!-- sql: $sql -->\n";
	//$limit = 0;
	if($limit > 0) 	{
		for($i=0;$i<$limit;$i++) {
			$row = mysql_fetch_assoc($rs);
			$keywords_selected[$row['keyword_id']] = "selected";
		}
	} else {
		$keywords_selected = array();
	}
}

?>
<style>
body {
	overflow-x : hidden; /* stupid hack for equally stupid MSIE */
}
</style>
<script language="JavaScript" src="media/script/datefunctions.js"></script>
<script language="JavaScript">
function winOpen(url,width,height)
{
	var ManualWindow;
	if (!ManualWindow || ManualWindow.closed)
	{
		ManualWindow = window.open(url,'ManualWindow',"toolbar=1,location=1,directories=1,status=1," +
		 "menubar=1,scrollbars=1,resizable=yes,width=" + width + ",height=" + height + 
		 ",screenX=200,screenY=200");
		
		if (!ManualWindow.opener)
		{
			ManualWindow.opener = window
		}
	}
	else
	{
		ManualWindow.location.href = url;
		self.ManualWindow.focus();
	}
}

function changestate(element) {
	currval = eval(element).value;
	if(currval==1) {
		eval(element).value=0;
	} else {
		eval(element).value=1;
	}
}

function deletedocument() {
	if(confirm("<?php echo $_lang['confirm_delete_document']; ?>")==true) {
		document.location.href="index.php?id=" + document.mutate.id.value + "&a=6";
	}
}

function previewdocument() {
	// Modified by Raymond
	/*if(confirm("<?php echo $_lang['confirm_preview']; ?>")==true) {
		winOpen("../index.php?id=" + document.mutate.id.value + "&manprev=z", 900, 700);		
	}	
	*/	
	var win = window.frames['preview'];
	url = "../index.php?id=" + document.mutate.id.value + "&manprev=z";
	nQ = "id=" + document.mutate.id.value + "&manprev=z"; // new querysting
	oQ = (win.location.href.split("?"))[1]; // old querysting
	if (nQ != oQ) {
		win.location.href = url;
		win.alreadyPreviewed = true;
	}
}

// Added by Raymond
var modVariables = [];
function setVariableModified(fieldName){
	var i, isDirty, mv = modVariables;
	for(i=0;i<mv.length;i++){
		if (mv[i]==fieldName) {
			isDirty=true;
		}
	}
	if (!isDirty) {
		mv[mv.length]=fieldName;
		var f = document.forms['mutate'];
		f.variablesmodified.value=mv.join(",");
	}
}

function saveRefreshPreview(){
	var f = document.forms['mutate'];
	documentDirty=false;	
	f.target = "preview";
	f.refresh_preview.value=1;
	f.save.click();	
	setTimeout("document.forms['mutate'].target='';document.forms['mutate'].refresh_preview.value=0",100);
}
// end modifications

parent.menu.ca = "parent";

try {
	top.menu.Sync(<?php echo $id; ?>);
} catch(oException) {
	xyy=window.setTimeout("loadagain(<?php echo $id; ?>)", 1000);
}

function setParent(pId, pName) {
	if(pId==0 || checkParentChildRelation(pId, pName)){
		document.mutate.parent.value=pId;
		document.getElementById('parentName').innerHTML = pId + " (" + pName + ")";
	}
}

// check if the selected parent is a child of this document
function checkParentChildRelation(pId, pName) {
	var sp;
	var id = document.mutate.id.value;
	var tdoc = parent.menu.document;
	var pn = (tdoc.getElementById) ? tdoc.getElementById("node"+pId) : tdoc.all["node"+pId];
	if(!pn) return;
	if (pn.id.substr(4)==id) {
		alert("<?php echo $_lang['illegal_parent_self']; ?>");
		return;
	}
	else {
		while (pn.getAttribute("p")>0) {
			pId = pn.getAttribute("p");
			pn = (tdoc.getElementById) ? tdoc.getElementById("node"+pId) : tdoc.all["node"+pId];
			if (pn.id.substr(4)==id) {
				alert("<?php echo $_lang['illegal_parent_child']; ?>");
				return;
			}
		}
	}
	return true;
}

function clearSelection() {
	var opt = document.mutate.elements["keywords[]"].options;
	for(i = 0; i < opt.length; i++) {
		opt[i].selected = false;
	}
}

// ADDED BY S BRENNAN
var curTemplate = 0;
var curTemplateIndex = 0;
function storeCurTemplate(){
	var dropTemplate = document.getElementById('template');
	if (dropTemplate) for (var i=0; i<dropTemplate.length; i++){
		if (dropTemplate[i].selected){
			curTemplate = dropTemplate[i].value;
			curTemplateIndex = i;
		}
	}
}
function templateWarning(){
	var dropTemplate = document.getElementById('template');
	if (dropTemplate) for (var i=0; i<dropTemplate.length; i++){
		if (dropTemplate[i].selected){
			newTemplate = dropTemplate[i].value;
		}
	}
	if (curTemplate == newTemplate){return;}
	
	if (confirm('<?=$_lang['tmplvar_change_template_msg']?>')){
		var redirectURL = "index.php?newtemplate=" + newTemplate + "&a=<?php echo $action; ?>";
		if (<?php echo $action; ?> == 27){
			redirectURL += "&id=<?php echo $id; ?>";
		}
		documentDirty=false;
		location.href=redirectURL;
	}
	else{
		dropTemplate[curTemplateIndex].selected = true;
	}
}
// END ADDED BY S BRENNAN

/** 
 * Snippet properties 
 */

var snippetParams = [];		// Snippet Params
var currentParams = [];		// Current Params
var lastsp, lastmod = [];

function showParameters(ctrl) {
	var c,p,df,cp;
	var ar,desc,value,key,dt;
	
	cp = [];
	currentParams = []; // reset;

	if (ctrl) f = ctrl.form;
	else {
		f= document.forms['mutate'];
		ctrl = f.snippetlist;
	}

	// get display format
	df = "";//lastsp = ctrl.options[ctrl.selectedIndex].value; 

	// load last modified param values
	if (lastmod[df]) cp = lastmod[df].split("&");
	for(p in cp){
		cp[p]=(cp[p]+'').replace(/^\s|\s$/,""); // trim
		ar = cp[p].split("=");
		currentParams[ar[0]]=ar[1];
	}

	// setup parameters
	dp = (snippetParams[df]) ? snippetParams[df].split("&"):[""];
	if(dp) {
		t='<table width="100%" style="margin-bottom:3px;margin-left:14px;background-color:#EEEEEE" cellpadding="2" cellspacing="1"><thead><tr><td width="50%"><?php echo $_lang['parameter']; ?></td><td width="50%"><?php echo $_lang['value']; ?></td></tr></thead>';
		for(p in dp) {
			dp[p]=(dp[p]+'').replace(/^\s|\s$/,""); // trim
			ar = dp[p].split("=");
			key = ar[0]		// param
			ar = (ar[1]+'').split(";");
			desc = ar[0];	// description
			dt = ar[1];		// data type	
			value = decode((currentParams[key]) ? currentParams[key]:(dt=='list') ? ar[3] : (ar[2])? ar[2]:'');			
			if (value!=currentParams[key]) currentParams[key] = value;
			value = (value+'').replace(/^\s|\s$/,""); // trim
			if (dt) {
				switch(dt) {
				case 'int':
					c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" />';
					break;
				case 'list':
					c = '<select name="prop_'+key+'" height="1" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
					ls = (ar[2]+'').split(",");
					if(currentParams[key]==ar[2]) currentParams[key] = ls[0]; // use first list item as default
					for(i=0;i<ls.length;i++){						
						c += '<option value="'+ls[i]+'"'+((ls[i]==value)? ' selected="selected"':'')+'>'+ls[i]+'</option>';
					}
					c += '</select>';
					break;
				default:  // string
					c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" />';
					break;

				}
				t +='<tr><td bgcolor="#FFFFFF" width="50%">'+desc+'</td><td bgcolor="#FFFFFF" width="50%">'+c+'</td></tr>';
			};
		}
		t+='</table>';
		td = (document.getElementById) ? document.getElementById('snippetparams'):document.all['snippetparams'];	
		td.innerHTML = t;
	}
	implodeParameters();
}

function setParameter(key,dt,ctrl) {
	var v;	
	if(!ctrl) return null;
	switch (dt) {
		case 'int':
			ctrl.value = parseInt(ctrl.value);
			if(isNaN(ctrl.value)) ctrl.value = 0;
			v = ctrl.value;
			break;
		case 'list':
			v = ctrl.options[ctrl.selectedIndex].value;
			break;
		default:
			v = ctrl.value+'';
			break;
	}
	currentParams[key] = v;
	implodeParameters();
}

function resetParameters() {
 	document.mutate.params.value = "";
 	lastmod[lastsp]="";
 	showParameters(); 	
}
// implode parameters
function implodeParameters(){
	var v, p, s='';	
	for(p in currentParams){
		v = currentParams[p];
		if(v) s += '&'+p+'='+ encode(v);
	}
	//document.forms['mutate'].params.value = s;
	if (lastsp) lastmod[lastsp] = s;
}

function encode(s){
	s=s+'';
	s = s.replace(/\=/g,'%3D'); // =
	s = s.replace(/\&/g,'%26'); // &
	return s;
}

function decode(s){
	s=s+'';
	s = s.replace(/\%3D/g,'='); // =
	s = s.replace(/\%26/g,'&'); // &
	return s;
}

</script>

<form name="mutate" method="post" enctype="multipart/form-data" action="index.php">
<?php
	// invoke OnDocFormPrerender event
	$evtOut = $modx->invokeEvent("OnDocFormPrerender",array("id" => $id));
	echo implode("",$evtOut);
?>
<input type="hidden" name="a" value="5">
<input type="hidden" name="id" value="<?php echo $content['id'];?>">
<input type="hidden" name="mode" value="<?php echo $_GET['a'];?>">
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo isset($upload_maxsize)? $upload_maxsize:1048576; ?>">
<input type="hidden" name="refresh_preview" value="0">
<input type="hidden" name="variablesmodified" value="">

<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['edit_document_title']; ?></span>

	<table cellpadding="0" cellspacing="0">
		<tr>
			<td id="Button1" onclick="documentDirty=false; document.mutate.save.click();"><img src="media/images/icons/save.gif" align="absmiddle"> <?php echo $_lang['save']; ?></td>
				<script>createButton(document.getElementById("Button1"));</script>
			<td id="Button2" onclick="deletedocument();"><img src="media/images/icons/delete.gif" align="absmiddle"> <?php echo $_lang['delete']; ?></span></td>
				<script>createButton(document.getElementById("Button2"));</script>
	<?php if($_GET['a']=='4' || $_GET['a']==72) { ?><script>document.getElementById("Button2").setEnabled(false);</script><?php } ?>
<!--			<td id="Button3" onclick="previewdocument();"><img src="media/images/icons/preview.gif" align="absmiddle"> <?php echo $_lang['preview']; ?></span></td>
				<script>createButton(document.getElementById("Button3"));</script>
	<?php if($_GET['a']=='4' || $_GET['a']==72) { ?><script>document.getElementById("Button3").setEnabled(false);</script><?php } ?>
-->	
			<td id="Button5" onclick="<?php echo $id==0 ? "document.location.href='index.php?a=2';" : "document.location.href='index.php?a=3&id=$id';"; ?>"><img src="media/images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?></td>
				<script>createButton(document.getElementById("Button5"));</script>
		</tr>
	</table>
	<div class="stay">   
	<table border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td><span class="comment">&nbsp;After saving:</span></td>
		<td><input name="stay" type="radio" class="inputBox" value="1"  <?php echo $_GET['stay']=='1' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['stay_new']; ?></span></td> 
		<td><input name="stay" type="radio" class="inputBox" value="2"  <?php echo $_GET['stay']=='2' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['stay']; ?></span></td>
		<td><input name="stay" type="radio" class="inputBox" value=""  <?php echo $_GET['stay']=='' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['close']; ?></span></td>
	</tr>
	</table>
	</div>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['document_setting']; ?></div><div class="sectionBody">
	<link type="text/css" rel="stylesheet" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>tabs.css<?php echo "?$theme_refresher";?>" /> 
	<script type="text/javascript" src="media/script/tabpane.js"></script> 
	<div class="tab-pane" id="documentPane"> 
		<script type="text/javascript">
			tpSettings = new WebFXTabPane(document.getElementById( "documentPane" ),false);
		</script> 

		<!-- General -->
		<div class="tab-page" id="tabGeneral"> 
			<h2 class="tab"><?php echo $_lang["settings_general"] ?></h2> 
			<script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabGeneral" ) );</script> 
			<?php if($content['type']=="reference" || $_REQUEST['a']==72) { 
				echo $_lang['weblink_message'];
			} ?>
			<table width="450" border="0" cellspacing="0" cellpadding="0">
			  <tr style="height: 24px;">
				<td width='100px' align="left"><span class='warning'><?php echo $_lang['document_title']; ?></span></span></td>
				<td ><input name="pagetitle" type="text" maxlength="100" value="<?php echo htmlspecialchars(stripslashes($content['pagetitle']));?>" class="inputBox" style="width:300px;" onChange="documentDirty=true;">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_title_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>
			  <tr style="height: 24px;"> 
				<td width='100px' align="left"><span class='warning'><?php echo $_lang['long_title']; ?></span></span></td> 
				<td ><input name="setitle" type="text" maxlength="120" value="<?php echo htmlspecialchars(stripslashes($content['longtitle']));?>" class="inputBox" style="width:300px;" onChange="documentDirty=true;">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_long_title_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td> 
			  </tr>   
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['document_description']; ?></span></td>
				<td ><input name="description" type="text" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($content['description']));?>" class="inputBox" style="width:300px;" onChange="documentDirty=true;">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_description_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['document_alias']; ?></span></td>
				<td ><input name="alias" type="text" maxlength="100" value="<?php echo stripslashes($content['alias']);?>" class="inputBox" style="width:300px;" onChange="documentDirty=true;">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_alias_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>
			<?php if($content['type']=="reference" || $_REQUEST['a']==72) { ?>
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['weblink']; ?></span></td>
				<td ><input name="ta" type="text" maxlength="255" value="<?php echo !empty($content['content']) ? stripslashes($content['content']) : "http://" ;?>" class="inputBox" style="width:300px;" onChange="documentDirty=true;">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_weblink_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>
			<?php } else { ?>
			  <tr style="height: 24px;">
				<td valign="top" width='100px' align="left"><span class='warning'><?php echo $_lang['document_summary']; ?></span></span></td>
				<td valign="top"><textarea name="introtext" type="text" maxlength="64000"  class="inputBox" rows="3" style="width:300px;" onChange="documentDirty=true;"><?php echo htmlspecialchars(stripslashes($content['introtext']));?></textarea>&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_summary_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>			
			 <?php } ?>
			  <tr style="height: 24px;">
				<td><span class='warning'><?php echo $_lang['page_data_template']; ?></span></td>
				<td>
			<?php
				$sql = "select templatename, id from $dbase.".$table_prefix."site_templates"; 
				$rs = mysql_query($sql); 
			?>
			<select id="template" name="template" class="inputBox" onChange='templateWarning();' style="width:300px">
			<?php
			while ($row = mysql_fetch_assoc($rs)) {
				if(isset($_GET['newtemplate'])){
					$selectedtext = $row['id']==$_GET['newtemplate'] ? "selected='selected'" : "" ;
				}
				else if(isset($content['template'])) {
					$selectedtext = $row['id']==$content['template'] ? "selected='selected'" : "" ;
				} else {
					$selectedtext = $row['id']==$default_template ? "selected='selected'" : "" ;	
				}
			?>
				<option value="<?php echo $row['id']; ?>" <?php echo $selectedtext; ?>><?php echo $row['templatename']; ?></option>
			<?php					
			}
			?>		
				</select>
				&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_template_help']; ?>" onClick="alert(this.alt);" style="cursor:help;">
				</td>
			  </tr>
			  <tr style="height: 24px;">
				<td align="left" style="width:100px;"><span class='warning'><?php echo $_lang['document_opt_menu_index']; ?></span></td>
				<td ><input name="menuindex" type="text" maxlength="3" value="<?php echo $content['menuindex'];?>" class="inputBox" style="width:20px;" onChange="documentDirty=true;">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_menu_index_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['document_parent']; ?></span></td>
				<td valign="top"><?php
			if(isset($_GET['id'])) {
				if($content['parent']==0) {
					$parentname = $site_name;	
				} else {
					$sql = "SELECT pagetitle FROM $dbase.".$table_prefix."site_content WHERE $dbase.".$table_prefix."site_content.id = ".$content['parent'].";";
					$rs = mysql_query($sql);
					$limit = mysql_num_rows($rs);
					if($limit!=1) {
						$e->setError(8);
						$e->dumpError();
					}
					$parentrs = mysql_fetch_assoc($rs);
					$parentname = $parentrs['pagetitle'];
				}
			} else if(isset($_GET['pid'])) {
				if($_GET['pid']==0) {
					$parentname = $site_name;	
				} else {
					$sql = "SELECT pagetitle FROM $dbase.".$table_prefix."site_content WHERE $dbase.".$table_prefix."site_content.id = ".$_GET['pid'].";";
					$rs = mysql_query($sql);
					$limit = mysql_num_rows($rs);
					if($limit!=1) {
						$e->setError(8);
						$e->dumpError();
					}
					$parentrs = mysql_fetch_assoc($rs);
					$parentname = $parentrs['pagetitle'];
				}
			} else {
					$parentname = $site_name;	
					$content['parent']=0;
			}
			?>&nbsp;&nbsp;<b><span id="parentName"><?php echo isset($_REQUEST['pid']) ? $_REQUEST['pid'] : $content['parent']; ?> (<?php echo $parentname; ?>)</span></b>&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_parent_help']; ?>" onClick="alert(this.alt);" style="cursor:help;">
			<input type="hidden" name="parent" value="<?php echo isset($_REQUEST['pid']) ? $_REQUEST['pid'] : $content['parent']; ?>" onChange="documentDirty=true;" />
				</td>
			  </tr>
			</table>
		</div>

		<!-- Settings -->
		<div class="tab-page" id="tabSettings"> 
			<h2 class="tab"><?php echo $_lang["settings_page_settings"] ?></h2> 
			<script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabSettings" ) );</script> 
			<table width="600" border="0" cellspacing="0" cellpadding="0">
			  <tr style="height: 24px;">
				<td width="150"><span class='warning'><?php echo $_lang['document_opt_folder']; ?></span></td>
				<td ><input name="isfoldercheck" type="checkbox" <?php echo ($content['isfolder']==1||$_REQUEST['a']==85) ? "checked" : "" ;?> onClick="changestate(document.mutate.isfolder);"><input type="hidden" name="isfolder" value="<?php echo ($content['isfolder']==1||$_REQUEST['a']==85) ? 1 : 0 ;?>" onChange="documentDirty=true;">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_folder_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
				<td rowspan="10" width="150" align="center" valign='top'><span class='warning'><?php echo $_lang['keywords']; ?></span>&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_keywords_help']; ?>" onClick="alert(this.alt);" style="cursor:help;" align="absmiddle">
				<select multiple name="keywords[]" size="16" class="inputBox" style="width: 140px;" onChange="documentDirty=true;">
					<?php
					$keys = array_keys($keywords);
					for($i=0;$i<count($keys);$i++) {
						$key = $keys[$i];
						$value = $keywords[$key];
						$selected = $keywords_selected[$key];
						echo "<option $selected value=\"$key\">$value\n";
					}
					?>
				</select>
				<br /><br />
				<input type="button" value="<?php echo $_lang['deselect_keywords']; ?>" onClick="clearSelection();">

				</td>

			  </tr>
			<?php if($content['type']!="reference" && $_REQUEST['a']!=72) { ?>  
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['document_opt_richtext']; ?></span></td>
				<td ><input name="richtextcheck" type="checkbox" <?php echo $content['richtext']==0 && $_REQUEST['a']==27 ? "" : "checked" ;?> onClick="changestate(document.mutate.richtext);"><input type="hidden" name="richtext" value="<?php echo $content['richtext']==0 && $_REQUEST['a']==27 ? 0 : 1 ;?>" onChange="documentDirty=true;">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_richtext_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>
			<?php } ?>  
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['document_opt_published']; ?></span></td>
				<td ><input name="publishedcheck" type="checkbox" <?php echo (isset($content['published']) && $content['published']==1) || (!isset($content['published']) && $publish_default==1) ? "checked" : "" ;?> onClick="changestate(document.mutate.published);"><input type="hidden" name="published" value="<?php echo (isset($content['published']) && $content['published']==1) || (!isset($content['published']) && $publish_default==1) ? 1 : 0 ;?>">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_published_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['page_data_publishdate']; ?></span></td>
				<td ><input name="pub_date" type="hidden" value="<?php echo $content['pub_date']=="0" || !isset($content['pub_date']) ? "" : strftime("%d-%m-%Y %H:%M:%S", $content['pub_date']);?>" onBlur="documentDirty=true;">
				<table width="250" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="160" style="border: 1px solid #808080;"><span id="pub_date_show" class="inputBox"><?php echo $content['pub_date']=="0" || !isset($content['pub_date']) ? "<i>(not set)</i>" : strftime("%d-%m-%Y %H:%M:%S", $content['pub_date']);?></span> </td>
					<td>&nbsp;
						<a onClick="documentDirty=false; cal1.popup();" onMouseover="window.status='<?php echo $_lang['select_date']; ?>'; return true;" onMouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="media/images/icons/cal.gif" width="16" height="16" border="0" alt="<?php echo $_lang['select_date']; ?>"></a>
						<a onClick="document.mutate.pub_date.value=''; document.getElementById('pub_date_show').innerHTML='(<?php echo $_lang['not_set']?>)'; return true;" onMouseover="window.status='<?php echo $_lang['remove_date']?>'; return true;" onMouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="media/images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="<?php echo $_lang['remove_date']; ?>"></a>
						&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_publishdate_help']; ?>" onClick="alert(this.alt);" style="cursor:help;">
					</td>
				  </tr>
				</table>

			  </tr>
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['page_data_unpublishdate']; ?></span></td>
				<td ><input name="unpub_date" type="hidden" value="<?php echo $content['unpub_date']=="0" || !isset($content['unpub_date']) ? "" : strftime("%d-%m-%Y %H:%M:%S", $content['unpub_date']); ?>" onBlur="documentDirty=true;"> 
				<table width="250" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="160" style="border: 1px solid #808080;"><span id="unpub_date_show" class="inputBox"><?php echo $content['unpub_date']=="0" || !isset($content['unpub_date']) ? "<i>(not set)</i>" : strftime("%d-%m-%Y %H:%M:%S", $content['unpub_date']);?></span> </td>
					<td>&nbsp;
						<a onClick="documentDirty=false; cal2.popup();" onMouseover="window.status='Select a date'; return true;" onMouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img src="media/images/icons/cal.gif" width="16" height="16" border="0"></a> 
						<a onClick="document.mutate.unpub_date.value=''; document.getElementById('unpub_date_show').innerHTML = '(not set)'; return true;" onMouseover="window.status='Don\'t set an unpublish date'; return true;" onMouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img src="media/images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="No date"></a>
						&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_unpublishdate_help']; ?>" onClick="alert(this.alt);" style="cursor:help;">
					</td>
				  </tr>
				</table>
			   </td>
			  </tr>
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['page_data_searchable']; ?></span></td>
				<td ><input name="searchablecheck" type="checkbox" <?php echo (isset($content['searchable']) && $content['searchable']==1) || (!isset($content['searchable']) && $search_default==1) ? "checked" : "" ;?> onClick="changestate(document.mutate.searchable);"><input type="hidden" name="searchable" value="<?php echo (isset($content['searchable']) && $content['searchable']==1) || (!isset($content['searchable']) && $search_default==1) ? 1 : 0 ;?>" onChange="documentDirty=true;">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_searchable_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>
			<?php if($content['type']!="reference" && $_REQUEST['a']!=72) { ?>  
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['page_data_cacheable']; ?></span></td>
				<td ><input name="cacheablecheck" type="checkbox" <?php echo (isset($content['cacheable']) && $content['cacheable']==1) || (!isset($content['cacheable']) && $cache_default==1) ? "checked" : "" ;?> onClick="changestate(document.mutate.cacheable);"><input type="hidden" name="cacheable" value="<?php echo (isset($content['cacheable']) && $content['cacheable']==1) || (!isset($content['cacheable']) && $cache_default==1) ? 1 : 0 ;?>" onChange="documentDirty=true;">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_cacheable_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['document_opt_emptycache']; ?></span></td>
				<td ><input name="syncsitecheck" type="checkbox" checked onClick="changestate(document.mutate.syncsite);"><input type="hidden" name="syncsite" value="1">&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_emptycache_help']; ?>" onClick="alert(this.alt);" style="cursor:help;"></td>
			  </tr>
			<?php if($_SESSION['role']==1) { ?>   
			  <tr style="height: 24px;">
				<td ><span class='warning'><?php echo $_lang['page_data_contentType']; ?></span></td>
				<td >
						<select name="contentType" class="inputBox" onChange='documentDirty=true;' style="width:150px">
							<option value="text/html" <?php echo $content['contentType']=="text/html" ? "selected='selected'" : "" ;?>>text/html</option>
							<option value="text/plain" <?php echo $content['contentType']=="text/plain" ? "selected='selected'" : "" ;?>>text/plain</option>
							<option value="text/xml" <?php echo $content['contentType']=="text/xml" ? "selected='selected'" : "" ;?>>text/xml</option>
						</select>
				&nbsp;&nbsp;<img src="media/images/icons/b02_trans.gif" onMouseover="this.src='media/images/icons/b02.gif';" onMouseout="this.src='media/images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_contentType_help']; ?>" onClick="alert(this.alt);" style="cursor:help;">
				</td>
			  </tr>  
			<?php } else { ?>  
			<input type="hidden" name="contentType" value="<?php echo isset($content['contentType']) ? $content['contentType'] : "text/html"; ?>" />
			<?php } ?>  
			<input type="hidden" name="type" value="document">  
			<?php } else { ?>
			<input type="hidden" name="contentType" value="text/html" />
			<input type="hidden" name="cacheable" value="0" />
			<input type="hidden" name="syncsite" value="1" />
			<input type="hidden" name="template" value="0" />
			<input type="hidden" name="richtext" value="0" />
			<input type="hidden" name="type" value="reference" />
			<?php } ?>    
			</table>
		</div>	

		<!-- Snippets -->
		<div class="tab-page" id="tabSnippets"> 
			<h2 class="tab"><?php echo $_lang["settings_snippets"] ?></h2> 
			<script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabSnippets" ) );</script> 
			<table border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td align="left" valign="top" width="10%">
				<?php echo $_lang['snippets_availabe']; ?>:<br />
				<table border="0" cellspacing="0" cellpadding="0">
				<tr><td width="1">
				<select size="10" name="snippets" style="width:400px;">
				<?php
					$sql = "SELECT * FROM $dbase.".$table_prefix."site_snippets ORDER BY name ASC;"; 
					$rs = mysql_query($sql); 
					$limit = mysql_num_rows($rs); 
					for ($i = 0; $i < $limit; $i++) { 
						$row=mysql_fetch_assoc($rs); 
						//$sp .= "snippetParams['".$row['id']."']='".$row['properties']."';\n";
						echo "<option value='".$row['id']."'>".$row['name']."</option>";
					}
				?>
				</select><?php //echo "<script>$sp;</script>"; ?>
				</td><td><input type="hidden" onclick="" value="<?php echo $_lang['insert'];?>" title="<?php echo $_lang['insert_snippet'];?>" /></td>
				</tr>
				<tr><td colspan="2"></td></tr>
				</table>
				<!--select size="10" name="snippetlist" style="width:250px" onclick="showParameters(this)"></select -->
				</td>
				<!--td align="left" valign="top" id="snippetparams" width="90%" style="padding-top:35px">&nbsp;</td-->
			  </tr>
			</table>
		</div>		
		

	<?php if($_GET['a']!='4' && $_GET['a']!=72) { ?>
		<!-- Preview -->
		<div class="tab-page" id="tabPreview"> 
			<h2 class="tab"><img src="media/images/icons/preview.gif" align="absmiddle" height="12"> <?php echo $_lang['preview']; ?></h2> 
			<script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPreview" ), previewdocument );</script> 
			<table width="96%" border="0"><tr><td><?php echo $_lang['preview_msg'];?></td></tr>
			<tr><td><iframe name="preview" frameborder="0" width="100%" height="400" style="border:1px solid #E0E0E0"></iframe></td></tr>
			</table>
		</div>		
	<?php } ?>
		
		
	</div>
</div>

<!-- Content -->
<?php if($content['type']=="document" || $_REQUEST['a']==4) { ?>
<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['document_content']; ?></div><div class="sectionBody">
	<?php
	if(($content['richtext']==1 || $_GET['a']==4) && $use_editor==1) {
	if($which_editor==2) {
	?>
	<script type="text/javascript">
		_editor_lang = "en";
		_editor_url = "media/editor/";
	</script> 

	<script type="text/javascript" src="media/editor/editor.js"></script>
	<style type="text/css">@import url(media/editor/editor.css);</style>

	<script type="text/javascript" >
	// load up the plugins...
	<?php if($im_plugin==1) { ?>
		HTMLArea.loadPlugin("ImageManager"); 
	<?php } ?>
		HTMLArea.loadPlugin("EnterParagraphs");
	<?php if($to_plugin==1) { ?>
		HTMLArea.loadPlugin("TableOperations"); 
	<?php } ?>
	<?php if($cm_plugin==1) { ?>
		HTMLArea.loadPlugin("ContextMenu"); 
	<?php } ?>
		HTMLArea.loadPlugin("ListType");
	</script>

	<textarea id="ta" name="ta" style="width:100%; height: 400px;" onChange="documentDirty=true;"><?php
	if(! empty($content['content'])) {
		if(substr($im_plugin_base_url, -1) != '/') {
			$base_url = $im_plugin_base_url . '/';
		} else {
			$base_url = $im_plugin_base_url;
		}
		$elements = parse_url($base_url);
		$image_path = $elements['path'];
		// make sure image path ends with a /
		if(substr($image_path, -1) != '/') {
			$image_path .= '/';
		}
		$etomite_root = dirname(dirname($_SERVER['PHP_SELF']));
		$image_prefix = substr($image_path, strlen($etomite_root));
		if(substr($image_prefix, -1) != '/') {
			$image_prefix .= '/';
		}
		// escape / in path
		$image_prefix = str_replace('/', '\/', $image_prefix);
		$newcontent = preg_replace("/(<img[^>]+src=['\"])($image_prefix)([^'\"]+['\"][^>]*>)/", "\${1}$base_url\${3}", $content['content']);
		echo htmlspecialchars($newcontent);
	}
	?></textarea>
	
	<?php
	} elseif($which_editor==1) {
	?>
	<!-- tinyMCE -->
	<textarea id="ta" name="ta" style="width:100%; height: 400px;" onChange="documentDirty=true;"><?php
	if(! empty($content['content'])) {
		if(substr($im_plugin_base_url, -1) != '/') {
			$base_url = $im_plugin_base_url . '/';
		} else {
			$base_url = $im_plugin_base_url;
		}
		$elements = parse_url($base_url);
		$image_path = $elements['path'];
		// make sure image path ends with a /
		if(substr($image_path, -1) != '/') {
			$image_path .= '/';
		}
		$etomite_root = dirname(dirname($_SERVER['PHP_SELF']));
		$image_prefix = substr($image_path, strlen($etomite_root));
		if(substr($image_prefix, -1) != '/') {
			$image_prefix .= '/';
		}
		// escape / in path
		$image_prefix = str_replace('/', '\/', $image_prefix);
		$newcontent = preg_replace("/(<img[^>]+src=['\"])($image_prefix)([^'\"]+['\"][^>]*>)/", "\${1}$base_url\${3}", $content['content']);
		echo htmlspecialchars($newcontent);
	}
	?></textarea>
	<script language="javascript" type="text/javascript" src="media/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript">
	   tinyMCE.init({
			theme : "advanced",
			mode : "exact",
			elements : "ta",
			<?php echo !empty($tiny_css_path) ? "content_css : '$tiny_css_path'," : "" ?>
			<?php echo !empty($tiny_css_selectors) ? "theme_advanced_styles : '$tiny_css_selectors'," : "" ?>
			debug : false
	   });
	</script>
	<!-- /tinyMCE -->
	<?php
	}
	} else {
	?>
	<div style="width:100%"><textarea id="ta" name="ta" style="width:100%; height: 400px;" onChange="documentDirty=true;"><?php	echo htmlspecialchars($content['content']); ?></textarea> </div>
	<?php
	}
	?>
</div>
<?php } ?>

<!-- Template Variables -->
<?php 
	// Modified by Raymond for TV - Orig Added by Apodigm 09-06-2004- DocVars - web@apodigm.com			
	if($content['type']=="document" || $_REQUEST['a']==4) { 
?>
<div class='sectionHeader'><img src='media/images/misc/dot.gif' alt='.' />&nbsp;<?php echo $_lang["settings_templvars"]; ?></div><div class="sectionBody">
<?php
		// MODIFIED BY S.BRENNAN
		$template = $default_template;
		if(isset($_GET['newtemplate'])){
			$template = $_GET['newtemplate'];
		}
		else{
			if(isset($content['template'])) {
				$template = $content['template'];
			}
		}
		
		$sql = "SELECT DISTINCT tv.*, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
		$sql.= "FROM $dbase.".$table_prefix."site_tmplvars tv ";
		$sql.= "INNER JOIN $dbase.".$table_prefix."site_tmplvar_templates tvtpl ON tvtpl.tmplvarid = tv.id ";
		$sql.= "LEFT JOIN $dbase.".$table_prefix."site_tmplvar_contentvalues tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = $id ";
		$sql.= "LEFT JOIN $dbase.".$table_prefix."site_tmplvar_access tva ON tva.tmplvarid=tv.id  ";			
		$sql.= "WHERE tvtpl.templateid = ".$template." AND (1='".$_SESSION['role']."' OR ISNULL(tva.documentgroup)".((!$docgrp)? "":" OR tva.documentgroup IN ($docgrp)").") ORDER BY tv.rank;";
		$rs = mysql_query($sql); 
		$limit = mysql_num_rows($rs);
		if($limit>0){
			echo "<table style='position:relative' border='0' cellspacing='0' cellpadding='3' width='96%'>";
			require('tmplvars.inc.php');
			require('tmplvars.commands.inc.php');
			for ($i=0; $i<$limit; $i++) {
				// go through and display all the document variables
				$row = mysql_fetch_assoc($rs);
				if($row['type']=='htmlarea'){
					if (is_array($replace_htmlarea))
						$replace_htmlarea = array_merge($replace_htmlarea,array($row['name']));
					else
						$replace_htmlarea = array($row['name']);
				}
				// splitter
				if($i>0 && $i<$limit) echo '<tr><td colspan="2"><div class="split"></div></td></tr>';
		?>
			  <tr style="height: 24px;">
				<td align="left" valign="top" width="150">
					<span class='warning'><?php echo $row['caption']; ?></span><br /><span class='comment'><?php echo $row['description']; ?></span>
				</td>
				<td valign="top" style="position:relative">
				<?php echo renderFormElement($row['type'], $row['name'], $row['default_text'], $row['elements'], $row['value'], ' style="width:300px;"'); ?>
				</td>
				</td>
			  </tr>			  
		<?php
			}  //loop through all template variables	
		?>
		</table>
		<?php
		}  
		else {
			echo $_lang['tmplvars_novars'];
		}//end check to see if there are template variables to display
?>
</div>
<?php
	} //end check to make sure it is not a weblink
	// End modification
?> 



<?php
if($use_udperms==1) {
$groupsarray = array();

if($_GET['a']=='27') { // fetch permissions on the document from the database
	$sql = "SELECT * FROM $dbase.".$table_prefix."document_groups where document=".$id;
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	for ($i = 0; $i < $limit; $i++) { 
		$currentgroup=mysql_fetch_assoc($rs);
		$groupsarray[$i] = $currentgroup['document_group'];
	}
} else { // set permissions on the document based on the permissions of the parent document
	if(!empty($_REQUEST['pid'])) {
		$sql = "SELECT * FROM $dbase.".$table_prefix."document_groups where document=".$_REQUEST['pid'];
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		for ($i = 0; $i < $limit; $i++) { 
			$currentgroup=mysql_fetch_assoc($rs);
			$groupsarray[$i] = $currentgroup['document_group'];
		}
	}
}
?>	

<!-- Acccess Permissions -->
<?php if($_SESSION['permissions']['access_permissions']==1) { ?>
<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['access_permissions']; ?></div><div class="sectionBody">
<script>
	function makePublic(b){
		var notPublic=false;
		var f=document.forms['mutate'];
		var chkpub = f['chkalldocs'];
		var chks = f['docgroups[]'];
		if (!b && chkpub) {
			if(!chks.length) notPublic=chks.checked;
			else for(i=0;i<chks.length;i++) if(chks[i].checked) notPublic=true;
			chkpub.checked=!notPublic;
		}
		else {
			if(!chks.length) chks.checked = (b)? false:chks.checked;
			else for(i=0;i<chks.length;i++) if (b) chks[i].checked=false;
			chkpub.checked=true;			
		}
	}
</script>
<?php echo $_lang['access_permissions_docs_message']; ?><p />
<?php
	}		
	$sql = "SELECT name, id FROM $dbase.".$table_prefix."documentgroup_names"; 
	$rs = mysql_query($sql); 
	$limit = mysql_num_rows($rs);
	for($i=0; $i<$limit; $i++) {
		$row=mysql_fetch_assoc($rs);
		$checked = in_array($row['id'], $groupsarray);
		if($_SESSION['permissions']['access_permissions']==1) {
			if($checked) $notPublic = true;
			$chks .= "<input type='checkbox' name='docgroups[]' value='".$row['id']."' ".($checked ? "checked='checked'" : '')." onclick=\"makePublic(false)\" />".$row['name']."<br />";
		} else {
			if($checked) echo "<input type='hidden' name='docgroups[]'  value='".$row['id']."' />";
		}
	}
	if($_SESSION['permissions']['access_permissions']==1) {
		$chks = "<input type='checkbox' name='chkalldocs' ".(!$notPublic ? "checked='checked'" : '')." onclick=\"makePublic(true)\" /><span class='warning'>".$_lang['all_doc_groups']."</span><br />".$chks;
	}
	echo $chks;
?>
</div>
<?php
}
?>

<input type="submit" name="save" style="display:none">
<?php
	// invoke OnDocFormRender event
	$evtOut = $modx->invokeEvent("OnDocFormRender",array("id" => $id));
	echo implode("",$evtOut);
?>
</form>
<script>//setTimeout('showParameters()',10);</script>

<?php 

// MODIFIED BY S.BRENNAN

if($content['type']=="document" || $_REQUEST['a']==4) {
	if(($content['richtext']==1 || $_GET['a']==4) && $use_editor==1) {
		if($which_editor==2) {
	?>
		<script type="text/javascript">
		function initEditor() {

			var config = new HTMLArea.Config();

			<?php if($strict_editor==1) { ?>
			config.toolbar = [
					[ "formatblock", "space",
					  "bold", "italic", "underline", "strikethrough", "separator",
					  "subscript", "superscript", "separator",
					  "copy", "cut", "paste", "space", "undo", "redo",
					  "orderedlist", "unorderedlist", "separator",
					  "inserthorizontalrule", "createlink", "insertimage", "inserttable", "htmlmode"]
				];
			<?php } ?>

			  editor = new HTMLArea("ta",config);

			<?php if($to_plugin==1) { ?>
				editor.registerPlugin(TableOperations);
			<?php } ?>
				editor.registerPlugin(EnterParagraphs);
			<?php if($cm_plugin==1) { ?>
				editor.registerPlugin(ContextMenu);
			<?php } ?>
			<?php if($strict_editor!=1) { ?>
				editor.registerPlugin(ListType);
			<?php }?>
				
				// generate main htmlarea
				editor.generate();

			 //edited by Apodigm - Docvars
			<?php 
				if(is_array($replace_htmlarea)) {
					foreach($replace_htmlarea as $tag_id){ 
						echo 'HTMLArea.replace("tv'.$tag_id.'", config); ';
					}
				}
			?>
			 //end modification
			  return false;
		}
		document.onload=initEditor();
		storeCurTemplate();
		</script>
		<?
		}
	}
}
// END MODIFIED BY S.BRENNAN
?>

<script type="text/javascript">
	var cal1 = new calendar1(document.forms['mutate'].elements['pub_date'], document.getElementById("pub_date_show"));
	cal1.path="<?php echo str_replace("index.php", "media/", $_SERVER["PHP_SELF"]); ?>";
	cal1.year_scroll = true;
	cal1.time_comp = true;

			
	var cal2 = new calendar1(document.forms['mutate'].elements['unpub_date'], document.getElementById("unpub_date_show"));
	cal2.path="<?php echo str_replace("index.php", "media/", $_SERVER["PHP_SELF"]); ?>";
	cal2.year_scroll = true;
	cal2.time_comp = true;
			
</script>



