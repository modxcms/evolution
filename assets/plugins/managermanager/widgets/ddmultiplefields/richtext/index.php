<?php
//Kill them all
$_GET = $_POST = $_REQUEST = array();
//Relative path for manager folder
define('MODX_MANAGER_PATH', $_SERVER['DOCUMENT_ROOT'].'/manager/');
//Config
$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'] = '/';
require_once(MODX_MANAGER_PATH.'/includes/config.inc.php');
startCMSSession();

if ($_SESSION['mgrValidated']){
	define('IN_MANAGER_MODE', true);
	require_once(MODX_MANAGER_PATH.'/includes/protect.inc.php');
	//Setup the MODx API
	define('MODX_API_MODE', true);
	//Initiate a new document parser
	require_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');
	$modx = new DocumentParser;
	
	//Provide the MODx DBAPI
	$modx->db->connect();
	//Provide the $modx->documentMap and user settings
	$modx->getSettings();
	
	$mmDir = 'assets/plugins/managermanager/';
	$windowDir = $mmDir.'widgets/ddmultiplefields/richtext/';
	
	//Include the ddTools library
	require_once($modx->config['base_path'].$mmDir.'modx.ddtools.class.php');
	
	$temp = $modx->invokeEvent('OnRichTextEditorInit', array(
		'editor' => 'TinyMCE',
		'elements' => array('ddMultipleFields_richtext')
	));
	
	echo ddTools::parseText(file_get_contents($modx->config['base_path'].$windowDir.'template.html'), array(
		'site_url' => $modx->config['site_url'],
		'mmDir' => $mmDir,
		'windowDir' => $windowDir,
		'charset' => '<meta charset="'.$modx->config['modx_charset'].'" />',
		'style' => MODX_MANAGER_URL.'media/style/'.$modx->config['manager_theme'].'/style.css',
		'tinyMCE' => $temp[0]
	), '[+', '+]', false);
}else{
	echo file_get_contents(dirname(__FILE__).'/index.html');
}
?>