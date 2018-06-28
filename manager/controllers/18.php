<?php
// get the credits page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/credits.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
