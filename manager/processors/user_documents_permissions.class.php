<?php

class udperms{

	var $user;
	var $document;
	var $role;
	var $duplicateDoc = false;
	
	function checkPermissions() {
		
		global $table_prefix;
		global $dbase;
		global $udperms_allowroot;
		global $modx;

		$user = $this->user;
		$document = $this->document;
		$role = $this->role;

		if($role==1) {
			return true;  // administrator - grant all document permissions
		}
		
		if($document==0 && $udperms_allowroot==1) {
			return true;
		}
		
		$permissionsok = false;  // set permissions to false
		
		if($GLOBALS['use_udperms']==0 || $GLOBALS['use_udperms']=="" || !isset($GLOBALS['use_udperms'])) {
			return true; // permissions aren't in use
		}
		
		$parent = $modx->db->getValue('SELECT parent FROM '.$modx->getFullTableName('site_content').' WHERE id='.$this->document);
		if ($this->duplicateDoc==true && $parent==0 && $udperms_allowroot==0) {
			return false; // deny duplicate document at root if Allow Root is No
		}
		
		/*// get the groups this user is a member of
		$sql = "SELECT * FROM $dbase.`".$table_prefix."member_groups` WHERE $dbase.`".$table_prefix."member_groups`.member = $user;";
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		if($limit<1) {
			return false;
		}
		for($i=0; $i < $limit; $i++) {
			$row = mysql_fetch_assoc($rs);
			$membergroups[$i] = $row['user_group'];
		}

		$list = implode(",", $membergroups);

		// get the permissions for the groups this user is a member of
		$sql = "SELECT * FROM $dbase.`".$table_prefix."membergroup_access` WHERE $dbase.`".$table_prefix."membergroup_access`.membergroup IN($list);";
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		if($limit<1) {
			return false;
		}
		
		for($i=0; $i < $limit; $i++) {
			$row = mysql_fetch_assoc($rs);
			$documentgroups[$i] = $row['documentgroup'];
		}
		
		$list = implode(",", $documentgroups);

		// get the groups this user has permissions for
		$sql = "SELECT * FROM $dbase.`".$table_prefix."document_groups` WHERE $dbase.`".$table_prefix."document_groups`.document_group IN($list);";
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		if($limit<1) {
			return false;
		}
		
		for($i=0; $i < $limit; $i++) {
			$row = mysql_fetch_assoc($rs);
			if($row['document']==$document) {
				$permissionsok = true;
			}
		}*/

		// get document groups for current user
		if($_SESSION['mgrDocgroups']) {
			$docgrp = implode(",",$_SESSION['mgrDocgroups']);
		}

		/* Note:
			A document is flagged as private whenever the document group that it
			belongs to is assigned or links to a user group. In other words if 
			the document is assigned to a document group that is not yet linked 
			to a user group then that document will be made public. Documents that 
			are private to the manager users will not be private to web users if the 
			document group is not assigned to a web user group and visa versa.
		 */
		$tblsc = $dbase.".`".$table_prefix."site_content`";
		$tbldg = $dbase.".`".$table_prefix."document_groups`";
		$tbldgn = $dbase.".`".$table_prefix."documentgroup_names`";
		$sql = "SELECT DISTINCT sc.id 
				FROM $tblsc sc 
				LEFT JOIN $tbldg dg on dg.document = sc.id
				LEFT JOIN $tbldgn dgn ON dgn.id = dg.document_group
				WHERE sc.id = $document 
				AND (1='' OR NOT(dgn.private_memgroup<=>1)".(!$docgrp ? "":" OR dg.document_group IN ($docgrp)").");";
				   // ^ MySQL 4.1 will not return the correct result if this statement is removed! ???
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		if($limit==1) $permissionsok = true;
		
		return $permissionsok;
	}
}

?>