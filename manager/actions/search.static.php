<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
unset($_SESSION['itemname']); // clear this, because it's only set for logging purposes
?>

<h1><?php echo $_lang['search_criteria']; ?></h1>

<div class="sectionBody">

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
		<ul class="actionButtons">
		    <li><a href="#" onclick="document.searchform.submitok.click();"><img src="<?php echo $_style["icons_save"] ?>" /> <?php echo $_lang['search'] ?></a></li>
		    <li><a href="index.php?a=2"><img src="<?php echo $_style["icons_cancel"] ?>" /> <?php echo $_lang['cancel'] ?></a></li>
		</ul>
	</td>
  </tr>
</table>

<input type="submit" value="Search" name="submitok" style="display:none">
</form>
</div>


<?php
if(isset($_REQUEST['submitok'])) {
	$searchid = !empty($_REQUEST['searchid']) ? intval($_REQUEST['searchid']) : 0;
	$searchtitle = htmlentities($_POST['pagetitle'], ENT_QUOTES, $modx_manager_charset);
	$searchcontent = $modx->db->escape($_REQUEST['content']);
	$searchlongtitle = $modx->db->escape($_REQUEST['longtitle']);

$sqladd .= $searchid!="" ? " AND $dbase.`".$table_prefix."site_content`.id='$searchid' " : "" ;
$sqladd .= $searchtitle!="" ? " AND $dbase.`".$table_prefix."site_content`.pagetitle LIKE '%$searchtitle%' " : "" ;
$sqladd .= $searchlongtitle!="" ? " AND $dbase.`".$table_prefix."site_content`.longtitle LIKE '%$searchlongtitle%' " : "" ;
$sqladd .= $searchcontent!="" ? " AND $dbase.`".$table_prefix."site_content`.content LIKE '%$searchcontent%' " : "" ;

$sql = "SELECT id, pagetitle, description, deleted, published, isfolder, type FROM $dbase.`".$table_prefix."site_content` where 1=1 ".$sqladd." ORDER BY id;";
$rs = $modx->db->query($sql);
$limit = $modx->db->getRecordCount($rs);
?>
<div class="sectionHeader"><?php echo $_lang['search_results']; ?></div><div class="sectionBody">
<?php
if($limit<1) {
	echo $_lang['search_empty'];
} else {
	printf($_lang['search_results_returned_msg'], $limit);
?>
		<script type="text/javascript" src="media/script/tablesort.js"></script>
  <table border=0 cellpadding=2 cellspacing=0  class="sortabletable sortable-onload-2 rowstyle-even" id="table-1" width="90%"> 
    <thead> 
      <tr bgcolor='#CCCCCC'> 
		<th width="20"></th>
        <th class="sortable"><b><?php echo $_lang['search_results_returned_id']; ?></b></th> 
        <th class="sortable"><b><?php echo $_lang['search_results_returned_title']; ?></b></th> 
        <th class="sortable"><b><?php echo $_lang['search_results_returned_desc']; ?></b></th>
		<th width="20"></th>
		<th width="20"></th>
      </tr> 
    </thead> 
    <tbody>
     <?php
			for ($i = 0; $i < $limit; $i++) { 
				$logentry = $modx->db->getRow($rs);
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
    <tr> 
      <td align="center"><a href="index.php?a=3&id=<?php echo $logentry['id']; ?>"onMouseover="status='<?php echo $_lang['search_view_docdata']; ?>';return true;" onmouseout="status='';return true;" title="<?php echo $_lang['search_view_docdata']; ?>"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/context_view.gif" border=0></a></td> 
      <td><?php echo $logentry['id']; ?></td> 
	  <?php if (function_exists('mb_strlen') && function_exists('mb_substr')) {?>
		<td><?php echo mb_strlen($logentry['pagetitle'], $modx_manager_charset)>20 ? mb_substr($logentry['pagetitle'], 0, 20, $modx_manager_charset)."..." : $logentry['pagetitle'] ; ?></td> 
		<td><?php echo mb_strlen($logentry['description'], $modx_manager_charset)>35 ? mb_substr($logentry['description'], 0, 35, $modx_manager_charset)."..." : $logentry['description'] ; ?></td>
	  <?php } else { ?>
		<td><?php echo strlen($logentry['pagetitle'])>20 ? substr($logentry['pagetitle'], 0, 20)."..." : $logentry['pagetitle'] ; ?></td> 
		<td><?php echo strlen($logentry['description'])>35 ? substr($logentry['description'], 0, 35)."..." : $logentry['description'] ; ?></td>
	  <?php } ?>
      <td align="center"><?php echo $logentry['deleted']==1 ? "<img align='absmiddle' src='media/style/".($manager_theme ? $manager_theme.'/' : '')."images/icons/trash_full.png' alt='".$_lang['search_item_deleted']."'>" : ""; ?></td>
      <td align="center"><img align='absmiddle' src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/<?php echo $icon; ?>.gif'></td>	  
    </tr> 
    <?php
			}
?> 
    </tbody> 
     </table> 
<?php
}
?>
</div>
<?php
}
?>