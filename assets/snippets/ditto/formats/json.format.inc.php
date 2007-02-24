<?php

// ---------------------------------------------------
// JSON Parameters
// ---------------------------------------------------

$startID= (isset($_REQUEST['startID'])? $_REQUEST['startID']: $startID);

// set json placeholders
$json_placeholders['[+json_lang+]'] = (isset($abbrLanguage))? $abbrLanguage : $_lang['abbr_lang'];
$json_placeholders['[+json_copyright+]'] = isset($copyright) ? $copyright:'[(site_name)] 2006';
$json_placeholders['[+json_link+]'] = $modx->config['site_url']."[~".$modx->documentObject['id']."~]";
$json_placeholders['[+json_ttl+]'] = isset($ttl) ? intval($ttl):120;
$json_placeholders['[+json_op+]'] = (!empty($_REQUEST['jsonp']) ? $_REQUEST['jsonp'] : '');

// set tpl JSON placeholders
$placeholders['*'] = "json_parameters";
if(!function_exists("json_parameters")) { 
	function json_parameters($placeholders) {
		$jsonArr = array();
		foreach ($placeholders as $name=>$value) {
			$jsonArr["json_".$name] = addslashes($value);
		}
		$placeholders = array_merge($jsonArr,$placeholders);
		return $placeholders;	
	}
}
// ---------------------------------------------------
// JSON Templates
// ---------------------------------------------------

$json_header = '
[+json_op+]{
 "title":"[*pagetitle*]",
 "link":"[+json_link+]",
 "description":"[*description*]",
 "language":"[+json_lang+]",
 "copyright":"[+json_copyright+]",
 "ttl":"[+json_ttl+]",
 "entries":[
';
	// not heredoc because { cannont be used as a char in heredoc

$json_tpl = <<<TPL
	{
	 "title":"[+json_pagetitle+]",
	 "link":"[(site_url)][~[+id+]~]",
	 "date":"[+json_createdon+]",
	 "guid":"[(site_url)][~[+id+]~]",
	 "author":"[+json_author+]",
	 "description":"[+json_description+]",
	 "introtext":"[+json_introtext+]",
	},

TPL;

$json_tpl_last = <<<TPL
	{
	 "title":"[+json_pagetitle+]",
	 "link":"[(site_url)][~[+id+]~]",
	 "date":"[+json_createdon+]",
	 "guid":"[(site_url)][~[+id+]~]",
	 "author":"[+json_author+]",
	 "description":"[+json_description+]",
	 "introtext":"[+json_introtext+]",
	}
TPL;

$json_footer = <<<TPL

 ]
}
TPL;

// ---------------------------------------------------
// Pass JSON Templates To Snippet
// ---------------------------------------------------

$header = isset($header) ? $header : template::replace($json_placeholders,$json_header);

$tpl = isset($tpl) ? $tpl : "@CODE:".$json_tpl;

$tplLast = isset($tplLast) ? $tplLast : "@CODE:".$json_tpl_last;

$footer = isset($footer) ? $footer : $json_footer;

// set emptytext
$noResults = "      ";

?>