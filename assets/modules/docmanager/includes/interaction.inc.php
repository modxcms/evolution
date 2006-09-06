<?php

/**
 * Document Manager Module - interaction.inc.php
 * 
 * Purpose: Contains the main visual output functions for the module
 * Author: Garry Nutting (Mark Kaplan - Menu Index functionalty, Luke Stokes - Document Permissions concept)
 * For: MODx CMS (www.modxcms.com)
 * Date:03/09/2006 Version: 1.5
 * 
 */

function buttonCSS() {
	global $theme;

	$output .= '
			<style type="text/css">
			.topdiv {
			border: 0;
		}
		
		.subdiv {
			border: 0;
		}
	
		li {list-style:none;}
		
		.tplbutton {
			text-align: right;
		}
		
		#bttn .bttnheight {
			height: 25px !important;
			padding: 0px;
			padding-top: 6px;
			float: left;
			vertical-align: middle !important;
		}
		
		ul.sortableList {
			padding-left: 20px;
			margin: 0px;
			width: 300px;
			font-family: Arial, sans-serif;
		}
		
		ul.sortableList li {
			font-weight: bold;
			cursor: move;
			color: grey;
			padding: 2px 2px;
			margin: 2px 0px;
			border: 1px solid #000000;
			background-image: url("media/style' . $theme . '/images/bg/grid_hdr.gif");
			background-repeat: repeat-x;
		}
		
			#bttn .bttnheight {
				height: 25px !important;
				padding: 0px; 
				padding-top: 6px;
				float: left;
				vertical-align:		middle !important;
			
			}
			#bttn a{
				cursor: 			default !important;
				font: 				icon !important;
				color:				black !important;
				border:				0px !important;
				padding:			5px 5px 7px 5px!important;
				white-space:		nowrap !important;
				vertical-align:		middle !important;
				background:	transparent !important;
				text-decoration: none;
			}
			
			#bttn a:hover {
				border:		1px solid darkgreen !important;
				padding:			4px 4px 6px 4px !important;		
				background-image:	url("media/style' . $theme . '/images/bg/button_dn.gif") !important;
				text-decoration: none;
			}
			
			#bttn a img {
				vertical-align: middle !important;
			}
			
			.go a {
				cursor: default !important;
				font: icon !important;
				color: black !important;
				border: 0px !important;
				padding: 5px 5px 7px 5px !important;
				white-space: nowrap !important;
				vertical-align: middle !important;
				background: transparent;
				text-decoration: none;
			}
			
			.go a:hover {
				border: 1px solid darkgreen !important;
				padding: 4px 4px 6px 4px !important;
				background: url("media/style' . $theme . '/images/bg/button_dn.gif");
				text-decoration: none;
			}
			
			.go a img {
				vertical-align: middle !important;
			}
			
			</style>';

	return $output;

}

/**
 * showTemplateVariables - shows the main template variable form§
 * 
 */
