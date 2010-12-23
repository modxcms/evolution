<?php
/*
   Following funtions have been removed from the core emulation script, because
   they are considered too special to be commonly used in WWW scripts. Anybody
   using these, probably takes extra precautions prior calling them (you could
   still load this script). Some of these functions could also be too difficult
   to be reimplemented 100% exactly.
*/



#-- calls PHP interpreter itself (really only works with 4.3 onwards)
#   (you should use the PHP_Compat implementation of this preferably)
if (!function_exists("php_strip_whitespace")) {
   function php_strip_whitespace($fn) {
      // alternatives would be using te tokenizer or
      // some regexs to strip unwanted content parts
      // (PEAR::PHP_Compat simply calls the tokenizer)
      $fn = escapeshellcmd($fn);
      $text = `php -wqCf '$fn'`;
      if (!$text) {
         $text = implode("", file($fn));
      }
      return $text;
   }
}


#-- invocates PHP interpreter to do the syntax check (nothing else can do)
#   (you should use the PHP_Compat implementation of this preferably)
if (!function_exists("php_check_syntax")) {
   function php_check_syntax($fn) {
      $args = func_get_args();
      if (count($args)>1) {
         $result = & $args[1];
      }
      $fn = escapeshellcmd($fn);
      $result = system("php -lqCf '$fn'", $err);
      return($err==0);
   }
}


#-- print enumerated list of last-called functions
if (!function_exists("debug_print_backtrace") && function_exists("debug_backtrace")) {
   function debug_print_backtrace() {
      $d = debug_backtrace();
      foreach ($d as $i=>$info) {
         #-- index
         echo "#" . ($i) . "  ";
         
         #-- function name
         if (isset($info["class"])) {
            echo "$info[class]::";
         }
         if (isset($info["object"])) {
            echo "\$$info[object]->";
         }
         echo "$info[function]";
         
         #-- args
         echo "(";
         foreach ($info["args"] as $a) {
            echo str_replace("\n", "", var_export($a, 1)) . ", ";
         }
         echo ")";
         
         #-- caller
         echo " called at [";
         if ($info["file"]) {
            echo $info["file"] . ":" . $info["line"];
         }
         else {
            echo "unknown_location";
         }
         echo "]\n";
      }
   }
}


?>