<?php
/**
 * DBAPI Extension config file
 * Date: 01.10.13
 * Time: 13:32
 */

global $database_type;

if (empty($database_type)) $database_type = 'mysqli';

$out = false;
$class = 'DBAPI';
if( ! class_exists($class)){
    include_once MODX_MANAGER_PATH . 'includes/extenders/dbapi.' . $database_type . '.class.inc.php';
}

if(class_exists($class)){
    $this->db= new $class;
    $out = true;
}

return $out;
