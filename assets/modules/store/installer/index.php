<?php
error_reporting(E_ALL & ~E_NOTICE);

define('MODX_BASE_PATH',realpath('../../../../').'/');
include_once(MODX_BASE_PATH."assets/cache/siteManager.php");
define('MGR',MODX_BASE_PATH.MGR_DIR);



define('MODX_API_MODE', true);
include_once MGR.'/includes/config.inc.php';
include_once MGR.'/includes/document.parser.class.inc.php';
$modx = new DocumentParser;
$modx->db->connect();
$modx->getSettings();
startCMSSession();
$modx->minParserPasses=2;

if(IN_MANAGER_MODE!='true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');




if (version_compare(phpversion(), "5.3") < 0) {
    @ ini_set('magic_quotes_runtime', 0);
    @ ini_set('magic_quotes_sybase', 0);
}
$moduleurl = $modx->config['site_url'].'assets/modules/store/installer/index.php';
$modulePath = MODX_BASE_PATH.'assets/modules/store/installer/';
$self = $modulePath.'/index.php';
require_once($modulePath."/functions.php");

$_lang = array();
$_params = array();
$lang = $modx->config['manager_language'];
if (file_exists($modulePath.'/lang/'.$lang.'.inc.php')){
	include_once($modulePath.'/lang/'.$lang.'.inc.php');

} else {
	include_once($modulePath.'/lang/english.inc.php');
}
include_once(MODX_BASE_PATH."assets/cache/siteManager.php");
require_once(MGR.'/includes/version.inc.php');

$_SESSION['test'] = 1;
install_sessionCheck();

$moduleName = "MODX";
$moduleVersion = $modx_branch.' '.$modx_version;
$moduleRelease = $modx_release_date;
$moduleSQLBaseFile = "setup.sql";
$moduleSQLDataFile = "setup.data.sql";

$moduleChunks = array (); // chunks - array : name, description, type - 0:file or 1:content, file or content
$moduleTemplates = array (); // templates - array : name, description, type - 0:file or 1:content, file or content
$moduleSnippets = array (); // snippets - array : name, description, type - 0:file or 1:content, file or content,properties
$modulePlugins = array (); // plugins - array : name, description, type - 0:file or 1:content, file or content,properties, events,guid
$moduleModules = array (); // modules - array : name, description, type - 0:file or 1:content, file or content,properties, guid
$moduleTemplates = array (); // templates - array : name, description, type - 0:file or 1:content, file or content,properties
$moduleTVs = array (); // template variables - array : name, description, type - 0:file or 1:content, file or content,properties

$errors= 0;

// get post back status
$isPostBack = (count($_POST));
$action= isset ($_GET['action']) ? trim(strip_tags($_GET['action'])) : 'load';

ob_start();
echo '<!DOCTYPE html>
<html><head><title>Install</title>
<meta http-equiv="Content-Type" content="text/html; charset="utf-8" />
<link rel="stylesheet" href="'.$modx->config['site_url'].'assets/modules/store/installer/style.css" type="text/css" media="screen" /></head>
<body><div id="contentarea"><div class="container_12"><br>';


if (!@include ($modulePath.'/action.' . $action . '.php')) {
    die ('Invalid install action attempted. [action=' . $action . ']');
}

echo "</div><!-- // content --></div><!-- // contentarea --><br /></body></html>";
ob_end_flush();

?>
