<?php
// get the new plugin action
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_plugin.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
