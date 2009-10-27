<?php

// Default configuration file - AjaxSearch 1.8.4
// Keep care all these values are required

$dcfg['config'] = '';
$dcfg['debug'] = 0;
$dcfg['language'] = $modx->config['manager_language'];
$dcfg['ajaxSearch'] = 1;
$dcfg['advSearch'] = 'oneword';
$dcfg['whereSearch'] = 'content|tv';
$dcfg['subSearch'] = '5,1';
$dcfg['withTvs'] = '';
$dcfg['order'] = 'publishedon,pagetitle';
$dcfg['rank'] = 'pagetitle:100,extract';
$dcfg['maxWords'] = 5;
$dcfg['minChars'] = 3;
$dcfg['AS_showForm'] = 1;
$dcfg['resultsPage'] = 0;
$dcfg['grabMax'] = 10;
$dcfg['extract'] = '1:content,description,introtext,tv_value';
$dcfg['extractLength'] = 200;
$dcfg['extractEllips'] = '...';
$dcfg['extractSeparator'] = '<br />';
$dcfg['formatDate'] = 'd/m/y : H:i:s';
$dcfg['highlightResult'] = 1;
$dcfg['showPagingAlways'] = 0;
$dcfg['pageLinkSeparator'] = ' | ';
$dcfg['AS_landing'] = false;
$dcfg['AS_showResults'] = true;
$dcfg['parents'] = '';
$dcfg['documents'] = '';
$dcfg['depth'] = 10;
$dcfg['hideMenu'] = 2;
$dcfg['hideLink'] = 1;
$dcfg['filter'] = '';
$dcfg['tplLayout'] = '@FILE:' . AS_SPATH . 'templates/layout.tpl.html';
$dcfg['tplResults'] = '@FILE:' . AS_SPATH . 'templates/results.tpl.html';
$dcfg['tplResult'] = '@FILE:' . AS_SPATH . 'templates/result.tpl.html';
$dcfg['tplPaging'] = '@FILE:' . AS_SPATH . 'templates/paging.tpl.html';
$dcfg['tplComment'] = '@FILE:' . AS_SPATH . 'templates/comment.tpl.html';
$dcfg['stripInput'] = 'defaultStripInput';
$dcfg['stripOutput'] = 'defaultStripOutput';
$dcfg['searchWordList'] = '';
$dcfg['breadcrumbs'] = '';
$dcfg['tvPhx'] = 0;
$dcfg['clearDefault'] = 0;
$dcfg['jsClearDefault'] = AS_SPATH . 'js/clearDefault.js';
$dcfg['mbstring'] = 1;
$dcfg['asLog'] = '0:0:200';

$dcfg['liveSearch'] = 0;
$dcfg['ajaxMax'] = 6;
$dcfg['showMoreResults'] = 0;
$dcfg['moreResultsPage'] = 0;
$dcfg['opacity'] = 1.;
$dcfg['tplAjaxResults'] = '@FILE:' . AS_SPATH . 'templates/ajaxResults.tpl.html';
$dcfg['tplAjaxResult'] = '@FILE:' . AS_SPATH . 'templates/ajaxResult.tpl.html';
$dcfg['jscript'] = 'mootools';
$dcfg['addJscript'] = 1;
$dcfg['jsMooTools'] = 'manager/media/script/mootools/mootools.js';
$dcfg['jsMooTools1.2'] = AS_SPATH . 'js/mootools1.2/mootools.js';
$dcfg['jsJquery'] = AS_SPATH . 'js/jQuery/jquery.js';


// For a global parameter initialisation use the following syntax $__param = 'value';
// To overwrite parameter snippet call use $param = 'value';

?>
