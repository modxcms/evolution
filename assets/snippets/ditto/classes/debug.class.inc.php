<?php

/*
 * Title: Debug Class
 * Purpose:
 *  	The Debug class contains all functions relating to Ditto's 
 * 		implimentation of the MODX debug console
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
	function render_popup($ditto,$ditto_base,$ditto_version, $ditto_params, $IDs, $fields, $summarize, $templates, $orderBy, $start, $stop, $total,$filter,$resource) {
		global $ditto_lang,$modx;
		$tabs = array();
		$fields = (count($fields["db"]) > 0 && count($fields["tv"]) > 0) ? array_merge_recursive($ditto->fields,array("retrieved"=>$fields)) : $ditto->fields;
		
		$tabs[$ditto_lang["info"]] = $this->prepareBasicInfo($ditto,$ditto_version, $IDs, $summarize, $orderBy, $start, $stop, $total);
		$tabs[$ditto_lang["params"]] = $this->makeParamTable($ditto_params,$ditto_lang["params"]);
		$tabs[$ditto_lang["fields"]] = "<div class='ditto_dbg_fields'>".$this->array2table($this->cleanArray($fields), true, true)."</div>";		
		$tabs[$ditto_lang["templates"]] =  $this->makeParamTable($this->prepareTemplates($templates),$ditto_lang["templates"]);
			
		if ($filter !== false) {
			$tabs[$ditto_lang["filters"]] = $this->prepareFilters($this->cleanArray($filter));
		}

		if ($ditto->prefetch == true) {
			$tabs[$ditto_lang["prefetch_data"]] = $this->preparePrefetch($ditto->prefetch);				
		}
		if (count($resource) > 0 && $resource) {
			$tabs[$ditto_lang["retrieved_data"]] = $this->prepareDocumentInfo($resource);
		}
		$base_url = str_replace($modx->config["base_path"],$modx->config["site_url"],$ditto_base);
		$generatedOn = "\r\n\r\n".'<!--- '.strftime('%c').' --->';
		return $this->render($tabs,$ditto_lang['debug'],$base_url).$generatedOn;
	}
	
	// ---------------------------------------------------
	// Function: preparePrefetch
	// Create the content of the Prefetch tab
	// ---------------------------------------------------
	function preparePrefetch($prefetch) {
		global $ditto_lang;
		$ditto_IDs = array();
		if (count($prefetch["dbg_IDs_pre"]) > 0) {
			$ditto_IDs[$ditto_lang["ditto_IDs_all"]." (".count($prefetch["dbg_IDs_pre"]).")"] = implode(",",$prefetch["dbg_IDs_pre"]);
		}
		if (count($prefetch["dbg_IDs_post"]) > 0) {
			$ditto_IDs[$ditto_lang["ditto_IDs_selected"]." (".count($prefetch["dbg_IDs_post"]).")"] = implode(", ",$prefetch["dbg_IDs_post"]);
		} else {
			$ditto_IDs[$ditto_lang["ditto_IDs_selected"]." (0)"] = strip_tags($ditto_lang["no_documents"]);
		}
		$out = $this->array2table(array($ditto_lang["prefetch_data"]=>$ditto_IDs),true,true);
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
		function prepareBasicInfo($ditto,$ditto_version, $IDs, $summarize, $orderBy, $start, $stop, $total) {
		global $ditto_lang,$dittoID,$modx;
			$items[$ditto_lang['version']] = $ditto_version;
			$items[$ditto_lang['summarize']] = $summarize;
			$items[$ditto_lang['total']] = $total;	 
			$items[$ditto_lang['start']] = $start;	 
			$items[$ditto_lang['stop']] = $stop;	 	 
			$items[$ditto_lang['ditto_IDs']] = (count($IDs) > 0) ? wordwrap(implode(", ",$IDs),100, "<br />") : $ditto_lang['none'];
			$output = '';
			if (is_array($orderBy['parsed']) && count($orderBy['parsed']) > 0) {
				$sort = array();
				foreach ($orderBy['parsed'] as $key=>$value) {
					$sort[$key] = array($ditto_lang['sortBy']=>$value[0],$ditto_lang['sortDir']=>$value[1]);
				}
				$output = $this->array2table($this->cleanArray($sort), true, true);	
			}
			return $this->makeParamTable($items,$ditto_lang["basic_info"],false,false).$output;
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