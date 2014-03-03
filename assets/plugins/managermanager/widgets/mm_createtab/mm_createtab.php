<?php
/**
 * mm_createTab
 * @version 1.1 (2012-11-13)
 * 
 * Create a new tab.
 * 
 * @uses ManagerManager plugin 0.4.
 * 
 * @link http://code.divandesign.biz/modx/mm_createtab/1.1
 * 
 * @copyright 2012
 */

function mm_createTab($name, $id, $roles = '', $templates = '', $intro = '', $width = '680'){
	global $modx;
	$e = &$modx->Event;
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ((($e->name == 'OnDocFormRender') || ($e->name == 'OnPluginFormRender')) && useThisRule($roles, $templates)){
		// Plugin page tabs use a differen name for the tab object
		$js_tab_object = ($e->name == 'OnPluginFormRender') ? 'tpSnippet' : 'tpSettings';
		
		$output = "//  -------------- mm_createTab :: Begin ------------- \n";
		
		$empty_tab = '
<div class="tab-page" id="tab'.$id.'">
	<h2 class="tab">'.$name.'</h2>
	<div class="tabIntro" id="tab-intro-'.$id.'">'.$intro.'</div>
	<table width="'.$width.'" border="0" cellspacing="0" cellpadding="0" id="table-'.$id.'">
	</table>
</div>
		';
		
		// Clean up for js output
		$empty_tab = str_replace( array("\n", "\t", "\r") , '', $empty_tab);
		$output .='$j';
		$output .= "('div#'+mm_lastTab).after('".$empty_tab."'); \n";
		$output .= "mm_lastTab = 'tab".$id."'; \n";
		$output .= $js_tab_object. '.addTabPage( document.getElementById( "tab'.$id.'" ) ); ';
		
		$output .= "//  -------------- mm_createTab :: End ------------- \n";
		
		$e->output($output . "\n");
	}
}
?>