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
 *                                   ------------------------------ 5.2 ---
 * @group 5_2
 * @since 5.2
 *
 * Additions of PHP 5.2.0
 * - some listed here might have appeared earlier or in release candidates
 *
 * @emulated
 *    json_encode
 *    json_decode
 *    error_get_last
 *    preg_last_error
 *    lchown
 *    lchgrp
 *    E_RECOVERABLE_ERROR
 *    M_SQRTPI
 *    M_LNPI
 *    M_EULER
 *    M_SQRT3
 *    array_fill_keys  (@doc: 4.2 or 5.2 ?)
 *    array_diff_key   (@doc: 5.1 or 5.2 ?)
 *    array_diff_ukey
 *    array_product
 *    inet_ntop
 *    inet_pton
 *    array_intersect_key
 *    array_intersect_ukey
 *
 * @missing
 *    sys_getloadavg
 *    ftp_ssl_connect
 *    XmlReader
 *    XmlWriter
 *    PDO*
 *    pdo_drivers     (should be in ext/pdo)
 *
 * @unimplementable
 *    stream_*
 *
 */





/**
 * @since unknown
 */
if (!defined("E_RECOVERABLE_ERROR")) { define("E_RECOVERABLE_ERROR", 4096); }



/**
 * Converts PHP variable or array into a "JSON" (JavaScript value expression
 * or "object notation") string.
 *
 * @compat
 *    Output seems identical to PECL versions. "Only" 20x slower than PECL version.
 * @bugs
 *    Doesn't take care with unicode too much - leaves UTF-8 sequences alone.
 *
 * @param  $var mixed  PHP variable/array/object
 * @return string      transformed into JSON equivalent
 */
if (!function_exists("json_encode")) {
   function json_encode($var, /*emu_args*/$obj=FALSE) {
   
      #-- prepare JSON string
      $json = "";
      
      #-- add array entries
      if (is_array($var) || ($obj=is_object($var))) {

         #-- check if array is associative
         if (!$obj) foreach ((array)$var as $i=>$v) {
            if (!is_int($i)) {
               $obj = 1;
               break;
            }
         }

         #-- concat invidual entries
         foreach ((array)$var as $i=>$v) {
            $json .= ($json ? "," : "")    // comma separators
                   . ($obj ? ("\"$i\":") : "")   // assoc prefix
                   . (json_encode($v));    // value
         }

         #-- enclose into braces or brackets
         $json = $obj ? "{".$json."}" : "[".$json."]";
      }

      #-- strings need some care
      elseif (is_string($var)) {
         if (!utf8_decode($var)) {
            $var = utf8_encode($var);
         }
         $var = str_replace(array("\\", "\"", "/", "\b", "\f", "\n", "\r", "\t"), array("\\\\", '\"', "\\/", "\\b", "\\f", "\\n", "\\r", "\\t"), $var);
         $json = '"' . $var . '"';
         //@COMPAT: for fully-fully-compliance   $var = preg_replace("/[\000-\037]/", "", $var);
      }

      #-- basic types
      elseif (is_bool($var)) {
         $json = $var ? "true" : "false";
      }
      elseif ($var === NULL) {
         $json = "null";
      }
      elseif (is_int($var) || is_float($var)) {
         $json = "$var";
      }

      #-- something went wrong
      else {
         trigger_error("json_encode: don't know what a '" .gettype($var). "' is.", E_USER_ERROR);
      }
      
      #-- done
      return($json);
   }
}



/**
 * Parses a JSON (JavaScript value expression) string into a PHP variable
 * (array or object).
 *
 * @compat
 *    Behaves similar to PECL version, but is less quiet on errors.
 *    Now even decodes unicode \uXXXX string escapes into UTF-8.
 *    "Only" 27 times slower than native function.
 * @bugs
 *    Might parse some misformed representations, when other implementations
 *    would scream error or explode.
 * @code
 *    This is state machine spaghetti code. Needs the extranous parameters to
 *    process subarrays, etc. When it recursively calls itself, $n is the
 *    current position, and $waitfor a string with possible end-tokens.
 *
 * @param   $json string   JSON encoded values
 * @param   $assoc bool    pack data into php array/hashes instead of objects
 * @return  mixed          parsed into PHP variable/array/object
 */
