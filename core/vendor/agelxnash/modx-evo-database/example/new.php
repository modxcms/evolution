<?php
include_once dirname(__DIR__) . '/vendor/autoload.php';

try {
    $config = [
        'host' => 'localhost',
        'base' => 'modx',
        'user' => 'homestead',
        'pass' => 'secret',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => 'modx_',
        'method' => 'SET NAMES'
    ];

    $customConnection = new AgelxNash\Modx\Evo\Database\Drivers\IlluminateDriver($config);

    $DB = new AgelxNash\Modx\Evo\Database\Database($config, $customConnection);
    $DB->setDebug(true);

    $DB->connect();

    echo ' [ METHOD ] Eloquent' . PHP_EOL;
    $out = AgelxNash\Modx\Evo\Database\Models\SiteContent::where('parent', '=', 0)
        ->orderBy('pagetitle', 'DESC')
        ->limit(10)
        ->get();
    foreach ($out as $item) {
        echo "\t [ DOCUMENT #ID " . $item->id . ' ] ' . $item->pagetitle . PHP_EOL;
    }

    echo ' [ METHOD ] select with string' . PHP_EOL;
    $result = $DB->select('*', $DB->getFullTableName('site_content'), 'parent = 0', 'pagetitle DESC', '10');
    if ($result instanceof \PDOStatement) {
        foreach ($DB->makeArray($result) as $item) {
            echo "\t [ DOCUMENT #ID " . $item['id'] . ' ] ' . $item['pagetitle'] . PHP_EOL;
        }
    }

    echo ' [ DONE ] ' . PHP_EOL;
} catch (Exception $exception) {
    echo get_class($exception) . PHP_EOL;
    echo "\t" . $exception->getMessage() . PHP_EOL;
    echo $exception->getTraceAsString() . PHP_EOL;
    exit(1);
}
