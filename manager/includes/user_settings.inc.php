<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

// START HACK
if (isset ($modx)) {
	$user_id = $modx->getLoginUserID();
} else {
	$user_id = $_SESSION['mgrInternalKey'];
}
// END HACK

if (!empty($user_id)) {
	// Raymond: grab the user settings from the database.
	//$sql = "SELECT setting_name, setting_value FROM $dbase.".$table_prefix."user_settings WHERE user='".$modx->getLoginUserID()."' AND setting_value!=''";
	$sql = "SELECT setting_name, setting_value FROM $dbase.`" . $table_prefix . "user_settings` WHERE user=" . $user_id;
	$rs = mysql_query($sql);
	$number_of_settings = mysql_num_rows($rs);
	
	while ($row = mysql_fetch_assoc($rs)) {
		$settings[$row['setting_name']] = $row['setting_value'];
		if (isset($modx->config)) {
			$modx->config[$row['setting_name']] = $row['setting_value'];
		}
	}
	
	extract($settings, EXTR_OVERWRITE);
}
?>
