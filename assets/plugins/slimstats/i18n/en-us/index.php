<?php

/*
 * SlimStat: a simple web stats analyser based on ShortStat.
 * Copyright (C) 2006 Stephen Wettone
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

include_once( realpath( dirname( __FILE__ )."/../../_i18n.php" ) );

class SlimStatI18n extends SlimStatI18nBase {
	var $app_title;
	
	/** Name used to indicate hits to the homepage */
	var $homepage = "homepage";
	
	var $platforms = array(
		"win" => "Windows",
		"mac" => "Macintosh",
		"linux" => "Linux"
	);
	
	var $platform_win = "Windows";
	var $platform_mac = "Macintosh";
	var $platform_linux = "Linux";
	var $indeterminable = "Unknown";
	var $crawler = "Crawler/Search Engine";
	
	var $hits = "Hits";
	var $visits = "Visits";
	var $uniques = "IPs";
	var $percentage = "%";
	
	var $period = "Period";
	var $when = "When";
	var $since = "Since";
	var $none = "None";
	
	var $weekday_format = "l";
	var $hour_format = "ga";
	
	var $feed = "feed";
	
	var $titles = array(
		"details" => "Details",
		"details_filtered" => "Filtered details",
		"details_subtitle" => "select data to add filters",
		"filters" => "Filters",
		"filters_subtitle" => "select to remove",
		"summary" => "Summary"
	);
	
	var $date_periods = array(
		"hour" => "hour",
		"day" => "day",
		"week" => "week",
		"month" => "month",
		"today" => "Today",
		"yesterday" => "yesterday",
		"this_week" => "this week",
		"last_week" => "last week",
		"this_month" => "this month",
		"last_month" => "last month"
	);
	
	var $link_titles = array(
		"details_all" => "Show all details",
		"details_dt" => "Show details for DT",
		"details_filtered" => "Show details filtered by this FIELD",
		"external" => "External link to FIELD",
		"filter_remove" => "Remove this filter",
		"whois" => "Perform whois lookup"
	);
	
	var $fields = array(
		"dt" => "Date",
		"dt_start" => "Date Start",
		"dt_end" => "Date End",
		"remote_ip" => "Visitor",
		"remote_addr" => "Visitor Address",
		"domain" => "Referring Domain",
		"referer" => "Referrer",
		"platform" => "Platform",
		"browser" => "Browser",
		"version" => "Version",
		"resource" => "Resource",
		"searchterms" => "Search String",
		"country" => "Country",
		"language" => "Language",
		"visit" => "Visit",
		"weekday" => "Weekday",
		"hour" => "Hour"
	);
	
	var $name_lookups = array();
	
	var $module_titles = array(
		// Summary
		"summary"            => "Summary",
		"recent_referer"     => "Recent Referrers",
		"recent_searchterms" => "Recent Search Strings",
		"recent_resource"    => "Recent Resources",
		"unique_domain"      => "New Referring Domains",
		"unique_resource"    => "New Resources",
		
		// Details
		"hourly"        => "Hourly hits",
		"daily"         => "Daily hits",
		"weekly"        => "Weekly hits",
		"monthly"       => "Monthly hits",
		"resource"      => "Resources",
		"next_resource" => "Next Resources",
		"searchterms"   => "Search Strings",
		"domain"        => "Referring Domains",
		"referer"       => "Referrers",
		"browser"       => "Browsers",
		"version"       => "Browser Versions",
		"platform"      => "Platforms",
		"country"       => "Countries",
		"language"      => "Languages",
		"remote_ip"     => "Visitors",
		"visit"         => "Visits",
		"dayofweek"     => "Weekdays",
		"hour"          => "Hours",
		"pageviews"     => "Pages Viewed"
	);
	
	/** Used to display visitors' language settings */
	var $languages = array(
		"af" => "Afrikaans",
		"sq" => "Albanian",
		"ar-sa" => "Arabic/Saudi Arabia",
		"eu" => "Basque",
		"bg" => "Bulgarian",
		"be" => "Byelorussian",
		"ca" => "Catalan",
		"zh" => "Chinese",
		"zh-cn" => "Chinese/China",
		"zh-tw" => "Chinese/Taiwan",
		"zh-hk" => "Chinese/Hong Kong",
		"zh-sg" => "Chinese/singapore",
		"hr" => "Croatian",
		"cs" => "Czech",
		"da" => "Danish",
		"nl" => "Dutch",
		"nl-nl" => "Dutch/Netherlands",
		"nl-be" => "Dutch/Belgium",
		"en" => "English",
		"en-gb" => "English/United Kingdom",
		"en-us" => "English/United States",
		"en-au" => "English/Australian",
		"en-ca" => "English/Canada",
		"en-nz" => "English/New Zealand",
		"en-ie" => "English/Ireland",
		"en-za" => "English/South Africa",
		"en-jm" => "English/Jamaica",
		"en-bz" => "English/Belize",
		"en-tt" => "English/Trinidad",
		"eo" => "Esperanto",
		"et" => "Estonian",
		"fo" => "Faeroese",
		"fa" => "Farsi",
		"fi" => "Finnish",
		"fr" => "French",
		"fr-be" => "French/Belgium",
		"fr-fr" => "French/France",
		"fr-ch" => "French/Switzerland",
		"fr-ca" => "French/Canada",
		"fr-lu" => "French/Luxembourg",
		"gd" => "Gaelic",
		"gl" => "Galician",
		"de" => "German",
		"de-at" => "German/Austria",
		"de-de" => "German/Germany",
		"de-ch" => "German/Switzerland",
		"de-lu" => "German/Luxembourg",
		"de-li" => "German/Liechtenstein",
		"el" => "Greek",
		"el-gr" => "Greek",
		"he" => "Hebrew",
		"he-il" => "Hebrew/Israel",
		"hi" => "Hindi",
		"hu" => "Hungarian",
		"hu-hu" => "Hungarian/Hungary",
		"ie-ee" => "Internet Explorer/Easter Egg",
		"is" => "Icelandic",
		"id" => "Indonesian",
		"in" => "Indonesian",
		"ga" => "Irish",
		"it" => "Italian",
		"it-ch" => "Italian/ Switzerland",
		"ja" => "Japanese",
		"ko" => "Korean",
		"lv" => "Latvian",
		"lt" => "Lithuanian",
		"mk" => "Macedonian",
		"ms" => "Malaysian",
		"mt" => "Maltese",
		"no" => "Norwegian",
		"pl" => "Polish",
		"pt" => "Portuguese",
		"pt-br" => "Portuguese/Brazil",
		"rm" => "Rhaeto-Romanic",
		"ro" => "Romanian",
		"ro-mo" => "Romanian/Moldavia",
		"ru" => "Russian",
		"ru-ru" => "Russian/Russia",
		"ru-mo" => "Russian /Moldavia",
		"gd" => "Scots Gaelic",
		"sr" => "Serbian",
		"sk" => "Slovack",
		"sl" => "Slovenian",
		"sb" => "Sorbian",
		"es" => "Spanish",
		"es-do" => "Spanish",
		"es-ar" => "Spanish/Argentina",
		"es-co" => "Spanish/Colombia",
		"es-mx" => "Spanish/Mexico",
		"es-es" => "Spanish/Spain",
		"es-gt" => "Spanish/Guatemala",
		"es-cr" => "Spanish/Costa Rica",
		"es-pa" => "Spanish/Panama",
		"es-ve" => "Spanish/Venezuela",
		"es-pe" => "Spanish/Peru",
		"es-ec" => "Spanish/Ecuador",
		"es-cl" => "Spanish/Chile",
		"es-uy" => "Spanish/Uruguay",
		"es-py" => "Spanish/Paraguay",
		"es-bo" => "Spanish/Bolivia",
		"es-sv" => "Spanish/El salvador",
		"es-hn" => "Spanish/Honduras",
		"es-ni" => "Spanish/Nicaragua",
		"es-pr" => "Spanish/Puerto Rico",
		"sx" => "Sutu",
		"sv" => "Swedish",
		"sv-se" => "Swedish/Sweden",
		"sv-fi" => "Swedish/Finland",
		"th" => "Thai",
		"tn" => "Tswana",
		"tr" => "Turkish",
		"uk" => "Ukrainian",
		"ur" => "Urdu",
		"vi" => "Vietnamese",
		"xh" => "Xshosa",
		"ji" => "Yiddish",
		"zu" => "Zulu"
	);
	
	function SlimStatI18n( $_config ) {
		$this->config = $_config;
		
		$this->app_title = "SlimStat for ".$this->config->sitename;
		
		parent::init();
	}
	
}

?>
