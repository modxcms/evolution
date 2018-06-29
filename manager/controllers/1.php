<?php
// get the requested frame
$frame = preg_replace('/[^a-z0-9]/i','',$_REQUEST['f']);
if($frame>9) {
    $enable_debug=false;    // this is to stop the debug thingy being attached to the framesets
}
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("frames/".$frame.".php");
