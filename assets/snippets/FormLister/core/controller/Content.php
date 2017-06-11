<?php namespace FormLister;

/**
 * Контроллер для создания записей
 */
include_once(MODX_BASE_PATH . 'assets/snippets/FormLister/core/controller/Form.php');

/**
 * Class Content
 * @package FormLister
 */
class Content extends Form
{
    protected $mode = 'create';
    protected $id = 0;
    protected $owner = 0;
    /**
     * @var \autoTable $content
     */
    public $content = null;
    public $user = null;

    /**
     * Content constructor.
     * @param \DocumentParser $modx
     * @param array $cfg
     */
    public function __construct(\DocumentParser $modx, $cfg = array())
    {
        parent::__construct($modx, $cfg);
        $lang = $this->lexicon->loadLang('content');
        if ($lang) {
            $this->log('Lexicon loaded', array('lexicon' => $lang));
        }
        $this->content = $this->loadModel(
            $this->getCFGDef('model', '\modResource'),
            $this->getCFGDef('modelPath', 'assets/lib/MODxAPI/modResource.php')
        );
        if (is_null($this->content)) {
            return;
        }
        $this->user = $this->loadModel(
            $this->getCFGDef('userModel', '\modUsers'),
            $this->getCFGDef('userModelPath', 'assets/lib/MODxAPI/modUsers.php')
        );
        $idField = $this->getCFGDef('idField', 'id');
        $id = $this->getCFGDef($idField);
        if ($idField) {
            if ($id) {
                $this->mode = 'edit';
                $this->id = $id;
            } elseif (isset($_REQUEST[$idField]) && is_scalar($_REQUEST[$idField]) && (int)$_REQUEST[$idField] > 0) {
                $this->id = (int)$_REQUEST[$idField];
                $this->mode = 'edit';
            }
        }
        $data = array();
        if ($this->mode == 'edit') {
            $data = $this->content->edit($this->id);
            $this->mailConfig['noemail'] = 1;
            if ($ds = $this->getCFGDef('defaultsSources')) {
                $defaultsSources = "{$ds};param:contentdata";
            } else {
                $defaultsSources = "param:contentdata";
            }
            $this->config->setConfig(array(
                'defaultsSources' => $defaultsSources,
                'contentdata'     => $data->toArray()
            ));
        }
        $this->log('Content mode is ' . $this->mode, array('data' => $data));
    }


    /**
     * @return null|string
     */
    public function render()
    {
        $uid = $this->modx->getLoginUserID('web');
        $owner = $this->getCFGDef('ownerField', 'aid');
        $mode = $this->mode;
        //Если пользователь не авторизован и запрет для анонимов создавать записи
        if (!$uid && $this->getCFGDef('onlyUsers', 1) && $mode == 'create') {
            $this->redirect('exitTo');
            $this->renderTpl = $this->getCFGDef('skipTpl', $this->lexicon->getMsg('create.default_skipTpl'));
        }

        //Если пользователь не авторизован и пытается редактировать запись
        if (!$uid && $mode == 'edit') {
            $this->redirect('exitTo');
            $this->renderTpl = $this->getCFGDef('skipEditTpl', $this->lexicon->getMsg('edit.default_skipEditTpl'));
        }

        //Если пользователь авторизован, но не состоит в разрешенных группах
        if ($uid && $this->getCFGDef('onlyUsers', 1) && !$this->checkUserGroups($uid, $this->getCFGDef('userGroups'))) {
            if ($mode == 'edit') {
                $this->redirect('badGroupTo');
                $this->renderTpl = $this->getCFGDef('badGroupTpl', $this->lexicon->getMsg('edit.default_badGroupTpl'));
            } else {
                $this->redirect('badRecordTo');
                $this->renderTpl = $this->getCFGDef('badGroupTpl',
                    $this->lexicon->getMsg('create.default_badGroupTpl'));
            }
        }

        if ($uid && !is_null($this->user)) {
            $this->owner = $uid; //владелец документа
            $userdata = $this->user->edit($uid)->toArray();
            if ($userdata['id']) {
                $this->setFields($userdata, 'user');
                $this->log('Set user data', array('data' => $userdata));
            }
        }

        if ($mode == 'edit') {
            $cid = is_null($this->content) ? false : $this->content->getID();
            if ($cid) {
                if ($this->getCFGDef('onlyAuthors',
                        1) && ($this->content->get($owner) && $this->content->get($owner) != $uid)
                ) {
                    $this->redirect('badOwnerTo');
                    $this->renderTpl = $this->getCFGDef('badOwnerTpl',
                        $this->lexicon->getMsg('edit.default_badOwnerTpl'));
                } else {
                    if (!$this->isSubmitted()) {
                        $fields = $this->getContentFields();
                        $this->setFields($fields);
                    }
                    return parent::render();
                }
            } else {
                $this->redirect('badRecordTo');
                $this->renderTpl = $this->getCFGDef('badRecordTpl',
                    $this->lexicon->getMsg('edit.default_badRecordTpl'));
            }
        }

        return parent::render();
    }

