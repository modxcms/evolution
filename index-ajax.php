<?php
// harden it
require_once('./manager/includes/protect.inc.php');

// initialize the variables prior to grabbing the config file
$database_type = "";
$database_server = "";
$database_user = "";
$database_password = "";
$dbase = "";
$table_prefix = "";
$base_url = "";
$base_path = "";

// get the required includes
if($database_user=='') {
        if (!$rt = @include_once "manager/includes/config.inc.php") {
           exit('Could not load MODx configuration file!');
        }
}

if($axhandler = (strtoupper($_SERVER['REQUEST_METHOD'])=='GET') ? $_GET['q'] : $_POST['q']) {
  $axhandler = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $axhandler);
  $axhandler = realpath($axhandler) or die(); 
  $directory = realpath(MODX_BASE_PATH.DIRECTORY_SEPARATOR.'/assets/snippets'); 
  $axhandler = realpath($directory.str_replace($directory, '', $axhandler));
  
  if($axhandler && (strtolower(substr($axhandler,-4))=='.php')) {
    include_once($axhandler);
    exit;
  }
}
?>