<?php
/**
 * MODX Manager language file
 *
 * @version 1.0.15
 * @date 2014/02/24
 * @author The MODX Project Team
 *
 * @language British English
 * @package modx
 * @subpackage manager
 *
 * Please commit your language changes on Transifex (https://www.transifex.com/projects/p/modx-evolution/) or on GitHub (https://github.com/modxcms/evolution).
 */
$filename = dirname(__FILE__) . '/english.inc.php';
$contents = file_get_contents($filename);
eval('?>' . $contents);
$_lang["user_state"] = 'County';
$_lang["user_zip"] = 'Postcode';
$_lang["help_msg"] = '<p>You can obtain free community support by <a href="http://forums.modx.com/" target="_blank">visiting the MODX Forums</a>. There is also a growing body of <a href="http://rtfm.modx.com/display/Evo1/Home" target="_blank">MODX Documentation and Guides</a> that touch on virtually every aspect of MODX.</p><p>We are planning to offer commercial support services for MODX as well. Please <a href="mailto:modx@vertexworks.com?subject=MODX Commercial Support Enquiry">email us if you\'re interested</a>.';
$_lang["no_category"] = 'uncategorised';
$_lang["unauthorizedpage_message"] = 'Enter the ID of the document you want to send users to if they have requested a secured or unauthorised document. <b>NOTE: make sure the ID you\'ve entered belongs to an existing document, and that it has been published and is publicly accessible!</b>';
$_lang["unauthorizedpage_title"] = 'Unauthorised page:';
