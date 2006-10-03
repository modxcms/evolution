<?php
####
#
#	Name: Chunkie
#	Version: 1.0 BETA
#	Author: Armand "bS" Pondman (apondman@zerobarrier.nl)
#	Date: Sep 23, 2006 0:00 CET
#
####

class CChunkie {
 	var $_tpl;
	var $_tplvars = array();
	var $_tplblocks = array();
	var $_ds;
	var $_de;
	var $_dtags;
	
	function CChunkie($template = '', $ds = '[+', $de = '+]') {
		$this->_tpl = $template;
		$this->_ds = $ds;
		$this->_de = $de;
		$this->_dtags = array(
			array("[*","*]","[~","~]","[[","]]","[!","!]"),
			array("&#91;*","*&#93;","&#91;~","~&#93;","&#91;&#91;","&#93;&#93;","&#91;!","!&#93;")
		);
		
	}
	
	function ClearVars() {
		$this->_tplvars = array();
	}
	
	function CreateVars($value = '', $key = '', $path = '') {
		$keypath = !empty($path) ? $path . "." . $key : $key;
	    if (is_array($value)) { 
			foreach ($value as $subkey => $subval) {
			 $this->CreateVars($subval, $subkey, $keypath);
            }
		} else { 
			$this->_tplvars[$keypath] = $value;
		}
	}
	
	function AddVar($name, $value) {
		if (is_array($value)) { 
			$this->_tplblocks[$name] = $value;
		}
		$this->CreateVars($value,$name);
	}

	function Render() {
		global $modx;
		$template = $modx->mergeDocumentContent($this->_tpl);
		$template = str_replace($this->_dtags[0], $this->_dtags[1], $template);
		$template = $this->Parse($template);
		$template = str_replace($this->_dtags[1], $this->_dtags[0], $template);
		return $template;
	}
	
	function Parse($template='') {
		$template = $this->ParseBlocks($template);
		$template = $this->ParseValues($template);
		return $template;
	}
	
	function ParseValues($template='') {
		if ( preg_match_all('~\[\+([^:\+\[\]]+)([^\[]*?)\+\]~s',$template, $matches) ) {
			//$matches[0] // Complete string that's need to be replaced
			//$matches[1] // The placeholder(s)
			//$matches[2] // The modifiers
			$count = count($matches[0]);
			$var_search = array();
			$var_replace = array();
			for($i=0; $i<$count; $i++) {
				$match = $matches[0][$i];
				$input = $matches[1][$i];
				$modifiers = $matches[2][$i];
				$var_search[] = $match;
				$var_replace[] = $this->Filter($input,$modifiers);
			 }
			 $template = $this->ParseValues(str_replace($var_search, $var_replace, $template));
		}
		return $template;
	}
	
	function ParseBlocks($template='') {
		if ( preg_match_all('~\[\+repeat:([\w\.]+)(.*?)repeat\+\]~s',$template, $matches) ) { # this patterns needs to be advanced
			#$matches[0] // Complete string that's need to be replaced
			#$matches[1] // Source
			#$matches[2] // Template
			
			$count = count($matches[0]);
			$var_search = array();
			$var_replace = array();
			
			for($i=0; $i<$count; $i++) {
				$match = $matches[0][$i];
				$source = $matches[1][$i];
				$original = $matches[2][$i];
				
				$subtemplate = array();
				$repeat = count($this->_tplblocks[$source]);
				for($e=0; $e<$repeat; $e++) {
					$tpl = str_replace("[+#","[+".$source.".".$e, $original);
					$this->_tplvars[$source.".".$e] = $e+1;
					$subtemplate[] = $tpl;
				}
				$var_search[] = $match;
				$var_replace[] = implode("",$subtemplate);
			 }
			 $template = str_replace($var_search, $var_replace, $template);
		} 
		return $template;
	}
	
	function CondIf($operator,$value1,$value2) {
		switch ($operator) {
			case "==": return ($value1==$value2); break;
			case "!=": return ($value1!=$value2); break;
			case "<": return ($value1<$value2); break;
			case ">": return ($value1>$value2); break;
			case ">=": return ($value1>=$value2); break;
			case "<=": return ($value1<=$value2); break;
			default: return false; break;
		}
		return false;
	}
	
	function Filter($input, $modifiers) {
		$output = NULL; 
		if (preg_match_all('~:([^:=]+)(?:=`(.*?)`(?=:[^:=]+|$))?~s',$modifiers, $matches)) {
			if(preg_match_all('~(if)[\s]*`([^`]*?)`[\s]*(==|!=|>|<|>=|<=)[\s]*`([^`]*?)`~s',$input, $conditional)) {
				// Conditional
				switch ($conditional[1][0]) {
					case "if":
						#$conditional[2][0] First Value
						#$conditional[3][0] Operator
						#$conditional[4][0] Second Value
						$ifResult = intval($this->CondIf($conditional[3][0],$conditional[2][0],$conditional[4][0]));
						break;
				}				
			} else {
				// Value
				$output = $this->_tplvars[$input];
				$output = str_replace(array("[","]","`"),array("&#91;","&#93;","&#96;"),$output);
			}
						
			$modifier_cmd = $matches[1]; // modifier command
			$modifier_value = $matches[2]; // modifier value
			
			$count = count($modifier_cmd);
			for($i=0; $i<$count; $i++) {
				switch ($modifier_cmd[$i]) {
					case "math":
						$filter = preg_replace("~([a-zA-Z\n\r\t\s])~","",$modifier_value[$i]);
						$filter = str_replace("?",$output,$filter);
						$output = eval("return ".$filter.";");
						break;					
					case "then":
						if ($ifResult) { $output = str_replace(array("[","]","`"),array("&#91;","&#93;","&#96;"),$modifier_value[$i]);}
						break;
					case "else":
						if (!$ifResult) { $output = str_replace(array("[","]","`"),array("&#91;","&#93;","&#96;"),$modifier_value[$i]);}
						break;
					case "ifempty":
						if (empty($output)) $output = $modifier_value[$i];
						break;
					case "nl2br":
						$output = nl2br($output);
						break;
					case "esc":
						$output = htmlspecialchars($output);
						$output = str_replace(array("&amp;#91;","&amp;#93;","&amp;#96;"),array("&#91;","&#93;","&#96;"),$output);
						break;				
					case "date":
						$output = strftime($modifier_value[$i],0+$output);
						break;
					case "md5":
						$output = md5($output);
						break;
					case "strip":
						$output = preg_replace("~([\n\r\t\s]+)~"," ",$output);
						break;
					case "userinfo":
						$output = $this->ModUser($output,$modifier_value[$i]);
						break;		
				}
			}
		} else {
			$output = $this->_tplvars[$input];
		}			
		return $output;
	}
	
	function ModUser($userid,$field) {
		global $modx;
		if (intval($userid) < 0) {
			$user = $modx->getWebUserInfo(-($userid));
		} else {
			$user = $modx->getUserInfo($userid);
		}
		return $user[$field];
	}	

}
?>
