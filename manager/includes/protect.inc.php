<?php
/**
 *    Protect against some common security flaws
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

// Null is evil
if (isset($_SERVER['QUERY_STRING']) && strpos(urldecode($_SERVER['QUERY_STRING']), chr(0)) !== false) {
    die();
}

global $sanitize_seed;
$sanitize_seed = 'sanitize_seed_' . base_convert(md5(__FILE__), 16, 36);

// sanitize array
if (!function_exists('modx_sanitize_gpc')) {
    /**
     * @param array|string $values
     * @param int $depth
     * @return array|string
     */
    function modx_sanitize_gpc(& $values, $depth = 0)
    {
        if (200 < $depth) {
            exit('GPC Array nested too deep!');
        }
        if (is_array($values)) {
            $depth++;
            foreach ($values as $key => $value) {
                if (is_array($value)) {
                    modx_sanitize_gpc($value, $depth);
                } else {
                    $values[$key] = getSanitizedValue($value);
                }
            }
        } else {
            $values = getSanitizedValue($values);
        }

        return $values;
    }
}

/**
 * @param string $value
 * @return string
 */
function getSanitizedValue($value = '')
{
    global $sanitize_seed;

    if (empty($value)) {
        return $value;
    }

    $brackets = explode(' ', '[[ ]] [! !] [* *] [( )] {{ }} [+ +] [~ ~] [^ ^]');
    foreach ($brackets as $bracket) {
        if (strpos($value, $bracket) === false) {
            continue;
        }
        $sanitizedBracket = str_replace('#', $sanitize_seed,
            sprintf('#%s#%s#', substr($bracket, 0, 1), substr($bracket, 1, 1)));
        $value = str_replace($bracket, $sanitizedBracket, $value);
    }
    $value = str_ireplace('<script', 'sanitized_by_modx<s cript', $value);
    $value = preg_replace('/&#(\d+);/', 'sanitized_by_modx& #$1', $value);

    return $value;
}

if (! function_exists('html_escape')) {
    function html_escape($str, $charset = 'UTF-8')
    {
        return htmlentities($str, ENT_COMPAT | ENT_SUBSTITUTE, $charset, false);
    }
}

modx_sanitize_gpc($_GET);
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    modx_sanitize_gpc($_POST);
}
modx_sanitize_gpc($_COOKIE);
modx_sanitize_gpc($_REQUEST);

foreach (array('PHP_SELF', 'HTTP_USER_AGENT', 'HTTP_REFERER', 'QUERY_STRING') as $key) {
    $_SERVER[$key] = isset ($_SERVER[$key]) ? htmlspecialchars($_SERVER[$key], ENT_QUOTES) : null;
}

// Unset vars
unset ($key, $value);
