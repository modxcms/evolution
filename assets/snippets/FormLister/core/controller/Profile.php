<?php namespace FormLister;

/**
 * Контроллер для редактирования профиля
 */
include_once(MODX_BASE_PATH . 'assets/snippets/FormLister/core/controller/Form.php');

/**
 * Class Profile
 * @package FormLister
 */
class Profile extends Form
{
    /**
     * @var \modUsers
     */
    public $user = null;

    /**
     * Profile constructor.
     * @param \DocumentParser $modx
     * @param array $cfg
     */
    public function __construct(\DocumentParser $modx, $cfg = array())
    {
        parent::__construct($modx, $cfg);
        $lang = $this->lexicon->loadLang('profile');
        if ($lang) {
            $this->log('Lexicon loaded', array('lexicon' => $lang));
        }
        $uid = $modx->getLoginUserId('web');
        if ($uid) {
            $user = $this->loadModel(
                $this->getCFGDef('model', '\modUsers'),
                $this->getCFGDef('modelPath', 'assets/lib/MODxAPI/modUsers.php')
            );
            $this->user = $user->edit($uid);
            if ($ds = $this->getCFGDef('defaultsSources')) {
                $defaultsSources = "{$ds};user:web";
            } else {
                $defaultsSources = "user:web";
            }
            $this->config->setConfig(array(
                'defaultsSources' => $defaultsSources,
            ));
        }
    }

    /**
     * @return null|string
     */
    public function render()
    {
        if (is_null($this->user)) {
            $this->redirect('exitTo');
            $this->renderTpl = $this->getCFGDef('skipTpl', $this->lexicon->getMsg('profile.default_skipTpl'));
        }

        return parent::render();
    }

    /**
     * @param string $param
     * @return array|mixed|\xNop
     */
    public function getValidationRules($param = 'rules')
    {
        $rules = parent::getValidationRules($param);
        $password = $this->getField('password');
        if (empty($password)) {
            $this->forbiddenFields[] = 'password';
            if (isset($rules['password'])) {
                unset($rules['password']);
            }
            if (isset($rules['repeatPassword'])) {
                unset($rules['repeatPassword']);
            }
        } else {
            if (isset($rules['repeatPassword']['equals'])) {
                $rules['repeatPassword']['equals']['params'] = $this->getField('password');
            }
        }

        return $rules;
    }

    /**
     * @param $fl
     * @param $value
     * @return bool
     */
    public static function uniqueEmail($fl, $value)
    {
        $result = true;
        if (!is_null($fl->user) && ($fl->user->get("email") !== $value)) {
            $fl->user->set('email', $value);
            $result = $fl->user->checkUnique('web_user_attributes', 'email', 'internalKey');
        }

        return $result;
    }

    public static function uniqueUsername($fl, $value)
    {
        $result = true;
        if (!is_null($fl->user)) {
            $fl->user->set('username', $value);
            $result = $fl->user->checkUnique('web_users', 'username');
        }

        return $result;
    }

    /**
     *
     */
    public function process()
    {
        if ($this->user->get('username') == $this->user->get('email') && $this->getField('email') && !$this->getField('username')) {
            $this->setField('username', $this->getField('email'));
            $this->allowedFields[] = 'username';
        }
        $newpassword = $this->getField('password');
        $password = $this->user->get('password');
        $fields = $this->filterFields($this->getFormData('fields'), $this->allowedFields, $this->forbiddenFields);
        $result = $this->user->fromArray($fields)->save(true);
        $this->log('Update profile', array('data' => $fields, 'result' => $result));
        if ($result) {
            $this->setFormStatus(true);
            if (!empty($newpassword) && ($password !== $this->user->getPassword($newpassword))) {
                $this->user->logOut('WebLoginPE', true);
                $this->redirect('exitTo');
            }
            $this->redirect();
            if ($successTpl = $this->getCFGDef('successTpl')) {
                $this->renderTpl = $successTpl;
            } else {
                $this->addMessage($this->lexicon->getMsg('profile.update_success'));
            }
        } else {
            $this->addMessage($this->lexicon->getMsg('profile.update_failed'));
        }
    }
}
