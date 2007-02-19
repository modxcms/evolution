<?php

// ---------------------------------------------------
// Tagging Parameters
// ---------------------------------------------------

$landing = isset($tagDocumentID) ? $tagDocumentID : $modx->documentObject['id'];

$source = isset($tagData) ? $tagData : "";
	
$mode = isset($tagMode) ? $tagMode: "onlyTags";
	// get the mode to remove tags. either onlyAllTags, removeAllTags, onlyTags, or removeTags.

$delimiter= isset($tagDelimiter) ? $tagDelimiter: " ";
	// get tag delimiter used to split tags. defaults to space.

$givenTags = !empty($tags) ? trim($tags) : false;
	// given tags from the get array
	
$tags = new tagging($delimiter,$source,$mode,$landing,$givenTags,$format);

if ($tags->givenTags != "") {
	$cFilters["tagging"] = array($tags,"tagFilter"); 
		// set tagging custom filter
}

//generate TagList
$modx->setPlaceholder($dittoID."tagLinks",$tags->tagLinks($tags->givenTags, $delimiter, $landing, $format));

// set raw tags placeholder
$modx->setPlaceholder("tags",$tags->givenTags);
	
// set tagging placeholder			
$placeholders['tagLinks'] = array(array($source,"*"),array($tags,"makeLinks"));

// ---------------------------------------------------
// Tagging Class
// ---------------------------------------------------

class tagging {
	var $delimiter,$source,$landing,$mode,$format,$givenTags;
	
	function tagging($delimiter,$source,$mode,$landing,$givenTags,$format) {
		$this->delimiter = $delimiter;
		$this->source = $source;
		$this->mode = $mode;
		$this->landing = $landing;
		$this->format = $format;
		$this->givenTags = $this->prepGivenTags($givenTags);
	}
	
	function prepGivenTags ($givenTags) {
		global $_GET,$dittoID;

		$getTags = !empty($_GET[$dittoID.'tags']) ? trim($_GET[$dittoID.'tags']) : false;
			// Get tags from the $_GET array

		$tags1 = array();
		$tags2= array();
		
		if ($getTags !== false) {
			$tags1 = explode($this->delimiter,$getTags);
		}
		
		if ($givenTags !== false) {
			$tags2 = explode($this->delimiter,$givenTags);		
		} 
		$tags = array_merge($tags1,$tags2);
		$tags = array_unique($tags);
		
		return implode($this->delimiter,$tags);		
	}

	function tagFilter ($value) {
		$documentTags = explode($this->delimiter, $this->givenTags);
		$filterTags = $this->combineTags($this->source, $value,array(),true);
		$compare = array_intersect($filterTags, $documentTags);
		$commonTags = count($compare);
		$totalTags = count($filterTags);
		$docTags = count($documentTags);
		$unset = 1;

		switch ($this->mode) {
			case "onlyAllTags" :
				if ($commonTags != $docTags)
					$unset = 0;
				break;
			case "removeAllTags" :
				if ($commonTags == $docTags)
					$unset = 0;
				break;
			case "onlyTags" :
				if ($commonTags > $totalTags || $commonTags == 0)
					$unset = 0;
				break;
			case "removeTags" :
				if ($commonTags <= $totalTags && $commonTags != 0)
					$unset = 0;
				break;
			}
			return $unset;
	}

	function makeLinks($resource) {
		return $this->tagLinks($this->combineTags($this->source,$resource), $this->delimiter, $this->landing, $this->format);
	}
	
	function parseTagData($tagData,$names=array()) {
		return explode(",",$tagData);
	}

	function combineTags($tagData, $resource, $resourceTags = array(),$leaveAsArray=false) {
		$tagData = $this->parseTagData($tagData);
		$tags = array();
		foreach ($tagData as $source) {
			if(!empty($resource[$source])) {
				$tags[] = $resource[$source];
			}
		}		

		$return = ($leaveAsArray == true) ? explode($this->delimiter,implode($this->delimiter,$tags)) : implode($this->delimiter,$tags);
		return $return;
	}


	function tagLinks($tags, $tagDelimiter, $tagID=false, $format="html") {
		global $ditto_lang;
		if(strlen($tags) == 0 && $format=="html") {
			return $ditto_lang['none'];
		} else if (strlen($tags) == 0 && ($format=="rss" || $format=="xml")) 
		{
			return "<category>".$ditto_lang['none']."</category>";
		}
		$tags = explode($tagDelimiter, $tags);
		$tags = array_unique($tags);
		$output = "";
		foreach ($tags as $tag) {
			if ($format == "html") {
				$tagDocID = (!$tagID) ? $modx->documentObject['id'] : $tagID;
				$url = ditto::buildURL("tags=$tag",$tagDocID);
				$output .= "<a href=\"$url\" class=\"ditto_tag\" rel=\"tag\">$tag</a> ";
			} else if ($format == "rss" || $format == "xml") {
				$output .=  "<category>$tag</category>
				";
			}
		}
		return $output;
	}
}


?>