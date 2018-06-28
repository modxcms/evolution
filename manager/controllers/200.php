<?php
// show phpInfo
if($modx->hasPermission('logs')) {
    echo $modx->get('ManagerTheme')->view('header')->render();
    include_once(includeFileProcessor("actions/phpinfo.static.php",$manager_theme));
    echo $modx->get('ManagerTheme')->view('footer')->render();
}
