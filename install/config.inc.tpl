<?php
/**
 * MODX Configuration file
 */
$database_type     = 'mysqli';
$database_server   = '[+database_server+]';
$database_user     = '[+user_name+]';
$database_password = '[+password+]';
$database_connection_charset = '[+connection_charset+]';
$database_connection_method = '[+connection_method+]';
$dbase             = '`[+dbase+]`';
$table_prefix      = '[+table_prefix+]';

$lastInstallTime = '[+lastInstallTime+]';

$https_port = '443';

if(!defined('MGR_DIR')) define('MGR_DIR', 'manager');

// automatically assign base_path and base_url
if(empty($base_path)||empty($base_url)||$_REQUEST['base_path']||$_REQUEST['base_url']) {
    $sapi= 'undefined';
    if (!strstr($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME']) && ($sapi= @ php_sapi_name()) == 'cgi') {
        $script_name= $_SERVER['PHP_SELF'];
    } else {
        $script_name= $_SERVER['SCRIPT_NAME'];
    }
    $script_name = str_replace('\\', '/', dirname($script_name));
    if(strpos($script_name,MGR_DIR)!==false)
        $separator = MGR_DIR;
    elseif(strpos($script_name,'/assets/')!==false)
        $separator = 'assets';
    else $separator = '';

    if($separator!=='') $a= explode('/'.$separator, $script_name);
    else $a = array($script_name);

    if (count($a) > 1)
        array_pop($a);
    $url= implode($separator, $a);
    reset($a);
    $a= explode(MGR_DIR, str_replace('\\', '/', dirname(__FILE__)));
    if (count($a) > 1)
        array_pop($a);
    $pth= implode(MGR_DIR, $a);
    unset ($a);
    $base_url= $url . (substr($url, -1) != '/' ? '/' : '');
    $base_path= $pth . (substr($pth, -1) != '/' && substr($pth, -1) != '\\' ? '/' : '');
}

// check for valid hostnames
$site_hostname = str_replace(':' . $_SERVER['SERVER_PORT'], '', $_SERVER['HTTP_HOST']);
if (!defined('MODX_SITE_HOSTNAMES')) {
	$site_hostnames_path = $base_path . 'assets/cache/siteHostnames.php';
	if (is_file($site_hostnames_path)) {
		include_once($site_hostnames_path);
	} else {
		define('MODX_SITE_HOSTNAMES', '');
	}
}
$site_hostnames = explode(',', MODX_SITE_HOSTNAMES);
if (!empty($site_hostnames[0]) && !in_array($site_hostname, $site_hostnames)) {
    $site_hostname = $site_hostnames[0];
}

// assign site_url
$secured = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https');
$site_url= ((isset ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || $_SERVER['SERVER_PORT'] == $https_port || $secured ) ? 'https://' : 'http://';
$site_url .= $site_hostname;
if ($_SERVER['SERVER_PORT'] != 80)
    $site_url= str_replace(':' . $_SERVER['SERVER_PORT'], '', $site_url); // remove port from HTTP_HOST  
$site_url .= ($_SERVER['SERVER_PORT'] == 80 || (isset ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || $_SERVER['SERVER_PORT'] == $https_port) ? '' : ':' . $_SERVER['SERVER_PORT'];
$site_url .= $base_url;

if (!defined('MODX_BASE_PATH')) define('MODX_BASE_PATH', $base_path);
if (!defined('MODX_BASE_URL')) define('MODX_BASE_URL', $base_url);
if (!defined('MODX_SITE_URL')) define('MODX_SITE_URL', $site_url);
if (!defined('MODX_MANAGER_PATH')) define('MODX_MANAGER_PATH', $base_path.MGR_DIR.'/');
if (!defined('MODX_MANAGER_URL')) define('MODX_MANAGER_URL', $site_url.MGR_DIR.'/');

include_once(MODX_MANAGER_PATH.'includes/preload.functions.inc.php');
