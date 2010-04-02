<?php
define('IN_MANAGER_MODE', true);
define('MODX_API_MODE', true);
$manage_path = '../../../../manager/';
include($manage_path . 'includes/config.inc.php');
include($manage_path . 'includes/document.parser.class.inc.php');
startCMSSession();
$modx = new DocumentParser;

/* only display if manager user is logged in */
if ($modx->getLoginUserType() !== 'manager') {
    // Make output a real JavaScript file!
    header('Content-type: text/javascript'); // browser will now recognize the file as a valid JS file
    
    // prevent browser from caching
    header('pragma: no-cache');
    header('expires: 0'); // i.e. contents have already expired
    
    echo "var tinyMCELinkList = new Array();";
    exit();
}

$allpages = getAllPages();
foreach($allpages as $page){
    $caption = ($page['pagetitle'])?htmlspecialchars($page['pagetitle'],ENT_QUOTES):htmlspecialchars($page['menutitle'],ENT_QUOTES);
	$list .=($list!='')?",\n":"\n";
	$list.= "[\"".$caption." (".$page['id'].")"."\", \"[\"+\"~".$page['id']."~\"+\"]\"]";
}
$output = "var tinyMCELinkList = new Array(\n". $list .");";

echo $output;


function getAllPages($id=0, $sort='menuindex', $dir='ASC', $fields='pagetitle, id, menutitle') {
	global $modx, $table_prefix;		
    
    $tblsc = $modx->getFullTableName("site_content");
    $tbldg = $modx->getFullTableName("document_groups");

    // modify field names to use sc. table reference
    $fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
    $sort = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));

    @$modx->db->query("{$GLOBALS['database_connection_method']} {$GLOBALS['database_connection_charset']}");

    $sql = "SELECT DISTINCT $fields FROM $tblsc sc
      LEFT JOIN $tbldg dg on dg.document = sc.id
      WHERE sc.published=1 AND sc.deleted=0
      ORDER BY $sort $dir;";

    $result = $modx->db->query($sql) or die('Query failed: ' . $modx->db->getLastError());
    $resourceArray = array();
    for($i=0;$i<@$modx->db->getRecordCount($result);$i++)  {
      array_push($resourceArray,$modx->db->getRow($result));
    }

    sort($resourceArray);

    return $resourceArray;
}
?>