function showTemplateVariables() {
	global $modx;
	global $_lang;
	global $theme;

	$temptable = $modx->getFullTableName('site_templates');
	$templates = $modx->db->select('id,templatename,description', $temptable,'','id ASC');

	$output = '<p>' . $_lang['DM_tv_desc'] . '</p><br />';
	$output .= '<form name="templatevariables" method="post">';
	
	$alt = 0;
	if($modx->db->getRecordCount($templates) > 0) {
	$output.='<table style="width:100%">';
	$output.='<tr><td class="gridHeader"></td>';
	$output.='<td class="gridHeader">'.$_lang['DM_tpl_column_name'].'</td>';
	$output.='<td class="gridHeader">'.$_lang['DM_tpl_column_description'].'</td>';
	$output.='</tr>';
	while ($row = $modx->db->getRow($templates)) {
		$output.='<tr><td '.($alt==0 ? 'class="gridItem"' : 'class="gridAltItem"').'>';
		$output.= '<input name="tid" type=\'radio\' '.(isset($_POST['selectedTV']) && $_POST['selectedTV'] == $row['id'] ? 'checked' : '').' name=\'id\' onclick="document.getElementById(\'tvloading\').style.display=\'block\';new Ajax.Updater(\'results\',\''.$modx->getConfig('site_url').'assets/modules/docmanager/includes/tv.ajax.php\', {method:\'post\',evalScripts:true, postBody:\'theme='.$theme.'&langIgnoreTV='.addslashes($_lang['DM_tv_ignore_tv']).'&langNoTV='.addslashes($_lang['DM_tv_no_tv']).'&tplID='.$row['id'].'&langInsert='.$_lang['DM_tv_ajax_insertbutton'].'\',onSuccess: function(t) { $(\'results\').innerHTML = t.responseText; $(\'selectedTV\').value=\''.$row['id'].'\';}}); document.getElementById(\'tvloading\').style.display = \'none\';" value=\''.$row['id'].'\' /> '.$row['id'];
		$output.='</td><td '.($alt==0 ? 'class="gridItem"' : 'class="gridAltItem"').'>';
		$output.= $row['templatename'];
		$output.='</td><td '.($alt==0 ? 'class="gridItem"' : 'class="gridAltItem"').'>';
		$output.= ($row['description'] != '') ? $row['description'] : '&nbsp;';
		$output.='</td></tr>';
		if($alt == 0) $alt=1;
		else $alt = 0;
	}
	$output.='</table>';
	} else {
		$output.=$_lang['DM_tpl_no_templates'];
	}
	
	$output.='<div id="tvloading" class="warning" style="display:none">'.$_lang['DM_tv_loading'].'</div><br />';
	$output.='<br />' .
			'<div id="results">'.$_lang['DM_tv_no_template_selected'].'</div>';
	
	$output.='<input type="hidden" name="opcode" value="" /> 
			  <input type="hidden" name="pids" value="" />
			  <input type="hidden" name="tplID" value="" />
			  <input type="hidden" name="tabAction" value="" />';
	$output .= '</form>';

	return $output;
}

/**
 * showTemplate - shows the main template form for the tabbed interface
 * 
 */
function showTemplate() {
	global $modx;
	global $_lang;

	$temptable = $modx->getFullTableName('site_templates');
	$templates = $modx->db->select('id,templatename,description', $temptable,'','id ASC');

	$output = '<p>' . $_lang['DM_tpl_desc'] . '</p><br />';
	$output .= '<form name="template">';

	//-- render list of templates
	$grd = new DataGrid('', $templates);
	// set page size to 0 to show all items 
	$grd->noRecordMsg = $_lang['DM_tpl_no_templates'];
	$grd->cssClass = "grid";
	$grd->columnHeaderClass = "gridHeader";
	$grd->itemClass = "gridItem";
	$grd->altItemClass = "gridAltItem";
	$grd->columns = " ," . $_lang['DM_tpl_column_id'] . "," . $_lang['DM_tpl_column_name'] . "," . $_lang['DM_tpl_column_description'];
	$grd->colTypes = "template:<input type='radio' name='id' value='[+id+]' /> [+value+]";
	$grd->colWidths = "5%,5%,40%,50%";
	$grd->fields = "template,id,templatename,description";
	$output .= $grd->render();
	$output .= "<br /> 
								<table class='grid' cellpadding='1' cellspacing='1'><tr><td class='gridItem' style=\"width:5%;\"><input type='radio' name='id' 
			value='0' /></td><td  class='gridItem' style=\"width:5%;\">0</td><td style=\"width:40%;\" class='gridItem'>" . $_lang['DM_tpl_blank_template'] . "</td><td style=\"width:50%;\"></td></tr></table> 
								";

	$output .= '</form>';

	return $output;

}

/**
 * showDocGroups - shows the main document permissions form for the tabbed interface
 * 
 */
