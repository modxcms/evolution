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
	var $homepage = "Startseite";
	
	var $platforms = array(
		"win" => "Windows",
		"mac" => "Macintosh",
		"linux" => "Linux"
	);
	
	var $platform_win = "Windows";
	var $platform_mac = "Macintosh";
	var $platform_linux = "Linux";
	var $indeterminable = "Unbekannt";
	var $crawler = "Crawler/Suchmaschine";
	
	var $hits = "Aufrufe";
	var $visits = "Besucher";
	var $uniques = "IPs";
	var $percentage = "%";
	
	var $period = "Zeitraum";
	var $when = "wann";
	var $since = "seit";
	var $none = "nichts";
	
	var $weekday_format = "l";
	var $hour_format = "H:i";
	
	var $feed = "feed";
	
	var $titles = array(
		"details" => "Details",
		"details_filtered" => "Gefilterte Details",
		"details_subtitle" => "Ausw&auml;hlen um Filter zu setzen",
		"filters" => "Filter",
		"filters_subtitle" => "zum Entfernen ausw&auml;hlen",
		"summary" => "&Uuml;bersicht"
	);
	
	var $date_periods = array(
		"hour" => "Stunde",
		"day" => "Tag",
		"week" => "Woche",
		"month" => "Monat",
		"today" => "Heute",
		"yesterday" => "gestern",
		"this_week" => "diese Woche",
		"last_week" => "letzte Woche",
		"this_month" => "dieser Monat",
		"last_month" => "letzter Monat"
	);
	
	var $link_titles = array(
		"details_all" => "Alle Details anzeigen",
		"details_dt" => "Zeige Detials f&uuml;r DT",
		"details_filtered" => "Zeige nach diesem FIELD gefilterte Details",
		"external" => "Externer Link zu diesem Feld (to FIELD)",
		"filter_remove" => "Filter entfernen",
		"whois" => "whois Lookup durchf&uuml;hren"
	);
	
	var $fields = array(
		"dt" => "Datum",
		"dt_start" => "Startdatum",
		"dt_end" => "Enddatum",
		"remote_ip" => "Besucher-IP",
		"remote_addr" => "Besucheradresse",
		"domain" => "verweisende Domain",
		"referer" => "Verweis",
		"platform" => "Plattform",
		"browser" => "Browser",
		"version" => "Version",
		"resource" => "Resource",
		"searchterms" => "Suchbegriff",
		"country" => "Land",
		"language" => "Sprache",
		"visit" => "Besuch",
		"weekday" => "Wochentag",
		"hour" => "Stunde"
	);
	
	var $name_lookups = array();
	
	var $module_titles = array(
		// Summary
		"summary"            => "&Uuml;bersicht",
		"recent_referer"     => "aktuelle Verweise",
		"recent_searchterms" => "aktuelle Suchbegriffe",
		"recent_resource"    => "aktuelle Resourcen",
		"unique_domain"      => "Neue verweisende Domain",
		"unique_resource"    => "Neue Resource",
		
		// Details
		"hourly"        => "St&uuml;ndliche Treffer",
		"daily"         => "T&auml;gliche Treffer",
		"weekly"        => "W&ouml;chentliche Treffer",
		"monthly"       => "Monatliche Treffer",
		"resource"      => "Resourcen",
		"next_resource" => "N&auml;chste Resourcen",
		"searchterms"   => "Suchbegriffe",
		"domain"        => "Verweisende Domains",
		"referer"       => "Verweise",
		"browser"       => "Browser",
		"version"       => "Browser-Versionen",
		"platform"      => "Plattformen",
		"country"       => "L&auml;nder",
		"language"      => "Sprachen",
		"remote_ip"     => "Besucher",
		"visit"         => "Besuche",
		"dayofweek"     => "Wochentage",
		"hour"          => "Stunden",
		"pageviews"     => "Angezeigte Seiten"
	);
	
	/** Used to display visitors' language settings */
	var $languages = array(
		"af" => "Afrikaans",
		"sq" => "Albanisch",
		"ar-sa" => "Arabisch/Saudi-Arabien",
		"eu" => "Baskisch",
		"bg" => "Bulgarisch",
		"be" => "Belarussisch",
		"ca" => "Katalanisch",
		"zh" => "Chinesisch",
		"zh-cn" => "Chinesisch/China",
		"zh-tw" => "Chinesisch/Taiwan",
		"zh-hk" => "Chinesisch/Hongkong",
		"zh-sg" => "Chinesisch/Singapur",
		"hr" => "Kroatisch",
		"cs" => "Tschechisch",
		"da" => "D&auml;nisch",
		"nl" => "Niederl&auml;ndisch",
		"nl-nl" => "Niederl&auml;ndisch/Niederlande",
		"nl-be" => "Niederl&auml;ndisch/Belgien",
		"en" => "Englisch",
		"en-gb" => "Englisch/Gro&szlig;britannien",
		"en-us" => "Englisch/USA",
		"en-au" => "Englisch/Australien",
		"en-ca" => "Englisch/Kanada",
		"en-nz" => "Englisch/Neuseeland",
		"en-ie" => "Englisch/Irland",
		"en-za" => "Englisch/S&uuml;dafrika",
		"en-jm" => "Englisch/Jamaika",
		"en-bz" => "Englisch/Belize",
		"en-tt" => "Englisch/Trinidad",
		"eo" => "Esperanto",
		"et" => "Estnisch",
		"fo" => "F&auml;r&ouml;isch",
		"fa" => "Persisch",
		"fi" => "Finnisch",
		"fr" => "Franz&ouml;sisch",
		"fr-be" => "Franz&ouml;sisch/Belgien",
		"fr-fr" => "Franz&ouml;sisch/Frankreich",
		"fr-ch" => "Franz&ouml;sisch/Schweiz",
		"fr-ca" => "Franz&ouml;sisch/Kanada",
		"fr-lu" => "Franz&ouml;sisch/Luxemburg",
		"gd" => "G&auml;lisch",
		"gl" => "Galicisch",
		"de" => "Deutsch",
		"de-at" => "Deutsch/&Ouml;sterreich",
		"de-de" => "Deutsch/Deutschland",
		"de-ch" => "Deutsch/Schweiz",
		"de-lu" => "Deutsch/Luxemburg",
		"de-li" => "Deutsch/Liechtenstein",
		"el" => "Griechisch",
		"el-gr" => "Griechisch",
		"he" => "Hebräisch",
		"he-il" => "Hebräisch/Israel",
		"hi" => "Hindi",
		"hu" => "Ungarisch",
		"hu-hu" => "Ungarisch/Ungarn",
		"ie-ee" => "Internet Explorer/Easter Egg",
		"is" => "Isländisch",
		"id" => "Indonesisch",
		"id-id" => "Indonesisch",
		"in" => "Indonesisch",
		"ga" => "Irisch",
		"it" => "Italienisch",
		"it-ch" => "Italienisch/Schweiz",
		"ja" => "Japanisch",
		"ko" => "Koreanisch",
		"lv" => "Lettisch",
		"lt" => "Litauisch",
		"mk" => "Mazedonisch",
		"ms" => "Malaysisch",
		"mt" => "Maltesisch",
		"no" => "Norwegisch",
		"pl" => "Polnisch",
		"pt" => "Portugiesisch",
		"pt-br" => "Portugiesisch/Brasilien",
		"rm" => "Rätoromanisch",
		"ro" => "Rumänisch",
		"ro-mo" => "Rumänisch/Moldau",
		"ru" => "Russisch",
		"ru-ru" => "Russisch/Russland",
		"ru-mo" => "Russisch/Moldau",
		"gd" => "G&auml;lisch/Schottland",
		"sr" => "Serbisch",
		"sk" => "Slowakisch",
		"sl" => "Slowenisch",
		"sb" => "Serbisch",
		"es" => "Spanisch",
		"es-do" => "Spanisch",
		"es-ar" => "Spanisch/Argentien",
		"es-co" => "Spanisch/Kolumbien",
		"es-mx" => "Spanisch/Mexiko",
		"es-es" => "Spanisch/Spanien",
		"es-gt" => "Spanisch/Guatemala",
		"es-cr" => "Spanisch/Costa Rica",
		"es-pa" => "Spanisch/Panama",
		"es-ve" => "Spanisch/Venezuela",
		"es-pe" => "Spanisch/Peru",
		"es-ec" => "Spanisch/Ecuador",
		"es-cl" => "Spanisch/Chile",
		"es-uy" => "Spanisch/Uruguay",
		"es-py" => "Spanisch/Paraguay",
		"es-bo" => "Spanisch/Bolivien",
		"es-sv" => "Spanisch/El Salvador",
		"es-hn" => "Spanisch/Honduras",
		"es-ni" => "Spanisch/Nikaragua",
		"es-pr" => "Spanisch/Puerto Rico",
		"sx" => "Sutu",
		"sv" => "Schwedisch",
		"sv-se" => "Schwedisch/Schweden",
		"sv-fi" => "Schwedisch/Finnland",
		"th" => "Thailändisch",
		"tn" => "Setswana",
		"tr" => "T&uuml;rkisch",
		"uk" => "Ukrainisch",
		"ur" => "Urdu",
		"vi" => "Vietnamesisch",
		"xh" => "Xshosa",
		"ji" => "Jiddisch",
		"zu" => "Zulu"
	);
	
	function SlimStatI18n( $_config ) {
		$this->config = $_config;
		
		$this->app_title = "SlimStat f&uuml;r ".$this->config->sitename;
		
		parent::init();
	}
	
}

?>
