<?php
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/web_access_permissions.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
