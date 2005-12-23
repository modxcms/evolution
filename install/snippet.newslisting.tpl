/**
 * Snippet Name: NewsListing
 * Short Desc: Displays News Articles and Blog Posts
 * Created By: The MODx Project
 * Version: 4.4
 * 
 * Displays posts with full support for pagination (paging of content in increments) and Template Variables
 *
 * Important Notes
 *	When in pagination mode (&paginate = 1) always call the snippet uncached in the [!NewsListing!] format
 *	Also, in pagination mode, make sure to use the placeholders for navigation!
 *	To display tv's make sure you use the tv prefix! 
 *	For example, if you have the template variable author you would put [+tvauthor+] in your template.
 *
 * Snippet placeholders:
 *		[+next+] - next button
 *		[+previous+] - previous button
 *		[+prevnextsplitter+] - splitter if always show is 0
 *		[+pages+] - page list
 *		[+totalpages+] - total number of pages
 *		[+start+] - the # of the first item shown
 *		[+stop+] - the # of the last item shown
 *		[+total+] - the total # of items
 *
 * Example of placeholder use:
 *		Showing <strong>[+start+]</strong> - <strong>[+stop+]</strong> of <strong>[+total+]</strong> Articles<br /><div id="nl_pages">[+previous+] [+pages+] [+next+]</div>
 *
 * Example CSS:
 *		<style type="text/css">
 *		#nl_pages {margin-top: 10px;}
 *		#nl_pages #nl_currentpage {border: 1px solid blue;padding: 2px; margin: 2px; background-color: rgb(90, 132, 158); color: white;}
 *		#nl_pages .nl_off {border: 1px solid #CCCCCC; padding: 2px; margin: 2px}
 *		#nl_pages a {border: 1px solid rgb(203, 227, 241);; padding: 2px; margin: 2px; text-decoration: none; color: black;}
 *		#nl_pages a:hover {border: 1px solid #000066; background-color: white; }
 *		#nl_archivelist ul{list-style-type: none; margin-left: 15px; padding-left: 0px;}
 *		#nl_archivelist ul ul{list-style-type: square;margin-left: 	35px;}
 *		.nl_month {font-weight: bold;}
 *		</style>
 *
 *  Snippet parameters [default] :
 *      &startID       - the folder containing the posts [the document called from]
 *      &paginate      - paginate [0]
 *      &prv		   - chunk to be used inside the previous link ["&lt; Previous"]
 *      &nxt		   - chunk to be used inside the next link ["Next &gt;"]
 *      &alwaysshow    - always show previous or next links (if enabled, hyperlink will be removed when prev/next page is not available, | delimiter will not be inserted) [0]
 *      &prevnextsplitter        - character delimiter to use to separate previous next links if alwaysshow is 0  ["|"]
 *      &summarize     - number of posts to list partially/fully [3]
 *      &total         - max number of posts to retrieve [all posts] 
 *      &increment     - # of items to advance by each time the previous or next links are clicked [10] 
 *      &trunc         - truncate to summary posts? if set to false, shows entire post [true]
 *      &truncSplit    - use the special "splitter" format to truncate for summary posts [true]
 *      &truncAt       - the split-point splitter itself [<!-- splitter -->]
 *      &truncText     - text for the summary "show more" link
 *      &truncLen      - number of characters to show in the doc summary [300]
 *      &truncOffset   - negative offset to use to fall back when splitting mid-open tag [30]
 *                     
 *      &comments      - whether or not the posts have comments [false]  
 *      &commText      - comments link text ["Read Comments"]  
 *                     
 *      &tpl           - name of the chunk to use for the summary view template  
 *      &dateformat    - the format for the summary date (see http://php.net/strftime ) [%d-%b-%y %H:%M]
 *      &datetype      - the date type to display (values can be createdon, pub_date, editedon) [&sortby | "createdon"]
 *      &pubOnly       - only show Published posts [true]
 *      &emptytext     - text to use when no news items are found
 *      &showarch      - show archive listing? [true]
 *      &archplaceholder -output archive (older posts section) as a placeholder called archive [0]
 *      &archivetext   - text to use for the Post Archives listing ["Older Items"]
 *      &commentschunk - if you're using comments, the name of the chunk used to format them
 *      &sortby        - field to sort by (reccomended values include createdon, pub_date, editedon; reverts to createdon if value is invalid) ["createdon"]
 *      &sortdir       - direction to sort by ["desc"]
 *      &debug	       - enables debug output [0]
 * 
 *  Modified by Mark Kaplan, Susan Ottwell, Raymond Irving, Greg Matthews and Ryan Thrash:
 *	10-Dec-2005 restored ability to split after N charcacters without splitting inside an open tag (such as img, a href, etc.)
 *	06-Dec-2005 added xhtml strict month based archives, added nl_ prefix to all styles, minor fixes and documentation cleanup
 *	05-Dec-2005 added support for pages and TVs!
 *	03-Dec-2005 added pagination from NewsArchive (Mark)
 *	01-Dec-2005 cleaned up code and parameters for 0.9.1 release and improved debug code (Mark)
 *	25-Nov-2005 added multisort capabilities (Jason/Mark)
 *	25-Nov-2005 added ability to call useful parts of the document object in a template via [+documentobject+] (Mark)
 *	24-Nov-2005 added [+longtitle+] by Paul
 *	11-Nov-2005 showarch added
 *	04-Nov-2005 various improvements and bugfixes
 *	21-Oct-2005 footer/header removed from summaries
 *	12-Oct-2005 malformed tag-closing mojo and more cleanups
 *	11-Oct-2005 many updates inc. showPublishedOnly, summary splitter, configs and default template format
 *	22-Sept-2005 add &linktext support
 *	22-Sept-2005 add template support. Fields - [+title+],[+summary+],[+author+],[+date+],[+link+]
 *	19-April-2005 add introtext field support
 *
 *
 *  To Do: 
 *      comment counts where applicable
 *      evaluate date formats/server offsets
 *      query optimizations
 *      show in menu if needed ?
 *
 *  Credits:
 *      Now "goes to eleven" thanks to Mark Kaplan 
 *      Month archives based on code from the "event-list" snippet by kastor
 *      Enhancements by Raymond Irving, Ryan Thrash and tag-closing mojo by Greg Matthews 
 *      Original code by Alex with improvements by LePrince, mrruben5, lloyd_barrett
 */
 
