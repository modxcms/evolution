<?php
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/user_management.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
