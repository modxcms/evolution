<?php

/*
 * Title: Debug Class
 * Purpose:
 *  	The Debug class contains all functions relating to Ditto's 
 * 		implimentation of the MODx debug console
*/

class debug extends modxDebugConsole {
	var $debug;
	
	// ---------------------------------------------------
	// Function: render_link
	// Render the links to the debug console
	// ---------------------------------------------------
	function render_link($dittoID,$ditto_base) {
		global $ditto_lang,$modx;
		$base_url = str_replace($modx->config["base_path"],$modx->config["site_url"],$ditto_base);
		return $this->makeLink($ditto_lang["debug"],$ditto_lang["open_dbg_console"], $ditto_lang["save_dbg_console"],$base_url.'debug/',"ditto_".$dittoID);
	}
	// ---------------------------------------------------
	// Function: render_popup
	// Render the contents of the debug console
	// ---------------------------------------------------	
	function render_popup($ditto,$ditto_base,$ditto_version, $ditto_params, $IDs, $fields, $summarize, $templates, $sortBy, $sortDir, $start, $stop, $total,$filter,$resource) {
		global $ditto_lang,$modx;
		$tabs = array();
		$fields = (count($fields["db"]) > 0 && count($fields["tv"]) > 0) ? array_merge_recursive($ditto->fields,array("retrieved"=>$fields)) : $fields;
		
		$tabs[$ditto_lang["info"]] = $this->prepareBasicInfo($ditto,$ditto_version, $IDs, $summarize, $sortBy, $sortDir, $start, $stop, $total);
		$tabs[$ditto_lang["modx"]] = $this->prepareMODxInfo();
		$tabs[$ditto_lang["params"]] = $this->makeParamTable($ditto_params,$ditto_lang["params"]);
		$tabs[$ditto_lang["fields"]] = "<div class='ditto_dbg_fields'>".$this->array2table($this->cleanArray($fields), true, true)."</div>";		
		$tabs[$ditto_lang["templates"]] =  $this->makeParamTable($this->prepareTemplates($templates),$ditto_lang["templates"]);
			
		if ($filter !== false) {
			$tabs[$ditto_lang["filters"]] = $this->prepareFilters($filter);
		}

		if ($ditto->prefetch == true) {
			$tabs[$ditto_lang["prefetch_data"]] = $this->preparePrefetch($ditto->prefetch);				
		}
		if (count($resource) > 0) {
			$tabs[$ditto_lang["retrieved_data"]] = $this->prepareDocumentInfo($resource);
		}
		$base_url = str_replace($modx->config["base_path"],$modx->config["site_url"],$ditto_base);
		return $this->render($tabs,$ditto_lang['debug'],$base_url);
	}
	
	// ---------------------------------------------------
	// Function: preparePrefetch
	// Create the content of the Prefetch tab
	// ---------------------------------------------------
	function preparePrefetch($prefetch) {
		global $ditto_lang;
		if (count($prefetch["dbg_IDs_pre"]) > 0 && count($prefetch["dbg_IDs_post"]) > 0) {
			$ditto_IDs = array($ditto_lang["ditto_IDs_all"]." (".count($prefetch["dbg_IDs_pre"]).")"=>implode(", ",$prefetch["dbg_IDs_pre"]),$ditto_lang["ditto_IDs_selected"]." (".count($prefetch["dbg_IDs_post"]).")"=>implode(", ",$prefetch["dbg_IDs_post"]));
			$out = $this->array2table(array($ditto_lang["prefetch_data"]=>$ditto_IDs),true,true);
		} else {
			$out = $ditto_lang["no_documents"];
		}
		return $out.$this->prepareDocumentInfo($prefetch["dbg_resource"]);
	}

	// ---------------------------------------------------
	// Function: prepareFilters
	// Create the content of the Filters tab
	// ---------------------------------------------------
	function prepareFilters($filter) {
 		$output = "";
		foreach ($filter as $name=>$value) {
			if ($name == "custom") {
				foreach ($value as $name=>$value) {
					$output .= $this->array2table(array($name=>$value), true, true);
				}
			} else {
				$output .= $this->array2table(array($name=>$value), true, true);
			}
		}
		return $output;
	}

	// ---------------------------------------------------
	// Function: prepareMODxInfo
	// Create the content of the MODx tab
	// ---------------------------------------------------
	function prepareMODxInfo() {
		global $modx,$ditto_lang;
		$output = "";
		$ph = array();
		foreach ($modx->placeholders as $key=>$value) {
			if (strpos($key,"resource") === FALSE && strpos($key,"object") === FALSE) {
				$ph[$key] = $value;
			}
		}
		$output .= $this->makeParamTable($ph,$ditto_lang['placeholders']);
		$output .= $this->makeParamTable($modx->documentObject,$ditto_lang['document_info']);
		return $output;
	}
	
	// ---------------------------------------------------
	// Function: prepareDocumentInfo
	// Create the output for the Document Info tab
	// ---------------------------------------------------	
	function prepareDocumentInfo($resource) {
		global $ditto_lang;
		$output = "";
		if (count($resource) > 0) {
			foreach ($resource as $item) {
				$header = str_replace(array('[+pagetitle+]','[+id+]'),array($item['pagetitle'],$item['id']),$this->templates["item"]);
				$output .=  $this->makeParamTable($item,$header,true,true,true,"resource");
			}
		}
		return $output;
	}

	// ---------------------------------------------------
	// Function: prepareBasicInfo
	// Create the outut for the Info ta
	// ---------------------------------------------------
		function prepareBasicInfo($ditto,$ditto_version, $IDs, $summarize, $sortBy, $sortDir, $start, $stop, $total) {
		global $ditto_lang,$dittoID,$modx;
			$items[$ditto_lang['version']] = $ditto_version;
			$items[$ditto_lang['summarize']] = $summarize;
			$items[$ditto_lang['total']] = $total;	 
			$items[$ditto_lang['sortBy']] = ($ditto->advSort !== false) ? $ditto->advSort : $sortBy;	 
			$items[$ditto_lang['sortDir']] = $sortDir;	 
			$items[$ditto_lang['start']] = $start;	 
			$items[$ditto_lang['stop']] = $stop;	 	 
			$items[$ditto_lang['ditto_IDs']] = (count($IDs) > 0) ? wordwrap(implode(", ",$IDs),100, "<br />") : $ditto_lang['none'];
			return $this->makeParamTable($items,$ditto_lang["basic_info"],false,false);
	}

	// ---------------------------------------------------
	// Function: prepareTemplates
	// Create the output for the Templates tab
	// ---------------------------------------------------	
	function prepareTemplates($templates) {
		global $ditto_lang;
		$displayTPLs = array();
		foreach ($templates as $name=>$value) {
			switch ($name) {
				case "base":
					$displayName = "tpl";
				break;

				case "default":
					$displayName = "tpl";
				break;

				default:
					$displayName = "tpl".strtoupper($name{0}).substr($name,1);
				break;
			}
			$displayTPLs[$displayName] = $value;
		}
		return $displayTPLs;
	}

}

?>