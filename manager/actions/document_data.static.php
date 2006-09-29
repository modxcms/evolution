<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

// Includes TreeView State Saver added by Jeroen:Modified by Raymond
$id = $_REQUEST['id'];
// Jeroen posts SESSION vars :Modified by Raymond
if (isset($_GET['opened'])) $_SESSION['openedArray'] = $_GET['opened'];

?>
<script type="text/javascript">
    function duplicatedocument(){
        if(confirm("<?php echo $_lang['confirm_duplicate_document'] ?>")==true) {
            document.location.href="index.php?id=<?php echo $_REQUEST['id']; ?>&a=94";
        }
    }
    function deletedocument() {
        if(confirm("<?php echo $_lang['confirm_delete_document'] ?>")==true) {
            document.location.href="index.php?id=<?php echo $_REQUEST['id']; ?>&a=6";
        }
    }
    function editdocument() {
        document.location.href="index.php?id=<?php echo $_REQUEST['id']; ?>&a=27";
    }
    function movedocument() {
        document.location.href="index.php?id=<?php echo $_REQUEST['id']; ?>&a=51";
    }
</script>

<?php
$tblsc = $dbase.".".$table_prefix."site_content";
$tbldg = $dbase.".".$table_prefix."document_groups";
// get document groups for current user
if($_SESSION['mgrDocgroups']) $docgrp = implode(",",$_SESSION['mgrDocgroups']);
$access = "1='".$_SESSION['mgrRole']."' OR sc.privatemgr=0".
		  (!$docgrp ? "":" OR dg.document_group IN ($docgrp)");
$sql = "SELECT DISTINCT sc.*
		FROM $tblsc sc
		LEFT JOIN $tbldg dg on dg.document = sc.id
		WHERE sc.id = $id
		AND ($access);";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit>1) {
	echo " Internal System Error...<p>";
	print "More results returned than expected. <p>Aborting.";
	exit;
}
else if($limit==0){
	$e->setError(15);
	$e->dumpError();
}
$content = mysql_fetch_assoc($rs);

$createdby = $content['createdby'];
$sql = "SELECT username FROM $dbase.".$table_prefix."manager_users WHERE id='$createdby';";
$rs = mysql_query($sql);

$row=mysql_fetch_assoc($rs);
$createdbyname = $row['username'];

$editedby = $content['editedby'];
$sql = "SELECT username FROM $dbase.".$table_prefix."manager_users WHERE id=$editedby;";
$rs = mysql_query($sql);

  $row=mysql_fetch_assoc($rs);
  $editedbyname = $row['username'];

$templateid = $content['template'];
$sql = "SELECT templatename FROM $dbase.".$table_prefix."site_templates WHERE id=$templateid;";
$rs = mysql_query($sql);

  $row=mysql_fetch_assoc($rs);
  $templatename = $row['templatename'];


   $_SESSION['itemname']=$content['pagetitle'];

// keywords stuff, by stevew (thanks Steve!)
$sql = "SELECT k.keyword FROM $dbase.".$table_prefix."site_keywords as k, $dbase.".$table_prefix."keyword_xref as x WHERE k.id = x.keyword_id AND x.content_id = $id ORDER BY k.keyword ASC";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit > 0) {
	for($i=0;$i<$limit;$i++) {
		$row = mysql_fetch_assoc($rs);
		$keywords[$i] = $row['keyword'];
	}
} else {
	$keywords = array();
}
// end keywords stuff

?>

<div class="subTitle">
	<span class="right"><?php echo $_lang["doc_data_title"]; ?></span>

	<table cellpadding="0" cellspacing="0">
		<td id="Button1" onclick="editdocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang["edit"]; ?></td>
		<td id="Button2" onclick="movedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>/images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang["move"]; ?></td>
		<td id="Button4" onclick="duplicatedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle"> <?php echo $_lang["duplicate"]; ?></td>
		<td id="Button3" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle"> <?php echo $_lang["delete"]; ?></td>
	</table>
	<script type="text/javascript">
	    createButton(document.getElementById("Button1"));
	    createButton(document.getElementById("Button2"));
	    createButton(document.getElementById("Button4"));
	    createButton(document.getElementById("Button3"));
	</script>
</div>

<div class="sectionHeader"><?php echo $_lang["page_data_title"]; ?></div>

<div class="sectionBody" id="lyr1">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2"><b><?php echo $_lang["page_data_general"]; ?></b></td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php echo $_lang["document_title"]; ?>: </td>
    <td><b><?php echo $content['pagetitle']; ?></b></td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php echo $_lang["long_title"]; ?>: </td>
    <td><small><?php echo $content['longtitle']!='' ? $content['longtitle'] : "(<i>".$_lang["notset"]."</i>)" ; ?></small></td>
  </tr>
  <tr>
    <td valign="top"><?php echo $_lang["document_description"]; ?>: </td>
    <td><?php echo $content['description']!='' ? $content['description'] : "(<i>".$_lang["notset"]."</i>)" ; ?></td>
  </tr>
  <tr>
    <td valign="top"><?php echo $_lang["type"]; ?>: </td>
    <td><?php echo $content['type']=='reference' ? $_lang['weblink'] : $_lang['document'] ; ?></td>
  </tr>
  <tr>
    <td valign="top"><?php echo $_lang["document_alias"]; ?>: </td>
    <td><?php echo $content['alias']!='' ? $content['alias'] : "(<i>".$_lang["notset"]."</i>)" ; ?></td>
  </tr>
    <tr>
    <td valign="top"><?php echo $_lang['keywords']; ?>: </td>
	<td><?php
	  	if(count($keywords) != 0) {
	  		echo join($keywords, ", ");
	  	} else {
			echo "(<i>".$_lang['notset']."</i>)";
		}
	?></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><b><?php echo $_lang["page_data_changes"]; ?></b></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_created"]; ?>: </td>
    <td><?php echo strftime("%d/%m/%y %H:%M:%S", $content['createdon']+$server_offset_time); ?> (<b><?php echo $createdbyname ?></b>)</td>
  </tr>