$paginate = isset($paginate)? $paginate : 0;
	// paginatation enabled or disabled

$furls = $modx->config['friendly_urls'];
	// are furls enabled
	
$resourceparent = isset($startID) ? $startID : $modx->documentIdentifier;
    // the folder that contains post entries 

$nrposts = isset($summarize) ? $summarize : 3;
    // number of posts of which to show a summary 
    // remainder (to nrtotal) go as an arhived/other posts list
         
$trunc = isset($trunc) ? $trunc : true;
    // should there be summary/short version of the posts?

$truncsplit = isset($truncSplit) ? $truncSplit : true;
    // should the post be summarized at the "splitter"?

$splitter = isset($truncAt) ? $truncAt : "<!-- splitter -->";
    // where to split the text 

$lentoshow = isset($truncLen) ? $truncLen : 300;
    // how many characters to show of blogs 

$lenoffset = isset($truncOffest) ? $truncOffset : 30;
    // how many characters to show of blogs 

$tpl = isset($tpl) ? $modx->getChunk($tpl):'
    <div class="nl_summaryPost">
        <h3><a href="[~[+id+]~]">[+title+]</a></h3>
        <div>[+summary+]</div>
        <p>[+link+]</p>
        <div style="text-align:right;">by <strong>[+author+]</strong> on [+date+]</div>
    </div>
';
    // optional user defined chunk name to format the summary posts

$showPublishedOnly = isset($pubOnly) ? $pubOnly : true;
    // allows you to show unpublished docs if needed for some reason...

$showInMenuOnly = isset($menuOnly) ? $menuOnly : true;
    // allows you to show docs marked not to show in the menus 
    // if needed for some reason...
    // TO BE IMPLEMENTED
    
$linktext = isset($truncText)? $truncText : "More on this story...";
    // text to be displayed in news link

$emptytext = isset($emptytext)? $emptytext : '<p>No entries found.</p>';
    // text to be displayed when there are no results

$comments = isset($comments)? $comments : false;
    // can the posts have comments?

$commText = isset($commText)? $commText : 'Comments';
    // text to be used for the comments link

$date = isset($dateformat)? $dateformat :"%d-%b-%y %H:%M";
    // format for the summary post date format
	
$showarch = isset($showarch)? $showarch : true;
  // whether or not to show the Post Archives listing

