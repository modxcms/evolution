<?php
/**
 * Wayfinder
 *
 * Completely template-driven and highly flexible menu builder
 *
 * @category 	snippet
 * @version 	2.1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties
 * @internal	@modx_category Navigation
 * @internal    @installset base, sample
 * @documentation Official docs https://rtfm.modx.com/extras/evo/wayfinder
 * @documentation Almost complete guide, Cheatsheet http://sottwell.com/links/wayfinder.html
 * @documentation Almost complete guide Direct-Link https://drive.google.com/file/d/0B5y4Q9am5QDTOVpIa3Y0VVpjcFU/edit?usp=sharing
 * @reportissues https://github.com/modxcms/evolution
 * @author      Kyle Jaebker http://muddydogpaws.com
 * @author      Ryan Thrash http://thrash.me
 * @author      and many others since 2006
 * @lastupdate  23/05/2016
 */
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

$wf_base_path = $modx->config['base_path'] . 'assets/snippets/wayfinder/';
$conf_path = "{$wf_base_path}configs/";

//Include a custom config file if specified
if(is_file("{$conf_path}default.config.php"))
    include("{$conf_path}default.config.php");

$config = (!isset($config)) ? 'default' : trim($config);
$config = ltrim($config,'/');

if(substr($config, 0, 6) == '@CHUNK')               eval('?>' . $modx->getChunk(trim(substr($config, 7))));
elseif(substr($config, 0, 5) == '@FILE')            include($modx->config['base_path'] . trim(substr($config, 6)));
elseif($config!='default'&&is_file("{$conf_path}{$config}.config.php"))
                                                    include("{$conf_path}{$config}.config.php");
elseif(is_file("{$conf_path}{$config}"))            include("{$conf_path}{$config}");
elseif(is_file($modx->config['base_path'].$config)) include($modx->config['base_path'] . $config);

include_once("{$wf_base_path}wayfinder.inc.php");

if (class_exists('Wayfinder')) $wf = new Wayfinder();
else                           return 'error: Wayfinder class not found';

$wf->_config = array(
	'id' => isset($startId) ? $startId : $modx->documentIdentifier,
	'level' => isset($level) ? intval($level) : 0,
	'includeDocs' => isset($includeDocs) ? $includeDocs : 0,
	'excludeDocs' => isset($excludeDocs) ? $excludeDocs : 0,
	'where' => isset($where) ? $where : '',
	'ph' => isset($ph) ? $ph : FALSE,
	'debug' => isset($debug) ? TRUE : FALSE,
	'ignoreHidden' => isset($ignoreHidden) ? $ignoreHidden : FALSE,
	'hideSubMenus' => isset($hideSubMenus) ? $hideSubMenus : FALSE,
	'useWeblinkUrl' => isset($useWeblinkUrl) ? $useWeblinkUrl : TRUE,
	'fullLink' => isset($fullLink) ? $fullLink : FALSE,
	'nl' => isset($removeNewLines) ? '' : "\n",
	'sortOrder' => isset($sortOrder) ? strtoupper($sortOrder) : 'ASC',
	'sortBy' => isset($sortBy) ? $sortBy : 'menuindex',
	'limit' => isset($limit) ? $limit : 0,
	'cssTpl' => isset($cssTpl) ? $cssTpl : FALSE,
	'jsTpl' => isset($jsTpl) ? $jsTpl : FALSE,
	'rowIdPrefix' => isset($rowIdPrefix) ? $rowIdPrefix : FALSE,
	'textOfLinks' => isset($textOfLinks) ? $textOfLinks : 'menutitle',
	'titleOfLinks' => isset($titleOfLinks) ? $titleOfLinks : 'pagetitle',
	'displayStart' => isset($displayStart) ? $displayStart : FALSE,
	'showPrivate' => isset($showPrivate) ? $showPrivate : FALSE,
	'entityEncode' => isset($entityEncode) ? $entityEncode : TRUE,
	// for local references - use original document fields separated by comma (useful for set active if it is current, titles, link attr, etc)
	'useReferenced' => isset($useReferenced) ? $useReferenced: "id", 
	'hereId' => isset($hereId) ? intval($hereId) : $modx->documentIdentifier
);

//get user class definitions
$wf->_css = array(
	'first' => isset($firstClass) ? $firstClass : '',
	'last' => isset($lastClass) ? $lastClass : 'last',
	'here' => isset($hereClass) ? $hereClass : 'active',
	'parent' => isset($parentClass) ? $parentClass : '',
	'row' => isset($rowClass) ? $rowClass : '',
	'outer' => isset($outerClass) ? $outerClass : '',
	'inner' => isset($innerClass) ? $innerClass : '',
	'outerLevel' => isset($outerLevelClass) ? $outerLevelClass: '',
	'level' => isset($levelClass) ? $levelClass: '',
	'self' => isset($selfClass) ? $selfClass : '',
	'weblink' => isset($webLinkClass) ? $webLinkClass : '',
);

//get user templates
$wf->_templates = array(
	'outerTpl' => isset($outerTpl) ? $outerTpl : '',
	'rowTpl' => isset($rowTpl) ? $rowTpl : '',
	'parentRowTpl' => isset($parentRowTpl) ? $parentRowTpl : '',
	'parentRowHereTpl' => isset($parentRowHereTpl) ? $parentRowHereTpl : '',
	'hereTpl' => isset($hereTpl) ? $hereTpl : '',
	'innerTpl' => isset($innerTpl) ? $innerTpl : '',
	'innerRowTpl' => isset($innerRowTpl) ? $innerRowTpl : '',
	'innerHereTpl' => isset($innerHereTpl) ? $innerHereTpl : '',
	'activeParentRowTpl' => isset($activeParentRowTpl) ? $activeParentRowTpl : '',
	'categoryFoldersTpl' => isset($categoryFoldersTpl) ? $categoryFoldersTpl : '',
	'startItemTpl' => isset($startItemTpl) ? $startItemTpl : '',
	'lastRowTpl' => isset($lastRowTpl) ? $lastRowTpl : '',
);

//Process Wayfinder
$output = $wf->run();

if ($wf->_config['debug'] && $modx->checkSession()) {
	$modx->regClientHTMLBlock($wf->renderDebugOutput());
}

//Output Results
if ($wf->_config['ph']) {
    $modx->setPlaceholder($wf->_config['ph'],$output);
    return;
} else {
    return $output;
}
