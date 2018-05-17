<?php
/**
 * MODIFIERS Extension config file
 * Date: 2016-07-07
 * Time: 10:00
 */

if (isset($this->filter) && is_object($this->filter)) {
    return true;
}

include_once(MODX_MANAGER_PATH . 'includes/extenders/modifiers.class.inc.php');
$this->filter = new MODIFIERS;
return true;
