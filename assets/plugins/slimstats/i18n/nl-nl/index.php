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
	var $homepage = "homepagina";
	
	var $platforms = array(
		"win" => "Windows",
		"mac" => "Macintosh",
		"linux" => "Linux"
	);
	
	var $platform_win = "Windows";
	var $platform_mac = "Macintosh";
	var $platform_linux = "Linux";
	var $indeterminable = "Onbekend";
	var $crawler = "Crawler/Zoekmachine";
	
	var $hits = "Klikken";
	var $visits = "Bezoeken";
	var $uniques = "IP's";
	var $percentage = "%";
	
	var $period = "Period";
	var $when = "Wanneer";
	var $since = "Sinds";
	var $none = "Geen";
	
	var $weekday_format = "1";
	var $hour_format = "ga";
	
	var $feed = "feed";
	
	var $titles = array(
		"details" => "Details",
		"details_filtered" => "Gefilterde details",
		"details_subtitle" => "selecter gegevens voor toepassen filter",
		"filters" => "Filters",
		"filters_subtitle" => "selecteer filter om te verwijderen",
		"summary" => "Samenvatting"
	);
	
	var $date_periods = array(
		"hour" => "uur",
		"day" => "dag",
		"week" => "week",
		"month" => "maand",
		"today" => "vandaag",
		"yesterday" => "gisteren",
		"this_week" => "deze week",
		"last_week" => "vorige week",
		"this_month" => "deze maand",
		"last_month" => "vorige maand"
	);
	
	var $link_titles = array(
		"details_all" => "Toon alle details",
		"details_dt" => "Toon details voor DT",
		"details_filtered" => "Toon details gefilterd door dit FIELD",
		"external" => "Externe link naar FIELD",
		"filter_remove" => "Verwijder dit filter",
		"whois" => "Bekijk IP gegevens (whois lookup)"
	);
	
	var $fields = array(
		"dt" => "Datum",
		"dt_start" => "Startdatum",
		"dt_end" => "Einddatum",
		"remote_ip" => "Bezoeker",
		"remote_addr" => "Bezoekersadres",
		"domain" => "Verwijzend domein",
		"referer" => "Verwijzer",
		"platform" => "Platform",
		"browser" => "Browser",
		"version" => "Versie",
		"resource" => "Bron",
		"searchterms" => "Zoekterm",
		"country" => "Land",
		"language" => "Taal",
		"visit" => "Bezoek",
		"weekday" => "Weekdag",
		"hour" => "Uur"
	);
	
	var $name_lookups = array();
	
	var $module_titles = array(
		// Summary
		"summary"            => "Samenvatting",
		"recent_referer"     => "Recente Verwijzers",
		"recent_searchterms" => "Recente Zoektermen",
		"recent_resource"    => "Recente Bronnen",
		"unique_domain"      => "Nieuwe Verwijzende Domeinen",
		"unique_resource"    => "Nieuwe Bronnen",
		
		// Details
		"hourly"        => "Klikken per uur",
		"daily"         => "Klikken per dag",
		"weekly"        => "Klikken per week",
		"monthly"       => "Klikken per maand",
		"resource"      => "Bronnen",
		"next_resource" => "Volgende Bronnen",
		"searchterms"   => "Zoektermen",
		"domain"        => "Verwijzende Domeinen",
		"referer"       => "Verwijzers",
		"browser"       => "Browsers",
		"version"       => "Browser Versies",
		"platform"      => "Platformen",
		"country"       => "Landen",
		"language"      => "Talen",
		"remote_ip"     => "Bezoekers",
		"visit"         => "Bezoeken",
		"dayofweek"     => "Weekdagen",
		"hour"          => "Uren",
		"pageviews"     => "Bezochte pagina's"
	);
	
	/** Used to display visitors' language settings */
	var $languages = array(
		"af" => "Afrikaans",
		"sq" => "Albanees",
		"ar-sa" => "Arabisch/Saudi Arabië",
		"eu" => "Baskisch",
		"bg" => "Bulgaars",
		"be" => "Belarussisch",
		"ca" => "Catalaans",
		"zh" => "Chinees",
		"zh-cn" => "Chinees/China",
		"zh-tw" => "Chinees/Taiwan",
		"zh-hk" => "Chinees/Hong Kong",
		"zh-sg" => "Chinees/Singapore",
		"hr" => "Kroatisch",
		"cs" => "Tsjechisch",
		"da" => "Deens",
		"nl" => "Nederlands",
		"nl-nl" => "Nederlands/Nederland",
		"nl-be" => "Nederlands/België",
		"en" => "Engels",
		"en-gb" => "Engels/Verenigd Koninkrijk",
		"en-us" => "Engels/Verenigde Staten",
		"en-au" => "Engels/Australië",
		"en-ca" => "Engels/Canada",
		"en-nz" => "Engels/Nieuw Zeeland",
		"en-ie" => "Engels/Ierland",
		"en-za" => "Engels/Zuid Afrika",
		"en-jm" => "Engels/Jamaica",
		"en-bz" => "Engels/Belize",
		"en-tt" => "Engels/Trinidad",
		"eo" => "Esperanto",
		"et" => "Ests",
		"fo" => "Faëroees",
		"fa" => "Farsi",
		"fi" => "Fins",
		"fr" => "Frans",
		"fr-be" => "Frans/België",
		"fr-fr" => "Frans/Frankrijk",
		"fr-ch" => "Frans/Zwitzerland",
		"fr-ca" => "Frans/Canada",
		"fr-lu" => "Frans/Luxemburg",
		"gd" => "Gaelisch",
		"gl" => "Gallisch",
		"de" => "Duits",
		"de-at" => "Duits/Oostenrijk",
		"de-de" => "Duits/Duitsland",
		"de-ch" => "Duits/Zwiterland",
		"de-lu" => "Duits/Luxemburg",
		"de-li" => "Duits/Liechtenstein",
		"el" => "Grieks",
		"el-gr" => "Grieks",
		"he" => "Hebreeuws",
		"he-il" => "Hebreeuws/Israël",
		"hi" => "Hindi",
		"hu" => "Hongaars",
		"hu-hu" => "Hongaars/Hongarije",
		"ie-ee" => "Internet Explorer/Easter Egg",
		"is" => "IJslands",
		"id" => "Indonesisch",
		"in" => "Indonesisch",
		"ga" => "Iers",
		"it" => "Italiaans",
		"it-ch" => "Italiaans/Zwitserland",
		"ja" => "Japans",
		"ko" => "Koreaans",
		"lv" => "Lets",
		"lt" => "Litouws",
		"mk" => "Macedonisch",
		"ms" => "Maleisisch",
		"mt" => "Maltees",
		"no" => "Noors",
		"pl" => "Pools",
		"pt" => "Portugees",
		"pt-br" => "Portugees/Brazilië",
		"rm" => "Raeto Romaans",
		"ro" => "Romaans",
		"ro-mo" => "Romaans/Moldavië",
		"ru" => "Russisch",
		"ru-ru" => "Russisch/Rusland",
		"ru-mo" => "Russisch /Moldavië",
		"gd" => "Schots Gaelisch",
		"sr" => "Servisch",
		"sk" => "Slowaaks",
		"sl" => "Sloveens",
		"sb" => "Sorbisch",
		"es" => "Spaans",
		"es-do" => "Spaans",
		"es-ar" => "Spaans/Argentinië",
		"es-co" => "Spaans/Colombia",
		"es-mx" => "Spaans/Mexico",
		"es-es" => "Spaans/Spanje",
		"es-gt" => "Spaans/Guatemala",
		"es-cr" => "Spaans/Costa Rica",
		"es-pa" => "Spaans/Panama",
		"es-ve" => "Spaans/Venezuela",
		"es-pe" => "Spaans/Peru",
		"es-ec" => "Spaans/Ecuador",
		"es-cl" => "Spaans/Chili",
		"es-uy" => "Spaans/Uruguay",
		"es-py" => "Spaans/Paraguay",
		"es-bo" => "Spaans/Bolivia",
		"es-sv" => "Spaans/El Salvador",
		"es-hn" => "Spaans/Honduras",
		"es-ni" => "Spaans/Nicaragua",
		"es-pr" => "Spaans/Puerto Rico",
		"sx" => "Sutu",
		"sv" => "Zweeds",
		"sv-se" => "Zweeds/Zweden",
		"sv-fi" => "Zweeds/Finland",
		"th" => "Thais",
		"tn" => "Tswana",
		"tr" => "Turks",
		"uk" => "Oekraiens",
		"ur" => "Urdu",
		"vi" => "Vietnamees",
		"xh" => "Xhosa",
		"ji" => "Jiddish",
		"zu" => "Zoeloe"
	);
	
	function SlimStatI18n( $_config ) {
		$this->config = $_config;
		
		$this->app_title = "SlimStat for ".$this->config->sitename;
		
		parent::init();
	}
	
}

?>
