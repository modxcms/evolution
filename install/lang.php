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

$manager_language = "english";

if (isset($_POST['managerlanguage'])) {
	$manager_language = $_POST['managerlanguage'];
} else {
	if (isset($_GET['managerlanguage'])) 
		$manager_language = $_GET['managerlanguage'];
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