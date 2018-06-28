<?php
// get event log details viewer
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/eventlog_details.dynamic.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
