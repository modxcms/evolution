<?php
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 25.06.2016
 * Time: 18:59
 */
$e = $modx->event;
include_once(MODX_BASE_PATH . 'assets/lib/MODxAPI/modUsers.php');
if ($e->name == 'OnWebLogin') {
    if (!$userObj->get('lastlogin')) {
        $userObj->set('lastlogin',time());
    } else {
        $userObj->set('lastlogin',$userObj->get('thislogin'));
    }
    $userObj->set('thislogin', time());
    $userObj->set('logincount', (int)$userObj->get('logincount') + 1);
    $userObj->save(false,false);
    if (isset($_COOKIE[$cookieName])) {
        $userObj->setAutoLoginCookie($cookieName,$cookieLifetime);
    }
}
if ($e->name == 'OnWebPageInit' || $e->name == 'OnPageNotFound') {
    $user = new \modUsers($modx);
    if ($modx->getLoginUserID('web')) {
        if (isset($_REQUEST[$logoutKey])) {
            $user->logOut($cookieName, true);
            $page = $modx->config['site_url'] . (isset($_REQUEST['q']) ? $_REQUEST['q'] : '');
            $query = $_GET;
            unset($query[$logoutKey], $query['q']);
            if ($query) $page . '?' . http_build_query($query);
            $modx->sendRedirect($page);
        }
    } else {
        $user->AutoLogin($cookieLifetime, $cookieName, true);
    }
}
