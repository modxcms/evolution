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
 *                                   ------------------------------ 5.0 ---
 * @group 5_0
 * @since 5.0
 *
 * PHP 5.0 introduces the Zend Engine 2 with new object-orientation features
 * which cannot be reimplemented/defined for PHP4. The additional procedures
 * and functions however can.
 *
 * @emulated
 *    stripos
 *    strripos
 *    str_ireplace
 *    get_headers
 *    headers_list
 *    fprintf
 *    vfprintf
 *    str_split
 *    http_build_query
 *    convert_uuencode
 *    convert_uudecode
 *    scandir
 *    idate
 *    time_nanosleep
 *    strpbrk
 *    get_declared_interfaces
 *    array_combine
 *    array_walk_recursive
 *    substr_compare
 *    spl_classes
 *    class_parents
 *    session_commit
 *    dns_check_record
 *    dns_get_mx
 *    setrawcookie
 *    file_put_contents
 *    COUNT_NORMAL
 *    COUNT_RECURSIVE
 *    count_recursive
 *    FILE_USE_INCLUDE_PATH
 *    FILE_IGNORE_NEW_LINES
 *    FILE_SKIP_EMPTY_LINES
 *    FILE_APPEND
 *    FILE_NO_DEFAULT_CONTEXT
 *    E_STRICT
 *
 * @missing
 *    proc_nice
 *    dns_get_record
 *    date_sunrise - undoc.
 *    date_sunset - undoc.
 *    PHP_CONFIG_FILE_SCAN_DIR
 *    clone
 *
 * @unimplementable
 *    set_exception_handler
 *    restore_exception_handler
 *    debug_print_backtrace - in ext, needs4.3
 *    debug_backtrace       - stub
 *    class_implements
 *    proc_terminate
 *    proc_get_status
 *    range        - new param
 *    microtime    - new param
 *
 */
 
 


#-- constant: end of line
if (!defined("PHP_EOL")) { define("PHP_EOL", ( (DIRECTORY_SEPARATOR == "\\") ? "\015\012" : (strncmp(PHP_OS, "D", 1) ? "\012" : "\015") )  ); } # "D" for Darwin



/**
 * case-insensitive string search function,
 * - finds position of first occourence of a string c-i
 * - parameters identical to strpos()
 */
if (!function_exists("stripos")) {
   function stripos($haystack, $needle, $offset=NULL) {
   
      #-- simply lowercase args
      $haystack = strtolower($haystack);
      $needle = strtolower($needle);
      
      #-- search
      $pos = strpos($haystack, $needle, $offset);
      return($pos);
   }
}




/**
 * case-insensitive string search function
 * - but this one starts from the end of string (right to left)
 * - offset can be negative or positive
 * 
 */
if (!function_exists("strripos")) {
   function strripos($haystack, $needle, $offset=NULL) {

      #-- lowercase incoming strings
      $haystack = strtolower($haystack);
      $needle = strtolower($needle);

      #-- [-]$offset tells to ignore a few string bytes,
      #   we simply cut a bit from the right
      if (isset($offset) && ($offset < 0)) {
         $haystack = substr($haystack, 0, strlen($haystack) - 1);
      }

      #-- let PHP do it
      $pos = strrpos($haystack, $needle);

      #-- [+]$offset => ignore left haystack bytes
      if (isset($offset) && ($offset > 0) && ($pos > $offset)) {
         $pos = false;
      }

      #-- result      
      return($pos);
   }
}


/**
 * case-insensitive version of str_replace
 * 
 */
if (!function_exists("str_ireplace")) {
   function str_ireplace($search, $replace, $subject, $count=NULL) {

      #-- call ourselves recursively, if parameters are arrays/lists 
      if (is_array($search)) {
         $replace = array_values($replace);
         foreach (array_values($search) as $i=>$srch) {
            $subject = str_ireplace($srch, $replace[$i], $subject);
         }
      }
      
      #-- sluice replacement strings through the Perl-regex module
      #   (faster than doing it by hand)
      else {
         $replace = addcslashes($replace, "$\\");
         $search = "{" . preg_quote($search) . "}i";
         $subject = preg_replace($search, $replace, $subject);
      }

      #-- result
      return($subject);
   }
}


