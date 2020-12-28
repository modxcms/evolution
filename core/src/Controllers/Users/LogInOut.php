<?php namespace EvolutionCMS\Controllers\Users;

use EvolutionCMS\Controllers\AbstractController;
use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class LogInOut extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = '';

    /**
     * {@inheritdoc}
     */
    public function checkLocked(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        return true;
    }

    public function process(): bool
    {
        switch ($this->getIndex()) {
            case 8:
                $this->logout();
                break;
            case 0:
                $this->login();
                break;
        }

        return true;
    }

    public function logout()
    {
        \UserManager::logout();
        // show login screen
        header('Location: ' . MODX_MANAGER_URL);
        exit();
    }

    public function login()
    {
        if (!isset($_GET['hash'])) {
            $this->simpleLogin();
        } else {
            $this->loginFromHash();
        }

    }

    public function simpleLogin()
    {
        if (EvolutionCMS()->getConfig('use_captcha') == 1) {
            if (!isset ($_SESSION['veriword'])) {
                jsAlert(\Lang::get('global.login_processor_captcha_config'));
                exit();
            } elseif ($_SESSION['veriword'] != $_POST['captcha_code']) {
                jsAlert(\Lang::get('global.login_processor_bad_code'));
                exit();
            }
        }
        try {
            $user = \UserManager::login($_POST);
        } catch (ServiceActionException $exception) {
            jsAlert($exception->getMessage());
            exit();
        }

        $id = 0;
// check if we should redirect user to a web page
        $setting = \EvolutionCMS\Models\UserSetting::where('user', $user->getKey())
            ->where('setting_name', 'manager_login_startup')->first();
        if (!is_null($setting)) {
            $id = (int)$setting->setting_value;
        }
        $ajax = (int)get_by_key($_POST, 'ajax', 0, 'is_scalar');
        if ($id > 0) {
            $header = 'Location: ' . \UrlProcessor::makeUrl($id, '', '', 'full');
            if ($ajax === 1) {
                echo $header;
            } else {
                header($header);
            }
        } else {
            $header = 'Location: ' . MODX_MANAGER_URL;
            if ($ajax === 1) {
                echo $header;
            } else {
                header($header);
            }
        }
        exit();
    }

    public function loginFromHash()
    {
        try {
            \UserManager::hashLogin($_GET);
        } catch (ServiceActionException $exception) {
            jsAlert($exception->getMessage());
            exit();
        }

        header('Location: ' . MODX_MANAGER_URL.'#?a=28');
        exit();
    }


}
