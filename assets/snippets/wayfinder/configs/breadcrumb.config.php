<?php
////////////////// default settings
	if(!isset($hideSubMenus)) $hideSubMenus = 1;
	if(!isset($ignoreHidden)) $ignoreHidden = true;
	if(!isset($startId))      $startId      = 0;
	
////////////////// template
include_once('breadcrumb.class.inc.php');
$wfbc = new WFBC();
	if(!isset($outerTpl)) $outerTpl = '<div id="breadcrumbnav">[+home+][+wf.wrapper+]</div>';
	else                  $outerTpl = $wfbc->fetch($outerTpl);
	if(!isset($innerTpl)) $innerTpl = '[+wf.wrapper+]';
	else                  $innerTpl = $wfbc->fetch($innerTpl);
	if(!isset($rowTpl))   $rowTpl   = ' ';
	else                  $rowTpl = $wfbc->fetch($rowTpl);
	if(!isset($hereTpl))  $hereTpl  = '[+wf.linktext+]';
	else                  $hereTpl = $wfbc->fetch($hereTpl);
	if(!isset($delim))    $delim    = ' &raquo; ';
	else                  $delim = $wfbc->fetch($delim);
	if(!isset($activeParentRowTpl)) $activeParentRowTpl = '<a href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]</a>[+delim+][+wf.wrapper+]';
	else                  $activeParentRowTpl = $wfbc->fetch($activeParentRowTpl);
	
////////////////// build
	$activeParentRowTpl = str_replace('[+delim+]',$delim,$activeParentRowTpl);
	if ($modx->config['site_start'] !== $modx->documentIdentifier)
	{
		$home = $modx->getDocumentObject('id', $modx->config['site_start']);
		$home_title = $home['menutitle'] ? $home['menutitle'] : $home['pagetitle'];
		$homeLink = '<a href="' . $modx->config['site_url'] . '" title="' . $home_title . '">' . $home_title . '</a>' . $delim;
	}
	else 
	{
		$homeLink = '';
	}
	
	if ($modx->config['site_start'] !== $modx->documentIdentifier)
		$outerTpl = '@CODE:' . str_replace('[+home+]',$homeLink,$outerTpl);
	else
		$outerTpl = '@CODE: ';
	
	$innerTpl = '@CODE:' . $innerTpl;
	$rowTpl   = '@CODE:' . $rowTpl;
	$hereTpl  = '@CODE:' . $hereTpl;
	$activeParentRowTpl = '@CODE:' . $activeParentRowTpl;

