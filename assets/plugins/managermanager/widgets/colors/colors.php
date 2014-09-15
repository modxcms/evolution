<?php
/**
 * mm_widget_colors
 * @version 1.2 (2013-12-11)
 * 
 * A widget for ManagerManager plugin that allows text field to be turned into a color picker storing a chosen hex value in the field on the document editing page.
 * 
 * @uses ManagerManager plugin 0.6.
 * 
 * @param $fields {comma separated string} - The name(s) of the template variables this should apply to. @required
 * @param $default {string} - Which color in hex format should be selected by default in new documents. This is only used in situations where the TV does not have a default value specified in the TV definition. Default: '#ffffff'.
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * 
 * @event OnDocFormPrerender
 * @event OnDocFormRender
 * 
 * @link http://code.divandesign.biz/modx/mm_widget_colors/1.2
 * 
 * @copyright 2013
 */

function mm_widget_colors($fields, $default = '#ffffff', $roles = '', $templates = ''){
	if (!useThisRule($roles, $templates)){return;}
	
	global $modx;
	$e = &$modx->Event;
	
	$output = '';
	
	if ($e->name == 'OnDocFormPrerender'){
		$output .= includeJsCss($modx->config['base_url'] .'assets/plugins/managermanager/widgets/colors/farbtastic.js', 'html', 'farbtastic', '1.2');
		$output .= includeJsCss($modx->config['base_url'] .'assets/plugins/managermanager/widgets/colors/farbtastic.css', 'html');
		
		$e->output($output);
	}else if ($e->name == 'OnDocFormRender'){
		global $mm_current_page, $mm_fields;
		
		// if we've been supplied with a string, convert it into an array
		$fields = makeArray($fields);
		
		// Does this page's template use any of these TVs? If not, quit.
		$tv_count = tplUseTvs($mm_current_page['template'], $fields);
		
		if ($tv_count === false){return;}
		
		$output .= "//---------- mm_widget_colors :: Begin -----\n";
		
		// Go through each of the fields supplied
		foreach ($fields as $tv){
			$tv_id = $mm_fields[$tv]['fieldname'];
			
			$output .=
'
$j("#'.$tv_id.'").css("background-image","none");
$j("#'.$tv_id.'").after(\'<div id="colorpicker'.$tv_id.'"></div>\');
if ($j("#'.$tv_id.'").val() == ""){
	$j("#'.$tv_id.'").val("'.$default.'");
}
$j("#colorpicker'.$tv_id.'").farbtastic("#'.$tv_id.'");
$j("#colorpicker'.$tv_id.'").mouseup(function(){
	// mark the document as dirty, or the value wont be saved
	$j("#'.$tv_id.'").trigger("change");
});
';
		}
		
		$output .= "//---------- mm_widget_colors :: End -----\n";
		
		$e->output($output);
	}
}
?>