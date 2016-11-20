<?php
/**
 *  Tree Nodes
 *  Build and return document tree view nodes
 *
 */
if(IN_MANAGER_MODE!='true') die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');

include_once('nodes.functions.inc.php');

    // save folderstate
    if (isset($_GET['opened']))        $_SESSION['openedArray'] = $_GET['opened'];
    if (isset($_GET['savestateonly'])) exit('send some data'); //??

    $indent    = intval($_GET['indent']);
    $parent    = intval($_GET['parent']);
    $expandAll = intval($_GET['expandAll']);
    $output    = '';
    $theme = "{$manager_theme}/";

    // setup sorting
    $sortParams = array('tree_sortby','tree_sortdir','tree_nodename');
    foreach($sortParams as $param) {
        if(isset($_REQUEST[$param])) {
            $_SESSION[$param] = $_REQUEST[$param];
            $modx->manager->saveLastUserSetting($param, $_REQUEST[$param]);
        }
    }

    // icons by content type
    $icons        = getIconInfo($_style);
    $iconsPrivate = getPrivateIconInfo($_style);

    if (isset($_SESSION['openedArray'])) $opened = array_filter(array_map('intval', explode('|', $_SESSION['openedArray'])));
    else                                 $opened = array();
    
    $opened2 = array();
    $closed2 = array();

    makeHTML($indent,$parent,$expandAll,$theme);
    echo $output;

    // check for deleted documents on reload
    if ($expandAll==2) {
        $rs = $modx->db->select('COUNT(*)', $modx->getFullTableName('site_content'), 'deleted=1');
        $count = $modx->db->getValue($rs);
        if ($count>0) echo '<span id="binFull"></span>'; // add a special element to let system now that the bin is full
    }
