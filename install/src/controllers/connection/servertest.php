<?php


$output = $_lang['status_connecting'];
try {
    $dbh = new PDO($_POST['method'] . ':host=' . $_POST['host'] . ';', $_POST['uid'], $_POST['pwd']);
    $output .= '<span id="server_pass" style="color:#80c000;"> ' . $_lang['status_passed_server'] . '</span>';
} catch (PDOException $e) {
    $output .= '<span id="server_fail" style="color:#FF0000;"> ' . $_lang['status_failed'] . ' ' . $e->getMessage() . '</span>';

}
echo $output;
