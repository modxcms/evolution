<?php
    $filename = dirname(__FILE__) . '/chinese.inc.php';
    $contents = file_get_contents($filename);
    $contents = mb_convert_encoding($contents, 'EUC-CN', 'UTF-8');
    eval('?>' . $contents);
    $modx_lang_attribute = 'zh'; // Manager HTML and XML Language Attribute
    $modx_manager_charset = 'gb2312';
?>