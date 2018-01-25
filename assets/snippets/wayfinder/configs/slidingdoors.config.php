<?php
	
	$level = 1;
	$hereClass = ''; 
	$selfClass = 'current'; 
	$lastClass = '';
	
	$outerTpl = '@CODE:<div id="menu">
		<ul>
			[+wf.wrapper+]
		</ul>
	</div>';
	
	$innerTpl = '@CODE:<ul>
		[+wf.wrapper+]
	</ul>';
	
	$rowTpl = '@CODE:<li[+wf.classes+]><a href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]</a>[+wf.wrapper+]</li>';
	
	$cssTpl = '@CODE:<link rel="stylesheet" media="all" type="text/css" href="assets/snippets/wayfinder/examples/slidingdoors/slidingdoors.css" />';
?>