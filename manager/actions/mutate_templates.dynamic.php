<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('edit_template') && $_REQUEST['a']==16) {
	$e->setError(3);
	$e->dumpError();
}
if(!$modx->hasPermission('new_template') && $_REQUEST['a']==19) {
	$e->setError(3);
	$e->dumpError();
}

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
	// check to see the template editor isn't locked
	$sql = "SELECT internalKey, username FROM $dbase.".$table_prefix."active_users WHERE $dbase.".$table_prefix."active_users.action=16 AND $dbase.".$table_prefix."active_users.id=$id";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit>1) {
		for ($i=0;$i<$limit;$i++) {
			$lock = mysql_fetch_assoc($rs);
			if($lock['internalKey']!=$modx->getLoginUserID()) {
				$msg = sprintf($_lang["lock_msg"],$lock['username'],"template");
				$e->setError(5, $msg);
				$e->dumpError();
			}
		}
	}
	// end check for lock
} else {
	$id='';
}

$content = array();
if(isset($_REQUEST['id']) && $_REQUEST['id']!='' && is_numeric($_REQUEST['id'])) {
	$sql = "SELECT * FROM $dbase.".$table_prefix."site_templates WHERE $dbase.".$table_prefix."site_templates.id = $id;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit>1) {
		echo "Oops, something went terribly wrong...<p>";
		print "More results returned than expected. Which sucks. <p>Aborting.";
		exit;
	}
	if($limit<1) {
		echo "Oops, something went terribly wrong...<p>";
		print "No database record has been found for this template. <p>Aborting.";
		exit;
	}
	$content = mysql_fetch_assoc($rs);
	$_SESSION['itemname']=$content['templatename'];
	if($content['locked']==1 && $_SESSION['mgrRole']!=1) {
		$e->setError(3);
		$e->dumpError();
	}
} else {
	$_SESSION['itemname']="New template";
}

if (isset ($_POST['which_editor'])) {
	$which_editor = $_POST['which_editor'];
}

$content = array_merge($content, $_POST);

// Print RTE Javascript function
?>
<script language="javascript" type="text/javascript">
// Added for RTE selection
function changeRTE(){
	var whichEditor = document.getElementById('which_editor');
	if (whichEditor) for (var i=0; i<whichEditor.length; i++){
		if (whichEditor[i].selected){
			newEditor = whichEditor[i].value;
			break;
		}
	}

	documentDirty=false;
	document.mutate.a.value = <?php echo $action; ?>;
	document.mutate.which_editor.value = newEditor;
	document.mutate.submit();
}

function duplicaterecord(){
	if(confirm("<?php echo $_lang['confirm_duplicate_record'] ?>")==true) {
		documentDirty=false;
		document.location.href="index.php?id=<?php echo $_REQUEST['id']; ?>&a=96";
	}
}

function deletedocument() {
	if(confirm("<?php echo $_lang['confirm_delete_template']; ?>")==true) {
		documentDirty=false;
		document.location.href="index.php?id=" + document.mutate.id.value + "&a=21";
	}
}

</script>

<form name="mutate" method="post" action="index.php">
<?php
	// invoke OnTempFormPrerender event
	$evtOut = $modx->invokeEvent("OnTempFormPrerender",array("id" => $id));
	if(is_array($evtOut)) echo implode("",$evtOut);
?>
<input type="hidden" name="a" value="20">
<input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>">
<input type="hidden" name="mode" value="<?php echo $_REQUEST['a'];?>">

<div class="subTitle">
	<span class="right"><?php echo $_lang['template_title']; ?></span>
	<table cellpadding="0" cellspacing="0">
		<td id="Button1" onclick="documentDirty=false; document.mutate.save.click(); saveWait('mutate');"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang['save']; ?></td>
			<script type="text/javascript">createButton(document.getElementById("Button1"));</script>
