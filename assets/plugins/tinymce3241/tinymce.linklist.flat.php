<?php
include_once "../../../manager/includes/config.inc.php";

$allpages = getAllPages();
foreach($allpages as $page){
    $caption = ($page['pagetitle'])?htmlspecialchars($page['pagetitle'],ENT_QUOTES):htmlspecialchars($page['menutitle'],ENT_QUOTES);
	$list .=($list!='')?",\n":"\n";
	$list.= "[\"".$caption." (".$page['id'].")"."\", \"[\"+\"~".$page['id']."~\"+\"]\"]";
}
$output = "var tinyMCELinkList = new Array(\n". $list .");";

echo $output;


function getAllPages($id=0, $sort='menuindex', $dir='ASC', $fields='pagetitle, id, menutitle') {
    global $database_type;
    global $database_server;
    global $database_user;
    global $database_password;    
	global $dbase;
	global $table_prefix;		
    
    $tblsc = $dbase.".`".$table_prefix."site_content`";
    $tbldg = $dbase.".`".$table_prefix."document_groups`";

    // modify field names to use sc. table reference
    $fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
    $sort = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));

	// Connecting, selecting database
	$link = mysql_connect($database_server, $database_user, $database_password) or die('Could not connect: ' . mysql_error());
	mysql_select_db(str_replace('`', '', $dbase)) or die('Could not select database');
	@mysql_query("{$GLOBALS['database_connection_method']} {$GLOBALS['database_connection_charset']}");

    $sql = "SELECT DISTINCT $fields FROM $tblsc sc
      LEFT JOIN $tbldg dg on dg.document = sc.id
      WHERE sc.published=1 AND sc.deleted=0
      ORDER BY $sort $dir;";

    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    $resourceArray = array();
    for($i=0;$i<@mysql_num_rows($result);$i++)  {
      array_push($resourceArray,mysql_fetch_assoc($result));
    }
	// Free resultset
	mysql_free_result($result);
	
	// Closing connection
	mysql_close($link);
	
	sort($resourceArray);

    return $resourceArray;
}
?>