if (!function_exists("json_decode")) {
   function json_decode($json, $assoc=FALSE, $limit=512, /*emu_args*/$n=0,$state=0,$waitfor=0) {

      #-- result var
      $val = NULL;
      static $lang_eq = array("true" => TRUE, "false" => FALSE, "null" => NULL);
      static $str_eq = array("n"=>"\012", "r"=>"\015", "\\"=>"\\", '"'=>'"', "f"=>"\f", "b"=>"\b", "t"=>"\t", "/"=>"/");
      if ($limit<0) return /* __cannot_compensate */;

      #-- flat char-wise parsing
      for (/*n*/; $n<strlen($json); /*n*/) {
         $c = $json[$n];

         #-= in-string
         if ($state==='"') {

            if ($c == '\\') {
               $c = $json[++$n];
               // simple C escapes
               if (isset($str_eq[$c])) {
                  $val .= $str_eq[$c];
               }

               // here we transform \uXXXX Unicode (always 4 nibbles) references to UTF-8
               elseif ($c == "u") {
                  // read just 16bit (therefore value can't be negative)
                  $hex = hexdec( substr($json, $n+1, 4) );
                  $n += 4;
                  // Unicode ranges
                  if ($hex < 0x80) {    // plain ASCII character
                     $val .= chr($hex);
                  }
                  elseif ($hex < 0x800) {   // 110xxxxx 10xxxxxx 
                     $val .= chr(0xC0 + $hex>>6) . chr(0x80 + $hex&63);
                  }
                  elseif ($hex <= 0xFFFF) { // 1110xxxx 10xxxxxx 10xxxxxx 
                     $val .= chr(0xE0 + $hex>>12) . chr(0x80 + ($hex>>6)&63) . chr(0x80 + $hex&63);
                  }
                  // other ranges, like 0x1FFFFF=0xF0, 0x3FFFFFF=0xF8 and 0x7FFFFFFF=0xFC do not apply
               }

               // no escape, just a redundant backslash
               //@COMPAT: we could throw an exception here
               else {
                  $val .= "\\" . $c;
               }
            }

            // end of string
            elseif ($c == '"') {
               $state = 0;
            }

            // yeeha! a single character found!!!!1!
            else/*if (ord($c) >= 32)*/ { //@COMPAT: specialchars check - but native json doesn't do it?
               $val .= $c;
            }
         }

         #-> end of sub-call (array/object)
         elseif ($waitfor && (strpos($waitfor, $c) !== false)) {
            return array($val, $n);  // return current value and state
         }
         
         #-= in-array
         elseif ($state===']') {
            list($v, $n) = json_decode($json, $assoc, $limit, $n, 0, ",]");
            $val[] = $v;
            if ($json[$n] == "]") { return array($val, $n); }
         }

         #-= in-object
         elseif ($state==='}') {
            list($i, $n) = json_decode($json, $assoc, $limit, $n, 0, ":");   // this allowed non-string indicies
            list($v, $n) = json_decode($json, $assoc, $limit, $n+1, 0, ",}");
            $val[$i] = $v;
            if ($json[$n] == "}") { return array($val, $n); }
         }

         #-- looking for next item (0)
         else {
         
            #-> whitespace
            if (preg_match("/\s/", $c)) {
               // skip
            }

            #-> string begin
            elseif ($c == '"') {
               $state = '"';
            }

            #-> object
            elseif ($c == "{") {
               list($val, $n) = json_decode($json, $assoc, $limit-1, $n+1, '}', "}");
               
               if ($val && $n) {
                  $val = $assoc ? (array)$val : (object)$val;
               }
            }

            #-> array
            elseif ($c == "[") {
               list($val, $n) = json_decode($json, $assoc, $limit-1, $n+1, ']', "]");
            }

            #-> comment
            elseif (($c == "/") && ($json[$n+1]=="*")) {
               // just find end, skip over
               ($n = strpos($json, "*/", $n+1)) or ($n = strlen($json));
            }

            #-> numbers
            elseif (preg_match("#^(-?\d+(?:\.\d+)?)(?:[eE]([-+]?\d+))?#", substr($json, $n), $uu)) {
               $val = $uu[1];
               $n += strlen($uu[0]) - 1;
               if (strpos($val, ".")) {  // float
                  $val = (float)$val;
               }
               elseif ($val[0] == "0") {  // oct
                  $val = octdec($val);
               }
               else {
                  $val = (int)$val;
               }
               // exponent?
               if (isset($uu[2])) {
                  $val *= pow(10, (int)$uu[2]);
               }
            }

            #-> boolean or null
            elseif (preg_match("#^(true|false|null)\b#", substr($json, $n), $uu)) {
               $val = $lang_eq[$uu[1]];
               $n += strlen($uu[1]) - 1;
            }

            #-- parsing error
            else {
               // PHPs native json_decode() breaks here usually and QUIETLY
              trigger_error("json_decode: error parsing '$c' at position $n", E_USER_WARNING);
               return $waitfor ? array(NULL, 1<<30) : NULL;
            }

         }//state
         
         #-- next char
         if ($n === NULL) { return NULL; }
         $n++;
      }//for

      #-- final result
      return ($val);
   }
}




