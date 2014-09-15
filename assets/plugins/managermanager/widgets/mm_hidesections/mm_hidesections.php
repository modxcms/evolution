<?php
/**
 * mm_hideSections
 * @version 1.2.1 (2014-05-25)
 * 
 * @desc A widget for ManagerManager plugin that allows one or a few sections to be hidden on the document edit page.
 * 
 * @uses ManagerManager plugin 0.6.2.
 * 
 * @param $sections {comma separated string} - The id(s) of the sections this should apply to. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * 
 * @link http://code.divandesign.biz/modx/mm_hidesections/1.2.1
 * 
 * @copyright 2014
 */

function mm_hideSections($sections, $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		// if we've been supplied with a string, convert it into an array
		$sections = makeArray($sections);
		
		$output = "//---------- mm_hideSections :: Begin -----\n";
		
		foreach($sections as $section){
			switch ($section){
				case 'access': // These have moved to tabs in 1.0.1
					$output .= '$j("#sectionAccessHeader, #sectionAccessBody").hide();'."\n";
				break;
				
				default:
					$section = prepareSectionId($section);
					
					$output .= '$j("#'.$section.'_header, #'.$section.'_body").hide();'."\n";
				break;
			}
		}
		
		$output .= "//---------- mm_hideSections :: End -----\n";
		
		$e->output($output);
	}
}
?>