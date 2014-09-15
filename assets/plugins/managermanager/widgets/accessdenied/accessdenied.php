<?php
/**
 * mm_widget_accessdenied
 * @version 1.1.1 (2013-12-11)
 * 
 * @desc A widget for ManagerManager plugin that allows access to specific documents (by ID) to be denied without inheritance on the document editing page.
 * 
 * @uses ManagerManager plugin 0.6.
 * 
 * @param $ids {comma separated string} - List of documents ID to prevent access. @required
 * @param $message {string} - HTML formatted message. Default: 'Access denied - Access to current document closed for security reasons.'.
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * 
 * @link http://code.divandesign.biz/modx/mm_widget_accessdenied/1.1.1
 * 
 * Icon by designmagus.com
 * Originally written by Metaller
 * @copyright 2013
 */

function mm_widget_accessdenied($ids = '', $message = '', $roles = ''){
	global $modx;
	$e = &$modx->Event;
	
	if ($e->name == 'OnDocFormRender' && useThisRule($roles)){
		if (empty($message)){$message = '<span>Access denied</span>Access to current document closed for security reasons.';}
		
		$docid = (int)$_GET[id];
		
		$ids = makeArray($ids);
		
		$output = "//---------- mm_widget_accessdenied :: Begin -----\n";
		
		if (in_array($docid, $ids)){
			$output .= includeJsCss($modx->config['base_url'] . 'assets/plugins/managermanager/widgets/accessdenied/accessdenied.css', 'js');
			
			$output .=
'
$j("input, div, form[name=mutate]").remove(); // Remove all content from the page
$j("body").prepend(\'<div id="aback"><div id="amessage">'.$message.'</div></div>\');
$j("#aback").css({height: $j("body").height()} );
';
		}
		
		$output .= "//---------- mm_widget_accessdenied :: End -----\n";
		
		$e->output($output);
	}
}
?>