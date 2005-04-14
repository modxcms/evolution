/*
 *	NewsListing 
 *	Displays news.
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

$resource = $modx->getAllChildren($resourceparent, 'createdon', 'DESC', $fields='id, pagetitle, description, content, createdon, createdby');
$limit=count($resource);
if($limit<1) { 
   $output .= "No entries found.<br />"; 
} 
$nrblogs = $nrblogs<$limit ? $nrblogs : $limit; 
if($limit>0) { 
   for ($x = 0; $x < $nrblogs; $x++) { 
	  $tbl = $this->dbConfig['dbase'].".".$this->dbConfig['table_prefix']."manager_users";
      $sql = "SELECT username FROM $tbl WHERE $tbl.id = ".$resource[$x]['createdby']; 
      $rs2 = $modx->dbQuery($sql);
      $limit2 = $modx->recordCount($rs2); 
      if($limit2<1) { 
         $username .= "anonymous"; 
      } else { 
         $resourceuser = $modx->fetchRow($rs2); 
         $username = $resourceuser['username']; 
         // strip the content 
         if(strlen($resource[$x]['content'])>$lentoshow) { 
            $rest = substr($resource[$x]['content'], 0, $lentoshow); 
            $rest .= "...<br />&nbsp;&nbsp;&nbsp;&nbsp;<a href='[~".$resource[$x]['id']."~]'>More on this story ></a>"; 
         } else { 
            $rest = $resource[$x]['content']; 
         } 
         $output .= "<fieldset><legend>".$resource[$x]['pagetitle']."</legend>".$rest."<br /><div style='text-align:right;'>Author: <b>".$username."</b> on ".strftime("%d-%m-%y %H:%M:%S", $resource[$x]['createdon'])."</div></fieldset>"; 
      } 
   } 
} 

if($limit>$nrblogs) { 
   $output .= "<br /><br /><b>Older news</b><br />"; 
   for ($x = $nrblogs; $x < $limit; $x++) { 
      $output .= "> <a href='[~".$resource[$x]['id']."~]'>".$resource[$x]['pagetitle']."</a><br />";          
   } 
}

return $output;