/**
 * performs a http HEAD request
 * 
 */
if (!function_exists("get_headers")) {
   function get_headers($url, $parse=0) {
   
      #-- extract URL parts ($host, $port, $path, ...)
      $c = parse_url($url);
      $c = array_merge(array("port"=>"80", "path"=>"/"), $c);
      extract($c);
      
      #-- try to open TCP connection      
      $f = fsockopen($host, $port, $errno, $errstr, $timeout=15);
      if (!$f) {
         return;
      }

      #-- send request header
      socket_set_blocking($f, true);
      fwrite($f, "HEAD $path HTTP/1.0\015\012"
               . "Host: $host\015\012"
               . "Connection: close\015\012"
               . "Accept: */*, xml/*\015\012"
               . "User-Agent: ".trim(ini_get("user_agent"))."\015\012"
               . "\015\012");

      #-- read incoming lines
      $ls = array();
      while ( !feof($f) && ($line = trim(fgets($f, 1<<16))) ) {
         
         #-- read header names to make result an hash (names in array index)
         if ($parse) {
            if ($l = strpos($line, ":")) {
               $name = substr($line, 0, $l);
               $value = trim(substr($line, $l + 1));
               #-- merge headers
               if (isset($ls[$name])) {
                  $ls[$name] .= ", $value";
               }
               else {
                  $ls[$name] = $value;
               }
            }
            #-- HTTP response status header as result[0]
            else {
               $ls[] = $line;
            }
         }
         
         #-- unparsed header list (numeric indices)
         else {
            $ls[] = $line;
         }
      }

      #-- close TCP connection and give result
      fclose($f);
      return($ls);
   }
}


/**
 * @stub
 * list of already/potentially sent HTTP responsee headers(),
 * CANNOT be implemented (except for Apache module maybe)
 * 
 */
if (!function_exists("headers_list")) {
   function headers_list() {
      trigger_error("headers_list(): not supported by this PHP version", E_USER_WARNING);
      return (array)NULL;
   }
}


/**
 * write formatted string to stream/file,
 * arbitrary numer of arguments
 * 
 */
if (!function_exists("fprintf")) {
   function fprintf(/*...*/) {
      $args = func_get_args();
      $stream = array_shift($args);
      return fwrite($stream, call_user_func_array("sprintf", $args));
   }
}


/**
 * write formatted string to stream, args array
 * 
 */
if (!function_exists("vfprintf")) {
   function vfprintf($stream, $format, $args=NULL) {
      return fwrite($stream, vsprintf($format, $args));
   }
}


/**
 * splits a string in evenly sized chunks
 * 
 * @return array
 */
if (!function_exists("str_split")) {
   function str_split($str, $chunk=1) {
      $r = array();
      
      #-- return back as one chunk completely, if size chosen too low
      if ($chunk < 1) {
         $r[] = $str;
      }
      
      #-- add substrings to result array until subject strings end reached
      else {
         $len = strlen($str);
         for ($n=0; $n<$len; $n+=$chunk) {
            $r[] = substr($str, $n, $chunk);
         }
      }
      return($r);
   }
}


/**
 * constructs a QUERY_STRING (application/x-www-form-urlencoded format, non-raw)
 * from a nested array/hash with name=>value pairs
 * - only first two args are part of the original API - rest used for recursion
 *
 * @param  mixed  $vars           variable data for query string
 * @param  string $int_prefix     (optional)
 * @param  string $subarray_pfix  (optional)
 * @param integer $level  
 * @return mixed
 */
