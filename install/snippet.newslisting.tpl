/*
 *	NewsListing 
 *	Displays news.
 *
 *	Modified by Raymond Irving on 19-April-2005 to use introtext field
 *
 */
 
$resourceparent = isset($newsid) ? $newsid : $modx->documentIdentifier;
         // the folder that contains blog entries 
$output = '';
         // initialise the blog variable 
$nrblogs = 3;
         // nr of blogs to show a short portion of 
$nrblogstotal = 100;
         // total nr of blogs to retrieve 
$lentoshow = 150;
         // how many characters to show of blogs 

$resource = $modx->getAllChildren($resourceparent, 'createdon', 'DESC', $fields='id, pagetitle, description, introtext, content, createdon, createdby');
$limit=count($resource);
if($limit<1) { 
   $output .= "No entries found.<br />"; 
} 
$nrblogs = $nrblogs<$limit ? $nrblogs : $limit; 
if($limit>0) { 
	for ($x = 0; $x < $nrblogs; $x++) { 
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
		$rs2 = $modx->dbQuery($sql);
		$limit2 = $modx->recordCount($rs2); 
		if($limit2<1) { 
			$username = "anonymous"; 
		}
		else { 
			$resourceuser = $modx->fetchRow($rs2); 
			$username = $resourceuser['username']; 
		} 
		// show summary
		if(strlen($resource[$x]['introtext'])>0) {
			$rest = $resource[$x]['introtext'];
			if(strlen($resource[$x]['content'])>0) $rest .= "...<br />&nbsp;&nbsp;&nbsp;&nbsp;<a href='[~".$resource[$x]['id']."~]'>More on this story ></a>"; 
		} else if(strlen($resource[$x]['content'])>$lentoshow) { 
			// strip the content 
			$rest = substr($resource[$x]['content'], 0, $lentoshow); 
			$rest .= "...<br />&nbsp;&nbsp;&nbsp;&nbsp;<a href='[~".$resource[$x]['id']."~]'>More on this story ></a>"; 
		} else { 
			$rest = $resource[$x]['content']; 
		}  
		$output .= "<fieldset><legend>".$resource[$x]['pagetitle']."</legend>".$rest."<br /><div style='text-align:right;'>Author: <b>".$username."</b> on ".strftime("%d-%b-%y %H:%M:%S", $resource[$x]['createdon'])."</div></fieldset>"; 
	} 
} 

if($limit>$nrblogs) { 
   $output .= "<br /><br /><b>Older news</b><br />"; 
   for ($x = $nrblogs; $x < $limit; $x++) { 
      $output .= "> <a href='[~".$resource[$x]['id']."~]'>".$resource[$x]['pagetitle']."</a><br />";          
   } 
}

return $output;