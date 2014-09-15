<?php
/**
 * mm_hideTemplates
 * @version 1.1 (2012-11-13)
 * 
 * Hide a template within the dropdown list of templates.
 * Based on code submitted by Metaller
 * 
 * @uses ManagerManager plugin 0.4.
 * 
 * @link http://code.divandesign.biz/modx/mm_hidetemplates/1.1
 * 
 * @copyright 2012
 */

function mm_hideTemplates($tplIds, $roles = '', $templates = ''){
	global  $modx;
	$e = &$modx->Event;
	
	$tplIds = makeArray($tplIds);
	
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		$output = "//  -------------- mm_hideTemplates :: Begin ------------- \n";
		
		foreach ($tplIds as $tpl){
			$output .= 'if ($j("select#template").val() != '.$tpl. '){ '."\n";
			$output .= '$j("select#template option[value='.$tpl.']").remove();'."\n";
			$output .= '}' . "\n";
		}
		
		$output .= "//  -------------- mm_hideTemplates :: End ------------- \n";
		
		$e->output($output . "\n");
	}
}
?>