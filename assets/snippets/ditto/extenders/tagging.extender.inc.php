<?php

/*
 * Title: Tagging
 * Purpose:
 *  	Collection of parameters, functions, and classes that expand
 *  	Ditto's functionality to include tagging
*/

// ---------------------------------------------------
// Tagging Parameters
// ---------------------------------------------------

$landing = isset($tagDocumentID) ? $tagDocumentID : $modx->documentObject['id'];
/*
	Param: tagDocumentID

	Purpose:
 	ID for tag links to point to

	Options:
	Any MODX document with a Ditto call setup to receive the tags
	
	Default:
	Current MODX Document
*/
$source = isset($tagData) ? $tagData : "";
/*
	Param: tagData

	Purpose:
 	Field to get the tags from

	Options:
	Comma separated list of MODX fields or TVs
	
	Default:
	[NULL]
*/
$caseSensitive = isset($caseSensitive) ? $caseSensitive : 0;
/*
	Param: caseSensitive

	Purpose:
 	Determine whether or not tag matching and duplicate tag removal are case sensitive

	Options:
	0 - off
	1 - on
	
	Default:
	0 - off
*/
$mode = isset($tagMode) ? $tagMode: "onlyTags";
/*
	Param: tagMode

	Purpose:
 	Filtering method to remove tags

	Options:
	onlyAllTags - show documents that have all of the tags
	onlyTags - show documents that have any of the tags
	removeAllTags - remove documents that have all of the tags
	removeTags - documents that have any of the tags
	
	Default:
	"onlyTags"
*/
$delimiter= isset($tagDelimiter) ? $tagDelimiter: " ";
/*
	Param: tagDelimiter

	Purpose:
 	Delimiter that splits each tag in the tagData source

	Options:
	Any character not included in the tags themselves
	
	Default:
	" " - space
*/
$displayDelimiter= isset($tagDisplayDelimiter) ? $tagDisplayDelimiter: $delimiter;
/*
	Param: tagDisplayDelimiter

	Purpose:
 	What separates the tags in [+tagLinks+]

	Options:
	Any character

	Default:
	&tagDelimiter
*/
$sort= isset($tagSort) ? $tagSort: 1;
/*
	Param: tagSort

	Purpose:
 	Sort the tags alphanumerically

	Options:
	0 - off
	1 - on

	Default:
	1 - on
*/
$displayMode= isset($tagDisplayMode) ? $tagDisplayMode: 1;
/*
	Param: tagDisplayMode

	Purpose:
 	How to display the tags in [+tagLinks+]

	Options:
	1 - string of links &tagDisplayDelimiter separated 
	2 - ul/li list

	Note:
	Output of individual items can be customized by <tplTagLinks>
	
	Default:
	1 - string of links &tagDisplayDelimiter separated 
*/
$givenTags = !empty($tags) ? trim($tags) : false;
/*
	Param: tags

	Purpose:
 	Allow the user to provide initial tags to be filtered

	Options:
	Any valid tags separated by <tagDelimiter>
	
	Default:
	[NULL]
*/
$tplTagLinks = !empty($tplTagLinks) ? template::fetch($tplTagLinks) : false;
/*
	Param: tplTagLinks

	Purpose:
 	Define a custom template for the tagLinks placeholder

	Options:
	- Any valid chunk name
	- Code via @CODE
	- File via @FILE
	
	Default:
	(code)
	<a href="[+url+]" class="ditto_tag" rel="tag">[+tag+]</a>	
*/
$callback = !empty($tagCallback) ? trim($tagCallback) : false;
/*
	Param: tagCallback

	Purpose:
 	Allow the user to modify both where the tags come from and how they are parsed.

	Options:
	Any valid function name
	
	Default:
	[NULL]
	
	Notes:
	The function should expect to receive the following three parameters:
	tagData - the provided source of the tags
	resource - the resource array for the document being parsed
	array - return the results in an array if true
*/

