<?php
// get the home page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/welcome.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
