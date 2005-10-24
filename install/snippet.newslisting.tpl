/**
 *	NewsListing 
 *	Displays posts (articles, blogs, news items and so on).
 *
 *  Modified by Raymond Irving, Greg Matthews Mark Kaplan and Ryan Thrash:
 *      21-Oct-2005 footer/header removed from summaries
 *      12-Oct-2005 malformed tag-closing mojo and more cleanups
 *      11-Oct-2005 many updates inc. showPublishedOnly, summary splitter, configs and default template format
 *      22-Sept-2005 add &linktext support
 *      22-Sept-2005 add template support. Fields - [+title+],[+summary+],[+author+],[+date+],[+linkurl+]
 *      19-April-2005 add introtext field support
 *  
 *  TO DO: 
 *      A BETTER NAME!!!
 *      support multiple calls per page
 *      nicer formatting, including classes and maybe a template chunk, on summary post "show more" links
 *      comment counts where applicable
 *      restore splitting at specified length, but not in the middle of an Anchor or Image or other tag
 *      evaluate date formats/server offsets
 *      query optimizations
 *      show in menu if needed ?
 *
 *  Credits:
 *      tag-closing mojo by Greg Matthews
 *      enhancements Raymond Irving, Mark Kaplan, Ryan Thrash, LePrince, mrruben5, lloyd_barrett
 *      original code Alex
 *
 *  Snippet parameters [default] :
 *      &startID       - the folder containing the posts [the document called from]
 *      &summarize     - number of posts to list partially/fully [3]
 *      &total         - max number of posts to retrieve [100] 
 *                     
 *      &trunc         - truncate to summary posts? if set to false, shows entire post [true]
 *      &truncSplit    - use the special "splitter" format to truncate for summary posts [true]
 *      &truncAt       - the split-point splitter itself [<!-- splitter -->]
 *      &truncLen      - if you don't have a splitter or you turn that off explicitly, the number 
 *                       of characters of the blog to show for summary if not using splitter [450] 
 *                       However, if you have a summary of the post, it will use that instead. 
 *                       DEPRECATED ... see below...
 *      &truncText     - text for the summary "show more" link
 *                     
 *      &comments      - whether or not the posts have comments [false]  
 *      &commText      - comments link text ["Read Comments"]  
 *                     
 *      &tpl           - name of the chunk to use for the summary view template  
 *      &dateformat    - the format for the summary date (see http://php.net/strftime ) [%d-%b-%y %H:%M]
 *      &pubOnly       - only show Published posts [true]
 *      &menuOnly      - only show docs with "show in menu" checked [true] TO BE IMPLEMENTED
 *      &emptytext     - text to use when no news items are found
 *      &archivetext   - text to use for the Post Archives listing ["Older Items"]
 *      
 */
 
$resourceparent = isset($startID) ? $startID : $modx->documentIdentifier;
    // the folder that contains post entries 

$nrposts = isset($summarize) ? $summarize : 3;
    // number of posts of which to show a summary 
    // remainder (to nrtotal) go as an arhived/other posts list
         
$nrtotal = isset($total) ? $total : 100;
    // total number of posts to retrieve 

$trunc = isset($truc) ? $trunc : true;
    // should there be summary/short version of the posts?

$truncsplit = isset($trucSplit) ? $truncSplit : true;
    // should the post be summarized at the "splitter"?

$splitter = isset($trucAt) ? $truncAt : "<!-- splitter -->";
    // where to split the text 

//$lentoshow = isset($trucLen) ? $truncLen : 450;
    // how many characters to show of blogs 
    // DEPRECATED: too easy to split in middle of an A tag, use splitter, above
    // but left here to uncomment if you feel so inclined

$tpl = isset($tpl) ? $modx->getChunk($tpl):'
    <div class="summaryPost">
        <h3>[+title+]</h3>
        <div>[+summary+]</div>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;[+link+]</p>
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
    
$linktext = isset($truncText)? $truncText : "More on this story >";
    // text to be displayed in news link

