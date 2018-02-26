<?php
class DATEPICKER {
    function __construct() {
    }
    function getDP() {
        $modx = DocumentParser::getInstance(); global$_lang;

        $tpl = file_get_contents(dirname(__FILE__).'/datepicker.tpl');
        return $modx->parseText($tpl,$_lang,'[%','%]');
    }
}
