<?php
global $tinyLang;
$tinyLang = array();

$tinyLang[] = array("czech","cs");
$tinyLang[] = array("danish","da");
$tinyLang[] = array("english","en");
$tinyLang[] = array("english-british","en");
$tinyLang[] = array("finnish","fi");
$tinyLang[] = array("francais","fr");
$tinyLang[] = array("francais-utf8","fr");
$tinyLang[] = array("german","de");
$tinyLang[] = array("italian","it");
$tinyLang[] = array("japanese-utf8","ja");
$tinyLang[] = array("nederlands","nl");
$tinyLang[] = array("norsk","nn");
$tinyLang[] = array("persian","fa");
$tinyLang[] = array("polish","pl");
$tinyLang[] = array("portuguese","pt");
$tinyLang[] = array("russian","ru");
$tinyLang[] = array("russian-UTF8","ru");
$tinyLang[] = array("simple_chinese-gb2312","zh");
$tinyLang[] = array("spanish","es");
$tinyLang[] = array("svenska","sv");
$tinyLang[] = array("svenska-utf8","sv");

global $tinyLangCount;
$tinyLangCount = count($tinyLang);

if (!function_exists('getTinyMCELang')) {
	function getTinyMCELang($lang){
		global $tinyLang;
		global $tinyLangCount;
		$langSel = 'en';
		for ($i=0;$i<$tinyLangCount;$i++) {
			if($tinyLang[$i][0] == $lang){
				$langSel = $tinyLang[$i][1];
			}
		}
		return $langSel;
	}
}
?>