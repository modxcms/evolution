<?php
use EvolutionCMS\ParameterProvider;

return [
    EvolutionCMS\Interfaces\DatabaseInterface::class => [
        'class' => EvolutionCMS\Database::class,
        'arguments' => [
            new ParameterProvider('db.server'),
            new ParameterProvider('db.base'),
            new ParameterProvider('db.user'),
            new ParameterProvider('db.password'),
            new ParameterProvider('db.prefix'),
            new ParameterProvider('db.charset'),
            new ParameterProvider('db.method'),
        ],
        'calls' => [
            [
                'method' => 'query',
                'arguments' => [
                    "SET SQL_MODE = '';"
                ]
            ]
        ]
    ],
    EvolutionCMS\Interfaces\PhpCompatInterface::class => array(
        'class' => EvolutionCMS\Legacy\PhpCompat::class
    )
    /*Evo\Interfaces\ConfigInterface::class => [
        'class' => Evo\Config::class,
        'arguments' => [
            new ParameterProvider('config')
        ],
        'calls' => []
    ],
    Evo\Interfaces\LoggerInterface::class => [
        'class' => Evo\Logger::class,
        'arguments' => [
            null, null
        ],
        'calls' => []
    ],
    Evo\Interfaces\SessionInterface::class => [
        'class' => Evo\Session::class,
        'arguments' => [
            new ParameterProvider('session.domain'),
            new ParameterProvider('session.start')
        ],
        'calls' => []
    ],
    Evo\Interfaces\SystemEventInterface::class => [
        'class' => Evo\SystemEvent::class,
        'arguments' => [],
        'calls' => []
    ],
    Doctrine\Common\Cache\Cache::class => [
        'class' => Doctrine\Common\Cache\VoidCache::class,
        'arguments' => [
            //MODX_BASE_PATH.'assets/cache/data/'
        ],
        'calls' => [
            [
                'method' => 'setNamespace',
                'arguments' => [
                    'site_name'
                ]
            ]
        ]
    ]*/
];
