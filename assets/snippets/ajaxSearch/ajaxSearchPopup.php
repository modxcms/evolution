<?php
/*
 * ajaxSearchPopup.php
 * Version: 1.8.4 - refactored by coroico (coroico@wangba.fr)
 *
 * Created by: KyleJ (kjaebker@muddydogpaws.com)
 * Created on: 01/03/2007
 *
 * Description: This code is called from the ajax request. It returns the search results.
 *
 * 29/03/2009 - mootools1.2, jquery, maxWords, mbstring parameters, search logs
 * 02/10/2008 - whereSearch, withTvs, new sql query, debug, subSearch
 * 24/07/2008 - Added rank, order & filter, breadcrumbs, tvPhx, cleardefault parameters
 * O2/07/2008 - New extract algorithm, search in tv, jot and maxygallery
 * O2/07/2008 - Added Phx templating & chunk parameters
 * 06/03/2008 - Added Hidden from menu and advanced search
 * 01/02/2008 - Added several fixes and a security patch
 * 17/11/2007 - Added IDs document selection
 * 06/11/2007 - Encoding troubles corrected
 *
 * 01/22/07 - Added templating/language/mootools support
 * 01/03/07 - Added fixes/updates from forum
 * 09/18/06 - Added user permissions to searching
 * 03/20/06 - All variables are set in the main snippet & snippet call
*/

if ($_POST['search']) {

  define('AS_VERSION' , '1.8.4');
  define ('AS_SPATH' , 'assets/snippets/ajaxSearch/');
  define('AS_PATH' , MODX_BASE_PATH . AS_SPATH);

  require_once(MODX_MANAGER_PATH . '/includes/protect.inc.php');

  if (!isset($_POST['as_version']) || ($_POST['as_version'] != AS_VERSION )) {
    $output = "AjaxSearch version obsolete. <br />Please check the snippet code in MODx manager.";
  }
  else {
    // include the ajaxSearchPopup class
    include_once AS_PATH."classes/ajaxSearchPopup.class.inc.php";

    // Setup the MODx API
    define('MODX_API_MODE', true);
    // initiate a new document parser
    include_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');
    $modx = new DocumentParser;

    $modx->db->connect();
    $modx->getSettings();

    // Load the default configuration $dcfg to get the default values
    $as_default = AS_PATH . 'configs/default.config.php';
    if (file_exists($as_default)) include $as_default;
    else return  "<h3> $as_default not found !<br />Check the existing of this file!</h3>";
    if (!isset($dcfg)) return  "<h3> default configuration array not defined in $as_default!<br /> Check the content of this file!</h3>";

    // get the user configuration string
    $ucfg = $_POST['ucfg'];

    $asp = new ajaxSearchPopup(AS_VERSION,$dcfg,$ucfg);
    $output = $asp->run();
  }

  echo $output;

}
?>
