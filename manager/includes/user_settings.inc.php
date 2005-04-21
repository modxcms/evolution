<?php

// Raymond: grab the user settings from the database.
$sql = "SELECT setting_name, setting_value FROM $dbase.".$table_prefix."user_settings WHERE user='".$_SESSION['internalKey']."' AND setting_value!=''";
$rs = mysql_query($sql);
$number_of_settings = mysql_num_rows($rs);
$settings = array();
while ($row = mysql_fetch_assoc($rs)) {
	$settings[$row['setting_name']] = $row['setting_value'];
}
extract($settings, EXTR_OVERWRITE);

?>