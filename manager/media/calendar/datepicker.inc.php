<?php
class DATEPICKER {
    function __construct() {
    }
    function getDP() {
        global $modx,$_lang;
        
        $tpl = file_get_contents(MODX_MANAGER_PATH . 'media/calendar/datepicker.tpl');
        return $modx->parseText($tpl,$_lang,'[%','%]');
    }
}
