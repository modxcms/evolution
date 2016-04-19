<?php
/**
 * UltimateParent
 *
 * Travels up the document tree from a specified document and returns its "ultimate" non-root parent
 *
 * @category    snippet
 * @version     2.1
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties
 * @internal    @modx_category Navigation
 * @internal    @installset base
 * @documentation [+site_url+]assets/snippets/ultimateparent/readme.html
 * @reportissues https://github.com/modxcms/evolution
 * @author      Based on UltimateParent 1.x by Susan Ottwell
 * @author      v2.0 by Jason Coward http://opengeek.com
 * @author      v2.1 Refactored 2013 by Dmi3yy
 * @lastupdate  25/12/2013
 */
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

$top= isset ($top) && intval($top) ? $top : 0;
$id= isset ($id) && intval($id) ? intval($id) : $modx->documentIdentifier;
$topLevel= isset ($topLevel) && intval($topLevel) ? intval($topLevel) : 0;
if ($id && $id != $top) {
    $pid= $id;
    if (!$topLevel || count($modx->getParentIds($id)) >= $topLevel) {
        while ($parentIds= $modx->getParentIds($id, 1)) {
            $pid= array_pop($parentIds);
            if ($pid == $top) {
                break;
            }
            $id= $pid;
            if ($topLevel && count($modx->getParentIds($id)) < $topLevel) {
                break;
            }
        }
    }
}
return $id;
?>