<?php

return [
    'default' => 'default',
    'redis' => [

        'client' => 'phpredis',

        'default' => [
            'url' => env('REDIS_URL', 'tcp://127.0.0.1:6379?database=0'),
        ],

    ],
];