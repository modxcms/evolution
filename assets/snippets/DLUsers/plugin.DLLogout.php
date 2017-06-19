<?php
if (!defined('MODX_BASE_PATH')) {
    die('What are you doing? Get out of here!');
}
if ($modx->event->name == 'OnWebPagePrerender' && $modx->getLoginUserID('web')) {
    $snippetName = (isset($snippetName) && is_string($snippetName)) ? $snippetName : 'DLUsers';
    $modx->runSnippet($snippetName, array(
        'action' => 'logout'
    ));
}
