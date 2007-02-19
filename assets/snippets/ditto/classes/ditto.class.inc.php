<?php

/*
 * Title: Ditto Class
 * Desc: Aggregates documents to create blogs, article/news
 * 		 collections, etc.,with full support for templating.
 * Author: Mark Kaplan
 * Version: 2.0 RC1
 */

class ditto {
	var $template,$resource,$format,$debug,$advSort,$header,$footer,$fields,$constantFields,$prefetch,$sortOrder;

	function ditto($dittoID,$format,$header,$footer,$language,$debug) {
		$this->format = $format;
		$GLOBALS["ditto_lang"] = $language;
		$this->prefetch = false;
		$this->advSort = false;
		$this->header = $header;
		$this->footer = $footer;
		$this->constantFields[] = array("db","tv");
		$this->constantFields["db"] = array("id","type","contentType","pagetitle","longtitle","description","alias","link_attributes","published","pub_date","unpub_date","parent","isfolder","introtext","content","richtext","template","menuindex","searchable","cacheable","createdby","createdon","editedby","editedon","deleted","deletedon","deletedby","publishedon","publishedby","menutitle","donthit","haskeywords","hasmetatags","privateweb","privatemgr","content_dispo","hidemenu");
		$this->constantFields["tv"] = $this->getTVList();
		$GLOBALS["ditto_constantFields"] = $this->constantFields;
		$this->fields = array("display"=>array(),"backend"=>array("tv"=>array(),"db"=>array("id", "published")));
		$this->template = new template();
		$this->sortOrder = false;
		if ($debug == 1) {$this->debug = new debug();}
	}

	// ---------------------------------------------------
	// Function: getTVList
	// Get a list of all available TVs
	// ---------------------------------------------------
		
	function getTVList() {
		global $modx;
		$table = $modx->getFullTableName("site_tmplvars");
		$tvs = $modx->db->select("name", $table);
			// TODO: make it so that it only pulls those that apply to the current template
		$dbfields = array();
		while ($dbfield = $modx->db->getRow($tvs))
			$dbfields[] = $dbfield['name'];
		return $dbfields;
	}
	
	// ---------------------------------------------------
	// Function: addField
	// Add a field to the internal field detection system
	// ---------------------------------------------------
	
	function addField($name,$location,$type=false) {
		if ($type === false) {
			$type = $this->getDocVarType($name);
		}
		if ($type == "tv:prefix") {
			$type = "tv";
			$name = substr($name, 2);
		}
		if ($location == "*") {
			$this->fields["backend"][$type][] = $name;
			$this->fields["display"][$type][] = $name;
		} else {
			$this->fields[$location][$type][] = $name;
		}
	}
	
	// ---------------------------------------------------
	// Function: setDisplayFields
	// Move the detected fields into the Ditto fields array
	// ---------------------------------------------------
	
	function setDisplayFields($fields) {
		$this->fields["display"] = $fields;
		if (count($this->fields["display"]['qe']) > 0) {
			$this->addField("pagetitle","display","db");
		}
	}

	// ---------------------------------------------------
	// Function: getDocVarType
	// Determine if the provided field is a tv, a database field, or something else
	// ---------------------------------------------------
	
	function getDocVarType($field) {
		global $ditto_constantFields;
		$tvFields = $ditto_constantFields["tv"];
		$dbFields = $ditto_constantFields["db"];
		if(in_array($field, $tvFields)){
			return "tv";
		}else if(in_array(substr($field,2), $tvFields)) {
			return "tv:prefix";
				// TODO: Remove TV Prefix support
		} else if(in_array($field, $dbFields)){
			return "db";
		} else {
			return "unknown";
		}
	}
	
	// ---------------------------------------------------
	// Function: parseSort
	// Parse out the sort
	// ---------------------------------------------------

	function parseSort($sortBy, $randomize) {
		global $modx;

		if ($randomize == true) {return "id";}
			// if randomize is enabled, forget everything else!
		$advSort = array ("pub_date","unpub_date","editedon","deletedon","publishedon");
			// adv sort fields taht will require custom resets
					
		$type = $this->getDocVarType($sortBy);
		$sort = "id";		

		switch($type) {
			case "tv:prefix":
				$this->advSort = substr($sortBy, 2);
			break;
			case "tv":
				$this->advSort = $sortBy;
			break;
			case "db":
				if (!in_array($sortBy, $advSort)) {
					$sort = $sortBy;
				} else {
					$sort = "createdon";
				}
			break;
		}
		if ($this->advSort !== false) {$this->addField($this->advSort,"backend");}
		$this->addField($sort,"backend");
		return $sort;
	}
	
