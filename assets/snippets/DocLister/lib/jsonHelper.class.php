<?php
include_once("xnop.class.php");

/**
 * Class jsonHelper
 */
class jsonHelper
{
    /**
     * @var array
     */
    protected static $_error = array(
        0 => 'error_none', //JSON_ERROR_NONE
        1 => 'error_depth', //JSON_ERROR_DEPTH
        2 => 'error_state_mismatch', //JSON_ERROR_STATE_MISMATCH
        3 => 'error_ctrl_char', //JSON_ERROR_CTRL_CHAR
        4 => 'error_syntax', //JSON_ERROR_SYNTAX
        5 => 'error_utf8' //SON_ERROR_UTF8
    );

    /**
     * Разбор JSON строки при помощи json_decode
     *
     * @param $json string строка c JSON
     * @param array $config ассоциативный массив с настройками для json_decode
     * @param bool $nop создавать ли пустой объект запрашиваемого типа
     * @return array|mixed|xNop
     */
    public static function jsonDecode($json, $config = array(), $nop = false)
    {
        if (isset($config['assoc'])) {
            $assoc = (boolean)$config['assoc'];
        } else {
            $assoc = false;
        }

        if (isset($config['depth']) && (int)$config['depth'] > 0) {
            $depth = (int)$config['depth'];
        } else {
            $depth = 512;
        }
        if (is_scalar($json)) {
            if (version_compare(phpversion(), '5.3.0', '<')) {
                $out = json_decode($json, $assoc);
            } else {
                $out = json_decode($json, $assoc, $depth);
            }
        } else {
            $out = null;
        }

        if ($nop && is_null($out)) {
            if ($assoc) {
                $out = array();
            } else {
                $out = new xNop();
            }
        }

        return $out;
    }

    /**
     * Получение кода последенй ошибки
     * @see http://www.php.net/manual/ru/function.json-last-error-msg.php
     * @return string
     */
    public static function json_last_error_msg()
    {
        if (function_exists('json_last_error')) {
            $error = json_last_error();
        } else {
            $error = 999;
        }

        return isset(self::$_error[$error]) ? self::$_error[$error] : 'other';
    }

    /**
     * @param array $data
     * @return bool|string
     */
    public static function toJSON(array $data = array())
    {
        if (version_compare(PHP_VERSION, '5.4.0') < 0) {
            $out = json_encode($data);
            $out = str_replace('\\/', '/', $out);
        } else {
            $out = json_encode($data, JSON_UNESCAPED_SLASHES);
        }

        return self::json_format($out);
    }

    /**
     * @param $json
     * @return bool|string
     */
    public static function json_format($json)
    {
        $tab = "  ";
        $new_json = "";
        $indent_level = 0;
        $in_string = false;
        $json_obj = json_decode($json);
        if ($json_obj === false) {
            return false;
        }
        $len = strlen($json);
        for ($c = 0; $c < $len; $c++) {
            $char = $json[$c];
            switch ($char) {
                case '{':
                case '[':
                    if (!$in_string) {
                        $new_json .= $char . "\n" . str_repeat($tab, $indent_level + 1);
                        $indent_level++;
                    } else {
                        $new_json .= $char;
                    }
                    break;
                case '}':
                case ']':
                    if (!$in_string) {
                        $indent_level--;
                        $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
                    } else {
                        $new_json .= $char;
                    }
                    break;
                case ',':
                    if (!$in_string) {
                        $new_json .= ",\n" . str_repeat($tab, $indent_level);
                    } else {
                        $new_json .= $char;
                    }
                    break;
                case ':':
                    if (!$in_string) {
                        $new_json .= ": ";
                    } else {
                        $new_json .= $char;
                    }
                    break;
                case '"':
                    if ($c > 0 && $json[$c - 1] != '\\') {
                        $in_string = !$in_string;
                    }
                    // no break ???
                default:
                    $new_json .= $char;
                    break;
            }
        }

        return $new_json;
    }

}
