<?php
/**
 * session_keepalive.php
 *
 * This page is requested once in awhile to keep the session alive and kicking.
 */
$database_type = "";
$database_server = "";
$database_user = "";
$database_password = "";
$dbase = "";
$table_prefix = "";
$base_url = "";
$base_path = "";

// get the required includes
if ($database_user == "") {
    if ($rt = @ include_once "config.inc.php") {
        // Keep it alive
        startCMSSession();
        
        header('Location: ../media/script/_session.gif?rnd=' . $_REQUEST['rnd']);
    }
}