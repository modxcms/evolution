<?php
// get the search page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/search.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