	// ---------------------------------------------------
	// Function: parseFilters
	// Split up the filters into an array and add the required fields to the fields array
	// ---------------------------------------------------

	function parseFilters($filter,$globalDelimiter,$localDelimiter,$cFilters=false) {
		$parsedFilters = array("basic"=>array(),"custom"=>array());
		$filters = explode($globalDelimiter, $filter);
		foreach ($filters AS $filter) {
			if (!empty($filter)) {
				$filterArray = explode($localDelimiter, $filter);
				$source = $filterArray[0];
				$this->addField($source,"backend");
				$value = $filterArray[1];
				$mode = (isset ($filterArray[2])) ? $filterArray[2] : 1;
				$parsedFilters["basic"][] = array("source"=>$source,"value"=>$value,"mode"=>$mode);
			}
		}
		if ($cFilters) {
			foreach ($cFilters as $name=>$value) {
				if (!empty($name) && !empty($value)) {
					$parsedFilters["custom"][$name] = $value;
				}
			}
		}
		return $parsedFilters;
	}

	// ---------------------------------------------------
	// Function: render
	// Render the document output
	// ---------------------------------------------------
	
	function render($resource, $template, $removeChunk,$ph=array(),$phx) {
		global $modx,$ditto_lang;

		if (!is_array($resource)) {
			return $ditto_lang["resource_array_error"];
		}
		$placeholders = array();
		foreach ($resource as $name=>$value) {
			$placeholders["$name"] = $value;
		}

		// set author placeholder
		if (in_array("author",$this->fields["display"]["custom"])) {
			$placeholders['author'] = $this->getAuthor($resource['createdby']);		
		}

		// set title placeholder
		if (in_array("title",$this->fields["display"]["custom"])) {
			$placeholders['title'] = $resource['pagetitle'];
		}

		// set url placeholder
		if (in_array("url",$this->fields["display"]["custom"])) {
			$placeholders['url'] = substr($modx->config['site_url'], 0, -1).$modx->makeURL($resource['id']);
		}

		if (count($this->fields["display"]['qe']) > 0) {
			$placeholders = $this->renderQELinks($this->template->fields['qe'],$resource,$ditto_lang["edit"]." : ".$resource['pagetitle']." : ",$placeholders);
				// set QE Placeholders
		}			
		// set custom placeholder
		foreach ($ph as $name=>$value) {
			if ($name != "*") {
				$placeholders[$name] = call_user_func($value[1],$resource);
			} else {
				$placeholders = call_user_func($value,$placeholders);
			}
		}
				
		if (!$this->debug) {
			if ($phx == 1) {
				$phx = new prePHx($template);
				$phx->CreateVars($placeholders);
				$output = $phx->Render();
			} else {
			 	$output = $this->str_replace_phx($placeholders,$template);
			}
			if ($removeChunk) {
				foreach ($removeChunk as $chunk) {
					$output = str_replace('{{'.$chunk.'}}',"",$output);
					$output = str_replace($modx->getChunk($chunk),"",$output);
						// remove chunk that is not wanted			
				}
			}
		} else {
			$output = $this->debug->content($resource, $placeholders,$this->template->current,$template);
		}

		return $output;
	}

    function str_replace_phx( $placeholders, $tpl ) {
		$phs = array();
		foreach ($placeholders as $ph=>$value) {
			$phs["[+$ph+]"] = $value;
		}
		return str_replace( array_keys( $phs ), array_values( $phs ), $tpl );
	}
	
	// ---------------------------------------------------
	// Function: parseFields
	// Find the fields that are contained in the custom placeholders or those that are needed in other functions
	// ---------------------------------------------------
	
	function parseFields($placeholders,$seeThruUnpub,$customReset) {
		$this->parseCustomPlaceholders($placeholders);
		$this->parseDBFields($seeThruUnpub,$customReset);
		$this->addField("id","display","db");
		$this->addField("pagetitle","display","db");
		$this->fields = $this->arrayUnique($this->fields);
	}


