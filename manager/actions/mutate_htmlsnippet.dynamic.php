<?php
if (IN_MANAGER_MODE != "true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

switch($_REQUEST['a']) {
  case 78:
    if(!$modx->hasPermission('edit_chunk')) {
      $e->setError(3);
      $e->dumpError();
    }
    break;
  case 77:
    if(!$modx->hasPermission('new_chunk')) {
      $e->setError(3);
      $e->dumpError();
    }
    break;
  default:
    $e->setError(3);
    $e->dumpError();  
}

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
	$id = $_REQUEST['id'];

	// check to see the snippet editor isn't locked
	$sql = "SELECT internalKey, username FROM $dbase.`" . $table_prefix . "active_users` WHERE $dbase.`" . $table_prefix . "active_users`.action=78 AND $dbase.`" . $table_prefix . "active_users`.id=$id";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if ($limit > 1) {
		for ($i = 0; $i < $limit; $i++) {
			$lock = mysql_fetch_assoc($rs);
			if ($lock['internalKey'] != $modx->getLoginUserID()) {
				$msg = sprintf($_lang["lock_msg"], $lock['username'], "chunk");
				$e->setError(5, $msg);
				$e->dumpError();
			}
		}
	}
	// end check for lock
} else {
	$id = '';
}

$content = array();
if(isset($_REQUEST['id']) && $_REQUEST['id']!='' && is_numeric($_REQUEST['id'])) {
	$sql = "SELECT * FROM $dbase.`" . $table_prefix . "site_htmlsnippets` WHERE $dbase.`" . $table_prefix . "site_htmlsnippets`.id = $id;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if ($limit > 1) {
		echo "Multiple Chunk sharing same unique id. Not good.<p>";
		exit;
	}
	if ($limit < 1) {
		echo "Chunk doesn't exist.";
		exit;
	}
	$content = mysql_fetch_assoc($rs);
	$_SESSION['itemname'] = $content['name'];
	if ($content['locked'] == 1 && $_SESSION['mgrRole'] != 1) {
		$e->setError(3);
		$e->dumpError();
	}
} else {
	$_SESSION['itemname'] = "New Chunk";
}

if (isset ($_POST['which_editor'])) {
	$which_editor = $_POST['which_editor'];
} else {
	$which_editor = 'none';
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

<form name="mutate" method="post" action="index.php">
<?php

// invoke OnChunkFormPrerender event
$evtOut = $modx->invokeEvent("OnChunkFormPrerender", array (
	"id" => $id
));
if (is_array($evtOut))
	echo implode("", $evtOut);
?>
<input type="hidden" name="a" value="79">
<input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>">
<input type="hidden" name="mode" value="<?php echo $_REQUEST['a'];?>">

<div class="subTitle">
	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<td id="Button1"><a href="#" onclick="documentDirty=false; document.mutate.save.click(); saveWait('mutate');"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang['save']; ?></a></td>
<?php if($_REQUEST['a']=='78') { ?>
		<td id="Button2"><a href="#" onclick="duplicaterecord();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle"> <?php echo $_lang["duplicate"]; ?></a></td>
		<td id="Button3"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle"> <?php echo $_lang['delete']; ?></a></td>
<?php } ?>
		<td id="Button4"><a href="#" onclick="documentDirty=false;document.location.href='index.php?a=76';"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?></a></td>
	</table>
	<div class="stay">
	<table border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td><span class="comment">&nbsp;<?php echo $_lang["after_saving"];?>:</span></td>
		<td><input name="stay" type="radio" class="radio" value="1"  <?php echo $_GET['stay']=='1' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['stay_new']; ?></span></td>
		<td><input name="stay" type="radio" class="radio" value="2"  <?php echo $_GET['stay']=='2' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['stay']; ?></span></td>
		<td><input name="stay" type="radio" class="radio" value=""  <?php echo $_GET['stay']=='' ? "checked='checked'":'' ?> /></td><td><span class="comment"><?php echo $_lang['close']; ?></span></td>
	</tr>
	</table>
	</div>
</div>


<div class="sectionHeader"><?php echo $_lang['htmlsnippet_title']; ?></div><div class="sectionBody">
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
if ($ds)
	foreach ($ds as $n => $v) {
		echo "<option value='" . $v['id'] . "'" . ($content["category"] == $v["id"] ? " selected='selected'" : "") . ">" . htmlspecialchars($v["category"]) . "</option>";
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
    <td align="left" colspan="2"><input name="locked" type="checkbox" <?php echo $content['locked']==1 ? "checked='checked'" : "" ;?> class="inputBox" /> <?php echo $_lang['lock_htmlsnippet']; ?> <span class="comment"><?php echo $_lang['lock_htmlsnippet_msg']; ?></span></td>
  </tr>
</table>
	<!-- HTML text editor start -->
	<div style="width:100%;position:relative">
	    <div style="padding:1px; width:100%; height:16px; background-color:#eeeeee; border:1px solid #e0e0e0;margin-top:5px">
	    	<span style="color:brown;font-weight:bold; padding:3px">&nbsp;<?php echo $_lang['chunk_code']; ?></span>
	   	</div>
		<textarea dir="ltr" name="post" style="width:100%; height: 370px;" onChange='documentDirty=true;'><?php echo isset($content['post']) ? htmlspecialchars($content['post']) : htmlspecialchars($content['snippet']); ?></textarea>
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

// invoke OnChunkFormRender event
$evtOut = $modx->invokeEvent("OnChunkFormRender", array (
	"id" => $id
));
if (is_array($evtOut))
	echo implode("", $evtOut);
?>
</form>
<?php

// invoke OnRichTextEditorInit event
if ($use_editor == 1) {
$evtOut = $modx->invokeEvent("OnRichTextEditorInit", array (
	editor => $which_editor,
	elements => array (
		'post'
	)
));
if (is_array($evtOut))
	echo implode("", $evtOut);
}
?>
