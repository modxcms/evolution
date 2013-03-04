<?php
// date functions

function daysInMonth($month, $year) {
   return date("t",mktime(0, 0, 0, $month, 1, $year));
} 

function convertdate($date) {
	list ($day, $month, $year) = explode ("-", $date);
	$date_valid = checkdate($month, $day, $year);
	if($date_valid==false) {
		echo "checkdate() returned false.";
		exit;
	}
	if (($timestamp = strtotime("$month/$day/$year")) === -1) {
		echo "Invalid date format.";
		exit;
	} else {
	   return $timestamp;
	}
}

$oneday = 86400;

function getDay($tracking_start_date = ''){
	if(empty($tracking_start_date)) {
		$tracking_start_date = strftime('%d-%m-%Y', time());
	}
	$startdate = convertdate($tracking_start_date);
	$enddate = $startdate+$GLOBALS['oneday'];
	return array('startdate' => $startdate, 'enddate' => $enddate);
}

function getMonth($tracking_start_date = ''){
	if(empty($tracking_start_date)) {
		$tracking_start_date = strftime('%d-%m-%Y', time());
	}
	$startdate = convertdate($tracking_start_date);
	$datetemp = getdate($startdate); 
	$diff = ($datetemp['mday']-1)*$GLOBALS['oneday'];
	$startdate = $startdate-$diff;
	$enddate = $startdate+(daysInMonth($datetemp['mon'], $datetemp['year'])*$GLOBALS['oneday']);
	return array('startdate' => $startdate, 'enddate' => $enddate);
}
?>