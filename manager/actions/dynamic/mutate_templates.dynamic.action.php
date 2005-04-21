<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if($_SESSION['permissions']['edit_template']!=1 && $_REQUEST['a']==16) {	$e->setError(3);
	$e->dumpError();	
}
if($_SESSION['permissions']['new_template']!=1 && $_REQUEST['a']==19) {	$e->setError(3);
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

if(isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
} else {
	$id=0;
}

// check to see the template editor isn't locked
$sql = "SELECT internalKey, username FROM $dbase.".$table_prefix."active_users WHERE $dbase.".$table_prefix."active_users.action=16 AND $dbase.".$table_prefix."active_users.id=$id";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit>1) {
	for ($i=0;$i<$limit;$i++) {
		$lock = mysql_fetch_assoc($rs);
		if($lock['internalKey']!=$_SESSION['internalKey']) {		
			$msg = $lock['username']." is currently editing this template. Please wait until the other user has finished and try again.";
			$e->setError(5, $msg);
			$e->dumpError();
		}
	}
} 
// end check for lock

// make sure the id's a number
if(!isNumber($id)) {
	header("Location: /index.php?id=".$site_start);
}

if(isset($_GET['id'])) {
	$sql = "SELECT * FROM $dbase.".$table_prefix."site_templates WHERE $dbase.".$table_prefix."site_templates.id = $id;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit>1) {
		echo "Oops, something went terribly wrong...<p>";
		print "More results returned than expected. Which sucks. <p>Aborting.";
		exit;
	}
	if($limit<1) {
		header("Location: /index.php?id=".$site_start);
	}
	$content = mysql_fetch_assoc($rs);
	$_SESSION['itemname']=$content['templatename'];
	if($content['locked']==1 && $_SESSION['role']!=1) {
		$e->setError(3);
		$e->dumpError();
	}
} else {
	$_SESSION['itemname']="New template";
}
?>
<script language="JavaScript">

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



<form name="mutate" method="post" action="index.php?a=20">
<?php
	// invoke OnTempFormPrerender event
	$evtOut = $modx->invokeEvent("OnTempFormPrerender",array("id" => $id));
	echo implode("",$evtOut);
?>
<input type="hidden" name="id" value="<?php echo $content['id'];?>">
<input type="hidden" name="mode" value="<?php echo $_GET['a'];?>">

<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['template_title']; ?></span>
	<table cellpadding="0" cellspacing="0">
		<td id="Button1" onclick="documentDirty=false; document.mutate.save.click(); saveWait('mutate');"><img src="media/images/icons/save.gif" align="absmiddle"> <?php echo $_lang['save']; ?></td>
			<script>createButton(document.getElementById("Button1"));</script>
<?php if($_GET['a']=='16') { ?>
		<td id="Button2" onclick="duplicaterecord();"><img src="media/images/icons/copy.gif" align="absmiddle"> <?php echo $_lang["duplicate"]; ?></td>
			<script>createButton(document.getElementById("Button2"));</script>
		<td id="Button3" onclick="deletedocument();"><img src="media/images/icons/delete.gif" align="absmiddle"> <?php echo $_lang['delete']; ?></span></td>
			<script>createButton(document.getElementById("Button3"));</script>
<?php } ?>
		<td id="Button4" onclick="document.location.href='index.php?a=76';"><img src="media/images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?></td>
			<script>createButton(document.getElementById("Button4"));</script>
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

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['template_title']; ?></div><div class="sectionBody">
<?php echo $_lang['template_msg']; ?><p />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><img src="media/images/_tx_.gif" width="100" height="1"></td>
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
    <td align="left" colspan="2"><input name="locked" type="checkbox" <?php echo $content['locked']==1 ? "checked='checked'" : "" ;?> class="inputBox"> <?php echo $_lang['lock_template']; ?> <span class="comment"><?php echo $_lang['lock_template_msg']; ?></span></td>
  </tr>
</table>
<textarea name="post" style="width:100%; height: 370px;" onChange='documentDirty=true;'><?php echo htmlspecialchars($content['content']); ?></textarea>
<input type="submit" name="save" style="display:none">
</div>
<?php
	// invoke OnTempFormRender event
	$evtOut = $modx->invokeEvent("OnTempFormRender",array("id" => $id));
	echo implode("",$evtOut);
?>
</form>