$emptytext = isset($emptytext)? $emptytext : '<p>No entries found.</p>';
    // text to be displayed when there are no results

$comments = isset($comments)? $comments : false;
    // can the posts have comments?

$commText = isset($commText)? $commText : 'Comments';
    // text to be used for the comments link

$date = isset($dateformat)? $dateformat :"%d-%b-%y %H:%M";
    // format for the summary post date format

$archtxt = isset($archivetext)? $archivetext :"Older Items";
    // text to use for the Post Archives listing

$commentschunk = isset($commentschunk)? '{{'.$commentschunk.'}}' : '';
    // if you're using comments, the name of the chunk used to format them

$debug = false;
    // for testing only

$output = '';
    // initialize the blog variable 

$callby = ($showPublishedOnly)? 'getActiveChildren' : 'getAllChildren';

$resource = $modx->$callby($resourceparent, 'createdon', 'DESC', $fields='id, pagetitle, description, introtext, content, createdon, createdby, hidemenu');
$limit = count($resource);
$output .= ($limit < 1)? $emptytext."\n" : '';  
$limit = min( $limit, $nrtotal ); 

// uncomment the following line for some very rudimentary debugging output
// $output .= "Number supposed to be summarized (nrposts/count): $nrposts<br />Total supposed to be returned (nrtotal/total): $nrtotal<br />Count of total in db (count/limit): $limit<br /><br />";

// function used to clean all the open HTML tags inside summary posts
// useful so it won't break layouts due to there being open tags like 
// OL, UL, DIV, H1 or maybe even A tags for example
function closeTags($text) { 
    $openPattern = "/<([^\/].*?)>/";   
    $closePattern = "/<\/(.*?)>/"; 
    $endOpenPattern = "/<([^\/].*?)$/"; 
    $endClosePattern = "/<(\/.*?[^>])$/"; 
    $endTags=''; 
     
    preg_match_all($openPattern,$text,$openTags); 
    preg_match_all($closePattern,$text,$closeTags); 
    
    if ($debug) {
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
            if ($debug) { echo $tag.'=='.$closeTags[1][$c]."\n"; } 
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

if ($nrposts > 0) { 
	for ($x = 0; $x < $nrposts; $x++) { 
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

        //perform the query
		$rs2 = $modx->dbQuery($sql);
		$limit2 = $modx->recordCount($rs2); 
		if($limit2<1) { 
			$username = "anonymous"; 
		}
		else { 
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
            $summary = closeTags($summary);
            
        // fall back to the summary text    
		} else if (strlen($resource[$x]['introtext'])>0) {
			$summary = $resource[$x]['introtext'];
			
		// fall back to the summary text count	
		// skipping this because of ease of breaking in the middle of an A tag... 
		// so it's not a good idea. If you must have this, then uncomment
		// } else if(strlen($resource[$x]['content']) > $lentoshow) { 
		// 	$summary = substr($resource[$x]['content'], 0, $lentoshow).'...'; 
		
		// and back to where we started if all else fails (short post)
		} else { 
			$summary = $resource[$x]['content']; 
		}  

		$summary = str_replace($commentschunk,'',$summary); 

        // add the link to the full post (permalink)
        $link = '<a href="[~'.$resource[$x]['id'].'~]">'.$linktext.'</a>';
		
		$fields = array('[+title+]','[+summary+]','[+link+]','[+author+]','[+date+]');
		$values = array($resource[$x]['pagetitle'],$summary,$link,$username,strftime($date, $resource[$x]['createdon']));
		
		$output .= str_replace($fields,$values,$tpl); 
	} 
} 

if($limit>$nrposts) { 
   $output .= "<h3>$archtxt</h3>";
   $output .= "<ul class=\"archivelist\">";
   for ($x = $nrposts; $x < $limit; $x++) { 
      $output .= "<li><a href='[~".$resource[$x]['id']."~]'>".$resource[$x]['pagetitle']."</a></li>";
   } 
   $output .= "</ul>";
}

return $output;