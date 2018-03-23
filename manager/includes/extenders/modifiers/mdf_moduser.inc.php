<?php
$userid = (int)$value;
if (!isset($modx->filter->cache['ui'][$userid])) {
    if ($userid < 0) $user = $modx->getWebUserInfo(abs($userid));
    else             $user = $modx->getUserInfo($userid);
    $modx->filter->cache['ui'][$userid] = $user;
} else {
    $user = $modx->filter->cache['ui'][$userid];
}
$user['name'] = !empty($user['fullname']) ? $user['fullname'] : $user['username'];

return $user[$opt];
