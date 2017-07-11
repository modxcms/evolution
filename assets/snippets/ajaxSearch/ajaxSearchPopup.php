<?php
/** ---------------------------------------------------------------------------
* Snippet: AjaxSearch
* -----------------------------------------------------------------------------
* ajaxSearchPopup.php
*
* @author       Coroico - www.evo.wangba.fr
* @version      1.11.0
* @date         12/04/2016
*
*/

/*!
* getUserConfigName : parse the non default configuration file name from ucfg string
*/


function getUserConfigName($ucfg) {
    preg_match('/&config=`([^`]*)`/', $ucfg, $matches);
    return $matches[1];
}

define('MODX_API_MODE', true);
include_once(__DIR__."/../../../index.php");
$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') || strpos($_SERVER['HTTP_REFERER'],$modx->config['site_url']) !== 0){
    $modx->sendErrorPage();
}

if (isset($_POST['search'])) {

    define('AS_VERSION', '1.11.0');
    define('AS_SPATH', 'assets/snippets/ajaxSearch/');
    define('AS_PATH', MODX_BASE_PATH . AS_SPATH);

    if (!isset($_POST['as_version']) || (strip_tags($_POST['as_version']) != AS_VERSION)) {
        $output = "AjaxSearch version obsolete. <br />Please check the snippet code in MODX manager.";
    }
    else {
        include_once AS_PATH . "classes/ajaxSearch.class.inc.php";
        $tstart = $modx->getMicroTime();
        $default = AS_PATH . 'configs/default.config.php';
        if (file_exists($default)) include $default;
        else return "<h3>AjaxSearch error: $default not found !<br />Check the existing of this file!</h3>";
        if (!isset($dcfg)) return "<h3>AjaxSearch error: default configuration array not defined in $default!<br /> Check the content of this file!</h3>";
        $config = getUserConfigName((strip_tags($_POST['ucfg'])));

        // Load the custom functions of the custom configuration file if needed
		if ($config) {
			if (substr($config, 0, 6) != "@FILE:") {
				// remove all not alphanumeric chars exept underscore and minus in the filename
				$config = preg_replace('/[^a-zA-Z0-9_-]/i','', $config);
				$lconfig = AS_PATH . "configs/{$config}.config.php";
				if (file_exists($lconfig)) {
					include $lconfig;
				} else {
					return "<h3>AjaxSearch error: " . $lconfig . " not found !<br />Check your config parameter or your config file name!</h3>";
				}
			} else {
				return "<h3>AjaxSearch error: @FILE: prefix not allowed !<br />Check your config parameter or your config file name!</h3>";
			}
		}
		if ($dcfg['version'] != AS_VERSION) return "<h3>AjaxSearch error: Version number mismatch. Check the content of the default configuration file!</h3>";
        $as = new AjaxSearch();
        $output = $as->run($tstart, $dcfg);
        header("Content-type: text/html; charset=".$modx->getConfig('modx_charset'));
    }
    echo $output;
}
