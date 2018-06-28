<?php
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/export_site.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
