<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if(!$modx->hasPermission('edit_user')) {
	$e->setError(3);
	$e->dumpError();
}
$theme = $manager_theme ? "$manager_theme/":"";

// initialize page view state - the $_PAGE object
$modx->manager->initPageViewState();

// get and save search string
if($_REQUEST['op']=='reset') {
	$query = '';
	$_PAGE['vs']['search']='';
}
else {
	$query = isset($_REQUEST['search'])? $_REQUEST['search']:$_PAGE['vs']['search'];
	$sqlQuery = $modx->db->escape($query);
	$_PAGE['vs']['search'] = $query;
}

// get & save listmode
$listmode = isset($_REQUEST['listmode']) ? $_REQUEST['listmode']:$_PAGE['vs']['lm'];
$_PAGE['vs']['lm'] = $listmode;


// context menu
include_once $base_path."manager/includes/controls/contextmenu.php";
$cm = new ContextMenu("cntxm", 150);
$cm->addItem($_lang["edit"],"js:menuAction(1)","media/style/$manager_theme/images/icons/logging.gif",(!$modx->hasPermission('edit_user') ? 1:0));
$cm->addItem($_lang["delete"], "js:menuAction(2)","media/style/$manager_theme/images/icons/delete.gif",(!$modx->hasPermission('delete_user') ? 1:0));
echo $cm->render();

?>
<script language="JavaScript" type="text/javascript">
  	function searchResource(){
		document.resource.op.value="srch";
		document.resource.submit();
	};

	function resetSearch(){
		document.resource.search.value = ''
		document.resource.op.value="reset";
		document.resource.submit();
	};

	function changeListMode(){
		var m = parseInt(document.resource.listmode.value) ? 1:0;
		if (m) document.resource.listmode.value=0;
		else document.resource.listmode.value=1;
		document.resource.submit();
	};

	var selectedItem;
	var contextm = <?php echo $cm->getClientScriptObject(); ?>;
	function showContentMenu(id,e){
		selectedItem=id;
		contextm.style.left = (e.pageX || (e.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft)))+"px";
		contextm.style.top = (e.pageY || (e.clientY + (document.documentElement.scrollTop || document.body.scrollTop)))+"px";
		contextm.style.visibility = "visible";
		e.cancelBubble=true;
		return false;
	};
	
	function menuAction(a) {
		var id = selectedItem;
		switch(a) {
			case 1:		// edit
				window.location.href='index.php?a=12&id='+id;
				break;
			case 2:		// delete
				if(confirm("<?php echo $_lang['confirm_delete_user']; ?>")==true) {
					window.location.href='index.php?a=33&id='+id;
				}
				break;
		}
	}

	document.addEvent('click', function(){
		contextm.style.visibility = "hidden";
	});
</script>
<form name="resource" method="post">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="listmode" value="<?php echo $listmode; ?>" />
<input type="hidden" name="op" value="" />

<h1><?php echo $_lang['user_management_title']; ?></h1>

<div class="sectionBody">
	<p><?php echo $_lang['user_management_msg']; ?></p>
	<div class="searchbar">
		<table border="0" style="width:100%">
			<tr>
			<td><a class="searchtoolbarbtn" href="index.php?a=11"><img src="<?php echo $_style["icons_save"] ?>" /> <?php echo $_lang['new_user']; ?></a></td>
			<td nowrap="nowrap">
				<table border="0" style="float:right"><tr><td><?php echo $_lang["search"]; ?></td><td><input class="searchtext" name="search" type="text" size="15" value="<?php echo $query; ?>" /></td>
				<td><a href="#" class="searchbutton" title="<?php echo $_lang["search"];?>" onclick="searchResource();return false;"><?php echo $_lang['go']; ?></a></td>
				<td><a href="#" class="searchbutton" title="<?php echo $_lang["reset"];?>" onclick="resetSearch();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/refresh.gif" width="16" height="16"/></a></td>
				<td><a href="#" class="searchbutton" title="<?php echo $_lang["list_mode"];?>" onclick="changeListMode();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/table.gif" width="16" height="16"/></a></td>
				</tr>
				</table>
			</td>
			</tr>
		</table>
	</div>
	<br />
	<div>
	<?php
	$noAdminSql = ($_SESSION['mgrRole'] != 1)? 'mua.role != 1' : '' ;
	$sql = "SELECT
		mu.id,
		mu.username,
		rname.name AS role,
		mua.fullname,
		mua.email,
		IF(mua.gender=1,'".$_lang['user_male']."',IF(mua.gender=2,'".$_lang['user_female']."','-')) AS gender,
		IF(mua.blocked,'".$_lang['yes']."','-') as blocked " .
		"FROM ".$modx->getFullTableName('manager_users')." AS mu ".
		"INNER JOIN ".$modx->getFullTableName('user_attributes')." AS mua ON mua.internalKey=mu.id ".
		"LEFT JOIN ".$modx->getFullTableName('user_roles')." AS rname ON mua.role=rname.id ";
	if ($noAdminSql){
	    if(!empty($sqlQuery)){
	        $sql .= "WHERE ((mu.username LIKE '$sqlQuery%') OR (mua.fullname LIKE '%$sqlQuery%') OR (mua.email LIKE '$sqlQuery%')) AND $noAdminSql ";
	    } else {
	        $sql .= "WHERE $noAdminSql ";
	    }
	} else {
	    $sql .= (!empty($sqlQuery) ? "WHERE (mu.username LIKE '$sqlQuery%') OR (mua.fullname LIKE '%$sqlQuery%') OR (mua.email LIKE '$sqlQuery%') ":"");
	}
	$sql .= "ORDER BY username";

	$ds = mysql_query($sql);
	include_once $base_path."manager/includes/controls/datagrid.class.php";
	$grd = new DataGrid('',$ds,$number_of_results); // set page size to 0 t show all items
	$grd->noRecordMsg = $_lang["no_records_found"];
	$grd->cssClass="grid";
	$grd->columnHeaderClass="gridHeader";
	$grd->itemClass="gridItem";
	$grd->altItemClass="gridAltItem";
	$grd->fields="id,username,fullname,role,email,gender,blocked";
	$grd->columns=$_lang["icon"].",".$_lang["name"].",".$_lang["user_full_name"].",".$_lang['role'].",".$_lang["email"].",".$_lang["user_gender"].",".$_lang["user_block"];
	$grd->colWidths="34,,,,,40,34";
	$grd->colAligns="center,,,,,center,center";
	$grd->colTypes='template:<a class="gridRowIcon" href="#" onclick="return showContentMenu([+id+],event);" title="'.$_lang['click_to_context'].'"><img src="media/style/'.$manager_theme.'/images/icons/user.gif" /></a>||template:<a href="index.php?a=12&id=[+id+]" title="'.$_lang['click_to_edit_title'].'">[+value+]</a>';
	if($listmode=='1') $grd->pageSize=0;
	if($_REQUEST['op']=='reset') $grd->pageNumber = 1;
	// render grid
	echo $grd->render();
	?>
	</div>
</div>
</form>
