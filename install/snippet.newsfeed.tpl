/**
*	NewsFeed for MODx v3
*	Created by Raymond Irving, August 2005
*	Code made RSS valid and enhanced by Mark Kaplan, September-October 2005
*	Tag-closing feature by Greg Matthews
*
*	Enable RSS2 news feed from your website
*
*	Snippet Parameters [default]:
* 
*      &defaultauthor    - Default username to use when missing or not available. 
*                          Defaults to the initial admin user account. [string] 
*                          Example: &defaultauthor=`Admin <youremail@yoursite.com>`
*
*		&makerss	- set to 0 to generate a link to the feed. Defaults to 1
*		
*	(available when &makerss=1)
*		&newsfolder	- Folder id where news items are to be stored. Example &newsfolder=`2`. If &newsfolder is missing the current document id will be used.
*		&topitems	- set the top number of items to be listed in news feed. [20]
*		&copyright	- set copyright information
* 		&ttl		- set how often should feed readers check for new material (in seconds) -- mostly ignored by readers.
*      &trunc         - truncate to summary posts? if set to false, shows entire post [true]
*      &truncSplit    - use the special "splitter" format to truncate for summary posts [true]
*      &truncAt       - the split-point splitter itself [<!-- splitter -->]
*      &truncLen      - if you don't have a splitter or you turn that off explicitly, the number 
*                       of characters of the blog to show for summary if not using splitter [450] 
*                       However, if you have a summary of the post, it will use that instead. 
*      &pubOnly    - display published documents [true]
*	(available when &makerss=0)
*		&showlink	- set to 1 to show feed link. Defaults to 1 
*		&linkid		- set the document id for the rss new feed. (available when &makerss=0)
*/


// get folder id where we should look for news else look in current document
$folder = isset($newsfolder) ? intval($newsfolder):$modx->documentIdentifier;

// get current document id
$docid = $modx->documentIdentifier;

// set subscribe mode
$makerss = isset($makerss) ? $makerss:1;

// set link id
$linkid = isset($linkid) ? intval($linkid): 0;

// set show link mode
$showlink = isset($showlink) ? $showlink: 1;

// set top items
$topitems = isset($topitems) ? $topitems : 20;

// set copyright info
$copyright = isset($copyright) ? $copyright:'';

// set ttl value
$ttl = ($ttl) ? intval($ttl):120;

// set lentoshow
$lentoshow = isset($truncLen) ? $truncLen : 450;

$trunc = isset($truc) ? $trunc : true;
    // should there be summary/short version of the posts?

$truncsplit = isset($trucSplit) ? $truncSplit : true;
    // should the post be summarized at the "splitter"?

$splitter = isset($trucAt) ? $truncAt : "<!-- splitter -->";
    // where to split the text 
    
$showPublishedOnly = isset($pubOnly) ? $pubOnly : true;
    // allows you to show unpublished docs if needed for some reason...


