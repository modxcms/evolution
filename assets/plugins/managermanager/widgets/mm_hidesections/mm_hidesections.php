<?php
/**
 * mm_hideSections
 * @version 1.2 (2013-05-31)
 * 
 * Hides sections.
 * 
 * @uses ManagerManager plugin 0.5.
 * 
 * @param $sections {comma separated string} - The id(s) of the sections this should apply to. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles).
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates).
 * 
 * @link http://code.divandesign.biz/modx/mm_hidesections/1.2
 * 
 * @copyright 2013
 */

function mm_hideSections($sections, $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		// if we've been supplied with a string, convert it into an array
		$sections = makeArray($sections);
		
		$output = "\n//  -------------- mm_hideSections :: Begin ------------- \n";
		
		foreach($sections as $section){
			switch ($section){
				case 'content':
					$output .= '$j("#content_header, #content_body").hide();'."\n";
				break;
				
				case 'tvs':
					$output .= '$j("#tv_header, #tv_body").hide();'."\n";
				break;
				
				case 'access': // These have moved to tabs in 1.0.1
					$output .= '$j("#sectionAccessHeader, #sectionAccessBody").hide();'."\n";
				break;
				
				default:
					$section = prepareSectionId($section);
					
					$output .= '$j("#'.$section.'_header, #'.$section.'_body").hide();'."\n";
				break;
			}
			
			$output .= "\n//  -------------- mm_hideSections :: End ------------- \n";
			
			$e->output($output . "\n");
		}
	}
}
?>