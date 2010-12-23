<?php
/*
   These functions emulate the "character type" extension, which is
   present in PHP first since version 4.3 per default. In this variant
   only ASCII and Latin-1 characters are being handled. The first part
   is eventually faster.
*/


#-- regex variants
if (!function_exists("ctype_alnum")) {
   function ctype_alnum($text) {
      return preg_match("/^[A-Za-z\d\300-\377]+$/", $text);
   }
   function ctype_alpha($text) {
      return preg_match("/^[a-zA-Z\300-\377]+$/", $text);
   }
   function ctype_digit($text) {
      return preg_match("/^\d+$/", $text);
   }
   function ctype_xdigit($text) {
      return preg_match("/^[a-fA-F0-9]+$/", $text);
   }
   function ctype_cntrl($text) {
      return preg_match("/^[\000-\037]+$/", $text);
   }
   function ctype_space($text) {
      return preg_match("/^\s+$/", $text);
   }
   function ctype_upper($text) {
      return preg_match("/^[A-Z\300-\337]+$/", $text);
   }
   function ctype_lower($text) {
      return preg_match("/^[a-z\340-\377]+$/", $text);
   }
   function ctype_graph($text) {
      return preg_match("/^[\041-\176\241-\377]+$/", $text);
   }
   function ctype_punct($text) {
      return preg_match("/^[^0-9A-Za-z\000-\040\177-\240\300-\377]+$/", $text);
   }
   function ctype_print($text) {
      return ctype_punct($text) && ctype_graph($text);
   }

}

?>