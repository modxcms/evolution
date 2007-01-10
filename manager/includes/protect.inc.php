<?php
/**
 *    Protect against some common security flaws
 */

// Null is evil
if (strpos(urldecode($_SERVER['QUERY_STRING']), chr(0)) !== false)
    die();

// Unregister globals
if (@ ini_get('register_globals')) {
    foreach ($_REQUEST as $key => $value) {
        $$key = null; // This is NOT paranoid because
        unset ($$key); // unset may not work.
    }
}
$modxtags = array (
    '@<script[^>]*?>.*?</script>@si',
    '@&#(\d+);@e',
    '@\[\[(.*?)\]\]@si',
    '@\[!(.*?)!\]@si',
    '@\[\~(.*?)\~\]@si',
    '@\[\((.*?)\)\]@si',
    '@{{(.*?)}}@si',
    '@\[\+(.*?)\+\]@si',
    '@\[\*(.*?)\*\]@si'
);
if (!function_exists('modx_sanitize_gpc')) {
    function modx_sanitize_gpc(& $target, $modxtags, $limit= 3) {
        foreach ($target as $key => $value) {
            if (is_array($value) && $limit > 0) {
                modx_sanitize_gpc($value, $modxtags, $limit - 1);
            } else {
                $target[$key] = preg_replace($modxtags, "", $value);
            }
        }
        return $target;
    }
}
modx_sanitize_gpc($_GET, $modxtags);
if (!defined('IN_MANAGER_MODE') || (defined('IN_MANAGER_MODE') && (!IN_MANAGER_MODE || IN_MANAGER_MODE == 'false'))) {
    modx_sanitize_gpc($_POST, $modxtags);
}
modx_sanitize_gpc($_COOKIE, $modxtags);
modx_sanitize_gpc($_REQUEST, $modxtags);

foreach (array ('PHP_SELF', 'HTTP_USER_AGENT', 'HTTP_REFERER', 'QUERY_STRING') as $key) {
    $_SERVER[$key] = isset ($_SERVER[$key]) ? htmlspecialchars($_SERVER[$key], ENT_QUOTES) : null;
}

// Unset vars
unset ($modxtags, $key, $value);
