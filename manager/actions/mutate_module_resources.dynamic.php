<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('edit_module')) {
	$e->setError(3);
	$e->dumpError();
}

// initialize page view state - the $_PAGE object
$modx->manager->initPageViewState();


if(isset($_REQUEST['id'])) {
	$id = intval($_REQUEST['id']);
} else {
	$id=0;
}


// check to see the  editor isn't locked
$sql = "SELECT internalKey, username FROM $dbase.`".$table_prefix."active_users` WHERE $dbase.`".$table_prefix."active_users`.action=108 AND $dbase.`".$table_prefix."active_users`.id=$id";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit>1) {
	for ($i=0;$i<$limit;$i++) {
		$lock = mysql_fetch_assoc($rs);
		if($lock['internalKey']!=$modx->getLoginUserID()) {
			$msg = sprintf($_lang["lock_msg"],$lock['username'],"module");
			$e->setError(5, $msg);
			$e->dumpError();
		}
	}
}
// end check for lock

// make sure the id's a number
if(!is_numeric($id)) {
	echo "Passed ID is not a valid number!";
	exit;
}

// take action
switch ($_REQUEST['op']) {
	case 'add':
		$opids = explode(",",$_REQUEST['newids']);
		if (count($opids)>0){
			// 1-snips, 2-tpls, 3-tvs, 4-chunks, 5-plugins, 6-docs
			$rt = strtolower($_REQUEST["rt"]);
			if($rt=="chunk")$type=10;
			if($rt=="doc") 	$type=20;
			if($rt=="plug") $type=30;
			if($rt=="snip") $type=40;
			if($rt=="tpl")	$type=50;
			if($rt=="tv")	$type=60;
			$sql="INSERT INTO ".$modx->getFullTableName("site_module_depobj")." (module,resource,type) VALUES ";
			for($i=0;$i<count($opids);$i++) {
				if($i!=0) $sql.=",";
				$opids[$i] = intval($opids[$i]);
				$sql.="('$id',".$opids[$i].",$type)";
			}
			$modx->dbQuery("DELETE FROM ".$modx->getFullTableName("site_module_depobj")." WHERE module='$id' AND resource IN (".implode(",",$opids).") AND type='$type'");
			$ds = $modx->dbQuery($sql);
			if(!$ds){
				echo "<script type='text/javascript'>" .
						"function jsalert(){ alert('An error occured while trying to update the database. '".mysql_error().");" .
						"setTimeout('jsalert()',100)".
						"</script>";
			}
		}
		break;
	case 'del':
		$opids = $_REQUEST['depid'];
		for($i=0;$i<count($opids);$i++) {
			$opids[$i]=intval($opids[$i]); // convert ids to numbers
		}
		// get resources that needs to be removed
		$ds = $modx->dbQuery("SELECT * FROM ".$modx->getFullTableName("site_module_depobj")." WHERE id IN (".implode(",",$opids).")");
		if ($ds) {
			// loop through resources and look for plugins and snippets
			$i=0; $plids=array(); $snid=array();
			while ($row=mysql_fetch_assoc($ds)){
				if($row['type']=='30') $plids[$i]=$row['resource'];
				if($row['type']=='40') $snids[$i]=$row['resource'];
			}
			// get guid
			$ds = $modx->dbQuery("SELECT * FROM ".$modx->getFullTableName("site_modules")." WHERE id='$id'");
			if($ds) {
				$row = $modx->fetchRow($ds);
				$guid = $row['guid'];
			}
			// reset moduleguid for deleted resources
			if(($cp=count($plids))||($cs=count($snids))){
				if($cp) $modx->dbQuery("UPDATE ".$modx->getFullTableName("site_plugins")." SET moduleguid='' WHERE id IN (".implode(",",$plids).") AND moduleguid='$guid'");
				if($cs) $modx->dbQuery("UPDATE ".$modx->getFullTableName("site_snippets")." SET moduleguid='' WHERE id IN (".implode(",",$snids).") AND moduleguid='$guid'");
				// reset cache
				include_once $base_path."/manager/processors/cache_sync.class.processor.php";
				$sync = new synccache();
				$sync->setCachepath("../assets/cache/");
				$sync->setReport(false);
				$sync->emptyCache(); // first empty the cache
			}
		}
		$sql="DELETE FROM ".$modx->getFullTableName("site_module_depobj")." WHERE id IN (".implode(",",$opids).")";
		$modx->dbQuery($sql);
		break;
}

// load record
$sql = "SELECT * FROM ".$modx->getFullTableName("site_modules")." WHERE id = $id;";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit>1) {
	echo "<p>Multiple modules sharing same unique id. Please contact the Site Administrator.<p>";
	exit;
}
if($limit<1) {
	echo "<p>Module not found for id '$id'.</p>";
	exit;
}
$content = mysql_fetch_assoc($rs);
$_SESSION['itemname']=$content['name'];
if($content['locked']==1 && $_SESSION['mgrRole']!=1) {
	$e->setError(3);
	$e->dumpError();
}

