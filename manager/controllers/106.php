<?php
// get module management
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/modules.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
