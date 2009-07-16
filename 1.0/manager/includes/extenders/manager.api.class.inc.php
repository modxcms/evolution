<?php
/*
 * MODx Manager API Class
 * Written by Raymond Irving 2005
 *
 */

global $_PAGE; // page view state object. Usage $_PAGE['vs']['propertyname'] = $value;

// Content manager wrapper class
class ManagerAPI {
	
	var $action; // action directive

	function ManagerAPI(){
		global $action;
		$this->action = $action; // set action directive
	}
	
	function initPageViewState($id=0){
		global $_PAGE;
		$vsid = isset($_SESSION["mgrPageViewSID"]) ? $_SESSION["mgrPageViewSID"] : '';
		if($vsid!=$this->action) {
			$_SESSION["mgrPageViewSDATA"] = array(); // new view state
			$_SESSION["mgrPageViewSID"] = $id>0 ? $id:$this->action; // set id
		}
		$_PAGE['vs'] = &$_SESSION["mgrPageViewSDATA"]; // restore viewstate
	}

	// save page view state - not really necessary,
	function savePageViewState($id=0){
		$_SESSION["mgrPageViewSDATA"] = $_PAGE['vs'];
		$_SESSION["mgrPageViewSID"] = $id>0 ? $id:$this->action;
	}
	
	// check for saved form
	function hasFormValues() {
		if(isset($_SESSION["mgrFormValueId"])) {		
			if($this->action==$_SESSION["mgrFormValueId"]) {
				return true;
			}
			else {
				$this->clearSavedFormValues();
			}
		}
	}	
	// saved form post from $_POST
	function saveFormValues($id=0){
		$_SESSION["mgrFormValues"] = $_POST;
		$_SESSION["mgrFormValueId"] = $id>0 ? $id:$this->action;
	}		
	// load saved form values into $_POST
	function loadFormValues(){
		if($this->hasFormValues()) {
			$p = $_SESSION["mgrFormValues"];
			foreach($p as $k=>$v) $_POST[$k]=$v;
			$this->clearSavedFormValues();
		}
	}
	// clear form post
	function clearSavedFormValues(){
		unset($_SESSION["mgrFormValues"]);
		unset($_SESSION["mgrFormValueId"]);	
	}
	
}


?>