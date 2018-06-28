<?php
// get the mutate page for adding a folder
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_content.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
