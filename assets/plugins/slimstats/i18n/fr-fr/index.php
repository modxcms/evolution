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
	var $homepage = "page de garde";
	
	var $platforms = array(
		"win" => "Windows",
		"mac" => "Macintosh",
		"linux" => "Linux"
	);
	
	var $platform_win = "Windows";
	var $platform_mac = "Macintosh";
	var $platform_linux = "Linux";
	var $indeterminable = "Inconnu";
	var $crawler = "Moteur de recherche";
	
	var $hits = "Hits";
	var $visits = "Visites";
	var $uniques = "IPs";
	var $percentage = "%";
	
	var $period = "P&eacute;riode";
	var $when = "Quand";
	var $since = "Depuis";
	var $none = "Aucun";
	
	var $weekday_format = "l";
	var $hour_format = "H:i";
	
	var $feed = "fils";
	
	var $titles = array(
		"details" => "D&eacute;tails",
		"details_filtered" => "D&eacute;tails filtr&eacute;s",
		"details_subtitle" => "cliquez sur les donn&eacute;s pour ajouter des filtres",
		"filters" => "Filtres",
		"filters_subtitle" => "cliquez sur un filtre pour l'enlever",
		"summary" => "Sommaire"
	);
	
	var $date_periods = array(
		"hour" => "heure",
		"day" => "jour",
		"week" => "semaine",
		"month" => "mois",
		"today" => "Aujourd'hui",
		"yesterday" => "hier",
		"this_week" => "cette semaine",
		"last_week" => "la semaine derni&egrave;re",
		"this_month" => "ce mois ci",
		"last_month" => "le mois dernier"
	);
	
	var $link_titles = array(
		"details_all" => "Montrer toutes les d&eacute;tails",
		"details_dt" => "Montrer les details pour DT",
		"details_filtered" => "Montrer les details filtr&eacute; par ce CHAMP",
		"external" => "Lien externe pour ce champ (CHAMP)",
		"filter_remove" => "Enlevez ce champ",
		"whois" => "Faire une recherche whois"
	);
	
	var $fields = array(
		"dt" => "Date",
		"dt_start" => "Date Start",
		"dt_end" => "Date End",
		"remote_ip" => "Visiteur",
		"remote_addr" => "Adresse Visiteur",
		"domain" => "Domaine R&eacute;f&eacute;rent",
		"referer" => "R&eacute;f&eacute;rent",
		"platform" => "Platforme",
		"browser" => "Navigateur",
		"version" => "Version",
		"resource" => "Resource",
		"searchterms" => "Mots-cl&eacute;s",
		"country" => "Pays",
		"language" => "Langue",
		"visit" => "Visite",
		"weekday" => "Jour de semaine",
		"hour" => "Heure"
	);
	
	var $name_lookups = array();
	
	var $module_titles = array(
		// Summary
		"summary"            => "Sommaire",
		"recent_referer"     => "R&eacute;f&eacute;rents R&eacute;cents",
		"recent_searchterms" => "Mots-cl&eacute;s R&eacute;cents",
		"recent_resource"    => "Ressources R&eacute;centes",
		"unique_domain"      => "Nouveaux Domaines R&eacute;f&eacute;rents",
		"unique_resource"    => "Nouvelles Ressources",
		
		// Details
		"hourly"        => "Hits horaires",
		"daily"         => "Hits journaliers",
		"weekly"        => "Hits hebdomadaires",
		"monthly"       => "Hits mensuels",
		"resource"      => "Ressources",
		"next_resource" => "Ressources suivantes",
		"searchterms"   => "Mots-cl&eacute;s",
		"domain"        => "Domaines R&eacute;f&eacute;rents",
		"referer"       => "R&eacute;f&eacute;rents",
		"browser"       => "Navigateurs",
		"version"       => "Versions de Navigateurs",
		"platform"      => "Platformes",
		"country"       => "Pays",
		"language"      => "Langues",
		"remote_ip"     => "Visiteurs",
		"visit"         => "Visites",
		"dayofweek"     => "Jours de semaine",
		"hour"          => "Heures",
		"pageviews"     => "Pages Vues"
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
		
		$this->app_title = "SlimStat pour ".$this->config->sitename;
		
		parent::init();
	}
	
}

?>
