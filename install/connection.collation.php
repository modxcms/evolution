<?php

$host = $_POST['host'];
$uid = $_POST['uid'];
$pwd = $_POST['pwd'];
$database_collation = htmlentities($_POST['database_collation']);

$output = '<select id="database_collation" name="database_collation">
<option value="'.$database_collation.'" selected >'.$database_collation.'</option></select>';

if ($conn = @ mysql_connect($host, $uid, $pwd)) {
    // get collation
    $getCol = mysql_query("SHOW COLLATION");
    if (@mysql_num_rows($getCol) > 0) {
        $output = '<select id="database_collation" name="database_collation">';
        while ($row = mysql_fetch_row($getCol)) {
            $collation = htmlentities($row[0]);
            $selected = ( $collation==$database_collation ? ' selected' : '' );
            $output .= '<option value="'.$collation.'"'.$selected.'>'.$collation.'</option>';
        }
        $output .= '</select>';
    }
}
echo $output;
?>