<?php
// change the plugin priority
//include_once "header.inc.php"; - in action file
include_once(includeFileProcessor("actions/mutate_plugin_priority.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
