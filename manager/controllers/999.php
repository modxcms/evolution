<?php
// get the test page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("test_page.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
