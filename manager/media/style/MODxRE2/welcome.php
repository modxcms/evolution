<?php
$modx->config['enable_filter'] = 1;

$modx->addSnippet('hasPermission','return $modx->hasPermission($key);');

if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags'))
    $hasAnyPermission = 1;
else $hasAnyPermission = 0;
$modx->addSnippet('hasAnyPermission','global $hasAnyPermission; return $hasAnyPermission;');
$modx->addSnippet('getLoginUserName','return $modx->getLoginUserName();');
$code = 'global $_lang;return $_SESSION["nrtotalmessages"] ? sprintf($_lang["welcome_messages"], $_SESSION["nrtotalmessages"], \'<span style="color:red;">\' . $_SESSION["nrnewmessages"] . "</span>") : "No messages";';
$modx->addSnippet('getMessageCount',$code);
