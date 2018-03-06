<?php namespace FormLister;

/**
 * Контроллер для создания записей
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
            $data = $this->content->edit($this->id)->toArray('', '', '_', false);
            $this->mailConfig['noemail'] = 1;
            if ($ds = $this->getCFGDef('defaultsSources')) {
                $defaultsSources = "{$ds};param:contentdata";
            } else {
                $defaultsSources = "param:contentdata";
            }
            $this->config->setConfig(array(
                'defaultsSources' => $defaultsSources,
                'contentdata'     => $data,
                'formTpl'         => $this->getCFGDef('editTpl', $this->getCFGDef('formTpl')),
                'successTpl'      => $this->getCFGDef('editSuccessTpl'),
                'onlyUsers'       => 1
            ));
        }
        $this->log('Content mode is ' . $this->mode, array('data' => $data));
    }

    /**
     * @return string
     */
    public function render()
    {
        $uid = (int)$this->modx->getLoginUserID('web');
        $ownerField = $this->getCFGDef('ownerField', 'aid');
        $mode = $this->mode;
        $flag = true;

        if ($mode == 'create') {
            if ($this->getCFGDef('onlyUsers', 1)) {
                if (!$uid) {
                    $this->redirect('exitTo');
                    $this->renderTpl = $this->getCFGDef('skipTpl',
                        $this->lexicon->getMsg('create.default_skipTpl'));
                    $flag = false;
                } elseif (!$this->checkUserGroups($uid, $this->getCFGDef('userGroups'))) {
                    $this->redirect('badGroupTo');
                    $this->renderTpl = $this->getCFGDef('badGroupTpl',
                        $this->lexicon->getMsg('create.default_badGroupTpl'));
                    $flag = false;
                }
            }
            $this->owner = $uid;
        }

        if ($mode == 'edit') {
            if (!$uid) {
                $this->redirect('exitTo');
                $this->renderTpl = $this->getCFGDef('skipEditTpl',
                    $this->lexicon->getMsg('edit.default_skipEditTpl'));
                $flag = false;
            } elseif (!$this->checkUserGroups($uid, $this->getCFGDef('userGroups'))) {
                $this->redirect('badGroupTo');
                $this->renderTpl = $this->getCFGDef('badGroupTpl',
                    $this->lexicon->getMsg('edit.default_badGroupTpl'));
                $flag = false;
            } else {
                $cid = is_null($this->content) ? false : $this->content->getID();
                if ($cid) {
                    $owner = $this->content->get($ownerField);
                    if ($this->getCFGDef('onlyAuthors', 1) && $owner && $owner != $uid) {
                        $this->redirect('badOwnerTo');
                        $this->renderTpl = $this->getCFGDef('badOwnerTpl',
                            $this->lexicon->getMsg('edit.default_badOwnerTpl'));
                        $flag = false;
                    }
                    $this->owner = $uid;
                } else {
                    $this->redirect('badRecordTo');
                    $this->renderTpl = $this->getCFGDef('badRecordTpl',
                        $this->lexicon->getMsg('edit.default_badRecordTpl'));
                    $flag = false;
                }
            }

            if ($flag && !$this->isSubmitted()) {
                $fields = $this->getContentFields();
                $this->setFields($fields);
            }
        }

        $this->setValid($flag);

        return parent::render();
    }

    /**
     *
     */
    public function process()
    {
        $fields = $this->getContentFields();
        $ownerField = $this->getCFGDef('ownerField', 'aid');
        $result = false;
        if ($fields && !is_null($this->content)) {
            $clearCache = $this->getCFGDef('clearCache', false);
            switch ($this->mode) {
                case 'create':
                    if ($this->checkSubmitProtection() || $this->checkSubmitLimit()) {
                        return;
                    }
                    if ($this->owner || !$this->getCFGDef('onlyUsers', 1)) {
                        $fields[$ownerField] = $this->owner;
                        $result = $this->content->create($fields)->save(true, $clearCache);
                    }
                    if ($result) {
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
                        $this->log('Created record', array('data' => $fields, 'result' => $result));
                    }
                    break;
                case 'edit':
                    $result = $this->content->fromArray($fields)->save(true, $clearCache);
                    if ($result) {
                        $this->log('Update record', array('data' => $fields, 'result' => $result));
                    }
                    break;
            }
        }
        if (!$result) {
            $this->log('Save failed', array('model' => get_class($this->content), 'data' => $fields));
            $this->addMessage($this->lexicon->getMsg('edit.update_failed'));
        } else {
            $this->content->close();
            $this->setFields($this->content->edit($result)->toArray('', '', '_', false));
            if ($this->getCFGDef('contentFields')) {
                $this->setFields($this->getContentFields(true));
            }
            if ($this->owner) {
                $this->setFields($this->user->edit($this->owner)->toArray(), 'user');
            }
            $this->runPrepare('preparePostProcess');
            $this->log('Update form data', array('data' => $this->getFormData('fields')));
            if ($this->mode == 'create') {
                parent::process();
            } else {
                $this->postProcess();
            }
        }
    }

    /**
     *
     */
    public function postProcess()
    {
        $this->setFormStatus(true);
        $this->runPrepare('prepareAfterProcess');
        if ($this->mode == 'create') {
            if ($this->getCFGDef('editAfterCreate', 0)) {
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
            if ($successTpl = $this->getCFGDef('successTpl')) {
                $this->renderTpl = $successTpl;
            } else {
                $this->addMessage($this->lexicon->getMsg('edit.update_success'));
            }
        }
    }

    /**
     * @param bool $flip
     * @return array
     */
    public function getContentFields($flip = false)
    {
        $fields = array();
        $contentFields = $this->getCFGDef('contentFields');
        $contentFields = $this->config->loadArray($contentFields, '');
        if (!$contentFields) {
            $fields = $this->filterFields($this->getFormData('fields'), $this->allowedFields, $this->forbiddenFields);
            $this->log('Unable to get juxtaposition of content fields', array('contentFields' => $fields));
        } else {
            if ($flip || ($this->mode == 'edit' && !$this->isSubmitted())) {
                $contentFields = array_flip($contentFields);
            }
            foreach ($contentFields as $field => $formField) {
                $formField = $this->getField($formField);
                if ($formField !== '' || $this->getCFGDef('allowEmptyFields', 1)) {
                    $fields[$field] = $formField;
                }
            }
            $this->log('Juxtaposition of content fields', array('contentFields' => $fields));
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
