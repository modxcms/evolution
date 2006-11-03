<?php
/*####
#
#	Name: PHx (Placeholders Xtended)
#	Version: 1.4.4
#	Author: Armand "bS" Pondman (apondman@zerobarrier.nl)
#	Date: Oct 29, 2006 11:25 CET
#
####*/

class PHxParser {
	var $version;
	var $user;
	var $safetags;
	var $placeholders = array();
	
	function PHxParser($debug=0) {
		global $modx;
		$this->name = "PHx";
		$this->version = "1.4.4";
		$this->user["mgrid"] = intval($_SESSION['mgrInternalKey']);
		$this->user["usrid"] = intval($modx->getLoginUserID());
		$this->user["id"] = ($this->user["usrid"] > 0 ) ? (-$this->user["usrid"]) : $this->user["mgrid"];
		$this->safetags[0][0] = '~(?<![\[]|^\^)\[(?=[^\+\*\(\[\!]|$)~s';
		$this->safetags[0][1] = '~(?<=[^\+\*\)\]\!]|^)\](?=[^\]]|$)~s';
		$this->safetags[1][0] = '&_PHX_INTERNAL_091_&';
		$this->safetags[1][1] = '&_PHX_INTERNAL_093_&';
		$this->safetags[2][0] = '[';
		$this->safetags[2][1] = ']';
		$this->console = array();
		$this->showDebug = $debug;
		$modx->setPlaceholder("phx", "&_PHX_INTERNAL_&");
	}

	function OnParseDocument() {
		global $modx;
		$template = $modx->documentOutput;
		$template = $this->Parse($template);
		if ($this->showDebug) $template = $template.$this->DebugLog();
		$modx->documentOutput = $template;
	}
	
	function Parse($template='') {
		global $modx;
		$template = preg_replace($this->safetags[0],$this->safetags[1],$template);
		$template = $this->ParseValues($template);
		$template = str_replace($this->safetags[1],$this->safetags[2],$template);
		return $template;
	}
	
