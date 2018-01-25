<?php namespace FormLister;
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 06.05.2017
 * Time: 13:59
 */

/**
 * Interface Captcha
 * @package FormLister
 */
interface CaptchaInterface
{
    /**
     * Задает значение капчи
     * @return mixed
     */
    public function init();

    /**
     * Возвращает капчу для подстановки в плейсхолдер
     * @return string
     */
    public function getPlaceholder();

    /**
     * Проверяет капчу
     * @param \FormLister\Core $FormLister
     * @param string $value
     * @param \FormLister\CaptchaInterface $captcha
     * @return bool|string
     */
    public static function validate (Core $FormLister, $value, CaptchaInterface $captcha);
}
