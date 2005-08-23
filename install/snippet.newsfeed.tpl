/**
 *
 *	NewsFeed for MODx 
 *	Created by Raymond Irving, August 2005
 *
 *	Enable RSS2 news feed from your website
 *
 *	Parameters:
 *		&makerss	- set to 1 to generate rss xml feed. Defaults to 0
 *
 *	(available when &makerss=1)
 *		&newsfolder	- Folder id where news items are to be stored. Example &newsfolder=`2`. If &newsfolder is missing the current document id will be used.
 *		&topitems	- set the top number of items to be listed in news feed. Defaults to 20
 *		&copyright	- set copyright information
 *		&dateformat	- set php date format for news items
 * 		&ttl		- set how often should feed readers check for new material (in seconds) -- mostly ignored by readers.
 *
 *	(available when &makerss=0)
 *		&showlink	- set to 1 to show feed link. Defaults to 1 
 *		&linkid		- set the document id for the rss new feed. (available when &makerss=0)
 */
 
// get folder id where we should look for news else look in current document
$folder = isset($newsfolder) ? intval($newsfolder):$modx->documentIdentifier;


// get current document id
$docid = $modx->documentIdentifier;

// set subscribe mode
$makerss = isset($makerss) ? $makerss:0;

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

// set date format
$dateformat = isset($dateformat) ? $dateformat : '%d-%b-%y %H:%M:%S';

// switch block
switch ($makerss) {
	case true:	// generate rss2xml
		$link = $modx->config['site_url'].$modx->makeUrl($modx->documentIdentifier); // url to current page
		$output .=  '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n".
		'<rss version="2.0" xmlns="http://backend.userland.com/rss2">'."\n".
		'	<channel>'."\n".
		'		<title>'.$modx->documentObject['pagetitle'].'</title>'."\n".
		'		<link>'.$link.'</link>'."\n".
		'		<description>'.$modx->documentObject['introtext'].'</description>'."\n".
		'		<language>en</language>'."\n".
		'		<copyright>'.$copyright.'</copyright>'."\n".
		'		<ttl>'.$ttl.'</ttl>'."\n";
		$ds = $modx->getAllChildren($folder, 'createdon', 'DESC', $fields='id, pagetitle, description, introtext, content, createdon, createdby');
		$limit=count($ds);
		if($limit>0) { 
			$limit = $topitems<$limit ? $topitems : $limit; 
			for ($i = 0; $i < $limit; $i++) { 
				if ($ds[$i]['createdby']<0) {
					// get web user name
					$tbl = $modx->getFullTableName("web_users");
					$sql = "SELECT username FROM $tbl WHERE $tbl.id = '".abs($ds[$i]['createdby'])."'"; 
				}
				else {
					// get manager user name
					$tbl = $modx->getFullTableName("manager_users");
					$sql = "SELECT username FROM $tbl WHERE $tbl.id = '".$ds[$i]['createdby']."'"; 
				}
				$rs2 = $modx->dbQuery($sql);
				$limit2 = $modx->recordCount($rs2); 
				if($limit2<1) { 
					$username = "anonymous"; 
				}
				else { 
					$dsuser = $modx->fetchRow($rs2); 
					$username = $dsuser['username']; 
				} 
				// get summary
				if(strlen($ds[$i]['introtext'])>0) {
					$summary = $ds[$i]['introtext'];
					if(strlen($ds[$i]['content'])>0) $summary .= "...<br />&nbsp;&nbsp;&nbsp;&nbsp;<a href='[~".$ds[$i]['id']."~]'>More on this story ></a>"; 
				} else if(strlen($ds[$i]['content'])>$lentoshow) { 
					// strip the content 
					$summary = substr($ds[$i]['content'], 0, $lentoshow); 
					$summary .= "...<br />&nbsp;&nbsp;&nbsp;&nbsp;<a href='[~".$ds[$i]['id']."~]'>More on this story ></a>"; 
				} else { 
					$summary = $ds[$i]['content']; 
				}  
				
				$link = $modx->config['site_url'].$modx->makeUrl($ds[$i]['id']);
				$output .= '		<item>'."\n".
				'			<title>'.$ds[$i]['pagetitle'].'</title>'."\n".
				'			<link>'.$link.'</link>'."\n".
				'			<description>'.htmlspecialchars($summary).'</description>'."\n".
				'			<pubDate>'.strftime($dateformat, $ds[$i]['createdon']).'</pubDate>'."\n".
				'			<guid>'.$link.'</guid>'."\n".
				'			<author>'.htmlspecialchars($username).'</author>'."\n".
				'		</item>'."\n";
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