function showDocGroups() {
	global $modx;
	global $_lang;

	$doctable = $modx->getFullTableName('documentgroup_names');
	$documentgroups = $modx->db->select('id,name', $doctable,'','id ASC');

	$output = '<p>' . $_lang['DM_doc_desc'] . '</p><br />';
	$output .= '<form name="docgroups">';

	//-- render list of templates
	$grd = new DataGrid('', $documentgroups);
	// set page size to 0 to show all items 
	$grd->noRecordMsg = $_lang['DM_doc_no_docs'];
	$grd->cssClass = "grid";
	$grd->columnHeaderClass = "gridHeader";
	$grd->itemClass = "gridItem";
	$grd->altItemClass = "gridAltItem";
	$grd->columns = " ," . $_lang['DM_doc_column_id'] . "," . $_lang['DM_doc_column_name'];
	$grd->colTypes = "template:<input type='radio' name='docgroupid' value='[+id+]' /> [+value+]";
	$grd->colWidths = "5%,5%,40%,50%";
	$grd->fields = "template,id,name";
	$output .= $grd->render();

	$output .= '<br /><br />';
	$output .= '<input type="radio" name="tabAction" value="pushDocGroup" checked />&nbsp;' . $_lang['DM_doc_radio_add'] . '&nbsp;&nbsp;';
	$output .= '<input type="radio" name="tabAction" value="pullDocGroup" />&nbsp;' . $_lang['DM_doc_radio_remove'] . '<br /><br />';

	$output .= '</form>';

	return $output;

}

/**
 * showSortMenu - shows the main Sort Menu output for the tabbed interface
 * 
 */
function showSortMenu() {
	global $_lang;
	global $theme;

	$output .= ' 
							<form method="post" action="" name=\'newdocumentparent\'> 
							<span id="parentName" class="warning">' . $_lang['DM_sort_pick_item'] . '</span><br /> 
							<input name="actionkey" type="hidden" value="1" /> 
							<input type="hidden" name="new_parent" value="" class="inputBox" />
							<input type="hidden" name="tabAction" value="sortMenu" />    
							<br /> 
							<input type=\'save\' value="' . $_lang['DM_save'] . '" style="display:none"> 
							</form>
							';

	$output .= '<div class="go"><a id="Button1" onclick="save();">
						    <img src="media/style' . $theme . '/images/icons/save.gif">' . $_lang['DM_go'] . '</a><br /><br /></div>';

	return $output;

}

/**
 * showAdjustDates - shows the main 'Adjust Dates' form for the tabbed interface
 * 
 */
function showAdjustDates() {
	global $_lang;

	$output .= '<br /><h3>' . $_lang['DM_adjust_dates_header'] . '</h3><br />
				   <p>' . $_lang['DM_adjust_dates_desc'] . '</p></br />
				   <form style="margin-left:50px;" id="dates" name="dates" method="post" action="">
							<label for="date_pubdate" id="date_pubdate_label">' . $_lang['DM_date_pubdate'] . '</label><input type="hidden" id="date_pubdate" name="date_pubdate" />
								<span id="date_pubdate_show" name="date_pubdate_show"> (not set)</span>
								<a href="#" onclick="caldate1.popup();">' . $_lang['DM_view_calendar'] . '</a>&nbsp;&nbsp;
						  		<a href="#" onclick="document.forms[\'dates\'].elements[\'date_pubdate\'].value=\'\';document.getElementById(\'date_pubdate_show\').innerHTML=\'(not set)\'; return true;">' . $_lang['DM_clear_date'] . '</a>
							<br /><br />
							<label for="date_unpubdate" id="date_unpubdate_label">' . $_lang['DM_date_unpubdate'] . '</label><input type="hidden" id="date_unpubdate" name="date_unpubdate" />
								<span id="date_unpubdate_show" name="date_unpubdate_show"> (not set)</span>
								<a href="#" onclick="caldate2.popup();">' . $_lang['DM_view_calendar'] . '</a>&nbsp;&nbsp;
						  		<a href="#" onclick="document.forms[\'dates\'].elements[\'date_unpubdate\'].value=\'\';document.getElementById(\'date_unpubdate_show\').innerHTML=\'(not set)\'; return true;">' . $_lang['DM_clear_date'] . '</a>
							<br /><br />
							<label for="date" id="date_createdon_label">' . $_lang['DM_date_createdon'] . '</label><input type="hidden" id="date_createdon" name="date_createdon" />
								<span id="date_createdon_show" name="date_createdon_show"> (not set)</span>
								<a href="#" onclick="caldate3.popup();">' . $_lang['DM_view_calendar'] . '</a>&nbsp;&nbsp;
						  		<a href="#" onclick="document.forms[\'dates\'].elements[\'date_createdon\'].value=\'\';document.getElementById(\'date_createdon_show\').innerHTML=\'(not set)\'; return true;">' . $_lang['DM_clear_date'] . '</a>
							<br /><br />
							<label for="date_editedon" id="date_editedon_label">' . $_lang['DM_date_editedon'] . '</label><input type="hidden" id="date_editedon" name="date_editedon" />
						  		<span id="date_editedon_show" name="date_editedon_show"> (not set)</span>
								<a href="#" onclick="caldate4.popup();">' . $_lang['DM_view_calendar'] . '</a>&nbsp;&nbsp;
						  		<a href="#" onclick="document.forms[\'dates\'].elements[\'date_editedon\'].value=\'\';document.getElementById(\'date_editedon_show\').innerHTML=\'(not set)\'; return true;">' . $_lang['DM_clear_date'] . '</a>
						  </form>
						  	';

	$calarray = array (
		'caldate1',
		'caldate2',
		'caldate3',
		'caldate4'
	);
	$caltarget = array (
		'date_pubdate',
		'date_unpubdate',
		'date_createdon',
		'date_editedon'
	);

	$i = 0;
	$field_html .= '<script type="text/javascript">';

	foreach ($calarray as $cal) {
		$field_html .= '	var ' . $cal . ' = new calendar1(document.forms[\'dates\'].elements[\'' . $caltarget[$i] . '\'], document.getElementById("' . $caltarget[$i] . '_show"));';
		$field_html .= '   ' . $cal . '.path="' . str_replace("index.php", "media/", $_SERVER["PHP_SELF"]) . '";';

		$field_html .= '	' . $cal . '.year_scroll = true;';
		$field_html .= '   ' . $cal . '.time_comp = true;';

		$i += 1;
	}
	$field_html .= '</script>';

	return $output . $field_html;

}

