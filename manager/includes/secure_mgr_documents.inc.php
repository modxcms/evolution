<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

/**
 *	Secure Manager Documents
 *	This script will mark manager documents as private
 *
 *	A document will be marked as private only if a manager user group 
 *	is assigned to the document group that the document belongs to.
 *
 */

function secureMgrDocument($docid='') {
	global $modx;
		
	$modx->db->query("UPDATE ".$modx->getFullTableName("site_content")." SET privatemgr = 0 WHERE ".($docid>0 ? "id='$docid'":"privatemgr = 1"));
	$sql =  "SELECT DISTINCT sc.id 
			 FROM ".$modx->getFullTableName("site_content")." sc
			 LEFT JOIN ".$modx->getFullTableName("document_groups")." dg ON dg.document = sc.id
			 LEFT JOIN ".$modx->getFullTableName("membergroup_access")." mga ON mga.documentgroup = dg.document_group
			 WHERE ".($docid>0 ? " sc.id='$docid' AND ":"")."mga.id>0";
	$ids = $modx->db->getColumn("id",$sql);
	if(count($ids)>0) {
		$modx->db->query("UPDATE ".$modx->getFullTableName("site_content")." SET privatemgr = 1 WHERE id IN (".implode(", ",$ids).")");	
	}
}
?>