?>
<script language="JavaScript">

	function removeDependencies() {
		if(confirm("<?php echo $_lang['confirm_delete_record']; ?>")==true) {
			documentDirty=false;
			document.mutate.op.value="del";
			document.mutate.submit();
		}
	};

	function addSnippet(){
		openSelector("snip","m","setResource");
	};

	function addDocument(){
		openSelector("doc","m","setResource");
	};

	function addTemplate(){
		openSelector("tpl","m","setResource");
	};

	function addTV(){
		openSelector("tv","m","setResource");
	};

	function addChunk(){
		openSelector("chunk","m","setResource");
	};

	function addPlugin(){
		openSelector("plug","m","setResource");
	};

	function setResource(rt,ids){
		if(ids.length==0) return;
		document.mutate.op.value = "add";
		document.mutate.rt.value = rt;
		document.mutate.newids.value = ids.join(",");
		document.mutate.submit();
	};

	function openSelector(resource,mode,callback,w,h){
		var win
		w = w ? w:600;
		h = h ? h:400;
		url = "index.php?a=84&sm="+mode+"&rt="+resource+"&cb="+callback
		// center on parent
		if (window.screenX) {
			var x = window.screenX + (window.outerWidth - w) / 2;
			var y = window.screenY + (window.outerHeight - h) / 2;
		} else {
			var x = (screen.availWidth - w) / 2;
			var y = (screen.availHeight - h) / 2;
		}
		self.chkBoxArray = {}; //reset checkbox array;
		win = window.open(url,"resource_selector","left="+x+",top="+y+",height="+h+",width="+w+",status=yes,scrollbars=yes,resizable=yes,toolbar=no,menubar=no,location=no");
		win.opener = self;
	};
</script>

<form name="mutate" method="post" action="index.php?a=113">
<input type="hidden" name="op" value="" />
<input type="hidden" name="rt" value="" />
<input type="hidden" name="newids" value="" />
<input type="hidden" name="id" value="<?php echo $content['id'];?>" />
<div class="subTitle">
	<span class="right"><?php echo $_lang['module_resource_title']; ?></span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<td id="Button4"><a href="index.php?a=106"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['close']; ?></a></td>
	</table>
</div>



<div class="sectionHeader"><?php echo $content["name"]." - ".$_lang['module_resource_title']; ?></div><div class="sectionBody">
<p><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/modules.gif' alt="." width="32" height="32" align="left" hspace="10" /><?php echo $_lang['module_resource_msg']; ?></p>
<br />
<!-- Dependencies -->
	 <table width="100%" border="0" cellspacing="1" cellpadding="2">
	  <tr>
		<td valign="top" align="left">
		<?php
			$sql = "SELECT smd.id,COALESCE(ss.name,st.templatename,sv.name,sc.name,sp.name,sd.pagetitle) as 'name'," .
					"CASE smd.type " .
					" WHEN 10 THEN 'Chunk' " .
					" WHEN 20 THEN 'Document' " .
					" WHEN 30 THEN 'Plugin' " .
					" WHEN 40 THEN 'Snippet' " .
					" WHEN 50 THEN 'Template' " .
					" WHEN 60 THEN 'TV' " .
					"END as 'type' " .
					"FROM ".$modx->getFullTableName("site_module_depobj")." smd ".
					"LEFT JOIN ".$modx->getFullTableName("site_htmlsnippets")." sc ON sc.id = smd.resource AND smd.type = '10' ".
					"LEFT JOIN ".$modx->getFullTableName("site_content")." sd ON sd.id = smd.resource AND smd.type = '20' ".
					"LEFT JOIN ".$modx->getFullTableName("site_plugins")." sp ON sp.id = smd.resource AND smd.type = '30' ".
					"LEFT JOIN ".$modx->getFullTableName("site_snippets")." ss ON ss.id = smd.resource AND smd.type = '40' ".
					"LEFT JOIN ".$modx->getFullTableName("site_templates")." st ON st.id = smd.resource AND smd.type = '50' ".
					"LEFT JOIN ".$modx->getFullTableName("site_tmplvars")." sv ON sv.id = smd.resource AND smd.type = '60' ".
					"WHERE smd.module=$id ORDER BY smd.type,name ";
			$ds = $modx->dbQuery($sql);
			if (!$ds){
				echo "An error occured while loading module dependencies.";
			}
			else {
				include_once $base_path."manager/includes/controls/datagrid.class.php";
				$grd = new DataGrid('',$ds,0); // set page size to 0 t show all items
				$grd->noRecordMsg = $_lang["no_records_found"];
				$grd->cssClass="grid";
				$grd->columnHeaderClass="gridHeader";
				$grd->itemClass="gridItem";
				$grd->altItemClass="gridAltItem";
				$grd->columns=$_lang["resource_name"]." ,".$_lang["type"];
				$grd->colTypes = "template:<input type='checkbox' name='depid[]' value='[+id+]'> [+value+]";
				$grd->fields="name,type";
				echo $grd->render();
			}
		?>
		</td>
		<td valign="top" width="120" style="background-color:#eeeeee">
			<a class="searchtoolbarbtn" style="float:left;width:120px;margin-bottom:10px;" href="#" style="margin-top:2px;width:102px" onclick="removeDependencies();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle" /> <?php echo $_lang['remove']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addSnippet();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang['add_snippet']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addDocument();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang['add_doc']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addChunk();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang['add_chunk']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addPlugin();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang['add_plugin']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addTV();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang['add_tv']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addTemplate();return false;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang['add_template']; ?></a><br />
		</td>
	  </tr>
	</table>
</div>
<input type="submit" name="save" style="display:none">
</form>
