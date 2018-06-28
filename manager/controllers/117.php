<?php
// change the tv rank for selected template
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/mutate_template_tv_rank.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
