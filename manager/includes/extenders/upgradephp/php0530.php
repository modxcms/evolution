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
 *                                   ----------------------------- 5.3 ---
 * @group 5_3
 * @since 5.3
 *
 * Known additions of PHP 5.3
 *
 * @emulated
 *    ob_get_headers (stub)
 *    preg_filter
 *    lcfirst
 *    class_alias
 *    header_remove
 *    parse_ini_string
 *    array_replace
 *    array_replace_recursive
 *    str_getcsv
 *    forward_static_call
 *    forward_static_call_array
 *    quoted_printable_encode
 *
 * @missing
 *    get_called_class
 *    stream_context_get_params
 *    stream_context_set_default
 *    stream_supports_lock
 *    hash_copy
 *    date_create_from_format
 *    date_parse_from_format
 *    date_get_last_errors
 *    date_add
 *    date_sub
 *    date_diff
 *    date_timestamp_set
 *    date_timestamp_get
 *    timezone_location_get
 *    date_interval_create_from_date_string
 *    date_interval_format
 *
 * RANT: The PHP 5.3 \idiot\namespace\syntax (magic quotes 2.0) is not
 * reimplemented here.
 *
 */



/**
 * preg_replace() variant, which filters out any unmatched $subject.
 *
 */
if (!function_exists("preg_filter")) {
   function preg_filter($pattern, $replacement, $subject, $limit=-1, $count=NULL) {

      // just do the replacing first, and eventually filter later
      $r = preg_replace($pattern, $replacement, $subject, $limit, $count);

      // look at subject lines one-by-one, remove from result per index
      foreach ((array)$subject as $si=>$s) {
         $any = 0;
         foreach ((array)$pattern as $p) {
            $any = $any ||preg_match($p, $s);
         }
         // remove if NONE of the patterns matched
         if (!$any) {
            if (is_array($r)) {
               unset($r[$si]);  // del from result array
            }
            else {
               return NULL;  // subject was a str
            }
         }
      }

      return $r;    // is already string if $subject was too
   }
}



/**
 * Lowercase first character.
 *
 * @param string
 * @return string
 */
if (!function_exists("lcfirst")) {
   function lcfirst($str) {
      return strlen($str) ? strtolower($str[0]) . substr($str, 1) : "";
   }
}



/**
 * @stub  cannot be emulated, because output buffering functions
 *        already swallow up any sent http header
 * @since 5.3.?
 *
 * get all ob_ soaked headers(),
 *
 */
if (!function_exists("ob_get_headers")) {
   function ob_get_headers() {
      return (array)NULL;
   }
}



/**
 * @stub  Cannot be emulated correctly, but let's try.
 *
 */
if (!function_exists("header_remove")) {
   function header_remove($name="") {
      if (strlen($name) and ($name = preg_replace("/[^-_.\w\d]+/", "", $name))) header("$name: \t");
      // Apache1.3? removed duplettes, empty header overrides previous.
      // ONLY if case was identical to previous header() call. (Very uncertain for applications which need to resort to such code smell.)
   }
}



/**
 * WTF?
 * At least an explaning reference was available on the php.net manual.
 * Why the parameters are supposed to be optional is a mystery.
 *
 */
if (!function_exists("class_alias")) {
   function class_alias($original, $alias) {
      $abstract = "";
      if (class_exists("ReflectionClass")) {
         $oc = new ReflectionClass($original);
         $abstract = $oc->isAbstract() ? "abstract" : "";
      }
      eval("$abstract class $alias extends $original { /* identical subclass */ }");
      return get_parent_class($alias) == $original;
   }
}




/**
 * Hey, reimplementin is fun.
 * (Could have used a data: wrapper for parse_ini_file, but that wouldn't work for php<5.2, and the data:// (!) wrapper is flaky anyway.)
 *
 */
