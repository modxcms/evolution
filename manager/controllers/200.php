<?php
// show phpInfo
if($modx->hasPermission('logs')) {
    include_once(includeFileProcessor("includes/header.inc.php",$manager_theme));
    include_once(includeFileProcessor("actions/phpinfo.static.php",$manager_theme));
    include_once(includeFileProcessor("includes/footer.inc.php",$manager_theme));
}
