<?php

use FormLister\CaptchaInterface;
use FormLister\Core;
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 19.11.2016
 * Time: 0:27
 */
class SmsCaptchaWrapper implements CaptchaInterface
{
    /**
     * @var null
     * id, errorEmptyCode, errorCodeRequired, errorCodeFailed, errorCodeExpired, errorCodeUsed
     */
    public $cfg = null;
    protected $modx = null;

    /**
     * modxCaptchaWrapper constructor.
     * @param $modx
     * @param $cfg
     */
    public function __construct(\DocumentParser $modx, $cfg = array())
    {
        $this->cfg = $cfg;
        $this->modx = $modx;
    }

    /**
     * Устанавливает значение капчи
     * @return mixed
     */
    public function init()
    {
        return;
    }

    /**
     * Плейсхолдер капчи для вывода в шаблон
     * @return string
     */
    public function getPlaceholder()
    {
        return '';
    }

    /**
     * @param \FormLister\Core $FormLister
     * @param $value
     * @param \FormLister\CaptchaInterface $captcha
     * @return bool|string
     */
    public static function validate(Core $FormLister, $value, CaptchaInterface $captcha)
    {
        $id = \APIhelpers::getkey($captcha->cfg, 'id');
        if (empty($value)) {
            return \APIhelpers::getkey($captcha->cfg, 'errorEmptyCode',
                'Введите код авторизации');
        }

        if (empty($_SESSION[$id . '.smscaptcha'])) {
            return \APIhelpers::getkey($captcha->cfg, 'errorCodeRequired',
                'Получите код авторизации');
        }

        $sms = $FormLister->loadModel('SmsModel');

        if (is_null($sms->getData($_SESSION[$id . '.smscaptcha'], $id)->getID())) {

            return \APIhelpers::getkey($captcha->cfg, 'errorCodeRequired', 'Получите код авторизации');
        }

        if ($sms->get('code') != $value) {

            return \APIhelpers::getkey($captcha->cfg, 'errorCodeFailed', 'Неверный код авторизации');
        }

        if ($sms->get('expires') < time()) {
            $sms->delete($sms->getID());

            return \APIhelpers::getkey($captcha->cfg, 'errorCodeExpired',
                'Код авторизации истек, получите новый');
        } else {
            if (!$sms->get('active')) {
                $sms->set('active', 1)->set('expires', time() + \APIhelpers::getkey($captcha->cfg, 'codeLifeTime',
                        86400))->set('ip', \APIhelpers::getUserIP())->save();
            } else {

                return \APIhelpers::getkey($captcha->cfg, 'errorCodeUsed',
                    'Код авторизации уже использовался');
            }
            $out = true;

            $FormLister->setField('captcha.phone', $sms->get('phone'));
        }
        $FormLister->log('Validate captcha value '.$value.' against '.$sms->get('code'));

        return $out;
    }
}

