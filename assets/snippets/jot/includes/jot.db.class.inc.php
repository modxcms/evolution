<?php
class CJotDataDb {
	var $fields = array();
	var $cfields = array();
	var $isNew;
	var $tbl = array();
	
	function CJotDataDb() {
		global $modx;
		$this->tbl["check"] = $modx->getFullTableName('jot_fields');
		$this->tbl["content"] = $modx->getFullTableName('jot_content');
		$this->tbl["subscriptions"] = $modx->getFullTableName('jot_subscriptions');
		$this->tbl["fields"] = $modx->getFullTableName('jot_fields');
		$this->cache["userpostcount"] = array();
		$this->isNew = false;
	}
	
	function Set($field, $value){
		$this->fields[$field]=$value; return true;
	}
	
	function Get($field){
		return $this->fields[$field];
	}
	
	function getFields() {
		$returnFields = $this->fields;
		$returnFields["custom"] = $this->cfields;
		return $returnFields;
	}
	
	function setCustom($field, $value){
		$this->cfields[$field] = $value; return true;
	}
	
	function getCustom($field){
		return $this->cfields[$field];
	}
	
	function FirstRun($path) {
		global $modx;
		$jot = $this->tbl["check"];
		$rs = $modx->db->query("SHOW TABLES LIKE '{$jot}'");
		$count = $modx->db->getRecordCount($rs);
		
		if ($count==0) {
			$fh = fopen($path."includes/jot.install.db.sql", 'r');
			$idata = '';
			while (!feof($fh)) {
				$idata .= fread($fh, 1024);
			}
			fclose($fh);
			$idata = str_replace("\r", '', $idata);
			$idata = str_replace('{PREFIX}',$modx->db->config['table_prefix'], $idata);
			$sql_array = explode("\n\n", $idata);
			foreach($sql_array as $sql_entry) {
				$sql_do = trim($sql_entry, "\r\n; ");
				$modx->db->query($sql_do);	
			}
		}
	}
	
	function getCustomFieldsArray($id_values) {
		global $modx;
		$custom = array();
		$tbl = $this->tbl["fields"];
		
		if (is_array($id_values)) {
			$idstring = "'" . implode("','",$id_values) . "'";
		} else {
			$idstring = "'" . $id_values . "'";
		}
		$rs = $modx->db->select('id, label, content', $tbl, "id IN ({$idstring})");
		while ($row = $modx->db->getRow($rs)) {
			$custom[$row['id']][$row['label']] = $row['content'];
		}
		return $custom;		
	}
	
