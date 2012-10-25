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

if (isset($_POST['language'])) {
	$install_language = $_POST['language'];
} elseif (isset($_GET['language'])) {
	$install_language = $_GET['language'];
} else {
	$install_language = 'english';
}	

// Only allow alphanumeric characters, dashes '-' and underscores (minor security issue)
$install_language = preg_replace('/[^a-z0-9-_]+/i', '', $install_language);

if (isset($_POST['managerlanguage'])) {
	$manager_language = $_POST['managerlanguage'];
} elseif (isset($_GET['managerlanguage'])) {
	$manager_language = $_GET['managerlanguage'];
} else {
	$manager_language = 'english';
}

// Only allow alphanumeric characters, dashes '-' and underscores (minor security issue)
$manager_language = preg_replace('/[^a-z0-9-_]+/i', '', $manager_language);

// load language file(s)
// ---------------------

// Default fallback language file - english
// This populates the $_lang array - subsequent includes need then only overwrite subsets of the language data.
require_once('lang/english.inc.php');

if($install_language != 'english' && file_exists('lang/'.$install_language.'.inc.php')) {
	include_once('lang/'.$install_language.'.inc.php');
}

