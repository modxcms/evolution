<?php
/**
 * FirstChildRedirect
 *
 * Automatically redirects to the first child of a Container Resource
 *
 * @category 	snippet
 * @version 	2.0
 * @license 	Public Domain https://wiki.creativecommons.org/wiki/Public_domain
 * @internal	@properties
 * @internal	@modx_category Navigation
 * @internal    @installset base
 * @documentation MODX Docs https://rtfm.modx.com/extras/evo/firstchildredirect
 * @documentation Readme [+site_url+]assets/snippets/firstchildredirect/readme.html
 * @reportissues https://github.com/modxcms/evolution
 * @author      Jason Coward jason@opengeek.com
 * @author      Ryan Thrash ryan@vertexworks.com
 * @author      Revolution code ported back by Thomas Jakobi thomas.jakobi@partout.info
 * @lastupdate  25/12/2013
 */
if (!defined('MODX_BASE_PATH')) {
	die('What are you doing? Get out of here!');
}

// Snippet parameter
$docid = (isset($docid)) ? $docid : $modx->documentIdentifier;
$respcode = (isset($responseCode)) ? (int) $responseCode : 301;
$default = (isset($default)) ? $default : 'site_start';
$sortBy = (isset($sortBy)) ? $sortBy : 'menuindex';
$sortDir = (isset($sortDir)) ? $sortDir : 'ASC';

// Response code
$rcodes = array(
	301 => 'HTTP/1.1 301 Moved Permanently',
	302 => 'HTTP/1.1 302 Moved Temporarily',
);
if (isset($rcodes[$respcode])) {
	$respcode = $rcodes[$respcode];
} else {
	$respcode = $rcodes[301];
}

// Default document (in case there's no children)
if (in_array($default, array('site_start', 'site_unavailable_page', 'error_page', 'unauthorized_page'))) {
	$default = $modx->config[$default];
} else {
	if (is_numeric($default)) {
		$default = (int) $default;
	} else {
		return 'Invalid &default property.';
	}
}

// Execute
$children = $modx->getActiveChildren($docid, $sortBy, $sortDir);
if (!$children === false) {
	$firstChildUrl = $modx->makeUrl($children[0]['id']);
} else {
	$firstChildUrl = $modx->makeUrl($default);
}
return $modx->sendRedirect($firstChildUrl, 0, 'REDIRECT_HEADER', $respcode);
?>
