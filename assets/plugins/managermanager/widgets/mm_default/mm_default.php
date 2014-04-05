<?php
/**
 * mm_default
 * @version 1.1.1 (2014-02-13)
 * 
 * @desc A widget for ManagerManager plugin that allows field (or TV) default value for a new document/folder to be set.
 * 
 * @uses ManagerManager plugin 0.4.
 * 
 * @param $field {string} - The name of the document field (or TV) this should apply to. @required
 * @param $value {string} - The default value for the field. The current date/time will be used for the fields equals 'pub_date' or 'unpup_date' with empty value. A static value can be supplied as a string, or PHP code (to calculate something) can be supplied if the eval parameter is set as true. Default: ''.
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * @param $eval {bollean} - Should the value be evaluated as PHP code? Default: false.
 * 
 * @event OnDocFormRender
 * 
 * @link http://code.divandesign.biz/modx/mm_default/1.1.1
 * 
 * @copyright 2014
 */

function mm_default($field, $value = '', $roles = '', $templates = '', $eval = false){
	global $modx;
	$e = &$modx->Event;
	
	// if we aren't creating a new document or folder, we don't want to do this
	// Which action IDs so we want to do this for?
	// 85 =
	// 4 =
	// 72 = Create new weblink
	
	$allowed_actions = array('85', '4', '72');
	
	if (!in_array($modx->manager->action, $allowed_actions)){
		return;
	}
	
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		// What's the new value, and does it include PHP?
		$new_value = ($eval) ? eval($value) : $value;
		
		$output = "//---------- mm_default :: Begin -----\n";
		
		// Work out the correct date time format based on the config setting
		switch($modx->config['datetime_format']){
			case 'dd-mm-YYYY':
				$date_format = 'd-m-Y';
			break;
			case 'mm/dd/YYYY':
				$date_format = 'm-d-Y';
			break;
			case 'YYYY/mm/dd':
				$date_format = 'Y-m-d';
			break;
		}
		
		switch ($field){
			case 'pub_date':
				$new_value = ($new_value == '') ? date("$date_format H:i:s") : $new_value;
				$output .= '$j("input[name=\'pub_date\']").val("'.jsSafe($new_value).'");'."\n";
			break;
			
			case 'unpub_date':
				$new_value = ($new_value == '') ? date("$date_format H:i:s") : $new_value;
				$output .= '$j("input[name=\'unpub_date\']").val("'.jsSafe($new_value).'");'."\n";
			break;
			
			case 'published':
				$new_value = ($value) ? '1' : '0';
				$output .= '$j("input[name=\'published\']").val("'.$new_value.'");'."\n";
				
				if ($value){
					$output .= '$j("input[name=\'publishedcheck\']").attr("checked", "checked");'."\n";
				}else{
					$output .= '$j("input[name=\'publishedcheck\']").removeAttr("checked");'."\n";
				}
			break;
			
			case 'hide_menu':
				$new_value = ($value) ? '1' : '0';
				$output .= '$j("input[name=\'hidemenu\']").val("'.$new_value.'");'."\n";
				
				if (!$value){
					$output .= '$j("input[name=\'hidemenucheck\']").attr("checked", "checked");'."\n";
				}else{
					$output .= '$j("input[name=\'hidemenucheck\']").removeAttr("checked");'."\n";
				}
			break;
			
			case 'show_in_menu':
				//Note these are reversed from what you'd think
				$new_value = ($value) ? '0' : '1';
				$output .= '$j("input[name=\'hidemenu\']").val("'.$new_value.'");'."\n";
				
				if ($value){
					$output .= '$j("input[name=\'hidemenucheck\']").attr("checked", "checked");'."\n";
				}else{
					$output .= '$j("input[name=\'hidemenucheck\']").removeAttr("checked");'."\n";
				}
			break;
			
			case 'searchable':
				$new_value = ($value) ? '1' : '0';
				$output .= '$j("input[name=\'searchable\']").val("'.$new_value.'");'."\n";
				
				if ($value){
					$output .= '$j("input[name=\'searchablecheck\']").attr("checked", "checked");'."\n";
				}else{
					$output .= '$j("input[name=\'searchablecheck\']").removeAttr("checked");'."\n";
				}
			break;
			
			case 'cacheable':
				$new_value = ($value) ? '1' : '0';
				$output .= '$j("input[name=\'cacheable\']").val("'.$new_value.'");'."\n";
				
				if ($value){
					$output .= '$j("input[name=\'cacheablecheck\']").attr("checked", "checked");'."\n";
				}else{
					$output .= '$j("input[name=\'cacheablecheck\']").removeAttr("checked");'."\n";
				}
			break;
			
			case 'clear_cache':
				$new_value = ($value) ? '1' : '0';
				$output .= '$j("input[name=\'syncsite\']").val("'.$new_value.'");'."\n";
				
				if ($value){
					$output .= '$j("input[name=\'syncsitecheck\']").attr("checked", "checked");'."\n";
				}else{
					$output .= '$j("input[name=\'syncsitecheck\']").removeAttr("checked");'."\n";
				}
			break;
			
			case 'container':
			case 'is_folder':
				$new_value = ($value) ? '1' : '0';
				$output .= '$j("input[name=\'isfolder\']").val("'.$new_value.'");'."\n";
				
				if ($value){
					$output .= '$j("input[name=\'isfoldercheck\']").attr("checked", "checked");'."\n";
				}else{
					$output .= '$j("input[name=\'isfoldercheck\']").removeAttr("checked");'."\n";
				}
			break;
			
			case 'alias_visible':
				$new_value = ($value) ? '1' : '0';
				$output .= '$j("input[name=\'alias_visible\']").val("'.$new_value.'");'."\n";
				
				if ($value){
					$output .= '$j("input[name=\'alias_visible_check\']").attr("checked", "checked");'."\n";
				} else {
					$output .= '$j("input[name=\'alias_visible_check\']").removeAttr("checked");'."\n";
				}
			break;
			
			case 'is_richtext':
			case 'richtext':
				$new_value = ($value) ? '1' : '0';
				$output .= 'var originalRichtextValue = $j("#which_editor:first").val();'."\n";
				$output .= '$j("input[name=\'richtext\']").val("'.$new_value.'");'."\n";
				
				if ($value){
					$output .= '$j("input[name=\'richtextcheck\']").attr("checked", "checked");'."\n";
				}else{
					$output .= '
					$j("input[name=\'richtextcheck\']").removeAttr("checked");
					// Make the RTE displayed match the default value that has been set here
					if (originalRichtextValue != "none"){
						$j("#which_editor").val("none");
						changeRTE();
					}
					
					';
					
					$output .= ''."\n";
				}
			break;
			
			case 'log':
				//Note these are reversed from what you'd think
				$new_value = ($value) ? '0' : '1';
				$output .= '$j("input[name=\'donthit\']").val("'.$new_value.'");'."\n";
				
				if ($value){
					$output .= '$j("input[name=\'donthitcheck\']").attr("checked", "checked");'."\n";
				}else{
					$output .= '$j("input[name=\'donthitcheck\']").removeAttr("checked");'."\n";
				}
			break;
			
			case 'content_type':
				$output .= '$j("select[name=\'contentType\']").val("'.$new_value.'");'."\n";
			break;
			
			default:
				$output .= '$j("*[name=\''.$field.'\']").val("'.$new_value.'");'."\n";
			break;
		}
		
		$output .= "//---------- mm_default :: End -----\n";
		
		$e->output($output);
	}
}
?>