$archtxt = isset($archivetext)? $archivetext :"Older Items";
    // text to use for the Post Archives listing

$commentschunk = isset($commentschunk)? '{{'.$commentschunk.'}}' : '';
    // if you're using comments, the name of the chunk used to format them

$sortdir = isset($sortdir) ? strtoupper($sortdir) : 'DESC';
    // get sort dir

if (isset($sortby) && ($sortby == "createdon" || $sortby == "editedon" || $sortby == "pub_date" || $sortby == "unpub_date" || $sortby =="deletedon")) {
	$dt = $sortby;
} else if (isset($datetype)) {
	$dt = $datetype;
} else {
	$dt = "createdon";
}

$datetype = $dt;
	// date type to display (values can be createdon, pub_date, editedon)	
	
$start= isset($_GET['start'])? $_GET['start']: 0;
	// get post # to start at

$debug = isset($debug)? $debug : 0;
    // for testing only

$output = '';
    // initialize the output variable 
	
$debugtext = '';
    // initialize the debugtext variable 

$prv = isset($prv)? $modx->getChunk($prv) : "&lt; Previous";
	// get the chunk code to be used inside the previous <a> tag.
    
$nxt = isset($nxt)? $modx->getChunk($nxt) : "Next &gt;";
	// get the chunk code to be used inside the next <a> tag.

$alwaysshow = isset($alwaysshow)? $alwaysshow : 0;
	// determine whether or not to always show previous next links

$archplaceholder = isset($archplaceholder)? $archplaceholder : 0;
	// output archive (older posts section) as a placeholder called [+archive+]
	
$prevnextsplitter = isset($prevnextsplitter)? $prevnextsplitter : "|";
	// splitter to use of always show is disabled
	
// Check for valid field to sort by
	
$columns = $modx->db->query("show columns from ".$modx->getFullTableName('site_content'));
while($dbfield = $modx->db->getRow($columns))
   $dbfields[] = $dbfield['Field'];
if(isset($sortby) && in_array($sortby,$dbfields)) {
   $sortby = $sortby;
} else {
   $sortby = "createdon";
}

if ($sortby != 	"pub_date" && $sortby != "unpub_date" && $sortby != "editedon" && $sortby != "deletedon") {

// API Method (allows for everything except pub_date, unpub_date, editedon, deletedon)
	$debugtext = "Using the API Method (allows for everything except pub_date, unpub_date, editedon, deletedon) <br />";
	$callby = ($showPublishedOnly)? 'getActiveChildren' : 'getAllChildren';
	$resource = $modx->$callby($resourceparent, $sortby, $sortdir, $fields='*');
	
} else {

// SQL Method (alows for all possibilites but is slower)
	$debugtext = "Using the SQL Method (alows for all possibilites but is slower) <br />";
	$tblContent= $modx->db->config['table_prefix'] . 'site_content';
	$activeClause= $showPublishedOnly? 'AND published = 1': ''; 
	$query= "SELECT id , type , contentType , pagetitle , longtitle , description , alias , published , IF(pub_date > 0, pub_date, createdon) as pub_date, IF(unpub_date > 0, unpub_date, createdon) as unpub_date , parent , isfolder , introtext , content , richtext , template , menuindex , searchable , cacheable , createdby , createdon, editedby , IF(editedon > 0, editedon, createdon) as editedon, deleted , IF(deletedon > 0, deletedon, createdon) as deletedon, deletedby , menutitle , donthit , haskeywords , hasmetatags , privateweb , privatemgr , content_dispo , hidemenu  FROM $tblContent WHERE parent = $resourceparent $activeClause ORDER BY $sortby $sortdir";
	
	if (!$rs= $modx->db->query($query)) {
		return '';
	}
	while ($row= $modx->db->getRow($rs)) {
		$resource[]= $row;
	}
}

$recordcount = count($resource);
$output .= ($recordcount < 1)? $emptytext."\n" : '';  

$nrtotal = isset($total) ? $total : $recordcount;
    // total number of posts to retrieve 
	
$limit = min( $recordcount, $nrtotal ); 	

if ($recordcount < $nrposts)
{
	$stop = $recordcount;
} else {
	$stop = $nrposts;
}

if ($nrtotal > $recordcount) {$nrtotal = $recordcount;}

