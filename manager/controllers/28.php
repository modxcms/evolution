<?php
// get the change password page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_password.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
