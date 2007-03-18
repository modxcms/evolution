<?php
	$hideSubMenus = 1;
	
	if ($modx->config['site_start'] == $modx->documentObject['id']) {
		$homeLink = '';
	} else {
		$homeLink = "<a href=\"{$modx->config['site_url']}\" title=\"home\">Home</a> &raquo; ";
	}
	
	$outerTpl = "@CODE:<div id=\"breadcrumbnav\">
		{$homeLink}[+wf.wrapper+]
	</div>";
	
	$innerTpl = '@CODE:[+wf.wrapper+]';
	
	$rowTpl = '@CODE: ';
	
	$activeParentRowTpl = '@CODE:<a href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]</a> &raquo; [+wf.wrapper+]';
	
	$hereTpl = '@CODE:[+wf.linktext+]';
?>