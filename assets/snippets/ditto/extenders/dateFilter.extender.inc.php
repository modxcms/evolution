<?php
// ---------------------------------------------------
// Date Filter Parameters
// ---------------------------------------------------

$dateSource = isset($dateSource) ? $dateSource : "createdon";
	// date type to display (values can be createdon, pub_date, editedon)

$dateFormat = isset($dateFormat)? $dateFormat : "%d-%b-%y %H:%M";
	// date format

if (!empty($_GET[$dittoID.'year'])) {
	if (!empty($_GET[$dittoID.'month']) && $_GET[$dittoID.'month'] != 'false') {
		$month = $_GET[$dittoID.'month'];
		$year = $_GET[$dittoID.'year'];
		$dateFilter = "$dateSource,$month $year,date";
		$modx->setPlaceholder($dittoID."month",$_GET[$dittoID.'month']);
		$modx->setPlaceholder($dittoID."year",$_GET[$dittoID.'year']);
	} else {
		$year = $_GET[$dittoID.'year'];
		$dateFilter = "$dateSource,$year,date";
		$modx->setPlaceholder($dittoID."year",$_GET[$dittoID.'year']);		
	}
	$filter= ($filter !==false) ? $filter."|$dateFilter": "$dateFilter";
}
?>