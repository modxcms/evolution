<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('manage_metatags')) {	
	$e->setError(3);
	$e->dumpError();	
}

// initialize page view state - the $_PAGE object
$modx->manager->initPageViewState();

?>
<div class="subTitle">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['metatags_and_keywords'] ;?></span>
</div>

<script type="text/javascript">
	
	// meta tag rows
	var tagRows = []; // stores tag information in 2D array. 2nd array = 0-name,1-tag,2-value,3-http_equiv

	function checkForm() {
		var requireConfirm=false;
		var deleteList="";
	<?php for($i=0;$i<$limit;$i++) { 
		$row=mysql_fetch_assoc($rs);
		?>
		if(document.getElementById('delete<?php echo $row['id']; ?>').checked==true) {
			requireConfirm = true;
			deleteList = deleteList + "\n - <?php echo addslashes($row['keyword']); ?>";
			
		}
	<?php }	?>
		if(requireConfirm) {
			var agree=confirm("<?php echo $_lang['confirm_delete_keywords']; ?>\n" + deleteList);
			if(agree) {
				return true;
			} else {
				return false;
			}
		}
		return true;
	}
	
	function addTag() {
		var f=document.metatag;
		if(!f) return;
		if(!f.tagname.value) alert("<?php echo $_lang["require_tagname"];?>");
		else if(!f.tagvalue.value) alert("<?php echo $_lang["require_tagvalue"];?>");
		else {
			f.op.value=(f.cmdsavetag.value=="<?php echo $_lang["save_tag"];?>") ? 'edttag':'addtag';
			f.submit();
		}
	}

	function editTag(id){
		var opt;
		var f=document.metatag;
		if(!f) return;
		f.tagname.value = tagRows[id][0];
		f.tagvalue.value= tagRows[id][2];	
		for(i=0;i<f.tag.options.length;i++) {
			opt = f.tag.options[i];
			tagkey = tagRows[id][1]+";"+tagRows[id][3]; // combine tag and style to make key
			if(opt.value==tagkey){
				opt.selected = true;
				break;
			}
		}
		f.id.value=id;
		f.cmdsavetag.value='<?php echo $_lang["save_tag"];?>';
		f.cmdcanceltag.style.visibility = 'visible';
		f.tagname.focus();
	}

	function cancelTag(id){
		var opt;
		var f=document.metatag;
		if(!f) return;
		f.tagname.value = '';
		f.tagvalue.value= '';
		f.tag.options[0].selected = true;
		f.id.value='';
		f.cmdsavetag.value='<?php echo $_lang["add_tag"];?>';
		f.cmdcanceltag.style.visibility = 'hidden';
	}

	function deleteTag() {
		var f=document.metatag;
		if(!f) return;
		else if(confirm("<?php echo $_lang['confirm_delete_tags']; ?>")) {
			f.op.value='deltag';
			f.submit();
		}
	}
	
	</script>

