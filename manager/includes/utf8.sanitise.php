<?php
/**
 * Ensures UTF-8 in GPC is well-formed.
 *
 * Does not affect magic quotes.
 *
 * @author TimgS
 */
 
if (!is_object($modx) || !is_array($modx->config) || !isset($modx->config['modx_charset'])) exit(); // Silent exit.

if (strtoupper($modx->config['charset']) == 'UTF-8') {

	function http_input_clean($_ARRAY) {
		if (is_array($_ARRAY)) {
			$array = array();
			foreach($_ARRAY as $key=>$value) {
				if (is_array($value)) {
					$array[$key] = http_input_clean($value);
				} else {
					// 2. Let iconv discard any invalid characters.
					$array[$key] = iconv('UTF-8', 'UTF-8//IGNORE', get_magic_quotes_gpc() ? stripslashes($value) : $value);
					if (get_magic_quotes_gpc()) $array[$key] = addslashes($array[$key]);
				}
			}

			return $array;
		} else {
			return null;
		}
	}

	$_GET = http_input_clean($_GET);
	$_POST = http_input_clean($_POST);
	$_REQUEST = http_input_clean($_REQUEST);
	$_COOKIE = http_input_clean($_COOKIE);
}

