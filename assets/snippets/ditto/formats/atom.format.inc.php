<?php

// ---------------------------------------------------
// atom Parameters
// ---------------------------------------------------

$startID= (isset($_REQUEST['startID'])? $_REQUEST['startID']: $startID);

// set placeholders
$atom_placeholders['[+atom_lang+]'] = (isset($abbrLanguage))? $abbrLanguage : $_lang['abbr_lang'];
$atom_placeholders['[+atom_link+]'] = $modx->config['site_url']."[~".$modx->documentObject['id']."~]";
$atom_placeholders['[+atom_charset+]'] = isset($charset) ? $charset : $modx->config['modx_charset'];
$atom_placeholders['[+atom_lastmodified+]'] = date('Y-m-d\TH:i:s\Z', $modx->documentObject["editedon"]);
$placeholders['atom_createdon'] = array("createdon","atomCreatedDate"); 
$placeholders['atom_editedon'] = array("editedon","atomEditedDate");

function atomCreatedDate($resource) {
	return date('Y-m-d\TH:i:s\Z', intval($resource["createdon"]) + $modx->config["server_offset_time"]);
}
function atomEditedDate($resource) {
	return date('Y-m-d\TH:i:s\Z', intval($resource["editedon"]) + $modx->config["server_offset_time"]);
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
	<generator>Ditto 2.0 running on MODx</generator>
TPL;

$atom_tpl = <<<TPL

	<entry>
		<title>[+pagetitle+]</title>
		<link rel="alternate" type="text/html" href="[+url+]" />
		<author><name>[+author+]</name></author>
		<id>[+url+]</id>
		<updated>[+atom_editedon+]</updated>
		<published>[+atom_createdon+]</published>
		<content type="html">[+introtext+]</content>
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