if (!function_exists("parse_ini_string")) {
   function parse_ini_string($ini, $sectioned=false, $raw=0) {
      $r = array();
      $map = array("true"=>1, "yes"=>1, "1"=>1, "null"=>"", "false"=>"", "no"=>"", "0"=>0);
      $section = "";
      foreach (explode("\n", $ini) as $line) {
         if (!strlen($line)) {
         }
         // handle [sections]
         elseif (($line[0] == "[") and preg_match("/\[([-_\w ]+)\]/", $line, $uu)) {
            $section = $uu[1];
         }
         elseif (/*deprecated*/($line[0] != "#") && ($line[0] != ";") && ($i = strpos($line, "="))) {
            // key=value split
            $n = trim(substr($line, 0, $i));
            $v = trim(substr($line, $i+1));
            // replace special values
            if (!$raw) {
               $v=trim($v, '"');   // should actually use regex, to handle key="..\n.." multiline values
               $v=trim($v, "'");
               if (isset($map[$v])) {
                  $v=$map[$v];
               }
            }
            // special array[]= keys allowed
            if ($i = strpos($n, "[")) {
               $r[$section][substr($n, 0, $i)][] = $v;
            }
            else {
               $r[$section][$n] = $v;
            }
         }
      }
      return $sectioned ? $r : call_user_func_array("array_merge", $r);
   }
}




/**
 * Inject values from supplemental arrays into $target, according to its keys.
 *
 * @param array  $targt
 * @param+ array $supplements
 * @return array
 */
if (!function_exists("array_replace")) {
   function array_replace(/* & (?) */$target/*, $from, $from2, ...*/) {
      $merge = func_get_args();
      array_shift($merge);
      foreach ($merge as $add) {
         foreach ($add as $i=>$v) {
            $target[$i] = $v;
         }
      }
      return $target;
   }
}


/**
 * Descends into sub-arrays when replacing values by key in $target array.
 *
 */
if (!function_exists("array_replace_recursive")) {
   function array_replace_recursive($target/*, $from1, $from2, ...*/) {
      $merge = func_get_args();
      array_shift($merge);

      // loop through all merge arrays
      foreach ($merge as $from) {
         foreach ($from as $i=>$v) {
            // just add (wether array or scalar) if key does not exist yet
            if (!isset($target[$i])) {
               $target[$i] = $v;
            }
            // dive in
            elseif (is_array($v) && is_array($target[$i])) {
               $target[$i] = array_replace_recursive($target[$i], $v);
            }
            // replace
            else {
               $target[$i] = $v;
            }
         }
      }
      return $target;
   }
}




/**
 * Breaks up a SINGLE LINE in CSV format.
 * abc,123,"text with spaces and \n ewlines",xy,"\""
 *
 */
if (!function_exists("str_getcsv")) {
   function str_getcsv($line, $del=",", $q='"', $esc="\\") {
      $line = rtrim($line);
      preg_match_all("/\G ([^$q$del]*) $del | $q(( [$esc$esc][$q]|[^$q]* )+)$q $del /xms", "$line,", $r);
      foreach ($r[1] as $i=>$v) {  // merge both captures
         if (empty($v) && strlen($r[2][$i])) {
            $r[1][$i] = str_replace("$esc$q", "$q", $r[2][$i]);  // remove escape character
         }
      }
      return($r[1]);
   }
}



/**
 * @stub: Basically aliases for function calls; just throw an error if called from main() and not from within a class.
 * The real implementations would behave on late static binding, though.
 *
 */
if (!function_exists("forward_static_call")) {
   function forward_static_call_array($callback, $args=NULL) {
      return call_user_func_array($callback, $args);
   }
   function forward_static_call($callback /*, ... */) {
      $args = func_get_args();
      array_shift($args);
      return call_user_func_array($callback, $args);
   }
}




/**
 * Encodes special chars as =0D=0A patterns. Soft-break at 76 characters.
 *
 */
if (!function_exists("quoted_printable_encode")) {
   function quoted_printable_encode($str) {
      $str = preg_replace("/([\\000-\\041=\\176-\\377])/e", "'='.strtoupper(dechex(ord('\$1')))", $str);
      $str = preg_replace("/(.{1,76})(?<=[^=][^=])/ims", "\$1=\r\n", $str); // QP-soft-break
      return $str;
   }
}
