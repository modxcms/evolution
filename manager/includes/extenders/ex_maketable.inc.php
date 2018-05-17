<?php
/**
 * MakeTable Extension config file
 * Date: 2016-10-10
 * Time: 14:38
 */

if (!include_once MODX_MANAGER_PATH . 'includes/extenders/maketable.class.php') {
    return false;
} else {
    $this->table = new MakeTable;

    return true;
}
