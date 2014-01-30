<?php
/**
 * mm_hideFields
 * @version 1.1.1 (2013-05-16)
 *
 * Hide a field.
 * 
 * @uses ManagerManager plugin 0.4.
 * 
 * @param fields {comma separated string} - Поля документа (или TV), которые необходимо скрыть. @required
 * @param roles {comma separated string - Роли, для которых необходимо применить виждет, пустое значение — все роли.
 * @param templates {comma separated string} - Id шаблонов, для которых необходимо применить виджет, пустое значение — все шаблоны.
 * 
 * @link http://code.divandesign.biz/modx/mm_hidefields/1.1.1
 * 
 * @copyright 2013
 */

function mm_hideFields($fields, $roles = '', $templates = ''){
	global $mm_fields, $modx;
	$e = &$modx->Event;
	
	// if we've been supplied with a string, convert it into an array
	$fields = makeArray($fields);
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		$output = "//  -------------- mm_hideFields :: Begin ------------- \n";
		
		foreach ($fields as $field){
			switch ($field){
				// Exceptions
				case 'keywords':
					$output .= '$j("select[name*=keywords]").parent("td").hide();';
				break;
				
				case 'metatags':
					$output .= '$j("select[name*=metatags]").parent("td").hide()';
				break;
				
				case 'hidemenu':
				case 'hide_menu':
				case 'show_in_menu':
					$output .= '$j("input[name=hidemenucheck]").parent("td").hide();';
				break;
				
				case 'menuindex':
					$output .= '$j("input[name=menuindex]").parents("table").parent("td").prev("td").children("span.warning").hide();' ."\n";
					$output .= '$j("input[name=menuindex]").parent("td").hide();';
				break;
				
				case 'which_editor':
					$output .= '$j("select#which_editor").prev("span.warning").hide();' . "\n";
					$output .= '$j("select#which_editor").hide();';
				break;
				
				case 'content':
					$output .= '$j("#sectionContentHeader, #sectionContentBody").hide();'; // For 1.0.0
					$output .= '$j("#ta").parent("div").parent("div").hide().prev("div").hide();'."\n"; // For 1.0.1
				break;
				
				case 'pub_date':
					$output .= '$j("input[name=pub_date]").parents("tr").next("tr").hide(); '."\n";
					$output .= '$j("input[name=pub_date]").parents("tr").hide(); ';
				break;
				
				case 'unpub_date':
					$output .= '$j("input[name=unpub_date]").parents("tr").next("tr").hide(); '."\n";
					$output .= '$j("input[name=unpub_date]").parents("tr").hide(); ';
				break;
				
				// Ones that follow the regular pattern
				default:
					if (isset($mm_fields[$field])){ // Check the fields exist,  so we're not writing JS for elements that don't exist
						$output .= '$j("'.$mm_fields[$field]['fieldtype'].'[name='.$mm_fields[$field]['fieldname'].']").parents("tr").hide().next("tr").find("td[colspan=2]").parent("tr").hide(); ';
					}
				break;
			}
			
			$output .= "//  -------------- mm_hideFields :: End ------------- \n";
			
			$e->output($output . "\n");
		}
	}
}
?>