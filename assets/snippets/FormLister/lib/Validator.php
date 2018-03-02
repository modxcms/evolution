<?php namespace FormLister;

/**
 * Class Validator
 * @package FormLister
 *  */
class Validator
{
    /**
     * @param $value
     * @return bool
     */
    public function required($value) {
        return !in_array($value, array(null, ''), true);
    }

    /**
     * @param $value
     * @param $format
     * @return bool
     */
    public function date($value, $format) {
        if (!is_scalar($value)) {
            return false;
        }
        $d = \DateTime::createFromFormat($format, $value);
        return $d && $d->format($format) == $value;
    }

    /**
     * @param $value
     * @param $min
     * @return bool
     */
    public function min($value, $min)
    {
        return is_scalar($value) && $value >= $min;
    }

    /**
     * @param $value
     * @param $max
     * @return bool
     */
    public function max($value, $max)
    {
        return is_scalar($value) && $value <= $max;
    }

    /**
     * @param $value
     * @param $min
     * @return bool
     */
    public function greater($value, $min)
    {
        return is_scalar($value) && $value > $min;
    }

    /**
     * @param $value
     * @param $max
     * @return bool
     */
    public function less($value, $max)
    {
        return is_scalar($value) && $value < $max;
    }

    /**
     * @param $value
     * @param $min
     * @param $max
     * @return bool
     */
    public function between($value, $min, $max)
    {
        return (is_scalar($value) && $value >= $min && $value <= $max);
    }

    /**
     * @param $value
     * @param $allowed
     * @return bool
     */
    public function equals($value, $allowed)
    {
        return is_scalar($value) && $value === $allowed;
    }

    /**
     * @param $value
     * @param array $allowed
     * @return bool
     */
    public function in($value, $allowed)
    {
        return is_scalar($value) && in_array($value, $allowed, true);
    }

    /**
     * @param $value
     * @return bool
     */
    public function alpha($value)
    {
        return (bool) is_scalar($value) && preg_match('/^\pL++$/uD', $value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function numeric($value)
    {
        return (bool) is_scalar($value) && preg_match('#^[0-9]*$#',$value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function alphaNumeric($value)
    {
        return (bool) is_scalar($value) && preg_match('/^[\pL\pN]++$/uD', $value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function slug($value)
    {
        return (bool) is_scalar($value) && preg_match('/^[\pL\pN\-\_]++$/uD', $value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function decimal($value)
    {
        return (bool) is_scalar($value) && preg_match('/^[0-9]+(?:\.[0-9]+)?$/D', $value);
    }


    /**
     * @param $value
     * @return bool
     */
    public function phone($value)
    {
        return (bool) is_scalar($value) && preg_match('#^[0-9\(\)\+ \-]*$#',$value);
    }

    /**
     * @param $value
     * @param $regexp
     * @return bool
     */
    public function matches($value,$regexp)
    {
        return (bool) is_scalar($value) && preg_match($regexp,$value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function url($value)
    {
        return (bool) is_scalar($value) && preg_match(
            '~^
                [-a-z0-9+.]++://
                (?!-)[-a-z0-9]{1,63}+(?<!-)
                (?:\.(?!-)[-a-z0-9]{1,63}+(?<!-)){0,126}+
                (?::\d{1,5}+)?
                (?:/.*)?
            $~iDx',
            $value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function email($value)
    {
        return (bool) is_scalar($value) && preg_match(
            '/^
                [-_a-z0-9\'+*$^&%=~!?{}]++
                (?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+
                @(?:(?![-.])[-a-z0-9.]+(?<![-.])\.
                [a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})
                (?::\d++)?
            $/iDx',
            $value
        );
    }

    /**
     * @param $value
     * @param $length
     * @return bool
     */
    public function length($value, $length)
    {
        return $this->getLength($value) === $length;
    }

    /**
     * @param $value
     * @param $minLength
     * @return bool
     */
    public function minLength($value, $minLength)
    {
        return $this->getLength($value) >= $minLength;
    }

    /**
     * @param $value
     * @param $maxLength
     * @return bool
     */
    public function maxLength($value, $maxLength)
    {
        return $this->getLength($value) <= $maxLength;
    }

    /**
     * @param $value
     * @param $minLength
     * @param $maxLength
     * @return bool
     */
    public function lengthBetween($value, $minLength, $maxLength)
    {
        if (!is_scalar($value)) {
            return false;
        }
        $length = $this->getLength($value);

        return ($length >= $minLength && $length <= $maxLength);
    }

    /**
     * @param $value
     * @param $minSize
     * @return bool
     */
    public function minCount($value, $minSize) {
        return is_array($value) && count($value) >= $minSize;
    }

    /**
     * @param $value
     * @param $maxSize
     * @return bool
     */
    public function maxCount($value, $maxSize) {
        return is_array($value) && count($value) <= $maxSize;
    }

    /**
     * @param $value
     * @param $minSize
     * @param $maxSize
     * @return bool
     */
    public function countBetween($value, $minSize, $maxSize) {
        return (is_array($value) && count($value) >= $minSize && count($value) <= $maxSize);
    }

    /**
     * @param $string
     * @return int
     */
    protected function getLength($string)
    {
        return strlen(utf8_decode($string));
    }
}
