<?php

// this simulates magic_quotes_gpc = 0...
// Hope it works!
function & kill_magic_quotes(&$str) {
   if(is_array($str)) {
       while(list($key, $val) = each($str)) {
           $str[$key] = kill_magic_quotes($val); // this basically loops into arrays...
       }
   } else {
       $str = stripslashes($str); // get rid of those slashes!
   }   
   return $str;
}

if(get_magic_quotes_gpc()) {
   kill_magic_quotes($_GET);
   kill_magic_quotes($_POST);
   kill_magic_quotes($_COOKIE);
   kill_magic_quotes($_REQUEST);
}

?>