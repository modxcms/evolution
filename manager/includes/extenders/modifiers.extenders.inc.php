<?php
/**
 * MODIFIERS Extension config file
 * Date: 2016-05-10
 * Time: 10:00
 */

if (!include_once MODX_MANAGER_PATH . 'includes/extenders/modifiers.class.inc.php'){
    return false;
}

$this->filter= new MODIFIERS;
return true;
