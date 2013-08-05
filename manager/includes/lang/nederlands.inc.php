<?php
    $filename = dirname(__FILE__) . '/nederlands-utf8.inc.php';
    $contents = file_get_contents($filename);
    $contents = mb_convert_encoding($contents, $modx_charset, 'UTF-8');
    $contents = str_replace('UTF-8', $modx_charset, $contents);
    eval('?>' . $contents);
?>