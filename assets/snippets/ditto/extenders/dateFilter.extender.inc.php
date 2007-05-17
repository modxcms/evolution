<?php

/*
 * Title: Date Filter
 * Purpose:
 *  	Filtering companion to Reflect or other date based filtering
*/

// ---------------------------------------------------
// Date Filter Parameters
// ---------------------------------------------------

$dateSource = isset($dateSource) ? $dateSource : "createdon";
/*
	Param: dateSource

	Purpose:
	Source of the [+date+] placeholder

	Options:
	# - Any UNIX timestamp from MODx fields or TVs such as createdon, pub_date, or editedon
	
	Default:
	"createdon"
*/
if (!class_exists("dateFilter")) {
	class dateFilter {
		var $month,$year,$day,$dateSource;
	
		function dateFilter($month,$year,$day,$dateSource) {
			$this->month = $month;
			$this->year = $year;
			$this->day = $day;
			$this->dateSource = $dateSource;
		}
		function execute($value) {
			$month = $this->month;
			$year = $this->year;
			$day = $this->day;
			$unset = 1;
			
			if ($year && !$month && !$day) { // Year only e.g. 2007
				$min = mktime(0,0,0,1,1,$year);
				$max = mktime(23,59,59,12,31,$year);
			} else if ($year && $month && !$day) { // Year and month e.g. 2007-01
				$min = mktime(0,0,0,$month, 1, $year);
				$max = mktime(23,59,59,$month, date("t", $min), $year);
			} else if ($year && $month && $day) { // Year month and day e.g. 2007-01-11
				$min = mktime(0,0,0,$month, $day, $year);
				$max = mktime(23,59,59,$month, $day, $year);
			}
			
			if ($value[$this->dateSource] <= $min || $value[$this->dateSource] >= $max){
				$unset = 0;
			}
			return $unset;
		}
	}
}
if (!empty($_GET[$dittoID.'year'])) {
	if (!empty($_GET[$dittoID.'month']) && $_GET[$dittoID.'month'] != 'false') {
		$month = intval($_GET[$dittoID.'month']);
		$year = intval($_GET[$dittoID.'year']);
		$month_text = ditto::formatDate(mktime(10, 10, 10, $month, 10, $year),"%B");
		$modx->setPlaceholder($dittoID."month",$month_text);
		/*
			Placeholder: month

			Content:
			Month being filtered by
		*/
		$modx->setPlaceholder($dittoID."year",$year);
		/*
			Placeholder: year

			Content:
			Year being filtered by
		*/
	if ($_GET[$dittoID.'day'] != 'false') {
		$day = intval($_GET[$dittoID.'day']);
		$modx->setPlaceholder($dittoID."day",$day);
		/*
			Placeholder: day

			Content:
			Day being filtered by
		*/
	}
} else {
		$year = intval($_GET[$dittoID.'year']);
		$day = false;
		$month = false;
		$modx->setPlaceholder($dittoID."year",$year);		
}
	$dateFilterOject = new dateFilter($month,$year,$day,$dateSource);
	$filters["custom"]["dateFilter"] = array($dateSource,array($dateFilterOject,"execute"));
}

?>