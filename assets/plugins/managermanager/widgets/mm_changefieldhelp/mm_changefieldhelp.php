<?php
/**
 * mm_changeFieldHelp
 * @version 1.1.2 (2014-05-07)
 * 
 * @desc A widget for ManagerManager plugin that allows to change help text that appears near each document field when the icon or comment below template variable is hovered.
 * 
 * @uses ManagerManager plugin 0.5.
 * 
 * @param $field {string} - The name of the document field (or TV) this should apply to. @required
 * @param $helptext {string} - The new help text. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * 
 * @link http://code.divandesign.biz/modx/mm_changefieldhelp/1.1.2
 * 
 * @copyright 2014
 */

function mm_changeFieldHelp($field, $helptext = '', $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	if ($helptext == ''){return;}
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		global $mm_fields;
		
		$output = "//---------- mm_changeFieldHelp :: Begin -----\n";
		
		// What type is this field?
		if (isset($mm_fields[$field])){
			$fieldtype = $mm_fields[$field]['fieldtype'];
			$fieldname = $mm_fields[$field]['fieldname'];
			
			//Is this TV?
			if ($mm_fields[$field]['tv']){
				$output .=
'
var $mm_changeFieldHelp_title = $j("'.$fieldtype.'[name=\''.$fieldname.'\']").parents("td:first").prev("td"),
	$mm_changeFieldHelp_title_comment = $mm_changeFieldHelp_title.children("span.comment");

if ($mm_changeFieldHelp_title_comment.length == 0){
	$mm_changeFieldHelp_title.append("<br />");
	$mm_changeFieldHelp_title_comment = $j("<span class=\'comment\'></span>").appendTo($mm_changeFieldHelp_title);
}

$mm_changeFieldHelp_title_comment.html("'.addslashes($helptext).'");
';
				//Or document field
			}else{
				// Give the help button an ID, and modify the alt/title text
				$output .= '$j("'.$fieldtype.'[name=\''.$fieldname.'\']").siblings("img[style*=\'cursor:help\']").attr("id", "'.$fieldname.'-help").attr("alt", "'.addslashes($helptext).'").attr("title", "'.addslashes($helptext).'");'."\n";
			}
		}
		
		$output .= "//---------- mm_changeFieldHelp :: End -----\n";
		
		$e->output($output);
	}
}
?>