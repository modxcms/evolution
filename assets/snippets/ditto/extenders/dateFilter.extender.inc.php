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
		var $month,$year,$dateSource;
	
		function dateFilter($month,$year,$dateSource) {
			$this->month = $month;
			$this->year = $year;
			$this->dateSource = $dateSource;
		}
		function execute($value) {
			$month = $this->month;
			$year = $this->year;
			$unset = 1;
			$min = ($month == false) ? mktime(0,0,0,1,1,$year) : mktime(0,0,0,$month,1,$year);
			$max = ($month == false) ? mktime(0,0,0,1,1,$year+1): mktime(0,0,0,($month+1),0,$year);
			if ($value[$this->dateSource] <= $min || $value[$this->dateSource] >= $max){
				$unset = 0;
			}
			return $unset;
		}
	}
}
if (!empty($_GET[$dittoID.'year'])) {
	if (!empty($_GET[$dittoID.'month']) && $_GET[$dittoID.'month'] != 'false') {
		$month = $_GET[$dittoID.'month'];
		$year = $_GET[$dittoID.'year'];
		$month_text = ditto::formatDate(mktime(10, 10, 10, $month, 10, $year),"%B");
		$modx->setPlaceholder($dittoID."month",$month_text);
		/*
			Placeholder: month

			Content:
			Month being filtered by
		*/
		$modx->setPlaceholder($dittoID."year",$_GET[$dittoID.'year']);
		/*
			Placeholder: year

			Content:
			Year being filtered by
		*/
	} else {
		$year = $_GET[$dittoID.'year'];
		$month = false;
		$modx->setPlaceholder($dittoID."year",$_GET[$dittoID.'year']);		
	}
	$dateFilterOject = new dateFilter($month,$year,$dateSource);
	$cFilters["dateFilter"] = array($dateSource,array($dateFilterOject,"execute"));
}

?>