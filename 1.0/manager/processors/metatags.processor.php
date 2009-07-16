<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if(!$modx->hasPermission('manage_metatags')) {
	$e->setError(3);
	$e->dumpError();
}

// get op code
$opcode = isset($_POST['op']) ? $_POST['op'] : "keys" ;

// add tag
if($opcode=="addtag") {
	list($tag,$http_equiv) = explode(";",$_POST["tag"]);
	$f = array(
		name => mysql_escape_string($_POST["tagname"]),
		tag => mysql_escape_string($tag),
		tagvalue => mysql_escape_string($_POST["tagvalue"]),
		http_equiv => intval($http_equiv)
	);
	if($f["name"] && $f["tagvalue"]) {
		$modx->db->insert($f,$modx->getFullTableName("site_metatags"));
	}
}
// edit tag
else if($opcode=="edttag") {
	$id = intval($_POST["id"]);
	list($tag,$http_equiv) = explode(";",$_POST["tag"]);
	$f = array(
		name => mysql_escape_string($_POST["tagname"]),
		tag => mysql_escape_string($tag),
		tagvalue => mysql_escape_string($_POST["tagvalue"]),
		http_equiv => intval($http_equiv)
	);
	if($f["name"] && $f["tagvalue"]) {
		$modx->db->update($f,$modx->getFullTableName("site_metatags"),"id='$id'");
	}
}
// delete
else if($opcode=="deltag") {
	$f = $_POST["tag"];
	if(is_array($f) && count($f)>0) {
		for($i=0;$i<count($f);$i++) $f[$i]=mysql_escape_string($f[$i]);
		$modx->db->delete($modx->getFullTableName("site_metatags"),"id IN('".implode("','",$f)."')");
	}
}
else {
	$delete_keywords = isset($_POST['delete_keywords']) ? $_POST['delete_keywords'] : array() ;
	$orig_keywords = isset($_POST['orig_keywords']) ? $_POST['orig_keywords'] : array() ;
	$rename_keywords = isset($_POST['rename_keywords']) ? $_POST['rename_keywords'] : array() ;

	// do any renaming that has to be done
	foreach($orig_keywords as $key => $value) {
		if($rename_keywords[$key]!=$value) {
			$sql = "SELECT * FROM $dbase.`".$table_prefix."site_keywords` WHERE BINARY keyword='".addslashes($rename_keywords[$key])."'";
			$rs = mysql_query($sql);
			$limit = mysql_num_rows($rs);
			if($limit > 0) {
				echo "  - This keyword has already been defined!";
				exit;
			} else {
				$sql = "UPDATE $dbase.`".$table_prefix."site_keywords` SET keyword='".addslashes($rename_keywords[$key])."' WHERE keyword='".addslashes($value)."'";
				$rs = mysql_query($sql);
			}
		}
	}

	// delete any keywords that need to be deleted
	if(count($delete_keywords)>0) {
		$keywords_array = array();
		foreach($delete_keywords as $key => $value) {
			$keywords_array[] = $key;
		}

		$sql = "DELETE FROM $dbase.`".$table_prefix."keyword_xref` WHERE keyword_id IN(".join($keywords_array, ",").")";
		$rs = mysql_query($sql);
		if(!$rs) {
			echo "Failure on deletion of xref keys: ".mysql_error();
			exit;
		}

		$sql = "DELETE FROM $dbase.`".$table_prefix."site_keywords` WHERE id IN(".join($keywords_array, ",").")";
		$rs = mysql_query($sql);
		if(!$rs) {
			echo "Failure on deletion of keywords ".mysql_error();
			exit;
		}

	}

	// add new keyword
	if(!empty($_POST['new_keyword'])) {
		$nk = $_POST['new_keyword'];

		$sql = "SELECT * FROM $dbase.`".$table_prefix."site_keywords` WHERE keyword='".addslashes($nk)."'";
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		if($limit > 0) {
			echo "Keyword $nk already exists!";
			exit;
		} else {
			$sql = "INSERT INTO $dbase.`".$table_prefix."site_keywords` (keyword) VALUES('".addslashes($nk)."')";
			$rs = mysql_query($sql);
		}
	}
}

// empty cache
include_once "cache_sync.class.processor.php";
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(false);
$sync->emptyCache();

$header="Location: index.php?a=81";
header($header);

?>