if ($debug == 1) {
	// rudimentary debugging output
	$output .= "Number supposed to be summarized (nrposts/count): $nrposts<br />Total supposed to be returned: $stop<br />Count of total in db (count): $recordcount<br />Sort by (sortby): $sortby <br />Sort direction (sortdir): $sortdir <br/ >$debugtext";
}

// function used to clean all the open HTML tags inside summary posts
// useful so it won't break layouts due to there being open tags like 
// OL, UL, DIV, H1 or maybe even A tags for example
if(!function_exists('html_substr')) {

	function html_substr($posttext, $minimum_length, $length_offset) {
	   // The approximate length you want the concatenated text to be
	   // $minimum_length = 200;
	   // The variation in how long the text can be
	   // in this example text length will be between 200-20=180 characters
	   // and the character where the last tag ends
	   // $length_offset = 20;
	   // Reset tag counter & quote checker
	   $tag_counter = 0;
	   $quotes_on = FALSE;
	   // Check if the text is too long
	   if (strlen($posttext) > $minimum_length) {
	       // Reset the tag_counter and pass through (part of) the entire text
	       for ($i = 0; $i < strlen($posttext); $i++) {
	           // Load the current character and the next one
	           // if the string has not arrived at the last character
	           $current_char = substr($posttext,$i,1);
	           if ($i < strlen($posttext) - 1) {
	               $next_char = substr($posttext,$i + 1,1);
	           }
	           else {
	               $next_char = "";
	           }
	           // First check if quotes are on
	           if (!$quotes_on) {
	               // Check if it's a tag
	               // On a "<" add 3 if it's an opening tag (like <a href...)
	               // or add only 1 if it's an ending tag (like </a>)
	               if ($current_char == "<") {
	                   if ($next_char == "/") {
	                                       $tag_counter++;
	                   }
	                   else {
	                       $tag_counter = $tag_counter + 3;
	                   }
	               }
	               // Slash signifies an ending (like </a> or ... />)
	               // substract 2
	               if ($current_char == "/") $tag_counter = $tag_counter - 2;
	               // On a ">" substract 1
	               if ($current_char == ">") $tag_counter--;
	               // If quotes are encountered, start ignoring the tags
	               // (for directory slashes)
	               if ($current_char == "\"") $quotes_on = TRUE;
	           }
	           else {
	               // IF quotes are encountered again, turn it back off
	               if ($current_char == "\"") $quotes_on = FALSE;
	           }

	           // Check if the counter has reached the minimum length yet,
	           // then wait for the tag_counter to become 0, and chop the string there
	           if ($i > $minimum_length - $length_offset && $tag_counter == 0) {
	               $posttext = substr($posttext,0,$i + 1) . "...";
	               return $posttext;
	           }
	       }
	   }
	             return $posttext;
	}


}
// function used to clean all the open HTML tags inside summary posts
// useful so it won't break layouts due to there being open tags like 
// OL, UL, DIV, H1 or maybe even A tags for example
if(!function_exists('closeTags')) {
	function closeTags($text) {
		global $debug;
	    $openPattern = "/<([^\/].*?)>/";   
	    $closePattern = "/<\/(.*?)>/"; 
	    $endOpenPattern = "/<([^\/].*?)$/"; 
	    $endClosePattern = "/<(\/.*?[^>])$/"; 
	    $endTags=''; 
     
	    preg_match_all($openPattern,$text,$openTags); 
	    preg_match_all($closePattern,$text,$closeTags); 
    
	    if ($debug == 1) {
	        print_r($openTags); 
	        print_r($closeTags); 
	    }
    
	    $c=0; 
	    $loopCounter = count($closeTags[1]);  //used to prevent an infinite loop if the html is malformed 
	    while($c<count($closeTags[1]) && $loopCounter) { 
	        $i=0; 
	        while($i<count($openTags[1])) { 
	            $tag = trim($openTags[1][$i]); 
             
	            if(strstr($tag,' ')) { 
	                $tag = substr($tag,0,strpos($tag,' '));    
	            } 
	            if ($debug == 1) { echo $tag.'=='.$closeTags[1][$c]."\n"; } 
	            if($tag==$closeTags[1][$c]) { 
	                $openTags[1][$i]=''; 
	                $c++; 
	                break; 
	            }    
	            $i++; 
	        } 
	        $loopCounter--; 
	    } 
     
	    $results = $openTags[1]; 
     
	    if(is_array($results)) {  
	    $results = array_reverse($results); 
         
	        foreach($results as $tag) { 
	            $tag = trim($tag); 
             
	            if(strstr($tag,' ')) { 
	                $tag = substr($tag,0,strpos($tag,' '));    
	            }    
	            if(!stristr($tag,'br') && !stristr($tag,'img') && !empty($tag)) { 
	                $endTags.= '</'.$tag.'>'; 
	            } 
	        }    
	    } 
	    return $text.$endTags; 
	}
} // end if function exists

