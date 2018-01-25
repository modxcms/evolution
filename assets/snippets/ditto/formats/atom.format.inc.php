<?php
/*
 * Title: ATOM
 * Purpose:
 *  	Collection of parameters, functions, and classes that expand
 *  	Ditto's output capabilities to include ATOM
*/

// set placeholders
$atom_placeholders['atom_lang'] = (isset($abbrLanguage))? $abbrLanguage : $_lang['abbr_lang'];
/*
	Param: abbrLanguage

	Purpose:
	Language for the Atom feed

	Options:
	Any valid 2 character language abbreviation

	Default:
	[LANG]

	Related:
	- <language>
*/
$atom_placeholders['atom_link'] = $modx->config['site_url']."[~".$modx->documentObject['id']."~]";
$atom_placeholders['atom_charset'] = isset($charset) ? $charset : $modx->config['modx_charset'];
/*
	Param: charset

	Purpose:
	Charset to use for the Atom feed

	Options:
	Any valid charset identifier

	Default:
	MODX default charset
*/

$atom_placeholders['atom_lastmodified'] = date('Y-m-d\TH:i:s\Z', $modx->documentObject["editedon"]);
$placeholders['*'] = "atom_placeholders";
$placeholders['atom_createdon'] = array("createdon","atomCreatedDate"); 
$placeholders['atom_editedon'] = array("editedon","atomEditedDate");
$placeholders['atom_author'] = array("createdby","atomCreatedBy");

if(!function_exists("atomCreatedDate")) {
	function atomCreatedDate($resource) {
		return date('Y-m-d\TH:i:s\Z', intval($resource["createdon"]) + $modx->config["server_offset_time"]);
	}
}
if(!function_exists("atomEditedDate")) {
	function atomEditedDate($resource) {
		return date('Y-m-d\TH:i:s\Z', intval($resource["editedon"]) + $modx->config["server_offset_time"]);
	}
}
if(!function_exists("atomCreatedBy")) { 
	function atomCreatedBy($resource) {
		return htmlspecialchars(html_entity_decode(ditto::getAuthor($resource['createdby']), ENT_QUOTES));
	}
}
$extenders[] = "summary";
	// load required summary extender for backwards compatibility
	// TODO: Remove summary extender in next major version

// set atom placeholders
if(!function_exists("atom_placeholders")) { 
	function atom_placeholders($placeholders) {
		$field = array();
		foreach ($placeholders as $name=>$value) {
			$field["atom_escaped_".$name] = htmlspecialchars(html_entity_decode($value));
		}
		$placeholders = array_merge($field,$placeholders);
		return $placeholders;	
	}
}

// set default templates

$atom_header = <<<TPL
<?xml version="1.0" encoding="[+atom_charset+]"?>
<feed xmlns="http://www.w3.org/2005/Atom"
      xml:lang="[+atom_lang+]"
      xml:base="[(site_url)]">
	<id>[(site_url)][~[*id*]~]</id>
	<title>[*pagetitle*]</title>
	<link rel="self" type="text/xml" href="[+atom_link+]" />
	<author><name>[(site_name)]</name></author>
	<updated>[+atom_lastmodified+]</updated>
	<generator>Ditto 2.0 running on MODX</generator>
TPL;

$atom_tpl = <<<TPL

	<entry>
		<title>[+atom_escaped_pagetitle+]</title>
		<link rel="alternate" type="text/html" href="[+url+]" />
		<author><name>[+atom_author+]</name></author>
		<id>[+url+]</id>
		<updated>[+atom_editedon+]</updated>
		<published>[+atom_createdon+]</published>
		<content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml">[+summary+]</div></content>
	</entry>
TPL;

$atom_footer = <<<TPL

</feed>
TPL;

// set template values

$header = isset($header) ? $header : template::replace($atom_placeholders,$atom_header);

$tpl = isset($tpl) ? $tpl : "@CODE:".$atom_tpl;

$footer = isset($footer) ? $footer : $atom_footer;

// set emptytext
$noResults = "      ";

?>