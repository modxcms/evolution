<?php
/** ---------------------------------------------------------------------------
* Snippet: AjaxSearch
* -----------------------------------------------------------------------------
* ajaxSearchPopup.php
*
* @author       Coroico - www.evo.wangba.fr
* @version      1.10.1
* @date         05/06/2014
*
*/

/*!
* getUserConfigName : parse the non default configuration file name from ucfg string
*/
function getUserConfigName($ucfg) {
    preg_match('/&config=`([^`]*)`/', $ucfg, $matches);
    return $matches[1];
}

if (isset($_POST['search'])) {

    define('AS_VERSION', '1.10.1');
    define('AS_SPATH', 'assets/snippets/ajaxSearch/');
    define('AS_PATH', MODX_BASE_PATH . AS_SPATH);

    require_once (MODX_MANAGER_PATH . 'includes/protect.inc.php');
    if (!isset($_POST['as_version']) || (strip_tags($_POST['as_version']) != AS_VERSION)) {
        $output = "AjaxSearch version obsolete. <br />Please check the snippet code in MODX manager.";
    }
    else {
        include_once AS_PATH . "classes/ajaxSearch.class.inc.php";

        define('MODX_API_MODE', true);
        include_once (MODX_MANAGER_PATH . 'includes/document.parser.class.inc.php');
        $modx = new DocumentParser;
        $modx->db->connect();
        $modx->getSettings();
        startCMSSession();

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
