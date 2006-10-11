<?php
/*####
#
#	Name: PHx (Placeholders Xtended)
#	Version: 1.3.1
#	Author: Armand "bS" Pondman (apondman@zerobarrier.nl)
#	Date: Oct 07, 2006 02:00 CET
#
####*/

class PHxParser {
	var $version;
	var $safetags;
	var $placeholders = array();
	
	function PHxParser() {
		global $modx;
		$this->version = "1.3.1";
		$this->safetags[0][0] = '~(?<![\[]|^\^)\[(?=[^\+\*\(\[\!]|$)~s';
		$this->safetags[0][1] = '~(?<=[^\+\*\)\]\!]|^)\](?=[^\]]|$)~s';
		$this->safetags[1][0] = '&_PHX_INTERAL_091_&';
		$this->safetags[1][1] = '&_PHX_INTERAL_093_&';
		$this->safetags[2][0] = '[';
		$this->safetags[2][1] = ']';
		$modx->setPlaceholder("phx", "&_PHX_INTERAL_&");
	}

	function OnParseDocument() {
		global $modx;
		$template = $modx->documentOutput;
		$template = $this->Parse($template);
		$modx->documentOutput = $template;
	}
	
	function Parse($template='') {
		$template = preg_replace($this->safetags[0],$this->safetags[1],$template);
		$template = $this->ParseValues($template);
		$template = str_replace($this->safetags[1],$this->safetags[2],$template);
		return $template;
	}
	
	function ParseValues($template='',$pass=50) {
		global $modx;

		if ( preg_match_all('~\[(\+|\*|\()([^:\+\[\]]+)([^\[]*?)(\1|\))\]~s',$template, $matches) && $pass ) {
			//$matches[0] // Complete string that's need to be replaced
			//$matches[1] // Type
			//$matches[2] // The placeholder(s)
			//$matches[3] // The modifiers
			$count = count($matches[0]);
			$var_search = array();
			$var_replace = array();
			for($i=0; $i<$count; $i++) {
				$match = $matches[0][$i];
				$type = $matches[1][$i];
				$input = $matches[2][$i];
				$modifiers = $matches[3][$i];
				$var_search[] = $match;
					switch($type) {
						case "*": // Template Variable
							$input = $modx->documentObject[str_replace("#","",$input)];
							break;
						case "(": // MODx Setting
							$input = $modx->config[$input];
							#$input = str_replace(array("[","]","`"),array("&#91;","&#93;","&#96;"),$input);
							break;
						default:  // Placeholder / PHx variable
							$input = $this->getPHxVariable($input);
							#$input = str_replace(array("[","]","`"),array("&#91;","&#93;","&#96;"),$input);
							break;
					}
				$var_replace[] = $this->Filter($input,$modifiers);
			 }
			 $template = $this->ParseValues(str_replace($var_search, $var_replace, $template),$pass-1);
		}
		return $template;
	}

