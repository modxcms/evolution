/*
::::::::::::::::::::::::::::::::::::::::
 Snippet name: Wayfinder
 Short Desc: builds site navigation
 Version: beta3
 Authors: Ryan Thrash (vertexworks.com)
          Kyle Jaebker (muddydogpaws.com)
 Date: September 12, 2006
::::::::::::::::::::::::::::::::::::::::
Description:
    Totally refactored from original DropMenu nav builder to make it easier to
    create custom navigation by using chunks as output templates. By using templates,
    many of the paramaters are no longer needed for flexible output including tables,
    unordered- or ordered-lists (ULs or OLs), definition lists (DLs) or in any other
    format you desire.
::::::::::::::::::::::::::::::::::::::::
Example Usage:
    [[Wayfinder? &startId=`0`]]
::::::::::::::::::::::::::::::::::::::::
*/

include_once("assets/snippets/wayfinder/wayfinder.inc.php");

if (class_exists('Wayfinder')) {
   $wf = new Wayfinder();
} else {
    return 'error: Wayfinder class not found';
}

//parameter overrides
$wf->id = isset($startId)? $startId: $modx->documentIdentifier;
$wf->level = isset($level)? $level: 0;
$wf->ph = isset($ph)? $ph: FALSE;
$wf->debug = isset($debug)? TRUE: FALSE;
$wf->ignoreHidden = isset($ignoreHidden)? $ignoreHidden: FALSE;
$wf->hideSubMenus = isset($hideSubMenus)? $hideSubMenus: FALSE;
$wf->useWeblinkUrl = isset($useWeblinkUrl)? $useWeblinkUrl: TRUE;
isset($removeNewLines)? $wf->ie = '': $wf->ie = "\n";
//Include javascript & css chunks
$wf->cssTpl = isset($cssTpl)? $cssTpl : FALSE;
$wf->jsTpl = isset($jsTpl)? $jsTpl : FALSE;
//get user class definitions
$wf->css['first'] = isset($firstClass)? $firstClass: '';
$wf->css['last'] = isset($lastClass)? $lastClass: 'last';
$wf->css['here'] = isset($hereClass)? $hereClass: 'active';
$wf->css['parent'] = isset($parentClass)? $parentClass: '';
$wf->css['row'] = isset($rowClass)? $rowClass: '';
$wf->css['outer'] = isset($outerClass)? $outerClass: '';
$wf->css['inner'] = isset($innerClass)? $innerClass: '';
$wf->css['level'] = isset($levelClass)? $levelClass: '';
$wf->css['self'] = isset($selfClass)? $selfClass: '';
$wf->css['weblink'] = isset($webLinkClass)? $webLinkClass: '';
//prefix for adding id to each row
$wf->rowIdPrefix = isset($rowIdPrefix)? $rowIdPrefix: FALSE;
//get fields to output
$wf->textOfLinks = (isset($textOfLinks)) ? $textOfLinks : 'menutitle';
$wf->titleOfLinks = (isset($titleOfLinks)) ? $titleOfLinks : 'pagetitle';
//get user templates
$wf->templates['outerTpl'] = isset($outerTpl) ? $outerTpl : '';
$wf->templates['rowTpl'] = isset($rowTpl) ? $rowTpl : '';
$wf->templates['parentRowTpl'] = isset($parentRowTpl) ? $parentRowTpl : '';
$wf->templates['parentRowHereTpl'] = isset($parentRowHereTpl) ? $parentRowHereTpl : '';
$wf->templates['hereTpl'] = isset($hereTpl) ? $hereTpl : '';
$wf->templates['innerTpl'] = isset($innerTpl) ? $innerTpl : '';
$wf->templates['innerRowTpl'] = isset($innerRowTpl) ? $innerRowTpl : '';
$wf->templates['innerHereTpl'] = isset($innerHereTpl) ? $innerHereTpl : '';
$wf->templates['activeParentRowTpl'] = isset($activeParentRowTpl) ? $activeParentRowTpl : '';
$wf->templates['categoryFoldersTpl'] = isset($categoryFoldersTpl) ? $categoryFoldersTpl : '';
//setup here checking array
$wf->parentTree = $modx->getParentIds($modx->documentIdentifier);
$wf->parentTree[] = $modx->documentIdentifier;
//Get version info
$wf->modxVersion = $modx->getVersionData();

if ($wf->debug) {
    $wf->debugOutput .= '<p>Starting Menu from Docid: ' . $wf->id . '<br/>';
    $wf->debugOutput .= 'Docids for \'Here\' class checking: ' . implode(', ',$wf->parentTree) . '</p>';
    $wf->debugOutput .= '<h3>Chunk Checks</h3>';
}

$wf->checkChunks();

if ($wf->cssTpl || $wf->jsTpl) {
    $wf->regJsCss();
}

if ($wf->debug) {
    $wf->debugOutput .= '<h3>Document Processing</h3>';
}

$output = $wf->buildMenu($wf->id);

if ($wf->debug) {
    $output = $output . $wf->debugOutput;
}

if ($ph) {
    $modx->setPlaceholder($ph,$output);
} else {
    return $output;
}