/**
 * showAdjustAuthors - shows the main 'Adjust Authors' form for the tabbed interface
 * 
 */
function showAdjustAuthors() {
	global $_lang;
	global $modx;

	$rs = $modx->db->select('id,username', $modx->getFullTableName('manager_users'));

	$userOptions = '';

	while ($row = $modx->db->getRow($rs)) {
		$userOptions .= '<option value="' . $row['id'] . '">' . $row['username'] . '</option>';
	}

	$output .= '<br /><h3>' . $_lang['DM_adjust_authors_header'] . '</h3><br />
				   <p>' . $_lang['DM_adjust_authors_desc'] . '</p></br />
				   <form style="margin-left:50px;" name="authors" method="post" action="">
				   <label for="author_createdby">' . $_lang['DM_adjust_authors_createdby'] . '</label>
				   <select name="author_createdby" style="width:50%">
				   <option value="0">' . $_lang['DM_adjust_authors_noselection'] . '</option>';

	$output .= $userOptions;

	$output .= '</select><br /><br />';

	$output .= ' <label for="author_editedby">' . $_lang['DM_adjust_authors_editedby'] . '</label>
				   <select name="author_editedby" style="width:50%">
				   <option value="0">' . $_lang['DM_adjust_authors_noselection'] . '</option>';

	$output .= $userOptions;

	$output .= '</select></form>';

	return $output;
}

/**
 * showOther - shows the main 'Other Properties' form for the tabbed interface
 * 
 */
