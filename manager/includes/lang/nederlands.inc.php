<?php
    $filename = dirname(__FILE__) . '/nederlands-utf8.inc.php';
    $contents = file_get_contents($filename);
    $contents = utf8_decode($contents);
    eval('?>' . $contents);
    $modx_lang_attribute = 'nl';
    $modx_manager_charset = 'iso-8859-1';
?>