	// ---------------------------------------------------
	// Function: arrayUnique
	// Make fields array unique
	// ---------------------------------------------------
		
	function arrayUnique($array) {
		foreach($array as $u => $a) {
			foreach ($a as $n => $b) {
				$array[$u][$n] = array_unique($b);
			}
		}
		return $array;
	}
	  	
	// ---------------------------------------------------
	// Function: parseCustomPlaceholders
	// Parse the required fields out of the custom placeholders
	// ---------------------------------------------------
	
	function parseCustomPlaceholders($placeholders) {
		foreach ($placeholders as $name=>$value) {
			if(is_array($value[0])) {
				if(strpos($value[0][0],",")!==false){
					$fields = explode(",",$value[0][0]);
					foreach ($fields as $field) {
						$this->addField($field,$value[0][1]);					
					}
				} else {
					$this->addField($value[0][0],$value[0][1]);					
				}
			} else if(is_array($value)) {
				$fields = explode(",",$value[0]);
				foreach ($fields as $field) {
					$this->addField($field,"display");
				}
			}
		}
	}
	
	// ---------------------------------------------------
	// Function: parseDBFields
	// Parse out the fields required for each state
	// ---------------------------------------------------
	
	function parseDBFields($seeThruUnpub,$customReset) {
		if (!$seeThruUnpub) {
			$this->addField("parent","backend","db");
		}

		if (count($customReset) > 0) {
			$this->fields["backend"]["db"][] = "createdon";		
		}
		
		if (in_array("author",$this->fields["display"]["custom"])) {
			$this->fields["display"]["db"][] = "createdby";			
		}
		
		if (count($this->fields["display"]["tv"]) >= 0) {
			$this->addField("published","display","db");
		}
	}
	
	// ---------------------------------------------------
	// Function: renderQELinks
	// Render QE links when needed
	// ---------------------------------------------------

	function renderQELinks($fields, $resource, $QEPrefix,$placeholders) {
		global $modx;
		foreach ($fields as $dv) {
			$ds = $dv;
			if ($dv == "title") {
				$ds = "pagetitle";
			}
			$js = "window.open('".$modx->config["site_url"]."manager/index.php?a=112&id=1&doc=".$resource["id"]."&var=".$ds."', 'QuickEditor', 'width=525, height=300, toolbar=0, menubar=0, status=0, alwaysRaised=1, dependent=1');";
			$placeholders["#$dv"] = (!$modx->hasPermission('exec_module')) ? $placeholders["$dv"] : $placeholders["$dv"].'<a href="#" onclick="javascript: '.$js.'" title="'.$QEPrefix.$dv.'" class="QE_Link">&laquo; '.$QEPrefix.$dv.'</a>';
		}
		return $placeholders;
	}
	
	// ---------------------------------------------------
	// Function: getAuthor
	// Get the author name, or if not available the username
	// ---------------------------------------------------
	
	function getAuthor($createdby) {
		global $modx;
		
		$user = false;
		if ($createdby > 0) {
			$user = $modx->getUserInfo($createdby);
		} else {
			$user = $modx->getWebUserInfo(abs($createdby));
		}
		if ($user === false) {
			// get admin user name
			$user = $modx->getUserInfo(1);
		}
		return ($user['fullname'] != "") ? $user['fullname'] : $user['username'];
	}
	
	// ---------------------------------------------------
	// Function: customSort
	// Sort resource array if advanced sorting is needed
	// ---------------------------------------------------

	function customSort($data, $fields, $order) {
		// Covert $fields string to array
		// user contributed
		foreach (explode(',', $fields) as $s)
			$sortfields[] = trim($s);

		$code = "";
		for ($c = 0; $c < count($sortfields); $c++)
			$code .= "\$retval = strnatcmp(\$a['$sortfields[$c]'], \$b['$sortfields[$c]']); if(\$retval) return \$retval; ";
		$code .= "return \$retval;";

		$params = ($order == 'ASC') ? '$a,$b' : '$b,$a';
		uasort($data, create_function($params, $code));
		return $data;
	}

	// ---------------------------------------------------
	// Function: setSortOrder
	// Set the order of the documents for future use
	// ---------------------------------------------------
	
	function setSortOrder($processedIDs) {
			return array_flip($processedIDs);
	}

	// ---------------------------------------------------
	// Function: determineIDs
	// Get Document IDs for future use
	// ---------------------------------------------------
		
