// <?php
//    @name       ShowImageTVs
//    @version    0.2, 5 Feb 2008
//
//
//    @author     Brett @ The Man Can!
//                rewritten by Rachael Black
//                now works with MooTools and finds the image tvs itself
//

/* ---------------------------------------------------------------
Instructions:
     Create a new Plugin and tick Documents > OnDocFormRender event.
     Make sure it is set to execute after any other plugin that
     could effect the template, like InheritParentTemplate. To edit
     the plugin execution order, from the manager go to Resources >
     Manage Resources > Plugins > Edit Plugin Execution Order by Event
     link. That's it. It should now show images of all image TVs.

     To configure image size, copy the following text (no leading spaces):
       &w=Max width;int;300 &h=Max height;int;100
     into the plugin configuration and change values to suit
     This sets style="max-width: ; max-height: " for the image
     If you don't configure w or h, the image will be fullsize but
     you can add a css rule rule div.tvimage img {...} to the Manager Theme
------------------------------------------------------------------- */

global $content;
$template = $content['template'];
$e = &$modx->Event;

if ($e->name == 'OnDocFormRender' && ($template > 0)) {
	$site = $modx->config['site_url'];

	if (isset($w) || isset($h)) {
		$w = isset($w) ? $w : 300;
		$h = isset($h) ? $h : 100;
		$style = "'max-width:{$w}px; max-height:{$h}px'";
	}
	else
		$style = '';

		// get list of all image template vars
	$table = $modx->getFullTableName('site_tmplvars');
	$result = $modx->db->select('name', $table, "type='image'");
	$tvs = '';
	while ($row = $modx->db->getRow($result))
		$tvs .= ",'" . $row['name'] . "'";
	$tvs = substr($tvs, 1);		// remove leading ','

	$output = <<< EOT
<!-- ShowImageTVs Plugin :: Start -->

<script type="text/javascript" charset="utf-8">
  var imageNames = [$tvs];
  var pageImages = [];

  function full_url(url)
  {
	 	return (url != '' && url.search(/http:\/\//i) == -1) ? ('$site' + url) : url;
  }

  function checkImages()
  {
    for (var i = 0; i < pageImages.length; i++) {
    	var elem = pageImages[i];
      var url = elem.value;
      if (url != elem.oldUrl) {
     	  elem.thumbnail.setProperty('src', full_url(url));
     	  elem.thumbnail.setStyle('display', url=='' ? 'none': 'inline');
        elem.oldUrl = url;
      }
    }
  }

	window.onDomReady(function() {
    for (var i = 0; i < imageNames.length; i++) {
    	var elem = $('tv' + imageNames[i]);
      if (elem) {
        var url = elem.value;

        	// create div and img to show thumbnail
        var div = new Element('div').addClass('tvimage');
        var img = new Element('img').setProperty('src', full_url(url)).setStyles($style);
        elem.getParent().adopt(div.adopt(img));

        elem.thumbnail = img;    // direct access for when need to update
        elem.oldUrl = url;   		 // oldUrl so change HTML only when necessary
        pageImages.push(elem);   // save so don't have to search each time
      }
    }
    setInterval(checkImages, 1000);
  })
</script>

<!-- ShowImageTVs Plugin :: End -->
EOT;

	$e->output($output);
}

// ?>
