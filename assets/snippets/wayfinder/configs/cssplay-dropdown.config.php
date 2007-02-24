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
	    <!--[if lte IE 6]>
	    <a class="sub" href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]
	    <table><tr><td>
	    <![endif]-->
	    [+wf.wrapper+]
	    <!--[if lte IE 6]>
		</td></tr></table>
	    </a>
	    <![endif]-->
	</li>';
	
	$cssTpl = '@CODE:<link rel="stylesheet" media="all" type="text/css" href="assets/snippets/wayfinder/examples/cssplay/dropdown.css" />
	<!--[if lte IE 6]>
	<link rel="stylesheet" media="all" type="text/css" href="assets/snippets/wayfinder/examples/cssplay/dropdown_ie.css" />
	<![endif]-->';
?>