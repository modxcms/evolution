<?php namespace FormLister;

/**
 * Class MailChimp
 * @package FormLister
 */
class MailChimp extends Core
{
    /**
     * MailChimp constructor.
     * @param \DocumentParser $modx
     * @param array $cfg
     */
    public function __construct(\DocumentParser $modx, $cfg = array())
    {
        parent::__construct($modx, $cfg);
        $this->lexicon->loadLang('mailchimp');
    }

    /**
     * @return bool
     */
    public function process()
    {
        $errorMessage = $this->lexicon->getMsg('mc.subscription_failed');
        if (!$this->getCFGDef('apiKey')) {
            $this->addMessage($errorMessage);

            return false;
        }
        $MailChimp = new \DrewM\MailChimp\MailChimp($this->getCFGDef('apiKey'));
        $list_id = $this->getCFGDef('listId');
        if (!$list_id) {
            $this->addMessage($errorMessage);

            return false;
        }

        $MailChimp->post("lists/$list_id/members", array(
            'email_address' => $this->getField('email'),
            'merge_fields'  => array('NAME' => $this->getField('name')),
            'status'        => 'pending',
        ));
        if (!$MailChimp->getLastError()) {
            $this->addMessage($errorMessage);
        } else {
            $this->setFormStatus(true);
            $this->renderTpl = $this->getCFGDef('successTpl', $this->lexicon->getMsg('mc.default_successTpl'));

            return true;
        }
    }
}
