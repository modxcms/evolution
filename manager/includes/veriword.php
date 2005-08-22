<?php


include_once("config.inc.php");
include("captchaClass.php");

$vword = new VeriWord(148,60);
$vword->output_image();
$vword->destroy_image();
?>