// functions start here
function closeTags($text) { 
    $openPattern = "/<([^\/].*?)>/";   
    $closePattern = "/<\/(.*?)>/"; 
    $endOpenPattern = "/<([^\/].*?)$/"; 
    $endClosePattern = "/<(\/.*?[^>])$/"; 
    $endTags=''; 
     
    //$text=preg_replace($endOpenPattern,'',$text); 
    //$text=preg_replace($endClosePattern,'',$text); 
    preg_match_all($openPattern,$text,$openTags); 
    preg_match_all($closePattern,$text,$closeTags); 
    
    //print_r($openTags); 
    //print_r($closeTags); 
    
    $c=0; 
    $loopCounter = count($closeTags[1]);  //used to prevent an infinite loop if the html is malformed 
    while($c<count($closeTags[1]) && $loopCounter) { 
        $i=0; 
        while($i<count($openTags[1])) { 
             
            $tag = trim($openTags[1][$i]); 
             
            if(strstr($tag,' ')) { 
                $tag = substr($tag,0,strpos($tag,' '));    
            } 
            //echo $tag.'=='.$closeTags[1][$c]."\n"; 
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
   
// switch block
switch ($makerss) {
	case true:	// generate rss2xml
		$link = $modx->config['site_url'].$modx->makeUrl($modx->documentIdentifier); // url to current page
		$output .=  '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n".
		'<rss version="2.0">'."\n".
		'	<channel>'."\n".
		'		<title>'.$modx->documentObject['pagetitle'].'</title>'."\n".
		'		<link>'.$link.'</link>'."\n".
		'		<description>'.$modx->documentObject['introtext'].'</description>'."\n".
		'		<language>en</language>'."\n".
		'		<copyright>'.$copyright.'</copyright>'."\n".
		'		<ttl>'.$ttl.'</ttl>'."\n";
		
		$callby = ($showPublishedOnly)? 'getActiveChildren' : 'getAllChildren';
	
		$ds = $modx->$callby($folder, 'createdon', 'DESC', $fields='id, pagetitle, description, introtext, content, createdon, createdby');
		
		$limit=count($ds);
		if($limit>0) { 
			$limit = $topitems<$limit ? $topitems : $limit; 
			for ($i = 0; $i < $limit; $i++) { 
				
				if ($ds[$i]['createdby']<0) {
					// get web user name
					$tbl = $modx->getFullTableName("web_user_attributes");
					$sql = "SELECT fullname, email FROM $tbl WHERE $tbl.id = '".abs($ds[$i]['createdby'])."'"; 
				}
				else {
					// get manager user name
					$tbl = $modx->getFullTableName("user_attributes");
					$sql = "SELECT fullname, email  FROM $tbl WHERE $tbl.id = '".$ds[$i]['createdby']."'"; 
				}
				
    				$rs2 = $modx->dbQuery($sql);
    				$limit2 = $modx->recordCount($rs2); 
    				if($limit2<1) { 
							// get manager user name
						$btbl = $modx->getFullTableName("user_attributes");
						$bsql = "SELECT fullname, email  FROM $btbl WHERE $btbl.id = '1'"; 
						$brs = $modx->dbQuery($bsql);
						$blimit = $modx->recordCount($brs);
						$bdsuser = $modx->fetchRow($brs); 
    					$username = "".$bdsuser['fullname']." <".$bdsuser['email'].">";
								}
    				else { 
    					$dsuser = $modx->fetchRow($rs2); 
    					$username = "".$dsuser['fullname']." <".$dsuser['email'].">";
    				} 
				
		// determine and show summary
				    
		// contains the splitter and use splitter is on
if ((strstr($ds[$i]['content'], $splitter)) && $truncsplit) {
            $summary = array();
            
            // HTMLarea/XINHA encloses it in paragraph's
            $summary = explode('<p>'.$splitter.'</p>',$ds[$i]['content']);
            
            // For TinyMCE or if it isn't wrapped inside paragraph tags
            $summary = explode($splitter,$summary['0']); 

            $summary = $summary['0'];
            $summary = closeTags($summary);
            
        // fall back to the summary text    
		} else if (strlen($ds[$i]['introtext'])>0) {
			$summary = $ds[$i]['introtext'];
			
		// fall back to the summary text count	
		// skipping this because of ease of breaking in the middle of an A tag... 
		// so it's not a good idea. If you must have this, then uncomment
		// } else if(strlen($ds[$i]['content']) > $lentoshow) { 
		// 	$summary = substr($ds[$i]['content'], 0, $lentoshow).'...'; 
		//
		
		// and back to where we started if all else fails (short post)
		} else { 
			$summary = $ds[$i]['content']; 
		}  
		
		// summary is turned off
		if ($trunc == false) {
		    $summary = $ds[$i]['content']; 
	    }
				
				
				$allowedTags = '<p><br><i><em><b><strong><pre><table><th><td><tr><img><span><div><h1><h2><h3><h4><h5><font><ul><ol><li><dl><dt><dd>';
	
				// format content
				$strippedsummary = $modx->stripTags($summary,$allowedTags);
				$strippedsummary = str_replace('{{FormBlogComments}}','',$strippedsummary);

				$link = $modx->config['site_url'].$modx->makeUrl($ds[$i]['id']);
				$output .= '		<item>'."\n".
				'			<title>'.$ds[$i]['pagetitle'].'</title>'."\n".
				'			<link>'.$link.'</link>'."\n".
				'			<description>'.htmlspecialchars($strippedsummary).'</description>'."\n".
				'			<pubDate>'.date("r", $ds[$i]['createdon']).'</pubDate>'."\n".
				'			<guid>'.$link.'</guid>'."\n".
				'			<author>'.htmlspecialchars($username).'</author>'."\n".
				'			</item>'."\n";
			} 
		}
		$output .= '	</channel>'."\n".
		'</rss>';
		break;
	
	default:	// defaul mode
		$output = '';
		$title = $modx->documentObject['pagetitle'];
		$link = $modx->config['site_url'].$modx->makeUrl($linkid);
		$modx->regClientCSS('<link rel="alternate" type="application/rss+xml" title="'.$title.'" href="'.$link.'" />');
        if($showlink) $output = '<a href="'.$link.'">'.$title.'</a>';
		break;
}

return $output;