<?php
// view logging
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/logging.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
