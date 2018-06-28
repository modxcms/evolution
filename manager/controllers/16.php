<?php
// get the edit template action
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_templates.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