// ---------------------------------------------------
// Tagging Class
// ---------------------------------------------------
if(!class_exists("tagging")) {
	class tagging {
		var $delimiter,$source,$landing,$mode,$format,$givenTags,$caseSensitive, $displayDelimiter, $sort, $displayMode, $tpl, $callback;

		function tagging($delimiter,$source,$mode,$landing,$givenTags,$format,$caseSensitive, $displayDelimiter, $callback, $sort, $displayMode, $tpl) {
			$this->delimiter = $delimiter;
			$this->source = $this->parseTagData($source);
			$this->mode = $mode;
			$this->landing = $landing;
			$this->format = $format;
			$this->givenTags = $this->prepGivenTags($givenTags);
			$this->caseSensitive = $caseSensitive;
			$this->displayDelimiter = $displayDelimiter;
			$this->sort = $sort;
			$this->displayMode = $displayMode;
			$this->tpl = $tpl;
			$this->callback = $callback;
		}
	
		function prepGivenTags ($givenTags) {
			global $modx,$_GET,$dittoID;

			$getTags = !empty($_GET[$dittoID.'tags']) ? $modx->stripTags(trim($_GET[$dittoID.'tags'])) : false;
				// Get tags from the $_GET array

			$tags1 = array();
			$tags2= array();
		
			if ($getTags !== false) {
				$tags1 = explode($this->delimiter,$getTags);
			}
		
			if ($givenTags !== false) {
				$tags2 = explode($this->delimiter,$givenTags);		
			} 
		
			$kTags = array();
			$tags = array_merge($tags1,$tags2);
			foreach ($tags as $tag) {
				if (!empty($tag)) {				
					if ($this->caseSensitive) {
						$kTags[trim($tag)] = trim($tag);
					} else {
						$kTags[strtolower(trim($tag))] = trim($tag);
					}
				}
			}
			return $kTags;
		}

		function tagFilter ($value) {
			if ($this->caseSensitive == false) {
				$documentTags = array_values(array_flip($this->givenTags));
				$filterTags = array_values(array_flip($this->combineTags($this->source, $value,true)));
			} else {
				$documentTags = $this->givenTags;
				$filterTags =$this->combineTags($this->source, $value,true);
			}
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
			return $this->tagLinks($this->combineTags($this->source,$resource,true), $this->delimiter, $this->landing, $this->format);
		}
	
		function parseTagData($tagData,$names=array()) {
			return explode(",",$tagData);
		}

		function combineTags($tagData, $resource, $array=false) {
			if ($this->callback !== false) {
				return call_user_func_array($this->callback,array('tagData'=>$tagData,'resource'=>$resource,'array'=>$array));
			}
			$tags = array();
			foreach ($tagData as $source) {
				if(!empty($resource[$source])) {
					$tags[] = $resource[$source];
				}
			}		
			$kTags = array();
			$tags = explode($this->delimiter,implode($this->delimiter,$tags));
			foreach ($tags as $tag) {
				if (!empty($tag)) {
					if ($this->caseSensitive) {
						$kTags[trim($tag)] = trim($tag);
					} else {
						$kTags[strtolower(trim($tag))] = trim($tag);
					}
				}
			}
			return ($array == true) ? $kTags : implode($this->delimiter,$kTags);
		}

		function tagLinks($tags, $tagDelimiter, $tagID=false, $format="html") {
			global $ditto_lang,$modx;
			if(count($tags) == 0 && $format=="html") {
				return $ditto_lang['none'];
			} else if (count($tags) == 0 && ($format=="rss" || $format=="xml" || $format == "xml")) 
			{
				return "<category>".$ditto_lang['none']."</category>";
			}

			$output = "";
			if ($this->sort) {
				ksort($tags);
			}
			
			// set templates array
			$tplRss = "\r\n"."				<category>[+tag+]</category>";
			$tpl = ($this->tpl == false) ? '<a href="[+url+]" class="ditto_tag" rel="tag">[+tag+]</a>' : $this->tpl;
			
			$tpl = (($format == "rss" || $format == "xml" || $format == "atom") && $templates['user'] == false) ? $tplRss : $tpl; 
			
			if ($this->displayMode == 1) {
				foreach ($tags as $tag) {
					$tagDocID = (!$tagID) ? $modx->documentObject['id'] : $tagID;
					$url = ditto::buildURL("tags=$tag&start=0",$tagDocID);
					$output .= template::replace(array('url'=>$url,'tag'=>$tag),$tpl);
					$output .= ($format != "rss" && $format != "xml" && $format != "atom") ? $this->displayDelimiter : '';
				}			
			} else if ($format != "rss" && $format != "xml" && $format != "atom" && $this->displayMode == 2) {
				$tagList = array();
				foreach ($tags as $tag) {
					$tagDocID = (!$tagID) ? $modx->documentObject['id'] : $tagID;
					$url = ditto::buildURL("tags=$tag&start=0",$tagDocID);
					$tagList[] = template::replace(array('url'=>$url,'tag'=>$tag),$tpl);
				}
				$output = $modx->makeList($tagList, $ulroot='ditto_tag_list', $ulprefix='ditto_tag_', $type='', $ordered=false, $tablevel=0);
			}
			
			return ($format != "rss" && $format != "xml" && $format != "atom") ? substr($output,0,-1*strlen($this->displayDelimiter)) : $output;
		}
	}
}

// ---------------------------------------------------
// Tagging Parameters
// ---------------------------------------------------

$tags = new tagging($delimiter,$source,$mode,$landing,$givenTags,$format,$caseSensitive,$displayDelimiter, $callback, $sort, $displayMode,$tplTagLinks);

if (count($tags->givenTags) > 0) {
	$filters["custom"]["tagging"] = array($source,array($tags,"tagFilter")); 
		// set tagging custom filter
}

//generate TagList
$modx->setPlaceholder($dittoID."tagLinks",$tags->tagLinks($tags->givenTags, $delimiter, $landing, $format));
/*
	Placeholder: tagLinks
	
	Content:
	Nice 'n beautiful tag list with links pointing to <tagDocumentID>
*/
// set raw tags placeholder
$modx->setPlaceholder($dittoID."tags",implode($delimiter,$tags->givenTags));
/*
	Placeholder: tags
	
	Content:
	Raw tags separated by <tagDelimiter>
*/
// set tagging placeholder			
$placeholders['tagLinks'] = array(array($source,"*"),array($tags,"makeLinks"));
?>
