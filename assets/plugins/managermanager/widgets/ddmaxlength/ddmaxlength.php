<?php
/**
 * mm_ddMaxLength
 * @version 1.1.1 (2013-12-10)
 * 
 * Widget for ManagerManager plugin allowing number limitation of chars inputing in fields (or TVs).
 * 
 * @uses ManagerManager plugin 0.6.
 * 
 * @param $fields {comma separated string} - The name(s) of the document fields (or TVs) which the widget is applied to. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied. Default: ''.
 * @param $length {integer} - Maximum number of inputing chars. Default: 150.
 * 
 * @event OnDocFormPrerender
 * @event OnDocFormRender
 * 
 * @link http://code.divandesign.biz/modx/mm_ddmaxlength/1.1.1
 * 
 * @copyright 2013, DivanDesign
 * http://www.DivanDesign.biz
 */

function mm_ddMaxLength($fields = '', $roles = '', $templates = '', $length = 150){
	if (!useThisRule($roles, $templates)){return;}
	
	global $modx;
	$e = &$modx->Event;
	
	$output = '';
	
	if ($e->name == 'OnDocFormPrerender'){
		$widgetDir = $modx->config['site_url'].'assets/plugins/managermanager/widgets/ddmaxlength/';
		
		$output .= includeJsCss($widgetDir.'ddmaxlength.css', 'html');
		$output .= includeJsCss($widgetDir.'jquery.ddMM.mm_ddMaxLength.js', 'html', 'jquery.ddMM.mm_ddMaxLength', '1.0');
		
		$e->output($output);
	}else if ($e->name == 'OnDocFormRender'){
		global $mm_fields;
		
		$fields = getTplMatchedFields($fields, 'text,textarea');
		if ($fields == false){return;}
		
		$output .= "//---------- mm_ddMaxLength :: Begin -----\n";
		
		foreach ($fields as $field){
			$output .=
'
$j("'.$mm_fields[$field]['fieldtype'].'[name='.$mm_fields[$field]['fieldname'].']").addClass("ddMaxLengthField").each(function(){
	$j(this).parent().append("<div class=\"ddMaxLengthCount\"><span></span></div>");
}).ddMaxLength({
	max: '.$length.',
	containerSelector: "div.ddMaxLengthCount span",
	warningClass: "maxLengthWarning"
});
';
		}
		
		$output .= "//---------- mm_ddMaxLength :: End -----\n";
		
		$e->output($output);
	}
}
?>