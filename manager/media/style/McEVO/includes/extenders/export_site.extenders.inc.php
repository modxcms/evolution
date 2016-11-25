<?php
/**
 * EXPORT_SITE Extension config file
 * Date: 01.10.13
 * Time: 14:24
 */

if(include_once(MODX_MANAGER_PATH . 'includes/extenders/export.class.inc.php'))
{
    $this->export = new EXPORT_SITE;
    return true;
} else {
    return false;
}