	function Comment($id=0){
		global $modx;
		$this->isNew = $id == 0;
		if(!$this->isNew){
			
			// Standard Fields
			$tbl = $this->tbl["content"];
			$rs = $modx->db->select('*', $tbl, "id = '{$id}'");
			$this->fields = $modx->db->getRow($rs);
			$this->fields['id'] = $id;		
			
			// Custom Fields
			$cust = $this->getCustomFieldsArray($id);
			$this->cfields = $cust[$id];
			if (!is_array($this->cfields)) $this->cfields = array();
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
		
		$this->fields = $modx->db->escape($this->fields);
			
		if($this->isNew){

			$this->fields['id'] = $modx->db->insert($this->fields,$this->tbl["content"]);
			foreach($this->cfields as $n=>$v) { 
				$insert = array(
					'id' => $this->fields['id'],
					'label' => $n,
					'content' => $modx->db->escape($v)
				);
				$modx->db->insert($insert,$this->tbl["fields"]);
			}
			
			$this->isNew = false;
		} else {
			$id=$this->fields['id'];
			$modx->db->update($this->fields, $this->tbl["content"], "id='{$id}'");
			
			foreach($this->cfields as $n=>$v) { 
				$update = array(
					'id' => $id,
					'label' => $n,
					'content' => $modx->db->escape($v)
				);
				if (!$modx->db->update($update, $this->tbl["fields"], "id='{$id}' and label='{$update['label']}'")) $modx->db->insert($update,$this->tbl["fields"]);
			}
			
			
			
		}
	}
	
	function Delete(){
		global $modx;
		if($this->isNew) return;
		$id=$this->fields['id'];
		$modx->db->delete($this->tbl["content"],"id='{$id}'");
		$this->isNew=true;
	}
	
	function hasPosted($interval,$user) {
		global $modx;
		$chktime = strtotime("-".$interval." seconds");
		$rs = $modx->db->select('count(id)', $this->tbl["content"], "sechash = '{$user['sechash']}' AND createdon > {$chktime}");
		$returnValue = intval($modx->db->getValue($rs));
		if ($returnValue > 0 ) { return true; } else { return false; }
	}
	
	function getUserPostCount($userid, $docid,$tagid) {
		global $modx;
		$key = $userid . "&" . $docid . "&" . $tagid;
		if (array_key_exists($key, $this->cache["userpostcount"])) {
			$count = $this->cache["userpostcount"][$key];
		} else {
			$rs = $modx->db->select('count(id)', $this->tbl["content"], "createdby = '{$userid}' AND uparent = '{$docid}' AND tagid = '{$tagid}'");
			$count = intval($modx->db->getValue($rs));
			$this->cache["userpostcount"][$key] = $count;
		}
		return $count;
	}
	
	function GetCommentCount($docid,$tagid,$viewtype) {
		global $modx;
		switch ($viewtype) {
			case 2:
				$where = "published >= 0 "; // Mixed
				break;
			case 0:
				$where = "published = 0 "; // Unpublished
				break;
			case 1:
			default:
				$where = "published = 1 "; // Published
		}
		$rs = $modx->db->select('count(id)', $this->tbl["content"], "uparent = '{$docid}' AND tagid = '{$tagid}' AND ".$where);
		return intval($modx->db->getValue($rs));
	}
			
	function getOrderByDirection($dir = "a") {
		switch($dir) {
			case "d": return "desc";
			case "a":
			default:
   		return "asc"; 
		}
	}
	
	function GetComments($docid,$tagid,$viewtype,$sort,$offset,$length) {
		global $modx;
		$tbl = $this->tbl["content"];
		$where = NULL;
		if ($length > 0 ) { $limit = " limit $offset, $length"; }
		
		$orderby = " order by createdon desc ";
		$tblcustom = "";
		if (strlen($sort) > 3) {
			$orderby = array();
			$tblcustom = array();
			$obparts = explode(",", $sort);
			$c = 0;
			foreach ($obparts as $obpart) {
				$x = explode(":", $obpart);
				if($x[0]{0} == "#") {
					$c++;
					$fld = str_replace("#","",$x[0]);
					$tblcustom[] = "left join " . $this->tbl["fields"] . " as " . "c" . $c . " on c". $c . ".id = a.id and c". $c . ".label = '" . $fld . "'";
					$orderby[] = "c" . $c . ".content" . " " . $this->getOrderByDirection($x[1]);
				} else {
				$orderby[] = $x[0] . " " . $this->getOrderByDirection($x[1]);
				}
			}
			$orderby = " order by " . implode(", ",$orderby);
			$tblcustom = implode(" ",$tblcustom);
		} 

		switch ($viewtype) {
			case 2:
				$where = " and published >= 0 "; // Mixed
				break;
			case 0:
				$where = " and published = 0 "; // Unpublished
				break;
			case 1:
			default:
				$where = " and published = 1 "; // Published
		}
		$sql = "select a.* from " . $tbl . " as a " . $tblcustom . " where uparent = '" . $docid . "' and tagid = '" . $tagid ."' and mode = '0' " . $where . $orderby . $limit;
		#print $sql;
		return $this->GetCommentsArray($sql);
	}
	
	function GetCommentsArray($query) {
		global $modx;
		$rs = $modx->db->query($query);	
		$comments = array();
		$ids = array();
		while ($row = $modx->db->getRow($rs)) {
			$ids[] = $row["id"];
			$comments[] = $row;
		}
		
		$custom = $this->getCustomFieldsArray($ids);
		
		$arrComments = array();
		foreach($comments as $comment) {
			$comment["custom"] = $custom[$comment["id"]];
			$comment["userpostcount"] = $this->getUserPostCount($comment["createdby"],$comment["uparent"],$comment["tagid"]);
			$arrComments[] = $comment;
		}

		return $arrComments;
	}
	
	function hasSubscription($docid = 0,$tagid = '', $user = array()) {
		global $modx;
		$rs = $modx->db->select('count(id)', $this->tbl["subscriptions"], "userid = '{$user['id']}' AND uparent = '{$docid}' AND tagid = '{$tagid}'");
		$returnValue = intval($modx->db->getValue($rs));
		if ($returnValue > 0 ) { return true; } else { return false; }
	}
	
	
	function getSubscriptions($docid = 0,$tagid = '') {
		global $modx;
		$tbl = $this->tbl["subscriptions"];
		$rs = $modx->db->select('userid', $tbl, "uparent = '{$docid}' and tagid = '{$tagid}'");	
		$subscriptions = $modx->db->makeArray($rs);
		return $subscriptions;
	}
	
	function Subscribe($docid = 0,$tagid = '', $user = array()){
		global $modx;
		$tbl=$this->tbl["subscriptions"];
		$fields["uparent"] = $docid;
		$fields["tagid"] = $tagid;
		$fields["userid"] = $user["id"] ;
		$modx->db->insert($fields,$tbl);
	}
	
	function Unsubscribe($docid = 0,$tagid = '', $user = array()) {
		global $modx;
		$userid = $user["id"];
		$modx->db->delete($this->tbl["subscriptions"],"userid='$userid' and uparent='$docid' and tagid = '$tagid'");
	}
	
	function isValidComment($docid = 0,$tagid = '', $commentid = 0) {
		global $modx;
		$rs = $modx->db->select('count(id)', $this->tbl["content"], "id = '{$commentid}' AND uparent = '{$docid}' AND tagid = '{$tagid}'");
		return intval($modx->db->getValue($rs));
	}
	

}
?>
