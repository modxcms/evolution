/** <?php /**
 *
 * Snippet Name: Ditto
 * Short Desc: Displays content aggregated from other parts of the site such as News Articles and Blog Posts
 * Author: The MODx Project
 * Version: 1.0.2
 * Last Edited: 09-Jun-2006
 * Function: Displays documents with full support for pagination (paging of content in increments) and Template Variables
 */

  // ---------------------------------------------------
  // Get Language
  // ---------------------------------------------------

$language = (isset($language))? $language : "english";

  // ---------------------------------------------------
  // Include files
  // ---------------------------------------------------

$files['language'] = "assets/snippets/ditto/lang/$language.inc.php";
$files['functions'] = "assets/snippets/ditto/ditto.class.inc.php";

foreach ($files as $filename => $filevalue) {
	if (file_exists($filevalue) && $filename != "language") {
	   include_once($filevalue);
	} else if(file_exists($filevalue) &&  $filename == "language") {
		include($filevalue);
		$GLOBALS['_lang'] = $_lang;
	} else if($filename == "language") {
		return "Language file does not exist Please check: ".$filevalue;
	  } else {
		return $filevalue.$_lang['file_does_not_exist'];
	}
}

$ditto = null;

if (class_exists('ditto')) {
   $ditto = new ditto();
} else {
	return $_lang['invalid_class'];
}

  // ---------------------------------------------------
  // Parameters
  // ---------------------------------------------------

$mode = isset($mode)? $mode : "development";
	// determines whether variable sanity checks are run.
	// use "development" while creating the snippet call and "production" when your site goes live for a little speed boost.

$startID = isset($startID) ? $ditto->cleanStartIDs($startID, $mode) : $modx->documentIdentifier;
    // the folder that contains post entries. separate by commas to use multiple folders

$paginate = isset($paginate)? $paginate : 0;
	// paginatation enabled or disabled

$summarize = isset($summarize) ? $summarize : 3;
    // number of posts of which to show a summary
    // remainder (to total) go as an arhived/other posts list

$trunc = isset($trunc) ? $trunc : 1;
    // should there be summary/short version of the posts?

$truncSplit = isset($truncSplit) ? $truncSplit : 1;
    // should the post be summarized at the "splitter"?

$truncAt = isset($truncAt) ? $truncAt : $_lang['default_splitter'];
    // where to split the text

$truncLen = isset($truncLen) ? $truncLen : 300;
    // how many characters to show of blogs

$truncOffset = isset($truncOffset) ? $truncOffset : 30;
    // how many characters to show of blogs

$truncText = isset($truncText)? $truncText : $_lang['more_text'];
    // text to be displayed in item link

$truncChars = isset($truncChars) ? $truncChars : 0;
	// truncate based on characters and not html tags

$tpl = isset($tpl) ? $modx->getChunk($tpl): $_lang['default_template'];
    // optional user defined chunk name to format the summary posts

$showPublishedOnly = isset($showPublishedOnly) ? $showPublishedOnly : 1;
    // allows you to show unpublished docs if needed

$showInMenuOnly = isset($showInMenuOnly) ? $showInMenuOnly : 0;
    // allows you to show docs marked not to show in the menus

$emptyText = isset($emptyText)? $emptyText : $_lang['no_entries'];
    // text to be displayed when there are no results

$dateFormat = isset($dateFormat)? $dateFormat : $_lang['date_format'];
    // format for the summary post date format

$displayArchive = isset($displayArchive)? $displayArchive : 1;
  // whether or not to show the archive

$archiveText = isset($archiveText)? $archiveText : $_lang['archives'];
    // text to use for the Archives listing

$commentsChunk = isset($commentsChunk)? '{{'.$commentsChunk.'}}' : '';
    // if you're using comments, the name of the chunk used to format them

$hiddenTVs = (isset($hiddenTVs))? $hiddenTVs : "" ;
	// allows the snippet to filter by tv's not in the template. separate by comma.

$ditto->hiddenTVs = $hiddenTVs;
	// send the variable to the class

$sortDir = isset($sortDir) ? strtoupper($sortDir) : 'DESC';
    // get sort dir

