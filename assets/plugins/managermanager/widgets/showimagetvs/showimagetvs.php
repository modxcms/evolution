<?php
/**
 * mm_widget_showimagetvs
 * @version 1.2.1 (2014-05-07)
 * 
 * @desc A widget for ManagerManager plugin that allows the preview of images chosen in image TVs to be shown on the document editing page.
 * Emulates showimagestv plugin, which is not compatible with ManagerManager.
 * 
 * @uses ManagerManager plugin 0.6.1.
 * 
 * @param $tvs {comma separated string} - The name(s) of the template variables this should apply to. Default: ''.
 * @param $maxWidth {integer} - Preferred maximum width of the preview. Default: 300.
 * @param $maxHeight {integer} - Preferred maximum height of the preview. Default: 100.
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * 
 * @link http://code.divandesign.biz/modx/mm_widget_showimagetvs/1.2.1
 * 
 * @copyright 2014
 */

function mm_widget_showimagetvs($tvs = '', $maxWidth = 300, $maxHeight = 100, $thumbnailerUrl = '', $roles = '', $templates = ''){
	if (!useThisRule($roles, $templates)){return;}
	
	global $modx;
	$e = &$modx->Event;
	
	if ($e->name == 'OnDocFormPrerender'){
		//The main js file including
		$output = includeJsCss($modx->config['site_url'].'assets/plugins/managermanager/widgets/showimagetvs/jquery.ddMM.mm_widget_showimagetvs.js', 'html', 'jquery.ddMM.mm_widget_showimagetvs', '1.0.1');
		
		$e->output($output);
	}else if ($e->name == 'OnDocFormRender'){
		global $mm_current_page;
		
		$output = '';
		
        // Does this page's template use any image TVs? If not, quit now!
		$tvs = tplUseTvs($mm_current_page['template'], $tvs, 'image');
		if ($tvs == false){return;}
		
		$output .= "//---------- mm_widget_showimagetvs :: Begin -----\n";
		
		// Go through each TV
		foreach ($tvs as $tv){
			$output .= 
'
$j("#tv'.$tv['id'].'").mm_widget_showimagetvs({
	thumbnailerUrl: "'.trim($thumbnailerUrl).'",
	width: '.intval($maxWidth).',
	height: '.intval($maxHeight).',
});
';
		}
		
		$output .= "//---------- mm_widget_showimagetvs :: End -----\n";
		
		$e->output($output);
	}
}
?>