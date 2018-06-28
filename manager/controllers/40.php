<?php
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/access_permissions.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
