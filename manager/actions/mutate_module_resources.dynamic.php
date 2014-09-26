<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

if (!$modx->hasPermission('edit_module')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

// Get table names (alphabetical)
$tbl_active_users       = $modx->getFullTableName('active_users');
$tbl_site_content       = $modx->getFullTableName('site_content');
$tbl_site_htmlsnippets  = $modx->getFullTableName('site_htmlsnippets');
$tbl_site_module_depobj = $modx->getFullTableName('site_module_depobj');
$tbl_site_modules       = $modx->getFullTableName('site_modules');
$tbl_site_plugins       = $modx->getFullTableName('site_plugins');
$tbl_site_snippets      = $modx->getFullTableName('site_snippets');
$tbl_site_templates     = $modx->getFullTableName('site_templates');
$tbl_site_tmplvars      = $modx->getFullTableName('site_tmplvars');

// initialize page view state - the $_PAGE object
$modx->manager->initPageViewState();

// check to see the  editor isn't locked
$rs = $modx->db->select('username', $tbl_active_users, "action=108 AND id='{$id}' AND internalKey!='".$modx->getLoginUserID()."'");
	if ($username = $modx->db->getValue($rs)) {
			$modx->webAlertAndQuit(sprintf($_lang['lock_msg'], $username, 'module'));
	}
// end check for lock

// take action
switch ($_REQUEST['op']) {
	case 'add':
		// convert ids to numbers
		$opids = array_filter(array_map('intval', explode(',', $_REQUEST['newids'])));

		if (count($opids)>0){
			// 1-snips, 2-tpls, 3-tvs, 4-chunks, 5-plugins, 6-docs
			$rt = strtolower($_REQUEST["rt"]);
			if ($rt == 'chunk') $type = 10;
			if ($rt == 'doc')   $type = 20;
			if ($rt == 'plug')  $type = 30;
			if ($rt == 'snip')  $type = 40;
			if ($rt == 'tpl')   $type = 50;
			if ($rt == 'tv')    $type = 60;
			$modx->db->delete($tbl_site_module_depobj, "module='{$id}' AND resource IN (".implode(',',$opids).") AND type='{$type}'");
			foreach ($opids as $opid) {
				$modx->db->insert(
					array(
						'module'   => $id,
						'resource' => $opid,
						'type'     => $type,
					), $tbl_site_module_depobj);
			}
		}
		break;
	case 'del':
		// convert ids to numbers
		$opids = array_filter(array_map('intval', $_REQUEST['depid']));

		// get resources that needs to be removed
		$ds = $modx->db->select('*', $tbl_site_module_depobj, "id IN (".implode(",",$opids).")");
			// loop through resources and look for plugins and snippets
			$plids=array(); $snid=array();
			while ($row=$modx->db->getRow($ds)){
				if($row['type']=='30') $plids[$i]=$row['resource'];
				if($row['type']=='40') $snids[$i]=$row['resource'];
			}
			// get guid
			$ds = $modx->db->select('guid', $tbl_site_modules, "id='{$id}'");
				$guid = $modx->db->getValue($ds);
			// reset moduleguid for deleted resources
			if (($cp=count($plids)) || ($cs=count($snids))) {
				if ($cp) $modx->db->update(array('moduleguid'=>''), $tbl_site_plugins, "id IN (".implode(',', $plids).") AND moduleguid='{$guid}'");
				if ($cs) $modx->db->update(array('moduleguid'=>''), $tbl_site_plugins, "id IN (".implode(',', $snids).") AND moduleguid='{$guid}'");
				// reset cache
				$modx->clearCache('full');
			}
		$modx->db->delete($tbl_site_module_depobj, "id IN (".implode(',', $opids).")");
		break;
}

// load record
$rs = $modx->db->select('*', $tbl_site_modules, "id = '{$id}'");
$content = $modx->db->getRow($rs);
if(!$content) {
	$modx->webAlertAndQuit("Module not found for id '{$id}'.");
}
$_SESSION['itemname']=$content['name'];
if($content['locked']==1 && $_SESSION['mgrRole']!=1) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

?>
<script type="text/javascript">

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
	<h1><?php echo $_lang['module_resource_title']; ?></h1>

<div id="actions">
	<ul class="actionButtons">
		<li><a href="index.php?a=106"><img src="<?php echo $_style["icons_cancel"]?>" /> <?php echo $_lang['close']; ?></a>
	</ul>
</div>

<div class="section">
<div class="sectionHeader"><?php echo $content["name"]." - ".$_lang['module_resource_title']; ?></div>
<div class="sectionBody">
<p><img src="<?php echo $_style["icons_modules"] ?>" alt="" align="left" /><?php echo $_lang['module_resource_msg']; ?></p>
<br />
<!-- Dependencies -->
	 <table width="100%" border="0" cellspacing="1" cellpadding="2">
	  <tr>
		<td valign="top" align="left">
		<?php
			$ds = $modx->db->select(
				"smd.id,COALESCE(ss.name,st.templatename,sv.name,sc.name,sp.name,sd.pagetitle) as name,
				CASE smd.type
					WHEN 10 THEN 'Chunk'
					WHEN 20 THEN 'Document'
					WHEN 30 THEN 'Plugin'
					WHEN 40 THEN 'Snippet'
					WHEN 50 THEN 'Template'
					WHEN 60 THEN 'TV'
				END as type",
				"{$tbl_site_module_depobj} AS smd
					LEFT JOIN {$tbl_site_htmlsnippets} AS sc ON sc.id = smd.resource AND smd.type = '10'
					LEFT JOIN {$tbl_site_content} AS sd ON sd.id = smd.resource AND smd.type = '20'
					LEFT JOIN {$tbl_site_plugins} AS sp ON sp.id = smd.resource AND smd.type = '30'
					LEFT JOIN {$tbl_site_snippets} AS ss ON ss.id = smd.resource AND smd.type = '40'
					LEFT JOIN {$tbl_site_templates} AS st ON st.id = smd.resource AND smd.type = '50'
					LEFT JOIN {$tbl_site_tmplvars} AS sv ON sv.id = smd.resource AND smd.type = '60'",
				"smd.module={$id}",
				"smd.type,name");
				include_once MODX_MANAGER_PATH."includes/controls/datagrid.class.php";
				$grd = new DataGrid('',$ds,0); // set page size to 0 t show all items
				$grd->noRecordMsg = $_lang["no_records_found"];
				$grd->cssClass="grid";
				$grd->columnHeaderClass="gridHeader";
				$grd->itemClass="gridItem";
				$grd->altItemClass="gridAltItem";
				$grd->columns=$_lang["element_name"]." ,".$_lang["type"];
				$grd->colTypes = "template:<input type='checkbox' name='depid[]' value='[+id+]'> [+value+]";
				$grd->fields="name,type";
				echo $grd->render();
		?>
		</td>
		<td valign="top" width="120" style="background-color:#eeeeee">
			<a class="searchtoolbarbtn" style="float:left;width:120px;margin-bottom:10px;" href="#" style="margin-top:2px;width:102px" onclick="removeDependencies();return false;"><img src="<?php echo $_style["icons_delete_document"]?>" align="absmiddle" /> <?php echo $_lang['remove']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addSnippet();return false;"><img src="<?php echo $_style["icons_add"] ?>" align="absmiddle" /> <?php echo $_lang['add_snippet']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addDocument();return false;"><img src="<?php echo $_style["icons_add"] ?>" align="absmiddle" /> <?php echo $_lang['add_doc']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addChunk();return false;"><img src="<?php echo $_style["icons_add"] ?>" align="absmiddle" /> <?php echo $_lang['add_chunk']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addPlugin();return false;"><img src="<?php echo $_style["icons_add"] ?>" align="absmiddle" /> <?php echo $_lang['add_plugin']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addTV();return false;"><img src="<?php echo $_style["icons_add"] ?>" align="absmiddle" /> <?php echo $_lang['add_tv']; ?></a><br />
			<a class="searchtoolbarbtn" style="float:left;width:120px;" href="#" style="margin-top:2px;width:102px" onclick="addTemplate();return false;"><img src="<?php echo $_style["icons_add"] ?>" align="absmiddle" /> <?php echo $_lang['add_template']; ?></a><br />
		</td>
	  </tr>
	</table>
</div>
</div>
<input type="submit" name="save" style="display:none">
</form>
