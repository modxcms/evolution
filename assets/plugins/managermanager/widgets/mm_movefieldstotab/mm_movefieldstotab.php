<?php
/**
 * mm_moveFieldsToTab
 * @version 1.2.2 (2013-12-10)
 * 
 * @desc A widget for ManagerManager plugin that allows document fields & TVs to be moved in an another tab.
 * 
 * @uses ManagerManager plugin 0.6.
 * 
 * @param $fields {comma separated string} - The name(s) of the document fields (or TVs) this should apply to. @required
 * @param $tabId {string} - The ID of the tab which the fields should be moved to. Can be one of the default tab IDs or a new custom tab created with mm_createTab. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles).
 * @param $templates {comma separated string} - The Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates).
 * 
 * @link http://code.divandesign.biz/modx/mm_movefieldstotab/1.2.2
 * 
 * @copyright 2013
 */

function mm_moveFieldsToTab($fields, $tabId, $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		$output = "//---------- mm_moveFieldsToTab :: Begin -----\n";
		
		$output .= '$j.ddMM.moveFields("'.$fields.'", "'.prepareTabId($tabId).'");'."\n";
		
		$output .= "//---------- mm_moveFieldsToTab :: End -----\n";
		
		$e->output($output);
	}
}
?>