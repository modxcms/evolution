<?php
/**
 *
 *  Emulates mathematical functions with arbitrary precision (bcmath)
 *  using POSIX systems 'dc' utility. Cannot work in PHP 'safe mode'.
 * 
 *  @requires realmode
 *
 */


#-- BSD/Linux dc(1)
if (!function_exists("bcadd") && is_executable("/usr/bin/dc")) {

   #-- invokes commandline 'dc' utility (faster than with 'bc')
   #   (later version should use proc_open() for safer and faster bi-directional I/O)
   function dc___exec($calc, $scale=NULL) {
      static $saved_scale=0;
      
      #-- bcscale() set
      if ($calc=="SETSCALE") {
         $saved_scale = (int)$scale;
         return;
      }
      elseif (!isset($scale)) {
         $scale = $saved_scale;
      }

      #-- assemble dc expression
      $calc = str_replace(' -', ' _', $calc);  // convert minus signs for dc
      $calc = $scale . "k" . $calc;    // inject precision directive
      $calc = escapeshellarg($calc);   // could contain non-integers from elsewhere
      
      #-- prevent any command execution from within dc
      #   (for speed reasons we don't assert parameters to be fully numeric)
      if (strpos($calc, "!")) { return; }

      #-- do
      return str_replace("\\\n", "", `/usr/bin/dc -e $calc`);
   }

   #-- global state variable
   function bcscale($scale=0) {
      //ini_get("bcmath.scale");  // =0
      dc___exec("SETSCALE", $scale);
   }

   #-- wrapper calls
   function bcadd($a, $b, $scale=NULL) {
      return dc___exec(" $a $b +nq", $scale);
   }
   function bcsub($a, $b, $scale=NULL) {
      return dc___exec(" $a $b-nq", $scale);  // no space before '-' cmd!
   }
   function bcmul($a, $b, $scale=NULL) {
      return dc___exec(" $a $b *nq", $scale);
   }
   function bcdiv($a, $b, $scale=NULL) {
      return dc___exec(" $a $b /nq", $scale);
   }
   function bcmod($a, $b, $scale=0) {
      return dc___exec(" $a $b %nq", $scale);
   }
   function bcpow($a, $b, $scale=NULL) {
      return dc___exec(" $a $b ^nq", $scale);
   }
   function bcpowmod($x, $y, $mod, $scale=0) {
      return dc___exec(" $x $y $mod |nq", $scale);  // bc(1) wouldn't work
   }
   function bcsqrt($x, $scale=NULL) {
      return dc___exec(" $x vnq", $scale);
   }

   #-- looks slightly more complicated in dc notation
   function bccomp($a, $b, $scale=NULL) {
      bc___scaledown($a, $scale);
      bc___scaledown($b, $scale);
      return (int) dc_exec(" $a 1*sA $b 1*sB  lBlA[1nq]sX>X lBlA[_1nq]sX<X 0nq", $scale);
   }
   function bc___scaledown(&$a, $scale) {
      if (isset($scale) && ($dot = strpos($a, $dot))) {
         $a = substr($a, $dot + $scale) . "0";
      }
   }

}


?>