	function determineIDs($IDs, $IDType, $TVs, $sortBy, $advSort, $sortDir, $depth, $showPublishedOnly, $seeThruUnpub, $hideFolders, $showInMenuOnly, $myWhere, $keywords, $limit, $summarize, $filter, $paginate) {
		global $modx;

		if ($summarize == 0 && $summarize != "all") {
			return array();
		}
		
		// Get starting IDs;
		switch($IDType) {
			case "parents":
				$IDs = explode(",",$IDs);
				$documentIDs = $this->getChildIDs($IDs, $depth);
			break;
			case "documents":
				$documentIDs = explode(",",$IDs);
			break;
		}

		if ($this->advSort == false && $hideFolders==0 && $myWhere == "" && $filter == false) { 
			$this->prefetch = false; 
			if ($paginate = 1) {
				$documents = $modx->getDocuments($documentIDs, $showPublishedOnly, 0,"id");
				$documentIDs = array();
				foreach ($documents as $null=>$doc) {
					$documentIDs[] = $doc["id"];
				}
			}
			return $documentIDs;			
		} else {
			$this->prefetch = true; 
		}
		
		// Create where clause
		$where = array ();
		if ($hideFolders) {
			$where[] = 'isfolder = 0';
		}
		if ($showInMenuOnly) {
			$where[] = 'hidemenu = 0';
		}
		if ($myWhere != '') {
			$where[] = $myWhere;
		}
		$where = implode(" AND ", $where);
		$limit = ($limit == 0) ? "" : $limit;
			// set limit
			
		$customReset = $this->buildCustomResetList($sortBy,$this->advSort);

		$resource = $this->getDocuments($documentIDs, $this->fields["backend"]["db"], $TVs,$keywords,$showPublishedOnly,0,$where,$limit,$sortBy,$sortDir);
		$resource = array_values($resource);
		
		$recordCount = count($resource);
			// count number of records
		
		if (!$seeThruUnpub) {
			$parentList = $this->getParentList();
				// get parent list
		}
		
		if ($resource !== false) {
			for ($i = 0; $i < $recordCount; $i++) {
				if (!$seeThruUnpub) {
					$published = $parentList[$resource[$i]["parent"]];
					if ($published == "0")
						unset ($resource[$i]);
				}
				if (count($customReset) > 0) {
					foreach ($customReset as $field) {
						if ($resource[$i][$field] == false || $resource[$i][$field] == "") {
							$resource[$i][$field] = $resource[$i]["createdon"];
						}
					}
				}

			}

			if ($filter != false) {
				$filterObj = new filter();
				$resource = $filterObj->execute($resource, $filter);
			}

			if ($this->advSort !== false) {
				$resource = $this->customSort($resource, $this->advSort, $sortDir);
			}
			$fields = (array_intersect($this->fields["backend"],$this->fields["display"]));
			$readyFields = array();
			foreach ($fields as $field) {
				$readyFields = array_merge($readyFields,$field);
			}

			$processedIDs = array ();
			$keep = array();
			foreach ($resource as $key => $value) {
				$processedIDs[] = $value['id'];
				$iKey = '#'.$value['id'];
				foreach ($value as $key=>$v) {
					if (in_array($key,$readyFields)) {
						$keep[$iKey][$key] = $v;
					}
					if ($this->getDocVarType($key) == "tv:prefix") {
						if (in_array(substr($key,2),$readyFields)) {
							$keep[$iKey][$key] = $v;
						}
					}
					
				}
			}
			
			$this->prefetch = array("resource"=>$keep,"fields"=>$fields);
			if (count($processedIDs) > 0) {
				$this->sortOrder = $this->setSortOrder($processedIDs);
					// saves the order of the documents for use later
			}
			
			return $processedIDs;
		} else {
			return array();
		}
	}

	// ---------------------------------------------------
	// Function: getParentList
	// Get a list of all available parents
	// ---------------------------------------------------
		
	function getParentList() {
		global $modx;
		$kids = array();
		foreach ($modx->documentMap as $null => $document) {
			foreach ($document as $parent => $id) {
				$kids[$parent][] = $id;
			}
		}
		$parents = array();
		foreach ($kids as $item => $value) {
			if ($item != 0) {
				$pInfo = $modx->getPageInfo($item,0,"published");
			} else {
				$pInfo["published"] = "1";
			}
			$parents[$item] = $pInfo["published"];
		}
		return $parents;
	}

