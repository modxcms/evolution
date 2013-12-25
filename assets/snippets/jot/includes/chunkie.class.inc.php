<?php
####
#
#	Name: Chunkie
#	Version: 1.0
#	Author: Armand "bS" Pondman (apondman@zerobarrier.nl)
#	Date: Oct 8, 2006 00:00 CET
#
####

class CChunkie {
 	var $template, $phx, $phxreq, $phxerror, $check;
	
	function CChunkie($template = '') {
		if (!class_exists("PHxParser")) include_once(strtr(realpath(dirname(__FILE__))."/phx.parser.class.inc.php", '\\', '/')); 
		$this->template = $this->getTemplate($template);
		$this->phx = new PHxParser();
		$this->phxreq = "2.0.0";
		$this->phxerror = '<div style="border: 1px solid red;font-weight: bold;margin: 10px;padding: 5px;">
			Error! This MODX installation is running an older version of the PHx plugin.<br /><br />
			Please update PHx to version '.$this->phxreq .' or higher.<br />OR - Disable the PHx plugin in the MODX Manager. (Manage Resources -> Plugins)
			</div>';
		$this->check = ($this->phx->version < $this->phxreq) ? 0 : 1;
	}

	function CreateVars($value = '', $key = '', $path = '') {
		$keypath = !empty($path) ? $path . "." . $key : $key;
	    if (is_array($value)) {
				foreach ($value as $subkey => $subval) {
					$this->CreateVars($subval, $subkey, $keypath);
					}
			} else { $this->phx->setPHxVariable($keypath, $value); }
	}
	
	function AddVar($name, $value) {
		if ($this->check) $this->CreateVars($value,$name);
	}
	
	function getTemplate($tpl){
		global $modx;
		$template = "";
		if ($modx->getChunk($tpl) != "") {
			$template = $modx->getChunk($tpl);
		} else if(is_file($tpl)) {
			$template = file_get_contents($tpl);
		} else {
			$template = $tpl;
		}
		return $template;
	}
	
	function Render() {
		global $modx;
		if (!$this->check) {
			$template = $this->phxerror;
		} else {
			$template = $this->phx->Parse($this->template);
		}
		return $template;
	}	
}
?>
