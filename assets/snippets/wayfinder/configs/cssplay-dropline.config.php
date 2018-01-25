<?php
	$level = 3;
	
	$parentClass = 'hide';
	$outerClass = 'menu';

	$outerTpl = '@CODE:<div[+wf.classes+]>
	    <ul>
	        [+wf.wrapper+]
	    </ul>
	</div>';

	$rowTpl = '@CODE:<li[+wf.classes+]><a href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]</a>[+wf.wrapper+]</li>';

	$parentRowTpl = '@CODE:<li><a [+wf.classes+] href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]</a>
	    [+wf.wrapper+]
	</li>';
	
	$innerTpl = '@CODE:<ul>
	    [+wf.wrapper+]
	</ul>';
	
	$cssTpl = '@CODE:<link rel="stylesheet" media="all" type="text/css" href="assets/snippets/wayfinder/examples/cssplay/dropline.css" />';
