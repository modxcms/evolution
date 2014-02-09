<?php
    $filename = dirname(__FILE__) . '/estonian-utf8.inc.php';
    $contents = file_get_contents($filename);
    $contents = mb_convert_encoding($contents, 'iso-8859-4', 'UTF-8');
    eval('?>' . $contents);
