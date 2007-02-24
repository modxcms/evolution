<?php

// ---------------------------------------------------
// Date Placeholder Parameters
// ---------------------------------------------------

$dateSource = isset($dateSource) ? $dateSource : "createdon";
	// date type to display (values can be createdon, pub_date, editedon)

$dateFormat = isset($dateFormat)? $dateFormat : "%d-%b-%y %H:%M";
	// date format
	
$placeholders['date'] = array($dateSource,"datePH");

$GLOBALS["ditto_date_params"] = array($dateFormat,$dateSource);

if (!function_exists("datePH")) {
	function datePH($resource) {
		global $ditto_date_params,$modx;
		$dateFormat = $ditto_date_params[0];
		$dateSource = $ditto_date_params[1];
		$dt =  strftime($dateFormat, intval($resource[$dateSource]) + $modx->config["server_offset_time"]);
		return $dt;
	}
}
?>