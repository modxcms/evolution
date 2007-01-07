<?php
/**
 * Filename:       includes/lang/english-british.inc.php
 * Function:       Language file.
 * Encoding:       ??
 * Author:         The MODx Project Team (originally by Alex Butter)
 * Date:           2006/09/28
 * Version:        2.1
 * MODx version:   0.9.5
*/
// NOTE: Now alpha-sorted

include_once(dirname(__FILE__).'/english.inc.php'); // picks up American English

$_lang["no_category"] = 'uncategorised';
$_lang["help_msg"] = '<p>You can obtain free community support by <a href="http://modxcms.com/forums" target="_blank">visiting the MODx Forums</a>. There is also a growing body of <a href="http://modxcms.com/documentation" target="_blank">MODx Documentation and Guides</a> that touch on virtually every aspect of MODx.</p><p>We are planning to offer commercial support services for MODx as well. Please <a href="mailto:modx@vertexworks.com?subject=MODx Commercial Support Enquiry">email us if you\'re interested</a>.';
$_lang["unauthorizedpage_message"] = 'Enter the ID of the document you want to send users to if they have requested a secured or unauthorised document. <b>NOTE: make sure the ID you\'ve entered belongs to an existing document, and that it has been published and is publicly accessible!</b>';
$_lang["unauthorizedpage_title"] = 'Unauthorised page:';
?>