if (!function_exists("http_build_query")) {
   function http_build_query($vars, $int_prefix="", $subarray_pfix="", $level=0) {
   
      #-- empty starting string
      $s = "";
      ($SEP = ini_get("arg_separator.output")) or ($SEP = "&");
      
      #-- traverse hash/array/list entries 
      foreach ($vars as $index=>$value) {
         
         #-- add sub_prefix for subarrays (happens for recursed innovocation)
         if ($subarray_pfix) {
            if ($level) {
               $index = "[" . $index . "]";
            }
            $index =  $subarray_pfix . $index;
         }
         #-- add user-specified prefix for integer-indices
         elseif (is_int($index) && strlen($int_prefix)) {
            $index = $int_prefix . $index;
         }
         
         #-- recurse for sub-arrays
         if (is_array($value)) {
            $s .= http_build_query($value, "", $index, $level + 1);
         }
         else {   // or just literal URL parameter
            $s .= $SEP . $index . "=" . urlencode($value);
         }
      }
      
      #-- remove redundant "&" from first round (-not checked above to simplifiy loop)
      if (!$subarray_pfix) {
         $s = substr($s, strlen($SEP));
      }

      #-- return result / to previous array level and iteration
      return($s);
   }
}


/**
 * transform into 3to4 uuencode
 * - this is the bare encoding, not the uu file format
 * 
 * @param  string
 * @return string
 */
if (!function_exists("convert_uuencode")) {
   function convert_uuencode($bin) {

      #-- init vars
      $out = "";
      $line = "";
      $len = strlen($bin);
      $bin .= "\01\01\01";   // PHP and uuencode(1) use some special garbage??, looks like "\000"* and "`\n`" simply appended

      #-- canvass source string
      for ($n=0; $n<$len; ) {
      
         #-- make 24-bit integer from first three bytes
         $x = (ord($bin[$n++]) << 16)
            + (ord($bin[$n++]) <<  8)
            + (ord($bin[$n++]) <<  0);
            
         #-- disperse that into 4 ascii characters
         $line .= chr( 32 + (($x >> 18) & 0x3f) )
                . chr( 32 + (($x >> 12) & 0x3f) )
                . chr( 32 + (($x >>  6) & 0x3f) )
                . chr( 32 + (($x >>  0) & 0x3f) );
                
         #-- cut lines, inject count prefix before each
         if (($n % 45) == 0) {
            $out .= chr(32 + 45) . "$line\n";
            $line = "";
         }
      }

      #-- throw last line, +length prefix
      if ($trail = ($len % 45)) {
         $out .= chr(32 + $trail) . "$line\n";
      }

      // uuencode(5) doesn't tell so, but spaces are replaced with the ` char in most implementations
      $out = strtr("$out \n", " ", "`");
      return($out);
   }
}


/**
 * decodes uuencoded() data again
 *
 * @param  string $from  
 * @return string
 */
if (!function_exists("convert_uudecode")) {
   function convert_uudecode($from) {

      #-- prepare
      $out = "";
      $from = strtr($from, "`", " ");
      
      #-- go through lines
      foreach(explode("\n", ltrim($from)) as $line) {
         if (!strlen($line)) {
            break;  // end reached
         }
         
         #-- current line length prefix
         unset($num);
         $num = ord($line{0}) - 32;
         if (($num <= 0) || ($num > 62)) {  // 62 is the maximum line length
            break;          // according to uuencode(5), so we stop here too
         }
         $line = substr($line, 1);
         
         #-- prepare to decode 4-char chunks
         $add = "";
         for ($n=0; strlen($add)<$num; ) {
         
            #-- merge 24 bit integer from the 4 ascii characters (6 bit each)
            $x = ((ord($line[$n++]) - 32) << 18)
               + ((ord($line[$n++]) - 32) << 12)  // were saner with "& 0x3f"
               + ((ord($line[$n++]) - 32) <<  6)
               + ((ord($line[$n++]) - 32) <<  0);
               
            #-- reconstruct the 3 original data chars
            $add .= chr( ($x >> 16) & 0xff )
                  . chr( ($x >>  8) & 0xff )
                  . chr( ($x >>  0) & 0xff );
         }

         #-- cut any trailing garbage (last two decoded chars may be wrong)
         $out .= substr($add, 0, $num);
         $line = "";
      }

      return($out);
   }
}


