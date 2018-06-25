<?php
define('MODX_API_MODE', true);

if (file_exists(__DIR__ . '/config.php')) {
    $config = require __DIR__ . '/config.php';
} else {
    $config = [
        'root' => __DIR__
    ];
}

if (!empty($config['root']) && file_exists($config['root']. '/index.php')) {
    require_once $config['root'] . '/index.php';
} else {
    echo "<h3>Unable to load configuration settings</h3>";
    echo "Please run the EVO <a href='../install'>install utility</a>";
    exit;
}

$modx->getDatabase()->connect();
$modx->getSettings();

$modx->documentMethod = 'id';
$modx->documentIdentifier = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 1;
$modx->documentObject = $modx->getDocumentObject('id', $modx->documentIdentifier);

$modx->invokeEvent('OnWebPageInit');

dd(Evo::version());



