<?php 

/*
 * Title: Reflect Snippet
 * 
 * Desciption: 
 * 		Generates date based archives using Ditto
 * 
 * Author: 
 * 		Mark Kaplan for MODx CMF
 * 
 * Version: 
 * 		1.0.0
 * 
 * Note: 
 * 		 If Reflect is not retrieving its own documents, make sure that the
 *		 Ditto call feeding it has all of the fields in it that you plan on
 *       calling in your Reflect template. Furthermore, Reflect will ONLY
 * 		 show what is currently in the Ditto result set.
 *       Thus, if pagination is on it will ONLY show that page's items.
*/
 
// ---------------------------------------------------
//  Parameters
// ---------------------------------------------------

$id = isset($id) ? $id."_" : false;
/*
	Param: id

	Purpose:
	Unique ID for this Ditto instance for connection with other scripts (like Reflect) and unique URL parameters

	Options:
	Any valid folder location containing the Ditto source code with a trailing slash

	Default:
	"" - blank
*/
$getDocuments = isset($getDocuments) ? $getDocuments : 0;
/*
	Param: getDocuments

	Purpose:
 	Force Reflect to get documents

	Options:
	0 - off
	1 - on
	
	Default:
	0 - off
*/
$showItems = isset($showItems) ? $showItems : 1;
/*
	Param: showItems

	Purpose:
 	Show individual items in the archive

	Options:
	0 - off
	1 - on
	
	Default:
	1 - on
*/
$groupByYears = isset($groupByYears)? $groupByYears : 1;
/*
	Param: groupByYears

	Purpose:
 	Group the archive by years

	Options:
	0 - off
	1 - on
	
	Default:
	1 - on
*/
$targetID = isset($targetID) ? $targetID : $modx->documentObject['id'];
/*
	Param: targetID

	Purpose:
 	ID for archive links to point to

	Options:
	Any MODx document with a Ditto call setup with extenders=`dateFilter`
	
	Default:
	Current MODx Document
*/
$dateSource = isset($dateSource) ? $dateSource : "createdon";
/*
	Param: dateSource

	Purpose:
	Date source to display for archive items

	Options:
	# - Any UNIX timestamp from MODx fields or TVs such as createdon, pub_date, or editedon
	
	Default:
	"createdon"
	
	Related:
	- <dateFormat>
*/
$dateFormat = isset($dateFormat) ? $dateFormat : "%d-%b-%y %H:%M";	
/*
	Param: dateFormat

	Purpose:
	Format the [+date+] placeholder in human readable form

	Options:
	Any PHP valid strftime option

	Default:
	"%d-%b-%y %H:%M"
	
	Related:
	- <dateSource>
*/
$yearSortDir = isset($yearSortDir) ? $yearSortDir : "DESC";
/*
	Param: yearSortDir

	Purpose:
 	Direction to sort documents

	Options:
	ASC - ascending
	DESC - descending

	Default:
	"DESC"
	
	Related:
	- <monthSortDir>
*/
$monthSortDir = isset($monthSortDir) ? $monthSortDir : "ASC";
/*
	Param: monthSortDir

	Purpose:
 	Direction to sort the months

	Options:
	ASC - ascending
	DESC - descending

	Default:
	"ASC"
	
	Related:
	- <yearSortDir>
*/
$start = isset($start)? intval($start) : 0;
/*
	Param: start

	Purpose:
 	Number of documents to skip in the results
	
	Options:
	Any number

	Default:
	0
*/	
$phx = (isset($phx))? $phx : 1;
/*
	Param: phx

	Purpose:
 	Use PHx formatting

	Options:
	0 - off
	1 - on
	
	Default:
	1 - on
*/

// ---------------------------------------------------
//  Default Templates
// ---------------------------------------------------

$defaultTemplates['tpl'] = <<<TPL
<h3>Archives</h3>
<div class="reflect_archive_list">
	[+archive_items+]
</div>
TPL;

$defaultTemplates['year'] = <<<TPL
<a href="[+url+]" title="[+year+]" class="reflect_year_link">[+year+]</a>
TPL;

$defaultTemplates['month'] = <<<TPL
<a href="[+url+]" title="[+month+] [+year+]" class="reflect_month_link">[+month+]</a>
TPL;

$defaultTemplates['item'] = <<<TPL
<a href="[~[+id+]~]" title="[+pagetitle+]" class="reflect_item_link">[+pagetitle+]</a> (<span class="reflect_date">[+date+]</span>)
TPL;

// ---------------------------------------------------
//  Initialize Ditto
// ---------------------------------------------------
$placeholder = ($id != false && $getDocuments == 0) ? true : false;
if ($placeholder === false) {
	$rID = "reflect_".rand(1,1000);
	$itemTemplate = isset($tplItem) ? $tplItem: "@CODE:".$defaultTemplates['item'];
	$dParams = array(
		"id" => "$rID",
		"save" => "3",	
		"summarize" => "all",
		"dateFormat" => $dateFormat,
		"dateSource" => $dateSource,
		"tpl" => $itemTemplate,
	);

	if (isset($dittoSnippetParameters)) {
		$givenParams = explode("|",$dittoSnippetParameters);

		foreach ($givenParams as $parameter) {
			$p = explode(":",$parameter);
			$dParams[$p[0]] = $p[1];
		}
	}
	$dittoSnippetName = isset($dittoSnippetName) ? $dittoSnippetName : "Ditto";
	$modx->runSnippet($dittoSnippetName,$dParams);
	$ditto = $modx->getPlaceholder($rID."_ditto_object");
	$resource = $modx->getPlaceholder($rID."_ditto_resource");
} else {
	$ditto = $modx->getPlaceholder($id."ditto_object");
	$resource = $modx->getPlaceholder($id."ditto_resource");
}
if (!is_object($ditto) || !isset($ditto) || !isset($resource)) {
	return "The Ditto object is invalid. Please check it.";
}

