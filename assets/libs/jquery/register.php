<?php
/**
 * Register jQuery library
 * Подключаем библиотеку jQuery
 *
 * User: tonatos
 * Date: 28.02.13
 * Time: 2:43
 */

if(!defined('MODX_BASE_PATH')) {die('What are you doing? Get out of here!');}

//Library version. версия подключаемой библиотеки
$version = isset($version) ? $version : '1.11.0';

//Mode. Режим подключения
$mode = isset($mode) ? $mode : 'google';

switch($mode){

    case 'local':
        $url = "assets/libs/jquery/jquery-$version.min.js";
        break;

    case 'remote':
        $url = "//code.jquery.com/jquery-$version.min.js";
        break;

    case 'google':
    default:
        $url = "//ajax.googleapis.com/ajax/libs/jquery/$version/jquery.min.js";
}

global $modx;

$modx->regClientStartupScript($url);
$modx->regClientStartupScript('assets/libs/jquery/jquery.plugins.min.js');
