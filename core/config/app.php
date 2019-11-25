<?php
return [
    'timezone' => 'UTC',
    'env' => 'production',
    'debug' => false,
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
        'Bootstrap_ExceptionHandler' => EvolutionCMS\Providers\ExceptionHandlerServiceProvider::class,

        'Console_Artisan' => EvolutionCMS\Providers\ArtisanServiceProvider::class,
        'Console_Migration' => Illuminate\Database\MigrationServiceProvider::class,
        'Console_Composer' => EvolutionCMS\Providers\ComposerServiceProvider::class,

        'Laravel_View' => Illuminate\View\ViewServiceProvider::class,
        'Laravel_Database' => Illuminate\Database\DatabaseServiceProvider::class,
        'Laravel_Filesystem' => Illuminate\Filesystem\FilesystemServiceProvider::class,
        'Laravel_Cache' =>  Illuminate\Cache\CacheServiceProvider::class,
        'Laravel_Lang' => Illuminate\Translation\TranslationServiceProvider::class,

        'Evolution_Observers' => EvolutionCMS\Providers\ObserversServiceProvider::class,
        'Evolution_Pagination' => EvolutionCMS\Providers\PaginationServiceProvider::class,
        'Evolution_Events' => EvolutionCMS\Providers\EventServiceProvider::class,
        'Evolution_DBAPI' => EvolutionCMS\Providers\DatabaseServiceProvider::class,
        'Evolution_DEPRECATED' => EvolutionCMS\Providers\DeprecatedCoreServiceProvider::class,
        'Evolution_EXPORT_SITE' => EvolutionCMS\Providers\ExportSiteServiceProvider::class,
        'Evolution_MODxMailer' => EvolutionCMS\Providers\MailServiceProvider::class,
        'Evolution_makeTable' => EvolutionCMS\Providers\MakeTableServiceProvider::class,
        'Evolution_ManagerAPI' => EvolutionCMS\Providers\ManagerApiServiceProvider::class,
        'Evolution_MODIFIERS' => EvolutionCMS\Providers\ModifiersServiceProvider::class,
        'Evolution_phpass' => EvolutionCMS\Providers\PasswordHashServiceProvider::class,
        'Evolution_PHPCOMPAT' => EvolutionCMS\Providers\PhpCompatServiceProvider::class,
        'Evolution_DocBlock' => EvolutionCMS\Providers\DocBlockServiceProvider::class,
        'Evolution_ManagerTheme' => EvolutionCMS\Providers\ManagerThemeServiceProvider::class,
        'Evolution_UrlProcessor' => EvolutionCMS\Providers\UrlProcessorServiceProvider::class,
        'Evolution_TemplateProcessor' => EvolutionCMS\Providers\TemplateProcessorServiceProvider::class,
        'Evolution_Blade' => EvolutionCMS\Providers\BladeServiceProvider::class,

        'Fix_DLTemplate' => EvolutionCMS\Providers\DLTemplateServiceProvider::class,
        'Fix_Phx' => EvolutionCMS\Providers\PhxServiceProvider::class,
        'Fix_ModResource' => EvolutionCMS\Providers\ModResourceServiceProvider::class,
        'Fix_ModUsers' => EvolutionCMS\Providers\ModUsersServiceProvider::class,
        'Fix_Fs' => EvolutionCMS\Providers\FsServiceProvider::class
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
        'DocBlock' => EvolutionCMS\Facades\DocBlock::class,
        'ManagerTheme' => EvolutionCMS\Facades\ManagerTheme::class,
        'UrlProcessor' => EvolutionCMS\Facades\UrlProcessor::class,
        'TemplateProcessor' => EvolutionCMS\Facades\TemplateProcessor::class
    ]
];
