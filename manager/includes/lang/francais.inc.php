<?php
    $filename = dirname(__FILE__) . '/francais-utf8.inc.php';
    $contents = file_get_contents($filename);
    $contents = utf8_decode($contents);
    eval('?>' . $contents);
    $modx_lang_attribute = 'fr'; // Manager HTML and XML Language Attribute
    $modx_manager_charset = 'iso-8859-1';
?>