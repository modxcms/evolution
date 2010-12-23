<?php

//---------------------------------------------------------------------------------
// mm_widget_showimagetvs
// Shows a preview of image TVs
// Emulates showimagestv plugin, which is not compatible with ManagerManager
//--------------------------------------------------------------------------------- 


function mm_widget_showimagetvs($tvs='', $w=300, $h=100, $thumbnailerUrl='', $roles='', $templates='') {
	
	global $modx, $content;
	$e = &$modx->Event;
	
	if (useThisRule($roles, $templates)) {
		
		$output = '';	
				
		$site = $modx->config['site_url'];
		
		if (isset($w) || isset($h)) {
			$w = isset($w) ? $w : 300;
			$h = isset($h) ? $h : 100;
			$style = "'max-width:{$w}px; max-height:{$h}px; margin: 4px 0; cursor: pointer;'";
		} else {
 			$style = '';
		}
		
		
		// Which template is this page using?
		if (isset($content['template'])) {
			$page_template = $content['template'];
		} else {
			// If no content is set, it's likely we're adding a new page at top level. 
			// So use the site default template. This may need some work as it might interfere with a default template set by MM?
			$page_template = $modx->config['default_template']; 
		}
		
		
        // Does this page's template use any image TVs? If not, quit now!
		$tvs = tplUseTvs($page_template, $tvs, 'image');
		if ($tvs == false) {
			return;
		}			

		
		$output .= "// ---------------- mm_widget_showimagetvs: Add image preview ------------- \n";
              
				
		// Go through each TV 
		foreach ($tvs as $tv) {
		
			$new_html = '';
			
			$output .= '// Adding preview for tv'.$tv['id'].'
			$j("#tv'.$tv['id'].'").addClass("imageField").bind( "change load", function() {
				// Get the new URL
				var url = $j(this).val();
				url = (url != "" && url.search(/http:\/\//i) == -1) ? ("'.$site.'" + url) : url;
				
				';
				
				// If we have a PHPThumb URL
				if (!empty($thumbnailerUrl)) {
					$output .= 'url = "'.$thumbnailerUrl.'?src="+escape(url)+"&w='.$w.'&h='.$h.'"; ' . "\n";
				}
				
			$output .= '	
				// Remove the old preview tv'.$tv['id'].'
				$j("#tv'.$tv['id'].'PreviewContainer").remove();
				
				if (url != "") {
					// Create a new preview
					$j("#tv'.$tv['id'].'").parents("td").append("<div class=\"tvimage\" id=\"tv'.$tv['id'].'PreviewContainer\"><img src=\""+url+"\" style=\""+'.$style.'+"\" id=\"tv'.$tv['id'].'Preview\"/></div>");	
					
					// Attach a browse event to the picture, so it can trigger too
					$j("#tv'.$tv['id'].'Preview").click( function() {
														BrowseServer("tv'.$tv['id'].'");		 
																 });
				}
				
			}); // Trigger a change event on load
	
			
			';	
			
		}
		
		$output .= '
		
		
			// Monitor the image TVs for changes
			checkImageTVupdates = function () {
					$j(".imageField").each( function() {
						var $this = $j(this);
						if ($this.val() != $this.data("lastvalue") ) {
							$this.trigger("change").data("lastvalue", $this.val());
						}						
					});
			}	
			
			setInterval ( "checkImageTVupdates();", 250 );
		
	
		';
		
		
		
		
		$e->output($output . "\n");		
	}
	
} // end of widget

?>
