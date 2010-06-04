<?php
/**
 * Inherit Parent Template
 * 
 * Newly created Resources use the same template as their Parent or Sibling Containers
 *
 * @category    plugin
 * @version     1.1
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties &inheritTemplate=Inherit Template;list;From Parent,From First Sibling;From Parent
 * @internal    @events OnDocFormPrerender 
 * @internal    @modx_category Manager and Admin
 */
 
/*
 * Inherit Parent Permissions
 * Javier Arraiza // www.marker.es // 24/3/2008
 * Based in
 * Inherit Template from Parent
 * Written By Raymond Irving - 12 Oct 2006
 *
 * A code to inherit the parent permissions from parent document
 *
 * Configuration:
 * check the OnDocFormSave event
 *
 * Version 1.1
 *
 */

global $content;
$e = &$modx->Event;

switch($e->name) {
    case 'OnDocFormPrerender':        
        if ($inheritTemplate == 'From First Sibling') {
            if ($_REQUEST['pid'] > 0 && $id == 0) {
                if ($sibl = $modx->getDocumentChildren($_REQUEST['pid'], 1, 0, 'template', '', 'menuindex', 'ASC', 1)) {
                    $content['template'] = $sibl[0]['template'];
                } else if ($sibl = $modx->getDocumentChildren($_REQUEST['pid'], 0, 0, 'template', '', 'menuindex', 'ASC', 1)) {
                    $content['template'] = $sibl[0]['template'];
                } else if ($parent = $modx->getPageInfo($_REQUEST['pid'], 0, 'template')) {
                    $content['template'] = $parent['template'];
                }
            }
        } else {
             if ($parent = $modx->getPageInfo($_REQUEST['pid'],0,'template')) {
                 $content['template'] = $parent['template'];
             }
        }
        break;
    default:
        break;
}