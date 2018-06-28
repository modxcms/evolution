<?php
// get the tv-rank action
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_tv_rank.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
