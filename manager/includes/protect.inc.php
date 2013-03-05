<?php
/**
 *    Protect against some common security flaws
 */

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
    foreach ($_REQUEST as $key => $value) {
        $$key = null; // This is NOT paranoid because
        unset ($$key); // unset may not work.
    }
}

// sanitize array
$modxtags = array('[[', ']]', '[!', '!]', '[*', '*]', '[(', ')]', '{{', '}}', '[+', '+]', '[~', '~]', '[^', '^]');
if (!function_exists('modx_sanitize_gpc')) {
    function modx_sanitize_gpc(&$target, $tags, $count = 0) {
    	foreach ($tags as $_) {
			$replaced[] = " {$_['0']} {$_['1']} ";
		}
		foreach ($target as $key => $value) {
			if (is_array($value)) {
				$count++;
				if (10 < $count) {
					echo '<h1>Error: array nested too deep!</h1>';
					exit;
				}
				modx_sanitize_gpc($value, $tags, $count);
			} else {
				$value = str_replace($tags, $replaced, $value);
				$value = preg_replace('/<script/i', 'sanitized<s cript', $value);
				$value = preg_replace('/&#(\d{1,4});?/', 'sanitized& #$1', $value);
				$value = preg_replace('/&#x([0-9a-f]{1,4});?/i', 'sanitized& #x$1', $value);
				$target[$key] = $value;
			}
			$count = 0;
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
