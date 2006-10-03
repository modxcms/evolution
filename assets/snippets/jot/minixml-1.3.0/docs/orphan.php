<?php

header('Content-type: text/plain');

require_once('minixml.inc.php');

	$xmlDoc = new MiniXMLDoc();
 
	
	# Fetch the ROOT element for the document
	# (an instance of XML::Mini::Element)
	$xmlElement =& $xmlDoc->getRoot();
	
	# Create a sub element
	$newChild =& $xmlElement->createChild('mychild');
	
	$newChild->text('hello mommy');
	
	
	# Create an orphan element
	
	$orphan =& $xmlDoc->createElement('annie');
	$orphan->attribute('hair', '#ff0000');
	$orphan->text('tomorrow, tomorrow');
	
	# Adopt the orphan
	$newChild->prependChild($orphan);

	$toy =& $xmlDoc->createElement('toy');
	$toy->attribute('color', '#0000ff');
	$toy->createChild('type', 'teddybear');

	$newChild->insertChild($toy, 1);
	
	print $xmlDoc->toString();

	print "\nUhm, it's not working out - she won't stop singing... Calling removeChild()\n\n";

	$newChild->removeChild($orphan);
	$newChild->text('???');

	print $xmlDoc->toString();
	
?>
