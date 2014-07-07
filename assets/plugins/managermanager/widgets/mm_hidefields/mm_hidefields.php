<?php
/**
 * mm_hideFields
 * @version 1.1.2 (2014-05-07)
 * 
 * @desc A widget for ManagerManager plugin that allows one or more of the default document fields or template variables to be hidden within the manager.
 * 
 * @uses ManagerManager plugin 0.4.
 * 
 * @param $fields {comma separated string} - The name(s) of the document fields (or TVs) this should apply to. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * 
 * @link http://code.divandesign.biz/modx/mm_hidefields/1.1.2
 * 
 * @copyright 2014
 */

function mm_hideFields($fields, $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	// if we've been supplied with a string, convert it into an array
	$fields = makeArray($fields);
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		global $mm_fields;
		
		$output = "//---------- mm_hideFields :: Begin -----\n";
		
		foreach ($fields as $field){
			switch ($field){
				// Exceptions
				case 'keywords':
					$output .= '$j("select[name*=\'keywords\']").parent("td").hide();'."\n";
				break;
				
				case 'metatags':
					$output .= '$j("select[name*\'=metatags\']").parent("td").hide()'."\n";
				break;
				
				case 'hidemenu':
				case 'hide_menu':
				case 'show_in_menu':
					$output .= '$j("input[name=\'hidemenucheck\']").parent("td").hide();'."\n";
				break;
				
				case 'menuindex':
					$output .= '$j("input[name=\'menuindex\']").parents("table").parent("td").prev("td").children("span.warning").hide();'."\n";
					$output .= '$j("input[name=\'menuindex\']").parent("td").hide();'."\n";
				break;
				
				case 'which_editor':
					$output .= '$j("select#which_editor").prev("span.warning").hide();'."\n";
					$output .= '$j("select#which_editor").hide();'."\n";
				break;
				
				case 'content':
					$output .= '$j("#sectionContentHeader, #sectionContentBody").hide();'."\n"; // For 1.0.0
					$output .= '$j("#ta").parent("div").parent("div").hide().prev("div").hide();'."\n"; // For 1.0.1
				break;
				
				case 'pub_date':
					$output .= '$j("input[name=\'pub_date\']").parents("tr").next("tr").hide();'."\n";
					$output .= '$j("input[name=\'pub_date\']").parents("tr").hide();'."\n";
				break;
				
				case 'unpub_date':
					$output .= '$j("input[name=\'unpub_date\']").parents("tr").next("tr").hide();'."\n";
					$output .= '$j("input[name=\'unpub_date\']").parents("tr").hide();'."\n";
				break;
				
				// Ones that follow the regular pattern
				default:
					if (isset($mm_fields[$field])){ // Check the fields exist,  so we're not writing JS for elements that don't exist
						$output .= '$j("'.$mm_fields[$field]['fieldtype'].'[name=\''.$mm_fields[$field]['fieldname'].'\']").parents("tr").hide().next("tr").find("td[colspan=2]").parent("tr").hide();'."\n";
					}
				break;
			}
			
			$output .= "//---------- mm_hideFields :: End -----\n";
			
			$e->output($output);
		}
	}
}
?>