<?php
/**
 * api:		php
 * title:	upgrade.php
 * description:	Emulates functions from new PHP versions on older interpreters.
 * version:	17
 * license:	Public Domain
 * url:		http://freshmeat.net/projects/upgradephp
 * type:	functions
 * category:	library
 * priority:	auto
 * load_if:     (PHP_VERSION<5.2)
 * sort:	-255
 * provides:	upgrade-php, api:php5, json
 *
 *
 * By loading this library you get PHP version independence. It provides
 * downwards compatibility to older PHP interpreters by emulating missing
 * functions or constants using IDENTICAL NAMES. So this doesn't slow down
 * script execution on setups where the native functions already exist. It
 * is meant as quick drop-in solution. It spares you from rewriting code or
 * using cumbersome workarounds instead of the more powerful v5 functions.
 * 
 * It cannot mirror PHP5s extended OO-semantics and functionality into PHP4
 * however. A few features are added here that weren't part of PHP yet. And
 * some other function collections are separated out into the ext/ directory.
 * It doesn't produce many custom error messages (YAGNI), and instead leaves
 * reporting to invoked functions or for native PHP execution.
 * 
 * And further this is PUBLIC DOMAIN (no copyright, no license, no warranty)
 * so therefore compatible to ALL open source licenses. You could rip this
 * paragraph out to republish this instead only under more restrictive terms
 * or your favorite license (GNU LGPL/GPL, BSDL, MPL/CDDL, Artistic/PHPL, ..)
 *
 * Any contribution is appreciated. <milky*users#sf#net>
 *
 */





/**
 *                                   --------------------- CVS / FUTURE ---
 * @group CVS
 * @since future
 *
 * Following functions aren't implemented in current PHP versions, but
 * might already be in CVS/SVN.
 *
 * @emulated
 *    gzdecode
 *
 * @moved out
 *    contrib/xmlentities
 *
 */




/**
 *                                   ------------------------------ 5.1 ---
 * @group 5_1
 * @since 5.1
 *
 * Additions in PHP 5.1
 * - most functions here appeared in -rc1 already
 * - and were backported to 4.4 series?
 *
 * @emulated
 *    property_exists
 *    time_sleep_until
 *    fputcsv
 *    strptime
 *    ENT_COMPAT
 *    ENT_QUOTES
 *    ENT_NOQUOTES
 *    htmlspecialchars_decode
 *    PHP_INT_SIZE
 *    PHP_INT_MAX
 *    M_SQRTPI
 *    M_LNPI
 *    M_EULER
 *    M_SQRT3
 *
 * @missing
 *    strptime
 *
 * @unimplementable
 *    ...
 *
 */



/**
 * Constants for future 64-bit integer support.
 *
 */
if (!defined("PHP_INT_SIZE")) { define("PHP_INT_SIZE", 4); }
if (!defined("PHP_INT_MAX")) { define("PHP_INT_MAX", 2147483647); }



/**
 * @flag bugfix
 * @see #33895
 *
 * Missing constants in 5.1, originally appeared in 4.0.
 */
if (!defined("M_SQRTPI")) { define("M_SQRTPI", 1.7724538509055); }
if (!defined("M_LNPI")) { define("M_LNPI", 1.1447298858494); }
if (!defined("M_EULER")) { define("M_EULER", 0.57721566490153); }
if (!defined("M_SQRT3")) { define("M_SQRT3", 1.7320508075689); }




/**
 * removes entities &lt; &gt; &amp; and eventually &quot; from HTML string
 *
 */
if (!function_exists("htmlspecialchars_decode")) {
   if (!defined("ENT_COMPAT")) { define("ENT_COMPAT", 2); }
   if (!defined("ENT_QUOTES")) { define("ENT_QUOTES", 3); }
   if (!defined("ENT_NOQUOTES")) { define("ENT_NOQUOTES", 0); }
   function htmlspecialchars_decode($string, $quotes=2) {
      $d = $quotes & ENT_COMPAT;
      $s = $quotes & ENT_QUOTES;
      return str_replace(
         array("&lt;", "&gt;", ($s ? "&quot;" : "&.-;"), ($d ? "&#039;" : "&.-;"), "&amp;"),
         array("<",    ">",    "'",                      "\"",                     "&"),
         $string
      );
   }
}



