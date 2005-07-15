/*
 *	MenuBuilder 
 *	Builds the site menu
 *
 *	Modified by Raymond Irving July, 2005 
 *	- now supports menutitle and hidmenu
 *	
 *	Params:	&normclass	-  normal item class (mouseout) 
 *			&selclass	-  selected item class (active)
 *
 */
 

if(!isset($id)) {
    $id = $modx->documentIdentifier; //current document
}

$indentString="";

if(!isset($indent)) {
    $indent = "";
    $indentString .= "";
} else {
    for($in=0; $in<$indent; $in++) {
        $indentString .= "&nbsp;";
    }
    $indentString .= "&raquo;&nbsp;";
}

$children = $modx->getActiveChildren($id,'menuindex','ASC','id, pagetitle, description, parent, alias, menutitle, hidemenu'); 
$menu = ""; 
$childrenCount = count($children); 
$active="";

if($children==false) {
    return false;
}
for($x=0; $x<$childrenCount; $x++) {
	if($children[$x]['hidemenu']!=1){ // check if we should hide/show menu
		if($children[$x]['id']==$modx->documentIdentifier) {
			$active = isset($selclass) ? "class='$selclass'":"class='highLight'";
		} 
		else {
			$active="";
			$active = isset($normclass) ? "class='$normclass'":"";
		}

		// use either menu or page title
		$mnuTitle = $children[$x]['menutitle'] ? $children[$x]['menutitle']:$children[$x]['pagetitle'];
		if($children[$x]['id']==$modx->documentIdentifier || $children[$x]['id']==$modx->documentObject['parent']) {
			$menu .= "<a ".$active." href='[~".$children[$x]['id']."~]'>$indentString".$mnuTitle."</a>[[MenuBuilder?id=".$children[$x]['id']."&indent=2]]";	
		}
		else {
			$menu .= "<a href='[~".$children[$x]['id']."~]'>$indentString".$mnuTitle."</a>";
		}
	}
}
return $menu."";