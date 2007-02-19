<?php

// ------------------------------------------------------------------------------//
// All variables must be prefixed with ditto_ for the snippet to recognize them! //
// ------------------------------------------------------------------------------//

$variables = array();
$ok = array();
$offLimits = array("seeThroughtUnpub","showInMenuOnly","showPublishedOnly","debug","start","config","extenders","dittoID");
	// Can't set &seeThroughtUnpub, &showInMenuOnly, &showPublishedOnly, &start by Url.
$safeIDs = isset($safeIDs) ? explode(",",$safeIDs) : array();
	// IDs that are ok to use
$stripTags = isset($stripTags) ? $stripTags : 1;
$bad = isset($bad) ? array_merge($offLimits,explode(",",$bad)) : $offLimits;
foreach ($_REQUEST as $name=>$value) {
	$saneName = str_replace($dittoID, "", substr($name, 6));
	$dID = ($dittoID == "") ? true : strpos($name, $dittoID);
	if ((substr($name, 0, 6) == "ditto_" && $dID) && !in_array($saneName,$bad) && !ereg("[\^`~!/@\\#\}\$%:;\)\(\{&\*=\|'\+]", $value)){
		if ($stripTags) $var = strip_tags($value);
		$variables[$saneName] = trim($value);
	}
}

if ($_REQUEST[$dittoID."dbg"]==1) {print_r($variables);}
extract($variables);

// ------------------------------------------------------------------------------//
// Kudo's MultiFilter Code 														 //
// ------------------------------------------------------------------------------//
// Accepts ditto_filter, ditto_filter_2, with continuous numbering				 //
// Note: For complex filtering start with ditto_filter_1 (with one as number)!   //
// ------------------------------------------------------------------------------//
  
if (isset($filter) && isset($filter_2)) {
		$i = 2;
		while (isset(${'filter_'.$i})) {
			$filter .= '|'.${'filter_'.$i};
			$i++;
		}
	} elseif (!isset($filter) && isset($filter_1)) {
		$filter = $filter_1;
		$i = 2;
		while (isset(${'filter_'.$i})) {
			$filter .= '|'.${'filter_'.$i};
			$i++;
		}
}

?>

