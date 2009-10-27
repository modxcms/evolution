//<?php
/**
 * Inherit Parent Template
 * 
 * Newly created Resources use the same template as their Parent Containers
 *
 * @category 	plugin
 * @version 	1.0
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@events OnDocFormPrerender 
 * @internal	@modx_category Manager and Admin
 */

/*
 * Written By Raymond Irving - 12 Oct 2006
 *
 * Simply results in new documents inherriting the template 
 * of their parent folder upon creating a new document
 *
 * Configuration:
 * check the OnDocFormPrerender event
 *
 * Version 1.0
 *
 */

global $content;
$e = &$modx->Event;

switch($e->name) {
  case 'OnDocFormPrerender':
    if(($_REQUEST['pid'] > 0) && ($id == 0)) {
      if($parent = $modx->getPageInfo($_REQUEST['pid'],0,'template')) {
        $content['template'] = $parent['template'];
      }
    }
    break;

  default:
    return;
    break;
}
