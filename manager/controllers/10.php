<?php
// get the messages page
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/messages.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
