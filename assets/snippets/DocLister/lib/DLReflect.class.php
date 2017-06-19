<?php

if (!class_exists("DLReflect", false)) {
    /**
     * Class DLReflect
     */
    class DLReflect
    {
        /**
         * @param $val
         * @return bool
         */
        public static function validateMonth($val)
        {
            $flag = false;
            if (is_scalar($val)) {
                $val = explode("-", $val, 2);
                $flag = (count($val) && is_array($val) && strlen($val[0]) == 2 && strlen($val[1]) == 4); //Валидация содержимого массива
                $flag = ($flag && (int)$val[0] > 0 && (int)$val[0] <= 12); //Валидация месяца
                $flag = ($flag && self::validateYear($val[1])); //Валидация года
            }

            return $flag;
        }

        /**
         * @param $val
         * @return bool
         */
        public static function validateYear($val)
        {
            $flag = false;
            if (is_scalar($val)) {
                $flag = (strlen($val) == 4); //Валидация строки
                $flag = ($flag && (int)$val > 1900 && (int)$val <= 2100); //Валидация года
            }

            return $flag;
        }

        /**
         * @param string $type
         * @param $monthAction
         * @param $yearAction
         * @return mixed|null
         */
        public static function switchReflect($type, $monthAction, $yearAction)
        {
            $out = null;
            $action = $type . "Action";

            return is_callable($$action) ? call_user_func($$action) : $out;
        }
    }
}
