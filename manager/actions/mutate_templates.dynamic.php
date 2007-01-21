<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

switch($_REQUEST['a']) {
  case 16:
    if(!$modx->hasPermission('edit_template')) {
      $e->setError(3);
      $e->dumpError();
    }
    break;
  case 19:
    if(!$modx->hasPermission('new_template')) {
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
	// check to see the template editor isn't locked
	$sql = "SELECT internalKey, username FROM $dbase.`".$table_prefix."active_users` WHERE $dbase.`".$table_prefix."active_users`.action=16 AND $dbase.`".$table_prefix."active_users`.id=$id";
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
	$sql = "SELECT * FROM $dbase.`".$table_prefix."site_templates` WHERE $dbase.`".$table_prefix."site_templates`.id = $id;";
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

$content = array_merge($content, $_POST);

?>
<script type="text/javascript">
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
	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<td id="Button1"><a href="#" onclick="documentDirty=false; document.mutate.save.click(); saveWait('mutate');"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang['save']; ?></a></td>
<?php if($_REQUEST['a']=='16') { ?>
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

<?php if ($_REQUEST['a'] == 16) { ?>
<script type="text/javascript" src="media/script/tabpane.js"></script>
<?php } ?>

<div class="sectionHeader"><?php echo $_lang['template_title']; ?></div>
<div class="sectionBody">

<?php if ($_REQUEST['a'] == 16) { ?>
<div class="tab-pane" id="templatesPane">
	<script type="text/javascript">
		tpResources = new WebFXTabPane( document.getElementById( "templatesPane" ),false );
	</script>
	<div class="tab-page" id="tabTemplate">
    	<h2 class="tab"><?php echo $_lang["template_edit_tab"] ?></h2>
    	<script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabTemplate" ) );</script>
	<?php } ?>
	
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
		<textarea dir="ltr" name="post" style="width:100%; height: 370px;" onChange='documentDirty=true;'><?php echo isset($content['post']) ? htmlspecialchars($content['post']) : htmlspecialchars($content['content']); ?></textarea>
	</div>
	<!-- HTML text editor end -->
	<input type="submit" name="save" style="display:none">
	
<?php if ($_REQUEST['a'] == 16) { 
	$sql = "SELECT tv.name as 'name', tv.id as 'id', tr.templateid, tr.rank, if(isnull(cat.category),'".$_lang['no_category']."',cat.category) as category
	FROM ".$modx->getFullTableName('site_tmplvar_templates')." tr
	INNER JOIN ".$modx->getFullTableName('site_tmplvars')." tv ON tv.id = tr.tmplvarid
	LEFT JOIN ".$modx->getFullTableName('categories')." cat ON tv.category = cat.id
	WHERE tr.templateid='".$_REQUEST['id']."' ORDER BY tr.rank ASC";
	

$rs = $modx->db->query($sql);
$limit = $modx->db->getRecordCount($rs);
	?>
	</div>
	<div class="tab-page" id="tabAssignedTVs">
    	<h2 class="tab"><?php echo $_lang["template_assignedtv_tab"] ?></h2><p />
    	<script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabAssignedTVs" ) );</script>
    	<p><?php if ($limit > 0) echo $_lang['template_tv_msg']; ?></p>	
    	<p><?php if($modx->hasPermission('save_template') && $limit > 1) { ?><a href="index.php?a=117&amp;id=<?php echo $_REQUEST['id'] ?>"><?php echo $_lang['template_tv_edit']; ?></a><?php } ?></p>
<?php
$tvList = '';

if($limit>0) {
    for ($i=0;$i<$limit;$i++) {
        $row = $modx->db->getRow($rs);
        if ($i == 0 ) $tvList .= '<br/><ul>';
        $tvList .= '<li><strong>'.$row['name'].'</strong> ('.$row['category'].')</li>';
    }
    $tvList .= '</ul>';
	
} else {
	echo $_lang['template_no_tv'];
}
echo $tvList;
?></div>
<?php } ?>

<?php
	// invoke OnTempFormRender event
	$evtOut = $modx->invokeEvent("OnTempFormRender",array("id" => $id));
	if(is_array($evtOut)) echo implode("",$evtOut);
?>
</form>
</div>
