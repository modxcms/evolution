<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
unset($_SESSION['itemname']); // clear this, because it's only set for logging purposes
?>

<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['search']; ?></span>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['search_criteria']; ?></div><div class="sectionBody">
<form action="index.php?a=71" method="post" name="searchform">
<table width="100%" border="0">
  <tr>
    <td width="120"><?php echo $_lang['search_criteria_id']; ?></td>
    <td width="20">&nbsp;</td>
    <td width="120"><input name="searchid" type="text"></td>
	<td><?php echo $_lang['search_criteria_id_msg']; ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang['search_criteria_title']; ?></td>
    <td>&nbsp;</td>
    <td><input name="pagetitle" type="text"></td>
	<td><?php echo $_lang['search_criteria_title_msg']; ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang['search_criteria_longtitle']; ?></td>
    <td>&nbsp;</td>
    <td><input name="longtitle" type="text"></td>
	<td><?php echo $_lang['search_criteria_longtitle_msg']; ?></td>
  </tr>
  <tr>
    <td><?php echo $_lang['search_criteria_content']; ?></td>
    <td>&nbsp;</td>
    <td><input name="content" type="text"></td>
	<td><?php echo $_lang['search_criteria_content_msg']; ?></td>
  </tr>
  <tr>
  	<td colspan="4">
		<table cellpadding="0" cellspacing="0" border="0">
		    <td id="Button1" align="right" onclick="document.searchform.submitok.click();"><img src="media/images/icons/save.gif" align="absmiddle"> <?php echo $_lang['search']; ?>
				<script>createButton(document.getElementById("Button1"));</script>	
			</td>
		    <td id="Button2" align="right" onclick="document.location.href='index.php?a=2';"><img src="media/images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?>
				<script>createButton(document.getElementById("Button2"));</script>	
			</td>
		</table>
	</td>
  </tr>
</table>

<input type="submit" value="Search" name="submitok" style="display:none">
</form>
</div>


<?php
if(isset($_REQUEST['submitok'])) {
	$searchid = $_REQUEST['searchid'];
	$searchtitle = htmlentities($_POST['pagetitle'], ENT_QUOTES);
	$searchcontent = addslashes($_REQUEST['content']);
	$searchlongtitle = addslashes($_REQUEST['longtitle']);
	

$sqladd .= $searchid!="" ? " AND $dbase.".$table_prefix."site_content.id='$searchid' " : "" ;
$sqladd .= $searchtitle!="" ? " AND $dbase.".$table_prefix."site_content.pagetitle LIKE '%$searchtitle%' " : "" ;
$sqladd .= $searchlongtitle!="" ? " AND $dbase.".$table_prefix."site_content.longtitle LIKE '%$searchlongtitle%' " : "" ;
$sqladd .= $searchcontent!="" ? " AND $dbase.".$table_prefix."site_content.content LIKE '%$searchcontent%' " : "" ;

$sql = "SELECT id, pagetitle, description, deleted, published, isfolder, type FROM $dbase.".$table_prefix."site_content where 1=1 ".$sqladd." ORDER BY id;";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
?>
<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['search_results']; ?></div><div class="sectionBody">
<?php
if($limit<1) {
	echo $_lang['search_empty'];
} else {
	printf($_lang['search_results_returned_msg'], $limit);
?>
		<script type="text/javascript" src="media/script/sortabletable.js"></script>
  <table border=0 cellpadding=2 cellspacing=0  class="sort-table" id="table-1" width="90%"> 
    <thead> 
      <tr bgcolor='#CCCCCC'> 
		<td width="20"></td>
		<td width="20"></td>
        <td><b><?php echo $_lang['search_results_returned_id']; ?></b></td> 
        <td><b><?php echo $_lang['search_results_returned_title']; ?></b></td> 
        <td><b><?php echo $_lang['search_results_returned_desc']; ?></b></td>
		<td width="20"></td>
		<td width="20"></td>
      </tr> 
    </thead> 
    <tbody>
     <?php
			for ($i = 0; $i < $limit; $i++) { 
				$logentry = mysql_fetch_assoc($rs);
				$classname = ($i % 2) ? 'class="even" ' : 'class="odd" ';
	// figure out the icon for the document...
	$icon = "";
	if($logentry['published']==0) {
		$icon .= "unpublished";	
	} 
	if($logentry['type']=='reference') {
		$icon .= "weblink";
	}
	if($logentry['isfolder']==1) {
		$icon .= "folder";
	}
	if($icon=="" || $icon=="unpublished") {
		$icon .= "page";
	}
?> 
    <tr <?php echo $classname; ?>> 
	  <td class="cell" align="center">&nbsp;</td> 
      <td class="cell" align="center"><a href="index.php?a=3&id=<?php echo $logentry['id']; ?>"onMouseover="status='<?php echo $_lang['search_view_docdata']; ?>';return true;" onmouseout="status='';return true;" title="<?php echo $_lang['search_view_docdata']; ?>"><img src="media/images/icons/context_view.gif" border=0></a></td> 
      <td class="cell"><?php echo $logentry['id']; ?></td> 
	  <td class="cell"><?php echo strlen($logentry['pagetitle'])>20 ? substr($logentry['pagetitle'], 0, 20)."..." : $logentry['pagetitle'] ; ?></td> 
      <td class="cell"><?php echo strlen($logentry['description'])>35 ? substr($logentry['description'], 0, 35)."..." : $logentry['description'] ;  ?></td>
      <td class="cell" align="center"><?php echo $logentry['deleted']==1 ? "<img align='absmiddle' src='media/images/tree/trash.gif' alt='".$_lang['search_item_deleted']."'>" : ""; ?></td>
      <td class="cell" align="center"><img align='absmiddle' src='media/images/tree/<?php echo $icon; ?>.gif'></td>	  
    </tr> 
    <?php
			}
?> 
    </tbody> 
     </table> 
<script type="text/javascript">

var st1 = new SortableTable(document.getElementById("table-1"),
	["None", "None", "Number", "CaseInsensitiveString", "CaseInsensitiveString", "None", "None"]);

function addClassName(el, sClassName) {
	var s = el.className;
	var p = s.split(" ");
	var l = p.length;
	for (var i = 0; i < l; i++) {
		if (p[i] == sClassName)
			return;
	}
	p[p.length] = sClassName;
	el.className = p.join(" ");
			
}

function removeClassName(el, sClassName) {
	var s = el.className;
	var p = s.split(" ");
	var np = [];
	var l = p.length;
	var j = 0;
	for (var i = 0; i < l; i++) {
		if (p[i] != sClassName)
			np[j++] = p[i];
	}
	el.className = np.join(" ");
}

st1.onsort = function () {
	var rows = st1.tBody.rows;
	var l = rows.length;
	for (var i = 0; i < l; i++) {
		removeClassName(rows[i], i % 2 ? "odd" : "even");
		addClassName(rows[i], i % 2 ? "even" : "odd");
	}
};
</script>	
<?php
}
?>
</div>
<?php
}
?>