<?php if($_REQUEST['a']=='16') { ?>
		<td id="Button2" onclick="duplicaterecord();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle"> <?php echo $_lang["duplicate"]; ?></td>
			<script type="text/javascript">createButton(document.getElementById("Button2"));</script>
		<td id="Button3" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle"> <?php echo $_lang['delete']; ?></span></td>
			<script type="text/javascript">createButton(document.getElementById("Button3"));</script>
<?php } ?>
		<td id="Button4" onclick="document.location.href='index.php?a=76';"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?></td>
			<script type="text/javascript">createButton(document.getElementById("Button4"));</script>
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

<div class="sectionHeader"><?php echo $_lang['template_title']; ?></div>
<div class="sectionBody">
	<?php echo $_lang['template_msg']; ?><p />
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td align="left"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="100" height="1"></td>
	    <td align="left">&nbsp;</td>
	  </tr>
	  <tr>
	    <td align="left"><?php echo $_lang['template_name']; ?>:&nbsp;&nbsp;</td>
	    <td align="left"><input name="templatename" type="text" maxlength="100" value="<?php echo html_entity_decode($content['templatename']);?>" class="inputBox" style="width:150px;" onChange='documentDirty=true;'><span class="warning" id='savingMessage'></span></td>
	  </tr>
	    <tr>
	    <td align="left"><?php echo $_lang['template_desc']; ?>:&nbsp;&nbsp;</td>
	    <td align="left"><input name="description" type="text" maxlength="255" value="<?php echo html_entity_decode($content['description']);?>" class="inputBox" style="width:300px;" onChange='documentDirty=true;'></td>
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
		<td align="left" valign="top" style="padding-top:5px;"><span style="font-family:'Courier New', Courier, mono">&nbsp;&nbsp;</span><input name="newcategory" type="text" maxlength="45" value="<?php echo isset($content['newcategory']) ? $content['newcategory'] : '' ?>" class="inputBox" style="width:300px;" onChange='documentDirty=true;'></td>
	  </tr>
	  <tr>
	    <td align="left" colspan="2"><input name="locked" type="checkbox" <?php echo $content['locked']==1 ? "checked='checked'" : "" ;?> class="inputBox"> <?php echo $_lang['lock_template']; ?> <span class="comment"><?php echo $_lang['lock_template_msg']; ?></span></td>
	  </tr>
	</table>
	<!-- HTML text editor start -->
	<div style="width:100%;position:relative">
	    <div style="padding:1px; width:100%; height:16px; background-color:#eeeeee; border:1px solid #e0e0e0;margin-top:5px">
	    	<span style="float:left;color:brown;font-weight:bold; padding:3px">&nbsp;<?php echo $_lang['template_code']; ?></span>
	   	</div>
		<textarea name="post" style="width:100%; height: 370px;" onChange='documentDirty=true;'><?php echo isset($content['post']) ? htmlspecialchars($content['post']) : htmlspecialchars($content['content']); ?></textarea>
	</div>
	<span class='warning'><?php echo $_lang["which_editor_title"]?></span>
			<select id="which_editor" name="which_editor" onchange="changeRTE();">
	<?php

	// invoke OnRichTextEditorRegister event
	$evtOut = $modx->invokeEvent("OnRichTextEditorRegister");
	echo "<option value='none'" . ($which_editor == 'none' ? " selected='selected'" : "") . ">" . $_lang["none"] . "</option>\n";
	if (is_array($evtOut))
		for ($i = 0; $i < count($evtOut); $i++) {
			$editor = $evtOut[$i];
			echo "<option value='$editor'" . ($which_editor == $editor ? " selected='selected'" : "") . ">$editor</option>\n";
		}
?>
			</select>
		</div>
	<!-- HTML text editor end -->
	<input type="submit" name="save" style="display:none">
</div>
<?php
	// invoke OnTempFormRender event
	$evtOut = $modx->invokeEvent("OnTempFormRender",array("id" => $id));
	if(is_array($evtOut)) echo implode("",$evtOut);
?>
</form>
<?php
// invoke OnRichTextEditorInit event
if ($use_editor == 1) {
			$evtOut = $modx->invokeEvent("OnRichTextEditorInit", array (
				editor => $which_editor,
				elements => array('post')
			));
			if (is_array($evtOut))
				echo implode("", $evtOut);
}
?>
