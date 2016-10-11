<?php

$host = $_POST['host'];
$uid = $_POST['uid'];
$pwd = $_POST['pwd'];
$database_collation = htmlentities($_POST['database_collation']);

if ($conn = mysqli_connect($host, $uid, $pwd)) {
    // get collation
    $rs = mysqli_query($conn, "SHOW COLLATION");
    if (mysqli_num_rows($rs) > 0) {
        $output = '<select id="database_collation" name="database_collation">';
        $_ = array();
        while ($row = mysqli_fetch_row($rs)) {
            $selected = ( $row[0]==$database_collation ? ' selected' : '' );
            $_[$row[0]] = $selected;
        }
        $_ = sortItem($_);
        foreach($_ as $collation=>$selected) {
            $collation = htmlentities($collation);
            if(substr($collation,0,4)!=='utf8') continue;
            $output .= '<option value="'.$collation.'"'.$selected.'>'.$collation.'</option>';
        }
        $output .= '</select>';
    }
}
echo $output;

function sortItem($array=array()) {
    $rs = array();
    $order = explode(',', 'utf8mb4_general_ci,utf8_general_ci,utf8mb4_unicode_ci,utf8_unicode_ci,utf8mb4_bin,utf8_bin');
    foreach($order as $v ) {
    	if(isset($array[$v])) {
    		$rs[$v] = $array[$v];
    		unset($array[$v]);
    	}
    }
    return $rs + $array;
}