<form name="metatag" method="post" action="index.php" onsubmit="return checkForm();">
<input type="hidden" name="a" value="82" />
<input type="hidden" name="op" value="82" />
<input type="hidden" name="id" value="" />
<!-- META tags -->
<div class="sectionHeader"><img src='<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['metatags'] ;?></div><div class="sectionBody">
	<?php echo $_lang['metatag_intro'] ;?><br /><br />
	<div class="searchbara">
	<table border="0" width="100%" cellspacing="1">
	  <tr>
		<td width="70%">
		<table border="0" cellspacing="1">
		  <tr>
			<td valign="bottom">Name:<br>
			<input type="text" name="tagname" size="15"></td>
			<td valign="bottom">Tag:<br>
			<select size="1" name="tag">
			<option value="Abstract;0">Abstract</option>
			<option value="Author;0">Author</option>
			<option value="Classification;0">Classification</option>
			<option value="Content-Type;1">Content-Type</option>
			<option value="Copyright;0">Copyright</option>
			<option value="Description;0">Description</option>
			<option value="Designer;0">Designer</option>
			<option value="Distribution;0">Distribution</option>			
			<option value="Expires;1">Expires</option>
			<option value="Generator;0">Generator</option>
			<option value="Language;1">Language</option>
			<option value="PICS-Label;1">PICS-Label</option>
			<option value="Pragma;1">Pragma</option>
			<option value="Refresh;1">Refresh</option>
			<option value="Reply-to;1">Reply-to</option>
			<option value="Revist-after;0">Revist-after</option>
			<option value="Robot;0">Robot</option>
			<option value="Set-Cookie;1">Set-Cookie</option>
			<option value="Subject;0">Subject</option>
			<option value="Title;0">Title</option>			
			</select></td>
			<td valign="bottom">Value:<br>
			<input type="text" name="tagvalue" size="20"></td>
			<td nowrap="nowrap"><br>
			<input type="button" value="<?php echo $_lang["add_tag"];?>" name="cmdsavetag" onclick="addTag()" /> <input style="visibility:hidden" type="button" value="<?php echo $_lang["cancel"];?>" name="cmdcanceltag" onclick="cancelTag()" /></td>
		  </tr>
		</table><img src="media/images/_tx_.gif" width="1" height="5" /></td>
	  </tr>
	</table>
	</div>
	<div>
	<?php

		$sql = "SELECT * " .
				"FROM ".$modx->getFullTableName("site_metatags")." st ".
				"ORDER BY name"; 
		$ds = mysql_query($sql); 
		include_once $base_path."manager/includes/controls/datagrid.class.php";
		$grd = new DataGrid('',$ds,$number_of_results); // set page size to 0 t show all items	
		$grd->noRecordMsg = $_lang["no_records_found"];
		$grd->cssClass="grid";
		$grd->columnHeaderClass="gridHeader";
		$grd->itemClass="gridItem"; 
		$grd->altItemClass="gridAltItem";
		$grd->fields="id,name,tag,tagvalue"; 
		$grd->columns=$_lang["delete"]." ,".$_lang["name"]." ,".$_lang["tag"]." ,".$_lang["value"];
		$grd->colWidths="40";					
		$grd->colAligns="center";
		$grd->colTypes="template:<input name='tag[]' type='checkbox' value='[+id+]'/><img src='media/images/icons/comment.gif' width='16' height='16' align='absmiddle' /></a>||".
					   "template:<a href='javascript:;' title='".$_lang["click_to_edit_title"]."' onclick='editTag([+id+])'>[+value+]</a><span style='display:none;'><script> tagRows['[+id+]']=[\"[+name+]\",\"[+tag+]\",\"[+tagvalue+]\",\"[+http_equiv+]\"];</script>";
		echo $grd->render();						
	?>
	</div>
	<table border=0 cellpadding=2 cellspacing=0> 
		<tr><td colspan="5">&nbsp;</td></tr>
		<tr>
			<td align="right">
				<input type="button" name="cmddeltag" value="<?php echo $_lang["delete_tags"];?>" onclick="deleteTag();" />
			</td>
		</tr>
	</table>
</div>

<!-- keywords -->
<div class="sectionHeader"><img src="media/style/".<?php echo $manager_theme ? "$manager_theme/":""; ?>."images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['keywords'] ;?></div><div class="sectionBody">
<?php echo $_lang['keywords_intro'] ;?><br /><br />
<?php
	$sql = "SELECT * FROM $dbase.".$table_prefix."site_keywords ORDER BY keyword ASC";
	$ds = mysql_query($sql);
	$grd = new DataGrid('',$ds,$number_of_results); // set page size to 0 t show all items	
	$grd->noRecordMsg = $_lang["no_keywords_found"];
	$grd->cssClass="grid";
	$grd->columnHeaderClass="gridHeader";
	$grd->itemClass="gridItem"; 
	$grd->altItemClass="gridAltItem";
	$grd->fields="id,keyword,keyword"; 
	$grd->columns=$_lang["delete"]." ,".$_lang["keyword"]." ,".$_lang["rename"];
	$grd->colWidths="40";					
	$grd->colAligns="center";
	$grd->colTypes="template:<input id='delete[+id+]' name='delete_keywords[[+id+]]' type='checkbox'  />||".
				   "template:<a onclick=\"if(document.getElementById('delete[+id+]').checked==true) { document.getElementById('delete[+id+]').checked=false; } else { document.getElementById('delete[+id+]').checked=true; }; return false;\" style='cursor:pointer'>[+keyword+]</a>||".
				   "template:<input type='hidden' name='orig_keywords[keyword[+id+]]' value='[+keyword+]' /><input type='text' name='rename_keywords[keyword[+id+]]' value='[+keyword+]' style='width:100%;' />";		
	echo $grd->render();						
?>
	<table border=0 cellpadding=2 cellspacing=0> 
		<tr><td colspan="5">&nbsp;</td></tr>
		<tr>
			<td>
				<input type=submit value="<?php echo $_lang['save_all_changes']; ?>" onsubmit="return checkForm();" /> &nbsp;				
			</td>
			<td align="right">&nbsp;<?php echo $_lang['new_keyword']; ?></td>
			<td>
				<input type="text" name="new_keyword" value="" size="30" />
			</td>
		</tr>
	</table>	
</div>
</form>
	