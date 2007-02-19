<?php

// ---------------------------------------------------
// Title: Template Class
// File: template.class.inc.php
// Functions that handle templating
// ---------------------------------------------------

class template{
	var $language,$fields,$current;

	function template() {
		$this->language = $GLOBALS["ditto_lang"];
		$this->fields = array (
			"db" => array (),
			"tv" => array (),
			"custom" => array (),
			"item" => array (),
			"qe" => array (),
			"phx" => array (),
			"rss" => array (),
			"json" => array (),
			"xml" => array (),
			"unknown" => array()
		);
	}
	function process($tpl,$tplAlt,$tplFirst,$tplLast,$tplCurrentDocument) {
		$templates['tpl'] = (!empty($tpl)) ? $this->fetch($tpl): $this->language['default_template'];
			// optional user defined chunk name to format the summary posts

		$templateNames = array (
			"tplAlt" => $tplAlt,
			"tplFirst" => $tplFirst,
			"tplLast" => $tplLast,
			"tplCurrentDocument" => $tplCurrentDocument
		);
			// template names list

		foreach ($templateNames as $name=>$tpl) {
			if(!empty($tpl)) {
				$templates[$name] = $this->fetch($tpl);
			}
		}
		$fieldList = array();
		foreach ($templates as $tplName=>$tpl) {
			$check = $this->findTemplateVars($tpl);
			if (is_array($check)) {
				$fieldList = array_merge($check, $fieldList);
			}else{
				return $check;
			}
		}

		$fieldList = array_unique($fieldList);
		$fields = $this->sortFields($fieldList);
		$checkAgain = array ("qe", "json", "xml");
		foreach ($checkAgain as $type) {
			$fields = array_merge_recursive($fields, $this->sortFields($fields[$type]));
		}
		$this->fields = $fields;
		return $templates;
	}

	function findTemplateVars($tpl) {
		preg_match_all('~\[\+(.*?)\+\]~', $tpl, $matches);
		$cnt = count($matches[1]);

		$tvnames = array ();
		for ($i = 0; $i < $cnt; $i++) {
			$match = explode(":", $matches[1][$i]);
			$tvnames[] = (strpos($matches[1][$i], "phx") === false) ? $match[0] : $matches[1][$i];
		}

		if (count($tvnames) >= 1) {
			return array_unique($tvnames);
		} else {
			return false;
		}
	}

	function sortFields ($fieldList) {
		global $ditto_constantFields;
		$dbFields = $ditto_constantFields["db"];
		$tvFields = $ditto_constantFields["tv"];
		$fields = array (
			"db" => array (),
			"tv" => array (),
			"custom" => array (),
			"item" => array (),
			"qe" => array (),
			"phx" => array (),
			"rss" => array (),
			"json" => array (),
			"xml" => array (),
			"unknown" => array()
		);
		
		$custom = array("author","tagLinks","url","title");

		foreach ($fieldList as $field) {
			if (substr($field, 0, 4) == "rss_") {
				$fields['rss'][] = substr($field,4);
			}else if (substr($field, 0, 4) == "xml_") {
				$fields['xml'][] = substr($field,4);
			}else if (substr($field, 0, 5) == "json_") {
				$fields['json'][] = substr($field,5);
			}else if (substr($field, 0, 4) == "item") {
				$fields['item'][] = substr($field, 4);
			}else if (in_array($field, $custom)) {
				$fields['custom'][] = $field;
			}else if (substr($field, 0, 1) == "#") {
				$fields['qe'][] = substr($field,1);
			}else if (substr($field, 0, 3) == "phx") {
				$fields['phx'][] = $field;
			}else if (in_array($field, $dbFields)) {
				$fields['db'][] = $field;
			}else if(in_array($field, $tvFields)){
				$fields['tv'][] = $field;
			}else if(substr($field, 0, 2) == "tv" && in_array(substr($field,2), $tvFields)) {
				$fields['tv'][] = substr($field,2);
					// TODO: Remove TV Prefix support in Ditto 2.1
			}else {
				$fields['unknown'] = $field; 
			}
		}
		return $fields;
	}
    function replace( $placeholders, $tpl ) {
		return str_replace( array_keys( $placeholders ), array_values( $placeholders ), $tpl );
	}
		
	function determine($templates,$x,$start,$stop,$id) {
		global $modx;

		// determine current template
		$currentTPL = "tpl";
		if (($x + 1) % 2) {
			$currentTPL = "tplAlt";
		}
		if ($id == $modx->documentObject['id']) {
			$currentTPL = "tplCurrentDocument";
		}
		if ($x == 0) {
			$currentTPL = "tplFirst";
		}
		if ($x == ($stop -1)) {
			$currentTPL = "tplLast";
		}
		$tpl = empty($templates[$currentTPL]) ? $templates["tpl"] : $templates[$currentTPL];
		$this->current = $currentTPL;
		return $tpl;
	}

	function fetch($tpl){
		// based on version by Doze at http://modxcms.com/forums/index.php/topic,5344.msg41096.html#msg41096
		global $modx;
		$template = "";
		if ($modx->getChunk($tpl) != "") {
			$template = $modx->getChunk($tpl);
		} else if(substr($tpl, 0, 6) == "@FILE:") {
			$template = $this->get_file_contents(substr($tpl, 6));
		} else if(substr($tpl, 0, 6) == "@CODE:") {
			$template = substr($tpl, 6);
		} else {
			$template = $this->language['missing_placeholders_tpl'];
		}
			return $template;
	}

	function get_file_contents($filename) {
		// Function written at http://www.nutt.net/2006/07/08/file_get_contents-function-for-php-4/#more-210
		// Returns the contents of file name passed
		if (!function_exists('file_get_contents')) {
			$fhandle = fopen($filename, "r");
			$fcontents = fread($fhandle, filesize($filename));
			fclose($fhandle);
		} else	{
			$fcontents = file_get_contents($filename);
		}
		return $fcontents;
	}
}

?>