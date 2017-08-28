<?php

include_once(__DIR__ . '/tagging.extender.class.inc.php');

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
