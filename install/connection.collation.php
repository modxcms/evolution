<?php

$host = $_POST['host'];
$uid = $_POST['uid'];
$pwd = $_POST['pwd'];

$self = 'install/connection.collation.php';
$base_path = str_replace($self, '', str_replace('\\', '/', __FILE__));
if (is_file("{$base_path}assets/cache/siteManager.php")) {
    include_once("{$base_path}assets/cache/siteManager.php");
}
if (!defined('MGR_DIR') && is_dir("{$base_path}manager")) {
    define('MGR_DIR', 'manager');
}
require_once('lang.php');

if (function_exists('mysqli_connect')) {
    $h = explode(':', $host, 2);
    $conn = mysqli_connect($h[0], $uid, $pwd,'', isset($h[1]) ? $h[1] : null);
    if (!$conn) {
        exit('can not connect');
    }
} else {
    exit('undefined function mysqli_connect()');
}

// get collation
$rs = mysqli_query($conn, "SHOW COLLATION");
if (mysqli_num_rows($rs) > 0) {
    $output = '<select id="database_collation" name="database_collation">';
    $_ = array();
    while ($row = mysqli_fetch_row($rs)) {
        $_[$row[0]] = '';
    }

    $database_collation = isset($_POST['database_collation']) ? htmlentities($_POST['database_collation']) : '';
    $recommend_collation = $_lang['recommend_collation'];

    if (isset($_[$recommend_collation])) {
        $_[$recommend_collation] = ' selected';
    } elseif (isset($_['utf8mb4_general_ci'])) {
        $_['utf8mb4_general_ci'] = ' selected';
    } elseif (isset($_['utf8_general_ci'])) {
        $_['utf8_general_ci']    = ' selected';
    } elseif (!empty($database_collation) && isset($_[$database_collation])) {
        $_[$database_collation]  = ' selected';
    }

    $_ = sortItem($_, $_lang['recommend_collations_order']);

    foreach ($_ as $collation=>$selected) {
        $collation = htmlentities($collation);
        // if(substr($collation,0,4)!=='utf8') continue;
        if (strpos($collation, 'sjis')===0) {
            continue;
        }
        if ($collation=='recommend') {
            $output .= '<optgroup label="recommend">';
        } elseif ($collation=='unrecommend') {
            $output .= '</optgroup><optgroup label="unrecommend">';
        } else {
            $output .= sprintf('<option value="%s" %s>%s</option>', $collation, $selected, $collation);
        }
    }
    $output .= '</optgroup></select>';
}

echo $output;
exit;

function sortItem($array=array(), $order='utf8mb4,utf8')
{
    $rs = array('recommend'=>'');
    $order = explode(',', $order);
    foreach ($order as $v) {
        foreach ($array as $name=>$sel) {
            if (strpos($name, $v)!==false) {
                $rs[$name] = $array[$name];
                unset($array[$name]);
            }
        }
    }
    $rs['unrecommend']='';
    return $rs + $array;
}
