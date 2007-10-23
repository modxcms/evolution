<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

// Includes TreeView State Saver added by Jeroen:Modified by Raymond
$id = $_REQUEST['id'];
// Jeroen posts SESSION vars :Modified by Raymond
if (isset($_GET['opened'])) $_SESSION['openedArray'] = $_GET['opened'];

//helio: required for makeTable class => table pagination

$maxpageSize = $modx->config['number_of_results'];
define('MAX_DISPLAY_RECORDS_NUM',$maxpageSize);

//end table pagination


$url = $modx->config['site_url'];

$tblsc = $dbase.".`".$table_prefix."site_content`";
$tbldg = $dbase.".`".$table_prefix."document_groups`";
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
    $e->setError(3);
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

/* helio: Added metadata stuff
code from mutate_content.dynamic.php
*/
    // get list of site META tags
$metatags = array ();
    $tbl = $modx->getFullTableName("site_metatags");
$ds = $modx->db->select("*", $tbl);
    $limit = $modx->db->getRecordCount($ds);
if ($limit > 0) {
    for ($i = 0; $i < $limit; $i++) {
            $row = $modx->db->getRow($ds);
            $metatags[$row['id']] = $row['name'] . ": " . $row['tagvalue'];//changed this line from original code, we could prepend the value with label "name","value
        }
    }
    // get selected META tags using document's id

if (isset ($content['id']) && count($metatags) > 0) {//changed this line from original code
    $metatags_selected = array ();
        $tbl = $modx->getFullTableName("site_content_metatags");
    $ds = $modx->db->select("*", $tbl, "content_id='" . $content['id'] . "'");
        $limit = $modx->db->getRecordCount($ds);
    if ($limit > 0) {
        for ($i = 0; $i < $limit; $i++) {
                $row = $modx->db->getRow($ds);
                //changed this line from original code
                $metatags_selected[] = $metatags[$row['metatag_id']];
            }
        }
    }
// end metadata stuff



//I've also moved the <script> part first because of cookie management (tabPane) but no more used in this version (error: output already started header.inc.php)
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

<div class="subTitle">
    <span class="right"><?php echo $_lang["doc_data_title"]; ?></span>

    <table cellpadding="0" cellspacing="0" class="actionButtons">
        <td id="Button1"><a href="#" onclick="editdocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang["edit"]; ?></a></td>
        <td id="Button2"><a href="#" onclick="movedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>/images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang["move"]; ?></a></td>
        <td id="Button4"><a href="#" onclick="duplicatedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle"> <?php echo $_lang["duplicate"]; ?></a></td>
        <td id="Button3"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle"> <?php echo $_lang["delete"]; ?></a></td>
    </table>
</div>

<div class="sectionHeader"><?php echo $_lang["page_data_title"]; ?></div><div class="sectionBody">

<!-- helio : changed here, add tab support -->

<script type="text/javascript" src="media/script/tabpane.js"></script>

    <div class="tab-pane" id="childPane">
        <script type="text/javascript">
            docSettings = new WebFXTabPane( document.getElementById( "childPane" ) );
        </script>

        <!-- General -->
        <div class="tab-page" id="tabdocGeneral">
            <h2 class="tab"><?php echo $_lang["settings_general"] ?></h2>
            <script type="text/javascript">docSettings.addTabPage( document.getElementById( "tabdocGeneral" ) );</script>

