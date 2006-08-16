<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if(!$modx->hasPermission('edit_snippet') && $_REQUEST['a']==78) {
	$e->setError(3);
	$e->dumpError();
}
if(!$modx->hasPermission('new_snippet') && $_REQUEST['a']==77) {
	$e->setError(3);
	$e->dumpError();
}

if(isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
} else {
	$id=0;
}

// check to see the snippet editor isn't locked
$sql = "SELECT internalKey, username FROM $dbase.".$table_prefix."active_users WHERE $dbase.".$table_prefix."active_users.action=78 AND $dbase.".$table_prefix."active_users.id=$id";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit>1) {
	for ($i=0;$i<$limit;$i++) {
		$lock = mysql_fetch_assoc($rs);
		if($lock['internalKey']!=$modx->getLoginUserID()) {
			$msg = sprintf($_lang["lock_msg"],$lock['username'],"chunk");
			$e->setError(5, $msg);
			$e->dumpError();
		}
	}
}
// end check for lock

// make sure the id's a number
if(!is_numeric($id)) {
	echo "Passed ID is NaN!";
	exit;
}

if(isset($_GET['id'])) {
	$sql = "SELECT * FROM $dbase.".$table_prefix."site_htmlsnippets WHERE $dbase.".$table_prefix."site_htmlsnippets.id = $id;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit>1) {
		echo "Multiple Chunk sharing same unique id. Not good.<p>";
		exit;
	}
	if($limit<1) {
		echo "Chunk doesn't exist.";
		exit;
	}
	$content = mysql_fetch_assoc($rs);
	$_SESSION['itemname']=$content['name'];
	if($content['locked']==1 && $_SESSION['mgrRole']!=1) {
		$e->setError(3);
		$e->dumpError();
	}
} else {
	$_SESSION['itemname']="New Chunk";
}

// Print RTE Javascript function
$use_rb = ($use_browser==1 ? "true":"false");
$autoLang = isset($fck_editor_autolang) ? $fck_editor_autolang : 0;
echo <<<RTE_SCRIPT
<script language="javascript" type="text/javascript" src="{$base_url}assets/plugins/fckeditor/fckeditor.js"></script>
<script language="javascript" type="text/javascript">
	function setRichText(){

		var elm = document.getElementById('switcher');
		if(elm) elm.style.display='none';

		var rte = new FCKeditor('post') ;
		var FCKImageBrowserURL = '{$base_url}manager/media/browser/mcpuk/browser.html?Type=images&Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}';
		var FCKLinkBrowserURL = '{$base_url}manager/media/browser/mcpuk/browser.html?Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}';
		var FCKFlashBrowserURL = '{$base_url}manager/media/browser/mcpuk/browser.html?Type=flash&Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}';
		var FCKAutoLanguage = {$autoLang};
		var FCKEditorAreaCSS = '{$editor_css_path}';

		rte.Height = '400';
		rte.BaseHref = '{$site_url}';
		rte.BasePath = '{$base_url}assets/plugins/fckeditor/';
		rte.Config['ImageBrowser'] = {$use_rb};
		rte.Config['ImageBrowserURL'] = FCKImageBrowserURL;
		rte.Config['LinkBrowser'] = {$use_rb};
		rte.Config['LinkBrowserURL'] = FCKLinkBrowserURL;
		rte.Config['FlashBrowser'] = {$use_rb};
		rte.Config['FlashBrowserURL'] = FCKFlashBrowserURL;
		rte.Config['SpellChecker'] = 'SpellerPages';
		rte.Config['CustomConfigurationsPath'] = '{$base_url}assets/plugins/fckeditor/custom_config.js';
		rte.ToolbarSet = 'standard';
		rte.Config['EditorAreaCSS'] = FCKEditorAreaCSS;

		rte.Config['FullPage'] = false ;
		rte.ReplaceTextarea();
	}
</script>
RTE_SCRIPT;
?>

<script language="JavaScript">


function duplicaterecord(){
	if(confirm("<?php echo $_lang['confirm_duplicate_record'] ?>")==true) {
		documentDirty=false;
		document.location.href="index.php?id=<?php echo $_REQUEST['id']; ?>&a=97";
	}
}

function deletedocument() {
	if(confirm("<?php echo $_lang['confirm_delete_htmlsnippet']; ?>")==true) {
		documentDirty=false;
		document.location.href="index.php?id=" + document.mutate.id.value + "&a=80";
	}
}

</script>

<form name="mutate" method="post" action="index.php?a=79">
<?php
	// invoke OnChunkFormPrerender event
	$evtOut = $modx->invokeEvent("OnChunkFormPrerender",array("id" => $id));
	if(is_array($evtOut)) echo implode("",$evtOut);
?>
<input type="hidden" name="id" value="<?php echo $content['id'];?>">
<input type="hidden" name="mode" value="<?php echo $_GET['a'];?>">

