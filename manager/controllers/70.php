<?php
// get the schedule page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/site_schedule.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
