<?php

$host = $_POST['host'];
$uid = $_POST['uid'];
$pwd = $_POST['pwd'];

require_once("lang.php");

$output = $_lang["status_checking_database"];
if (!$conn = @ mysql_connect($host, $uid, $pwd)) {
    $output .= '<span style="color:#FF0000;">'.$_lang['status_failed'].'</span>';
}
else {
    $database_name = $_POST['database_name'];
    $tableprefix = $_POST['tableprefix'];
    $database_collation = $_POST['database_collation'];

    if (!@ mysql_select_db(str_replace("`", "", $database_name, $conn))) {
        // create database
        $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
        $query = "CREATE DATABASE ".$database_name." CHARACTER SET ".$database_charset." COLLATE ".$database_collation.";";

        if (!@ mysql_query($query)){
            $output .= '<span style="color:#FF0000;">'.$_lang['status_failed_could_not_create_database'].'</span>';
        }
        else {
            $output .= '<span style="color:#9CCD00;">'.$_lang['status_passed_database_created'].'</span>';
        }
    }
    elseif (@ mysql_query("SELECT COUNT(*) FROM {$database_name}.`{$tableprefix}site_content`")) {
        $output .= '<span style="color:#FF0000;">'.$_lang['status_failed_table_prefix_already_in_use'].'</span>';
    }
    else {
        $output .= '<span style="color:#9CCD00;">'.$_lang['status_passed'].'</span>';
    }
}

echo $output;
?>