    /**
     *
     */
    public function process()
    {
        $fields = $this->getContentFields();
        $owner = $this->getCFGDef('ownerField', 'aid');
        $result = false;
        if ($fields && !is_null($this->content)) {
            $clearCache = $this->getCFGDef('clearCache', false);
            switch ($this->mode) {
                case 'create':
                    if ($this->checkSubmitProtection() || $this->checkSubmitLimit()) {
                        return;
                    }
                    if ($this->owner) {
                        $fields[$owner] = $this->owner;
                    }
                    $result = $this->content->create($fields)->save(true, $clearCache);
                    $this->log('Create record', array('data' => $fields, 'result' => $result));
                    break;
                case 'edit':
                    $result = $this->content->fromArray($fields)->save(true, $clearCache);
                    $this->log('Update record', array('data' => $fields, 'result' => $result));
                    break;
                default:
                    break;
            }
            //чтобы не получился косяк, когда плагины обновят поля
            $this->content->close();
            $this->setFields($this->content->edit($this->id)->toArray());
            $this->log('Update form data', array('data' => $this->getFormData('fields')));
        }
        if (!$result) {
            $this->addMessage($this->lexicon->getMsg('edit.update_fail'));
        } else {
            if ($this->mode == 'create') {
                $url = '';
                $evtOut = $this->modx->invokeEvent('OnMakeDocUrl', array(
                    'invokedBy' => __CLASS__,
                    'id'        => $result,
                    'data'      => $this->getFormData('fields')
                ));
                if (is_array($evtOut) && count($evtOut) > 0) {
                    $url = array_pop($evtOut);
                }
                if ($url) {
                    $this->setField('content.url', $url);
                }
            }
            parent::process();
        }
    }

    /**
     *
     */
    public function postProcess()
    {
        $this->setFormStatus(true);
        if ($this->mode == 'create') {
            if ($this->getCFGDef('editAfterCreate',0)) {
                $idField = $this->getCFGDef('idField');
                $this->redirect('redirectTo',
                    array(
                        $idField => $this->getField($idField)
                    )
                );
            } else {
                $this->redirect();
            }
            $this->renderTpl = $this->getCFGDef('successTpl', $this->lexicon->getMsg('create.default_successTpl'));
        } else {
            $this->addMessage($this->lexicon->getMsg('edit.update_success'));
        }
    }

    /**
     * @return array
     */
    public function getContentFields()
    {
        $fields = array();
        $contentFields = $this->config->loadArray($this->getCFGDef('contentFields'));
        if ($this->mode == 'edit' && !$this->isSubmitted()) {
            $contentFields = array_flip($contentFields);
        }
        foreach ($contentFields as $field => $formField) {
            $formField = $this->getField($formField);
            if ($formField !== '' || $this->getCFGDef('allowEmptyFields', 1)) {
                $fields[$field] = $formField;
            }
        }

        return $fields;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param $uid
     * @param string $groups
     * @return bool
     */
    public function checkUserGroups($uid, $groups = '')
    {
        $flag = true;
        if (is_scalar($groups) && !empty($groups) && !is_null($this->user)) {
            $groups = explode(';', $groups);
            if (!empty($groups)) {
                $userGroups = $this->user->getUserGroups($uid);
                $flag = !empty(array_intersect($groups, $userGroups));
            }
        }
        $this->log('Check user groups', array('result' => $flag));

        return $flag;
    }
}