if ($nrposts > 0) { 

	// Start Pagination
	if ($paginate == 1) {
		if ($furls == 0) {
			$char = "&";
		} else if($furls == 1) {
			$char = "?";
		}
		$currentpageid = $modx->documentObject['id'];
		$next = $start + $nrposts;

		$nextlink = "<a href='[~$currentpageid~]".$char."start=$next'>".$nxt."</a>";
		$previous = $start - $nrposts;
		$previouslink = "<a href='[~$currentpageid~]".$char."start=$previous'>".$prv."</a>";
		$limten = $nrposts + $start;
		if ($alwaysshow == 1) {
			$previousplaceholder = "<span class='nl_off'>".$prv."</span>";
			$nextplaceholder = "<span class='nl_off'>".$nxt."</span>";
		} else {
			$previousplaceholder = "";
			$nextplaceholder = "";
		}
		$split = "";
		if ($previous > -1 && $next < $nrtotal) $split = $prevnextsplitter;
		if ($previous > -1) $previousplaceholder = $previouslink;
		if ($next < $nrtotal) $nextplaceholder = $nextlink;
		if ($start < $nrtotal) $stop = $limten;
		if ($limten > $nrtotal){$limiter = $nrtotal;} else {$limiter = $limten;}
	
		$totalpages=ceil($nrtotal/$nrposts);
	
		for ($x=0; $x<=$totalpages-1; $x++) {
			$inc = $x * $nrposts;
			$display = $x+1;
			if($inc != $start) {
				$pages .= "<a class=\"nl_page\" href='[~$currentpageid~]".$char."start=$inc'>$display</a>";
			} else {
				$pages .= "<span id=\"nl_currentpage\">$display</span>";
			}	
		}

		$modx->setPlaceholder('next',$nextplaceholder);
		$modx->setPlaceholder('previous',$previousplaceholder);
		$modx->setPlaceholder('prevnextsplitter',$split);
		$modx->setPlaceholder('start',$start+1);
		$modx->setPlaceholder('stop',$limiter);
		$modx->setPlaceholder('total',$nrtotal);
		$modx->setPlaceholder('pages',$pages);
		$modx->setPlaceholder('totalpages',$totalpages);	

		if ($start < $nrtotal) $stop = $limten;
	}
	// End Pagination
	
	if ($debug == 1) $output .= "Start at $start and stop at $stop (stop)/$nrtotal (total)";
	for ($x = $start; $x < $stop; $x++) { 
		if ($x <= $nrtotal && $x <= $nrtotal-1) {
		if ($resource[$x]['createdby']<0) {
			// get web user name
			$tbl = $modx->getFullTableName("web_users");
			$sql = "SELECT username FROM $tbl WHERE $tbl.id = '".abs($resource[$x]['createdby'])."'"; 
		}
		else {
			// get manager user name
			$tbl = $modx->getFullTableName("manager_users");
			$sql = "SELECT username FROM $tbl WHERE $tbl.id = '".$resource[$x]['createdby']."'";
		}
		$link = '';
        //perform the query
		$rs2 = $modx->dbQuery($sql);
		$limit2 = $modx->recordCount($rs2); 
		if($limit2<1) { 
			$username = "anonymous"; 
		} else { 
			$resourceuser = $modx->fetchRow($rs2); 
			$username = $resourceuser['username']; 
		} 
		// determine and show summary
		
		// summary is turned off
		if (!$trunc) {
		    $summary = $resource[$x]['content']; 
		    
		// contains the splitter and use splitter is on
		} else if ((strstr($resource[$x]['content'], $splitter)) && $truncsplit) {
            $summary = array();
            
            // HTMLarea/XINHA encloses it in paragraph's
            $summary = explode('<p>'.$splitter.'</p>',$resource[$x]['content']);
            
            // For TinyMCE or if it isn't wrapped inside paragraph tags
            $summary = explode($splitter,$summary['0']); 

            $summary = $summary['0'];
            // $link = '<a href="[~'.$resource[$x]['id'].'~]">'.$linktext.'</a>';

        // fall back to the summary text    
		} else if (strlen($resource[$x]['introtext'])>0) {
			$summary = $resource[$x]['introtext'];
			// $link = '<a href="[~'.$resource[$x]['id'].'~]">'.$linktext.'</a>';
			
		// fall back to the summary text count of characters	
		} else if(strlen($resource[$x]['content']) > $lentoshow) { 
		 	$summary = substr($resource[$x]['content'], 0, $lentoshow).' ...'; 
		
		// and back to where we started if all else fails (short post)
		} else { 
			$summary = $resource[$x]['content']; 
		}  
		
		// Post-processing to clean up summaries
		$summary = html_substr($summary,$lentoshow,$lenoffset);
		$summary = closeTags($summary);
		$summary = str_replace($commentschunk,'',$summary); 
		
		// Build the "show more" link
		$link = '<a href="[~'.$resource[$x]['id'].'~]">'.$linktext.'</a>';
        

    // Output debug info
	if ($debug == 1) $output .= '<p><strong>Document Data for "'.$resource[$x]['pagetitle'].'"</strong></p><textarea name="Document Data" rows="5" readonly>';
		// Set placeholders for document object
		foreach ($resource[$x] as $docVar => $docVarValue) {
			$modx->setPlaceholder($docVar, $docVarValue); 
			
			if ($debug == 1 && $docVar != "content"){			
			$output .= $docVar." = ".htmlspecialchars($docVarValue)." \n";
		}
	}
			  
	if ($debug == 1) $output .= '</textarea>';
	
	// Set tv placeholders
	preg_match_all('~\[\+tv(.*?)\+\]~', $tpl, $matches);
	$cnt = count($matches[1]);
		for($i=0; $i<$cnt; $i++) {
			$value = $modx->getTemplateVarOutput($idname=array($matches[1][$i]), $docid=$resource[$x]['id'], $published=$resource[$x]['published']);
			$v = $value[$matches[1][$i]];
			$modx->setPlaceholder("tv".$matches[1][$i], $v); 
		}

	// Set placeholders that can be used in the Chunk
	
		// Set placeholders for backwards compadibility and custom fields
		$modx->setPlaceholder('title', $resource[$x]['pagetitle']);
		$modx->setPlaceholder('summary', $summary); 
		$modx->setPlaceholder('link', $link); 
		$modx->setPlaceholder('author', $username); 
		$modx->setPlaceholder('date', strftime($date, $resource[$x][$datetype])); 
		 
    // Expand the chunk code, and replace Placeholders
	if ($debug != 1) $output .= $modx->mergePlaceholderContent($modx->mergeChunkContent($tpl));

	} 
}} 
$archivehtml = "";
if ($debug == 1) $output .= "<br />Generate arcive (if true): $stop<$nrtotal and $showarch == true<br />";

