<?php
    $filename = dirname(__FILE__) . '/chinese.inc.php';
    $contents = file_get_contents($filename);
    eval('?>' . $contents);
    $modx_lang_attribute = 'zh'; // Manager HTML/XML Language Attribute see http://en.wikipedia.org/wiki/ISO_639-1
    $modx_manager_charset = 'UTF-8';
