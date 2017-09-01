<?php namespace FormLister;

/**
 * Class DeleteUser
 * @package FormLister
 */
class DeleteUser extends Form
{
    public $user = null;

    /**
     * Form constructor.
     * @param \DocumentParser $modx
     * @param array $cfg
     */
    public function __construct(\DocumentParser $modx, array $cfg = array())
    {
        parent::__construct($modx, $cfg);
        $lang = $this->lexicon->loadLang('deleteUser');
        if ($lang) {
            $this->log('Lexicon loaded', array('lexicon' => $lang));
        }
        $uid = $modx->getLoginUserId('web');
        $userdata = array();
        if ($uid) {
            $this->user = $this->loadModel(
                $this->getCFGDef('model', '\modUsers'),
                $this->getCFGDef('modelPath', 'assets/lib/MODxAPI/modUsers.php')
            );
            if ($ds = $this->getCFGDef('defaultsSources')) {
                $defaultsSources = "{$ds};param:userdata";
            } else {
                $defaultsSources = "param:userdata";
            }
            if (!is_null($this->user)) {
                $userdata = $this->user->edit($uid)->toArray();
                unset($userdata['password']);
            }
            $this->config->setConfig(array(
                'defaultsSources'    => $defaultsSources,
                'userdata'           => $userdata,
                'ignoreMailerResult' => 1,
                'keepDefaults'       => 1,
                'protectSubmit'      => 0,
                'submitLimit'        => 0
            ));
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        if (!$this->modx->getLoginUserID('web')) {
            $this->redirect('exitTo');
            $this->renderTpl = $this->getCFGDef('skipTpl', $this->lexicon->getMsg('deleteUser.default_skipTpl'));
            $this->setValid(false);
        };

        return parent::render();
    }


    /**
     *
     */
    public function process()
    {
        $uid = $this->modx->getLoginUserID('web');
        if (!is_null($this->user)) {
            $password = $this->getField('password');
            if ($this->user->testAuth($uid, $password, true)) {
                $result = $this->user->delete($uid, true);
                if ($result) {
                    $this->user->logout();
                    parent::process();
                } else {
                    return $this->addMessage($this->lexicon->getMsg('deleteUser.delete_failed'));
                }
            } else {

            }
        }

        return $this->addMessage($this->lexicon->getMsg('deleteUser.delete_failed'));
    }

    /**
     *
     */
    public function postProcess()
    {
        parent::postProcess();
        $this->renderTpl = $this->getCFGDef('successTpl', $this->lexicon->getMsg('deleteUser.default_successTpl'));
    }
}