// ---------------------------------------------------
//  Templates
// ---------------------------------------------------

$templates['tpl'] = isset($tplContainer) ? $ditto->template->fetch($tplContainer): $defaultTemplates['tpl'];
/*
	Param: tplContainer

	Purpose:
	Container template for the archive

	Options:
	- Any valid chunk name
	- Code via @CODE:
	- File via @FILE:

	Default:
	(code)
	<h3>Archives</h3>
	<div class="reflect_archive_list">
		[+archive_items+]
	</div>
*/
$templates['year'] = isset($tplYear) ? $ditto->template->fetch($tplYear): $defaultTemplates['year'];
/*
	Param: tplYear

	Purpose:
	Template for the year item (inside of li)

	Options:
	- Any valid chunk name
	- Code via @CODE:
	- File via @FILE:

	Default:
	(code)
	<a href="[+url+]" title="[+year+]" class="reflect_year_link">[+year+]</a>
*/
$templates['month'] = isset($tplMonth) ? $ditto->template->fetch($tplMonth): $defaultTemplates['month'];
/*
	Param: tplMonth

	Purpose:
	Template for the month item (inside of li)

	Options:
	- Any valid chunk name
	- Code via @CODE:
	- File via @FILE:

	Default:
	(code)
	<a href="[+url+]" title="[+month+] [+year+]" class="reflect_month_link">[+month+]</a>
*/
$templates['item'] = isset($tplItem) ? $ditto->template->fetch($tplItem): $defaultTemplates['item'];
/*
	Param: tplItem

	Purpose:
	Template for the individual item (inside of li)

	Options:
	- Any valid chunk name
	- Code via @CODE:
	- File via @FILE:

	Default:
	(code)
	<a href="[~[+id+]~]" title="[+pagetitle+]" class="reflect_item_link">[+pagetitle+]</a> (<span class="reflect_date">[+date+]</span>)
*/

$ditto->addField("date","display","custom");
	// force add the date field if receiving data from a Ditto instance

// ---------------------------------------------------
//  Reflect
// ---------------------------------------------------

if (function_exists("reflect") === FALSE) {
function reflect($archiveDocumentID, $showItems, $groupByYears, $resource, $archiveDateSource, $dateFormat, $ditto, $archive,$id,$start,$yearSortDir,$monthSortDir) {
	global $modx;
	$cal = array();
	$output = '';
	$build = array();
	$stop = count($resource);

	// loop and fetch all the results
	for ($i = $start; $i < $stop; $i++) {
		$date = getdate($resource[$i][$archiveDateSource]);
		$year = $date["year"];
		$month = $date["mon"];
		$cal[$year][$month][] = $resource[$i];
	}
	if ($yearSortDir == "DESC") {
		krsort($cal);
	} else {
		ksort($cal);
	}
	foreach ($cal as $year=>$months) {
		if ($monthSortDir == "ASC") {
			ksort($months);
		} else {
			krsort($months);
		}
		$build[$year] = $months;
	}
	$output .= '<ul class="reflect_archive">';
	foreach ($build as $year=>$months) {
		$year_url = $ditto->buildURL("year=".$year."&month=false",$archiveDocumentID,$id);
		if ($groupByYears) $output .=  '<li class="reflect_year">'.str_replace(array("[+year+]","[+url+]"),array($year,$year_url),$archive['year'])."\n\n";	
		foreach ($months as $mon=>$month) {
			$month_text = $ditto->formatDate(mktime(10, 10, 10, $mon, 10, $year),"%B");
			$month_url = $ditto->buildURL("month=".$mon."&year=".$year,$archiveDocumentID,$id);
			if ($groupByYears) $output .=  '<ul>';
			$output .= '<li class="reflect_month">'.str_replace(array("[+year+]","[+month+]","[+url+]"),array($year,$month_text,$month_url),$archive['month'])."\n";
			if ($showItems) {
				$output .=  '<ul class="reflect_items">'."\n";
				foreach ($month as $resource) {
					$output .=  '<li class="reflect_item">'.$ditto->render($resource, $archive['item'], false, $archiveDateSource, $dateFormat, array(),$phx).'</li>';
				}
				$output .= '</ul>';
			}
			$output .= '</li>';
			if ($groupByYears) $output .= '</ul>';
		}
		if ($groupByYears) $output .= '</li>';
	}
	$output .= '</ul>';
return str_replace("[+archive_items+]",$output, $archive['tpl']);
	
}
}

return reflect($targetID, $showItems, $groupByYears, $resource, $dateSource, $dateFormat, $ditto, $templates,$id,$start,$yearSortDir,$monthSortDir);

?>