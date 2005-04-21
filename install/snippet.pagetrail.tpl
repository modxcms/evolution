/*
 * PageTrail - updated to work with MODx
 *
 * Properties:
 *
 *	&sep 	- page trail separator
 *	&style	- inline style
 *	&class	- style sheet name
 */
 

$sep = isset($sep) ? $sep :" &raquo; ";
$style = isset($style) ? " style=\"$style\" " :"";
$class = isset($class) ? " class=\"$class\" " :"";

// end config
$ptarr = array();
$pid = $etomite->documentObject['parent'];
$ptarr[] = "<a $class $style href='[~".$etomite->documentObject['id']."~]'>".$etomite->documentObject['pagetitle']."</a>";

while ($parent=$etomite->getPageInfo($pid)) {
    $ptarr[] = "<a $class $style href='[~".$parent['id']."~]'>".$parent['pagetitle']."</a>";
    $pid = $parent['parent'];
}

$ptarr = array_reverse($ptarr);
return join($ptarr, $sep);