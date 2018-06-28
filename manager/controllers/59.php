<?php
// get the about page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/about.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
