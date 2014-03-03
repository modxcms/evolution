<?php
/** 
 * mm_ddCreateSection
 * @version 1.0 (2013-05-22)
 * 
 * @description 
 * 
 * @uses ManagerManager 0.5.
 *
 * @param $title {string} - The display name of the new section. @required
 * @param $id {string} - A unique ID for this section. @required
 * @param $tabId {string} - The ID of the tab which the section should be inserted to. Can be one of the default tab IDs or a new custom tab created with mm_createTab. Default: 'general'.
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * 
 * @link http://code.divandesign.biz/modx/mm_ddcreatesection/1.0
 * 
 * @copyright 2013, DivanDesign
 * http://www.DivanDesign.ru
 */

function mm_ddCreateSection($title, $id, $tabId = 'general', $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates) && !empty($id)){
		// We always put a JS comment, which makes debugging much easier
		$output = "\n//  -------------- mm_ddCreateSection :: Begin ------------- \n";
		
		if ($title == ''){$title = $id;}
		
		$id = prepareSectionId($id);
		$tabId = prepareTabId($tabId);
		
		$section = '
<div class="sectionHeader" id="'.$id.'_header">'.$title.'</div>
<div class="sectionBody" id="'.$id.'_body"><table style="position:relative;" border="0" cellspacing="0" cellpadding="3" width="100%"></table></div>
		';
		//tabGeneral
		// Clean up for js output
		$section = str_replace( array("\n", "\t", "\r") , '', $section);
		
		$output .= '$j("#'.$tabId.'").append(\''.$section.'\');';
		
		//JS comment for end of widget
		$output .= "\n//  -------------- mm_ddCreateSection :: End ------------- \n";
		
		// Send the output to the browser
		$e->output($output . "\n");
	}
}
?>