<?php
/** ---------------------------------------------------------------------------
* Snippet: AjaxSearch
* -----------------------------------------------------------------------------
* ajaxSearchPopup.php
*
* @author       Coroico - www.modx.wangba.fr
* @version      1.9.0
* @date         18/05/2010
*
*/

if (isset($_POST['search'])) {

    define('AS_VERSION', '1.9.0');
    define('AS_SPATH', 'assets/snippets/ajaxSearch/');
    define('AS_PATH', MODX_BASE_PATH . AS_SPATH);

    require_once (MODX_MANAGER_PATH . '/includes/protect.inc.php');
    if (!isset($_POST['as_version']) || ($_POST['as_version'] != AS_VERSION)) {
        $output = "AjaxSearch version obsolete. <br />Please check the snippet code in MODx manager.";
    }
    else {
        include_once AS_PATH . "classes/ajaxSearch.class.inc.php";

        define('MODX_API_MODE', true);
        include_once (MODX_MANAGER_PATH . '/includes/document.parser.class.inc.php');
        $modx = new DocumentParser;
        $modx->db->connect();
        $modx->getSettings();
        startCMSSession();

        $tstart = $modx->getMicroTime();
        $default = AS_PATH . 'configs/default.config.php';
        if (file_exists($default)) include $default;
        else return "<h3>AjaxSearch error: $default not found !<br />Check the existing of this file!</h3>";
        if (!isset($dcfg)) return "<h3>AjaxSearch error: default configuration array not defined in $default!<br /> Check the content of this file!</h3>";
        $ucfg = parseUserConfig($_POST['ucfg']);
        // Load the custom functions of the custom configuration file if needed
        if ($ucfg['config']) {
            $config = $ucfg['config'];
            $lconfig = (substr($config, 0, 5) != "@FILE") ? AS_PATH . "configs/$config.config.php" : $modx->config['base_path'] . trim(substr($config, 5));
            if (file_exists($lconfig)) include $lconfig;
            else return "<h3>AjaxSearch error: " . $lconfig . " not found !<br />Check your config parameter or your config file name!</h3>";
        }
        $as = new AjaxSearch();
        $output = $as->run($tstart, $dcfg);
    }
    echo $output;
}
/*!
* parseUserConfig : parse the non default configuration from string
*/
function parseUserConfig($strUcfg) {
    $ucfg = array();
    $pattern = '/&([^=]*)=`([^`]*)`/';
    preg_match_all($pattern, $strUcfg, $out);
    foreach ($out[1] as $key => $values) $ucfg[$out[1][$key]] = $out[2][$key];
    return $ucfg;
}
?>