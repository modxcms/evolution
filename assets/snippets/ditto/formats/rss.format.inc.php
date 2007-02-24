<?php

// ---------------------------------------------------
// RSS Parameters
// ---------------------------------------------------

// set placeholders
$rss_placeholders['[+rss_copyright+]'] = isset($copyright) ? $copyright: $_lang['default_copyright'];
$rss_placeholders['[+rss_lang+]'] = (isset($abbrLanguage))? $abbrLanguage : $_lang['abbr_lang'];
$rss_placeholders['[+rss_link+]'] = $modx->config['site_url']."[~".$modx->documentObject['id']."~]";
$rss_placeholders['[+rss_ttl+]'] = isset($ttl) ? intval($ttl):120;
$rss_placeholders['[+rss_charset+]'] = isset($charset) ? $charset : $modx->config['modx_charset'];
$rss_placeholders['[+rss_xsl+]'] = isset($xsl) ? '<?xml-stylesheet type="text/xsl" href="'.$modx->config['site_url'].$xsl.'" ?>' : ''; 

$dateSource = isset($dateSource) ? $dateSource : "createdon";
	// date type to display (values can be createdon, pub_date, editedon)
	
// set tpl rss placeholders
$placeholders['rss_date'] = array($dateSource,"rss_date");
$placeholders['rss_pagetitle'] = array("pagetitle","rss_pagetitle");
$placeholders['rss_author'] = array("createdby","rss_author"); 

if(!function_exists("rss_date")) { 
	function rss_date($resource) {
		return date("r",  intval($resource["createdon"]) + $modx->config["server_offset_time"]);
	}
}
if(!function_exists("rss_pagetitle")) { 
	function rss_pagetitle($resource) {
		return htmlspecialchars(html_entity_decode($resource['pagetitle'], ENT_QUOTES));
	}
}
if(!function_exists("rss_author")) { 
	function rss_author($resource) {
		return htmlspecialchars(html_entity_decode(ditto::getAuthor($resource['createdby']), ENT_QUOTES));
	}
}
// set default templates

$rss_header = <<<TPL
<?xml version="1.0" encoding="[+rss_charset+]" ?>
[+rss_xsl+]
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<channel>
			<title>[*pagetitle*]</title>
			<link>[+rss_link+]</link>
			<description>[*description*]</description>
			<language>[+rss_lang+]</language>
			<copyright>[+rss_copyright+]</copyright>
			<ttl>[+rss_ttl+]</ttl>
TPL;

$rss_tpl = <<<TPL

			<item>
				<title>[+rss_pagetitle+]</title>
				<link>[(site_url)][~[+id+]~]</link>
				<description><![CDATA[ [+introtext+] ]]></description>
				<pubDate>[+rss_date+]</pubDate>
				<guid isPermaLink="false">[(site_url)][~[+id+]~]</guid>
				<dc:creator>[+rss_author+]</dc:creator>
				[+tagLinks+]
			</item>
		
TPL;

$rss_footer = <<<TPL
	</channel>
</rss>
TPL;

// set template values

$header = isset($header) ? $header : template::replace($rss_placeholders,$rss_header);

$tpl = isset($tpl) ? $tpl : "@CODE:".$rss_tpl;

$footer = isset($footer) ? $footer : $rss_footer;

// set emptytext
$noResults = "      ";

?>