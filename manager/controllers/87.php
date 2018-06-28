<?php
// get the new web user page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_web_user.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
