<?php
// get the help page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/help.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
