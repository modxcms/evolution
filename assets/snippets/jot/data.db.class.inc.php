<?php
class CJotDataDb {
	var $settings;
	var $fields;
	var $custom;
	var $isNew;
	
	function CJotDataDb() {
		global $modx;
		$this->isNew = false;
	}
	
	function Set($field, $value){
		$this->fields[$field]=$value; return true;
	}
	
	function SetCustom($field, $value){
		$this->custom->AddField($field,$value);
	}
	
	function GetCustom($field){
		return $this->custom->GetField($field);
	}
	
	function FirstRun($path) {
		global $modx;
		$jot = '%jot_content%';
		$rs = $modx->db->query("SHOW TABLES LIKE '".$jot."'");
		$count = $modx->db->getRecordCount($rs);
		
		if ($count==0) {
			$fh = fopen($path."jot.install.db.sql", 'r');
			$idata = '';
			while (!feof($fh)) {
				$idata .= fread($fh, 1024);
			}
			fclose($fh);
			$idata = str_replace("\r", '', $idata);
			$idata = str_replace('{PREFIX}',$GLOBALS['table_prefix'], $idata);
			$sql_array = split("\n\n", $idata);
			foreach($sql_array as $sql_entry) {
				$sql_do = trim($sql_entry, "\r\n; ");
				$modx->db->query($sql_do);	
			}
		}
	}
	
	function Comment($id=0){
		global $modx;
		$this->isNew = $id == 0;
		$this->custom = new CJotFields;
		if(!$this->isNew){
			$tbl = $modx->getFullTableName('jot_content');
			$rs = $modx->db->query("select * from $tbl where id = $id");
			$this->fields = $modx->db->getRow($rs);
			$this->fields['id'] = $id;
			$this->custom = new CJotFields($this->fields["customfields"],"");
		}
		else {		
			$this->fields = array(
				'title' => 'new comment',
				'tagid' => '',
				'published' => 1,
				'uparent' => 0,
				'parent' => 0,
				'flags' => '',
				'secip' => '',
				'sechash' => '',
				'content' => '',
				'customfields' => '',
				'mode' => 0,
				'createdby' => 0,
				'createdon' => 0,
				'editedby' => 0,
				'editedon' => 0,
				'deleted' => 0,
				'deletedon' => 0,
				'deletedby' => 0,
				'publishedon' => 0,
				'publishedby' => 0
		    );
		}	
	}
	
	function Save(){
		global $modx;
		$tbl=$modx->getFullTableName('jot_content');
		$this->fields['customfields'] = $this->custom->ToString();
		foreach($this->fields as $n=>$v) { $this->fields[$n] = $modx->db->escape($v);}
		if($this->isNew){
			$this->fields['id']=$modx->db->insert($this->fields,$tbl);
			$this->isNew = false;
		} else {
			$id=$this->fields['id'];
			$modx->db->update($this->fields, $tbl, "id=$id");
		}
	}
	
	function Delete(){
		global $modx;
		if($this->isNew) return;
		$id=$this->fields['id'];
		$modx->db->delete($modx->getFullTableName('jot_content'),"id=$id");
		$this->isNew=true;
	}
	
	function hasPosted($interval,$user) {
		global $modx;
		$chktime = strtotime("-".$interval." seconds");
		$sql = 'SELECT count(id) as post FROM '.$modx->getFullTableName('jot_content').' WHERE sechash = "'.$user['sechash'].'" AND createdon > '.$chktime;
		$returnValue = intval($modx->db->getValue($sql));
		if ($returnValue > 0 ) { return true; } else { return false; }
	}
	
	function GetCommentCount($docid,$tagid,$viewtype) {
		global $modx;
		switch ($viewtype) {
			case 0:
				$where = " and published = 0 "; // Unpublished
				break;
			case 1:
			default:
				$where = " and published = 1 "; // Published
		}
		$sql = 'SELECT count(id) FROM '.$modx->getFullTableName('jot_content').' WHERE uparent = '.$docid.' AND tagid = "' . $tagid .'"'.$where;
		return intval($modx->db->getValue($sql));
	}
	
	function GetComments($docid,$tagid,$viewtype,$sort,$offset,$length) {
		global $modx;
		$tbl = $modx->getFullTableName('jot_content');
		$where = NULL;
		if ($length > 0 ) { $limit = " limit $offset, $length"; }
		switch ($viewtype) {
			case 0:
				$where = " and published = 0 "; // Unpublished
				break;
			case 1:
			default:
				$where = " and published = 1 "; // Published
		}
		$sql = "select * from $tbl where uparent = $docid and tagid = '$tagid' and mode = 0 $where order by createdon desc".$limit;
		return $this->GetCommentsArray($sql,$sort,$offset,$length);
	}
	
	function GetCommentsArray($query,$sort,$offset,$length) {
		global $modx;
		$rs = $modx->db->query($query);	
		$comments = array();
		while ($row = $modx->db->getRow($rs)) {
			$comments[] = $row;
			$i = count($comments)-1;
			if ($row["customfields"] != '') {
				$num = $total_count-($i+$offset);
				$cfobj = new CJotFields($row["customfields"],"");
				$comments[$i]["custom"] = $cfobj->GetFields();
				$comments[$i]["postnumber"] = $num;
			}
		}
		return $comments;
	}
	
	function hasSubscription($docid = 0,$tagid = '', $user = array()) {
		global $modx;
		$sql = 'SELECT count(id) as subscription FROM '.$modx->getFullTableName('jot_subscriptions').' WHERE userid = "'.$user['id'].'" AND uparent = "'.$docid.'" AND tagid = "'.$tagid.'"';
		$returnValue = intval($modx->db->getValue($sql));
		if ($returnValue > 0 ) { return true; } else { return false; }
	}
	
	
	function GetSubscriptions($docid = 0,$tagid = '') {
		global $modx;
		$tbl = $modx->getFullTableName('jot_subscriptions');
		$rs = $modx->db->query("select userid from $tbl where uparent = $docid and tagid = '$tagid'");	
		$subscriptions = array();
		while ($row = $modx->db->getRow($rs)) {
			$subscriptions[] = $row;
		}
		return $subscriptions;
	}
	
	function Subscribe($docid = 0,$tagid = '', $user = array()){
		global $modx;
		$tbl=$modx->getFullTableName('jot_subscriptions');
		$fields["uparent"] = $docid;
		$fields["tagid"] = $tagid;
		$fields["userid"] = $user["id"] ;
		$modx->db->insert($fields,$tbl);
	}
	
	function Unsubscribe($docid = 0,$tagid = '', $user = array()) {
		global $modx;
		$userid = $user["id"];
		$modx->db->delete($modx->getFullTableName('jot_subscriptions'),"userid='$userid' and uparent='$docid' and tagid = '$tagid'");
	}

}
?>
