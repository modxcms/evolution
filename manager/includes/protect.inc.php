<?php
/**
 *    Protect against some common security flaws
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

// php bug 53632 (php 4 <= 4.4.9 and php 5 <= 5.3.4)
if (strstr(str_replace('.','',serialize(array_merge($_GET, $_POST, $_COOKIE))), '22250738585072011')) {
    header('Status: 422 Unprocessable Entity');
    die();
}

// Null is evil
if (isset($_SERVER['QUERY_STRING']) && strpos(urldecode($_SERVER['QUERY_STRING']), chr(0)) !== false)
    die();

// Unregister globals
if (@ ini_get('register_globals')) {
    die('Please disable register_globals!');
}

global $sanitize_seed;
$sanitize_seed = 'sanitize_seed_' . base_convert(md5(__FILE__),16,36);

// sanitize array
if (!function_exists('modx_sanitize_gpc')) {
    function modx_sanitize_gpc(& $values, $depth=0) {
        if(10 < $depth) exit('GPC Array nested too deep!');
        if(is_array($values)) {
            $depth++;
            foreach ($values as $key => $value) {
                if (is_array($value)) modx_sanitize_gpc($value, $depth);
                else                  $values[$key] = getSanitizedValue($value);
            }
        }
        else $values = getSanitizedValue($values);
        
        return $values;
    }
}

function getSanitizedValue($value='') {
    global $sanitize_seed;
    
    if(!$value) return $value;
    
    $brackets = explode(' ', '[[ ]] [! !] [* *] [( )] {{ }} [+ +] [~ ~] [^ ^]');
    foreach($brackets as $bracket) {
        if(strpos($value,$bracket)===false) continue;
        $sanitizedBracket = str_replace('#', $sanitize_seed, sprintf('#%s#%s#', substr($bracket,0,1), substr($bracket,1,1)));
        $value = str_replace($bracket,$sanitizedBracket,$value);
    }
    $value = str_ireplace('<script', 'sanitized_by_modx<s cript', $value);
    $value = preg_replace('/&#(\d+);/', 'sanitized_by_modx& #$1', $value);
    return $value;
}

modx_sanitize_gpc($_GET);
if (!defined('IN_MANAGER_MODE') || (defined('IN_MANAGER_MODE') && (!IN_MANAGER_MODE || IN_MANAGER_MODE === 'false'))) {
    modx_sanitize_gpc($_POST);
}
modx_sanitize_gpc($_COOKIE);
modx_sanitize_gpc($_REQUEST);

foreach (array ('PHP_SELF', 'HTTP_USER_AGENT', 'HTTP_REFERER', 'QUERY_STRING') as $key) {
    $_SERVER[$key] = isset ($_SERVER[$key]) ? htmlspecialchars($_SERVER[$key], ENT_QUOTES) : null;
}

// Unset vars
unset ($key, $value);
