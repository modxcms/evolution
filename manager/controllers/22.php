<?php
// get the edit snippet action
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_snippet.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
