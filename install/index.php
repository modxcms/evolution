<?php
/**
 * MODx Installer for 0.9.6
 */
// do a little bit of environment cleanup if possible
@ ini_set('magic_quotes_runtime', 0);
@ ini_set('magic_quotes_sybase', 0);

// start session
session_start();

// set error reporting
error_reporting(E_ALL & ~E_NOTICE);

// session loop-back tester
if (!$_SESSION['session_test'] && $_GET['s'] != 'set') {
    $_SESSION['session_test'] = 1;
    $installBaseUrl = (!isset ($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) != 'on') ? 'http://' : 'https://';
    $installBaseUrl .= $_SERVER['HTTP_HOST'];
    if ($_SERVER['SERVER_PORT'] != 80)
        $installBaseUrl = str_replace(':' . $_SERVER['SERVER_PORT'], '', $installBaseUrl); // remove port from HTTP_HOST
    $installBaseUrl .= ($_SERVER['SERVER_PORT'] == 80 || isset ($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'on') ? '' : ':' . $_SERVER['SERVER_PORT'];
    echo "<html><head><title>Loading...</title><script>window.location.href='" . $installBaseUrl . $_SERVER['PHP_SELF'] . "?action=welcome';</script></head><body></body></html>";
    exit;
}

$moduleName = "MODx";
$moduleVersion = "0.9.6";
$moduleSQLBaseFile = "setup.sql";
$moduleSQLDataFile = "setup.data.sql";
$moduleSQLUpdateFile = "setup.updates.sql";

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

$action= isset ($_GET['action']) ? trim(strip_tags($_GET['action'])) : 'welcome';

// make sure they agree to the license
if (!in_array($action, array ('welcome', 'license'))) {
    if (!isset ($_POST['chkagree'])) $action= 'license';
}

include ('header.php');

if (!@include ('action.' . $action . '.php')) {
    die ('Invalid install action attempted. [action=' . $action . ']');
}

include ('footer.php');
?>