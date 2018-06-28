<?php
// get the wait page (so the tree can reload)
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/wait.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
