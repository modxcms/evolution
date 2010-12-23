<?php
/**
 * api: php
 * title: future PHP functions
 * descriptions: functions, that are not yet in php.net releases
 *
 *
 *  
 */



/**
 * @nonstandard
 *
 * Encodes required named XML entities. It's like htmlentities().
 * Doesn't re-encode or fix numeric entities.
 *
 * @param string
 * @return string
 */
if (!function_exists("xmlentities")) {
   function xmlentities($str) {
      return strtr($str, array(
        "&#"=>"&#", "&"=>"&amp;", "'"=>"&apos;",
        "<"=>"&lt;", ">"=>"&gt;", "\""=>"&quot;", 
      ));
   }
}


?>