/**
 * @stub
 *
 * Should return last PCRE error.
 *
 */
if (!function_exists("preg_last_error")) {
   if (!defined("PREG_NO_ERROR")) { define("PREG_NO_ERROR", 0); }
   if (!defined("PREG_INTERNAL_ERROR")) { define("PREG_INTERNAL_ERROR", 1); }
   if (!defined("PREG_BACKTRACK_LIMIT_ERROR")) { define("PREG_BACKTRACK_LIMIT_ERROR", 2); }
   if (!defined("PREG_RECURSION_LIMIT_ERROR")) { define("PREG_RECURSION_LIMIT_ERROR", 3); }
   if (!defined("PREG_BAD_UTF8_ERROR")) { define("PREG_BAD_UTF8_ERROR", 4); }
   function preg_last_error() {
      return PREG_NO_ERROR;
   }
}




/**
 * returns path of the system directory for temporary files
 *
 * @since 5.2.1
 */
if (!function_exists("sys_get_temp_dir")) {
   function sys_get_temp_dir() {
      # check possible alternatives
      ($temp = ini_get("temp_dir"))
      or
      ($temp = @$_ENV["TEMP"])
      or
      ($temp = @$_ENV["TMP"])
      or
      ($temp = "/tmp");
      # fin
      return($temp);
   }
}



/**
 * @stub
 *
 * Should return associative array with last error message.
 *
 */
if (!function_exists("error_get_last")) {
   function error_get_last() {
      return array(
         "type" => 0,
         "message" => $GLOBALS["php_errormsg"],
         "file" => "unknonw",
         "line" => 0,
      );
   }
}




/**
 * @flag quirky, exec, realmode
 *
 * Change owner of a symlink filename.
 *
 */
if (!function_exists("lchown")) {
   function lchown($fn, $user) {
      if (PHP_OS != "Linux") {
         return false;
      }
      $user = escapeshellcmd($user);
      $fn = escapeshellcmd($fn);
      exec("chown -h '$user' '$fn'", $uu, $state);
      return($state);
   }
}



/**
 * @flag quirky, exec, realmode
 *
 * Change group of a symlink filename.
 *
 */
if (!function_exists("lchgrp")) {
   function lchgrp($fn, $group) {
      return lchown($fn, ":$group");
   }
}



/**
 * @doc: Got this function new in PHP 5.2, but documentation says 4.2 ???
 * 
 * array_fill() with given $keys
 *
 */
if (!function_exists("array_fill_keys")) {
   function array_fill_keys($keys, $value) {
      return array_combine($keys, array_fill(0, count($keys), $value));
   }
}



/**
 * @doc: php manual says 5.1, but function appeared with 5.2
 *
 * Returns array entries, whose keys are not in any of the comparison arrays.
 *
 */