/**
 * return array of filenames in a given directory
 * (only works for local files)
 *
 * @param  string $dirname  
 * @param  bool   $desc  
 * @return array
 */
if (!function_exists("scandir")) {
   function scandir($dirname, $desc=0) {
   
      #-- check for file:// protocol, others aren't handled
      if (strpos($dirname, "file://") === 0) {
         $dirname = substr($dirname, 7);
         if (strpos($dirname, "localh") === 0) {
            $dirname = substr($dirname, strpos($dirname, "/"));
         }
      }
      
      #-- directory reading handle
      if ($dh = opendir($dirname)) {
         $ls = array();
         while ($fn = readdir($dh)) {
            $ls[] = $fn;  // add to array
         }
         closedir($dh);
         
         #-- sort filenames
         if ($desc) {
            rsort($ls);
         }
         else {
            sort($ls);
         }
         return $ls;
      }

      #-- failure
      return false;
   }
}


/**
 * like date(), but returns an integer for given one-letter format parameter
 *
 * @param  string  $formatchar
 * @param  integer $timestamp
 * @return integer
 */
if (!function_exists("idate")) {
   function idate($formatchar, $timestamp=NULL) {
   
      #-- reject non-simple type parameters
      if (strlen($formatchar) != 1) {
         return false;
      }
      
      #-- get current time, if not given
      if (!isset($timestamp)) {
         $timestamp = time();
      }
      
      #-- get and turn into integer
      $str = date($formatchar, $timestamp);
      return (int)$str;
   }
}



/**
 * combined sleep() and usleep() 
 * 
 */
if (!function_exists("time_nanosleep")) {
   function time_nanosleep($sec, $nano) {
      sleep($sec);
      usleep($nano);
   }
}




/**
 * search first occourence of any of the given chars, returns rest of haystack
 * (char_list must be a string for compatibility with the real PHP func)
 *
 * @param  string $haystack  
 * @param  string $char_list  
 * @return integer
 */
if (!function_exists("strpbrk")) {
   function strpbrk($haystack, $char_list) {
   
      #-- prepare
      $len = strlen($char_list);
      $min = strlen($haystack);
      
      #-- check with every symbol from $char_list
      for ($n = 0; $n < $len; $n++) {
         $l = strpos($haystack, $char_list{$n});
         
         #-- get left-most occourence
         if (($l !== false) && ($l < $min)) {
            $min = $l;
         }
      }
      
      #-- result
      if ($min) {
         return(substr($haystack, $min));
      }
      else {
         return(false);
      }
   }
}



/**
 * logo image activation URL query strings (gaga feature)
 * 
 */
if (!function_exists("php_real_logo_guid")) {
   function php_real_logo_guid() { return php_logo_guid(); }
   function php_egg_logo_guid() { return zend_logo_guid(); }
}


/**
 * no need to implement this
 * (there aren't interfaces in PHP4 anyhow)
 * 
 */
if (!function_exists("get_declared_interfaces")) {
   function get_declared_interfaces() {
      trigger_error("get_declared_interfaces(): Current script won't run reliably with PHP4.", E_USER_WARNING);
      return( (array)NULL );
   }
}



/**
 * creates an array from lists of $keys and $values
 * (both should have same number of entries)
 *
 * @param  array $keys  
 * @param  array $values  
 * @return array
 */