$sortBy = isset($sortBy) ? $ditto->checkSort($sortBy, $dateFormatType, $mode) : "createdon";
    // get sortBy

$hiddenTVs = $ditto->hiddenTVs;
	// grab latest hiddenTV additions

$dateFormatType = $ditto->checkDateFormat($sortBy,$dateFormatType);
	// date type to display (values can be createdon, pub_date, editedon)

if ($paginate == 1 && $_GET['start'] != 0) {
	$start= isset($_GET['start'])? $_GET['start']: 0;
		// get post # to start at
}else{
	$start = isset($start) ? $start : 0;
		// get start number
}

$debug = isset($debug)? $debug : 0;
    // for testing odittoy

$archiveDateType = isset($archiveDateType) ? $archiveDateType : $dateFormatType;
	// date type to display for archives (values can be createdon, pub_date, editedon)

$paginateAlwaysShowLinks = isset($paginateAlwaysShowLinks)? $paginateAlwaysShowLinks : 0;
	// determine whether or not to always show previous next links

$paginateSplitterCharacter = isset($paginateSplitterCharacter)? $paginateSplitterCharacter : $_lang['button_splitter'];
	// splitter to use of always show is disabled

$archivePlaceholder = isset($archivePlaceholder)? $archivePlaceholder : 0;
	// output archive (older posts section) as a placeholder called [+archive+]

$filter = isset($filter)? $filter : false;
	// odittoy show items meeting a criteria
	// differant filters are | (pipe) delimited while each filter is comma delimited
	// documentobjectortvwithtvprefix,criteria

$hideFolders = isset($hideFolders)? $hideFolders : 0;
	// don't show folders in the returned results

$descendentDepth = (isset($descendentDepth))? $descendentDepth : 10;
	// number of levels deep to go

$seeThruUnpub = (isset($seeThruUnpub))? $seeThruUnpub : 0 ;
	// allows the snippet to see unpublished folders children

$format = (isset($format))? $format : "html" ;
	// tells the snippet whether to output html, archive, or rss

$output = '';
    // initialize the output variable


// ---------------------------------------------------
// RSS Parameters
// ---------------------------------------------------

if ($format == "rss") {

	$startID= (isset($_REQUEST['startID'])? $_REQUEST['startID']: $startID);

	$copyright = isset($copyright) ? $copyright: $_lang['default_copyright'];
		// set copyright info

	$rssLanguage = isset($rssLanguage) ? $rssLanguage: $_lang['rss_lang'];
		// set copyright info

	$link = $modx->config['site_url']."[~".$modx->documentObject['id']."~]";
		// url to current page

	$ttl = isset($ttl) ? intval($ttl):120;

	$header =  '<?xml version="1.0" encoding="'.$modx->config['etomite_charset'].'" ?>'."\n".
		// set ttl value
			'<rss version="2.0">'."\n".
			'	<channel>'."\n".
			'		<title>'.$modx->documentObject['pagetitle'].'</title>'."\n".
			'		<link>'.$link.'</link>'."\n".
			'		<description>'.$modx->documentObject['introtext'].'</description>'."\n".
			'		<generator>Ditto 1.0.2 powered by MODx CMS</generator>'."\n".
			'		<language>'.$rssLanguage.'</language>'."\n".
			'		<copyright>'.$copyright.'</copyright>'."\n".
			'		<ttl>'.$ttl.'</ttl>'."\n";

	$footer = '	</channel>'."\n".
		'</rss>';

	$tpl = '		<item>'."\n".
		'			<title>[+rsspagetitle+]</title>'."\n".
		'			<link>[(site_url)][~[+id+]~]</link>'."\n".
		'			<description><![CDATA[ [+summary+] ]]></description>'."\n".
		'			<pubDate>[+rssdate+]</pubDate>'."\n".
		'			<guid>[(site_url)][~[+id+]~]</guid>'."\n".
		'			<author>[+rssusername+]</author>'."\n".
		'			</item>'."\n";

	$emptyText = $header."".$footer;
	$output .= $header;
}

// ---------------------------------------------------
// JSON Parameters
// ---------------------------------------------------

