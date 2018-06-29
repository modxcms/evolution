<?php
/*
*************************************************************************
	EVO Content Management System and PHP Application Framework ("EVO")
	Managed and maintained by Dmytro Lukianenko and the	EVO community
*************************************************************************
	EVO is an opensource PHP/MySQL content management system and content
	management framework that is flexible, adaptable, supports XHTML/CSS
	layouts, and works with most web browsers.

	EVO is distributed under the GNU General Public License
*************************************************************************

	This file and all related or dependant files distributed with this file
	are considered as a whole to make up EVO.

	EVO is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	EVO is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with EVO (located in "/assets/docs/"); if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1335, USA

	For more information on EVO please visit https://evo.im/
	Github: https://github.com/evolution-cms/evolution/

**************************************************************************
	Based on MODX Evolution CMS and Application Framework
	Copyright 2005 and forever thereafter by Raymond Irving & Ryan Thrash.
	All rights reserved.

	MODX Evolution is originally based on Etomite by Alex Butter
**************************************************************************
*/

/**
 *  Filename: index.php
 *  Function: This file is the main root file for EVO. It is
 *          only file that will be directly requested, and
 *          depending on the request, will branch different
 *          content
 */
if (!isset($_SERVER['REQUEST_TIME_FLOAT'])) {
    $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
}
$mstart = memory_get_usage();

if(mb_strtoupper($_SERVER['REQUEST_METHOD']) === 'GET' && count($_GET) === 1 && !empty($_GET['time'])) {
    die();
}
// we use this to make sure files are accessed through
// the manager instead of seperately.
if (!defined('IN_MANAGER_MODE')) {
    define('IN_MANAGER_MODE', true);
}

if (file_exists(__DIR__ . '/config.php')) {
    $config = require __DIR__ . '/config.php';
} elseif (file_exists(dirname(__DIR__) . '/config.php')) {
    $config = require dirname(__DIR__) . '/config.php';
} else {
    $config = [
        'core' => dirname(__DIR__) . '/core'
    ];
}

if (!empty($config['core']) && file_exists($config['core'] . '/bootstrap.php')) {
    require_once $config['core'] . '/bootstrap.php';
} else {
    echo "<h3>Unable to load configuration settings</h3>";
    echo "Please run the EVO <a href='../install'>install utility</a>";
    exit;
}

if (! isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

// send anti caching headers
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('X-UA-Compatible: IE=edge;FF=3;OtherUA=4');
header('X-XSS-Protection: 0');

// check PHP version. EVO is compatible with php 5 (5.6.0+)
$php_ver_comp = version_compare(phpversion(), "7.1.3");
// -1 if left is less, 0 if equal, +1 if left is higher
if ($php_ver_comp < 0) {
    echo sprintf('EVO Evolution is compatible with PHP version 7.1.3 and higher. This server is using version %s%. Please upgrade your PHP installation!', phpversion());
    exit;
}

// check if iconv is installed
if (!function_exists('iconv')) {
    echo 'It is important to install/enable extension iconv. Please speak to your host if you don´t know how to enable it.';
    exit;
}

// set the document_root :|
if (!isset($_SERVER['DOCUMENT_ROOT']) || empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = str_replace(
            $_SERVER['PATH_INFO'],
            "",
            preg_replace("/\\\\/", "/", $_SERVER['PATH_TRANSLATED'])
        ) . "/";
}

// initiate the content manager class
$modx = evolutionCMS();
$modx->mstart = $mstart;
$modx->sid = session_id();

//$settings = $modx->allConfig();
//extract($settings, EXTR_OVERWRITE);

// attempt to foil some simple types of CSRF attacks
if ((int)$modx->getConfig('validate_referer') !== 0) {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];

        if (!empty($referer)) {
            if (!preg_match('/^' . preg_quote(MODX_SITE_URL, '/') . '/i', $referer)) {
                $modx->webAlertAndQuit(
                    "A possible CSRF attempt was detected from referer: {$referer}.",
                    "index.php"
                );
            }
        } else {
            $modx->webAlertAndQuit(
                "A possible CSRF attempt was detected. No referer was provided by the client.",
                "index.php"
            );
        }
    } else {
        $modx->webAlertAndQuit(
            "A possible CSRF attempt was detected. No referer was provided by the server.",
            "index.php"
        );
    }
}

// Initialize System Alert Message Queque
if (!isset($_SESSION['SystemAlertMsgQueque'])) {
    $_SESSION['SystemAlertMsgQueque'] = array();
}
$SystemAlertMsgQueque = &$_SESSION['SystemAlertMsgQueque'];

$_lang = $modx->get('ManagerTheme')->getLexicon();

// send the charset header
header('Content-Type: text/html; charset=' . $modx->get('ManagerTheme')->getCharset());

// Update last action in table active_users
$action = $modx->get('ManagerTheme')->getActionId();

// accesscontrol.php checks to see if the user is logged in. If not, a log in form is shown
if ($modx->get('ManagerTheme')->isAuthManager() === false) {
    echo $modx->get('ManagerTheme')->renderLoginPage();
    exit;
}

/** Ignore Logout action */
if (8 !== $action && $modx->get('ManagerTheme')->hasManagerAccess() === false) {
    echo $modx->get('ManagerTheme')->renderAccessPage();
    exit;
}

// Update table active_user_sessions
$modx->updateValidatedUserSession();

if ($action === null) {
    // include_once the style variables file
    if ($modx->get('ManagerTheme')->getTheme() !== '' && ! isset($_style)) {
        $_style = array();
        include_once __DIR__ . "/media/style/" . $modx->get('ManagerTheme')->getTheme() . "/style.php";
    }

    // first we check to see if this is a frameset request
    if (!isset($_POST['updateMsgCount'])) {
        // this looks to be a top-level frameset request, so let's serve up a frameset
        if (is_file(__DIR__ . "/media/style/" . $modx->get('ManagerTheme')->getTheme() . "/frames/1.php")) {
            include_once __DIR__ . "/media/style/" . $modx->get('ManagerTheme')->getTheme() . "/frames/1.php";
        } else {
            include_once __DIR__ . "/frames/1.php";
        }
        exit;
    } else {
        if ($modx->hasPermission('messages')) {
            include_once __DIR__ . '/includes/messageCount.inc.php';
        }
    }
} else {
    $modx->get('ManagerTheme')->saveAction($action);

    $modx->invokeEvent('OnManagerPageInit', compact('action'));

    echo $modx->get('ManagerTheme')->handle($action);
}
