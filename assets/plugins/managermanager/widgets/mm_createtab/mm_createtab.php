<?php
/**
 * mm_createTab
 * @version 1.1.1 (2014-12-01)
 * 
 * @desc A widget for ManagerManager plugin that allows create a new custom tab within the document editing page.
 * 
 * @uses ManagerManager plugin 0.6.2.
 * 
 * @param $name {string} - The display name of the new tab. @required
 * @param $id {string} - A unique ID for this tab, so you can reference it later on, if you need to. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Templates IDs for which the widget is applying (empty value means the widget is applying to all templates). Default: ''.
 * @param $intro {string} - HTML text which appears at the top of the new tab. Default: ''.
 * @param $width {string} - New width for the content within the tab. If no units are included, they will be assumed to be pixels e.g. '100%' or '450px'. Default: '680'.
 * 
 * @event OnDocFormRender
 * @event OnPluginFormRender
 * 
 * @link http://code.divandesign.biz/modx/mm_createtab/1.1.1
 * 
 * @copyright 2014
 */

function mm_createTab($name, $id, $roles = '', $templates = '', $intro = '', $width = ''){
	global $modx;
	$e = &$modx->Event;
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ((($e->name == 'OnDocFormRender') || ($e->name == 'OnPluginFormRender')) && useThisRule($roles, $templates)){
		// Plugin page tabs use a differen name for the tab object
		$js_tab_object = ($e->name == 'OnPluginFormRender') ? 'tpSnippet' : 'tpSettings';
		
		$output = "//---------- mm_createTab :: Begin -----\n";
		
		$tabId = prepareTabId($id);
		
		$empty_tab = '
<div class="tab-page" id="'.$tabId.'">
	<h2 class="tab">'.$name.'</h2>
	<div class="tabIntro" id="tab-intro-'.$id.'">'.$intro.'</div>
	<table width="'.$width.'" border="0" cellspacing="0" cellpadding="0" id="table-'.$id.'">
	</table>
</div>
		';
		
		// Clean up for js output
		$empty_tab = str_replace(array("\n", "\t", "\r"), '', $empty_tab);
		
		$output .= '$j';
		$output .= "('div#' + mm_lastTab).after('".$empty_tab."');\n";
		$output .= "mm_lastTab = '".$tabId."';\n";
		$output .= $js_tab_object.'.addTabPage(document.getElementById("'.$tabId.'"));'."\n";
		
		$output .= "//---------- mm_createTab :: End -----\n";
		
		$e->output($output);
	}
}
?>