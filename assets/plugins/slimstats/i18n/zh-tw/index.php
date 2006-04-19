<?php

/*
 * SlimStat: a simple web stats analyser based on ShortStat.
 * Copyright (C) 2005 Stephen Wettone
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
 *
 * Traditional Chinese Edition Translated by CGS(cgs.chen@gmail.com)
 *                                           http://www.cgs.tw/
 */

include_once( realpath( dirname( __FILE__ )."/../../_i18n.php" ) );

class SlimStatI18n extends SlimStatI18nBase {
	var $app_title;
	
	/** Name used to indicate hits to the homepage */
	var $homepage = "網頁";
	
	var $platforms = array(
		"win" => "Windows",
		"mac" => "Macintosh",
		"linux" => "Linux"
	);
	
	var $platform_win = "Windows";
	var $platform_mac = "Macintosh";
	var $platform_linux = "Linux";
	var $indeterminable = "不詳";
	var $crawler = "網頁機器人/搜尋引擎";
	
	var $hits = "網頁數";
	var $visits = "訪客數";
	var $uniques = "IP數";
	var $percentage = "%";
	
	var $period = "週期"; 	
	var $when = "時間";
	var $since = "從";
	var $none = "無";
	
	var $weekday_format = "l";
	var $hour_format = "ga";
	
	var $feed = "新聞交換";
	
	var $titles = array(
		"details" => "明細",
		"details_filtered" => "明細分析",
		"details_subtitle" => "選擇加入過濾條件的資料",
		"filters" => "過濾條件",
		"filters_subtitle" => "選擇移除的條件",
		"summary" => "綜合"
	);
	
	var $date_periods = array(
		"hour" => "時",
		"day" => "日",
		"week" => "週",
		"month" => "月",
		"today" => "今日",
		"yesterday" => "昨日",
		"this_week" => "本週",
		"last_week" => "上週",
		"this_month" => "本月",
		"last_month" => "上月"
	);
	
	var $link_titles = array(
		"details_all" => "顯示所有明細",
		"details_dt" => "顯示 DT 明細",
		"details_filtered" => "顯示以此欄位過濾的明細",
		"external" => "外部超連結到此欄位",
		"filter_remove" => "移除過濾條件",
		"whois" => "執行 whois 查詢"
	);
	
	var $fields = array(
		"dt" => "日期",
		"dt_start" => "日期開始",
		"dt_end" => "日期結束",
		"remote_ip" => "訪客IP",
		"remote_addr" => "訪客網址",
		"domain" => "參考網域",
		"referer" => "參考者",
		"platform" => "作業系統",
		"browser" => "瀏覽器",
		"version" => "版本",
		"resource" => "URL",
		"searchterms" => "搜尋字串",
		"country" => "國別",
		"language" => "語言",
		"visit" => "訪客" ,
		"weekday" => "星期",
		"hour" => "小時"		
	);
	
	var $name_lookups = array();
	
	var $module_titles = array(
		// Summary
		"summary"            => "綜合報表",
		"recent_referer"     => "最近參考網頁",
		"recent_searchterms" => "最近搜尋字串",
		"recent_resource"    => "最近URL",
		"unique_domain"      => "新參考網域",
		"unique_resource"    => "新URL",
		
		// Details
		"hourly"        => "小時總計",
		"daily"         => "本日總計",
		"weekly"        => "本週總計",
		"monthly"       => "本月總計",
		"resource"      => "URL",
		"next_resource" => "次URL",
		"searchterms"   => "搜尋字串",
		"domain"        => "參考網址",
		"referer"       => "參考者",
		"browser"       => "瀏覽器",
		"version"       => "瀏覽器版本",
		"platform"      => "作業系統",
		"country"       => "國別",
		"language"      => "語言",
		"remote_ip"     => "訪客IP數",
		"visit"         => "訪客數",
		"dayofweek"     => "星期",
		"hour"          => "小時",
		"pageviews"     => "瀏覽頁數"
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
		
		$this->app_title = $this->config->sitename ." 的 SlimStat 統計 ";
		
		parent::init();
	}
	
	function date_period_label( $_dt_start, $_dt_end=0 ) {
		return mb_convert_encoding( parent::date_period_label( $_dt_start, $_dt_end ), "UTF-8", "BIG-5" );
	}
	
	function time_period_label( $_dt_start, $_dt_end=0 ) {
		return mb_convert_encoding( parent::time_period_label( $_dt_start, $_dt_end ), "UTF-8", "BIG-5" );
	}
	
	function date_label( $_dt ) {
		return mb_convert_encoding( parent::date_label( $_dt ), "UTF-8", "BIG-5" );
	}
	
	function time_label( $_dt, $_compared_to_dt=0 ) {
		return mb_convert_encoding( parent::time_label( $_dt, $_compared_to_dt ), "UTF-8", "BIG-5" );
	}
	
}

?>
