<?php
/**
 * Name: TV Ajax Handler
 * For: Doc Manager Module
 * Author: Garry Nutting
 * Date: 29/09/2006 Version: 1.6
 * 
 * This file includes slightly modified code from the MODx core distribution.
 * 
 */

 require_once '../../../../manager/includes/protect.inc.php'; 
 include_once ("../../../../manager/includes/config.inc.php");
 include_once(MODX_BASE_PATH."manager/includes/document.parser.class.inc.php");
 $modx = new DocumentParser;
 
 $output = '';
 
 if(isset($_POST['tplID']) && is_numeric($_POST['tplID'])) {
 	//-- grab the template variables
 	$sql = "SELECT * FROM ".$modx->getFullTableName('site_tmplvars')." tv LEFT JOIN ".$modx->getFullTableName('site_tmplvar_templates')." ON tv.id = ".$modx->getFullTableName('site_tmplvar_templates').".tmplvarid WHERE ".$modx->getFullTableName('site_tmplvar_templates').".templateid ='". $_POST['tplID']."'";
 	$rs = $modx->db->query($sql);
 	$limit = $modx->db->getRecordCount($rs);
 	
 	if ($limit > 0) {
 		//-- include the tv files
		require(MODX_BASE_PATH.'/manager/includes/tmplvars.commands.inc.php');
		$output.= "<table style='position:relative' border='0' cellspacing='0' cellpadding='3' width='96%'>";
		
		//-- render TV display
		for ($i=0; $i<$limit; $i++) {
 			$row = $modx->db->getRow($rs);
				//-- splitter
				if($i>0 && $i<$limit) $output .= '<tr><td colspan="2"><div class="split"></div></td></tr>';
				
				$output.='<tr style="height: 24px;">
				<td align="left" valign="top" width="150">
					<span class=\'warning\'>'.$row['caption'].'</span><br /><span class=\'comment\'>'.$row['description'].'</span>
				</td>
				<td valign="top" style="position:relative">';
				
				$base_url = 'http://localhost/garryn/';
				$output.= renderFormElement($row['type'], $row['name'], $row['default_text'], $row['elements'], $row['value'], ' style="width:300px;"');
				$output.= '</td></tr>';
		}
		$output.='</table>';
		$output.= '<br />'.stripslashes($_POST['langIgnoreTV']).' <input type="text" id="ignoreTV" name="ignoreTV" size="50" value="" />';
 	} else {
 		print stripslashes($_POST['langNoTV']);
 	}
 	
 	print $output;
 } else {
 	print '';
 }
 
 
	// Modified by Raymond for use with Template Variables

	//added by  Apodigm - DocVars - web@apodigm.com
	//note... this was lifted from Open-Realty, which is released under GNU GPL.
	//I have modified it substantially to fit the requirments for Etomite.

	// DISPLAY FORM ELEMENTS

	function renderFormElement($field_type, $field_name, $default_text, $field_elements, $field_value, $field_style='') {
		global $base_url;
		global $rb_base_url;

		$field_html ='';
		$field_value = ($field_value!="" ? $field_value : $default_text);

		switch ($field_type) {

			case "text": // handler for regular text boxes
			case "rawtext"; // non-htmlentity converted text boxes
			case "email": // handles email input fields
			case "number": // handles the input of numbers
				$field_html .=  '<input type="text" id="tv'.$field_name.'" name="tv'.$field_name.'" value="'.htmlspecialchars($field_value).'" '.$field_style.' tvtype="'.$field_type.'" style="width:100%" />';
				break;
			case "textareamini": // handler for textarea mini boxes
				$field_html .=  '<textarea id="tv'.$field_name.'" name="tv'.$field_name.'" cols="40" rows="5" style="width:100%">' . htmlspecialchars($field_value) .'</textarea>';
				break;
			case "textarea": // handler for textarea boxes
			case "rawtextarea": // non-htmlentity convertex textarea boxes
			case "htmlarea": // handler for textarea boxes (deprecated)
			case "richtext": // handler for textarea boxes
				$field_html .=  '<textarea id="tv'.$field_name.'" name="tv'.$field_name.'" cols="40" rows="15" style="width:100%;">' . htmlspecialchars($field_value) .'</textarea>';
				break;
			case "date":
                if($field_value=='') $field_value=0;
                $cal = 'cal' . $field_name;

				$field_html .=  '<input id="tv'.$field_name.'" name="tv'.$field_name.'" type="hidden" value="' . ($field_value==0 || !isset($field_value) ? "" : $field_value) . '">';

				$field_html .=  '	<table width="250" border="0" cellspacing="0" cellpadding="0">';
				$field_html .=  '	  <tr>';
				$field_html .=  '		<td width="160" style="border: 1px solid #808080;"><span id="tv'.$field_name.'_show" class="inputBox"> ' . ($field_value==0 || !isset($field_value) ? '(not set)' : $field_value) . '</span> </td>';

				$field_html .=  '		<td>&nbsp;';
				$field_html .=  '			<a onClick="'.$cal.'.popup();" style="cursor:pointer; cursor:hand"><img src="'.$base_url.'manager/media/style'.$_POST['theme'].'/images/icons/cal.gif" width="16" height="16" border="0"></a>';
				$field_html .=  '			<a onClick="document.forms[\'templatevariables\'].elements[\'tv'.$field_name.'\'].value=\'\';document.getElementById(\'tv'.$field_name.'_show\').innerHTML=\'(not set)\'; return true;" style="cursor:pointer; cursor:hand"><img src="'.$base_url.'manager/media/style'.$_POST['theme'].'/images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="No date"></a>';

				$field_html .=  '		</td>'; 
				$field_html .=  '	  </tr>';
				$field_html .=  '    </table>';

				$field_html .=  '<script type="text/javascript">';
				$field_html .=  '   '.$cal.' = new calendar1(document.forms[\'templatevariables\'].elements[\'tv'.$field_name.'\'], document.getElementById("tv'.$field_name.'_show"));';
				$field_html .=  '   '.$cal.'.path="' . $base_url .'/manager/media/";';

				$field_html .=  '	'.$cal.'.year_scroll = true;';
				$field_html .=  '   '.$cal.'.time_comp = true;';

				$field_html .=  '</script>';

				break;
			case "dropdown": // handler for select boxes
				$field_html .=  '<select id="tv'.$field_name.'" name="tv'.$field_name.'" size="1">';
				$index_list = ParseIntputOptions(ProcessTVCommand($field_elements, $field_name));
				while (list($item, $itemvalue) = each ($index_list))
				{
					list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
					if (strlen($itemvalue)==0) $itemvalue = $item;
					$field_html .=  '<option value="'.htmlspecialchars($itemvalue).'"'.($itemvalue==$field_value ?' selected="selected"':'').'>'.htmlspecialchars($item).'</option>';
				}
				$field_html .=  "</select>";
				break;
			case "listbox": // handler for select boxes
				$field_html .=  '<select id="tv'.$field_name.'" name="tv'.$field_name.'" size="8">';	
				$index_list = ParseIntputOptions(ProcessTVCommand($field_elements, $field_name));
				while (list($item, $itemvalue) = each ($index_list))
				{
					list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
					if (strlen($itemvalue)==0) $itemvalue = $item;
					$field_html .=  '<option value="'.htmlspecialchars($itemvalue).'"'.($itemvalue==$field_value ?' selected="selected"':'').'>'.htmlspecialchars($item).'</option>';
				}
				$field_html .=  "</select>";
				break;
			case "listbox-multiple": // handler for select boxes where you can choose multiple items
				$field_value = explode("||",$field_value);
				$field_html .=  '<select id="tv'.$field_name.'[]" name="tv'.$field_name.'[]" multiple="multiple" size="8">';
				$index_list = ParseIntputOptions(ProcessTVCommand($field_elements, $field_name));
				while (list($item, $itemvalue) = each ($index_list))
				{
					list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
					if (strlen($itemvalue)==0) $itemvalue = $item;
					$field_html .=  '<option value="'.htmlspecialchars($itemvalue).'"'.(in_array($itemvalue,$field_value) ?' selected="selected"':'').'>'.htmlspecialchars($item).'</option>';
				}
				$field_html .=  "</select>";
				break;
			case "url": // handles url input fields
				$urls= array(''=>'--', 'http://'=>'http://', 'https://'=>'https://', 'ftp://'=>'ftp://', 'mailto:'=>'mailto:');
				$field_html ='<table border="0" cellspacing="0" cellpadding="0"><tr><td><select id="tv'.$field_name.'_prefix" name="tv'.$field_name.'_prefix">';
				foreach($urls as $k => $v){
					if(strpos($field_value,$v)===false) $field_html.='<option value="'.$v.'">'.$k.'</option>';
					else{
						$field_value = str_replace($v,"",$field_value);
						$field_html.='<option value="$v" selected="selected">'.$k.'</option>';
					}
				}
				$field_html .='</select></td><td>';
				$field_html .=  '<input type="text" id="tv'.$field_name.'" name="tv'.$field_name.'" value="'.htmlspecialchars($field_value).'" width="100" '.$field_style.' /></td></tr></table>';
				break;
			case "checkbox": // handles check boxes
				$field_value = explode("||",$field_value);
				$index_list = ParseIntputOptions(ProcessTVCommand($field_elements, $field_name));
				$i=0;
				while (list($item, $itemvalue) = each ($index_list))
				{
					list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
					if (strlen($itemvalue)==0) $itemvalue = $item;
					$field_html .=  '<input type="checkbox" value="'.htmlspecialchars($itemvalue).'" id="tv_'.$i.'" name="tv'.$field_name.'[]" '. (in_array($itemvalue,$field_value)?" checked='checked'":"").' /><label for="tv_'.$i.'">'.$item.'</label><br />';
					$i++;
				}
				break;
			case "option": // handles radio buttons
				$index_list = ParseIntputOptions(ProcessTVCommand($field_elements, $field_name));
				while (list($item, $itemvalue) = each ($index_list))
				{
					list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
					if (strlen($itemvalue)==0) $itemvalue = $item;
					$field_html .=  '<input type="radio" value="'.htmlspecialchars($itemvalue).'" name="tv'.$field_name.'" '.($itemvalue==$field_value ?'checked="checked"':'').' />'.$item.'<br />';
				}
				break;
			case "image":	// handles image fields using htmlarea image manager
				global $_lang;
				global $ResourceManagerLoaded;
				global $content,$use_editor,$which_editor;
				if (!$ResourceManagerLoaded && !(($content['richtext']==1 || $_GET['a']==4) && $use_editor==1 && $which_editor==3)){ 
					$field_html .="
					<script type=\"text/javascript\">
							var lastImageCtrl;
							var lastFileCtrl;
							OpenServerBrowser = function(url, width, height ) {
								var iLeft = (screen.width  - width) / 2 ;
								var iTop  = (screen.height - height) / 2 ;

								var sOptions = 'toolbar=no,status=no,resizable=yes,dependent=yes' ;
								sOptions += ',width=' + width ;
								sOptions += ',height=' + height ;
								sOptions += ',left=' + iLeft ;
								sOptions += ',top=' + iTop ;

								var oWindow = window.open( url, 'FCKBrowseWindow', sOptions ) ;
							};			
							BrowseServer = function(ctrl) {
								lastImageCtrl = ctrl;
								var w = screen.width * 0.7;
								var h = screen.height * 0.7;
								OpenServerBrowser('".$base_url."manager/media/browser/mcpuk/browser.html?Type=images&Connector=".$base_url."manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath=".$base_url."', w, h);
							};
							
							BrowseFileServer = function(ctrl) {
								lastFileCtrl = ctrl;
								var w = screen.width * 0.7;
								var h = screen.height * 0.7;
								OpenServerBrowser('".$base_url."manager/media/browser/mcpuk/browser.html?Type=files&Connector=".$base_url."manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath=".$base_url."', w, h);
							};
							
							SetUrl = function(url, width, height, alt){
								if(lastFileCtrl) {
									var c = document.templatevariables[lastFileCtrl];
									if(c) c.value = url;
									lastFileCtrl = '';
								} else if(lastImageCtrl) {
									var c = document.templatevariables[lastImageCtrl];
									if(c) c.value = url;
									lastImageCtrl = '';
								} else {
									return;
								}

							};
					</script>";
					$ResourceManagerLoaded  = true;					
				} 
				$field_html .='<input type="text" id="tv'.$field_name.'" name="tv'.$field_name.'"  value="'.$field_value .'" '.$field_style.' />&nbsp;<input type="button" value="'.$_POST['langInsert'].'" onclick="BrowseServer(\'tv'.$field_name.'\')" />';
				break;
			case "file": // handles the input of file uploads
			/* Modified by Timon for use with resource browser */
                		global $_lang;
				global $ResourceManagerLoaded;
				global $content,$use_editor,$which_editor;
				if (!$ResourceManagerLoaded && !(($content['richtext']==1 || $_GET['a']==4) && $use_editor==1 && $which_editor==3)){
				/* I didn't understand the meaning of the condition above, so I left it untouched ;-) */ 
					$field_html .="
					<script type=\"text/javascript\">
							var lastFileCtrl;
							var lastImageCtrl;
							OpenServerBrowser = function(url, width, height ) {
								var iLeft = (screen.width  - width) / 2 ;
								var iTop  = (screen.height - height) / 2 ;

								var sOptions = 'toolbar=no,status=no,resizable=yes,dependent=yes' ;
								sOptions += ',width=' + width ;
								sOptions += ',height=' + height ;
								sOptions += ',left=' + iLeft ;
								sOptions += ',top=' + iTop ;

								var oWindow = window.open( url, 'FCKBrowseWindow', sOptions ) ;
							};
							
							BrowseServer = function(ctrl) {
								lastImageCtrl = ctrl;
								var w = screen.width * 0.7;
								var h = screen.height * 0.7;
								OpenServerBrowser('".$base_url."manager/media/browser/mcpuk/browser.html?Type=images&Connector=".$base_url."manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath=".$base_url."', w, h);
							};
							
							BrowseFileServer = function(ctrl) {
								lastFileCtrl = ctrl;
								var w = screen.width * 0.7;
								var h = screen.height * 0.7;
								OpenServerBrowser('".$base_url."manager/media/browser/mcpuk/browser.html?Type=files&Connector=".$base_url."manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath=".$base_url."', w, h);
							};
							
							SetUrl = function(url, width, height, alt){
								if(lastFileCtrl) {
									var c = document.mutate[lastFileCtrl];
									if(c) c.value = url;
									lastFileCtrl = '';
								} else if(lastImageCtrl) {
									var c = document.mutate[lastImageCtrl];
									if(c) c.value = url;
									lastImageCtrl = '';
								} else {
									return;
								}
							}
					</script>";
					$ResourceManagerLoaded  = true;					
				} 
				$field_html .='<input type="text" id="tv'.$field_name.'" name="tv'.$field_name.'"  value="'.$field_value .'" '.$field_style.' />&nbsp;<input type="button" value="'.$_POST['langInsert'].'" onclick="BrowseFileServer(\'tv'.$field_name.'\')" />';
                
				break;
			default: // the default handler -- for errors, mostly
				$field_html .=  '<input type="text" id="tv'.$field_name.'" name="tv'.$field_name.'" value="'.htmlspecialchars($field_value).'" '.$field_style.' />';

		} // end switch statement
		return $field_html;
	} // end renderFormElement function


	function ParseIntputOptions($v) {
		$a = array();
		if(is_array($v)) return $v;
		else if(is_resource($v)) {
			while ($cols = mysql_fetch_row($v)) $a[] = $cols;
		}
		else $a = explode("||", $v);
		return $a;
	}
	
 
?>
