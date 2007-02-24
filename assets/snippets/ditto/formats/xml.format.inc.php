<?php

// ---------------------------------------------------
// XML Parameters
// ---------------------------------------------------

$startID= (isset($_REQUEST['startID'])? $_REQUEST['startID']: $startID);

// set placeholders
$xml_placeholders['[+xml_copyright+]'] = isset($copyright) ? $copyright: $_lang['default_copyright'];
$xml_placeholders['[+xml_lang+]'] = (isset($abbrLanguage))? $abbrLanguage : $_lang['abbr_lang'];
$xml_placeholders['[+xml_link+]'] = $modx->config['site_url']."[~".$modx->documentObject['id']."~]";
$xml_placeholders['[+xml_ttl+]'] = isset($ttl) ? intval($ttl):120;
$xml_placeholders['[+xml_charset+]'] = isset($charset) ? $charset : $modx->config['modx_charset'];
$rss_placeholders['[+xml_xsl+]'] = isset($xsl) ? '<?xml-stylesheet type="text/xsl" href="'.$modx->config['site_url'].$xsl.'" ?>' : '';
// set tpl rss placeholders
$placeholders['*'] = "xml_parameters"; 
if(!function_exists("xml_parameters")) { 
	function xml_parameters($placeholders) {
		$xmlArr = array();
		foreach ($placeholders as $name=>$value) {
			$xmlArr["xml_".$name] = htmlentities($value);
		}
		$placeholders = array_merge($xmlArr,$placeholders);
		return $placeholders;	
	}
}
// set default templates

$xml_header = <<<TPL
<?xml version="1.0" encoding="[+xml_charset+]" ?>
[+xml_xsl+]
<xml version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
		<channel>
				<title>[*pagetitle*]</title>
				<link>[+xml_link+]</link>
				<description>[*description*]</description>
				<language>[+xml_lang+]</language>
				<copyright>[+xml_copyright+]</copyright>
				<ttl>[+xml_ttl+]</ttl>
TPL;

$xml_tpl = <<<TPL
		<item>
			<title>[+xml_pagetitle+]</title>
			<link>[(site_url)][~[+id+]~]</link>
			<summary><![CDATA[ [+xml_introtext+] ]]></summary>
			<date>[+xml_createdon+]</date>
			<createdon>[+xml_createdon+]</createdon>
			<author>[+xml_author+]</author>
			[+tags+]
		</item>
TPL;

$xml_footer = <<<TPL
			</channel>
</xml>
TPL;

// set template values

$header = isset($header) ? $header : template::replace($xml_placeholders,$xml_header);

$tpl = isset($tpl) ? $tpl : "@CODE:".$xml_tpl;

$footer = isset($footer) ? $footer : $xml_footer;

// set emptytext
$noResults = "      ";

?>