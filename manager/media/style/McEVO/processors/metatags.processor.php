<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('manage_metatags')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

// get op code
$opcode = isset($_POST['op']) ? $_POST['op'] : "keys" ;

// add tag
if($opcode=="addtag") {
	list($tag,$http_equiv) = explode(";",$_POST["tag"]);
	$f = array(
		'name' => $modx->db->escape($_POST["tagname"]),
		'tag' => $modx->db->escape($tag),
		'tagvalue' => $modx->db->escape($_POST["tagvalue"]),
		'http_equiv' => intval($http_equiv)
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
		'name' => $modx->db->escape($_POST["tagname"]),
		'tag' => $modx->db->escape($tag),
		'tagvalue' => $modx->db->escape($_POST["tagvalue"]),
		'http_equiv' => intval($http_equiv)
	);
	if($f["name"] && $f["tagvalue"]) {
		$modx->db->update($f,$modx->getFullTableName("site_metatags"),"id='{$id}'");
	}
}
// delete
else if($opcode=="deltag") {
	$f = $_POST["tag"];
	if(is_array($f) && count($f)>0) {
		$f = $modx->db->escape($f);
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
			$rs = $modx->db->select('count(*)', $modx->getFullTableName('site_keywords'), "BINARY keyword='".$modx->db->escape($rename_keywords[$key])."'");
			$limit = $modx->db->getValue($rs);
			if($limit > 0) {
				$modx->webAlertAndQuit("Keyword '{$rename_keywords[$key]}' already been defined!");
			} else {
				$modx->db->update(
					array(
						'keyword' => $modx->db->escape($rename_keywords[$key]),
					), $modx->getFullTableName('site_keywords'), "keyword='".$modx->db->escape($value)."'");
			}
		}
	}

	// delete any keywords that need to be deleted
	if(count($delete_keywords)>0) {
		$keywords_array = array_keys($delete_keywords);

		$modx->db->delete($modx->getFullTableName('keyword_xref'), "keyword_id IN(".implode(",", $keywords_array).")");

		$modx->db->delete($modx->getFullTableName('site_keywords'), "id IN(".implode(",", $keywords_array).")");

	}

	// add new keyword
	if(!empty($_POST['new_keyword'])) {
		$nk = $_POST['new_keyword'];

		$rs = $modx->db->select('count(*)', $modx->getFullTableName('site_keywords'), "keyword='".$modx->db->escape($nk)."'");
		$limit = $modx->db->getValue($rs);
		if($limit > 0) {
			$modx->webAlertAndQuit("Keyword '{$nk}' already exists!");
		} else {
			$modx->db->insert(
				array(
					'keyword' => $modx->db->escape($nk),
				), $modx->getFullTableName('site_keywords'));
		}
	}
}

// empty cache
$modx->clearCache('full');

$header="Location: index.php?a=81";
header($header);
?>