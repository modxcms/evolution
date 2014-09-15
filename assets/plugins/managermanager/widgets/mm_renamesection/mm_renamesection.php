<?php
/**
 * mm_renameSection
 * @version 1.2.1 (2014-05-26)
 * 
 * @desc A widget for ManagerManager plugin that allows sections to be renamed.
 * 
 * @uses ManagerManager plugin 0.6.2.
 * 
 * @param $section {string; 'content'; 'tvs'} - The name of the section this should apply to. @required
 * @param $newname {string} - The new text for the label. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * 
 * @event OnDocFormRender
 * 
 * @link http://code.divandesign.biz/modx/mm_renamesection/1.2.1
 * 
 * @copyright 2014
 */

function mm_renameSection($section, $newname, $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		$output = "//---------- mm_renameSection :: Begin -----\n";
		
		switch ($section){
			case 'access': // These have moved to tabs in 1.0.1
				$output .= '$j("div#sectionAccessHeader").empty().prepend("'.jsSafe($newname).'");'."\n";
			break;
			
			default:
				$output .= '$j("#'.prepareSectionId($section).'_header").empty().prepend("'.jsSafe($newname).'");'."\n";
			break;
		}
		
		$output .= "//---------- mm_renameSection :: End -----\n";
		
		$e->output($output);
	}
}
?>