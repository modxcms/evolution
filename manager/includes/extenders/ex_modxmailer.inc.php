<?php
/**
 * MODxMailer Extension config file.
 * User: tonatos
 * Date: 01.10.13
 * Time: 14:17
 */

if (!include_once(MODX_MANAGER_PATH . 'includes/extenders/modxmailer.class.inc.php')){
    return false;
}else{
    $this->mail = new MODxMailer;
    return true;
}