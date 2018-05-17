<?php
/**
 * Группировка документов по первой букве
 *
 * [[DLFirstChar?
 *        &documents=`2,4,23,3`
 *    &idType=`documents`
 *        &tpl=`@CODE:[+CharSeparator+][+OnNewChar+]<span class="brand_name"><a href="[+url+]">[+pagetitle+]</a></span><br />`
 *        &tplOnNewChar=`@CODE:<div class="block"><strong class="bukva">[+char+]</strong> ([+total+])</div>`
 *      &tplCharSeparator=`@CODE:</div>`
 *      &orderBy=`BINARY pagetitle ASC`
 * ]]
 */
if (! defined('MODX_BASE_PATH')) {
    die('HACK???');
}

include_once(MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');
include_once(MODX_BASE_PATH . 'assets/snippets/DocLister/lib/DLFixedPrepare.class.php');

$p = &$modx->event->params;
if (! is_array($p)) {
    $p = array();
}

/**
 * Получение prepare сниппетов из параметров BeforePrepare и AfterPrepare
 * для совмещения с обязательным вызовом DLFixedPrepare::firstChar метода
 */
$prepare = \APIhelpers::getkey($p, 'BeforePrepare', '');
$prepare = explode(",", $prepare);
$prepare[] = 'DLFixedPrepare::firstChar';
$prepare[] = \APIhelpers::getkey($p, 'AfterPrepare', '');
$p['prepare'] = trim(implode(",", $prepare), ',');

return $modx->runSnippet('DocLister', $p);
