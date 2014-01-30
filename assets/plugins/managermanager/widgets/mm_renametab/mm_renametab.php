<?php
/**
 * mm_renameTab
 * @version 1.1 (2012-11-13)
 * 
 * Rename a tab.
 * 
 * @uses ManagerManager plugin 0.4.
 * 
 * @link http://code.divandesign.biz/modx/mm_renametab/1.1
 * 
 * @copyright 2012
 */

function mm_renameTab($tab, $newname, $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		$output = "//  -------------- mm_renameTab :: Begin ------------- \n";
		
		switch ($tab){
			case 'general':
				$output .= '$j("div#documentPane h2:nth-child(1) span").empty().prepend("'.jsSafe($newname).'");' . "\n";
			break;
			
			case 'settings':
				$output .= '$j("div#documentPane h2:nth-child(2) span").empty().prepend("'.jsSafe($newname).'");' . "\n";
			break;
			
			// This is =<1.0.0 only
			case 'meta':
				if ($modx->hasPermission('edit_doc_metatags')  && $modx->config['show_meta'] != "0"){
					$output .= '$j("div#documentPane h2:nth-child(3) span").empty().prepend("'.jsSafe($newname).'");' . "\n";
				}
			break;
			
			// This is 1.0.1 specific
			case 'access':
				$access_index = ($modx->config['show_meta'] == "0") ? 3 : 4;
				$output .= '$j("div#documentPane h2:nth-child('.$access_index .') span").empty().prepend("'.jsSafe($newname).'");' . "\n";
			break;
		}
		
		$output .= "//  -------------- mm_renameTab :: End ------------- \n";
		
		$e->output($output . "\n");
	}
}
?>