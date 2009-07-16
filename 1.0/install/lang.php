<?php

/**
 * Multilanguage functions for MODx Installer
 *
 * @author davaeron
 * @package MODx
 * @version 1.0
 * 
 * Filename:       /install/lang.php
 */

$_lang = array ();

#default fallback language file - english
require_once("lang/english/english.inc.php");

$install_language = "english";

if (isset($_POST['language'])) {
	$install_language = $_POST['language'];
} else {
	if (isset($_GET['language'])) 
		$install_language = $_GET['language'];
}


# load language file
if($install_language!="english" && file_exists("lang/".$install_language."/".$install_language.".inc.php")) {
    include_once "lang/".$install_language."/".$install_language.".inc.php";
}
/**
 * Multilanguage Image include function with fallback
 *
 */
function include_image ($image) {
	global $install_language;
	$result = "lang/english/images/" . $image;
	if($install_language!="english" && file_exists("lang/" . $install_language . "/images/" . $image)) {
    	$result = "lang/" . $install_language . "/images/" . $image;
	} else {
    	$result = "lang/english/images/" . $image;
	}
	return $result;
}
?>