<div class="subTitle">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['htmlsnippet_title']; ?></span>

	<table cellpadding="0" cellspacing="0">
		<td id="Button1" onclick="documentDirty=false; document.mutate.save.click(); saveWait('mutate');"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang['save']; ?></td>
			<script>createButton(document.getElementById("Button1"));</script>
<?php if($_GET['a']=='78') { ?>
		<td id="Button2" onclick="duplicaterecord();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle"> <?php echo $_lang["duplicate"]; ?></td>
			<script>createButton(document.getElementById("Button2"));</script>
		<td id="Button3" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle"> <?php echo $_lang['delete']; ?></span></td>
			<script>createButton(document.getElementById("Button3"));</script>
<?php } ?>
		<td id="Button4" onclick="document.location.href='index.php?a=76';"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?></td>
			<script>createButton(document.getElementById("Button4"));</script>
	</table>
	<div class="stay">
	<table border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td><span class="comment">&nbsp;<?php echo $_lang["after_saving"];?>:</span></td>
		<td><input name="stay" type="radio" class="inputBox" value="1"  <?php echo $_GET['stay']=='1' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['stay_new']; ?></span></td>
		<td><input name="stay" type="radio" class="inputBox" value="2"  <?php echo $_GET['stay']=='2' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['stay']; ?></span></td>
		<td><input name="stay" type="radio" class="inputBox" value=""  <?php echo $_GET['stay']=='' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['close']; ?></span></td>
	</tr>
	</table>
	</div>
</div>


<div class="sectionHeader"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['htmlsnippet_title']; ?></div><div class="sectionBody">
<?php echo $_lang['htmlsnippet_msg']; ?><p />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><?php echo $_lang['htmlsnippet_name']; ?>:</td>
    <td align="left"><span style="font-family:'Courier New', Courier, mono">{{</span><input name="name" type="text" maxlength="100" value="<?php echo $content['name'];?>" class="inputBox" style="width:140px;" onChange='documentDirty=true;'><span style="font-family:'Courier New', Courier, mono">}}</span><span class="warning" id='savingMessage'>&nbsp;</span></td>
  </tr>
  <tr>
    <td align="left"><?php echo $_lang['htmlsnippet_desc']; ?>:&nbsp;&nbsp;</td>
    <td align="left"><span style="font-family:'Courier New', Courier, mono">&nbsp;&nbsp;</span><input name="description" type="text" maxlength="255" value="<?php echo $content['description'];?>" class="inputBox" style="width:300px;" onChange='documentDirty=true;'></td>
  </tr>
  <tr>
	<td align="left"><?php echo $_lang['existing_category']; ?>:&nbsp;&nbsp;</td>
	<td align="left"><span style="font-family:'Courier New', Courier, mono">&nbsp;&nbsp;</span><select name="categoryid" style="width:300px;" onChange='documentDirty=true;'>
	<option>&nbsp;</option>
	<?php
        include_once "categories.inc.php";
		$ds = getCategories();
		if($ds) foreach($ds as $n=>$v){
			echo "<option value='".$v['id']."'".($content["category"]==$v["id"]? " selected='selected'":"").">".htmlspecialchars($v["category"])."</option>";
		}
	?>
	</select>
	</td>
  </tr>
  <tr>
	<td align="left" valign="top" style="padding-top:5px;"><?php echo $_lang['new_category']; ?>:</td>
	<td align="left" valign="top" style="padding-top:5px;"><span style="font-family:'Courier New', Courier, mono">&nbsp;&nbsp;</span><input name="newcategory" type="text" maxlength="45" value="" class="inputBox" style="width:300px;" onChange='documentDirty=true;'></td>
  </tr>
  <tr>
    <td align="left" colspan="2"><input name="locked" type="checkbox" <?php echo $content['locked']==1 ? "checked='checked'" : "" ;?> class="inputBox" /> <?php echo $_lang['lock_htmlsnippet']; ?> <span class="comment"><?php echo $_lang['lock_snippet_msg']; ?></span></td>
  </tr>
</table>
	<!-- HTML text editor start -->
	<div style="width:100%;position:relative">
	    <div style="padding:1px; width:100%; height:16px; background-color:#eeeeee; border:1px solid #e0e0e0;margin-top:5px">
	    	<span style="float:left;color:brown;font-weight:bold; padding:3px">&nbsp;<?php echo $_lang['chunk_code']; ?></span>
	    	<a id="switcher" style="float:right;color:#707070;padding:3px;cursor:pointer" onclick="setRichText()"><?php echo $_lang['switch_to_rte']; ?> &gt;&gt;</a>
	   	</div>
		<textarea name="post" style="width:100%; height: 370px;" onChange='documentDirty=true;'><?php echo htmlspecialchars($content['snippet']); ?></textarea>
	</div>
	<!-- HTML text editor end -->
<input type="submit" name="save" style="display:none">
</div>
<?php
	// invoke OnChunkFormRender event
	$evtOut = $modx->invokeEvent("OnChunkFormRender",array("id" => $id));
	if(is_array($evtOut)) echo implode("",$evtOut);
?>
</form>
