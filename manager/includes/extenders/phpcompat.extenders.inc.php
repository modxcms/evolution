<?php
/**
 * phpcompat extenders
 * Date: 23.07.14
 * Time: 12:25
 */

if (is_object($this->phpcompat)){
    return true;
}

if (!include_once(MODX_MANAGER_PATH . 'includes/extenders/phpcompat.class.inc.php')){
    return false;
}else{
    $this->phpcompat = new PHPCOMPAT;
    return true;
}