	function ParseValues($template='',$pass=50) {
		global $modx;
    
		// MODx Chunks
		$template = $modx->mergeChunkContent($template);
		
		// MODx Snippets
    $template = $modx->evalSnippets($template);
		
		// PHx / MODx Tags
		if ( preg_match_all('~\[(\+|\*|\()([^:\+\[\]]+)([^\[]*?)(\1|\))\]~s',$template, $matches) && $pass ) {
			
			//$matches[0] // Complete string that's need to be replaced
			//$matches[1] // Type
			//$matches[2] // The placeholder(s)
			//$matches[3] // The modifiers
			
			// Debugging
			if ($this->showDebug) {
				$cpass = (51-$pass);
				if ($this->inPass != $cpass) {
					$this->inPass = $cpass;
					$this->Log("");$this->Log("--- Pass " . (51-$pass));$this->Log("");
				}
			}
					
			$count = count($matches[0]);
			$var_search = array();
			$var_replace = array();
			for($i=0; $i<$count; $i++) {
				$match = $matches[0][$i];
				$type = $matches[1][$i];
				$input = $matches[2][$i];
				$modifiers = $matches[3][$i];
				$this->Log("Parsing variable '" . $input . "' of type '" . $type . "'");
				$var_search[] = $match;
					switch($type) {
						case "*": // Document / Template Variable
							$input = $modx->mergeDocumentContent("[*".$input."*]");
							break;
						case "(": // MODx Setting
							$input = $modx->mergeSettingsContent("[(".$input.")]");
							break;
						default:  // Placeholder / PHx
							$input = $this->getPHxVariable($input);
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
				$this->Log("  |--- Value = '". $output ."'");
				$this->Log("  |--- Modifier = '". $modifier_cmd[$i] ."'");
				if ($modifier_value[$i] != '') $this->Log("  |--- Options = '". $modifier_value[$i] ."'");
				switch ($modifier_cmd[$i]) {
					#####  Conditional Modifiers 
					case "input":	case "if": $output = $modifier_value[$i]; break;
					case "equals": case "is": $condition[] = intval(($output==$modifier_value[$i])); break;
					case "notequals": case "isnot":	case "isnt":$condition[] = intval(($output!=$modifier_value[$i]));break;
					case "isgreaterthan":	case "isgt": $condition[] = intval(($output>=$modifier_value[$i]));break;
					case "islowerthan": case "islt": $condition[] = intval(($output<=$modifier_value[$i]));break;
					case "greaterthan": case "gt": $condition[] = intval(($output>$modifier_value[$i]));break;
					case "lowerthan":	case "lt":$condition[] = intval(($output<$modifier_value[$i]));break;
					case "or":$condition[] = "||";break;
					case "and":	$condition[] = "&&";break;
					case "show": 
						$conditional = implode(' ',$condition);
						$isvalid = intval(eval("return (". $conditional. ");"));
						if (!$isvalid) { $output = NULL;}
					case "then":
						$conditional = implode(' ',$condition);
						$isvalid = intval(eval("return (". $conditional. ");"));
						if ($isvalid) { $output = $modifier_value[$i]; }
						else { $output = NULL; }
						break;
					case "else":
						$conditional = implode(' ',$condition);					
						$isvalid = intval(eval("return (". $conditional. ");"));
						if (!$isvalid) { $output = $modifier_value[$i]; }
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
					case "lcase": $output = strtolower($output); break;
					case "ucase": $output = strtoupper($output); break;
					case "ucfirst": $output = ucfirst($output); break;
					case "esc":
						$output = preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($output));
  					$output = str_replace(array("[","]","`"),array("&#91;","&#93;","&#96;"),$output);
						break;
					case "strip": $output = preg_replace("~([\n\r\t\s]+)~"," ",$output); break;
					case "notags": $output = strip_tags($output); break;
					case "length": case "len": $output = strlen($output); break;
					case "reverse": $output = strrev($output); break;
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
					case "ifempty": if (empty($output)) $output = $modifier_value[$i]; break;
				  case "nl2br": $output = nl2br($output); break;
					case "date": $output = strftime($modifier_value[$i],0+$output); break;
					case "set":
						$c = $i+1;
						if ($count>$c&&$modifier_cmd[$c]=="value") $output = preg_replace("~([^a-zA-Z0-9])~","",$modifier_value[$i]);
						break;
					case "value":
						if ($i>0&&$modifier_cmd[$i-1]=="set") { $modx->SetPlaceholder("phx.".$output,$modifier_value[$i]); }	
						$output = NULL;
						break;
					case "md5": $output = md5($output); break;
					case "userinfo":
						if ($output == "&_PHX_INTERNAL_&") $output = $this->user["id"];
						$output = $this->ModUser($output,$modifier_value[$i]);
						break;
					case "inrole":
						if ($output == "&_PHX_INTERNAL_&") $output = $this->user["id"];
						$grps = (strlen($modifier_value) > 0 ) ? explode(",",$modifier_value[$i]) :array();
						$output = intval($this->isMemberOfWebGroupByUserId($output,$grps));
						break;
					default:
						 $sql= "SELECT snippet FROM " . $modx->getFullTableName("site_snippets") . " WHERE " . $modx->getFullTableName("site_snippets") . ".name='phx:" . $modifier_cmd[$i] . "';";
             $result = $modx->dbQuery($sql);
             if ($modx->recordCount($result) == 1) {
						  $row = $modx->fetchRow($result);
							ob_start();
							$options = $modifier_value[$i];
        			$custom = eval($row["snippet"]);
    		      $msg = ob_get_contents();
							$output = $msg.$custom;
		          ob_end_clean();
						 }					
					  break;
				} 
			if (count($condition)) $this->Log("  |--- Condition = '". $condition[count($condition)-1] ."'");
			$this->Log("  |--- Output = '". $output ."'");
			}
		} 	
		return $output;
	}
	
	// Log to debug console
	function Log($string) {
		if ($this->showDebug) $this->console[] = "  #".count($this->console). " [". strftime("%H:%M:%S",time()). "] " . $string;
	}
	
	// Returns debug console
	function DebugLog() {
		if($this->console) { 
			$console = implode("\n",$this->console);
			$this->console = array();
			return '<pre style="overflow: auto;background-color: white;font-weight: bold;">   '. $this->name . " " . $this->version . "\n" . $console . '</pre>';
		}
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
        if ($name != "phx") $this->placeholders[$name] = $value;
    }

}
?>
