<?php
return [
    'timezone' => 'UTC',
    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */
    'locale' => 'en',
    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */
    'fallback_locale' => 'en',
    'providers' => [
        /**
         * Keys are needed only for the convenience of replace ServiceProvider class
         * via custom/config/app/providers/*.php
         */
        'Laravel.View' => Illuminate\View\ViewServiceProvider::class,
        'Laravel.Database' => Illuminate\Database\DatabaseServiceProvider::class,
        'Laravel.Filesystem' => Illuminate\Filesystem\FilesystemServiceProvider::class,
        'Laravel.Pagination' => Illuminate\Pagination\PaginationServiceProvider::class,
        'Laravel.Cache' =>  Illuminate\Cache\CacheServiceProvider::class,
        'Laravel.Lang' => Illuminate\Translation\TranslationServiceProvider::class,

        'Bootstrap.ExceptionHandler' => EvolutionCMS\Providers\ExceptionHandlerProvider::class,
        'Evolution.Events' => EvolutionCMS\Providers\EventServiceProvider::class,
        'Evolution.DBAPI' => EvolutionCMS\Providers\DatabaseProvider::class,
        'Evolution.DEPRECATED' => EvolutionCMS\Providers\DeprecatedCoreProvider::class,
        'Evolution.EXPORT_SITE' => EvolutionCMS\Providers\ExportSiteProvider::class,
        'Evolution.MODxMailer' => EvolutionCMS\Providers\MailProvider::class,
        'Evolution.makeTable' => EvolutionCMS\Providers\MakeTableProvider::class,
        'Evolution.ManagerAPI' => EvolutionCMS\Providers\ManagerApiProvider::class,
        'Evolution.MODIFIERS' => EvolutionCMS\Providers\ModifiersProvider::class,
        'Evolution.phpass' => EvolutionCMS\Providers\PasswordHashProvider::class,
        'Evolution.PHPCOMPAT' => EvolutionCMS\Providers\PhpCompatProvider::class,
        'Evolution.DocBlock' => EvolutionCMS\Providers\DocBlockProvider::class,
        'Evolution.ManagerTheme' => EvolutionCMS\Providers\ManagerThemeServiceProvider::class,
    ],

    'aliases' => [
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'View' => Illuminate\Support\Facades\View::class,
        /**
         * EvolutionCMS
         * @TODO DBAPI, MakeTable and other will be added at version 2.1
         */
        'Evo' => Illuminate\Support\Facades\App::class,
        'DocBlock' => EvolutionCMS\Facades\DocBlock::class
    ]
];
