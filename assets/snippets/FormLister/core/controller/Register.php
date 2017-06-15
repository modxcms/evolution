<?php namespace FormLister;

/**
 * Контроллер для регистрации пользователя
 */
include_once(MODX_BASE_PATH . 'assets/snippets/FormLister/core/controller/Form.php');

/**
 * Class Register
 * @package FormLister
 */
class Register extends Form
{
    /**
     * @var \modUsers
     */
    public $user = null;

    /**
     * Register constructor.
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
        $lang = $this->lexicon->loadLang('register');
        if ($lang) {
            $this->log('Lexicon loaded', array('lexicon' => $lang));
        }
    }

    /**
     * @return null|string
     */
    public function render()
    {
        if ($this->modx->getLoginUserID('web')) {
            $this->redirect('exitTo');
            $this->renderTpl = $this->getCFGDef('skipTpl', $this->lexicon->getMsg('register.default_skipTpl'));
        };

        return parent::render();
    }

    /**
     * @param string $param
     * @return array|mixed|\xNop
     */
    public function getValidationRules($param = 'rules')
    {
        $rules = parent::getValidationRules($param);
        if (isset($rules['password']) && isset($rules['repeatPassword']) && !empty($this->getField('password'))) {
            if (isset($rules['repeatPassword']['equals'])) {
                $rules['repeatPassword']['equals']['params'] = $this->getField('password');
            }
        }

        return $rules;
    }

    /**
     * Custom validation rule
     * Проверяет уникальность email
     * @param $fl
     * @param $value
     * @return bool
     */
    public static function uniqueEmail($fl, $value)
    {
        $result = true;
        if (!is_null($fl->user)) {
            $fl->user->set('email', $value);
            $result = $fl->user->checkUnique('web_user_attributes', 'email', 'internalKey');
        }

        return $result;
    }

    /**
     * Custom validation rule
     * Проверяет уникальность имени пользователя
     * @param $fl
     * @param $value
     * @return bool
     */
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
        $this->allowedFields[] = 'username';
        $this->allowedFields[] = 'password';
        $this->allowedFields[] = 'email';
        //регистрация без логина, по емейлу
        if ($this->getField('username') == '') {
            $this->setField('username', $this->getField('email'));
        }
        if ($this->checkSubmitProtection()) {
            return;
        }
        //регистрация со случайным паролем
        if ($this->getField('password') == '' && !isset($this->rules['password'])) {
            $this->setField('password', \APIhelpers::genPass($this->getCFGDef('passwordLength', 6)));
        }
        $fields = $this->filterFields($this->getFormData('fields'), $this->allowedFields, $this->forbiddenFields);
        $result = $this->user->create($fields)->save(true);
        $this->log('Register user', array('data' => $fields, 'result' => $result));
        if (!$result) {
            $this->addMessage($this->lexicon->getMsg('register.registration_failed'));
        } else {
            $this->addWebUserToGroups($this->user->getID(), $this->getCFGDef('userGroups'));
            parent::process();
        }
    }

    /**
     *
     */
    public function postProcess()
    {
        $this->redirect();
        $this->setFormStatus(true); //результат отправки писем значения не имеет
        $this->renderTpl = $this->getCFGDef('successTpl', $this->lexicon->getMsg('register.default_successTpl'));
    }

    /**
     * Добавляет пользователя в группы
     * @param int $uid
     * @param string $groups
     * @return $this
     */
    public function addWebUserToGroups($uid = 0, $groups = '')
    {
        if ($groups == '' || !$uid) {
            return $this;
        }
        $groups = explode('||', $groups);
        foreach ($groups as &$group) {
            $group = $this->modx->db->escape(trim($group));
        }
        $groups = "'" . implode("','", $groups) . "'";
        $groupNames = $this->modx->db->query("SELECT `id` FROM " . $this->modx->getFullTableName('webgroup_names') . " WHERE `name` IN (" . $groups . ")");
        while ($row = $this->modx->db->getRow($groupNames)) {
            $webGroupId = $row['id'];
            $this->modx->db->query("REPLACE INTO " . $this->modx->getFullTableName('web_groups') . " (`webgroup`, `webuser`) VALUES ('" . $webGroupId . "', '" . $uid . "')");
        }

        return $this;
    }
}