if ($format == "json") {

$startID= (isset($_REQUEST['startID'])? $_REQUEST['startID']: $startID);

$language = (isset($language))? $language : "en" ;
	// json language

$copyright = isset($copyright) ? $copyright:'[(site_name)] 2006';
	// set copyright info

$link = $modx->config['site_url']."[~".$modx->documentObject['id']."~]";
	// url to current page

$ttl = isset($ttl) ? intval($ttl):120;
	// set ttl value

$jsonp = (!empty($_REQUEST['jsonp']) ? $_REQUEST['jsonp'] : '');

$header =
$jsonp.'{
 "title":"'.$modx->documentObject['pagetitle'].'",
 "link":"'.$link.'",
 "description":"'.$modx->documentObject['introtext'].'",
 "language":"'.$language.'",
 "copyright":"'.$copyright.'",
 "copyright":"'.$copyright.'",
 "ttl":"'.$ttl.'",
 "entries":[
 ';

$footer = '
 ]
}';

$tpl = '
  {
   "title":"[+pagetitle+]",
   "link":"[(site_url)][~[+id+]~]",
   "description":"[+description+]",
   "content":"[+summary+]",
   "pubDate":"[+date+]",
   "guid":"[(site_url)][~[+id+]~]",
   "author":"[+author+]"
  },
';

$output .= $header;
}

// ---------------------------------------------------
// Additional Template Parameters
// ---------------------------------------------------

$tplArch = isset($tplArch) ? $modx->getChunk($tplArch): $_lang['default_archive_template'];
    // optional user defined chunk name to format the archive summary posts

$tplAltRows = isset($tplAltRows) ? $modx->getChunk($tplAltRows) : $tpl;
    // tpl for alternating rows

$tplFirstRow = isset($tplFirstRow) ? $modx->getChunk($tplFirstRow) : $tpl;
	// tpl for first row

$tplLastRow = isset($tplLastRow) ? $modx->getChunk($tplLastRow) : $tpl;
	// tpl for last row

$tplArchivePrevious = isset($tplArchivePrevious)? $modx->getChunk($tplArchivePrevious) : $_lang['prev'];
	// get the chunk code to be used inside the previous <a> tag.

$tplArchiveNext = isset($tplArchiveNext)? $modx->getChunk($tplArchiveNext) : $_lang['next'];
	// get the chunk code to be used inside the next <a> tag.

$tplCurrentDocument = isset($tplCurrentDocument)? $modx->getChunk($tplCurrentDocument) : $tpl;
	// tpl for the current document

// ---------------------------------------------------
// Tagging Parameters
// ---------------------------------------------------

$tags= !empty($_GET['tags']) ? $_GET['tags']: $tags;
	// get tags

if (!empty($tags)  && isset($tagData)) {

$tagMode = isset($tagMode) ? $tagMode: "onlyTags";
	// get the mode to remove tags. either onlyAllTags, removeAllTags, onlyTags, or removeTags.

$ditto->tagDelimiter = isset($tagDelimiter) ? $tagDelimiter: " ";
	// get tag delimiter used to split tags. defaults to space.

$ditto->tags = $tags;
	// send tags to the class

$filterTags = "$tagData,[TAGS],$tagMode";
	// set up filter line

$filter= ($filter !==false ? $filter."|$filterTags": $filterTags);
	// append tag filter to filter
}


  // ---------------------------------------------------
  // Variable Sanity Checks
  // ---------------------------------------------------

  $templates = array("tpl" => $tpl, "tplArch" => $tplArch, "tplAltRows" => $tplAltRows, "tplFirstRow" => $tplFirstRow, "tplLastRow" => $tplLastRow, "tplCurrentDocument" => $tplCurrentDocument);

  if ($mode == "development") {
	// Check template has placeholders
		foreach ($templates as $tplName => $tplCode) {
			preg_match_all('~\[\+(.*?)\+\]~', $tplCode, $tplmatches);
			if ($tpl == "") { return "&".$tplName." ".$_lang['blank_tpl'];}
			else if (count($tplmatches[1]) <= 0) { return $_lang['missing_placeholders_tpl'].$tpl.$_lang['missing_placeholders_tpl_2'];}
		}
  }

  // ---------------------------------------------------
  // Begin Processing
  // ---------------------------------------------------

