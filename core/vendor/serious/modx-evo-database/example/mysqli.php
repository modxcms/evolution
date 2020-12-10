<?php
include_once dirname(__DIR__) . '/vendor/autoload.php';

$DB = new AgelxNash\Modx\Evo\Database\LegacyDatabase(
    'localhost',
    'modx',
    'homestead',
    'secret',
    'modx_',
    'utf8mb4',
    'SET NAMES',
    'utf8mb4_unicode_ci',
    AgelxNash\Modx\Evo\Database\Drivers\MySqliDriver::class
);
$DB->setDebug(true);

try {
    $DB->connect();
    echo ' [ CONNECTION TIME ] ' . $DB->getConnectionTime(true) . ' s. ' . PHP_EOL;
    echo ' [ VERSION ] ' . $DB->getVersion() . PHP_EOL;

    $table = $DB->getFullTableName('site_content');

    echo ' [ METHOD ] query' . PHP_EOL;
    $result = $DB->query('SELECT * FROM ' . $table . ' WHERE parent = 0 ORDER BY pagetitle DESC LIMIT 10');
    if ($result instanceof mysqli_result) {
        foreach ($DB->makeArray($result) as $item) {
            echo "\t [ DOCUMENT #ID " . $item['id'] . ' ] ' . $item['pagetitle'] . PHP_EOL;
        }
    }

    echo ' [ METHOD ] select with string' . PHP_EOL;
    $result = $DB->select('*', $table, 'parent = 0', 'pagetitle DESC', '10');
    if ($result instanceof mysqli_result) {
        foreach ($DB->makeArray($result) as $item) {
            echo "\t [ DOCUMENT #ID " . $item['id'] . ' ] ' . $item['pagetitle'] . PHP_EOL;
        }
    }

    echo ' [ METHOD ] select with array' . PHP_EOL;
    $result = $DB->select(
        ['id', 'pagetitle', 'title' => 'longtitle'],
        ['c' => $table],
        ['parent = 0'],
        'ORDER BY pagetitle DESC',
        'LIMIT 10'
    );
    if ($result instanceof mysqli_result) {
        foreach ($DB->makeArray($result) as $item) {
            echo "\t [ DOCUMENT #ID " . $item['id'] . ' ] ' . $item['pagetitle'] . PHP_EOL;
        }
    }

    foreach ($DB->getAllExecutedQuery() as $id => $query) {
        echo ' [ QUERY #' . $id . ' ] ' . PHP_EOL;
        foreach ($query as $key => $data) {
            echo "\t [" . $key . '] ' . $data . PHP_EOL;
        }
    }
    echo ' [ DONE MYSQLI ] ' . PHP_EOL;
} catch (Exception $exception) {
    echo get_class($exception) . PHP_EOL;
    echo "\t" . $exception->getMessage() . PHP_EOL;
    echo $exception->getTraceAsString() . PHP_EOL;
    exit(1);
}
