<?php
/**
 * DBAPI Extension config file
 * Date: 01.10.13
 * Time: 13:32
 */

global $database_type;

if (empty($database_type)) $database_type = 'mysql';

if (!include_once MODX_MANAGER_PATH . 'includes/extenders/dbapi.' . $database_type . '.class.inc.php'){
    return false;
}else{
    $this->db= new DBAPI;
    return true;
}