function showOther() {
	global $_lang;

	$output .= '<br /><h3>' . $_lang['DM_other_header'] . '</h3><br />
					<p>' . $_lang['DM_misc_desc'] . '</p><br />
					<form style="margin-left:50px;" name="other" method="post" action="">
						  <input type="hidden" name="option1" value="' . $_lang['DM_other_publish_radio1'] . '" />
						  <input type="hidden" name="option2" value="' . $_lang['DM_other_publish_radio2'] . '" />
						  <input type="hidden" name="option3" value="' . $_lang['DM_other_show_radio1'] . '" />
						  <input type="hidden" name="option4" value="' . $_lang['DM_other_show_radio2'] . '" />
						  <input type="hidden" name="option5" value="' . $_lang['DM_other_search_radio1'] . '" />
						  <input type="hidden" name="option6" value="' . $_lang['DM_other_search_radio2'] . '" />
						  <input type="hidden" name="option7" value="' . $_lang['DM_other_cache_radio1'] . '" />
						  <input type="hidden" name="option8" value="' . $_lang['DM_other_cache_radio2'] . '" />
						  <input type="hidden" name="option9" value="' . $_lang['DM_other_richtext_radio1'] . '" />
						  <input type="hidden" name="option10" value="' . $_lang['DM_other_richtext_radio2'] . '" />
						  <input type="hidden" name="option11" value="' . $_lang['DM_other_delete_radio1'] . '" />
						  <input type="hidden" name="option12" value="' . $_lang['DM_other_delete_radio2'] . '" />
						  <label for="misc" id="misc_label">' . $_lang['DM_misc_label'] . '</label>	
						  <select name="misc" onchange="changeOtherLabels();">
							<option value="1">' . $_lang['DM_other_dropdown_publish'] . '</option>
							<option value="2">' . $_lang['DM_other_dropdown_show'] . '</option>
							<option value="3">' . $_lang['DM_other_dropdown_search'] . '</option>
							<option value="4">' . $_lang['DM_other_dropdown_cache'] . '</option>
							<option value="5">' . $_lang['DM_other_dropdown_richtext'] . '</option>
							<option value="6">' . $_lang['DM_other_dropdown_delete'] . '</option>
							<option value="0">&nbsp;-</option>
						  </select>
						  <br /><br />
						  <input type="radio" id="choice" name="choice" value = "1" />&nbsp;<label for="choice" id="choice_label_1">' . $_lang['DM_other_publish_radio1'] . '</label>
						  <input type="radio" id="choice" name="choice" value = "0" />&nbsp;<label for="choice" id="choice_label_2">' . $_lang['DM_other_publish_radio2'] . '</label>
						  </form>
						  	';

	return $output;

}

/**
 * showInteraction - shows the 'Range/Treeview' form for module
 * 
 */
function showInteraction() {
	global $_lang;
	global $theme;

	//-- initiate desired interaction method 
	if (isset ($_POST['tswitch'])) {
		$output .= '<div id="interaction">
											<div class="sectionHeader"><img src=\'media/style' . $theme . '/images/misc/dot.gif\' alt="." />&nbsp;' . $_lang['DM_tree_title'] . '</div> 
											<div class="sectionBody"> 
											<form name="module" method="post"> 
											<input type="hidden" name="opcode" value="tree" /> 
											<input type="hidden" name="pids" value="" />
											<input type="hidden" name="setoption" value="" />  
											<input type="hidden" name="newvalue" value="" />
											<input type="hidden" name="date_pubdate" value="" />
											<input type="hidden" name="date_unpubdate" value="" />
											<input type="hidden" name="date_createdon" value="" />
											<input type="hidden" name="date_editedon" value="" />
											<input type="hidden" name="author_createdby" value="" />
											<input type="hidden" name="author_editedby" value="" />
											<input type="hidden" name="tabAction" value="" /> 
											<input type="submit" name="submit" onclick="postForm(\'tree\');return false;" value="' . $_lang['DM_select_submit'] . '" /><br /><br />';

		$output .= getDocTree();
		$output .= '				</form><br />
											<form name="switch" method="post"> 
											<input type="submit" style="" name="rswitch" value="' . $_lang['DM_select_range'] . '" /> 
											<input type="hidden" id="selectedTV" name="selectedTV" value="" />
											</form> 
											<div style="clear:both;"></div> 
											</div></div>';

	} else {
		$output .= '<div id="interaction">
											<div class="sectionHeader"><img src=\'media/style' . $theme . '/images/misc/dot.gif\' alt="." />&nbsp;' . $_lang['DM_range_title'] . '</div> 
											<div class="sectionBody"> 
											<form id="range" name="range" method="post"> 
											<input type="hidden" name="opcode" value="range" /> 
											<input type="hidden" name="newvalue" value="" />
											<input type="hidden" name="setoption" value="" />
											<input type="hidden" name="date_pubdate" value="" />
											<input type="hidden" name="date_unpubdate" value="" />
											<input type="hidden" name="date_createdon" value="" />
											<input type="hidden" name="date_editedon" value="" />
											<input type="hidden" name="author_createdby" value="" />
											<input type="hidden" name="author_editedby" value="" />
											<input type="hidden" name="tabAction" value ="" /> 
											<input name="pids" type="text" size="100%" /> 
											<input type="submit" name="submit" onclick="postForm(\'range\');return false;" value="' . $_lang['DM_select_submit'] . '" /> 
											</form><br /> 
											';
		$output .= $_lang['DM_select_range_text'];

		$output .= '	<br /><form name="switch" method="post"> 
											<input type="submit" style="" name="tswitch" value="' . $_lang['DM_select_tree'] . '" /> 
											<input type="hidden" id="selectedTV" name="selectedTV" value="" />
											</form> 
										    <div style="clear:both;"></div> 
											</div></div>';
	}

	return $output;
}

