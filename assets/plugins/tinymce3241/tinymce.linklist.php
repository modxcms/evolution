<?php

// Sorted Link List for TinyMCE
// v1.0.3
// By NCrossland
//
// Changelog:
// 1.0: First release
// 1.0.1: Update to fix broken accented characters (thanks davidm)
// 1.0.2: Added choice of breadcrumbs or tree-style display (thanks raum). Setting for charset (thanks mmjaeger)
// 1.0.3: Added a choice of tree indent styles (thanks raum). Added ability to sort by menuindex and improved avoiding showing unpublished documents. 
//
// To do:
// * Based on Modx DB API, rather than raw SQL
// * If can't do the above, use less database queries
// * Make interface prettier -- proper icons for documents and file paths. Maybe a select element isn't best for this?!
// * Make options available in manager
// * integrate into TinyMCE plugin release
//
// Installation:
// Replace the contents of tinymce.linklist.php with this file. Done!

// Config options
$templates_to_ignore = array();	// Template IDs to ignore from the link list
$include_page_ids = false;
$charset = 'UTF-8';
$mode = 'tree'; // breadcrumbs or tree
$tree_style = '1'; // What style should the tree use? Choose 1,2,3 or 4
$sortby = 'menuindex'; // Could be menuindex or menutitle
$path_to_modx_config = '../../../manager/includes/config.inc.php';


/* That's it to config! */
$tree_styles = array("|--", "&#38;#x2516;&#38;#x2500;&nbsp;", "&#38;#x25B9;&nbsp;&nbsp;", "L&nbsp;&nbsp;");

include_once($path_to_modx_config);

$allpages = getAllPages();
if (!is_array($allpages) ) {
	die();
}

$list = array();

foreach($allpages as $page){
	if (!in_array($page['template'], $templates_to_ignore) )   {
			$caption = '';
			$page['parents'] = array_reverse($page['parents']);
			$breadcrumbs = array();
			$sortcrumbs = array();
			$published = $page['published'];
			foreach ($page['parents'] as $parent) {
				$p = getPage($parent);
				
				// Assemble what will be displayed
				$breadcrumbs[] = ($p['menutitle'])?htmlentities($p['menutitle'],ENT_QUOTES,$charset):htmlentities($p['pagetitle'],ENT_QUOTES,$charset);
				
				// How will it be sorted?
				if ($sortby == 'menuindex') {
					$more_sortby_types = array("menutitle","pagetitle");
					foreach ($more_sortby_types as $backup_sort_type) {
							if ( $page[$backup_sort_type] != '') {
							$sortcrumbs[] = sprintf("%010d", $p[$sortby]);
							break;
							}
					}
				} else {
					$sortcrumbs[] = $p[$sortby];
				}
				
				if ($p['published'] != '1') {
					$published = 0;
				}
				
			}
			if ($mode=='tree') {	// tree mode
				$bc_count = count($breadcrumbs);
				if ($bc_count>1) {
					$caption = str_repeat('&nbsp;', ($bc_count-1)*3);
					$caption .= $tree_styles[$tree_style-1];
					$caption .= $breadcrumbs[$bc_count-1];
				} else {
					$caption = $breadcrumbs[0];
				}
				
			} else {	// breadcrumb mode
				$caption = implode(': ', $breadcrumbs);
			}
			
			$keyname = implode('-', $sortcrumbs);
			
			// Check for duplicates
			while (isset($list[$keyname])) {
				$sortcrumbs[count($sortcrumbs)-1] += 1000000000;
				$keyname = implode('-', $sortcrumbs);
			}
			
			//$caption = $keyname;

if (function_exists('mb_encode_numericentity'))
	{
		$convmap = array(0x0080, 0xffff, 0, 0xffff);
		$encoding = $GLOBALS['database_connection_charset'];
		$caption = mb_encode_numericentity($caption, $convmap, $encoding);
	}
			$output = "[\"".$caption;
			if ($include_page_ids) {
				$output .= " (".$page['id'].")";
			}
			$output .= "\", \"[\"+\"~".$page['id']."~\"+\"]\"]";
			
			if ($published == '1') {
				$list[$keyname] = $output;
			}
			
	}
}

// Sort the list by it's keys
ksort($list);

// Output the array separated by commas
$list_output = implode(", \n", $list);

// Output as javascript
$output = "var tinyMCELinkList = new Array(\n". $list_output .");";

echo $output;




function getAllPages($id=0, $sort='parent', $dir='ASC', $fields='pagetitle, id, menutitle, parent, template, menuindex, published') {

	global $dbase;
	global $table_prefix;	

    $tblsc = $dbase.".`".$table_prefix."site_content`";
    $tbldg = $dbase.".`".$table_prefix."document_groups`";

    // modify field names to use sc. table reference
    $fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
    $sort = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));

    $sql = "SELECT DISTINCT $fields FROM $tblsc sc
      LEFT JOIN $tbldg dg on dg.document = sc.id
      WHERE sc.published=1 AND sc.deleted=0
      ORDER BY $sort $dir;";
	  
	$resourceArray = doSql($sql);
    for($i=0;$i<@count($resourceArray);$i++)  {
		$p = getAllParents($resourceArray[$i]['id']);
		$resourceArray[$i]['parents'] = $p;
    }

    return $resourceArray;
}


function getAllParents($doc_id) {
	$return_array = array($doc_id);
	while (getParent($doc_id) != 0) {
		$doc_id = getParent($doc_id);
		$return_array[] = $doc_id;
	} 
	return $return_array;
}

function getParent($doc_id) {
	$r = getPage($doc_id);
	return $r['parent'];
}

function getPage($doc_id) {
	global $dbase;
	global $table_prefix;	
	
	global $page_cache;
	
	// If already cached, return this instead of doing another MySQL query
	if (isset($page_cache[$doc_id])) {
		return $page_cache[$doc_id];
	}
	

    $tblsc = $dbase.".".$table_prefix."site_content";
    $tbldg = $dbase.".".$table_prefix."document_groups";

    // modify field names to use sc. table reference
    $fields = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$fields)));
    $sort = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));

    $sql = "SELECT sc.parent, sc.menutitle, sc.pagetitle, sc.menuindex, sc.published FROM $tblsc sc
      LEFT JOIN $tbldg dg on dg.document = sc.id
      WHERE sc.published=1 AND sc.deleted=0 AND sc.id=$doc_id;";
	  
	$resourceArray = doSql($sql);
	
	// If we have got this far, it must not have been cached already, so lets do it now.
	$page_cache[$doc_id] = $resourceArray[0];

    return $resourceArray[0];
}


function doSql($sql) {
    global $database_type;
    global $database_server;
    global $database_user;
    global $database_password;    
	global $dbase;
	global $table_prefix;	

	// Connecting, selecting database
	$link = mysql_connect($database_server, $database_user, $database_password) or die('Could not connect: ' . mysql_error());
	mysql_select_db(str_replace('`', '', $dbase)) or die('Could not select database');
	@mysql_query("{$GLOBALS['database_connection_method']} {$GLOBALS['database_connection_charset']}");

    $result = mysql_query($sql) or die('Query failed: ' . mysql_error() . ' / '. $sql);
    $resourceArray = array();
    for($i=0;$i<@mysql_num_rows($result);$i++)  {
	  $par = mysql_fetch_assoc($result);
      array_push($resourceArray, $par);
    }
	// Free resultset
	mysql_free_result($result);
	
	// Closing connection
	mysql_close($link);
	
    return $resourceArray;

}

?>