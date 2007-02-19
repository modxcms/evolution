/*
 * Title: Ditto Snippet
 * Desc: Aggregates documents to create blogs, article/news
 * 		 collections, etc.,with full support for templating.
 * Author: Mark Kaplan
 * Version: 2.0 RC1
 */

//---Core Settings---------------------------------------------------- //

$ditto_version = "2.0RC1";
	// Ditto version being executed

$ditto_base = $modx->config['base_path']."assets/snippets/ditto/";
	// location where Ditto files are located; ends with a trailing slash

$dittoID = (!isset($id)) ? "" : $id."_";
$GLOBALS["dittoID"] = $dittoID;
	// unique ID for this Ditto instance
		
$language = (isset($language))? $language : "english";
	// language for defaults, debug, and error messages

$format = (isset($format)) ? strtolower($format) : "html" ;
	// tells the snippet whether to output html, json, xml, atom, or rss

$config = (isset($config)) ? $config : "default";
	// load a custom configuration
	
$debug = isset($debug)? $debug : 0;
	// for testing Ditto

$phx = (isset($phx))? $phx : 1;
	// randomize the order of the output
		
$placeholders = array();
	// initialize custom placeholders array
	
//---Includes-------------------------------------------------------- //

$files = array (
	"language" => $ditto_base."lang/$language.inc.php",
	"main_class" => $ditto_base."classes/ditto.class.inc.php",
	"template_class" => $ditto_base."classes/template.class.inc.php",
	"filter_class" => $ditto_base."classes/filter.class.inc.php",
	"format" => $ditto_base."formats/$format.format.inc.php",
	"config" => $ditto_base."configs/$config.config.php"
);
if ($phx == 1) {
	$files["prePHx"] = $ditto_base."classes/phx.pre.class.inc.php";
}
if ($debug == 1) {
	$files["debug_class"] = $ditto_base."classes/debug.class.inc.php";
}

if (isset($tagData)) {
	$files["tagging"] = $ditto_base."extenders/tagging.extender.inc.php";
}

if(isset($extenders)) {
	$extenders = explode(",",$extenders);
		// explode the extenders
	foreach ($extenders as $extender) {
		$files["$extender"] = $ditto_base."extenders/".$extender.".extender.inc.php";
			// append files to 
	}
}

$files = array_unique($files);
foreach ($files as $filename => $filevalue) {
	if (file_exists($filevalue) && $filename == "language") {
		include($filevalue);
	} else if (file_exists($filevalue)) {
		include_once($filevalue);		
	} else if ($filename == "language") {
		$modx->logEvent(1, 3, "Language file does not exist Please check: " . $filevalue, "Ditto " . $ditto_version);
		return "Language file does not exist Please check: " . $filevalue;
	} else {
		$modx->logEvent(1, 3, $filevalue . " " . $_lang['file_does_not_exist'], "Ditto " . $ditto_version);
		return $filevalue . " " . $_lang['file_does_not_exist'];
	}
}

//---Initiate Class-------------------------------------------------- //

if (class_exists('ditto')) {
	$ditto = new ditto($dittoID,$format,$header,$footer,$_lang,$debug);
		// create a new Ditto instance in the specified format and language with the requested debug level
} else {
	$modx->logEvent(1,3,$_lang['invalid_class'],"Ditto ".$ditto_version);
	return $_lang['invalid_class'];
}

//---Parameters------------------------------------------------------- /*

if (isset($startID)) {$parents = $startID;}
	// allow backwards compatibility

$IDs = isset($parents) ? $ditto->cleanIDs($parents) : $modx->documentIdentifier;
	// ids of folders for ditto to retrieve their children; separate by commas

$IDs = isset($documents) ? $ditto->cleanIDs($documents) : $IDs;
	// ids of documents for ditto to retrieve; separate by commas

$idType = isset($documents) ? "documents" : "parents";
	// type of IDs provided; can be either parents or documents

$paginate = isset($paginate)? $paginate : 0;
	// paginatation enabled or disabled

$summarize = isset($summarize) ? $summarize : 3;
	// number of documents to display in the results

$showPublishedOnly = isset($showPublishedOnly) ? $showPublishedOnly : 1;
	// allows you to show unpublished docs if needed

$showInMenuOnly = isset($showInMenuOnly) ? $showInMenuOnly : 0;
	// allows you to show docs marked not to show in the menus

$hideFolders = isset($hideFolders)? $hideFolders : 0;
	// don't show folders in the returned results

$depth = isset($depth) ? $depth : 1;
	// number of levels deep to go

$seeThruUnpub = (isset($seeThruUnpub))? $seeThruUnpub : 1 ;
	// allows the snippet to see unpublished folders children

$limit = (isset($limit))? $limit : 0;
	// number of documents to pull from DB, 0 indicates auto

$where = (isset($where))? $where : "";
	// custom where statement for use in main SQL query, only works with document object values

$noResults = isset($noResults)? $ditto->getParam($noResults,"no_documents") : $_lang['no_documents'];
	// text or chunk to be displayed when there are no results

$removeChunk = isset($removeChunk) ? explode(",",$removeChunk) : false;
	// chunk to be stripped from content

$sortDir = isset($sortDir) ? strtoupper($sortDir) : 'DESC';
	// get sort dir

$sortBy = isset($sortBy) ? $ditto->parseSort($sortBy, $randomize) : "createdon";
	// get sortBy

$offset = isset($start) ? $start : 0;
	// get offset

$start = (isset($_GET[$dittoID.'start']) && $paginate==1) ? ($_GET[$dittoID.'start']) : 0;
	// get document to start at

