<?php
// get the edit category page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_categories.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
