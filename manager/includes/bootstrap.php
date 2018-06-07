<?php
/**
 * @see https://github.com/theseer/Autoload
 */
spl_autoload_register(
    function ($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'evolutioncms\\cache'                             => '/Cache.php',
                'evolutioncms\\core'                              => '/Core.php',
                'evolutioncms\\event'                             => '/Event.php',
                'evolutioncms\\managertheme'                      => '/ManagerTheme.php',
                'evolutioncms\\interfaces\\captchainterface'      => '/Interfaces/CaptchaInterface.php',
                'evolutioncms\\interfaces\\coreinterface'         => '/Interfaces/CoreInterface.php',
                'evolutioncms\\interfaces\\databaseinterface'     => '/Interfaces/DatabaseInterface.php',
                'evolutioncms\\interfaces\\eventinterface'        => '/Interfaces/EventInterface.php',
                'evolutioncms\\interfaces\\maketableinterface'    => '/Interfaces/MakeTableInterface.php',
                'evolutioncms\\interfaces\\managerthemeinterface' => '/Interfaces/ManagerThemeInterface.php',
                'evolutioncms\\interfaces\\menuinterface'         => '/Interfaces/MenuInterface.php',
                'evolutioncms\\interfaces\\mysqldumperinterface'  => '/Interfaces/MysqlDumperInterface.php',
                'evolutioncms\\interfaces\\paginginateinterface'  => '/Interfaces/PaginginateInterface.php',
                'evolutioncms\\legacy\\categories'                => '/Legacy/Categories.php',
                'evolutioncms\\legacy\\errorhandler'              => '/Legacy/ErrorHandler.php',
                'evolutioncms\\legacy\\loghandler'                => '/Legacy/LogHandler.php',
                'evolutioncms\\legacy\\mgrresources'              => '/Legacy/mgrResources.php',
                'evolutioncms\\legacy\\modulecategoriesmanager'   => '/Legacy/ModuleCategoriesManager.php',
                'evolutioncms\\legacy\\permissions'               => '/Legacy/Permissions.php',
                'evolutioncms\\legacy\\templateparser'            => '/Legacy/TemplateParser.php',
                'evolutioncms\\support\\captcha'                  => '/Support/Captcha.php',
                'evolutioncms\\support\\maketable'                => '/Support/MakeTable.php',
                'evolutioncms\\support\\menu'                     => '/Support/Menu.php',
                'evolutioncms\\support\\mysqldumper'              => '/Support/MysqlDumper.php',
                'evolutioncms\\support\\paginate'                 => '/Support/Paginate.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . '/src' . $classes[$cn];
        }
    },
    true,
    false
);

global $site_sessionname;
require_once 'functions/actions/bkmanager.php';
require_once 'functions/actions/files.php';
require_once 'functions/actions/help.php';
require_once 'functions/actions/import.php';
require_once 'functions/actions/logging.php';
require_once 'functions/actions/mutate_content.php';
require_once 'functions/actions/mutate_content.php';
require_once 'functions/actions/mutate_role.php';
require_once 'functions/actions/search.php';
require_once 'functions/actions/settings.php';

require_once 'functions/helper.php';
require_once 'functions/preload.php';
require_once 'functions/tv.php';
require_once 'functions/nodes.php';
require_once 'functions/processors.php';

$site_sessionname = genEvoSessionName(); // For legacy extras not using startCMSSession
