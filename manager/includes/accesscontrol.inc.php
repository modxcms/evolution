<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}

if ($modx->get('ManagerTheme')->isAuthManager() === false) {
    echo $modx->get('ManagerTheme')->renderLoginPage();
    exit;
}