if (!function_exists("array_combine")) {
   function array_combine($keys, $values) {
   
      #-- convert input arrays into lists
      $keys = array_values($keys);
      $values = array_values($values);
      $r = array();
      
      #-- one from each
      foreach ($values as $i=>$val) {
         if ($key = $keys[$i]) {
            $r[$key] = $val;
         }
         else {
            $r[] = $val;   // useless, PHP would have long aborted here
         }
      }
      return($r);
   }
}


/**
 * apply userfunction to each array element (descending recursively)
 * use it like:  array_walk_recursive($_POST, "stripslashes");
 * - $callback can be static function name or object/method, class/method
 *
 * @param  array  $input  
 * @param  string $callback  
 * @param  array  $userdata  (optional)
 * @return array
 */
if (!function_exists("array_walk_recursive")) {
   function array_walk_recursive(&$input, $callback, $userdata=NULL) {
      #-- each entry
      foreach ($input as $key=>$value) {

         #-- recurse for sub-arrays
         if (is_array($value)) {
            array_walk_recursive($input[$key], $callback, $userdata);
         }

         #-- $callback handles scalars
         else {
            call_user_func_array($callback, array(&$input[$key], $key, $userdata) );
         }
      }

      // no return value
   }
}


/**
 * complicated wrapper around substr() and and strncmp()
 *
 * @param  string  $haystack  
 * @param  string  $needle  
 * @param  integer $offset  
 * @param  integer $len  
 * @param  integer $ci  
 * @return mixed
 */
if (!function_exists("substr_compare")) {
   function substr_compare($haystack, $needle, $offset=0, $len=0, $ci=0) {

      #-- check params   
      if ($len <= 0) {   // not well documented
         $len = strlen($needle);
         if (!$len) { return(0); }
      }
      #-- length exception
      if ($len + $offset >= strlen($haystack)) {
         trigger_error("substr_compare: given length exceeds main_str", E_USER_WARNING);
         return(false);
      }

      #-- cut
      if ($offset) {
         $haystack = substr($haystack, $offset, $len);
      }
      #-- case-insensitivity
      if ($ci) {
         $haystack = strtolower($haystack);
         $needle = strtolower($needle);
      }

      #-- do
      return(strncmp($haystack, $needle, $len));
   }
}


/**
 * stub, returns empty list as usual;
 * you must load "ext/spl.php" beforehand to get this
 * 
 */
if (!function_exists("spl_classes")) {
   function spl_classes() {
      trigger_error("spl_classes(): not built into this PHP version");
      return (array)NULL;
   }
}



/**
 * gets you list of class names the given objects class was derived from, slow
 *
 * @param  object $obj  
 * @return object
 */
if (!function_exists("class_parents")) {
   function class_parents($obj) {
   
      #-- first get full list
      $all = get_declared_classes();
      $r = array();
      
      #-- filter out
      foreach ($all as $potential_parent) {
         if (is_subclass_of($obj, $potential_parent)) {
            $r[$potential_parent] = $potential_parent;
         }
      }
      return($r);
   }
}


/**
 * an alias
 * 
 */
if (!function_exists("session_commit") && function_exists("session_write_close")) {
   function session_commit() {
      // simple
      session_write_close();
   }
}


/**
 * aliases
 *
 * @param  mixed $host  
 * @param  mixed $type  (optional)
 * @return mixed
 */
if (!function_exists("dns_check_record")) {
   function dns_check_record($host, $type=NULL) {
      // synonym to
      return checkdnsrr($host, $type);
   }
}
if (!function_exists("dns_get_mx")) {
   function dns_get_mx($host, $mx) {
      $args = func_get_args();
      // simple alias - except the optional, but referenced third parameter
      if ($args[2]) {
         $w = & $args[2];
      }
      else {
         $w = false;
      }
      return getmxrr($host, $mx, $w);
   }
}


/**
 * setrawcookie(),
 * can this be emulated 100% exactly?
 *
 * @param  string $name 
 * @param  mixed  $value
 * @param  mixed  $expire
 * @param  mixed  $path
 * @param  mixed  $domain
 * @param integer $secure
 * @return string
 */
