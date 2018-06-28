<?php
// get the edit module action
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_module.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
