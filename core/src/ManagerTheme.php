<?php namespace EvolutionCMS;

use EvolutionCMS\Interfaces\ManagerThemeInterface;
use EvolutionCMS\Interfaces\CoreInterface;
use Exception;
use View;

class ManagerTheme implements ManagerThemeInterface
{
    /**
     * @var CoreInterface
     */
    protected $core;

    protected $theme;
    protected $namespace = 'manager';
    protected $lang = 'en';
    protected $langName = 'english';
    protected $textDir;
    protected $lexicon = [];
    protected $charset = 'UTF-8';

    protected $actions = [
        /** frame management - show the requested frame */
        1,
        /** show the homepage */
        2,
        /** document data */
        3,
        /** content management */
        85,
        27,
        4,
        5,
        6,
        63,
        51,
        52,
        61,
        62,
        56,
        /** show the wait page - gives the tree time to refresh (hopefully) */
        7,
        /** let the user log out */
        8,
        /** user management */
        87,
        88,
        89,
        90,
        11,
        12,
        32,
        28,
        34,
        33,
        /** role management */
        38,
        35,
        36,
        37,
        /** category management */
        120,
        121,
        /** template management */
        16,
        19,
        20,
        21,
        96,
        117,
        /** snippet management */
        22,
        23,
        24,
        25,
        98,
        /** htmlsnippet management */
        78,
        77,
        79,
        80,
        97,
        /** show the credits page */
        18,
        /** empty cache & synchronisation */
        26,
        /** Module management */
        106,
        107,
        108,
        109,
        110,
        111,
        112,
        113,
        /** plugin management */
        100,
        101,
        102,
        103,
        104,
        105,
        119,
        /** view phpinfo */
        200,
        /** errorpage */
        29,
        /** file manager */
        31,
        /** access permissions */
        40,
        91,
        /** access groups processor */
        41,
        92,
        /** settings editor */
        17,
        118,
        /** save settings */
        30,
        /** system information */
        53,
        /** optimise table */
        54,
        /** view logging */
        13,
        /** empty logs */
        55,
        /** calls test page    */
        999,
        /** Empty recycle bin */
        64,
        /** Messages */
        10,
        /** Delete a message */
        65,
        /** Send a message */
        66,
        /** Remove locks */
        67,
        /** Site schedule */
        70,
        /** Search */
        71,
        /** About */
        59,
        /** Add weblink */
        72,
        /** User management */
        75,
        99,
        86,
        /** template/ snippet management */
        76,
        /** Export to file */
        83,
        /** Resource Selector  */
        84,
        /** Backup Manager */
        93,
        /** Duplicate Document */
        94,
        /** Import Document from file */
        95,
        /** Help */
        9,
        /** Template Variables - Based on Apodigm's Docvars */
        300,
        301,
        302,
        303,
        304,
        305,
        /** Event viewer: show event message log */
        114,
        115,
        116,
        501
    ];

    public function __construct(CoreInterface $core, $theme = '')
    {
        $this->core = $core;

        if (empty($theme)) {
            $theme = $this->core->getConfig('manager_theme');
        }

        $this->theme = $theme;

        $this->loadLang(
            $this->core->getConfig('manager_language')
        );
    }