$TVnames = ($mode != "development") ? $hiddenTVs : $ditto->findAllTVs($templates, $hiddenTVs, $filter);

$resource = $ditto->getDocuments($startID, $TVnames, $sortBy, $sortDir, $descendentDepth, $showPublishedOnly, $seeThruUnpub, $hideFolders);

if (count($resource) < 1) { return $emptyText."\n"; }

if ($showInMenuOnly == 1) { $filter= ($filter !==false ? $filter.'|hidemenu, 0': 'hidemenu, 0'); }

  // ---------------------------------------------------
  // Filter array for filter
  // ---------------------------------------------------

 if ($filter != false) {
	$resource = $ditto->filterDocuments($resource, $filter);
 }


  // ---------------------------------------------------
  // Sort array
  // ---------------------------------------------------

$advSort = $ditto->advancedsort;
	// set advancedsort

if ($advSort == $sortBy){
	$ditto->customSort($resource, $advSort, $sortDir);
}

  // ---------------------------------------------------
  // Count items
  // ---------------------------------------------------

$resource = array_values($resource);
$recordCount = count($resource);

if ($recordCount < 1) {return $emptyText."\n"; }
if ($summarize == "all") {$summarize = $recordCount;}

$total = isset($total) ? min( $total, $recordCount ) : $recordCount;
    // total number of documents to retrieve
$stop = min( $recordCount, $summarize , $total);
 	// document to stop at

if ($debug == 1) {
	$output .= $_lang['debug_summarized']." ".$summarize ."<br />".$_lang['debug_returned']." ".$stop."<br />".$_lang['debug_retrieved_from_db']." ".$recordCount."<br />".$_lang['debug_sort_by']." ".$sortBy."<br />".$_lang['debug_sort_dir']." ".$sortDir."<br/ >".$_lang["api"]."<br />".$_lang['tvs']." ".implode($TVnames,", ")."<br />";
}

if ($stop > 0) {

  // ---------------------------------------------------
  // Pagination
  // ---------------------------------------------------

	if ($paginate == 1 && $format != "rss") {
		$ditto->paginate($start, $stop, $total, $summarize, $tplArchiveNext, $tplArchivePrevious, $paginateAlwaysShowLinks, $paginateSplitterCharacter);
		$stop = $ditto->stop;
		$start = $ditto->start;
	}

  // ---------------------------------------------------
  // Process information for display
  // ---------------------------------------------------

	$stop = min( $stop, $total, $recordCount );
	if ($debug == 1) $output .= $_lang['debug_start_at']." ".$start." ".$_lang['debug_stop_at']." ".$stop." ".$_lang['debug_out_of']." ".$total."<br /><br />";
	for ($x = $start; $x < $stop; $x++) {
		$summary = $ditto->trimSummary($summary, $resource[$x], $trunc, $truncAt, $truncText, $truncLen, $truncOffset, $truncSplit, $commentsChunk, $truncChars);
		$link = $ditto->link;
		// Output debug info
			$output .= $ditto->render($resource[$x], $x, $format, $dateFormatType, $templates, $dateFormat, $debug, $summary, $link, $stop);
		}
	}

  // ---------------------------------------------------
  // Archives
  // ---------------------------------------------------

if ($format == "archive") {$stop = $start;}
if(($stop<$total && $displayArchive == 1 && $format == "html" && $paginate != 1) || $format == "archive") {

	$output .= $ditto->generateArchive($archiveText, $archivePlaceholder, $stop, $total, $resource, $dateFormatType, $dateFormat, $archiveDateType, $templates, $debug);

}
if($format == "rss") {$output .= $footer;}
if($format == "json") {
  $output = trim($output);
  $len = strlen($output);
  if($output{$len-1} == ',') $output{$len-1} = ' ';
  $output .= $footer;
}
  // ---------------------------------------------------
  // Output results
  // ---------------------------------------------------

return $output;