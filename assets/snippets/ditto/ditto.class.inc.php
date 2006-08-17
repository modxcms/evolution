<?php

/**
 * Snippet Name: Ditto
 * Short Desc: Displays content aggregated from other parts of the site such as News Articles and Blog Posts
 * Author: The MODx Project
 * Version: 1.0.2
 * Last Edited: 09-Jun-2006
 * Function: Displays documents with full support for pagination (paging of content in increments) and Template Variables
 */

class ditto {
	var $link, $advancedsort, $hiddenTVs, $start, $stop, $array_key, $filtertype, $tagDelimiter, $filterValue, $tags;

	// ---------------------------------------------------
	// Get createdby
	// ---------------------------------------------------
	function getCreatedBy($createdby, $format = "html") {
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
			$rssusername = "" . $user['fullname'] . " <" . $user['email'] . ">";
			$username = $user['fullname'];
		} else {
			$username = ($user['fullname'] != "") ? $user['fullname'] : $user['username'];
			$rssusername = "" . $username . " <" . $user['email'] . ">";
		}
		$uname = ($format != "rss") ? $username : $rssusername;
		return $uname;
	}

	// ---------------------------------------------------
	// Sort resource array if advanced sorting is needed
	// ---------------------------------------------------

	function customSort(& $data, $fields, $order) {
		// Covert $fields string to array
		foreach (explode(',', $fields) as $s)
			$sortfields[] = trim($s);

		$code = "";
		for ($c = 0; $c < count($sortfields); $c++)
			$code .= "\$retval = strnatcmp(\$a['$sortfields[$c]'], \$b['$sortfields[$c]']); if(\$retval) return \$retval; ";
		$code .= "return \$retval;";

		$params = ($order == 'ASC') ? '$a,$b' : '$b,$a';
		usort($data, create_function($params, $code));
	}

	function appendTVs(&$resource, $tvnames) {
		global $modx;
		// ---------------------------------------------------
		// Append tv's to array
		// ---------------------------------------------------
		$tvnum = count($tvnames);

		if ($tvnum > 0) {
			for ($i = 0; $i < count($resource); $i++) {

				$tvs = $modx->getTemplateVarOutput($tvnames, $resource[$i]["id"], $published = $resource[$i]['published']);
				if ($tvs !== false) {
					foreach ($tvs as $name => $object) {

						$resource[$i]["tv" . $name] = $object;

					}
				}
			}
		}

	}

   function getChildIds($id, $depth= 10, $children= array()) {
   // function by Jason Coward
   global $modx;
     $c= null;
     foreach ($modx->documentMap as $mapEntry) {
       if (isset ($mapEntry[$id])) {
         $childId= $mapEntry[$id];
         $childKey= array_search($childId, $modx->documentListing);
         if (!$childKey) {
           $childKey= "$childId";
         }
         $c[$childKey]= $childId;
       }
     }
     $depth--;
     if (is_array($c)) {
       if (is_array($children)) {
         $children= $children + $c;
       } else {
         $children= $c;
       }
       if ($depth) {
           foreach ($c as $child) {
             $children= $children + $this->getChildIds($child, $depth, $children);
           }
       }
     }
     return $children;
   }

	function getAllSubDocs($resourceparent = array (), $tvnames = array(), $sortby = "createdon", $sortdir = "desc", $descendentDepth = 1,$showPublishedOnly = true, $seeThruUnpub = false, $hidefolders = false) {
		global $modx;

		// ---------------------------------------------------
		// Seed list of viable ids
		// ---------------------------------------------------

		$seedArray = explode(',', $resourceparent);


		$kids = array();
		foreach ($seedArray AS $seed) {
			$kids = $this->getChildIds($seed, $descendentDepth, $kids);
		}
		$kids = array_values($kids);

		$where = $hidefolders ? 'isfolder = 0' : '';
			// set where clause

		$resource = $modx->getDocuments($kids, $showPublishedOnly, 0, "*", $where, $sortby, $sortdir);
			// get actual documents

		// ---------------------------------------------------
		// Append tv's to array
		// ---------------------------------------------------

		$this->appendTVs($resource, $tvnames);

		return $resource;

       }

	function cleanStartIDs($startIDs, $mode = "development") {
		if ($mode == "development") {
			//Define the pattern to search for
			$pattern = array (
				'`([^,\d])`', //All chars except commas and numbers
				'`(,)+`', //Multiple commas
				'`^(,)`', //Comma on first position
				'`(,)$`' //Comma on last position
			);

			//Define replacement parameters
			$replace = array (
				'',
				',',
				'',
				''
			);

			//Clean startID (all chars except commas and numbers are removed)
			$startID = preg_replace($pattern, $replace, $startIDs);
		}
		return $startIDs;
	}

	function render(&$resource, $x, $format, $datetype, $templates, $date, $debug, $summary = "", $link = "", $stop) {
		global $modx, $_lang;
		$placeholders = array ();
		$contentvariables = array ();
		$contentVar = "";
		$output = "";
		// set blank values
		if ($debug == 1) $output .= '<p><strong>'.$_lang['debug_document_data'].'"'.$resource['pagetitle'].'"</strong></p><code>';

		// Set placeholders for document object
		foreach ($resource as $docVar => $docVarValue) {
			$placeholders["[+$docVar+]"] = "$docVarValue";
			if (substr($docVar, 0, 2) == "tv") {
				$contentVar = substr($docVar, 2);
			} else {
				$contentVar = $docVar;
			}

			$contentvariables["[*$contentVar*]"] = "$docVarValue";
		}
		// Replace content variables in the summary

		$summary = str_replace(array_keys($contentvariables), array_values($contentvariables), $summary);

		// Set placeholders that can be used in the Chunk

		// Set placeholders for backwards compadibility and custom fields
		$placeholders['[+title+]'] = $resource['pagetitle'];
		$placeholders['[+summary+]'] = $summary;
		$placeholders['[+link+]'] = $link;
		$placeholders['[+author+]'] = $this->getCreatedBy($resource['createdby'], $format);
		if ($resource['type'] =='reference') { $placeholders['[+weblink+]'] = $resource['content']; }

		// Ensure proper charset is used
		if ($modx->config['etomite_charset'] == 'UTF-8') {
			$placeholders['[+date+]'] = utf8_encode(strftime($date, $resource[$datetype]));
		} else {
			$placeholders['[+date+]'] = strftime($date, $resource[$datetype]);
		}
		//set rss placeholders
		if ($format == "rss") {
			$placeholders['[+rssdate+]'] = date("r", $resource[$datetype]);
			$placeholders['[+rsspagetitle+]'] = htmlentities($resource['pagetitle'], ENT_QUOTES, $modx->config['etomite_charset']);
			$placeholders['[+rssusername+]'] = htmlentities($this->getCreatedBy($resource['createdby'], $format), ENT_QUOTES, $modx->config['etomite_charset']);
		}

		$currentTPLKey = $this->getCurrentTPL($x, $stop, $resource['id'], $format, $templates);
		$currentTPL = $templates["$currentTPLKey"];
		$currentTPLname = substr("$currentTPLKey", 3);

		// Expand the chunk code, and replace Placeholders
		if ($debug != 1) {
			$itemcontent = str_replace(array_keys($placeholders), array_values($placeholders), $currentTPL);
			$modx->setPlaceholder('item[' . $x . ']', $itemcontent);
			$output .= $itemcontent;
		} else
			if ($debug == 1) {
				foreach ($placeholders as $key => $value) {
					if ($key != "[+content+]") {
						$key = htmlentities($key, ENT_QUOTES);
						$key = str_replace("[+","&#091;+",$key);
						$key = str_replace("+]","+&#093;",$key);
						$value = htmlentities($value, ENT_QUOTES);
						$value = str_replace("[+","&#091;+",$value);
						$value = str_replace("+]","+&#093;",$value);
						$output .= "$key => $value <br />";
					}
				}
				$ctploutput = htmlentities($currentTPL, ENT_QUOTES);
				$ctploutput = str_replace("[+","&#091;+",$ctploutput);
				$ctploutput = str_replace("+]","+&#093;",$ctploutput);
				$output .= "tpl = $currentTPLname -> ".$ctploutput;
			}


		if ($debug == 1) $output .= "</code>";


		return $output;
	}

	// ---------------------------------------------------
	// Determine current template
	// ---------------------------------------------------
	function getCurrentTPL($x, $stop, $id, $format, $templates) {
		global $modx;
		// determine current template
		$currentTPL = "tpl";
		if ($format == "archive") {
			$currentTPL = "tplArch";
		} else if ($format == "html") {
			if ($x % 2) {
				$currentTPL = "tplAltRows";
			}
			if ($x == 0) {
				$currentTPL = "tplFirstRow";
			}
			if ($x == $stop -1) {
				$currentTPL = ($x % 2 && $templates["tplLastRow"] == $templates["tpl"]) ? "tplAltRows" : "tplLastRow";
			}
			if ($id == $modx->documentObject['id']) {
				$currentTPL = "tplCurrentDocument";
			}
		}
	return $currentTPL;
	}

	// ---------------------------------------------------
	// Find tv names
	// ---------------------------------------------------

	function findTVs($tpl) {
		preg_match_all('~\[\+tv(.*?)\+\]~', $tpl, $matches);
		$cnt = count($matches[1]);

		$tvnames = array ();
		for ($i = 0; $i < $cnt; $i++) {
			$tvnames[] = $matches[1][$i];
		}

		$tvnames = array_unique($tvnames);

		if (count($tvnames) >= 1) {
			return $tvnames;
		} else {
			return false;
		}
	}

	// ---------------------------------------------------
	// Find all tv names
	// ---------------------------------------------------

	function findAllTVs($templates, $hiddentvs, $filter) {
		$tvnames = array ();
		$tmptvnames = "";
		foreach ($templates as $tpl) {
			$tmptvnames = $this->findTVs($tpl);
			if ($tmptvnames != false) {
				$tvnames = array_merge($tvnames, $tmptvnames);
			}
		}
		// check if there are any hidden tv's we need to add to array
		if ($hiddentvs !== "") {
			$hiddentvsarray = explode(',', $hiddentvs);
			$tvnames = array_merge($hiddentvsarray, $tvnames);
		}
		$filters = explode('|', $filter);
		foreach ($filters AS $processfilter) {
			$filterArray = explode(',', $processfilter);
			$this->array_key = $filterArray[0];
			if (substr($this->array_key, 0, 2) == "tv") {
				$tvname = substr($this->array_key, 2);
				$htvs = $this->hiddenTVs;
				$htvs = ($htvs !== "" ? $htvs . ",$tvname" : "$tvname");
				$tvnames[]=$htvs;
			}
		}
		$tvnames = array_unique($tvnames);
		return $tvnames;
	}

	// ---------------------------------------------------
	// Validate sort parameter
	// ---------------------------------------------------

	function checkSort($sortBy, $dateFormatType, $mode = "development") {
		global $modx;
		$this->advancedsort = "off";
		$dbfields = array();
		if (substr($sortBy, 0, 2) != "tv") {$dbfields[]=$sortBy;}
		if ($mode != "production") {
			$columns = $modx->db->query("show columns from " . $modx->getFullTableName('site_content'));
			while ($dbfield = $modx->db->getRow($columns))
				$dbfields[] = $dbfield['Field'];
		}
		if ($sortBy != "0" && in_array($sortBy, $dbfields)) {
			$dt = $sortBy;
		} else
			if (substr($sortBy, 0, 2) == "tv") {
				$tvname = substr($sortBy, 2);
				$htvs = $this->hiddenTVs;
				$htvs = ($htvs !== "" ? $htvs . ",$tvname" : "$tvname");
				$this->hiddenTVs = $htvs;
				$this->advancedsort = $sortBy;
				$dt = "id";
			} else {
				$dt = "createdon";
			}

		return $dt;
	}

	// ---------------------------------------------------
	// Validate date format
	// ---------------------------------------------------

	function checkDateFormat($sortBy, $dateFormatType) {
		if ((!empty($sortBy)) && ($sortBy == "createdon" || $sortBy == "editedon" || $sortBy == "pub_date" || $sortBy == "unpub_date" || $sortBy == "deletedon")) {
			$dt = $sortBy;
		} else
			if ($dateFormatType != "") {
				$dt = $dateFormatType;
			} else {
				$dt = "createdon";
			}
		return $dt;
	}

	// ---------------------------------------------------
	// Filter documents via either a tag filter or basic filter
	// ---------------------------------------------------

	function filterDocuments(&$resource, $filter) {
		$filters = explode('|', $filter);
		foreach ($filters AS $processfilter) {
			$filterArray = explode(',', $processfilter);
			$this->array_key = $filterArray[0];
			$this->filterValue = $filterArray[1];
			$this->filtertype = (isset ($filterArray[2])) ? $filterArray[2] : 1;
			$callback = ($this->filterValue == "[TAGS]") ? "tagFilter" : "basicFilter";
			$resource = array_filter($resource, array($this, $callback));
		}
		return $resource;
	}

	function tagFilter ($value) {
		$this->filterValue = $this->tags;
		$documentTags = explode($this->tagDelimiter, $this->filterValue);
		$filterTags = explode($this->tagDelimiter, $value[$this->array_key]);
		$compare = array_intersect($filterTags, $documentTags);
		$commonTags = count($compare);
		$totalTags = count($filterTags);
		$unset = 1;
		switch ($this->filtertype) {
			case "onlyAllTags" :
			case 7 :
				if ($commonTags != $totalTags)
					$unset = 0;
				break;
			case "removeAllTags" :
			case 8 :
				if ($commonTags == $totalTags)
					$unset = 0;
				break;
			case "onlyTags" :
			case 9 :
				if ($commonTags > $totalTags || $commonTags == 0)
					$unset = 0;
				break;
			case "removeTags" :
			case 10 :
				if ($commonTags <= $totalTags && $commonTags != 0)
					$unset = 0;
				break;
			}
			return $unset;
	}

	function basicFilter ($value) {
			$unset = 1;
			switch ($this->filtertype) {
				case "!=" :
				case 1 :
					if (!isset ($value[$this->array_key]) || $value[$this->array_key] != $this->filterValue)
						$unset = 0;
					break;
				case "=" :
				case 2 :
					if ($value[$this->array_key] == $this->filterValue)
						$unset = 0;
					break;
				case "<" :
				case 3 :
					if ($value[$this->array_key] < $this->filterValue)
						$unset = 0;
					break;
				case ">" :
				case 4 :
					if ($value[$this->array_key] > $this->filterValue)
						$unset = 0;
					break;
				case "<=" :
				case 5 :
					if (!($value[$this->array_key] < $this->filterValue))
						$unset = 0;
					break;
				case ">=" :
				case 6 :
					if (!($value[$this->array_key] > $this->filterValue))
						$unset = 0;
					break;
			}
			return $unset;
	}

	// ---------------------------------------------------
	// Retrieve documents from MODx database
	// ---------------------------------------------------

	function getDocuments($startID, $tvnames, $sortBy, $sortDir, $descendentDepth, $showPublishedOnly, $seeThruUnpub, $hidefolders) {
		global $modx;
		$startIDs = explode(",", $startID);
		$resource = $this->getAllSubDocs($startID, $tvnames, $sortBy, $sortDir, $descendentDepth, $showPublishedOnly, $seeThruUnpub, $hidefolders);
		return $resource;
	}

	function paginate($start, $stop, $total, $summarize, $tplArchiveNext, $tplArchivePrevious, $paginateAlwaysShowLinks, $paginateSplitterCharacter) {
		global $modx;
		$this->start = $start;
		$this->stop = $stop;
		$currentpageid = $modx->documentObject['id'];
		$url = $modx->makeUrl($currentpageid);
		$char = ((strpos($url,"?")===false) ? "?":"&amp;");
		$next = $start + $summarize;

		$nextlink = "<a href='[~$currentpageid~]" . $char . "start=$next'>" . $tplArchiveNext . "</a>";
		$previous = $start - $summarize;
		$previouslink = "<a href='[~$currentpageid~]" . $char . "start=$previous'>" . $tplArchivePrevious . "</a>";
		$limten = $summarize + $start;
		if ($paginateAlwaysShowLinks == 1) {
			$previousplaceholder = "<span class='ditto_off'>" . $tplArchivePrevious . "</span>";
			$nextplaceholder = "<span class='ditto_off'>" . $tplArchiveNext . "</span>";
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
				$pages .= "<a class=\"ditto_page\" href='[~$currentpageid~]" . $char . "start=$inc'>$display</a>";
			} else {
				$modx->setPlaceholder('current', $display);
				$pages .= "<span id=\"ditto_currentpage\">$display</span>";
			}
		}

		$modx->setPlaceholder('next', $nextplaceholder);
		$modx->setPlaceholder('previous', $previousplaceholder);
		$modx->setPlaceholder('splitter', $split);
		$modx->setPlaceholder('start', $start +1);
		$modx->setPlaceholder('stop', $limiter);
		$modx->setPlaceholder('total', $total);
		$modx->setPlaceholder('pages', $pages);
		$modx->setPlaceholder('totalpages', $totalpages);

		if ($start < $total)
			$stop = $limten;
		$this->start = $start;
		$this->stop = $stop;
	}

	// ---------------------------------------------------
	// Generate archives
	// ---------------------------------------------------

	function generateArchive($archiveText, $archivePlaceholder, $stop, $total, &$resource, $dateFormatType, $dateFormat, $archiveDateType, $templates, $debug) {
		global $modx;
		$lastCategory = "";
		$archivehtml = "";
		$archiveHTML .= "<h3>$archiveText</h3><div id=\"ditto_archivelist\"><ul>";

		for ($i = $stop; $i < $total; $i++) {
			$dateArray = getdate($resource[$i][$dateFormatType]);
			$category = strftime("%B %Y", $resource[$i][$dateFormatType]);
			$subCategory = $dateArray['mon'];
			if ($subCategory != $lastCategory) {
				if ($lastCategory != "") {
					$archiveHTML .= '</ul></li>';
				}
				$archiveHTML .= '<li><span class="ditto_month">' . $category . '</span><ul>';
			}
			$archiveHTML .= "<li class=\"ditto_archpost\">";
			$archiveHTML .= $this->render($resource[$i], 0, "archive", $archiveDateType, $templates, $dateFormat, $debug, "", "", $stop);
			$archiveHTML .= "</li>";
			$lastCategory = $subCategory;

		}
		$archiveHTML .= "</ul></li></ul></div>";

		if ($archivePlaceholder == 1) {
			$modx->setPlaceholder('archive', $archiveHTML);
		} else {
			return $archiveHTML;
		}
	}

	// ---------------------------------------------------
	// Truncate text
	// ---------------------------------------------------

	function html_substr($posttext, $minimum_length = 200, $length_offset = 20, $truncChars=false) {

	   // $minimum_length:
	   // The approximate length you want the concatenated text to be


	   // $length_offset:
	   // The variation in how long the text can be in this example text
	   // length will be between 200 and 200-20=180 characters and the
	   // character where the last tag ends

	   // Reset tag counter & quote checker
	   $tag_counter = 0;
	   $quotes_on = FALSE;
	   // Check if the text is too long
	   if (strlen($posttext) > $minimum_length && $truncChars != 1) {

	       // Reset the tag_counter and pass through (part of) the entire text
	       $c = 0;
	       for ($i = 0; $i < strlen($posttext); $i++) {
	           // Load the current character and the next one
	           // if the string has not arrived at the last character
	           $current_char = substr($posttext,$i,1);
	           if ($i < strlen($posttext) - 1) {
	               $next_char = substr($posttext,$i + 1,1);
	           }
	           else {
	               $next_char = "";
	           }
	           // First check if quotes are on
	           if (!$quotes_on) {
	               // Check if it's a tag
	               // On a "<" add 3 if it's an opening tag (like <a href...)
	               // or add only 1 if it's an ending tag (like </a>)
	               if ($current_char == '<') {
	                   if ($next_char == '/') {
	                       $tag_counter += 1;
	                   }
	                   else {
	                       $tag_counter += 3;
	                   }
	               }
	               // Slash signifies an ending (like </a> or ... />)
	               // substract 2
	               if ($current_char == '/' && $tag_counter <> 0) $tag_counter -= 2;
	               // On a ">" substract 1
	               if ($current_char == '>') $tag_counter -= 1;
	               // If quotes are encountered, start ignoring the tags
	               // (for directory slashes)
	               if ($current_char == '"') $quotes_on = TRUE;
	           }
	           else {
	               // IF quotes are encountered again, turn it back off
	               if ($current_char == '"') $quotes_on = FALSE;
	           }

	           // Count only the chars outside html tags
	           if($tag_counter == 2 || $tag_counter == 0){
	               $c++;
	           }

	           // Check if the counter has reached the minimum length yet,
	           // then wait for the tag_counter to become 0, and chop the string there
	           if ($c > $minimum_length - $length_offset && $tag_counter == 0) {
	               $posttext = substr($posttext,0,$i + 1);
	               return $posttext;
	           }
	       }
	   }  return $this->textTrunc($posttext, $minimum_length + $length_offset);
	}

	function textTrunc($string, $limit, $break=". ") {
  	// Original PHP code from The Art of Web: www.the-art-of-web.com

    // return with no change if string is shorter than $limit
    if(strlen($string) <= $limit) return $string;

    $string = substr($string, 0, $limit);
    if(false !== ($breakpoint = strrpos($string, $break))) {
      $string = substr($string, 0, $breakpoint+1);
    }

    return $string;
  }

	function closeTags($text) {
		global $debug;
		$openPattern = "/<([^\/].*?)>/";
		$closePattern = "/<\/(.*?)>/";
		$endOpenPattern = "/<([^\/].*?)$/";
		$endClosePattern = "/<(\/.*?[^>])$/";
		$endTags = '';

		preg_match_all($openPattern, $text, $openTags);
		preg_match_all($closePattern, $text, $closeTags);

		if ($debug == 1) {
			print_r($openTags);
			print_r($closeTags);
		}

		$c = 0;
		$loopCounter = count($closeTags[1]); //used to prevent an infinite loop if the html is malformed
		while ($c < count($closeTags[1]) && $loopCounter) {
			$i = 0;
			while ($i < count($openTags[1])) {
				$tag = trim($openTags[1][$i]);

				if (strstr($tag, ' ')) {
					$tag = substr($tag, 0, strpos($tag, ' '));
				}
				if ($debug == 1) {
					echo $tag . '==' . $closeTags[1][$c] . "\n";
				}
				if ($tag == $closeTags[1][$c]) {
					$openTags[1][$i] = '';
					$c++;
					break;
				}
				$i++;
			}
			$loopCounter--;
		}

		$results = $openTags[1];

		if (is_array($results)) {
			$results = array_reverse($results);

			foreach ($results as $tag) {
				$tag = trim($tag);

				if (strstr($tag, ' ')) {
					$tag = substr($tag, 0, strpos($tag, ' '));
				}
				if (!stristr($tag, 'br') && !stristr($tag, 'img') && !empty ($tag)) {
					$endTags .= '</' . $tag . '>';
				}
			}
		}
		return $text . $endTags;
	}

	function trimSummary($summary, &$resource, $trunc, $splitter, $linktext, $truncLen, $truncOffset, $truncsplit, $commentschunk, $truncChars) {
		$summary = '';
		$this->link = '';
		// determine and show summary

		// summary is turned off
		if ($trunc != "1") {
			$summary = $resource['content'];

			// contains the splitter and use splitter is on
		} else
			if ((strstr($resource['content'], $splitter)) && $truncsplit) {
				$summary = array ();

				// HTMLarea/XINHA encloses it in paragraph's
				$summary = explode('<p>' . $splitter . '</p>', $resource['content']);

				// For TinyMCE or if it isn't wrapped inside paragraph tags
				$summary = explode($splitter, $summary['0']);

				$summary = $summary['0'];
				$this->link = '<a href="[~' . $resource['id'] . '~]">' . $linktext . '</a>';

				// fall back to the summary text
			} else
				if (strlen($resource['introtext']) > 0) {
					$summary = $resource['introtext'];
					$this->link = '<a href="[~' . $resource['id'] . '~]">' . $linktext . '</a>';

					// fall back to the summary text count of characters
				} else
					if (strlen($resource['content']) > $truncLen) {
						$summary = $this->html_substr($resource['content'], $truncLen, $truncOffset, $truncChars);
						$this->link = '<a href="[~' . $resource['id'] . '~]">' . $linktext . '</a>';
						// and back to where we started if all else fails (short post)
					} else {
						$summary = $resource['content'];
						$this->link = '   ';
					}

		// Post-processing to clean up summaries
		$summary = $this->closeTags($summary);
		$summary = str_replace($commentschunk, '', $summary);
		return $summary;
	}
}
?>
