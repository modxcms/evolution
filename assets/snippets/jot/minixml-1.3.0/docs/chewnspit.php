<?php

/* This little test demonstrates the use of fromFile() 
** mainly so you can play with the MINIXML_USEFROMFILECACHING
** option.  For the moment, file caching is hardly usefull
** but this may change if we implement an XSLT interface.
*/


header('Content-type: text/plain');

require_once('minixml.inc.php');

$xmlDoc = new MiniXMLDoc();
 
$xmlDoc->fromFile('./test.xml');

print $xmlDoc->toString();

?>
