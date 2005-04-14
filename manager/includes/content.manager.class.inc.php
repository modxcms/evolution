<?php
/*
 * MODx Content Manager Class
 * Written by Raymond Irving 2005
 *
 */

// Load Document Parser class
include_once dirname(__FILE__)."/document.parser.class.inc.php";

class ContentManager extends DocumentParser {
	
	// constructor
	function ContentManager(){
		parent::DocumentParser();
	}
	
	function RegisterEventListener($evtName,$pluginName){
		// to-do:
	}
	
	/*function getSnippetExecutionMode(){
		$m = $this->insideManager(); // install, interact	
		if ($this->Event->ActivePlugin==$this->currentSnippet) $m = "event"; // event
		else if(!$m) $m = "web";	// execute from web frontend
		return $m;		
	}*/
	

}


?>