	// ---------------------------------------------------
	// Function: appendTV
	// Apeend a TV to the documents array
	// ---------------------------------------------------	
		
	function appendTV($tvname="",$docIDs){
		global $modx;
		
		$baspath= $modx->config["base_path"] . "manager/includes";
	    include_once $baspath . "/tmplvars.format.inc.php";
	    include_once $baspath . "/tmplvars.commands.inc.php";

		$tb1 = $modx->getFullTableName("site_tmplvar_contentvalues");
		$tb2 = $modx->getFullTableName("site_tmplvars");

		$query = "SELECT stv.name,stc.tmplvarid,stc.contentid,stv.type,stv.display,stv.display_params,stc.value";
		$query .= " FROM ".$tb1." stc LEFT JOIN ".$tb2." stv ON stv.id=stc.tmplvarid ";
		$query .= " WHERE stv.name='".$tvname."' AND stc.contentid IN (".implode($docIDs,",").") ORDER BY stc.contentid ASC;";
		$rs = $modx->db->query($query);
		$tot = $modx->db->getRecordCount($rs);
		$resourceArray = array();
		for($i=0;$i<$tot;$i++)  {
			$row = @$modx->fetchRow($rs);
			$resourceArray["#".$row['contentid']][$row['name']] = getTVDisplayFormat($row['name'], $row['value'], $row['display'], $row['display_params'], $row['type']);   
			$resourceArray["#".$row['contentid']]["tv".$row['name']] = $resourceArray["#".$row['contentid']][$row['name']];
		}
		if ($tot != count($docIDs)) {
			$query = "SELECT name,type,display,display_params,default_text";
			$query .= " FROM $tb2";
			$query .= " WHERE name='".$tvname."' LIMIT 1";
			$rs = $modx->db->query($query);
			$row = @$modx->fetchRow($rs);
			$defaultOutput = getTVDisplayFormat($row['name'], $row['default_text'], $row['display'], $row['display_params'], $row['type']);
			foreach ($docIDs as $id) {
				if (!isset($resourceArray["#".$id])) {
					$resourceArray["#$id"][$tvname] = $defaultOutput;
					$resourceArray["#$id"]["tv".$tvname] = $resourceArray["#$id"][$tvname];
				}
			}
		}
		return $resourceArray;
	}	
	
	// ---------------------------------------------------
	// Function: appendKeywords
	// Append keywords's to the resource array
	// ---------------------------------------------------
		
	function appendKeywords($resource) {
		$keys = $this->fetchKeywords($resource);
		$resource["keywords"] = "$keys";
		return $resource;
	}

	// ---------------------------------------------------
	// Function: fetchKeywords
	// Helper function to <appendKeywords>
	// ---------------------------------------------------
		
	function fetchKeywords($resource) {
		global $modx;
	  if($resource['haskeywords']==1) {
	    // insert keywords
	    $metas = implode(",",$modx->getKeywords($resource["id"]));
	  }
	  if($resource['hasmetatags']==1){
	    // insert meta tags
	    $tags = $modx->getMETATags($resource["id"]);
	    foreach ($tags as $n=>$col) {
	      $tag = strtolower($col['tag']);
	      $metas.= ",".$col['tagvalue'];
	    }
	  }
	  return $metas;
	}
	
	// ---------------------------------------------------
	// Function: getChildIDs
	// Get the IDs ready to be processed
	// Similar to the modx version by the same name but much faster
	// ---------------------------------------------------
	
	function getChildIDs($IDs, $depth) {
		global $modx;
		$depth = intval($depth);
		$kids = array();
		$docIDs = array();
		
		foreach ($modx->documentMap as $null => $document) {
			foreach ($document as $parent => $id) {
				$kids[$parent][] = $id;
			}
		}
		
		foreach ($IDs AS $seed) {
			if (!empty($kids[intval($seed)])) {
				$docIDs = array_merge($docIDs,$kids[intval($seed)]);
			}
		}

		$depth--;

		while($depth != 0) {
			foreach ($docIDs as $child=>$id) {
				if (!empty($kids[intval($id)])) {
					$docIDs = array_merge($docIDs,$kids[intval($id)]);
				}
			}
			$depth--;
		}
	
		return array_unique($docIDs);
	}

