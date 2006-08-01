<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('view_eventlog') && $_REQUEST['a']==114) {
	$e->setError(3);
	$e->dumpError();
}
$theme = $manager_theme ? "$manager_theme/":"";
// initialize page view state - the $_PAGE object
$modx->manager->initPageViewState();

// get and save search string
if($_REQUEST['op']=='reset') {
	$sqlQuery = $query = '';
	$_PAGE['vs']['search']='';
}
else {
	$sqlQuery = $query = isset($_REQUEST['search'])? $_REQUEST['search']:$_PAGE['vs']['search'];
	if(!is_numeric($sqlQuery)) $sqlQuery = mysql_escape_string($query);
	$_PAGE['vs']['search'] = $query;
}

// get & save listmode
$listmode = isset($_REQUEST['listmode']) ? $_REQUEST['listmode']:$_PAGE['vs']['lm'];
$_PAGE['vs']['lm'] = $listmode;

// context menu
include_once $base_path."manager/includes/controls/contextmenu.php";
$cm = new ContextMenu("cntxm", 150);
$cm->addItem($_lang["view_log"],"js:menuAction(1)","media/style/.$manager_theme./images/icons/save.gif");
$cm->addSeparator();
$cm->addItem($_lang["delete"], "js:menuAction(2)","media/style/$manager_theme/images/icons/delete.gif",(!$modx->hasPermission('delete_eventlog') ? 1:0));
echo $cm->render();

?>

<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['eventlog']; ?></span>
</div>
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
		var x,y,st = document.getScrollTop();
		x = e.clientX>0 ? e.clientX:e.pageX;
		y = e.clientY>0 ? e.clientY:e.pageY;
		selectedItem=id;
		if (((y+contextm.getHeight())-10)>document.getHeight()) y=document.getHeight() - contextm.getHeight();
		contextm.setLocation(x+5,y+st-10);
		contextm.setVisible(true);
		e.cancelBubble=true;
		return false;
	};
	function menuAction(a) {
		var id = selectedItem;
		switch(a) {
			case 1:		// view log details
				window.location.href='index.php?a=115&id='+id;
				break;
			case 2:		// clear log
				window.location.href='index.php?a=116&id='+id ;
				break;
		}
	}

	document.addEventListener("onclick",function(){
		contextm.setVisible(false);
	})
</script>
<form name="resource" method="post">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="listmode" value="<?php echo $listmode; ?>" />
<input type="hidden" name="op" value="" />
<div class="sectionHeader"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['eventlog_viewer']; ?></div><div class="sectionBody">
	<!-- load modules -->
	<p><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/eventlog.gif' alt="." width="32" height="32" align="left" hspace="10" /><?php echo $_lang['eventlog_msg']; ?></p>
	<div class="searchbar">
		<table border="0" style="width:100%">
			<tr>
			<td><a class="searchtoolbarbtn" href="index.php?a=116&cls=1"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif"  align="absmiddle" /> <?php echo $_lang['clear_log']; ?></a></td>
			<td nowrap="nowrap">
				<table border="0" style="float:right"><tr><td>Search </td><td><input class="searchtext" name="search" type="text" size="15" value="<?php echo $query; ?>" /></td>
				<td><a href="javascript:;" class="searchbutton" title="<?php echo $_lang["search"];?>" onclick="searchResource();return false;">Go</a></td>
				<td><a href="javascript:;" class="searchbutton" title="<?php echo $_lang["reset"];?>" onclick="resetSearch();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/refresh.gif" width="16" height="16"/></a></td>
				<td><a href="javascript:;" class="searchbutton" title="<?php echo $_lang["list_mode"];?>" onclick="changeListMode();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/table.gif" width="16" height="16"/></a></td>
				</tr>
				</table>
			</td>
			</tr>
		</table>
	</div>
	<br />
	<div>
	<?php


	$sql = "SELECT el.id, el.type, el.createdon, el.source, el.eventid,IFNULL(wu.username,mu.username) as 'username' " .
			"FROM ".$modx->getFullTableName("event_log")." el ".
			"LEFT JOIN ".$modx->getFullTableName("manager_users")." mu ON mu.id=el.user AND el.usertype=0 ".
			"LEFT JOIN ".$modx->getFullTableName("web_users")." wu ON wu.id=el.user AND el.usertype=1 ".
			($sqlQuery ? " WHERE ".(is_numeric($sqlQuery)?"(eventid='$sqlQuery') OR ":'')."(source LIKE '%$sqlQuery%') OR (description LIKE '%$sqlQuery%')":"")." ".
			"ORDER BY createdon DESC";
	$ds = mysql_query($sql);
	include_once $base_path."manager/includes/controls/datagrid.class.php";
	$grd = new DataGrid('',$ds,$number_of_results); // set page size to 0 t show all items
	$grd->noRecordMsg = $_lang["no_records_found"];
	$grd->cssClass="grid";
	$grd->columnHeaderClass="gridHeader";
	$grd->itemClass="gridItem";
	$grd->altItemClass="gridAltItem";
	$grd->fields="type,source,createdon,eventid,username";
	$grd->columns=$_lang["type"]." ,".$_lang["source"]." ,".$_lang["date"]." ,".$_lang["event_id"]." ,".$_lang["sysinfo_userid"];
	$grd->colWidths="34,,150,60";
	$grd->colAligns="center,,,center,center";
	$grd->colTypes="template:<a class='gridRowIcon' href='#' onclick='return showContentMenu([+id+],event);' title='".$_lang["click_to_context"]."'><img src='media/style/$manager_theme/images/icons/event[+type+].gif' width='16' height='16' /></a>||template:<a href='index.php?a=115&id=[+id+]' title='".$_lang["click_to_view_details"]."'>[+source+]</a>||date: %d-%b-%Y %I:%M %p";
	if($listmode=='1') $grd->pageSize=0;
	if($_REQUEST['op']=='reset') $grd->pageNumber = 1;
	// render grid
	echo $grd->render();
	?>
	</div>
</div>
</form>