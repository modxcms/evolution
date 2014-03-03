<?php
/**
 * mm_renameSection
 * @version 1.2 (2013-05-31)
 * 
 * Rename a section.
 * 
 * @uses ManagerManager plugin 0.5.
 * 
 * @param $section {string} - The id of the section this should apply to.
 * @param $newname {string} - The new text for the label.
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles).
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates).
 * 
 * @link http://code.divandesign.biz/modx/mm_renamesection/1.2
 * 
 * @copyright 2013
 */

function mm_renameSection($section, $newname, $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		$output = "//  -------------- mm_renameSection :: Begin ------------- \n";
		
		switch ($section){
			case 'content':
				$output .= '$j("div#content_header").empty().prepend("'.jsSafe($newname).'");' . "\n";
			break;
			
			case 'tvs':
				$output .= '
				$j("div#tv_header").empty().prepend("'.jsSafe($newname).'");
				' ;
			break;
			
			case 'access': // These have moved to tabs in 1.0.1
				$output .= '$j("div#sectionAccessHeader").empty().prepend("'.jsSafe($newname).'");' . "\n";
			break;
			
			default:
				$output .= '$j("#'.prepareSectionId($section).'_header").empty().prepend("'.jsSafe($newname).'");'."\n";
			break;
		}
		
		$output .= "//  -------------- mm_renameSection :: End ------------- \n";
		
		$e->output($output . "\n");
	}
}
?>