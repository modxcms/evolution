<?php
global $database_server,
       $database_type,
       $dbase,
       $database_user,
       $database_password,
       $table_prefix,
       $database_connection_charset,
       $database_connection_method,
       $database_collation;

return [
    EvolutionCMS\Interfaces\DatabaseInterface::class => array(
        'class' => EvolutionCMS\Database::class,
        'arguments' => array(
            [
                'host' => $database_server,
                'base' => $dbase,
                'user' => $database_user,
                'pass' => $database_password,
                'charset' => $database_connection_charset,
                'collation' => $database_collation,
                'prefix' => $table_prefix,
                'method' => $database_connection_method,
                'driver' => $database_type === 'mysqli' ? 'mysql' : $database_type
            ],
            AgelxNash\Modx\Evo\Database\Drivers\IlluminateDriver::class
        ),
        'calls' => array(
            array(
                'method' => 'query',
                'arguments' => array(
                    "SET SQL_MODE = '';"
                )
            )
        )
    ),
    EvolutionCMS\Interfaces\PhpCompatInterface::class => array(
        'class' => EvolutionCMS\Legacy\PhpCompat::class
    ),
    EvolutionCMS\Interfaces\MailInterface::class => array(
        'class' => EvolutionCMS\Mail::class
    ),
    EvolutionCMS\Interfaces\PasswordHashInterface::class => array(
        'class' => EvolutionCMS\Legacy\PasswordHash::class
    ),
    EvolutionCMS\Interfaces\MakeTableInterface::class => array(
        'class' => EvolutionCMS\Support\MakeTable::class
    ),
    EvolutionCMS\Interfaces\ExportSiteInerface::class => array(
        'class' => EvolutionCMS\Legacy\ExportSite::class
    ),
    EvolutionCMS\Interfaces\ManagerApiInterface::class => array(
        'class' => EvolutionCMS\Legacy\ManagerApi::class
    ),
    EvolutionCMS\Interfaces\DeprecatedCoreInterface::class => array(
        'class'=> EvolutionCMS\Legacy\DeprecatedCore::class
    ),
    EvolutionCMS\Interfaces\ModifiersInterface::class => array(
        'class' => EvolutionCMS\Legacy\Modifiers::class
    )
];
