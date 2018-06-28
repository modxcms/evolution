<?php
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/import_site.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
