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

// send anti caching headers
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('X-UA-Compatible: IE=edge;FF=3;OtherUA=4');
header('X-XSS-Protection: 0');

// provide english $_lang for error-messages
$_lang = array();
include_once EVO_CORE_PATH . "lang/english.inc.php";

// check PHP version. EVO is compatible with php 5 (5.6.0+)
$php_ver_comp = version_compare(phpversion(), "7.1.3");
// -1 if left is less, 0 if equal, +1 if left is higher
if ($php_ver_comp < 0) {
    echo sprintf($_lang['php_version_check'], phpversion());
    exit;
}

// check if iconv is installed
if (!function_exists('iconv')) {
    echo $_lang['iconv_not_available'];
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

$modx->getSettings();
$modx->mstart = $mstart;

// connect to the database
$modx->getDatabase()->connect();

$modx->sid = session_id();

// Now that session is given get user settings and merge into $modx->config
$usersettings = $modx->getUserSettings();

$settings =& $modx->config;
extract($modx->config, EXTR_OVERWRITE);

// now include_once different language file as english
if (!isset($manager_language) || !file_exists(EVO_CORE_PATH . "lang/" . $manager_language . ".inc.php")) {
    $manager_language = "english"; // if not set, get the english language file.
}

// $length_eng_lang = count($_lang); // Not used for now, required for difference-check with other languages than english (i.e. inside installer)

if ($manager_language != "english" && file_exists(EVO_CORE_PATH . "lang/" . $manager_language . ".inc.php")) {
    include_once EVO_CORE_PATH . "lang/" . $manager_language . ".inc.php";
}

// allow custom language overrides not altered by future EVO-updates
if (file_exists(EVO_CORE_PATH . "lang/override/" . $manager_language . ".inc.php")) {
    include_once EVO_CORE_PATH . "lang/override/" . $manager_language . ".inc.php";
}

$s = array('[+MGR_DIR+]');
$r = array(MGR_DIR);
foreach ($_lang as $k => $v) {
    if (strpos($v, '[+') !== false) {
        $_lang[$k] = str_replace($s, $r, $v);
    }
}

// send the charset header
header('Content-Type: text/html; charset=' . $modx_manager_charset);

/*
 * include_once "version.inc.php"; //include version info. Use $modx->getVersionData()
 */

// accesscontrol.php checks to see if the user is logged in. If not, a log in form is shown
include_once __DIR__ . "/includes/accesscontrol.inc.php";

// double check the session
if (!isset($_SESSION['mgrValidated'])) {
    echo "Not Logged In!";
    exit;
}

// include_once the style variables file
if (isset($manager_theme) && !isset($_style)) {
    $_style = array();
    include_once __DIR__ . "/media/style/" . $manager_theme . "/style.php";
}

// check if user is allowed to access manager interface
if (isset($allow_manager_access) && $allow_manager_access == 0) {
    include_once __DIR__ . "/includes/manager.lockout.inc.php";
}

// Initialize System Alert Message Queque
if (!isset($_SESSION['SystemAlertMsgQueque'])) {
    $_SESSION['SystemAlertMsgQueque'] = array();
}
$SystemAlertMsgQueque = &$_SESSION['SystemAlertMsgQueque'];

// first we check to see if this is a frameset request
if (!isset($_POST['a']) && !isset($_GET['a']) && !isset($_POST['updateMsgCount'])) {
    // this looks to be a top-level frameset request, so let's serve up a frameset
    if (is_file(__DIR__ . "/media/style/" . $manager_theme . "/frames/1.php")) {
        include_once __DIR__ . "/media/style/" . $manager_theme . "/frames/1.php";
    } else {
        include_once __DIR__ . "/frames/1.php";
    }
    exit;
}

// OK, let's retrieve the action directive from the request
$option = array('min_range' => 1, 'max_range' => 2000);
if (isset($_GET['a']) && isset($_POST['a'])) {
    $modx->webAlertAndQuit($_lang['error_double_action']);
} elseif (isset($_GET['a'])) {
    $action = (int)filter_input(INPUT_GET, 'a', FILTER_VALIDATE_INT, $option);
} elseif (isset($_POST['a'])) {
    $action = (int)filter_input(INPUT_POST, 'a', FILTER_VALIDATE_INT, $option);
} else {
    $action = null;
}

if (isset($_POST['updateMsgCount']) && $modx->hasPermission('messages')) {
    include_once __DIR__ . '/includes/messageCount.inc.php';
}

// save page to manager object
$modx->getManagerApi()->action = $action;

// attempt to foil some simple types of CSRF attacks
if ((int)$modx->getConfig('validate_referer') !== 0) {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];

        if (!empty($referer)) {
            if (!preg_match('/^' . preg_quote(MODX_SITE_URL, '/') . '/i', $referer)) {
                $modx->webAlertAndQuit("A possible CSRF attempt was detected from referer: {$referer}.", "index.php");
            }
        } else {
            $modx->webAlertAndQuit("A possible CSRF attempt was detected. No referer was provided by the client.",
                "index.php");
        }
    } else {
        $modx->webAlertAndQuit("A possible CSRF attempt was detected. No referer was provided by the server.",
            "index.php");
    }
}

// invoke OnManagerPageInit event
$modx->invokeEvent("OnManagerPageInit", array("action" => $action));

// return element filepath
function includeFileProcessor($filepath, $manager_theme)
{
    $element = "";
    if (is_file(__DIR__ . "/media/style/" . $manager_theme . "/" . $filepath)) {
        $element = __DIR__ . "/media/style/" . $manager_theme . "/" . $filepath;
    } else {
        $element = $filepath;
    }

    return $element;
}

$managerTheme = new EvolutionCMS\ManagerTheme($modx, $manager_theme);
// Now we decide what to do according to the action request. This is a BIG list :)
if ($controller = $managerTheme->handle($action)) {
    include_once $controller;
} else {
    /********************************************************************/
    /* default action: show not implemented message                     */
    /********************************************************************/
    // say that what was requested doesn't do anything yet
    include_once(includeFileProcessor("includes/header.inc.php", $manager_theme));
    echo "
			<div class='sectionHeader'>" . $_lang['functionnotimpl'] . "</div>
			<div class='sectionBody'>
				<p>" . $_lang['functionnotimpl_message'] . "</p>
			</div>
		";
    include_once(includeFileProcessor("includes/footer.inc.php", $manager_theme));
}

/********************************************************************/
// log action, unless it's a frame request
if ($action != 1 && $action != 7 && $action != 2) {
    $log = new EvolutionCMS\Legacy\LogHandler;
    $log->initAndWriteLog();
}
/********************************************************************/
// show debug
unset($_SESSION['itemname']); // clear this, because it's only set for logging purposes
include_once __DIR__ . "/includes/debug.inc.php";