<?php
if($editedbyname!='') {
?>
  <tr>
    <td><?php echo $_lang["page_data_edited"]; ?>: </td>
    <td><?php echo strftime("%d/%m/%y %H:%M:%S", $content['editedon']+$server_offset_time); ?> (<b><?php echo $editedbyname ?></b>)</td>
  </tr>
<?php
}
?>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><b><?php echo $_lang["page_data_status"]; ?></b></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_status"]; ?>: </td>
	<td><?php echo $content['published']==0 ? "<b style='color: #821517'>".$_lang['page_data_unpublished']."</b>" : "<b style='color: #006600'>".$_lang['page_data_published']."</b>"; ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_publishdate"]; ?>: </td>
	<td><?php echo $content['pub_date']==0 ? "(<i>".$_lang["notset"]."</i>)" : strftime("%d-%m-%Y %H:%M:%S", $content['pub_date']); ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_unpublishdate"]; ?>: </td>
	<td><?php echo $content['unpub_date']==0 ? "(<i>".$_lang["notset"]."</i>)" : strftime("%d-%m-%Y %H:%M:%S", $content['unpub_date']); ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_cacheable"]; ?>: </td>
	<td><?php echo $content['cacheable']==0 ? $_lang['no'] : $_lang['yes']; ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_searchable"]; ?>: </td>
	<td><?php echo $content['searchable']==0 ? $_lang['no'] : $_lang['yes']; ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang['document_opt_menu_index']; ?>: </td>
	<td><?php echo $content['menuindex']; ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang['document_opt_show_menu']; ?>: </td>
	<td><?php echo $content['hidemenu']==1 ? $_lang['no'] : $_lang['yes']; ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_web_access"]; ?>: </td>
	<td><?php echo $content['privateweb']==0 ? $_lang['public'] : "<b style='color: #821517'>".$_lang['private']."</b> <img src='media/style/".($manager_theme ? "$manager_theme/":"")."images/icons/secured.gif' align='absmiddle' width='16' height='16' />"; ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_mgr_access"]; ?>: </td>
	<td><?php echo $content['privatemgr']==0 ? $_lang['public'] : "<b style='color: #821517'>".$_lang['private']."</b> <img src='media/style/".($manager_theme ? "$manager_theme/":"")."images/icons/secured.gif' align='absmiddle' width='16' height='16' />"; ?></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><b><?php echo $_lang["page_data_markup"]; ?></b></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_template"]; ?>: </td>
	<td><?php echo $templatename ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_editor"]; ?>: </td>
	<td><?php echo $content['richtext']==0 ? $_lang['no'] : $_lang['yes']; ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang["page_data_folder"]; ?>: </td>
	<td><?php echo $content['isfolder']==0 ? $_lang['no'] : $_lang['yes']; ?></td>
  </tr>
</table>
</div>

<!--BEGIN SHOW HIDE PREVIEW WINDOW MOD-->
<?php
if ($show_preview==1) { ?>
<div class="sectionHeader">
  <?php echo $_lang["preview"]; ?></div><div class="sectionBody" id="lyr2">
  <iframe src="../index.php?id=<?php echo $id; ?>&z=manprev" frameborder=0 border=0 style="width: 100%; height: 400px; border: 3px solid #4791C5;">
  </iframe>
</div>
<?php } ?>
<!--END SHOW HIDE PREVIEW WINDOW MOD-->

<div class="sectionHeader"><?php echo $_lang["page_data_source"]; ?></div><div class="sectionBody">
<?php
$buffer = "";
$filename = "../assets/cache/docid_".$id.".pageCache.php";
$handle = @fopen($filename, "r");
if(!$handle) {
	$buffer = $_lang['page_data_notcached'];
} else {
	while (!feof($handle)) {
		$buffer .= fgets($handle, 4096);
	}
	fclose ($handle);
	$buffer=$_lang['page_data_cached']."<p><textarea style='width: 100%; height: 400px; border: 3px solid #4791C5;'>".htmlspecialchars($buffer)."</textarea>";
}

echo $buffer;
?>
</div>
<!-- This doesn't seem to do anything...
<script type="text/javascript">
try {
	top.menu.Sync(<?php echo $id; ?>);
} catch(oException) {
	xyy=window.setTimeout("loadagain(<?php echo $id; ?>)", 1000);
}
</script>
-->