if (!function_exists("array_diff_key")) {
   function array_diff_key($base /*...*/) {
      $other = func_get_args();
      array_shift($other);

      $cmp = call_user_func_array("array_merge", array_map("array_keys", $other));

      foreach ($cmp as $key) {
            $key = (string) $key;
            if (array_key_exists($key, $base)) {
               // cannot compare if $key is actually a string in $base
               unset($base[$key]);
            }
      }
      return ($base);
   }
}




/**
 * @doc: php manual says 5.1, but function appeared with 5.2
 *
 * Uses callback function to compare array keys.
 * Callback returns -1, 0, +1, and then some keys are filtered???
 * Let's assume ==0 is meant for no difference --> and no difference => filter out
 *
 */
if (!function_exists("array_diff_ukey")) {
   function array_diff_ukey($base, $other_arrays/*...*/, $callback) {
      $other = func_get_args();
      array_shift($other);
      $callback = array_pop($other);
      
      $cmp = call_user_func_array("array_merge", array_map("array_keys", $other));

      foreach ($base as $key=>$value) {
         // compare against each key from $other arrays
         foreach ($cmp as $k) {
            if ($callback($key, $k) === 0) {
               unset($base[$key]);
            }
         }
      }
      return $base;      
   }
}



/**
 * @doc: 5.1 vs 5.2
 *
 * Keeps only array-entries, if key exists also in comparison arrays
 *
 */
if (!function_exists("array_intersect_key")) {
   function array_intersect_key($base /*...*/) {
      $all_arrays = array_map("array_keys", func_get_args());
      $keep = call_user_func_array("array_intersect", $all_arrays);
      
      $r = array();
      foreach ($keep as $k) {
         $r[$k] = $base[$k];
      }
      return ($r);
   }
}



/**
 * @doc: 5.1 vs 5.2
 *
 * array_uintersect on keys
 *
 */
if (!function_exists("array_intersect_ukey")) {
   function array_intersect_ukey(/*...*/) {
      $args = func_get_args();
      $base = $args[0];
      $callback = array_pop($other);

      $keys = array_map("array_values", $args);
      $intersect = call_user_func_array("array_uintersect", array_merge($keys, array($callback)));
      
      $r = array();
      foreach ($intersect as $key) {
         $r[$key] = $base[$key];
      }
      return $r;
   }
}







/**
 * Hmmm.
 *
 */
if (!function_exists("array_product")) {
   function array_product($multiply_us) {
      $r = count($multiply_us) ? 1 : NULL;
      foreach ($multiply_us as $m) {
         $r = $r * $m;
      }
      return $r;
   }
}



/**
 * Converts chr/bin/string-representation to human-readable IP text.
 *
 */
if (!function_exists("inet_ntop")) {
   function inet_ntop($bin) {
      if (strlen($bin) == 4) {   // IPv4
         return implode(".", array_map("ord", str_split($bin, 1)));
      }
      elseif (strlen($bin) == 16) {  // IPv6
         return preg_replace("/:?(0000:)+/", "::", implode(":", str_split(bin2hex($bin), 4)));
      }
      elseif (strlen($bin) == 6) {  // MAC
         return implode(":", str_split(bin2hex($bin), 2));
      }
   }
}


/**
 * Compact IPv4 1.2.3.4 or IPv6 ::FFFF:0001 addresses into binary string.
 *
 */
if (!function_exists("inet_pton")) {
   function inet_pton($str) {
      if (strpos($str, ".")) {  // IPv4
         return array_map("chr", explode(".", $str));
      }
      elseif (strstr($str, ":")) { // IPv6
         $str = str_replace("::", str_repeat(":", 2 + 7 - substr_count($str, ":")), $str);   // padding "::" can appear anywhere inside, replaces 7-x other :0000 colons and zeros
         $str = implode(array_map("inet_pton___ipv6_pad", explode(":", $str)));
         return pack("H32", $str);
      }
   }
   function inet_pton___ipv6_pad($s) {
      return str_pad($s, 4, "0", STR_PAD_LEFT);
   }
}
