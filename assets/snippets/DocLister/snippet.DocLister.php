<?php
/**
 * DocLister snippet
 *
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Agel_Nash <Agel_Nash@xaker.ru>
 */
if (!defined('MODX_BASE_PATH')) {
    die('HACK???');
}
$_time = microtime(true);
$out = null;
$DLDir = MODX_BASE_PATH . 'assets/snippets/DocLister/';

require_once($DLDir . "core/DocLister.abstract.php");
require_once($DLDir . "core/extDocLister.abstract.php");
require_once($DLDir . "core/filterDocLister.abstract.php");

if (isset($controller)) {
    preg_match('/^(\w+)$/iu', $controller, $controller);
    $controller = $controller[1];
} else {
    $controller = "site_content";
}
$classname = $controller . "DocLister";

$dir = isset($dir) ? MODX_BASE_PATH . $dir : $DLDir . "core/controller/";
if ($classname != 'DocLister' && file_exists($dir . $controller . ".php") && !class_exists($classname, false)) {
    require_once($dir . $controller . ".php");
}

if (class_exists($classname, false) && $classname != 'DocLister') {
    $DocLister = new $classname($modx, $modx->Event->params, $_time);
    $data = $DocLister->getDocs();
    $out = isset($modx->Event->params['api']) ? $DocLister->getJSON($data,
        $modx->Event->params['api']) : $DocLister->render();
    if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == 'manager') {
        $debug = $DocLister->debug->showLog();
    } else {
        $debug = '';
    }

    if ($DocLister->getCFGDef('debug', 0)) {
        if ($DocLister->getCFGDef("api", 0)) {
            $modx->setPlaceholder($DocLister->getCFGDef("sysKey", "dl") . ".debug", $debug);
        } else {
            $out = ($DocLister->getCFGDef('debug') > 0) ? $debug . $out : $out . $debug;
        }
    }

    $saveDLObject = $DocLister->getCFGDef('saveDLObject');
    if ($saveDLObject && is_scalar($saveDLObject)) {
        $modx->setPlaceholder($saveDLObject, $DocLister);
    }
}
return $out;
