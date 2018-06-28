<?php
// get the cache emptying processor
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/refresh_site.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
