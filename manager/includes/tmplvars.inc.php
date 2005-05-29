<?php

	// Modified by Raymond for use with Template Variables

	//added by  Apodigm - DocVars - web@apodigm.com
	//note... this was lifted from Open-Realty, which is released under GNU GPL.
	//I have modified it substantially to fit the requirments for Etomite.

	// DISPLAY FORM ELEMENTS

	function renderFormElement($field_type, $field_name, $default_text, $field_elements, $field_value, $field_style='') {
		global $settings;

		$field_html ='';
		$field_value = ($field_value!="" ? $field_value : $default_text);

		switch ($field_type) {

			case "text": // handler for regular text boxes
			case "email": // handles email input fields
			case "number": // handles the input of numbers
				$field_html .=  '<input type="text" name="tv'.$field_name.'" value="'.htmlspecialchars($field_value).'" '.$field_style.' tvtype="'.$field_type.'" onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');" style="width:100%" />';
				break;
			case "textareamini": // handler for textarea mini boxes
				$field_html .=  '<textarea id="tv'.$field_name.'" name="tv'.$field_name.'" cols="40" rows="5" onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');" style="width:100%">' . htmlspecialchars($field_value) .'</textarea>';
				break;
			case "textarea": // handler for textarea boxes
			case "htmlarea": // handler for textarea boxes
				$field_html .=  '<textarea id="tv'.$field_name.'" name="tv'.$field_name.'" cols="40" rows="15" onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');" style="width:100%;">' . htmlspecialchars($field_value) .'</textarea>';
				break;
			case "date":
                if($field_value=='') $field_value=0;
                $cal = 'cal' . $field_name;

				$field_html .=  '<input name="tv'.$field_name.'" type="hidden" value="' . ($field_value==0 || !isset($field_value) ? "" : $field_value) . '" onBlur="documentDirty=true;setVariableModified(\''.$field_name.'\');">';

				$field_html .=  '	<table width="250" border="0" cellspacing="0" cellpadding="0">';
				$field_html .=  '	  <tr>';
				$field_html .=  '		<td width="160" style="border: 1px solid #808080;"><span id="tv'.$field_name.'_show" class="inputBox"> ' . ($field_value==0 || !isset($field_value) ? '(not set)' : $field_value) . '</span> </td>';

				$field_html .=  '		<td>&nbsp;';
				$field_html .=  '			<a onClick="documentDirty=false; '.$cal.'.popup();" onMouseover="window.status=\'Select a date\'; return true;" onMouseout="window.status=\'\'; return true;" style="cursor:pointer; cursor:hand"><img src="media/images/icons/cal.gif" width="16" height="16" border="0"></a>';
				$field_html .=  '			<a onClick="document.getElementById(\'tv'.$field_name.'_show\').innerHTML=\'(not set)\'; return true;" onMouseover="window.status=\'clear the date\'; return true;" onMouseout="window.status=\'\'; return true;" style="cursor:pointer; cursor:hand"><img src="media/images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="No date"></a>';

				$field_html .=  '		</td>';
				$field_html .=  '	  </tr>';
				$field_html .=  '    </table>';

				$field_html .=  '<script type="text/javascript">';
				$field_html .=  '	var '.$cal.' = new calendar1(document.forms[\'mutate\'].elements[\'tv'.$field_name.'\'], document.getElementById("tv'.$field_name.'_show"));';
				$field_html .=  '   '.$cal.'.path="' . str_replace("index.php", "media/", $_SERVER["PHP_SELF"]) . '";';

				$field_html .=  '	'.$cal.'.year_scroll = true;';
				$field_html .=  '   '.$cal.'.time_comp = true;';

				$field_html .=  '</script>';

				break;
			case "dropdown": // handler for select boxes
				$field_html .=  '<select name="tv'.$field_name.'" size="1" onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');">';
				$index_list = ParseIntputOptions(ProcessTVCommand("",$field_elements));
				while (list($item, $itemvalue) = each ($index_list))
				{
					list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
					if (strlen($itemvalue)==0) $itemvalue = $item;
					$field_html .=  '<option value="'.htmlspecialchars($itemvalue).'"'.($itemvalue==$field_value ?' selected="selected"':'').'>'.htmlspecialchars($item).'</option>';
				}
				$field_html .=  "</select>";
				break;
			case "listbox": // handler for select boxes
				$field_html .=  '<select name="tv'.$field_name.'" onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');" size="8">';	
				$index_list = ParseIntputOptions(ProcessTVCommand("",$field_elements));
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
				$field_html .=  '<select name="tv'.$field_name.'[]" multiple="multiple" onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');" size="8">';
				$index_list = ParseIntputOptions(ProcessTVCommand("",$field_elements));
				while (list($item, $itemvalue) = each ($index_list))
				{
					list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
					if (strlen($itemvalue)==0) $itemvalue = $item;
					$field_html .=  '<option value="'.htmlspecialchars($itemvalue).'"'.(in_array($itemvalue,$field_value) ?' selected="selected"':'').'>'.htmlspecialchars($item).'</option>';
				}
				$field_html .=  "</select>";
				break;
			case "url": // handles url input fields
				$urls= array("--","http://","ftp://");
				$field_html ="<table border='0' cellspacing='0' cellpadding='0'><tr><td><select name='tv".$field_name."_prefix' onchange='documentDirty=true;setVariableModified(\"".$field_name."\");'>";
				foreach($urls as $k => $v){
					if(strpos($field_value,$v)===false) $field_html.="<option value='$v'>$v</option>";
					else{
						$field_value = str_replace($v,"",$field_value);
						$field_html.="<option value='$v' selected='selected'>$v</option>";
					}
				}
				$field_html .="</select></td><td>";
				$field_html .=  '<input type="text" name="tv'.$field_name.'" value="'.htmlspecialchars($field_value).'" width="100" '.$field_style.' onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');" /></td></tr></table>';
				break;
			case "checkbox": // handles check boxes
				$field_value = explode("||",$field_value);
				$index_list = ParseIntputOptions(ProcessTVCommand("",$field_elements));
				while (list($item, $itemvalue) = each ($index_list))
				{
					list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
					if (strlen($itemvalue)==0) $itemvalue = $item;
					$field_html .=  '<input type="checkbox" value="'.htmlspecialchars($itemvalue).'" name="tv'.$field_name.'[]" '. (in_array($itemvalue,$field_value)?" checked='checked'":"").' onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');" />'.$item.'<br />';
				}
				break;
			case "option": // handles radio buttons
				$index_list = ParseIntputOptions(ProcessTVCommand("",$field_elements));
				while (list($item, $itemvalue) = each ($index_list))
				{
					list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
					if (strlen($itemvalue)==0) $itemvalue = $item;
					$field_html .=  '<input type="radio" value="'.htmlspecialchars($itemvalue).'" name="tv'.$field_name.'" '.($itemvalue==$field_value ?'checked="checked"':'').' onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');" />'.$item.'<br />';
				}
				break;
			case "image":	// handles image fields using htmlarea image manager
				//TODO: change to use TinyMCE image manager plugin
				global $_lang;
				global $ImageOutParam;
				global $ImageManagerLoaded;
				global $content,$use_editor,$which_editor;
				if (!$ImageManagerLoaded && !(($content['richtext']==1 || $_GET['a']==4) && $use_editor==1 && $which_editor==3)){ 
					$field_html .='<script type="text/javascript">';
					$field_html .='	_editor_lang = "en";';
					$field_html .='	_editor_url = "media/editor/";';
					$field_html .='</script> ';
					$field_html .='<script type="text/javascript" src="media/editor/editor.js"></script>';
					$field_html .='<script type="text/javascript">';
					$field_html .='	HTMLArea.loadPlugin("ImageManager"); ';
					$field_html .='</script>';
					$ImageManagerLoaded  = true;					
				} 
				if(!$ImageOutParam) {
					$field_html .='<script type="text/javascript">';
					$field_html .='var ImageOutParam = {';
					$field_html .='	f_url    : "",';
					$field_html .='	f_alt    : "",';
					$field_html .='	f_border : "",';
					$field_html .='	f_align  : "",';
					$field_html .='	f_vert   : "",';
					$field_html .='	f_horiz  : "",';
					$field_html .='	f_width  : "",';
					$field_html .='	f_height : ""';
					$field_html .='};';
					$field_html .='</script>	';
					$ImageOutParam = true;
				}
				$field_html .='<input type="text" name="tv'.$field_name.'"  value="'.$field_value .'" '.$field_style.' onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');" />&nbsp;<input type="button" value="'.$_lang['insert'].'" onclick="setVariableModified(\''.$field_name.'\');ImageOutParam.f_url=document.mutate[\'tv'.$field_name.'\'].value;Dialog(_editor_url + \'plugins/ImageManager/manager.php\', function(p){document.mutate[\'tv'.$field_name.'\'].value=p.f_url},ImageOutParam)" />';
				break;
			case "file": // handles the input of file uploads
				$field_html .=  '<input type="file" name="tv'.$field_name.'"  '.$field_style.' onclick="documentDirty=true;setVariableModified(\''.$field_name.'\');" /><br>';
                if($field_value!='') $field_html .=  '&nbsp;Current File: <a href="'.$settings['im_plugin_base_url'].$field_value.'" target="_new">'. $field_value . '</a><input type="hidden" name="tv'.$field_name.'_previous" value="'.$field_value.'" /><br /><input type="checkbox" name="tv'.$field_name.'_clear" onclick="documentDirty=true;setVariableModified(\''.$field_name.'\');" /> Clear link to doc &nbsp;&nbsp;<input type="checkbox" name="tv'.$field_name.'_delete" onclick="documentDirty=true;setVariableModified(\''.$field_name.'\');" /> Delete File'; 
				break;
			default: // the default handler -- for errors, mostly
				$field_html .=  '<input type="text" name="tv'.$field_name.'" value="'.htmlspecialchars($field_value).'" '.$field_style.' onchange="documentDirty=true;setVariableModified(\''.$field_name.'\');" />';

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