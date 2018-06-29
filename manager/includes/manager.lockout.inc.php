<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
$action = isset($_REQUEST['a']) ? (int)$_REQUEST['a'] : 1;

if (8 !== $action && ManagerTheme::hasManagerAccess() === false) {
    echo ManagerTheme::renderAccessPage();
    exit;
}