    protected function loadLang($lang = 'english')
    {
        $_lang = array();
        $modx_lang_attribute = $this->getLang();
        $modx_manager_charset = $this->getCharset();
        $modx_textdir = $this->getTextDir();

        include_once MODX_MANAGER_PATH . "includes/lang/english.inc.php";

        // now include_once different language file as english
        if (!isset($lang) || !file_exists(MODX_MANAGER_PATH . "includes/lang/" . $lang . ".inc.php")) {
            $lang = 'english'; // if not set, get the english language file.
        }

        // $length_eng_lang = count($_lang);
        //// Not used for now, required for difference-check with other languages than english (i.e. inside installer)

        if ($lang !== "english" && file_exists(MODX_MANAGER_PATH . "includes/lang/" . $lang . ".inc.php")) {
            include_once MODX_MANAGER_PATH . "includes/lang/" . $lang . ".inc.php";
        }

        // allow custom language overrides not altered by future EVO-updates
        if (file_exists(MODX_MANAGER_PATH . "includes/lang/override/" . $lang . ".inc.php")) {
            include_once MODX_MANAGER_PATH . "includes/lang/override/" . $lang . ".inc.php";
        }

        foreach ($_lang as $k => $v) {
            if (strpos($v, '[+') !== false) {
                $_lang[$k] = str_replace(
                    ['[+MGR_DIR+]'],
                    [MGR_DIR],
                    $v
                );
            }
        }
        $this->lexicon = $_lang;
        $this->langName = $lang;
        $this->lang = $modx_lang_attribute;
        $this->textDir = $modx_textdir;
        $this->setCharset($modx_manager_charset);

        $this->core->setConfig('lang_code', $this->getLang());
        $this->core->setConfig('manager_language', $this->getLangName());

        return $lang;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function getLangName()
    {
        return $this->langName;
    }

    public function getTextDir($notEmpty = null)
    {
        return ($notEmpty === null) ? $this->textDir : (empty($this->textDir) ? '' : $notEmpty);
    }

    public function getLexicon($key = null)
    {
        return $key === null ? $this->lexicon : get_by_key($this->lexicon, $key, '');
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    public function view($name, array $params = [])
    {
        return View::make(
            $this->namespace . '::' . $name,
            $this->getViewAttributes($params)
        );
    }

    public function getViewAttributes(array $params = [])
    {
        $baseParams = [
            'modx'                 => $this->core,
            'modx_lang_attribute'  => $this->getLang(),
            'modx_manager_charset' => $this->getCharset(),
            'manager_theme'        => $this->getTheme(),
            'modx_textdir'         => $this->getTextDir(),
            'manager_language'     => $this->getLangName(),
            '_lang'                => $this->getLexicon()
        ];

        return array_merge($baseParams, $params);
    }

    protected function getController($action)
    {
        $controller = $this->makeControllerPath($action);

        if (!\in_array($action, $this->actions, true) || !file_exists($controller)) {
            $controller = null;
        }

        return $controller;
    }

    protected function makeControllerPath($action)
    {
        return MODX_MANAGER_PATH . 'controllers/' . $action . '.php';
    }

    public function getFileProcessor($filepath, $theme = null)
    {
        if ($theme === null) {
            $theme = $this->getTheme();
        }

        if (is_file(MODX_MANAGER_PATH . "/media/style/" . $theme . "/" . $filepath)) {
            $element = MODX_MANAGER_PATH . "/media/style/" . $theme . "/" . $filepath;
        } else {
            $element = $filepath;
        }

        return $element;
    }
    public function handle($action)
    {
        if ($controller = $this->getController($action)) {
            include_once $controller;
        } else {
            /********************************************************************/
            /* default action: show not implemented message                     */
            /********************************************************************/
            // say that what was requested doesn't do anything yet
            include_once $this->getFileProcessor("includes/header.inc.php");
            echo "
			<div class='sectionHeader'>" . $this->getLexicon('functionnotimpl') . "</div>
			<div class='sectionBody'>
				<p>" . $this->getLexicon('functionnotimpl_message') . "</p>
			</div>
		";
            include_once $this->getFileProcessor("includes/footer.inc.php");
        }
    }

    public function getItemId()
    {
        $out = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
        if ($out <= 0) {
           $out = null;
        }

        return $out;
    }

    public function getActionId()
    {
        // OK, let's retrieve the action directive from the request
        $option = array('min_range' => 1, 'max_range' => 2000);
        if (isset($_GET['a']) && isset($_POST['a'])) {
            $this->core->webAlertAndQuit(
                $this->getLexicon('error_double_action')
            );
        } elseif (isset($_GET['a'])) {
            $action = (int)filter_input(INPUT_GET, 'a', FILTER_VALIDATE_INT, $option);
        } elseif (isset($_POST['a'])) {
            $action = (int)filter_input(INPUT_POST, 'a', FILTER_VALIDATE_INT, $option);
        } else {
            $action = null;
        }

        return $action;
        //return isset($_REQUEST['a']) ? (int)$_REQUEST['a'] : 1;
    }

    public function isAuthManager()
    {
        $out = null;

        if (isset($_SESSION['mgrValidated']) && $_SESSION['usertype'] != 'manager') {
            //		if (isset($_COOKIE[session_name()])) {
            //			setcookie(session_name(), '', 0, MODX_BASE_URL);
            //		}
            @session_destroy();
            // start session
            //	    startCMSSession();
        }

        // andrazk 20070416 - if installer is running, destroy active sessions
        if (file_exists(MODX_BASE_PATH . 'assets/cache/installProc.inc.php')) {
            include_once(MODX_BASE_PATH . 'assets/cache/installProc.inc.php');
            if (isset($installStartTime)) {
                if ((time() - $installStartTime) > 5 * 60) { // if install flag older than 5 minutes, discard
                    unset($installStartTime);
                    @ chmod(MODX_BASE_PATH . 'assets/cache/installProc.inc.php', 0755);
                    unlink(MODX_BASE_PATH . 'assets/cache/installProc.inc.php');
                } else {
                    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                        if (isset($_COOKIE[session_name()])) {
                            session_unset();
                            @session_destroy();
                            //					setcookie(session_name(), '', 0, MODX_BASE_URL);
                        }
                        $installGoingOn = 1;
                    }
                }
            }
        }

        // andrazk 20070416 - if session started before install and was not destroyed yet
        if (defined('EVO_INSTALL_TIME')) {
            if (isset($_SESSION['mgrValidated'])) {
                if (isset($_SESSION['modx.session.created.time'])) {
                    if ($_SESSION['modx.session.created.time'] < EVO_INSTALL_TIME) {
                        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                            if (isset($_COOKIE[session_name()])) {
                                session_unset();
                                @session_destroy();
                                //						setcookie(session_name(), '', 0, MODX_BASE_URL);
                            }
                            header('HTTP/1.0 307 Redirect');
                            header('Location: ' . MODX_MANAGER_URL . 'index.php?installGoingOn=2');
                        }
                    }
                }
            }
        }

        return isset($_SESSION['mgrValidated']);
    }

