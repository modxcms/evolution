<?php

class DATEPICKER
{
    public function __construct()
    {
    }

    public function getDP()
    {
        $modx = evolutionCMS();
        global $_lang;

        $tpl = file_get_contents(dirname(__FILE__) . '/datepicker.tpl');
        return $modx->parseText($tpl, $_lang, '[%', '%]');
    }
}
