<?php
/**
 * MODx Installer
 */
// do a little bit of environment cleanup if possible
if (version_compare(phpversion(), "5.3") < 0) {
    @ ini_set('magic_quotes_runtime', 0);
    @ ini_set('magic_quotes_sybase', 0);
}

// start session
session_start();
$_SESSION['test'] = 1;

// set error reporting
error_reporting(E_ALL & ~E_NOTICE);

require_once("lang.php");
require_once('../manager/includes/version.inc.php');

// session loop-back tester
if (!$_SESSION['test']) {
    $installBaseUrl = (!isset ($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) != 'on') ? 'http://' : 'https://';
    $installBaseUrl .= $_SERVER['HTTP_HOST'];
    if ($_SERVER['SERVER_PORT'] != 80)
        $installBaseUrl = str_replace(':' . $_SERVER['SERVER_PORT'], '', $installBaseUrl); // remove port from HTTP_HOST
    $installBaseUrl .= ($_SERVER['SERVER_PORT'] == 80 || isset ($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? '' : ':' . $_SERVER['SERVER_PORT'];
	$retryURL = $installBaseUrl . $_SERVER['PHP_SELF'] . "?action=language";
    echo "
<html>
<head>
	<title>Install Problem</title>
	<style type=\"text/css\">
		*{margin:0;padding:0}
		body{margin:50px;background:#eee;}
		.install{padding:10px;border:5px solid #f22;background:#f99;margin:0 auto;font:120%/1em serif;text-align:center;}
		p{ margin:20px 0; }
		a{font-size:200%;color:#f22;text-decoration:underline;margin-top:30px;padding:5px;}
	</style>
</head>
<body>
	<div class=\"install\">
		<p>".$_lang["session_problem"]."</p>
		<p><a href=\"".$retryURL."\">".$_lang["session_problem_try_again"]."</a></p>
	</div>
</body>
</html>";
	    exit;

}

$moduleName = "MODx";
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

$action= isset ($_GET['action']) ? trim(strip_tags($_GET['action'])) : 'language';

// make sure they agree to the license
#if (!in_array($action, array ('language', 'welcome', 'connection', 'options', 'license', 'mode', 'summary'))) {
#    if (!isset ($_POST['chkagree'])) $action= 'license';
#}

ob_start();
include ('header.php');

if (!@include ('action.' . $action . '.php')) {
    die ('Invalid install action attempted. [action=' . $action . ']');
}

include ('footer.php');
ob_end_flush();
?>