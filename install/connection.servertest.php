<?php

$host = $_POST['host'];
$uid = $_POST['uid'];
$pwd = $_POST['pwd'];

$self = 'install/connection.servertest.php';
$base_path = str_replace($self, '', str_replace('\\', '/', __FILE__));
if (is_file("{$base_path}assets/cache/siteManager.php")) {
    include_once("{$base_path}assets/cache/siteManager.php");
}
if (!defined('MGR_DIR') && is_dir("{$base_path}manager")) {
    define('MGR_DIR', 'manager');
}
require_once("lang.php");

$output = $_lang["status_connecting"];
if (function_exists('mysqli_connect')) {
    $h = explode(':', $host, 2);
    if (!$conn = @mysqli_connect($h[0], $uid, $pwd,'', isset($h[1]) ? $h[1] : null)) {
        $output .= '<span id="server_fail" style="color:#FF0000;"> '.$_lang['status_failed'].'</span>';
    } else {
        $output .= '<span id="server_pass" style="color:#80c000;"> '.$_lang['status_passed_server'].'</span>';

        // Mysql version check
        if (version_compare(mysqli_get_server_info($conn), '5.0.51', '=')) {
            $output .= '<br /><span style="color:#FF0000;"> '.$_lang['mysql_5051'].'</span>';
        }
        // Mode check
        $mysqlmode = mysqli_query($conn, "SELECT @@session.sql_mode");
        if (@mysqli_num_rows($mysqlmode) > 0) {
            $modes = mysqli_fetch_array($mysqlmode, MYSQLI_NUM);
            $strictMode = false;
            foreach ($modes as $mode) {
                if (stristr($mode, "STRICT_TRANS_TABLES") !== false || stristr($mode, "STRICT_ALL_TABLES") !== false) {
                    $strictMode = true;
                }
            }
            if ($strictMode) {
                $output .= '<br /><span style="color:#FF0000;"> '.$_lang['strict_mode'].'</span>';
            }
        }
    }
} else {
    $output .= '<span id="server_fail" style="color:#FF0000;"> '.$_lang['status_failed_mysqli'].'</span>';
}
echo $output;
