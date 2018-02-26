<?php
/**
 * ManagerAPI Extension config file
 * Date: 01.10.13
 * Time: 14:16
 */

if (!include_once MODX_MANAGER_PATH . 'includes/extenders/manager.api.class.inc.php') {
    return false;
} else {
    $this->manager = new ManagerAPI;

    return true;
}
