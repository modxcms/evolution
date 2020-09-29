<?php
include_once dirname(__DIR__) . '/vendor/autoload.php';

try {
    $DB = new AgelxNash\Modx\Evo\Database\LegacyDatabase(
        'localhost',
        'modx',
        'homestead',
        'secret',
        'modx_',
        'utf8mb4',
        'SET NAMES',
        'utf8mb4_unicode_ci',
        AgelxNash\Modx\Evo\Database\Drivers\IlluminateDriver::class
    );
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

    echo ' [ DONE ] ' . PHP_EOL;
} catch (Exception $exception) {
    echo get_class($exception) . PHP_EOL;
    echo "\t" . $exception->getMessage() . PHP_EOL;
    echo $exception->getTraceAsString() . PHP_EOL;
    exit(1);
}
