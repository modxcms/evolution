<?php
$frame = preg_replace('/[^a-z0-9]/i','',$_REQUEST['f']);
if ($frame > 9) {
    // this is to stop the debug thingy being attached to the framesets
    evolutionCMS()->setConfig('enable_debug', false);
}
if (! empty($frame)) {
    include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("frames/".$frame.".php");
}
?>
