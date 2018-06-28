<?php
// get the edit role page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_role.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
