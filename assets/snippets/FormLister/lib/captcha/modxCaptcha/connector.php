<?php
define('MODX_API_MODE', true);

include_once(__DIR__."/../../../../../../index.php");
$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}
if(strstr($_SERVER['HTTP_REFERER'],$modx->getConfig('site_url')) === false || !isset($_REQUEST['formid'])) throw new Exception('Wrong captcha request');
$formid = (string) $_REQUEST['formid'];
include_once ('modxCaptcha.php');
$width = isset($_REQUEST['w']) ? (int) $_REQUEST['w'] : 200;
$height = isset($_REQUEST['h']) ? (int) $_REQUEST['h'] : 160;
$captcha = new ModxCaptcha($modx, $width, $height);
$_SESSION[$formid.'.captcha'] = $captcha->word;
$captcha->outputImage();