/**
 * getDocTree - encapsulates a modified MakeMap function to display the document tree
 * 
 */
function getDocTree() {
	global $modx;
	global $table;
	global $theme;

	$subdiv = true;

	// $siteMapRoot [int] 
	$siteMapRoot = 0;

	// $removeNewLines [ true | false ] 
	$removeNewLines = (!isset ($removeNewLines)) ? false : ($removeNewLines == true);
	// $maxLevels [ int ] 
	$maxLevels = 0;
	// $textOfLinks [ string ] 
	$textOfLinks = (!isset ($textOfLinks)) ? 'menutitle' : "$textOfLinks";
	// $titleOfLinks [ string ] 
	$titleOfLinks = (!isset ($titleOfLinks)) ? 'description' : "$titleOfLinks";
	// $pre [ string ] 
	$pre = (!isset ($pre)) ? '' : "$pre";
	// $post [ string ] 
	$post = (!isset ($post)) ? '' : "$post";
	// $selfAsLink [ true | false ] 
	$selfAsLink = (!isset ($selfAsLink)) ? false : ($selfAsLink == true);
	// $hereClass [ string ] 
	$hereClass = (!isset ($hereClass)) ? 'here' : $hereClass;
	// $topdiv [ true | false ] 
	// Indicates if the top level UL is wrapped by a containing DIV block 
	$topdiv = (!isset ($topdiv)) ? false : ($topdiv == true);
	// $topdivClass [ string ] 
	$topdivClass = (!isset ($topdivClass)) ? 'topdiv' : "$topdivClass";
	// $topnavClass [ string ] 
	$topnavClass = (!isset ($topnavClass)) ? 'topnav' : "$topnavClass";

	// $useCategoryFolders [ true | false ] 
	// If you want folders without any content to render without a link to be used
	// as "category" pages (defaults to true). In order to use Category Folders,  
	// the template must be set to (blank) or it won't work properly. 
	$useCategoryFolders = (!isset ($useCategoryFolders)) ? true : "$useCategoryFolders";
	// $categoryClass [ string ] 
	// CSS Class for folders with no content (e.g., category folders) 
	$categoryClass = (!isset ($categoryClass)) ? 'category' : "$categoryClass";
	// $subdiv [ true | false ] 
	$subdiv = (!isset ($subdiv)) ? false : ($subdiv == true);

	// $subdivClass [ string ] 
	$subdivClass = (!isset ($subdivClass)) ? 'subdiv' : "$subdivClass";

	// $orderBy [ string ] 
	$orderBy = (!isset ($orderBy)) ? 'menuindex' : "$orderBy";

	// $orderDesc [true | false] 
	$orderDesc = (!isset ($orderDesc)) ? false : ($orderDesc == true);

	// ########################################### 
	// End config, the rest takes care of itself # 
	// ########################################### 

	$debugMode = false;

	// Initialize 
	$MakeMap = "";
	$siteMapRoot = (isset ($startDoc)) ? $startDoc : $siteMapRoot;
	$maxLevels = (isset ($levelLimit)) ? $levelLimit : $maxLevels;
	$ie = ($removeNewLines) ? '' : "\n";
	//Added by Remon: (undefined variables php notice) 
	$activeLinkIDs = array ();
	$subnavClass = '';

	//display expand/collapse exclusion for top level 
	$startRoot = $siteMapRoot;

	// Overcome single use limitation on functions 
	global $MakeMap_Defined;

	if (!isset ($MakeMap_Defined)) {
		function filterHidden($var) {
			return (!$var['hidemenu'] == 1);
		}
		function filterEmpty($var) {
			return (!empty ($var));
		}
		function MakeMap($modx, $listParent, $listLevel, $description, $titleOfLinks, $maxLevels, $inside, $pre, $post, $selfAsLink, $ie, $activeLinkIDs, $topdiv, $topdivClass, $topnavClass, $subdiv, $subdivClass, $subnavClass, $hereClass, $useCategoryFolders, $categoryClass, $showDescription, $descriptionField, $textOfLinks, $orderBy, $orderDesc, $debugMode) {
			global $theme;

			//-- get ALL children 
			$table = $modx->getFullTableName('site_content');
			$csql = $modx->db->select('*', $table, 'parent="' . $listParent . '"');
			$children = array ();
			for ($i = 0; $i < @ $modx->db->getRecordCount($csql); $i++) {
				array_push($children, @ $modx->db->getRow($csql));
			}

			$numChildren = count($children);

			if (is_array($children) && !empty ($children)) {

				// determine if it's a top category or not 
				$toplevel = !$inside;

				// build the output 
				$topdivcls = (!empty ($topdivClass)) ? ' class="' . $topdivClass . '"' : '';
				$topdivblk = ($topdiv) ? "<div$topdivcls id=\"$listParent\">" : '';
				$topnavcls = (!empty ($topnavClass)) ? ' class="' . $topnavClass . '"' : '';
				$subdivcls = (!empty ($subdivClass)) ? ' class="' . $subdivClass . '"' : '';
				$subdivblk = ($subdiv) ? "<div$subdivcls id=\"$listParent\">$ie" : '';
				$subnavcls = (!empty ($subnavClass)) ? ' class="' . $subnavClass . '"' : '';
				//-- output the div and add the expand/collapse if required 
				$output .= ($toplevel) ? "$topdivblk<ul$topnavcls>$ie" : "$ie" .
				 (($listParent != $startRoot) ? '' : '') . "$subdivblk<ul$subnavcls>$ie";

				//loop through and process subchildren 
				foreach ($children as $child) {

					// get highlight colour 
					if ($child['deleted'] == 1) {
						$color = '#000'; //black 
					}
					elseif ($child['hidemenu'] == 1) {
						$color = '#ff9933'; //orange 
					}
					elseif ($child['published'] == 0) {
						$color = '#ff6600'; //red 
					} else {
						$color = '#339900'; //green 
					}

					// figure out if it's a containing category folder or not  
					$numChildren--;
					$isFolder = $child['isfolder'];
					$itsEmpty = ($isFolder && ($child['template'] == '0'));
					$itm = "";

					// if menutitle is blank fall back to pagetitle for menu link 
					$textOfLinks = (empty ($child['menutitle'])) ? 'pagetitle' : "$textOfLinks";

					// If at the top level 
					if (!$inside) {
						$itm .= ((!$selfAsLink && ($child['id'] == $modx->documentIdentifier)) || ($itsEmpty && $useCategoryFolders)) ? $pre . $child[$textOfLinks] . $post .
						 (($debugMode) ? ' self|cat' : '') : $pre . $child[$textOfLinks] . $post;
						$itm .= ($debugMode) ? ' top' : '';
					}

					// it's a folder and it's below the top level 
					elseif ($isFolder && $inside) {
						$itm .= "<img src='media/style" . $theme . "/images/tree/folder.gif' alt='Folder' onclick=\"switchMenu(" . $child['id'] . ")\" />" .
						"&nbsp;<input type=\"checkbox\" class=\"pids\" id=\"check" . $child['id'] . "\" name=\"check\" value=\"" .
						$child['id'] . "\" />" . $pre . '<span class="document" style="color:' .
						$color . ';">&nbsp;&nbsp;' . $child[$textOfLinks] . ' (Template:' . $child['template'] . ')</span>' . $post .
						 (($debugMode) ? ' subfolder F' : '');
					}

					// it's a document inside a folder 
					else {
						$itm .= ($child['alias'] > '0' && !$selfAsLink && ($child['id'] == $modx->documentIdentifier)) ? $child[$textOfLinks] : "<img src='media/style" . $theme . "/images/tree/page-blank.gif' alt='Page' />&nbsp;<input type=\"checkbox\" class=\"pids\" id=\"check" . $child['id'] . "\" name=\"check\" value=\"" .
						$child['id'] . "\" />" . '<span style="color:' . $color . ';">&nbsp;&nbsp;' .
						$child[$textOfLinks] . ' (Template:' . $child['template'] . ')</span>';
						$itm .= ($debugMode) ? ' doc' : '';
					}
					$itm .= ($debugMode) ? "$useCategoryFolders $isFolder $itsEmpty" : '';

					// loop back through if the doc is a folder and has not reached the max levels 
					if ($isFolder && (($maxLevels == 0) || ($maxLevels > $listLevel +1))) {
						$itm .= MakeMap($modx, $child['id'], $listLevel +1, $description, $titleOfLinks, $maxLevels, true, $pre, $post, $selfAsLink, $ie, $activeLinkIDs, $topdiv, $topdivClass, $topnavClass, $subdiv, $subdivClass, $subnavClass, $hereClass, $useCategoryFolders, $categoryClass, false, '', $textOfLinks, $orderBy, $orderDesc, $debugMode);
					}

					if ($itm) {
						$output .= "<li$class>$itm</li>$ie";
						$class = '';
					}
				}
				$output .= "</ul>$ie";
				$output .= ($toplevel) ? (($topdiv) ? "</div>$ie" : "") : (($subdiv) ? "</div>$ie" : "");
			}
			return $output;
		}
		$MakeMap_Defined = true;
	}

	// return the output 
	return MakeMap($modx, $siteMapRoot, 0, false, $titleOfLinks, $maxLevels, true, $pre, $post, $selfAsLink, $ie, $activeLinkIDs, $topdiv, $topdivClass, $topnavClass, $subdiv, $subdivClass, $subnavClass, $hereClass, $useCategoryFolders, $categoryClass, false, '', $textOfLinks, $orderBy, $orderDesc, $debugMode);
}

