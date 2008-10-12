<?php
/*
AjaxSearch.php
Version: 1.8.1 - refactored by coroico (coroico@wangba.fr)

Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 01/03/2007

Description: This code is called from the ajax request. It returns the search results.

Updated: 02/10/2008 - whereSearch, withTvs, new sql query, subSearch
Updated: 18/07/2008 - Added whereSearch, rank, order & filter parameters
Updated: 02/07/2008 - Added Phx templating & chunk parameters
Updated: 06/03/2008 - Added Hidden from menu and advanced search
Updated: 01/02/2008 - Added several fixes and a security patch
Updated: 17/11/2007 - Added IDs document selection
Updated: 06/11/2007 - Encoding troubles corrected

Updated: 01/22/07 - Added templating/language/mootools support
Updated: 01/03/07 - Added fixes/updates from forum
Updated: 09/18/06 - Added user permissions to searching
Updated: 03/20/06 - All variables are set in the main snippet & snippet call
*/

if ($_POST['search']) {

  define('VERSION' , '1.8.1');
  define ('AS_SPATH' , 'assets/snippets/ajaxSearch/'); 
  define('AS_PATH' , MODX_BASE_PATH . AS_SPATH);

  require_once(MODX_MANAGER_PATH . '/includes/protect.inc.php');

  if (!isset($_POST['as_version']) || ($_POST['as_version'] != VERSION )) {
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

    // get the configuration parameters
    $cfg = array(
      'version' => VERSION,
      'debug' => $_POST['debug'],
      'config' => $_POST['config'],
      'language' => basename($_POST['as_language']),
      'ajaxSearch' => 1,
      'advSearch' => $_POST['advSearch'],
      'subSearch' => $_POST['subSearch'],
      'whereSearch' => urldecode($_POST['whereSearch']),
      'withTvs' => $_POST['withTvs'],
      'order' => $_POST['order'],
      'rank' => $_POST['rank'],
      'minChars' => $_POST['minChars'],
      'ajaxMax' => $_POST['ajaxMax'],
      'showMoreResults' => $_POST['showMoreResults'],
      'moreResultsPage' => $_POST['moreResultsPage'],
      'extract' => $_POST['extract'],
      'extractLength' => $_POST['extractLength'],
      'extractEllips' => $_POST['extractEllips'],
      'extractSeparator' => $_POST['extractSeparator'],
      'formatDate' => $_POST['formatDate'],
      'docgrp' => $_POST['docgrp'],
      'listIDs' => $_POST['listIDs'],
      'idType' => $_POST['idType'],
      'depth' => $_POST['depth'],
      'highlightResult' => $_POST['highlightResult'],
      'hideMenu' => $_POST['hideMenu'],
      'hideLink' => $_POST['hideLink'],
      'filter' => $_POST['as_filter'],
      'tplAjaxResults' => $_POST['tplAjaxResults'],
      'tplAjaxResult' => $_POST['tplAjaxResult'],
      'stripInput' => $_POST['stripInput'],
      'stripOutput' => $_POST['stripOutput'],
      'breadcrumbs' => $_POST['breadcrumbs'],
      'tvPhx' => $_POST['tvPhx']
    );

    $asp = new ajaxSearchPopup($cfg);
    $output = $asp->run();  
  }

  echo $output;

}
?>