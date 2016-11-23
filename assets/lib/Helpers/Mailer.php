<?php namespace Helpers;

include_once(MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');

/**
 * Class Mailer
 * @package Helpers
 */
class Mailer
{
    /**
     * @var \PHPMailer $mail
     */
    protected $mail = null;
    protected $modx = null;
    public $config = array();
    protected $debug = false;


    /**
     * Mailer constructor.
     * @param \DocumentParser $modx
     * @param $cfg
     * @param bool $debug
     */
    public function __construct(\DocumentParser $modx, $cfg, $debug = false)
    {
        $this->modx = $modx;
        $modx->loadExtension('MODxMailer');
        $this->mail = $modx->mail;
        $this->config = $cfg;
        $this->debug = $debug;
    }

    /**
     * @param string $type
     * @param string $addr
     * @return $this
     */
    public function addAddressToMailer($type, $addr)
    {
        if (!empty($addr)) {
            $a = array_filter(array_map('trim', explode(',', $addr)));
            foreach ($a as $address) {
                switch ($type) {
                    case 'to':
                        $this->mail->AddAddress($address);
                        break;
                    case 'cc':
                        $this->mail->AddCC($address);
                        break;
                    case 'bcc':
                        $this->mail->AddBCC($address);
                        break;
                    case 'replyTo':
                        $this->mail->AddReplyTo($address);
                }
            }
        }

        return $this;
    }

    /**
     * @param array $filelist
     * @return $this
     */
    public function attachFiles($filelist = array())
    {
        $contentType = "application/octetstream";
        foreach ($filelist as $file) {
            $this->mail->AddAttachment($file['filepath'], $file['filename'], "base64", $contentType);
        }

        return $this;
    }

    /**
     * @param $report
     * @return bool
     */
    public function send($report)
    {
        //если отправлять некуда или незачем, то делаем вид, что отправили
        if (!$this->getCFGDef('to') || $this->getCFGDef('noemail')) {
            return true;
        } elseif (empty($report)) {
            return false;
        }

        $this->mail->IsHTML($this->getCFGDef('isHtml', 1));
        $this->mail->From = $this->getCFGDef('from', $this->modx->config['site_name']);
        $this->mail->FromName = $this->getCFGDef('fromName', $this->modx->config['emailsender']);
        $this->mail->Subject = $this->getCFGDef('subject');
        $this->mail->Body = $report;
        $this->addAddressToMailer("replyTo", $this->getCFGDef('replyTo'));
        $this->addAddressToMailer("to", $this->getCFGDef('to'));
        $this->addAddressToMailer("cc", $this->getCFGDef('cc'));
        $this->addAddressToMailer("bcc", $this->getCFGDef('bcc'));

        $result = $this->mail->send();
        if ($result) {
            $this->mail->ClearAllRecipients();
            $this->mail->ClearAttachments();
        }

        return $result;
    }

    /**
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function getCFGDef($param, $default = null)
    {
        return \APIhelpers::getkey($this->config, $param, $default);
    }
}
