<?php
// get the settings editor
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/sysinfo.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