    public function hasManagerAccess()
    {
        // check if user is allowed to access manager interface
        return (bool)$this->core->getConfig('allow_manager_access', 1) === true;
    }

    public function getManagerStartupPageId()
    {
        $homeId = (int)$this->core->getConfig('manager_login_startup', 0);
        if($homeId <= 0) {
            $homeId = $this->core->getConfig('site_start', 1);
        }

        return $homeId;
    }

    public function renderAccessPage()
    {
        $homeurl = $this->core->makeUrl($this->getManagerStartupPageId());
        $logouturl = MODX_MANAGER_URL.'index.php?a=8';

        $this->core->setPlaceholder('modx_charset',$this->core->get('ManagerTheme')->getCharset());
        $this->core->setPlaceholder('theme',$this->core->get('ManagerTheme')->getTheme());

        $this->core->setPlaceholder('site_name',$this->core->getPhpCompat()->entities($this->core->getConfig('site_name')));
        $this->core->setPlaceholder('logo_slogan',$this->getLexicon("logo_slogan"));
        $this->core->setPlaceholder('manager_lockout_message',$this->getLexicon("manager_lockout_message"));

        $this->core->setPlaceholder('home',$this->getLexicon("home"));
        $this->core->setPlaceholder('homeurl',$homeurl);
        $this->core->setPlaceholder('logout',$this->getLexicon("logout"));
        $this->core->setPlaceholder('logouturl',$logouturl);
        $this->core->setPlaceholder('manager_theme_url',MODX_MANAGER_URL . 'media/style/' . $this->getTheme() . '/');
        $this->core->setPlaceholder('year',date('Y'));

        // load template
        if(!isset($this->core->config['manager_lockout_tpl']) || empty($this->core->config['manager_lockout_tpl'])) {
            $this->core->config['manager_lockout_tpl'] = MODX_MANAGER_PATH . 'media/style/common/manager.lockout.tpl';
        }

        $target = $this->core->config['manager_lockout_tpl'];
        $target = str_replace('[+base_path+]', MODX_BASE_PATH, $target);
        $target = $this->core->mergeSettingsContent($target);

        if(substr($target,0,1)==='@') {
            if(substr($target,0,6)==='@CHUNK') {
                $target = trim(substr($target,7));
                $lockout_tpl = $this->core->getChunk($target);
            }
            elseif(substr($target,0,5)==='@FILE') {
                $target = trim(substr($target,6));
                $lockout_tpl = file_get_contents($target);
            }
        } else {
            $chunk = $this->core->getChunk($target);
            if($chunk!==false && !empty($chunk)) {
                $lockout_tpl = $chunk;
            }
            elseif(is_file(MODX_BASE_PATH . $target)) {
                $target = MODX_BASE_PATH . $target;
                $lockout_tpl = file_get_contents($target);
            }
            elseif(is_file(MODX_MANAGER_PATH . 'media/style/' . $this->core->config['manager_theme'] . '/manager.lockout.tpl')) {
                $target = MODX_MANAGER_PATH . 'media/style/' . $this->core->config['manager_theme'] . '/manager.lockout.tpl';
                $lockout_tpl = file_get_contents($target);
            }
            elseif(is_file(MODX_MANAGER_PATH . 'media/style/' . $this->core->config['manager_theme'] . '/templates/actions/manager.lockout.tpl')) {
                $target = MODX_MANAGER_PATH . 'media/style/' . $this->core->config['manager_theme'] . '/templates/actions/manager.lockout.tpl';
                $login_tpl = file_get_contents($target);
            }
            elseif(is_file(MODX_MANAGER_PATH . 'media/style/' . $this->core->config['manager_theme'] . '/html/manager.lockout.html')) { // ClipperCMS compatible
                $target = MODX_MANAGER_PATH . 'media/style/' . $this->core->config['manager_theme'] . '/html/manager.lockout.html';
                $lockout_tpl = file_get_contents($target);
            }
            else {
                $target = MODX_MANAGER_PATH . 'media/style/common/manager.lockout.tpl';
                $lockout_tpl = file_get_contents($target);
            }
        }

        // merge placeholders
        $lockout_tpl = $this->core->mergePlaceholderContent($lockout_tpl);
        $regx = strpos($lockout_tpl,'[[+')!==false ? '~\[\[\+(.*?)\]\]~' : '~\[\+(.*?)\+\]~'; // little tweak for newer parsers
        $lockout_tpl = preg_replace($regx, '', $lockout_tpl); //cleanup

        return $lockout_tpl;
    }

