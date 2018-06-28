<?php
// get the page to manage files
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/files.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
