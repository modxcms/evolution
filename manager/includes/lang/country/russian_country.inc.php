<?php
    $filename = dirname(__FILE__) . '/russian-UTF8_country.inc.php';
    $contents = file_get_contents($filename);
    $contents = mb_convert_encoding($contents, $modx_charset, 'UTF-8');
    eval('?>' . $contents);
?>