/**
 * @flag needs5
 *
 * Checks for existence of object property, should return TRUE even for NULL values.
 *
 * @compat
 *    no test for edge cases
 */
if (!function_exists("property_exists")) {
   function property_exists($obj, $propname) {
      if (is_object($obj)) {
         $props = array_keys(get_object_vars($obj));
      }
      elseif (class_exists($obj)) {
         $props = array_keys(get_class_vars($obj));
      }
      return !empty($props) and in_array($propname, $props);
   }
}



/**
 * halt execution, until given timestamp
 *
 */
if (!function_exists("time_sleep_until")) {
   function time_sleep_until($t) {
      $delay = $t - time();
      if ($delay < 0) {
         trigger_error("time_sleep_until: timestamp in the past", E_USER_WARNING);
         return false;
      }
      else {
         sleep((int)$delay);
         #usleep(($delay - floor($delay)) * 1000000);
         return true;
      }
   }
}



/**
 * @untested
 *
 * Writes an array as CSV text line into opened filehandle.
 *
 */
if (!function_exists("fputcsv")) {
   function fputcsv($fp, $fields, $delim=",", $encl='"') {
      $line = "";
      foreach ((array)$fields as $str) {
         $line .= ($line ? $delim : "")
                . $encl
                . str_replace(array('\\', $encl), array('\\\\'. '\\'.$encl), $str)
                . $encl;
      }
      fwrite($fp, $line."\n");
   }
}



/**
 * @flag basic
 * @untested
 *
 * @compat
 *    only implements a few basic regular expression lookups
 *    no idea how to handle all of it
 */
if (!function_exists("strptime")) {
   function strptime($str, $format) {
      static $expand = array(
         "%D" => "%m/%d/%y",
         "%T" => "%H:%M:%S",
      );
      static $map_r = array(
          "%S"=>"tm_sec",
          "%M"=>"tm_min",
          "%H"=>"tm_hour",
          "%d"=>"tm_mday",
          "%m"=>"tm_mon",
          "%Y"=>"tm_year",
          "%y"=>"tm_year",
          "%W"=>"tm_wday",
          "%D"=>"tm_yday",
          "%u"=>"unparsed",
      );
      static $names = array(
         "Jan" => 1, "Feb" => 2, "Mar" => 3, "Apr" => 4, "May" => 5, "Jun" => 6,
         "Jul" => 7, "Aug" => 8, "Sep" => 9, "Oct" => 10, "Nov" => 11, "Dec" => 12,
         "Sun" => 0, "Mon" => 1, "Tue" => 2, "Wed" => 3, "Thu" => 4, "Fri" => 5, "Sat" => 6,
      );

      #-- transform $format into extraction regex
      $format = str_replace(array_keys($expand), array_values($expand), $format);
      $preg = preg_replace("/(%\w)/", "(\w+)", preg_quote($format));

      #-- record the positions of all STRFCMD-placeholders
      preg_match_all("/(%\w)/", $format, $positions);
      $positions = $positions[1];
      
      #-- get individual values
      if (preg_match("#$preg#", "$str", $extracted)) {

         #-- get values
         foreach ($positions as $pos=>$strfc) {
            $v = $extracted[$pos + 1];

            #-- add
            if ($n = $map_r[$strfc]) {
               $vals[$n] = ($v > 0) ? (int)$v : $v;
            }
            else {
               $vals["unparsed"] .= $v . " ";
            }
         }
         
         #-- fixup some entries
         $vals["tm_wday"] = $names[ substr($vals["tm_wday"], 0, 3) ];
         if ($vals["tm_year"] >= 1900) {
            $tm_year -= 1900;
         }
         elseif ($vals["tm_year"] > 0) {
            $vals["tm_year"] += 100;
         }
         if ($vals["tm_mon"]) {
            $vals["tm_mon"] -= 1;
         }
         else {
            $vals["tm_mon"] = $names[ substr($vals["tm_mon"], 0, 3) ] - 1;
         }
         
         #-- calculate wday
         // ... (mktime)
      }
      return isset($vals) ? $vals : false;
   }
}
