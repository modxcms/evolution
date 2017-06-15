<?php namespace FormLister;

/**
 * Контроллер для авторизации пользователя
 */
include_once(MODX_BASE_PATH . 'assets/snippets/FormLister/core/FormLister.abstract.php');

/**
 * Class Login
 * @package FormLister
 */
class Login extends Core
{
    public $user = null;

    /**
     * Login constructor.
     * @param \DocumentParser $modx
     * @param array $cfg
     */
    public function __construct(\DocumentParser $modx, $cfg = array())
    {
        parent::__construct($modx, $cfg);
        $this->user = $this->loadModel(
            $this->getCFGDef('model', '\modUsers'),
            $this->getCFGDef('modelPath', 'assets/lib/MODxAPI/modUsers.php')
        );
        $lang = $this->lexicon->loadLang('login');
        if ($lang) {
            $this->log('Lexicon loaded', array('lexicon' => $lang));
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($this->modx->getLoginUserID('web')) {
            $this->redirect();
            $this->renderTpl = $this->getCFGDef('skipTpl', $this->lexicon->getMsg('login.default_skipTpl'));
            $this->setFormStatus(true);
        };

        return parent::render();
    }

    /**
     *
     */
    public function process()
    {
        if (is_null($this->user)) {
            $this->addMessage($this->lexicon->getMsg('login.user_failed'));

            return;
        }
        $login = $this->getField($this->getCFGDef('loginField', 'username'));
        $password = $this->getField($this->getCFGDef('passwordField', 'password'));
        $remember = $this->getField($this->getCFGDef('rememberField', 'rememberme'));
        if ($this->user->checkBlock($login)) {
            $this->addMessage($this->lexicon->getMsg('login.user_blocked'));

            return;
        }
        $auth = $this->user->testAuth($login, $password, false, true);
        if (!$auth) {
            $this->addMessage($this->lexicon->getMsg('login.user_failed'));

            return;
        }
        $this->user->authUser($login, $remember, 'WebLoginPE', true);
        $this->setFormStatus(true);
        $this->redirect();
        $this->setFields($this->user->toArray());
        $this->renderTpl = $this->getCFGDef('successTpl');
    }
}
