<?php
/**
 * phpass extenders
 * Date: 2016-11-20
 * Time: 19:14
 */

if (isset($this->phpass) && is_object($this->phpass)) {
    return true;
}

if (!include_once(MODX_MANAGER_PATH . 'includes/extenders/phpass.class.inc.php')) {
    return false;
} else {
    $this->phpass = new PasswordHash;

    return true;
}
