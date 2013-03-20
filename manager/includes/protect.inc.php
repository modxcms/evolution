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
if (!function_exists('modx_sanitize_gpc')) {
	function modx_sanitize_gpc(&$target, $dummy = array(), $count = 0) {
		$tags = array('[', ']', '{', '}');
		$replaced = array('&#x005B;', '&#x005D;', '&#x007B;', '&#x007D;');

		$keys = array_keys($target);
		$values = array_values($target);

		for ($i = 0; $i < count($values); $i++) {
			$key = str_replace($tags, $replaced, $keys[$i]);
			$key = preg_replace('/<script/i', 'sanitized<s cript', $key);
			$key = preg_replace('/&#(\d{1,4});?/', 'sanitized& #$1', $key);
			$keys[$i] = $key;
			if (is_array($values[$i])) {
				$count++;
				if (10 < $count) {
					echo '<h1>Error: array nested too deep!</h1>';
					exit;
				}
				modx_sanitize_gpc($values[$i], $tags, $count);
			} else {
				$value = str_replace($tags, $replaced, $values[$i]);
				$value = preg_replace('/<script/i', 'sanitized<s cript', $value);
				$value = preg_replace('/&#(\d{1,4});?/', 'sanitized& #$1', $value);
				$values[$i] = $value;
			}
			$count = 0;
		}

		$target = array_combine($keys, $values);
	}
}

modx_sanitize_gpc($_GET);
if (!defined('IN_MANAGER_MODE') || (defined('IN_MANAGER_MODE') && (!IN_MANAGER_MODE || IN_MANAGER_MODE == 'false'))) {
    modx_sanitize_gpc($_POST);
}
modx_sanitize_gpc($_COOKIE);
modx_sanitize_gpc($_REQUEST);

foreach (array ('PHP_SELF', 'HTTP_USER_AGENT', 'HTTP_REFERER', 'QUERY_STRING') as $key) {
    $_SERVER[$key] = isset ($_SERVER[$key]) ? htmlspecialchars($_SERVER[$key], ENT_QUOTES) : null;
}

// Unset vars
unset ($key, $value);
