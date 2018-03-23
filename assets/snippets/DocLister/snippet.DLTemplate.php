<?php
if (! defined('MODX_BASE_PATH')) {
    die('HACK???');
}

require_once(MODX_BASE_PATH . "assets/snippets/DocLister/lib/DLTemplate.class.php");

$tpl = '';
if (isset($modx->Event->params['tpl'])) {
    $tpl = $modx->Event->params['tpl'];
    unset($modx->Event->params['tpl']);
}

return empty($tpl) ? '' : DLTemplate::getInstance($modx)->parseChunk($tpl, $modx->Event->params);
