<?php namespace FormLister;

/**
 * Контроллер для восстановления паролей
 * Class Reminder
 * @package FormLister
 */
class Reminder extends Form
{
    protected $user = null;

    protected $mode = 'hash';
    protected $userField = '';
    protected $uidField = '';
    protected $hashField = '';

    /**
     * Reminder constructor.
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
        $lang = $this->lexicon->loadLang('reminder');
        if ($lang) {
            $this->log('Lexicon loaded', array('lexicon' => $lang));
        }
        $hashField = $this->getCFGDef('hashField', 'hash');
        $uidField = $this->getCFGDef('uidField', $this->user->fieldPKName());
        $uidName = $this->getCFGDef('uidName', $uidField);
        $userField = $this->getCFGDef('userField', 'email');
        $this->hashField = $hashField;
        $this->uidField = $uidField;
        $this->userField = $userField;
        if ((isset($_REQUEST[$hashField]) && !empty($_REQUEST[$hashField])) && (isset($_REQUEST[$uidName]) && !empty($_REQUEST[$uidName]))) {
            $this->setFields($_REQUEST);
            $this->mode = 'reset';
            $this->config->setConfig(array(
                'rules'       => $this->getCFGDef('resetRules'),
                'reportTpl'   => $this->getCFGDef('resetReportTpl'),
                'submitLimit' => 0
            ));
        }
        $this->log('Reminder mode is ' . $this->mode);
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($id = $this->modx->getLoginUserID('web')) {
            $this->redirect('exitTo');
            $this->user->edit($id);
            $this->setFields($this->user->toArray());
            $this->renderTpl = $this->getCFGDef('skipTpl', $this->lexicon->getMsg('reminder.default_skipTpl'));
            $this->setValid(false);
        }

        if ($this->mode == 'reset') {
            $this->renderReset();
        }

        return parent::render();
    }


    /**
     *
     */
    public function renderReset()
    {
        $hash = $this->getField($this->hashField);
        $uid = $this->getField($this->getCFGDef('uidName', $this->uidField));
        if (is_scalar($hash) && $hash && $hash == $this->getUserHash($uid)) {
            if ($this->getCFGDef('resetTpl')) {
                $this->setField('user.hash', $hash);
                $this->setField('user.id', $uid);
                $this->renderTpl = $this->getCFGDef('resetTpl');

                return;
            }
            $this->process();
        } else {
            $this->addMessage($this->lexicon->getMsg('reminder.update_failed'));
        }
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
     * @param $uid
     * @return bool|string
     */
    public function getUserHash($uid)
    {
        if (is_null($this->user)) {
            $hash = false;
        } else {
            $userdata = $this->user->edit($uid)->toArray();
            $hash = $this->user->getID() ? md5(json_encode($userdata)) : false;
        }

        return $hash;
    }

    /**
     *
     */
    public function process()
    {
        switch ($this->mode) {
            /**
             * Задаем хэш, отправляем пользователю ссылку для восстановления пароля
             */
            case "hash":
                $uid = $this->getField($this->userField);
                if ($hash = $this->getUserHash($uid)) {
                    $this->setFields($this->user->toArray());
                    $url = $this->getCFGDef('resetTo', isset($this->modx->documentIdentifier) && $this->modx->documentIdentifier > 0 ? $this->modx->documentIdentifier : $this->config['site_start']);
                    $uidName = $this->getCFGDef('uidName', $this->uidField);
                    $this->setField('reset.url', $this->modx->makeUrl($url, "",
                        http_build_query(array($uidName  => $this->getField($this->uidField),
                                               $this->hashField => $hash
                        )),
                        'full'));
                    $this->mailConfig['to'] = $this->user->get('email');
                    parent::process();
                } else {
                    $this->addMessage($this->lexicon->getMsg('reminder.users_only'));
                }
                break;
            /**
             * Если пароль не задан, то создаем пароль
             * Отправляем пользователю письмо с паролем, если указан шаблон такого письма
             * Если не указан, то запрещаем отправку письма, пароль будет показан на экране
             */
            case "reset":
                $uid = $this->getField($this->uidField);
                $hash = $this->getField($this->hashField);
                if ($hash && $hash == $this->getUserHash($uid)) {
                    if ($this->getField('password') == '' && !isset($this->rules['password'])) {
                        $this->setField('password', \APIhelpers::genPass($this->getCFGDef('passwordLength', 6)));
                    }
                    $fields = $this->filterFields($this->getFormData('fields'), array($this->userField, 'password'));
                    $result = $this->user->edit($uid)->fromArray($fields)->save(true);
                    $this->log('Update password', array('data' => $fields, 'result' => $result));
                    if (!$result) {
                        $this->addMessage($this->lexicon->getMsg('reminder.update_failed'));
                    } else {
                        $this->setField('newpassword', $this->getField('password'));
                        $this->setFields($this->user->toArray());
                        if ($this->getCFGDef('resetReportTpl')) {
                            $this->mailConfig['to'] = $this->getField('email');
                        }
                        parent::process();
                    }
                } else {
                    $this->addMessage($this->lexicon->getMsg('reminder.update_failed'));
                    parent::process();
                }
                break;
        }
    }

    /**
     *
     */
    public function postProcess()
    {
        $this->setFormStatus(true);
        switch ($this->mode) {
            case "hash":
                $this->renderTpl = $this->getCFGDef('successTpl',
                    $this->lexicon->getMsg('reminder.default_successTpl'));
                break;
            case "reset":
                $this->redirect();
                $this->renderTpl = $this->getCFGDef('resetSuccessTpl',
                    $this->lexicon->getMsg('reminder.default_resetSuccessTpl'));
        }
    }
}
