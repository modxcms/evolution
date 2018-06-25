<?php
return [
    'timezone' => 'UTC',
    'providers' => [
        'DBAPI' => EvolutionCMS\Providers\DatabaseProvider::class,
        'DEPRECATED' => EvolutionCMS\Providers\DeprecatedCoreProvider::class,
        'ExceptionHandler' => EvolutionCMS\Providers\ExceptionHandlerProvider::class,
        'EXPORT_SITE' => EvolutionCMS\Providers\ExportSiteProvider::class,
        'MODxMailer' => EvolutionCMS\Providers\MailProvider::class,
        'makeTable' => EvolutionCMS\Providers\MakeTableProvider::class,
        'ManagerAPI' => EvolutionCMS\Providers\ManagerApiProvider::class,
        'MODIFIERS' => EvolutionCMS\Providers\ModifiersProvider::class,
        'phpass' => EvolutionCMS\Providers\PasswordHashProvider::class,
        'PHPCOMPAT' => EvolutionCMS\Providers\PhpCompatProvider::class,
        'DocBlock' => EvolutionCMS\Providers\DocBlockProvider::class
    ],

    'aliases' => [
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'View' => Illuminate\Support\Facades\View::class,
        /**
         * EvolutionCMS
         * @TODO DBAPI, MakeTable and other will be added at version 2.1
         */
        'Evo' => Illuminate\Support\Facades\App::class,
        'DocBlock' => EvolutionCMS\Facades\DocBlock::class,
    ]
];
