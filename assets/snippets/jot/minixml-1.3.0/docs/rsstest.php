<?php

/* This little test demonstrates the use of the new parser and 
** toArray() method 
*/


header('Content-type: text/plain');

require_once('minixml.inc.php');

$xmlDoc = new MiniXMLDoc();
 
$xmlDoc->fromFile('./rsstest.xml');

print_r($xmlDoc->toArray());

?>
