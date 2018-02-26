<?php
/**
 * Обертка для работы с классом капчи
 */

use FormLister\CaptchaInterface;
use FormLister\Core;
/**
 * Class modxCaptchaWrapper
 */
class ModxCaptchaWrapper implements CaptchaInterface
{
    /**
     * @var array $cfg
     * id
     * width
     * height
     * inline
     * connectorDir
     */
    public $cfg = null;
    protected $captcha = null;
    protected $lastValue = '';

    /**
     * modxCaptchaWrapper constructor.
     * @param $modx
     * @param $cfg
     */
    public function __construct(\DocumentParser $modx, $cfg = array())
    {
        $this->cfg = $cfg;
        $this->captcha = new \ModxCaptcha($modx, \APIhelpers::getkey($this->cfg, 'width', 100),
            \APIhelpers::getkey($this->cfg, 'height', 60));
    }

    /**
     * Устанавливает значение капчи
     * @return mixed
     */
    public function init()
    {
        $formid = \APIhelpers::getkey($this->cfg, 'id');
        if ($formid) {
            $this->lastValue = isset($_SESSION[$formid . '.captcha'])
                ? $_SESSION[$formid . '.captcha']
                : $this->captcha->word;
            $_SESSION[$formid . '.captcha'] = $this->captcha->word;
        }
    }

    /**
     * Плейсхолдер капчи для вывода в шаблон
     * Может быть ссылкой на коннектор (чтобы можно было обновлять c помощью js), может быть сразу картинкой в base64
     * @return string
     */
    public function getPlaceholder()
    {
        $inline = \APIhelpers::getkey($this->cfg, 'inline', 1);
        if ($inline) {
            $out = $this->captcha->outputImage(true);
        } else {
            $connectorDir = \APIhelpers::getkey($this->cfg, 'connectorDir',
                'assets/snippets/FormLister/lib/captcha/modxCaptcha/');
            $out = MODX_BASE_URL . $connectorDir . 'connector.php?formid=' . \APIhelpers::getkey($this->cfg, 'id',
                    'modx');
            $out .= '&w=' . \APIhelpers::getkey($this->cfg, 'width', 100);
            $out .= '&h=' . \APIhelpers::getkey($this->cfg, 'height', 60);
        }

        return $out;
    }

    /**
     * @param \FormLister\Core $FormLister
     * @param $value
     * @param \FormLister\CaptchaInterface $captcha
     * @return bool|string
     */
    public static function validate(Core $FormLister, $value, CaptchaInterface $captcha)
    {
        if (empty($value)) {
            $out = \APIhelpers::getkey($captcha->cfg, 'errorEmptyCode', 'Введите проверочный код');
        } else {
            $out = strtolower($value) == strtolower($captcha->lastValue) ? true : \APIhelpers::getkey($captcha->cfg,
                'errorCodeFailed', 'Неверный проверочный код');
        }
        $FormLister->log('Validate captcha value '.$value.' against '.$captcha->lastValue);

        return $out;
    }
}
