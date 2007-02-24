/**
 * 
 * Title: Reflect
 * Desc: Generates archives using Ditto
 * Author: Mark Kaplan
 * Version: 2.0 RC2
 * Note: If Reflect is not retrieving its own documents, make sure that the
 *		 Ditto call feeding it has all of the fields in it that you plan on
 *       calling in your Reflect template. Furthermore, Reflect will ONLY
 * 		 show what is currently in the Ditto result set.
 *       Thus, if pagination is on it will ONLY show that page's items.
 */
 
// ---------------------------------------------------
//  Parameters
// ---------------------------------------------------

$id = isset($id) ? $id."_" : false;
	// set Ditto ID
	
$getDocuments = isset($getDocuments) ? $getDocuments : 0;

$placeholder = ($id != false && $getDocuments == 0) ? true : false;
	// name of placeholder to get data from

$showItems = isset($showItems) ? $showItems : 1;
	// show individual items in the archive

$groupByYears = isset($groupByYears)? $groupByYears : 1;
	// group the archive  by year as well as by month

$targetID = isset($targetID) ? $targetID : $modx->documentObject['id'];
	// ID of document that links in the archive will point to

$dateSource = isset($dateSource) ? $dateSource : "createdon";
	// date type to display for archive items (values can be unixtime fields like createdon, pub_date, editedon)
	
$dateFormat = isset($dateFormat) ? $dateFormat : "%d-%b-%y %H:%M";	
	// format for the date output
	
$yearSortDir = isset($yearSortDir) ? $yearSortDir : "DESC";
$monthSortDir = isset($monthSortDir) ? $monthSortDir : "ASC";

$start = isset($start)? intval($start) : 0;
	// number of items to skip

$phx = (isset($phx))? $phx : 1;
	// randomize the order of the output

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
//  Templates
// ---------------------------------------------------

$templates['tpl'] = isset($tplContainer) ? $ditto->template->fetch($tplContainer): $defaultTemplates['tpl'];

$templates['year'] = isset($tplYear) ? $ditto->template->fetch($tplYear): $defaultTemplates['year'];

$templates['month'] = isset($tplMonth) ? $ditto->template->fetch($tplMonth): $defaultTemplates['month'];

$templates['item'] = isset($tplItem) ? $ditto->template->fetch($tplItem): $defaultTemplates['item'];

// ---------------------------------------------------
//  Initialize Ditto
// ---------------------------------------------------

if ($placeholder === false) {
	$rID = "reflect_".rand(1,1000);
	$dParams = array(
		"id" => "$rID",
		"save" => "3",	
		"summarize" => "all",
		"dateFormat" => $dateFormat,
		"dateSource" => $dateSource,
		"tpl" => "@CODE:".$templates['item'],
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
//  Reflect
// ---------------------------------------------------
if (function_exists("reflect") === FALSE) {
function reflect($archiveDocumentID, $showItems, $groupByYears, $resource, $archiveDateSource, $dateFormat, $debug, $ditto, $archive,$id,$start,$yearSortDir,$monthSortDir) {
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
			$month_url = $ditto->buildURL("month=".$month_text."&year=".$year,$archiveDocumentID,$id);
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

return reflect($targetID, $showItems, $groupByYears, $resource, $dateSource, $dateFormat, $debug, $ditto, $templates,$id,$start,$yearSortDir,$monthSortDir);