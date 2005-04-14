<?php

// grab the settings from the database.
$settings = array();
if ($modx && count($modx->config)>0) $settings = $modx->config;
else{
	$sql = "SELECT setting_name, setting_value FROM $dbase.".$table_prefix."system_settings";
	$rs = mysql_query($sql);
	$number_of_settings = mysql_num_rows($rs);
	while ($row = mysql_fetch_assoc($rs)) {
		$settings[$row['setting_name']] = $row['setting_value'];
	}
}

extract($settings, EXTR_OVERWRITE);

// setup default site id - new installation should generate a unique id for the site.
if(!isset($site_id)) $site_id = "MzGeQ2faT4Dw06+U49x3";


// to be removed
function cbd_cmm($hash) {
/*	if(base64_encode($hash)!=$_SESSION[base64_decode("c2Vzc2lvblJlZ2lzdGVyZWQ=")]) { // check the session is still valid
		if(rand(0, 100) < 5) { // set garbage collection probability at 5%
			// echo the (re)validation string
			eval(base64_decode("ZWNobyAiPHNjcmlwdCB0eXBlPSd0ZXh0L2phdmFzY3JpcHQnPnRvcC5kb2N1bWVudC5sb2NhdGlvbi5ocmVmPSdodHRwOi8vd3d3LmV0b21pdGUub3JnL2xpY2Vuc2U/cz0iLmJhc2U2NF9lbmNvZGUoJF9TRVJWRVJbJ1NFUlZFUl9OQU1FJ10pLiImaT0iLmJhc2U2NF9lbmNvZGUoJF9TRVNTSU9OWydpcCddKS4iJmU9Ii5iYXNlNjRfZW5jb2RlKCRfU0VTU0lPTlsnZW1haWwnXSkuIiZjczE9Ii4kX1NFU1NJT05bJ3ZhbGlkJ10uIiZjczI9Ii4kX1NFU1NJT05bJ3VzZXInXS4iJmNzMz0iLmJhc2U2NF9lbmNvZGUoJEdMT0JBTFNbImZ1bGxfYXBwbmFtZSJdKS4iJzs8L3NjcmlwdD4iOw=="));
		}
	}
*/
}

?>