    public function renderLoginPage()
    {
        $this->core->setPlaceholder('modx_charset', $this->getCharset());
        $this->core->setPlaceholder('theme', $this->getTheme());
        $this->core->setPlaceholder(
            'favicon',
            (file_exists(MODX_BASE_PATH . 'favicon.ico') ?
                MODX_SITE_URL . 'favicon.ico' :
                'media/style/' . $this->getTheme() . '/images/favicon.ico'
            )
        );

        // invoke OnManagerLoginFormPrerender event
        $evtOut = $this->core->invokeEvent('OnManagerLoginFormPrerender');
        $html = is_array($evtOut) ? implode('', $evtOut) : '';
        $this->core->setPlaceholder('OnManagerLoginFormPrerender', $html);

        $this->core->setPlaceholder('site_name', $this->core->getPhpCompat()->entities($this->core->getConfig('site_name')));
        $this->core->setPlaceholder('manager_path', MGR_DIR);
        $this->core->setPlaceholder('logo_slogan', $this->getLexicon('logo_slogan'));
        $this->core->setPlaceholder('login_message', $this->getLexicon("login_message"));
        $this->core->setPlaceholder('manager_theme_url', MODX_MANAGER_URL . 'media/style/' . $this->getTheme() . '/');
        $this->core->setPlaceholder('year', date('Y'));

        // set login logo image
        if ( !empty($this->core->config['login_logo']) ) {
            $this->core->setPlaceholder('login_logo', MODX_SITE_URL . $this->core->getConfig('login_logo'));
        } else {
            $this->core->setPlaceholder('login_logo', MODX_MANAGER_URL . 'media/style/' . $this->getTheme() . '/images/login/default/login-logo.png');
        }

        // set login background image
        if ( !empty($this->core->config['login_bg']) ) {
            $this->core->setPlaceholder('login_bg', MODX_SITE_URL . $this->core->config['login_bg']);
        } else {
            $this->core->setPlaceholder('login_bg', MODX_MANAGER_URL . 'media/style/' . $this->core->config['manager_theme'] . '/images/login/default/login-background.jpg');
        }

        // set form position css class
        $this->core->setPlaceholder('login_form_position_class', 'loginbox-' . $this->core->config['login_form_position']);

        switch ($this->core->config['manager_theme_mode']) {
            case '1':
                $this->core->setPlaceholder('manager_theme_style', 'lightness');
                break;
            case '2':
                $this->core->setPlaceholder('manager_theme_style', 'light');
                break;
            case '3':
                $this->core->setPlaceholder('manager_theme_style', 'dark');
                break;
            case '4':
                $this->core->setPlaceholder('manager_theme_style', 'darkness');
                break;
        }

        // andrazk 20070416 - notify user of install/update
        if (isset($_GET['installGoingOn'])) {
            $installGoingOn = $_GET['installGoingOn'];
        }
        if (isset($installGoingOn)) {
            switch ($installGoingOn) {
                case 1 :
                    $this->core->setPlaceholder('login_message',
                        "<p><span class=\"fail\">" . $this->getLexicon("login_cancelled_install_in_progress") . "</p><p>" . $this->getLexicon("login_message") . "</p>");
                    break;
                case 2 :
                    $this->core->setPlaceholder('login_message',
                        "<p><span class=\"fail\">" . $this->getLexicon("login_cancelled_site_was_updated") . "</p><p>" . $this->getLexicon("login_message") . "</p>");
                    break;
            }
        }

        if ($this->core->config['use_captcha'] == 1) {
            $this->core->setPlaceholder('login_captcha_message', $this->getLexicon("login_captcha_message"));
            $this->core->setPlaceholder('captcha_image',
                '<a href="' . MODX_MANAGER_URL . '" class="loginCaptcha"><img id="captcha_image" src="' . MODX_MANAGER_URL . 'captcha.php?rand=' . rand() . '" alt="' . $this->getLexicon("login_captcha_message") . '" /></a>');
            $this->core->setPlaceholder('captcha_input',
                '<label>' . $this->getLexicon("captcha_code") . '</label> <input type="text" name="captcha_code" tabindex="3" value="" />');
        }

        // login info
        $uid = isset($_COOKIE['modx_remember_manager']) ? preg_replace('/[^a-zA-Z0-9\-_@\.]*/', '',
            $_COOKIE['modx_remember_manager']) : '';
        $this->core->setPlaceholder('uid', $uid);
        $this->core->setPlaceholder('username', $this->getLexicon("username"));
        $this->core->setPlaceholder('password', $this->getLexicon("password"));

        // remember me
        $html = isset($_COOKIE['modx_remember_manager']) ? 'checked="checked"' : '';
        $this->core->setPlaceholder('remember_me', $html);
        $this->core->setPlaceholder('remember_username', $this->getLexicon("remember_username"));
        $this->core->setPlaceholder('login_button', $this->getLexicon("login_button"));

        // invoke OnManagerLoginFormRender event
        $evtOut = $this->core->invokeEvent('OnManagerLoginFormRender');
        $html = is_array($evtOut) ? '<div id="onManagerLoginFormRender">' . implode('', $evtOut) . '</div>' : '';
        $this->core->setPlaceholder('OnManagerLoginFormRender', $html);

        // load template
        $target = $this->core->getConfig('manager_login_tpl');
        $target = str_replace('[+base_path+]', MODX_BASE_PATH, $target);
        $target = $this->core->mergeSettingsContent($target);

        $login_tpl = null;
        if (substr($target, 0, 1) === '@') {
            if (substr($target, 0, 6) === '@CHUNK') {
                $target = trim(substr($target, 7));
                $login_tpl = $this->core->getChunk($target);
            } elseif (substr($target, 0, 5) === '@FILE') {
                $target = trim(substr($target, 6));
                $login_tpl = file_get_contents($target);
            }
        } else {
            $theme_path = MODX_MANAGER_PATH . 'media/style/' . $this->core->config['manager_theme'] . '/';
            if (is_file($theme_path . 'style.php')) {
                include($theme_path . 'style.php');
            }
            $chunk = $this->core->getChunk($target);
            if ($chunk !== false && !empty($chunk)) {
                $login_tpl = $chunk;
            } elseif (is_file(MODX_BASE_PATH . $target)) {
                $target = MODX_BASE_PATH . $target;
                $login_tpl = file_get_contents($target);
            } elseif (is_file($target)) {
                $login_tpl = file_get_contents($target);
            } elseif (is_file($theme_path . 'login.tpl')) {
                $target = $theme_path . 'login.tpl';
                $login_tpl = file_get_contents($target);
            } elseif (is_file($theme_path . 'templates/actions/login.tpl')) {
                $target = $theme_path . 'templates/actions/login.tpl';
                $login_tpl = file_get_contents($target);
            } elseif (is_file($theme_path . 'html/login.html')) { // ClipperCMS compatible
                $target = $theme_path . 'html/login.html';
                $login_tpl = file_get_contents($target);
            } else {
                $target = MODX_MANAGER_PATH . 'media/style/common/login.tpl';
                $login_tpl = file_get_contents($target);
            }
        }

        // merge placeholders
        $login_tpl = $this->core->mergePlaceholderContent($login_tpl);
        $regx = strpos($login_tpl,
            '[[+') !== false ? '~\[\[\+(.*?)\]\]~' : '~\[\+(.*?)\+\]~'; // little tweak for newer parsers
        $login_tpl = preg_replace($regx, '', $login_tpl); //cleanup

        return $login_tpl;
    }

    public function saveAction($action)
    {
        $flag = false;

        // save page to manager object
        $this->core->getManagerApi()->action = $action;

        if ((int)$action > 1) {
            $sql = sprintf(
                "REPLACE INTO %s (sid, internalKey, username, lasthit, action, id) VALUES ('%s', %d, '%s', %d, '%s', %s)",
                $this->core->getDatabase()->getFullTableName('active_users'),
                session_id(),
                $this->core->getLoginUserID(),
                $_SESSION['mgrShortname'],
                $this->core->tstart,
                (string)$action,
                $this->getItemId() === null ?
                    var_export(null, true) : $this->getItemId()
            );
            $this->core->getDatabase()->query($sql);
            $flag = true;
        }

        return $flag;
    }
}
