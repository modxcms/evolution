<?php
/**
 * ElementsInTree
 *
 * Get access to all Elements and Modules inside Manager sidebar
 *
 */

if(!defined('MODX_BASE_PATH')) die('What are you doing? Get out of here!');

$inc_path = str_replace('\\','/',dirname(__FILE__)) . '/includes/';
include_once($inc_path.'functions.inc.php');

global $_lang;

$e = &$modx->event;

if(!isset($_SESSION['elementsInTree'])) $_SESSION['elementsInTree'] = array();

switch($e->name) {
    case 'OnManagerMainFrameHeaderHTMLBlock': // Trigger reloading tree for relevant actions
        include_once($inc_path.'on_manager_main_frame_header_html_block.inc.php'); break;
    case 'OnManagerTreePrerender': // Main elementsInTree-part
        include_once($inc_path.'on_manager_tree_prerender.inc.php'); break;
    case 'OnManagerTreeRender':
        if(hasAnyPermission()) include_once($inc_path.'on_manager_tree_render.inc.php'); break;
    case 'OnTempFormSave':
    case 'OnTVFormSave':
    case 'OnChunkFormSave':
    case 'OnSnipFormSave':
    case 'OnPluginFormSave':
    case 'OnModFormSave':
    case 'OnTempFormDelete':
    case 'OnTVFormDelete':
    case 'OnChunkFormDelete':
    case 'OnSnipFormDelete':
    case 'OnPluginFormDelete':
    case 'OnModFormDelete':
// Set reloadTree = true for this events
        $_SESSION['elementsInTree']['reloadTree'] = true; break;
    default:
        if($_GET['r'] == 2) $_SESSION['elementsInTree']['reloadTree'] = true;
        return;
}
return;