<?php
/**
 * mm_changeFieldHelp
 * @version 1.1.1 (2013-05-20)
 *
 * Change the help text of a field.
 * 
 * @uses ManagerManager plugin 0.5.
 * 
 * @param $field {string} - The name of the document field (or TV) this should apply to. @required
 * @param $helptext {string} - The new help text. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * 
 * @link http://code.divandesign.biz/modx/mm_changefieldhelp/1.1.1
 * 
 * @copyright 2013
 */

function mm_changeFieldHelp($field, $helptext = '', $roles = '', $templates = ''){
	global $mm_fields, $modx;
	$e = &$modx->Event;

	if ($helptext == ''){
		return;
	}

	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		$output = "//  -------------- mm_changeFieldHelp :: Begin ------------- \n";
		
		// What type is this field?
		if (isset($mm_fields[$field])){
			$fieldtype = $mm_fields[$field]['fieldtype'];
			$fieldname = $mm_fields[$field]['fieldname'];
			
			//Is this TV?
			if ($mm_fields[$field]['tv']){
				$output .= '$j("'.$fieldtype.'[name='.$fieldname.']").parents("td:first").prev("td").children("span.comment").html("'.jsSafe($helptext).'");';
				//Or document field
			}else{
				// Give the help button an ID, and modify the alt/title text
				$output .= '$j("'.$fieldtype.'[name='.$fieldname.']").siblings("img[style*=\'cursor:help\']").attr("id", "'.$fieldname.'-help").attr("alt", "'.jsSafe($helptext).'").attr("title", "'.jsSafe($helptext).'"); ';
			}
		}
		
		$output .= "//  -------------- mm_changeFieldHelp :: End ------------- \n";
		
		$e->output($output . "\n");
	}
}
?>