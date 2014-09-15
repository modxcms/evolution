<?php
/**
 * mm_ddHTMLCleaner
 * @version 1.0.4 (2014-03-14)
 * 
 * @desc A widget for the plugin ManagerManager. It removes forbidden HTML attributes and styles from document fields and TVs when required.
 * 
 * @uses ManagerManager plugin 0.6.
 * 
 * @param $fields {comma separated string} - The name(s) of the document fields (or TVs) which the widget is applied to. @required
 * @param $roles {comma separated string} - Roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Templates IDs for which the widget is applying (empty value means the widget is applying to all templates). Default: ''.
 * @param $validAttrsForAllTags {comma separated string} - Default: 'title,class'.
 * @param $validStyles {comma separated string} - Default: 'word-spacing'.
 * @param $validAttrs {string: JSON} - Default: '{"img":"src,alt,width,height","a":"href,target"}'.
 * 
 * @event OnDocFormPrerender
 * @event OnDocFormRender
 * 
 * @link http://code.divandesign.biz/modx/mm_ddhtmlcleaner/1.0.4
 * 
 * @copyright 2014, DivanDesign
 * http://www.DivanDesign.biz
 */

function mm_ddHTMLCleaner($fields, $roles = '', $templates = '', $validAttrsForAllTags = 'title,class', $validStyles = 'word-spacing', $validAttrs = '{"img":"src,alt,width,height","a":"href,target"}'){
	if (!useThisRule($roles, $templates)){return;}
	
	global $modx;
	$e = &$modx->Event;
	
	if ($e->name == 'OnDocFormPrerender'){
		$widgetDir = $modx->config['site_url'].'assets/plugins/managermanager/widgets/mm_ddhtmlcleaner/';
		
		$output = includeJsCss($widgetDir.'jquery.ddHTMLCleaner-0.2.min.js', 'html', 'jquery.ddHTMLCleaner', '0.2');
		$output .= includeJsCss($widgetDir.'jquery.ddMM.mm_ddHTMLCleaner.js', 'html', 'jquery.ddMM.mm_ddHTMLCleaner', '1.0.1');
		
		$e->output($output);
	}else if ($e->name == 'OnDocFormRender'){
		global $mm_fields, $content;
		
		if ($content['contentType'] != 'text/html'){return;}
		
		$fields = getTplMatchedFields($fields);
		if ($fields == false){return;}
		
		$selectors = array();
		
		foreach ($fields as $field){
			$selectors[] = $mm_fields[$field]['fieldtype'].'[name=\"'.$mm_fields[$field]['fieldname'].'\"]';
		}
		
		$output = "//---------- mm_ddHTMLCleaner :: Begin -----\n";
		
		$output .=
'
$j.ddMM.mm_ddHTMLCleaner.addInstance("'.implode(',', $selectors).'", {
	validAttrsForAllTags: "'.$validAttrsForAllTags.'",
	validAttrs: '.$validAttrs.',
	validStyles: "'.$validStyles.'"
});
';
		
		$output .= "//---------- mm_ddHTMLCleaner :: End -----\n";
		
		$e->output($output);
	}
}
?>