if (!function_exists("setrawcookie")) {
   // we output everything directly as HTTP header(), PHP doesn't seem
   // to manage an internal cookie list anyhow
   function setrawcookie($name, $value=NULL, $expire=NULL, $path=NULL, $domain=NULL, $secure=0) {
      if (isset($value) && strpbrk($value, ",; \r\t\n\f\014\013")) {
         trigger_error("setrawcookie: value may not contain any of ',; \r\n' and some other control chars; thrown away", E_USER_WARNING);
      }
      else {
         $h = "Set-Cookie: $name=$value"
            . ($expire ? "; expires=" . gmstrftime("%a, %d-%b-%y %H:%M:%S %Z", $expire) : "")
            . ($path ? "; path=$path": "")
            . ($domain ? "; domain=$domain" : "")
            . ($secure ? "; secure" : "");
         header($h);
      }
   }
}


/**
 * write-at-once file access (counterpart to file_get_contents)
 *
 * @param  integer $filename
 * @param  mixed   $content  
 * @param  integer $flags 
 * @param  mixed   $resource
 * @return integer
 */
if (!function_exists("file_put_contents")) {
   function file_put_contents($filename, $content, $flags=0, $resource=NULL) {

      #-- prepare
      $mode = ($flags & FILE_APPEND ? "a" : "w" ) ."b";
      $incl = $flags & FILE_USE_INCLUDE_PATH;
      $length = strlen($content);
//      $resource && trigger_error("EMULATED file_put_contents does not support \$resource parameter.", E_USER_ERROR);
      
      #-- write non-scalar?
      if (is_array($content) || is_object($content)) {
         $content = implode("", (array)$content);
      }

      #-- open for writing
      $f = fopen($filename, $mode, $incl);
      if ($f) {
      
         // locking
         if (($flags & LOCK_EX) && !flock($f, LOCK_EX)) {
            return fclose($f) && false;
         }

         // write
         $written = fwrite($f, $content);
         fclose($f);
         
         #-- only report success, if completely saved
         return($length == $written);
      }
   }
}


/**
 * file-related constants
 *
 */
if (!defined("FILE_USE_INCLUDE_PATH")) { define("FILE_USE_INCLUDE_PATH", 1); }
if (!defined("FILE_IGNORE_NEW_LINES")) { define("FILE_IGNORE_NEW_LINES", 2); }
if (!defined("FILE_SKIP_EMPTY_LINES")) { define("FILE_SKIP_EMPTY_LINES", 4); }
if (!defined("FILE_APPEND")) { define("FILE_APPEND", 8); }
if (!defined("FILE_NO_DEFAULT_CONTEXT")) { define("FILE_NO_DEFAULT_CONTEXT", 16); }



#-- more new constants for 5.0
if (!defined("E_STRICT")) { define("E_STRICT", 2048); }  // _STRICT is a special case of _NOTICE (_DEBUG)
# PHP_CONFIG_FILE_SCAN_DIR



#-- array count_recursive()
if (!defined("COUNT_NORMAL")) { define("COUNT_NORMAL", 0); }      // count($array, 0);
if (!defined("COUNT_RECURSIVE")) { define("COUNT_RECURSIVE", 1); }    // use count_recursive()



/**
 * @since never
 * @nonstandard
 * 
 * we introduce a new function, because we cannot emulate the
 * newly introduced second parameter to count()
 * 
 * @param  array $array 
 * @param  integer $mode
 * @return integer
 */
if (!function_exists("count_recursive")) {
   function count_recursive($array, $mode=1) {
      if (!$mode) {
         return(count($array));
      }
      else {
         $c = count($array);
         foreach ($array as $sub) {
            if (is_array($sub)) {
               $c += count_recursive($sub);
            }
         }
         return($c);
      }
   }
}
