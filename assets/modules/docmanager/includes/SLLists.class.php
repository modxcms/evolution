<?php
/*
* This class implements a PHP wrapper around the scriptaculous javascript libraries created by
* Thomas Fuchs (http://script.aculo.us/).
*
* SLLists was created by Greg Neustaetter in 2005 and may be used for free by anyone for any purpose.  Just keep my name in here please and
* give me credit if you like, but give Thomas all the real credit!
*
* This version of the file has been customised to send all output to a variable - garryn (garry@immerse.me.uk) 
*/
class SLLists {

	var $lists = array();
	var $jsPath;
	var $debug = false;
	
	function SLLists($jsPath) {
		$this->jsPath = $jsPath;
	}
	
	function addList($list, $input, $tag = 'li', $additionalOptions = '') {
		if ($additionalOptions != '') $additionalOptions = ','.$additionalOptions;
		$this->lists[] = array("list" => $list, "input" => $input, "tag" => $tag, "additionalOptions" => $additionalOptions);
	}
	
	function printHiddenInputs() {
		$inputType = ($this->debug) ? 'text' : 'hidden';

		foreach($this->lists as $list) {
			if ($this->debug) echo '<br>'.$list['input'].': ';

			$output.="<input type=\"$inputType\" name=\"".$list['input']."\" id=\"".$list['input']."\" size=\"60\">";
		}
		if ($this->debug) $output.= '<br />';
		
		return $output;
	}

	function printForm($action, $method = 'POST', $submitText = 'Submit', $submitClass = '',$formName = 'sortableListForm') {
		$output.="
		<form action=\"$action\" method=\"$method\" onSubmit=\"populateHiddenVars();\" name=\"$formName\" id=\"$formName\">";
			$output.= $this->printHiddenInputs();
			$output.= "<input type=\"hidden\" name=\"sortableListsSubmitted\" value=\"true\">";

			if ($this->debug) {
			$output.= "<input type=\"button\" value=\"View Serialized Lists\" class=\"$submitClass\" onClick=\"populateHiddenVars();\"><br />";
			}

			$output.= "<input type=\"submit\" value=\"$submitText\" class=\"$submitClass\"></form>";
			
			return $output;
	}
	
		function printTopJS() {
		$output.="
		<script src=\"".$this->jsPath."prototype.js\" type=\"text/javascript\"></script>
		<script src=\"".$this->jsPath."scriptaculous.js\" type=\"text/javascript\"></script>
		<script type=\"text/javascript\"><!--
			function populateHiddenVars() {
			";
				foreach($this->lists as $list) {
					$output.="document.getElementById('".$list['input']."').value = Sortable.serialize('".$list['list']."')";
				}
				//return true;
			$output.="}
			//-->
		</script>";
		
		return $output;
	}

	function printBottomJs() {
		$output='
		 <script type="text/javascript">
			// <![CDATA[
			';
			foreach($this->lists as $list) {
				$output.='
				Sortable.create(\''.$list['list'].'\',{tag:\''.$list['tag'].'\''.$list['additionalOptions'].'});';
			}

			$output.='// ]]>
		 </script>';

		return $output;

	}
	
	function getOrderArray($input,$listname,$itemKeyName = 'element',$orderKeyName = 'order') {
		parse_str($input,$inputArray);
		$inputArray = $inputArray[$listname];
		$orderArray = array();
		for($i=0;$i<count($inputArray);$i++) {
			$orderArray[] = array($itemKeyName => $inputArray[$i], $orderKeyName => $i +1);
		}
		return $orderArray;
	}

}
?>
