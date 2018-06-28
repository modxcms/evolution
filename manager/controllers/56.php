<?php
// get the sort menuindex action
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_menuindex_sort.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