	function Filter($input, $modifiers) {
		global $modx;

		$output = $input;

		if (preg_match_all('~:([^:=]+)(?:=`(.*?)`(?=:[^:=]+|$))?~s',$modifiers, $matches)) {
			$modifier_cmd = $matches[1]; // modifier command
			$modifier_value = $matches[2]; // modifier value
			$count = count($modifier_cmd);
			$condition = array();
				
			for($i=0; $i<$count; $i++) {
				$output = trim($output);
				switch ($modifier_cmd[$i]) {
					#####  Conditional Modifiers 
					case "input":
					case "if":
						$output = $modifier_value[$i];
						break;
					case "equals":
					case "is":
						$condition[] = intval(($output==$modifier_value[$i]));
						break;
					case "notequals":
					case "isnot":
					case "isnt":
						$condition[] = intval(($output!=$modifier_value[$i]));
						break;
					case "isgreaterthan":
					case "isgt":
						$condition[] = intval(($output>=$modifier_value[$i]));
						break;
					case "islowerthan":
					case "islt":
						$condition[] = intval(($output<=$modifier_value[$i]));
						break;
					case "greaterthan":
					case "gt":
						$condition[] = intval(($output>$modifier_value[$i]));
						break;
					case "lowerthan":
					case "lt":
						$condition[] = intval(($output<$modifier_value[$i]));
						break;
					case "or":
						$condition[] = "||";
						break;
					case "and":
						$condition[] = "&&";
						break;
					case "then":
						$conditional = implode(' ',$condition);
						$isvalid = intval(eval("return (". $conditional. ");"));
						if ($isvalid) { $output = $modifier_value[$i]; }
						else { $output = NULL; }
						#if ($isvalid) $output .= "<br />compared : " . $conditional;
						break;
					case "else":
						$conditional = implode(' ',$condition);					
						$isvalid = intval(eval("return (". $conditional. ");"));
						if (!$isvalid) { $output = $modifier_value[$i]; }
						#if (!$isvalid) $output .= "<br />compared : " . $conditional;
						break;
					case "select":
						$raw = explode("&",$modifier_value[$i]);
						$map = array();
						for($m=0; $m<(count($raw)); $m++) {
							$mi = explode("=",$raw[$m]);
							$map[$mi[0]] = $mi[1];
						}
						$output = $map[$output];
						break;
					##### End of Conditional Modifiers
					
					#####  String Modifiers 
					case "lcase":
						$output = strtolower($output);
					case "ucase":
						$output = strtoupper($output);
					case "ucfirst":
						$output = ucfirst($output);
					case "esc":
  					$output = preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($output));
  					$output = str_replace(array("[","]","`"),array("&#91;","&#93;","&#96;"),$output);
						break;
					case "strip":
						$output = preg_replace("~([\n\r\t\s]+)~"," ",$output);
						break;
					case "notags":
						$output = strip_tags($output);
						break;
					case "length":
					case "len":
						$output = strlen($output); 
						break;
					case "reverse":
						$output = strrev($output);
						break;
					case "wordwrap": // default: 70
					  $wrapat = intval($modifier_value[$i]);
						if ($wrapat) { $output = wordwrap($output,$wrapat," ",1); }
						else { 
						$output = wordwrap($output,70," ",1);
						}
						break;
					case "limit": // default: 100
					  $limit = intval($modifier_value[$i]) ? intval($modifier_value[$i]) : 100;
						$output = substr($output,0,$limit);
						break;
														
					#####  Special functions 
					case "math":
						$filter = preg_replace("~([a-zA-Z\n\r\t\s])~","",$modifier_value[$i]);
						$filter = str_replace("?",$output,$filter);
						$output = eval("return ".$filter.";");
						break;					
					case "ifempty":
						if (empty($output)) $output = $modifier_value[$i];
						break;
				  case "nl2br":
						$output = nl2br($output);
						break;
					case "date":
						$output = strftime($modifier_value[$i],0+$output);
						break;
					case "set":
						$c = $i+1;
						if ($count>$c&&$modifier_cmd[$c]=="value") $output = preg_replace("~([^a-zA-Z0-9])~","",$modifier_value[$i]);
						break;
					case "value":
						if ($i>0&&$modifier_cmd[$i-1]=="set") { $modx->SetPlaceholder("phx.".$output,$modifier_value[$i]); }	
						$output = NULL;
						break;
					case "md5":
						$output = md5($output);
						break;
					case "userinfo":
						if ($output == "&_PHX_INTERAL_&") $output = -intval($modx->getLoginUserID());
						$output = $this->ModUser($output,$modifier_value[$i]);
						break;
					case "inrole":
						if ($output == "&_PHX_INTERAL_&") $output = intval($modx->getLoginUserID());
						$grps = (strlen($modifier_value) > 0 ) ? explode(",",$modifier_value[$i]) :array();
						$output = intval($this->isMemberOfWebGroupByUserId($output,$grps));
						break;
				}
			}
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
	 
	 function isMemberOfWebGroupByUserId($userid=0,$groupNames=array()) {
			global $modx;
			if(!is_array($groupNames)) return false;
			if (intval($userid) < 0) { $userid = -($userid); }

			if(!is_array($grpNames)) {
				$tbl = $modx->getFullTableName("webgroup_names");
				$tbl2 = $modx->getFullTableName("web_groups");
				$sql = "SELECT wgn.name FROM $tbl wgn	INNER JOIN $tbl2 wg ON wg.webgroup=wgn.id AND wg.webuser='".$userid."'";
				$grpNames = $modx->db->getColumn("name",$sql);
			}
			foreach($groupNames as $k=>$v)
				if(in_array(trim($v),$grpNames)) return true;
			return false;
	 }
	 
	
    function getPHxVariable($name) {
        global $modx;
				if (array_key_exists($name, $this->placeholders)) {
					return $this->placeholders[$name];
				} else {
					return $modx->getPlaceholder($name);
				}
    }

    function setPHxVariable($name, $value) {
        $this->placeholders[$name] = $value;
    }

}
?>
