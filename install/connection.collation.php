<?php

$host = $_POST['host'];
$uid = $_POST['uid'];
$pwd = $_POST['pwd'];

if ($conn = mysqli_connect($host, $uid, $pwd)) {
    // get collation
    $rs = mysqli_query($conn, "SHOW COLLATION");
    if (mysqli_num_rows($rs) > 0) {
        $output = '<select id="database_collation" name="database_collation">';
        $_ = array();
        while ($row = mysqli_fetch_row($rs)) {
            $_[$row[0]] = '';
        }
        
        $database_collation = htmlentities($_POST['database_collation']);
        if    (isset($_['utf8mb4_general_ci'])) $_['utf8mb4_general_ci'] = ' selected';
        elseif(isset($_['utf8_general_ci']))    $_['utf8_general_ci']    = ' selected';
        elseif(isset($_[$database_collation]))  $_[$database_collation]  = ' selected';
        
        $_ = sortItem($_,'utf8mb4,utf8,latin1');
        $order = 'utf8mb4_general_ci,utf8_general_ci,utf8mb4_unicode_ci,utf8_unicode_ci,utf8mb4_bin,utf8_bin,german,danish';
        $_ = sortItem($_,$order);
        
        foreach($_ as $collation=>$selected) {
            $collation = htmlentities($collation);
            // if(substr($collation,0,4)!=='utf8') continue;
            if(strpos($collation,'sjis')===0) continue;
            $output .= '<option value="'.$collation.'"'.$selected.'>'.$collation.'</option>';
        }
        $output .= '</select>';
    }
}
echo $output;

function sortItem($array=array(),$order='utf8mb4,utf8') {
    $rs = array();
    $order = explode(',', $order);
    foreach($order as $v) {
    	foreach($array as $name=>$sel) {
        	if(strpos($name,$v)!==false) {
        		$rs[$name] = $array[$name];
        		unset($array[$name]);
        	}
    	}
    }
    return $rs + $array;
}