/**
 * updateHeader - contains the common Update header html used in the module
 * 
 */
function updateHeader() {
	global $theme;
	global $siteURL;
	global $_lang;

	$output = ' <html><head>
							<link rel="stylesheet" type="text/css" href="media/style' . $theme . '/style.css" /> 
							<link rel="stylesheet" type="text/css" href="media/style' . $theme . '/coolButtons2.css" /> 
							<style type="text/css"> 
							.topdiv {border:0;} 
							.subdiv {border:0;} 
							ul, li {list-style:none;} 
							</style> 
							<script type="text/javascript" language="JavaScript" src="media/script/modx.js"></script>
							<script type="text/javascript" language="JavaScript" src="media/script/cb2.js"></script>';
	$output .= ButtonCSS();
	$output .= '		</head><body> 
					        <div class="subTitle" id="bttn"> 
							<span class="right"><img src="media/style' . $theme . '/images/_tx_.gif" width="1" height="5"><br />' . $_lang['DM_module_title'] . '</span> 
							<div class="bttnheight"><a id="Button5" onclick="document.location.href=\'index.php?a=106\';">
								<img src="media/style' . $theme . '/images/icons/close.gif"> ' . $_lang['DM_close'] . '</a>
							</div><div class="stay">   </div> 
				            </div>
							<div class="sectionHeader"><img src=\'media/style' . $theme . '/images/misc/dot.gif\' alt="." />&nbsp;' . $_lang['DM_update_title'] . '</div> 
							<div class="sectionBody"> 
						    ';

	return $output;

}
?>
