<?php
/**
 * EVO Installer
 */

$autoloader = realpath(__DIR__.'/../vendor/autoload.php');
if (file_exists($autoloader) && is_readable($autoloader)) {
    include_once($autoloader);
}

$self = 'install/index.php';
$base_path = str_replace($self,'',str_replace('\\','/', __FILE__));
require_once("{$base_path}install/functions.php");

// set error reporting
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

if (is_file("{$base_path}assets/cache/siteManager.php")) {
	include_once("{$base_path}assets/cache/siteManager.php");
}
if(!defined('MGR_DIR') && is_dir("{$base_path}manager")) {
	define('MGR_DIR', 'manager');
}


require_once("lang.php");
require_once('../'.MGR_DIR.'/includes/version.inc.php');
include_once('../'.MGR_DIR.'/includes/preload.functions.inc.php');

// start session
session_start();
$_SESSION['test'] = 1;
install_sessionCheck();

$moduleName = "EVO";
$moduleVersion = $modx_branch.' '.$modx_version;
$moduleRelease = $modx_release_date;
$moduleSQLBaseFile = "setup.sql";
$moduleSQLDataFile = "setup.data.sql";
$moduleSQLResetFile = "setup.data.reset.sql";

$moduleChunks = array (); // chunks - array : name, description, type - 0:file or 1:content, file or content
$moduleTemplates = array (); // templates - array : name, description, type - 0:file or 1:content, file or content
$moduleSnippets = array (); // snippets - array : name, description, type - 0:file or 1:content, file or content,properties
$modulePlugins = array (); // plugins - array : name, description, type - 0:file or 1:content, file or content,properties, events,guid
$moduleModules = array (); // modules - array : name, description, type - 0:file or 1:content, file or content,properties, guid
$moduleTemplates = array (); // templates - array : name, description, type - 0:file or 1:content, file or content,properties
$moduleTVs = array (); // template variables - array : name, description, type - 0:file or 1:content, file or content,properties
$moduleDependencies = array(); // module depedencies - array : module, table, column, type, name

$errors= 0;

// get post back status
$isPostBack = (count($_POST));

$ph = ph();
$ph = array_merge($ph,$_lang);
$ph['install_language'] = $install_language;

ob_start();
$action= isset ($_GET['action']) ? trim(strip_tags($_GET['action'])) : 'language';
if (!@include_once("actions/action_{$action}.php")) {
    die ("Invalid install action attempted. [action={$action}]");
}
$ph['content'] = ob_get_contents();
ob_end_clean();

$tpl = file_get_contents("{$base_path}install/template.tpl");
echo parse($tpl,$ph);

