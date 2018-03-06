<?php namespace Helpers;

include_once(MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');
include_once(MODX_BASE_PATH . 'assets/lib/Helpers/FS.php');
include_once(MODX_MANAGER_PATH . 'includes/extenders/modxmailer.class.inc.php');

use MODxMailer;
use DocumentParser;
use PHPMailer\PHPMailer\Exception as phpmailerException;

/**
 * Class Mailer
 * @package Helpers
 */
class Mailer
{
    /**
     * @var MODxMailer $mail
     */
    protected $mail = null;
    protected $modx = null;
    public $config = array();
    protected $debug = false;
    protected $queuePath = 'assets/cache/mail/';
    protected $noemail = false;

    /**
     * @var string
     */
    protected $Body = '';
    /**
     * @var string
     */
    protected $Subject = '';

    /**
     * Mailer constructor.
     * @param DocumentParser $modx
     * @param $cfg
     * @param bool $debug
     */
    public function __construct(DocumentParser $modx, $cfg, $debug = false)
    {
        $this->modx = $modx;
        $this->noemail = (bool)(isset($cfg['noemail']) ? $cfg['noemail'] : 0);
        if (!$this->noemail) {
            $this->mail = new MODxMailer();
            if (method_exists('MODxMailer', 'init')) {
                $this->mail->init($modx);
            }
            $this->config = $cfg;
            $this->debug = $debug;
            $this->applyMailConfig();
        }
    }

    /**
     * @param string $type
     * @param string $addr
     * @return $this
     */
    public function addAddressToMailer($type, $addr)
    {
        if (!$this->noemail && !empty($addr)) {
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
        if (!$this->noemail) {
            $fs = FS::getInstance();
            $contentType = "application/octetstream";
            foreach ($filelist as $file) {
                if (!$fs->checkFile($file['filepath'])) continue;
                $this->mail->AddAttachment($file['filepath'], $file['filename'], "base64", $contentType);
            }
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
        if (!$this->getCFGDef('to') || $this->noemail) {
            return true;
        } elseif (empty($report)) {
            return false;
        }

        $this->mail->Body = $this->getCFGDef('isHtml',1) ? $this->mail->msgHTML($report, MODX_BASE_PATH) : $report;

        $result = $this->mail->send();
        if ($result) {
            $this->mail->ClearAllRecipients();
            $this->mail->ClearAttachments();
        }

        return $result;
    }

    /**
     * @param $report
     * @return bool
     */
    public function toQueue($report)
    {
        //если отправлять некуда или незачем, то делаем вид, что отправили
        if (!$this->getCFGDef('to') || $this->noemail) {
            return true;
        } elseif (empty($report)) {
            return false;
        }

        $this->mail->Body = $this->getCFGDef('isHtml',1) ? $this->mail->msgHTML($report, MODX_BASE_PATH) : $report;

        $this->Body = $this->modx->removeSanitizeSeed($this->mail->Body);
        $this->Subject = $this->modx->removeSanitizeSeed($this->mail->Subject);
        try {
            $result = $this->mail->preSend() && $this->saveMessage();
        } catch (phpmailerException $e) {
            $this->mail->SetError($e->getMessage());

            $result = false;
        }

        if ($result) {
            $this->mail->ClearAllRecipients();
            $this->mail->ClearAttachments();
            $result = $this->getFileName();
        }

        return $result;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function setQueuePath($path = '') {
        if (!empty($path)) {
            $this->queuePath = $path;
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    protected function saveMessage()
    {
        $data = serialize(array(
            "header" => $this->mail->getMIMEHeader(),
            "body"   => $this->mail->getMIMEBody(),
            "config" => $this->config
        ));
        $file = $this->getFileName();
        FS::getInstance()->makeDir($this->queuePath);
        $result = @file_put_contents(MODX_BASE_PATH . $this->queuePath . $file, $data) !== false;
        if ($result) {
            $result = $file;
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getFileName() {
        return $this->mail->getMessageID() . '.eml';
    }

    /**
     * @param $file
     * @return bool
     */
    public function fromQueue($file)
    {
        $result = false;
        $dir = MODX_BASE_PATH . $this->queuePath;
        $fs = FS::getInstance();
        if ($fs->checkFile($dir)) {
            $message = unserialize(file_get_contents($dir . $file));
            $this->config = $message['config'];
            $this->applyMailConfig();
            $this->mail->setMIMEHeader($message['header'])->setMIMEBody($message['body']);
            unset($message);
            $result = $this->mail->postSend();
            if ($result) {
                $this->mail->setMIMEBody()->setMIMEHeader();
                FS::getInstance()->unlink($dir . $file);
            }
        }

        return $result;
    }

    /**
     * @return $this
     */
    protected function applyMailConfig()
    {
        $this->mail->IsHTML($this->getCFGDef('isHtml', 1));
        $this->mail->From = $this->getCFGDef('from', $this->modx->config['emailsender']);
        $this->mail->FromName = $this->getCFGDef('fromName', $this->modx->config['site_name']);
        $this->mail->Subject = $this->getCFGDef('subject');
        $this->addAddressToMailer("replyTo", $this->getCFGDef('replyTo'));
        $this->addAddressToMailer("to", $this->getCFGDef('to'));
        $this->addAddressToMailer("cc", $this->getCFGDef('cc'));
        $this->addAddressToMailer("bcc", $this->getCFGDef('bcc'));

        return $this;
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
