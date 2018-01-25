<?php
/**
 * mm_renameField
 * @version 1.2.1 (2014-05-08)
 * 
 * @desc A widget for ManagerManager plugin that allows one of the default document fields or template variables to be renamed within the manager.
 * 
 * @uses ManagerManager plugin 0.4.
 * 
 * @param $fields {comma separated string} - The name(s) of the document fields (or TVs) this should apply to. @required
 * @param $newlabel {string} - The new text for the label. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles).
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates).
 * @param $newhelp {string} - New text for the help icon with this field or for comment with TV. The same restriction apply as when using mm_changeFieldHelp directly.
 * 
 * @link http://code.divandesign.biz/modx/mm_renamefield/1.2.1
 * 
 * @copyright 2014
 */

function mm_renameField($fields, $newlabel, $roles = '', $templates = '', $newhelp = ''){
	global $modx;
	$e = &$modx->Event;
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		
		$fields = makeArray($fields);
		if (count($fields) == 0){return;}
		
		$output = "//---------- mm_renameField :: Begin -----\n";
		
		foreach ($fields as $field){
			$element = '';
			
			switch ($field){
				// Exceptions
				case 'keywords':
					$element = '$j("select[name*=\'keywords\']").siblings("span.warning")';
				break;
				
				case 'metatags':
					$element = '$j("select[name*=\'metatags\']").siblings("span.warning")';
				break;
				
				case 'hidemenucheck':
				case 'hidemenu':
				case 'show_in_menu':
					$element = '$j("input[name=\'hidemenucheck\']").parent().parent().find("span.warning")';
				break;
				
				case 'which_editor':
					$element = '$j("#which_editor").prev("span.warning")';
				break;
				
				case 'content':
					$element = '$j("#content_header")';
				break;
				
				//case 'menuindex':
				//	$element = '$j("input[name=\'menuindex\']").parents("table:first").parents("td:first").prev("td").find("span.warning")';
				//break;
				
				// Ones that follow the regular pattern
				default:
					global $mm_fields;
					
					if (isset($mm_fields[$field])){
						$element = '$j("'.$mm_fields[$field]['fieldtype'].'[name=\''.$mm_fields[$field]['fieldname'].'\']").parents("td:first").prev("td").children("span.warning")';
					}
				break;
			}
			
			if ($element != ''){
				$output .= $element.'.contents().filter(function(){return this.nodeType === 3;}).replaceWith("'.jsSafe($newlabel).'");'."\n";
			}
			
			// If new help has been supplied, do that too
			if ($newhelp != ''){
				mm_changeFieldHelp($field, $newhelp, $roles, $templates);
			}
		}
		
		$output .= "//---------- mm_renameField :: End -----\n";
		
		$e->output($output);
	}
}
?>