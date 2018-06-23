<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

require_once __DIR__ . '/vendor/autoload.php';

$tmp = __DIR__ . '.install';
if (is_readable($tmp)) {
    $lastInstallTime = (int)file_get_contents($tmp);
}
define('EVO_INSTALL_TIME', $lastInstallTime);
unset($lastInstallTime, $tmp);

$envFile = __DIR__ . '/custom/.env';
if (is_readable($envFile) && class_exists(Dotenv\Dotenv::class)) {
    /**
     * @see: https://github.com/vlucas/phpdotenv
     */
    $dotenv = new Dotenv\Dotenv(__DIR__ . '/custom');
    $dotenv->load();
}
unset($envFile, $dotenv);

if (file_exists(__DIR__ . '/custom/define.php')) {
    require_once __DIR__ . '/custom/define.php';
}
require_once __DIR__ . '/includes/define.inc.php';

require_once __DIR__ . '/includes/legacy.inc.php';

require_once __DIR__ . '/includes/protect.inc.php'; // harden it

if (! is_cli()) {
    startCMSSession(); // start session
}