$globalFilterDelimiter = isset($globalFilterDelimiter) ? $globalFilterDelimiter : "|";
	// filter delimiter used to separate filters in the filter string
	
$localFilterDelimiter = isset($localFilterDelimiter) ? $localFilterDelimiter : ",";
	// filter delimiter used to separate parameters within each filter string
	
$filter = (isset($filter) || isset($cFilters)) ? $ditto->parseFilters($filter,$globalFilterDelimiter,$localFilterDelimiter,$cFilters) : false;
	// only show items meeting a criteria
	// differant filters are delimited by the global delimiter while each filter is delimited by the local delimiter
	// each filter is written in the form:
	// field, criteria, mode

$keywords = (isset($keywords))? $keywords : 0;
	// enable fetching of associated keywords for each document. Can be used as [+keywords+] or as a tagData source.

$randomize = (isset($randomize))? $randomize : 0;
	// randomize the order of the output

$save = (isset($save))? $save : 0;
	// saves the ditto object and results set to placeholders for use by other snippets
	//	0 - off; returns output
	//	1 - remaining; returns output
	//	2 - all;
	//	3 - all; returns ph only
	
//-------------------------------------------------------------------- */

$templates = $ditto->template->process($tpl,$tplAlt,$tplFirst,$tplLast,$tplCurrentDocument);
	// parse the templates for TV's and store them for later use

$ditto->setDisplayFields($ditto->template->fields);
	// parse hidden fields
	
$ditto->parseFields($placeholders,$seeThruUnpub,$customReset);
	// parse the fields into the field array
	
$documentIDs = $ditto->determineIDs($IDs, $idType, $ditto->fields["backend"]["tv"], $sortBy, $ditto->advSort, $sortDir, $depth, $showPublishedOnly, $seeThruUnpub, $hideFolders, $showInMenuOnly, $where, $keywords, $limit, $summarize, $filter,$paginate);
	// retrieves a list of document IDs that meet the criteria and populates the $resources array with them

$count = count($documentIDs);
	// count the number of documents to be retrieved

$summarize = ($summarize == "all") ? $count : $summarize;
	// allow summarize to use all option

$stop = ($save != "1") ? $summarize : $count;
	// set initial stop count

if($paginate == 1) {
	$paginateAlwaysShowLinks = isset($paginateAlwaysShowLinks)? $paginateAlwaysShowLinks : 0;
		// determine whether or not to always show previous next links
	$paginateSplitterCharacter = isset($paginateSplitterCharacter)? $paginateSplitterCharacter : $_lang['button_splitter'];
		// splitter to use of always show is disabled
	$tplPaginatePrevious = isset($tplPaginatePrevious)? $ditto->template->fetch($tplPaginatePrevious) : $_lang['prev'];
		// previous template
	$tplPaginateNext = isset($tplPaginateNext)? $ditto->template->fetch($tplPaginateNext) : $_lang['next'];
		// next template
	$ditto->paginate($start, $stop, $count, $summarize, $tplPaginateNext, $tplPaginatePrevious, $paginateAlwaysShowLinks, $paginateSplitterCharacter);
		// generate the pagination placeholders
}

$dbFields = $ditto->fields["display"]["db"];
	// get the database fields
$TVs = $ditto->fields["display"]["tv"];
	// get the TVs
	
if ($ditto->prefetch !== false) {
	$documentIDs = array_slice($documentIDs,$start,$stop);
		// set the document IDs equal to the trimmed array
	$dbFields = array_diff($dbFields,$ditto->prefetch["fields"]["db"]);
		// calculate the difference between the database fields and those already prefetched
	$dbFields[] = "id";
		// append id to the db fields array
	$TVs = array_diff($TVs,$ditto->prefetch["fields"]["tv"]);
		// calculate the difference between the tv fields and those already prefetched
} else {
	$limit = $start.', '.$summarize;
		// limit the number of items to be retrieved
}

$resource = $ditto->getDocuments($documentIDs, $dbFields, $TVs, $keywords, $showPublishedOnly, 0, $where, $limit, $sortBy, $sortDir);
	// retrieves documents

$output = $ditto->header;
	// initialize the output variable and send the header

if ($debug == 1) {
	$output .= $ditto->debug->header($ditto, $ditto_version, $documentIDs, array("db"=>$dbFields,"tv"=>$TVs), $summarize, $count, $sortBy, $sortDir, $start, $stop+$start,$count,$filter);
		// send the debug output
}

$stop = min($summarize,count($resource));
	// check stop one last time

if ($resource) {
	$resource = array_values($resource);
	if ($randomize == 1) shuffle($resource);
			// shuffle the resource array for randomness
	for ($x=0;$x<$stop;$x++) {
		$template = $ditto->template->determine($templates,$x,0,$stop,$resource[$x]["id"]);
			// choose the template to use and set the code of that template to the template variable
		$renderedOutput = $ditto->render($resource[$x], $template, $removeChunk,$placeholders,$phx);
			// render the output using the correct template, in the correct format and language
		$modx->setPlaceholder($dittoID."item[".($x)."]",$renderedOutput);
			// save the individual item placeholder for later use
		$output .= $renderedOutput;
			// send the rendered output to the buffer
	}
} else {
	return $output.$noResults.$ditto->footer;
		// if no documents are found return a no documents found string
}
$output .= $ditto->footer;
	// send the footer

// ---------------------------------------------------
// Save Object
// ---------------------------------------------------

if($save) {
	$modx->setPlaceholder($dittoID."ditto_object", $ditto);
	$modx->setPlaceholder($dittoID."ditto_resource", ($save == "1") ? array_slice($resource,$summarize) : $resource);
}

return ($save != 3) ? $output : "";
