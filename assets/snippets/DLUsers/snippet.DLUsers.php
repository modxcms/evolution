<?php
include_once(MODX_BASE_PATH . 'assets/snippets/DLUsers/src/Actions.php');

$params = is_array($modx->event->params) ? $modx->event->params : array();
$action = APIHelpers::getkey($params, 'action', '');
$lang = APIHelpers::getkey($params, 'lang', $modx->getConfig('manager_language'));
$userClass = APIHelpers::getkey($params, 'userClass', 'modUsers');
$DLUsers = \DLUsers\Actions::getInstance($modx, $lang, $userClass);
$out = '';
if ( ! empty($action) && method_exists($DLUsers, $action)) {
    $out = call_user_func_array(array($DLUsers, $action), array($params));
}

return $out;
