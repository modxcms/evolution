<?php
//Kill them all
$_GET = $_POST = $_REQUEST = array();
define('IN_MANAGER_MODE', true);
//Setup the MODx API
define('MODX_API_MODE', true);

//Root dir
$richtextIncludeDirectory = '../../../../../../';

include_once ($richtextIncludeDirectory.'index.php');
//Define MGR_DIR
if (!defined('MGR_DIR')){define('MGR_DIR', 'manager');}

$richtextIncludeDirectory .= MGR_DIR.'/';

//Config
$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'] = '/';
$modx->db->connect();
$modx->getSettings();
$modx->invokeEvent('OnManagerPageInit');
if ($_SESSION['mgrValidated']){

	$mmDir = 'assets/plugins/managermanager/';
	$windowDir = $mmDir.'widgets/ddmultiplefields/richtext/';
	
	//Include the ddTools library
	require_once($modx->config['base_path'].$mmDir.'modx.ddtools.class.php');
	
	$temp = $modx->invokeEvent('OnRichTextEditorInit', array(
		'editor' => $modx->config['which_editor'],
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
