<?php
    $filename = dirname(__FILE__) . '/svenska-utf8_country.inc.php';
    $contents = file_get_contents($filename);
    $contents = mb_convert_encoding($contents, $modx_charset, 'UTF-8');
    eval('?>' . $contents);
?>