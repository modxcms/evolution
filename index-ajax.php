<?php
// secure variables from outside
// added 03-05-06
foreach(array('HTTP_REFERER','HTTP_USER_AGENT') as $outside) {
  $_SERVER[$outside] = isset($_SERVER[$outside]) ? preg_replace("/[^A-Za-z0-9_\-\,\.\/\s]/", "", $_SERVER[$outside]): '';
  if(strlen($_SERVER[$outside])>255) $_SERVER[$outside] = substr(0,255,$_SERVER[$outside]);
}

// Never allow request via get and post contain snippets, javascript or php
$modxtags = array('@<script[^>]*?>.*?</script>@si',
                  '@&#(\d+);@e',
                  '@\[\[(.*?)\]\]@si',
                  '@\[!(.*?)!\]@si',
                  '@\[\~(.*?)\~\]@si',
                  '@\[\((.*?)\)\]@si',
                  '@{{(.*?)}}@si',
                  '@\[\*(.*?)\*\]@si');
foreach($_POST as $key => $value) {
  $_POST[$key] = preg_replace($modxtags,"", $value);
}
foreach($_GET as $key => $value) {
  $_GET[$key] = preg_replace($modxtags,"", $value);
}

$database_type = "";
$database_server = "";
$database_user = "";
$database_password = "";
$dbase = "";
$table_prefix = "";
$base_url = "";
$base_path = "";

// get the required includes
if($database_user=="") {
        if (!$rt = @include_once "manager/includes/config.inc.php") {
           exit('Could not load MODx configuration file!');
        }
}

if(isset($_REQUEST['q'])) {
  $axhandler = preg_replace("/[^A-Za-z0-9_\-\.\/]/", "", $_REQUEST['q']);
  $axhandler = str_replace(array('..','assets','manager','cache','files','export','media','templates'),'',$axhandler);
  if (!@include_once('./assets' . $axhandler)) {
     exit('Could not load requested ajax handler: ' . $_REQUEST['q']);
  }
}

?>
