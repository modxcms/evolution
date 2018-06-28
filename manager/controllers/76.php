<?php
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/resources.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
