<?php
/**
 * mm_widget_template
 * @version 1.0 (2014-01-01)
 * 
 * A template for creating new widgets
 * 
 * @uses ManagerManager plugin 0.6.2.
 * 
 * @event OnDocFormPrerender
 * @event OnDocFormRender
 * 
 * @link http://
 * 
 * @copyright 2014
 */

function mm_widget_template($fields, $other_param = 'defaultValue', $roles = '', $templates = ''){
	if (!useThisRule($roles, $templates)){return;}
	
	global $modx;
	$e = &$modx->Event;
	
	$output = '';
	
	if ($e->name == 'OnDocFormPrerender'){
		// We have functions to include JS or CSS external files you might need
		// The standard ModX API methods don't work here
		$output .= includeJsCss($modx->config['base_url'].'assets/plugins/managermanager/widgets/template/javascript.js', 'html');
		$output .= includeJsCss($modx->config['base_url'].'assets/plugins/managermanager/widgets/template/styles.css', 'html');
		
		$e->output($output);
	}else if ($e->name == 'OnDocFormRender'){
		global $mm_fields, $mm_current_page;
		
		// if we've been supplied with a string, convert it into an array
		$fields = makeArray($fields);
		
		$tvs = tplUseTvs($mm_current_page['template'], $fields);
		if ($tvs == false){return;}

		// Your output should be stored in a string, which is outputted at the end
		// It will be inserted as a Javascript block (with jQuery), which is executed on document ready
		// We always put a JS comment, which makes debugging much easier
		$output .= "//---------- mm_widget_template :: Begin -----\n";
		
		// Do something for each of the fields supplied
		foreach ($fields as $targetTv){
			// If it's a TV, we may need to map the field name, to what it's ID is.
			// This can be obtained from the mm_fields array
			$tv_id = $mm_fields[$targetTv]['fieldname'];
		}
		
		//JS comment for end of widget
		$output .= "//---------- mm_widget_template :: End -----\n";
		
		// Send the output to the browser
		$e->output($output);
	}
}
?>