	// ---------------------------------------------------
	// Function: getDocuments
	// Get documents and append TVs + Prefetch Data, and sort
	// ---------------------------------------------------

	function getDocuments($ids= array (), $fields, $TVs, $keywords, $published= 1, $deleted= 0, $where= '', $limit= "", $sort= "id", $dir= "ASC") {
		global $modx;

	    if (count($ids) == 0) {
	        return false;
	    } else {
			sort($ids);
	        $limit= ($limit != "") ? "LIMIT $limit" : ""; // LIMIT capabilities - rad14701
	        $tblsc= $modx->getFullTableName("site_content");
	        $tbldg= $modx->getFullTableName("document_groups");
	        // modify field names to use sc. table reference
	        $fields= "sc.".implode(",sc.",$fields);
			$sort= ($sort == "") ? "" : 'sc.' . implode(',sc.', preg_replace("/^\s/i", "", explode(',', $sort)));
	        $where= ($where == "") ? "" : 'AND sc.' . implode('AND sc.', preg_replace("/^\s/i", "", explode('AND', $where)));
	        // get document groups for current user
	        if ($docgrp= $modx->getUserDocGroups())
	            $docgrp= implode(",", $docgrp);
	        $access= ($modx->isFrontend() ? "sc.privateweb=0" : "1='" . $_SESSION['mgrRole'] . "' OR sc.privatemgr=0") .
	         (!$docgrp ? "" : " OR dg.document_group IN ($docgrp)");
	        $sql= "SELECT DISTINCT $fields FROM $tblsc sc
	                LEFT JOIN $tbldg dg on dg.document = sc.id
	                WHERE (sc.id IN (" . join($ids, ",") . ") AND sc.published=$published AND sc.deleted=$deleted $where)
	                AND ($access)
	                GROUP BY sc.id" .
             ($sort ? " ORDER BY $sort $dir" : "") . " $limit ";

	        $result= $modx->dbQuery($sql);
	        $resourceArray= array ();
			$cnt = @$modx->recordCount($result);
			$TVData = array();
			$TVIDs = array();
			for ($i= 0; $i < $cnt; $i++) {
	            $resource = $modx->fetchRow($result);
				if($keywords) {
					$resource = $this->appendKeywords($resource);
				}
				if ($this->prefetch == true && $this->sortOrder !== false) $resource["ditto_sort"] = $this->sortOrder[$resource["id"]];
				$TVIDs[] = $resource["id"];
				$resourceArray["#".$resource["id"]] = $resource;
				if (count($this->prefetch["resource"]) > 0) {
					$x = "#".$resource["id"];
					$resourceArray[$x] = array_merge($resource,$this->prefetch["resource"][$x]);
							// merge the prefetch array and the normal array
				}
	        }

			$TVs = array_unique($TVs);
			if (count($TVs) > 0) {
				foreach($TVs as $tv){
					$TVData = array_merge_recursive($this->appendTV($tv,$TVIDs),$TVData);
				}
			}

			$resourceArray = array_merge_recursive($resourceArray,$TVData);
			if ($this->prefetch == true && $this->sortOrder !== false) {$resourceArray = $this->customSort($resourceArray,"ditto_sort","ASC");}
			return $resourceArray;
	    }
	}

	// ---------------------------------------------------
	// Function: buildCustomResetList
	// Build a list of the fields needing to be reset
	// ---------------------------------------------------
	
	function buildCustomResetList($sortBy,$advSort) {
		$checks = array($sortBy,$advSort);
		// array of values that may need to be reset

		$checkOptions = array("pub_date","unpub_date","editedon","deletedon","publishedon");
		// array of fields that may need to be reset

		$customReset = array();

		foreach ($checks as $check) {
			if (in_array($check, $checkOptions)) {
				$customReset[] = $check;
			}
		}

		return $customReset;
	}
	
	// ---------------------------------------------------
	// Function: cleanIDs
	// Clean the IDs of any dangerous characters
	// ---------------------------------------------------
	
	function cleanIDs($IDs) {
		//Define the pattern to search for
		$pattern = array (
			'`(,)+`', //Multiple commas
			'`^(,)`', //Comma on first position
			'`(,)$`' //Comma on last position
		);

		//Define replacement parameters
		$replace = array (
			',',
			'',
			''
		);

		//Clean startID (all chars except commas and numbers are removed)
		$startID = preg_replace($pattern, $replace, $IDs);

		return $IDs;
	}

