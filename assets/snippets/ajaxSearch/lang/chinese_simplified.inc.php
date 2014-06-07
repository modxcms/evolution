<?php
    $filename = dirname(__FILE__) . '/chinese_simplified-utf8.inc.php';
    $contents = file_get_contents($filename);
    $contents = mb_convert_encoding($contents, 'EUC-CN', 'UTF-8');
    eval('?>' . $contents);
