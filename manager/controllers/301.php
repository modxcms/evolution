<?php
// get the edit document variable action
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_tmplvars.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
