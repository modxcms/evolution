<?php
	$level = 3;
	$outerClass = 'menu';

	$outerTpl = '@CODE:<div[+wf.classes+]>
	    <ul>
	        [+wf.wrapper+]
	    </ul>
	</div>';

	$parentRowTpl = '@CODE:<li><a [+wf.classes+] href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]<!--[if IE 7]><!--></a><!--<![endif]-->
		<table><tr><td>
		[+wf.wrapper+]
		</td></tr></table>
		<!--[if lte IE 6]></a><![endif]-->
	</li>';
	
	$cssTpl = '@CODE:<link rel="stylesheet" media="all" type="text/css" href="assets/snippets/wayfinder/examples/cssplay/flyout_revisited.css" />';
?>