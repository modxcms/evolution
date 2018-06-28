<?php
// get the module resources (dependencies) action
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_module_resources.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
