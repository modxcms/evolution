<?php
// get the requested frame
$frame = preg_replace('/[^a-z0-9]/i','',$_REQUEST['f']);
if($frame>9) {
    $enable_debug=false;    // this is to stop the debug thingy being attached to the framesets
}
include_once(includeFileProcessor("frames/".$frame.".php",$manager_theme));
