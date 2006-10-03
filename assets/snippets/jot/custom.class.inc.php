<?php
class CJotFields {
	var $nodes = array();
	
	function CJotFields($string = NULL, $fields = NULL) {
		
		if (is_array($fields)) {
			if(count($fields)>0 ) {
				foreach($fields as $n=>$v) {
					$this->AddField($n,$v);
				}
			}	
		}
		
		if ($string != "") {		
			$xml = new MiniXMLDoc();
			$test = $xml->fromString($string);
			$cfields = $xml->toArray();
			$this->nodes = $cfields["custom"];
		}		
	}
	
	function AddField($name,$value) {
		$this->nodes[$name] = $value;
	}
	
	function GetField($name) {
		return $this->nodes[$name];
	}
	
	function GetFields() {
		return $this->nodes;
	}	
	
	function ToString() {
		# code for other xml methods
		return $this->ToStringNoXML();
	}
	
	function ToStringNoXML() {
		$xml = array();
		$xml[] = "<custom>";
		if(is_array($this->nodes)) {
			foreach($this->nodes as $n=>$v) { $xml[] = "<".$n.">".htmlspecialchars($v)."</".$n.">";}
		}
		$xml[] = "</custom>";
		return implode("",$xml);
	}
	
}
?>
