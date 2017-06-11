<?php
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 17.01.2016
 * Time: 17:45
 *
 * @var \DocumentParser $modx
 * @var array $params
 */

if (!isset($formid)) return;
$out = '';
$FLDir = MODX_BASE_PATH . 'assets/snippets/FormLister/';
if (isset($controller)) {
    preg_match('/^(\w+)$/iu', $controller, $controller);
    $controller = $controller[1];
} else {
    $params['controller'] = $controller = "Form";
}
$classname = '\FormLister\\'.$controller;

$dir = isset($dir) ? MODX_BASE_PATH.$dir : $FLDir . "core/controller/";
if ($classname != '\FormLister\Core' && file_exists($dir . $controller . ".php") && !class_exists($classname, false)) {
    require_once($dir . $controller . ".php");
}

if (!isset($langDir)) $params['langDir'] = 'assets/snippets/FormLister/core/lang/';

if (class_exists($classname, false) && $classname != '\FormLister\Core') {
    /** @var \FormLister\Core $FormLister */
    $FormLister = new $classname($modx, $params);
    if (!$FormLister->getFormId()) return;
    $FormLister->initForm();
    $out = $FormLister->render();
}
if ($FormLister->getFormStatus() && isset($saveObject) && is_scalar($saveObject)) {
    $modx->setPlaceholder($saveObject,$FormLister);
}

if (!is_null($FormLister->debug)) {
    $FormLister->debug->saveLog();
}

return $out;
