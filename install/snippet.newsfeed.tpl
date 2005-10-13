/**
 *
 *	NewsFeed for MODx v2 Beta 2
 *	Created by Raymond Irving, August 2005
 *  Code made RSS valid and enhanced by Mark Kaplan, September-October 2005
 *
 *	Enable RSS2 news feed from your website
 *
 *	Snippet Parameters:
 * 
 *      &defaultauthor	- Default username to use when missing or not available. 
 *                          Defaults to the initial admin user account. [string] 
 *                          Example: &defaultauthor=`Admin <youremail@yoursite.com>`
 *      &lentoshow  - Truncated length of content to show. Defaults to 300. [integer]
 *
 *		&makerss	- set to 1 to generate rss xml feed. Defaults to 1
 *		
 *	(available when &makerss=1)
 *		&newsfolder	- Folder id where news items are to be stored. Example &newsfolder=`2`. If &newsfolder is missing the current document id will be used.
 *		&topitems	- set the top number of items to be listed in news feed. Defaults to 20
 *		&copyright	- set copyright information
 * 		&ttl		- set how often should feed readers check for new material (in seconds) -- mostly ignored by readers.
 *
 *	(available when &makerss=0)
 *		&showlink	- set to 1 to show feed link. Defaults to 1 
 *		&linkid		- set the document id for the rss new feed. (available when &makerss=0)
 *		&linktitle	- set the title the rss link. (available when &makerss=0)
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
$copyright = isset($copyright) ? $copyright:"";

// set ttl value
$ttl = ($ttl) ? intval($ttl):1440;

// set lentoshow
$lentoshow = isset($lentoshow) ? $lentoshow : 300;

// set link title format
$linktitle = isset($linktitle) ? $linktitle :'';

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
		$ds = $modx->getActiveChildren($folder, 'createdon', 'DESC', $fields='id, pagetitle, description, introtext, content, createdon, createdby');
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
				
								// get summary
				if(strlen($ds[$i]['introtext'])>0) {
					$summary = $ds[$i]['introtext'];
					if(strlen($ds[$i]['content'])>0) $summary .= "..."; 
				} else if(strlen($ds[$i]['content'])>$lentoshow) { 
					// strip the content 
					$summary = substr($ds[$i]['content'], 0, $lentoshow); 
					$summary .= "..."; 
				} else { 
					$summary = $ds[$i]['content']; 
				} 
				
				
				$allowedTags = '<p><br><i><em><b><strong><pre><table><th><td><tr><img><span><div><h1><h2><h3><h4><h5><font><ul><ol><li><dl><dt><dd>';
	
				// format content
				$strippedsummary = $modx->stripTags($summary,$allowedTags);

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
	
	default:	// default mode
		$output = '';
		$title =  $linktitle ? $linktitle:$modx->documentObject['pagetitle'];
		$link = $modx->config['site_url'].$modx->makeUrl($linkid);
		$modx->regClientCSS('<link rel="alternate" type="application/rss+xml" title="'.$title.'" href="'.$link.'" />');
        if($showlink) $output = '<a href="'.$link.'">'.$title.'</a>';
		break;
}

return $output;
