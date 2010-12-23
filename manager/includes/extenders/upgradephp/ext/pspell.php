<?php
/**
 * api: php
 * title: pspell emulation
 * description: aspell wrapper functions for PHP pspell extension
 * type: functions
 * support: untested
 * config: <var name="$__pspell[]" priority="never" description="helper variable">
 * version: 1.1
 * license: Public Domain
 * 
 * Has no support for replacement and personal dictionaries, nor wordlist files.
 * (Some of these features are possible with the aspell cmdline arguments eventually.)
 *
 * Only works with aspell binary, not ispell.
 *
 */


 
if (!function_exists("pspell_check")) {

   // helper variable, holds indexes to configured dictionaries
   global $__pspell;
   $__pspell[0] = array(
      // array names do match aspell --cmdline options 1:1
      "lang" => "en",
      "variety" => "",
      "jargon" => "",
      "encoding" => "utf-8",
      "ignore" => 1,
      "sug-mode" => "normal",
      // pspell_*() emu internal options prefixed with _
      "_mode" => 0,
      "_bin" => trim(`which aspell`),
      "_insert" => "",
   );

   // ??   
   define("PSPELL_FAST", 0x01);
   define("PSPELL_NORMAL", 0x02);
   define("PSPELL_BAD_SPELLERS", 0x04);
   define("PSPELL_RUN_TOGETHER", 0x08);
   define("PSPELL_ULTRA", 0x20);  // non-standard
   

   /**
    * Check if a single word exists in the dictionary as-is.
    *
    */
   function pspell_check($i, $word) {

      // exec
      $cmd = pspell_cmd($i, "check --dont-suggest");
      $word = escapeshellarg($word);
      $r = `echo $word | $cmd`;
      
      // "*" means successful match
      return preg_match("/^[\*]/m", $r);
   }


   /**
    * non-standard, for emulation only
    *
    */
   function pspell_cmd($i, $insert="") {
      global $__pspell;
      
      # /usr/bin/aspell pipe check
      $cmd = $__pspell[$i]["_bin"] . " pipe $insert " . $__pspell[$i]["_insert"];

      # --lang= --ignore= --variety= --jargon= --personal= --repl= --extra-dicts= --dict-dir=
      foreach ($__pspell[$i] as $name=>$value) {
         if (strlen($value) && !strstr(",_bin,_mode",$name) && ($name[0]!="_")) {
            $cmd .= " --$name=$value";
         }
      }
      return $cmd;
   }   
   
   
   /**
    * If word does not exist in dictionary, returns list of alternatives.
    *
    */
   function pspell_suggest($i, $word) {
      
      // exec
      $cmd = pspell_cmd($i, "--suggest");
      $word = escapeshellarg($word);
      $r = `echo $word | $cmd`;
      
      // "&" multiple matches
      if (preg_match("/^[\&] (.+?) (\d+) (\d+): (.+)$/m", $r, $uu)) {
         return preg_split("/,\s*/", $uu[4]);
      }
      else {
         //return($word);    //@todo: native behaviour?
      }
   }


   /**
    * Set aspell options.
    *
    */
   function pspell_new($lang="en", $spelling="", $jargon="", $enc="utf-8", $mode=0) {
      global $__pspell;
      $i = count($__pspell);
      $__pspell[$i] = array_merge($__pspell[0], array(
         "lang" => $lang,
         "variety" => $spelling,
         "jargon" => $jargon,
         "encoding" => $enc,
      ));
      if ($mode) { 
         pspell_config_mode($i, $mode);
      }
      return($i);
   }


   /**
    * Various other dictionary options.
    * Just set $__pspell[][] cmdline --options array.
    *
    */
   function pspell_config_create($lang, $spelling=NULL, $jargon=NULL, $enc=NULL) {
      return pspell_new($lang, $spelling, $jargon, $enc);
   }
   function pspell_new_config($i) {
      return $i;  // dictionary and config are the same in this implementation
   }
   function pspell_config_mode($i, $mode) {
      global $__pspell;
      $__pspell[$i]["_mode"] = $mode;
      $modes = array(0x00=>"normal", PSPELL_NORMAL=>"normal", PSPELL_FAST=>"fast", PSPELL_ULTRA=>"ultra", PSPELL_BAD_SPELLERS=>"bad-spellers");
      $__pspell[$i]["sug-mode"] = $modes[$mode & 0x27];
      pspell_config_runtogether($i, $mode & PSPELL_RUN_TOGETHER);
   }
   function pspell_config_ignore($i, $minlength) {
      global $__pspell;
      $__pspell[$i]["ignore"] = (int)$minlength;
   }
   function pspell_config_personal($i, $file) {
      global $__pspell;
      $__pspell[$i]["personal"] = escapeshellarg($file);
   }
   function pspell_config_data_dir($i, $dir) {
      global $__pspell;
      $__pspell[$i]["data-dir"] = escapeshellarg($dir);
   }
   function pspell_config_dict_dir($i, $dir) {
      global $__pspell;
      $__pspell[$i]["dict-dir"] = escapeshellarg($dir);
   }
   function pspell_config_runtogether($i, $is) {
      global $__pspell;
      $__pspell[$i]["_insert"] = $is ? "--runtogether" : "";
   }

   
}


?>