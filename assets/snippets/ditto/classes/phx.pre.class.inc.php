<?php
####
#
#	Name: Chunkie
#	Version: 1.0
#	Author: Armand "bS" Pondman (apondman@zerobarrier.nl)
#	Date: Oct 8, 2006 00:00 CET
#
####

class prePHx {
 	var $template, $phx, $phxreq, $phxerror, $check;
	
	function __construct($template = '') {
		if (!class_exists("PHxParser")) include_once(strtr(realpath(dirname(__FILE__))."/phx.parser.class.inc.php", '\\', '/')); 
		$this->template = $template;
		$this->phx = new PHxParser();
		$this->phxreq = "2.0.0";
		$this->phxerror = '<div style="border: 1px solid red;font-weight: bold;margin: 10px;padding: 5px;">
			Error! This MODX installation is running an older version of the PHx plugin.<br /><br />
			Please update PHx to version '.$this->phxreq .' or higher.<br />OR - Disable the PHx plugin in the MODX Manager. (Manage Resources -> Plugins)
			</div>';
		$this->check = ($this->phx->version < $this->phxreq) ? 0 : 1;
	}

	function setPlaceholders($value = '', $key = '', $path = '') {
		$keypath = !empty($path) ? $path . "." . $key : $key;
	    if (is_array($value)) {
				foreach ($value as $subkey => $subval) {
					$this->setPlaceholders($subval, $subkey, $keypath);
					}
			} else { $this->phx->setPHxVariable($keypath, $value); }
	}
	

	function output() {
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
