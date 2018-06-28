<?php
// get the move action
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/move_document.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