<!-- end change -->
<div class="sectionBody">

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
   <tr>
    <td valign="top"><?php echo $_lang["document_summary"]; ?>: </td>
    <td><?php echo $content['introtext']!='' ? $content['introtext'] : "(<i>".$_lang["notset"]."</i>)" ; ?></td>
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
    <td valign="top"><?php echo $_lang['metatags']; ?>: </td>
    <td><?php // added keywords
        if(count($metatags_selected) != 0) {
            echo join($metatags_selected, "<br /> ");
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
    <td><?php echo $content['published']==0 ? "<span class='unpublishedDoc'>".$_lang['page_data_unpublished']."</span>" : "<span class='publishedDoc'>".$_lang['page_data_published']."</span>"; ?></td>
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
</div><!-- ent div tab -->

</div><!-- end section body -->
<?php

//view chidrens start here
//

if (!class_exists('makeTable')) include_once $base_path."manager/includes/extenders/maketable.class.php";
$childsTable = new makeTable();

//get Child document
$tblsc = $modx->getFullTableName("site_content");
$tbldg = $modx->getFullTableName("document_groups");
$access = "1='".$_SESSION['mgrRole']."' OR sc.privatemgr=0".
          (!$docgrp ? "":" OR dg.document_group IN ($docgrp)");


$query = "SELECT COUNT(*) as total FROM ".$tblsc ." sc ";
$query .= " LEFT JOIN $tbldg dg on dg.document = sc.id";
$query .= " WHERE sc.parent='".$content['id']."' ";
$query .= " AND ($access) ";
$numRecords = $modx->db->getValue($query);

$sql = "SELECT sc.* FROM ".$tblsc ." sc ";
$sql .= " LEFT JOIN $tbldg dg on dg.document = sc.id";
$sql .= " WHERE sc.parent='".$content['id']."' ";
$sql .= " AND ($access) ";
$sql .= $childsTable->handlePaging();// add limit clause

	if($modx->hasPermission('new_document')) {//check add content permission
		$addContentBar = '
 			<table cellpadding="0" cellspacing="0" class="actionButtons">
        	<td><a href="index.php?a=4&pid='.$content['id'].'"><img src="media/style/'.$manager_theme.'/images/icons/newdoc.gif" align="absmiddle"/> '.$_lang["create_document_here"].'</a></td>
        	<td><a href="index.php?a=72&pid='.$content['id'].'"><img src="media/style/'.$manager_theme.'/images/icons/weblink.gif" align="absmiddle"> '.$_lang["create_weblink_here"].'</a></td>
        	</table>

		';

	}else{

		$addContentBar = "";
	}



if($numRecords > 0){

	if(!$rs = $modx->db->query($sql)){
		//sql error

		$e->setError(1);
    	$e->dumpError();
    	include("../includes/footer.inc.php");
    	exit;

	}else{

		$resource = array();
		while($row = $modx->fetchRow($rs)){
		$resource[] = $row;

		}



	// css style for table
	$tableClass = "grid";
	$rowHeaderClass = "gridHeader";//
	$rowRegularClass = "gridItem";
	$rowAlternateClass = "gridAltItem";

	$childsTable->setTableClass($tableClass);
	$childsTable->setRowHeaderClass($rowHeaderClass);
	$childsTable->setRowRegularClass($rowRegularClass);
	$childsTable->setRowAlternateClass($rowAlternateClass);

	//table header
	$listTableHeader = array(
                    'docid'=>$_lang["id"],
                    'title'=>$_lang["document_title"],
                    'statut'=>$_lang["page_data_status"],
                    'edit'=>$_lang["mgrlog_action"]
                    );
     $tbWidth = array("5%","60%","10%","25%");
     $childsTable->setColumnWidths($tbWidth);

	$limitClause = $childsTable->handlePaging();



	$listDocs = array();
		foreach($resource as $k=>$children){

			$listDocs[]= array(
                    'docid'=>$children['id'],
                    'title'=>$children['pagetitle'],
                    'statut'=>($children['published'] == 0) ? "<span class='unpublishedDoc'>".$_lang['page_data_unpublished']."</span>"  : "<span class='publishedDoc'>".$_lang['page_data_published']."</span>",
                    'edit'=>"<a href=\"index.php?a=3&id=".$children['id']."\"><img src='media/style/$manager_theme/images/icons/context_view.gif' />".$_lang["view"]."</a>".(($modx->hasPermission('edit_document')) ? "&nbsp;<a href=\"index.php?a=27&id=".$children['id']."\"><img src='media/style/$manager_theme/images/icons/save.gif' />".$_lang["edit"]."</a>&nbsp;<a href=\"index.php?a=51&id=".$children['id']."\"><img src='media/style/$manager_theme/images/icons/cancel.gif' />".$_lang["move"]."</a>" : "" )
                    );


		}

	$childsTable->createPagingNavigation($numRecords,'a=3&id='.$content['id']);
	$output = $childsTable->create($listDocs,$listTableHeader,'index.php?a=3&id='.$content['id']);



	}

}else{//no childrens yet

	$output = $addContentBar . $_lang["documents_in_container_no"];//add this to lang file

}

?>
		<div class="tab-page" id="tabChildren">
            <h2 class="tab"><?php echo $_lang["view_child_documents_in_container"];?></h2>
            <script type="text/javascript">docSettings.addTabPage( document.getElementById( "tabChildren" ) );</script>
			<script type="text/javascript" src="media/script/tablesort.js"></script>
			<?php

			if($numRecords > 0) echo $addContentBar . "<h4><span class='publishedDoc'>". $numRecords ."</span> ".$_lang["documents_in_container"]." (<strong>".$content['pagetitle']."</strong>)</h4>";
			echo $output;

			?>

		</div><!-- end tab div-->
	</div><!-- end documentPane -->
</div><!-- end sectionBody -->


<!--BEGIN SHOW HIDE PREVIEW WINDOW MOD-->
<?php

if ($show_preview==1) { ?>
<div class="sectionHeader">
  <?php echo $_lang["preview"]; ?></div><div class="sectionBody" id="lyr2">
  <iframe src="../index.php?id=<?php echo $id; ?>&z=manprev" frameborder=0 border=0 id="previewIframe">
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
    $buffer=$_lang['page_data_cached']."<p><textarea style='width: 100%; height: 400px;'>".htmlspecialchars($buffer)."</textarea>";
}

echo $buffer;

?>
</div>

