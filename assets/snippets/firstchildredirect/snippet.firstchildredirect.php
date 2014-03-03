<?php
/*
 * @name FirstChildRedirect
 * @author Jason Coward <jason@opengeek.com>
 * @modified-by Ryan Thrash <ryan@vertexworks.com>
 * @ported-back Revolution code by Thomas Jakobi <thomas.jakobi@partout.info>
 * @license Public Domain
 * @version 2.0
 * 
 * This snippet redirects to the first child document of a folder in which this
 * snippet is included within the content (e.g. [!FirstChildRedirect!]).  This
 * allows MODX folders to emulate the behavior of real folders since MODX
 * usually treats folders as actual documents with their own content.
 * 
 * Modified to make Doc ID a required parameter... now defaults to the current 
 * Page/Folder you call the snippet from.
 *
 * Parameter:
 *
 * &docid=`12` (optional; default: current document)
 * Use the docid parameter to have this snippet redirect to the
 * first child document of the specified document.
 *
 * &default=`1` or &default=`site_start` (optional; default: site_start)
 * Use the default parameter to have this snippet redirect to the
 * document specified in cases where there is no children.
 * It can be a document ID or one of: site_start, site_unavailable_page, error_page, unauthorized_page
 *
 * &sortBy=`menuindex` (optional; default: menuindex)
 * Get the first child depending on this sort order
 * Can be any valid modx document field name
 *
 * &sortDir=`DESC` (optional; default: ASC)
 * Sort `ASC` for ascendant or `DESC` for descendant
 *
 * &responseCode ("301", "302" or the complete response code, eg "HTTP/1.1 302 Moved Temporarily", defaults to 301)
 * The responsecode (statuscode) to use for sending the redirect.
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
