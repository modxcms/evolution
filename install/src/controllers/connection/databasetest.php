<?php

$host = $_POST['host'];
$uid = $_POST['uid'];
$pwd = $_POST['pwd'];
$installMode = $_POST['installMode'];

$output = $_lang['status_checking_database'];
$h = explode(':', $host, 2);
$database_collation = $_POST['database_collation'];
$database_connection_method = $_POST['database_connection_method'];
$database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
$tableprefix = $_POST['tableprefix'];
if ($_POST['method'] == 'pgsql') {
    if ($database_charset == 'utf8mb4') $database_charset = 'utf8';
    $database_charset = mb_strtoupper($database_charset);
}
try {
    $dbh = new PDO($_POST['method'] . ':host=' . $_POST['host'] . ';dbname=' . $_POST['database_name'], $_POST['uid'], $_POST['pwd']);
    switch ($_POST['method']) {
        case 'pgsql':

            $result = $dbh->query("SELECT * FROM pg_settings WHERE name='client_encoding'");
            if ($result->errorCode() == 0) {
                $data = $result->fetch();
                if ($data['setting'] != $database_charset) {
                    echo $output . '<span id="database_fail" style="color:#FF0000;">' . sprintf($_lang['status_failed_database_collation_does_not_match'], $data['setting']) . '</span>';
                    exit();
                }
                $result = $dbh->query("SELECT COUNT(*) FROM {$tableprefix}site_content");

                if ($dbh->errorCode() == 0) {
                    echo $output . '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed_table_prefix_already_in_use'] . '</span>';
                    exit();
                }
            } else {
                echo $output . '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed'] . ' ' . print_r($result->errorInfo(), true) . '</span>';
                exit();
            }
            break;
        case 'mysql':
            $result = $dbh->query("show variables like 'collation_database'");
            if ($result->errorCode() == 0) {
                $data = $result->fetch();

                if ($data['Value'] != $database_collation) {


                    echo $output . '<span id="database_fail" style="color:#FF0000;">' . sprintf($_lang['status_failed_database_collation_does_not_match'], $data['1']) . '</span>';
                    exit();
                }
                $result = $dbh->query("SELECT COUNT(*) FROM {$tableprefix}site_content");
                if ($dbh->errorCode() == 0) {
                    echo $output . '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed_table_prefix_already_in_use'] . '</span>';
                    exit();
                }

            } else {

                echo $output . '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed'] . ' ' . print_r($result->errorInfo(), true) . '</span>';
                exit();
            }
            break;
    }
    echo $output . '<span id="database_pass" style="color:#80c000;"> ' . $_lang['status_passed'] . '</span>';
    exit();
} catch (PDOException $e) {
    if (!stristr($e->getMessage(), 'database "' . $_POST['database_name'] . '" does not exist') && !stristr($e->getMessage(), 'Unknown database \'' . $_POST['database_name'] . '\'')) {
        echo $output . '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed'] . ' ' . $e->getMessage() . '</span>';
        exit();
    }
}

try {
    $dbh = new PDO($_POST['method'] . ':host=' . $_POST['host'] . ';', $_POST['uid'], $_POST['pwd']);


    switch ($_POST['method']) {
        case 'pgsql':

            try {
                $dbh->query('CREATE DATABASE "' . $_POST['database_name'] . '" ENCODING \'' . $database_charset . '\';');
                if ($dbh->errorCode() > 0) {
                    $output .= '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed_could_not_create_database'] . ' ' . print_r($dbh->errorInfo(), true) . '</span>';
                }

            } catch (Exception $exception) {
                echo $exception->getMessage();
            }

            break;
        case 'mysql':
            $query = 'CREATE DATABASE `' . $_POST['database_name'] . '` CHARACTER SET ' . $database_charset . ' COLLATE ' . $database_collation . ";";
            if (!$dbh->query($query)) {
                $output .= '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed_could_not_create_database'] . '</span>';
                echo $output;
                exit();
            } else {
                $output .= '<span id="database_pass" style="color:#80c000;">' . $_lang['status_passed_database_created'] . '</span>';
                echo $output;
                exit();
            }
            break;
    }

    echo $output . '<span id="database_pass" style="color:#80c000;"> ' . $_lang['status_passed'] . '</span>';
    exit();
} catch (PDOException $e) {

    echo $output . '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed'] . ' ' . $e->getMessage() . '</span>';

}
/*
if (!$conn = mysqli_connect($h[0], $uid, $pwd,'', isset($h[1]) ? $h[1] : null)) {
    $output .= '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed'] . '</span>';
} else {
    $database_name = mysqli_real_escape_string($conn, $_POST['database_name']);
    $database_name = str_replace('`', '', $database_name);
    $tableprefix = mysqli_real_escape_string($conn, $_POST['tableprefix']);
    $database_collation = mysqli_real_escape_string($conn, $_POST['database_collation']);
    $database_connection_method = mysqli_real_escape_string($conn, $_POST['database_connection_method']);

    if (!@ mysqli_select_db($conn, $database_name)) {
        // create database
        $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
        $query = 'CREATE DATABASE `' . $database_name . '` CHARACTER SET ' . $database_charset . ' COLLATE ' . $database_collation . ";";

        if (!mysqli_query($conn, $query)) {
            $output .= '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed_could_not_create_database'] . '</span>';
        } else {
            $output .= '<span id="database_pass" style="color:#80c000;">' . $_lang['status_passed_database_created'] . '</span>';
        }
    } elseif (($installMode == 0) && (mysqli_query($conn,
            "SELECT COUNT(*) FROM {$database_name}.`{$tableprefix}site_content`"))) {
        $output .= '<span id="database_fail" style="color:#FF0000;">' . $_lang['status_failed_table_prefix_already_in_use'] . '</span>';
    } elseif (($database_connection_method != 'SET NAMES') && ($rs = mysqli_query($conn,
            "show variables like 'collation_database'")) && ($row = mysqli_fetch_row($rs)) && ($row[1] != $database_collation)) {
        $output .= '<span id="database_fail" style="color:#FF0000;">' . sprintf($_lang['status_failed_database_collation_does_not_match'],
                $row[1]) . '</span>';
    } else {
        $output .= '<span id="database_pass" style="color:#80c000;">' . $_lang['status_passed'] . '</span>';
    }
}*/

echo $output;
