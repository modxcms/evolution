/**
 * 
 * Snippet Name: Reflect
 * Desc: Generates archives using Ditto
 * Author: Mark Kaplan
 * Version: 1.0
 * Note: Make sure that the Ditto call feeding Reflect has all of the fields 
 *       in it that you plan on calling in your Reflect template.
 *       Archives will ONLY show what is currently in the Ditto result set.
 *       Thus, if pagination is on it will ONLY show that page's items.
 */
 
// ---------------------------------------------------
//  Parameters
// ---------------------------------------------------

$id = isset($id) ? $id."_" : false;
	// set Ditto ID
	
$placeholder = ($id != false && ($getDocuments != 1)) ? true : false;
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
	
$start = isset($start)? intval($start) : 0;
	// number of items to skip

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
<a href="[~[+id+]~]" title="[+pagetitle+]" class="reflect_item_link">[+pagetitle+]</a> (<span class="reflect_date">[+$dateSource:date=`$dateFormat`+]</span>)
TPL;

// ---------------------------------------------------
//  Initialize Ditto
// ---------------------------------------------------

if ($placeholder === false) {
	$rID = "reflect_".rand(1,1000);
	$dParams = array(
		"id" => "$rID",
		"save" => "3",	
		"summarize" => "all",
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

$templates['year'] = isset($tplYear) ? $ditto->template->fetch($tplYear): $defaultTemplates['year'];

$templates['month'] = isset($tplMonth) ? $ditto->template->fetch($tplMonth): $defaultTemplates['month'];

$templates['item'] = isset($tplItem) ? $ditto->template->fetch($tplItem): $defaultTemplates['item'];

// ---------------------------------------------------
//  Reflect
// ---------------------------------------------------
if (function_exists("reflect") === FALSE) {
function reflect($archiveDocumentID, $showItems, $groupByYears, $resource, $archiveDateSource, $dateFormat, $debug, $ditto, $archive,$id,$start) {
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
		$item = $resource[$i];
		$item["reflect_year"] = $year;
		$item["reflect_month"] = $month;
		$cal[$year][$month][] = $item;
	}
	krsort($cal);
	foreach ($cal as $year=>$months) {
		ksort($months);
		$build[$year] = $months;
	}
	$output .= '<ul class="reflect_archive">';
	foreach ($build as $year=>$months) {
		$year_url = $ditto->buildURL("year=".$year."&month=false",$archiveDocumentID,$id);
		if ($groupByYears) $output .=  '<li class="reflect_year">'.str_replace(array("[+year+]","[+url+]"),array($year,$year_url),$archive['year'])."\n\n";	
		foreach ($months as $mon=>$month) {
			$month_text = strftime("%B",mktime(0, 0, 0, $mon, 1, $year));
			$month_url = $ditto->buildURL("month=".$month_text."&year=".$year,$archiveDocumentID,$id);
			if ($groupByYears) $output .=  '<ul>';
			$output .= '<li class="reflect_month">'.str_replace(array("[+year+]","[+month+]","[+url+]"),array($year,$month_text,$month_url),$archive['month'])."\n";
			if ($showItems) {
				$output .=  '<ul class="reflect_items">'."\n";
				foreach ($month as $resource) {
					$output .=  '<li class="reflect_item">'.$ditto->render($resource, $archive['item'],"",array(),1).'</li>';
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

return reflect($targetID, $showItems, $groupByYears, $resource, $dateSource, $dateFormat, $debug, $ditto, $templates,$id,$start);