	// ---------------------------------------------------
	// Function: buildURL
	// Build a URL with regard to Ditto ID
	// ---------------------------------------------------
	
	function buildURL($args,$id=false,$dittoIdentifier=false) {
		global $modx, $dittoID;
			$dittoID = ($dittoIdentifier !== false) ? $dittoIdentifier : $dittoID;
			$query = $_GET;
			unset($query["id"]);
			unset($query["q"]);
			$args = explode("&",$args);
			foreach ($args as $arg) {
				$arg = explode("=",$arg);
				$query[$dittoID.$arg[0]] = $arg[1];
			}
			$queryString = "";
			foreach ($query as $param=>$value) {
				$queryString .= '&'.$param.'='.(is_array($value) ? implode(",",$value) : $value);
			}
			$cID = ($id !== false) ? $id : $modx->documentObject['id'];
			$url = $modx->makeURL($cID, '', $queryString);
			return str_replace("&","&amp;",$url);
	}
	
	// ---------------------------------------------------
	// Function: getParam
	// Get a parameter or use the default language value
	// ---------------------------------------------------
	
	function getParam($param,$langString){
		// get a parameter value and if it is not set get the default language string value
		global $modx,$ditto_lang;
		$out = "";
		if ($modx->getChunk($param) != "") {
			$out = $modx->getChunk($param);
		} else if(!empty($param)) {
			$out = $param;
		}else{
			$out = $ditto_lang[$langString];
		}
			return $out;
	}

	// ---------------------------------------------------
	// Function paginate
	// Paginate the documents
	// ---------------------------------------------------
		
	function paginate($start, $stop, $total, $summarize, $tplPaginateNext, $tplPaginatePrevious, $paginateAlwaysShowLinks, $paginateSplitterCharacter) {
		global $modx, $dittoID;
		
		if (($start == 0 && $stop == 0 && $total == 0) || $summarize==0) {
			return false;
		}
		$next = $start + $summarize;
		$nextlink = "<a href='".$this->buildURL("start=$next")."'>" . $tplPaginateNext . "</a>";
		$previous = $start - $summarize;
		$previouslink = "<a href='".$this->buildURL("start=$previous")."'>" . $tplPaginatePrevious . "</a>";
		$limten = $summarize + $start;
		if ($paginateAlwaysShowLinks == 1) {
			$previousplaceholder = "<span class='ditto_off'>" . $tplPaginatePrevious . "</span>";
			$nextplaceholder = "<span class='ditto_off'>" . $tplPaginateNext . "</span>";
		} else {
			$previousplaceholder = "";
			$nextplaceholder = "";
		}
		$split = "";
		if ($previous > -1 && $next < $total)
			$split = $paginateSplitterCharacter;
		if ($previous > -1)
			$previousplaceholder = $previouslink;
		if ($next < $total)
			$nextplaceholder = $nextlink;
		if ($start < $total)
			$stop = $limten;
		if ($limten > $total) {
			$limiter = $total;
		} else {
			$limiter = $limten;
		}
		$totalpages = ceil($total / $summarize);

		for ($x = 0; $x <= $totalpages -1; $x++) {
			$inc = $x * $summarize;
			$display = $x +1;
			if ($inc != $start) {
				$pages .= "<a class=\"ditto_page\" href='".$this->buildURL("start=$inc")."'>$display</a>";
			} else {
				$modx->setPlaceholder($dittoID."currentPage", $display);
				$pages .= "<span class=\"ditto_currentpage\">$display</span>";
			}
		}
		$modx->setPlaceholder($dittoID."next", $nextplaceholder);
		$modx->setPlaceholder($dittoID."previous", $previousplaceholder);
		$modx->setPlaceholder($dittoID."splitter", $split);
		$modx->setPlaceholder($dittoID."start", $start +1);
		$modx->setPlaceholder($dittoID."stop", $limiter);
		$modx->setPlaceholder($dittoID."total", $total);
		$modx->setPlaceholder($dittoID."pages", $pages);
		$modx->setPlaceholder($dittoID."perPage", $summarize);
		$modx->setPlaceholder($dittoID."totalPages", $totalpages);
	}
}
?>