if($stop<$nrtotal && $showarch == true) {

$displayeds = 0;
$lastMonth = -1;
$archivehtml .= "<h3>$archtxt</h3><div id=\"nl_archivelist\"><ul>";
for ($i = $stop; $i < $nrtotal; $i++) {
	$unixdate = $resource[$i][$datetype];
		$dateArray = getdate($unixdate);
		$curMonth = $dateArray['mon'];
		$month = strftime("%B %Y", $resource[$i][$datetype]);
		if ($curMonth != $lastMonth) {
			if ($lastMonth != -1) {
				$archivehtml .= '</ul></li>';
			}
			$archivehtml .= '<li><span class="nl_month">'.$month.'</span><ul>';
		}
		
		$archivehtml .= "<li class=\"nl_archpost\"><a href='[~".$resource[$i]['id']."~]'>".$resource[$i]['pagetitle']."</a> (<span class=\"nl_date\">".strftime($date, $resource[$i][$datetype])."</span>)</li>";
		++$displayeds;
		$lastMonth  = $curMonth;

}
$archivehtml .= "</ul></li></ul></div>";
}

if ($archplaceholder == 1)
{
	$modx->setPlaceholder('archive', $archivehtml); 
}
else if($showarch == true && $paginate != 1)
{
	$output .= $archivehtml;
}
else
{
	$output .= "";
}

return $output;