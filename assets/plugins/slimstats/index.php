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


//require_once( realpath( dirname( __FILE__ ) )."/_functions.php" );
if(IN_MANAGER_MODE!="true") die("Can't access the file directly");

$start_time = SlimStat::getmicrotime();

$config = SlimStatConfig::get_instance();

SlimStat::connect();

$filters = array();
foreach ( array_keys( $_GET ) as $key ) {
	if ( substr( $key, 0, 7 ) == "filter_" && array_key_exists( substr( $key, 7 ), $config->i18n->fields ) ) {
		$filters[$key] = urlencode( $_GET[$key] );
	}
}
if ( isset( $_GET["new_filter_field"] ) &&
     array_key_exists( $_GET["new_filter_field"], $config->i18n->fields ) &&
     isset( $_GET["new_filter_value"] ) ) {
	$filters["filter_".$_GET["new_filter_field"]] = urlencode( $_GET["new_filter_value"] );
}
ksort( $filters );


$today_dt_start = SlimStat::to_user_time( time() );
$today_dt_start = SlimStat::to_server_time( mktime( 0, 0, 0, date( "n", $today_dt_start ), date( "d", $today_dt_start ), date( "Y", $today_dt_start ) ) );
$today_dt_end = SlimStat::to_user_time( time() );
$today_dt_end = SlimStat::to_server_time( mktime( 23, 59, 59, date( "n", $today_dt_end ), date( "d", $today_dt_end ), date( "Y", $today_dt_end ) ) );

$thisweek_dt_start = SlimStat::to_user_time( $today_dt_start );
while ( date( "w", $thisweek_dt_start ) != $config->week_start_day ) { // move back to start of week
	$thisweek_dt_start = mktime( 0, 0, 0, date( "n", $thisweek_dt_start ), date( "d", $thisweek_dt_start ) - 1, date( "Y", $thisweek_dt_start ) );
}
$thisweek_dt_end = SlimStat::to_server_time( mktime( 23, 59, 59, date( "n", $thisweek_dt_start ), date( "d", $thisweek_dt_start ) + 6, date( "Y", $thisweek_dt_start ) ) );
$thisweek_dt_start = SlimStat::to_server_time( $thisweek_dt_start );

$thismonth_dt_start = SlimStat::to_server_time( mktime( 0, 0, 0, date( "n", SlimStat::to_user_time( time() ) ), 1, date( "Y", SlimStat::to_user_time( time() ) ) ) );
$thismonth_dt_end = SlimStat::to_server_time( mktime( 23, 59, 59, date( "n", SlimStat::to_user_time( time() ) ) + 1, 0, date( "Y", SlimStat::to_user_time( time() ) ) ) );

if ( isset( $filters["filter_dt_start"] ) && isset( $filters["filter_dt_end"] ) ) {
	$dt_start = min( intval( $filters["filter_dt_start"] ), intval( $filters["filter_dt_end"] ) );
	$dt_end = max( intval( $filters["filter_dt_start"] ), intval( $filters["filter_dt_end"] ) );
	if ( $dt_start > 0 && $dt_end > 0 ) {
		$filters["filter_dt_start"] = $dt_start;
		$filters["filter_dt_end"] = $dt_end;
		if ( !isset( $_GET["show"] ) && sizeof( $filters ) == 2 ) {
			if ( $dt_start == $today_dt_start && $dt_end == $today_dt_end ) {
				$_GET["show"] = "today";
			} elseif ( $dt_start == $thisweek_dt_start && $dt_end == $thisweek_dt_end ) {
				$_GET["show"] = "thisweek";
			} elseif ( $dt_start == $thismonth_dt_start && $dt_end == $thismonth_dt_end ) {
				$_GET["show"] = "thismonth";
			}
		}
	} else {
		unset( $filters["filter_dt_start"] );
		unset( $filters["filter_dt_end"] );
	}
} elseif ( isset( $_GET["show"] ) && $_GET["show"] == "today" ) {
	$filters["filter_dt_start"] = $today_dt_start;
	$filters["filter_dt_end"] = $today_dt_end;
} elseif ( isset( $_GET["show"] ) && $_GET["show"] == "thisweek" ) {
	$filters["filter_dt_start"] = $thisweek_dt_start;
	$filters["filter_dt_end"] = $thisweek_dt_end;
} elseif ( isset( $_GET["show"] ) && $_GET["show"] == "thismonth" ) {
	$filters["filter_dt_start"] = $thismonth_dt_start;
	$filters["filter_dt_end"] = $thismonth_dt_end;
}

if ( isset( $filters["filter_dt_start"] ) && isset( $filters["filter_dt_end"] ) ) {
	$days_spanned = ceil( ( $filters["filter_dt_end"] - $filters["filter_dt_start"] ) / $config->day );
	$hours_spanned = ceil( ( $filters["filter_dt_end"] - $filters["filter_dt_start"] ) / $config->hour );
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $config->language; ?>" xml:lang="<?php print $config->language; ?>">
<head>
<title><?php print $config->i18n->app_title; ?></title>
	<link rel="stylesheet" type="text/css" media="all" href="_css.css" />
	<meta name="robots" content="noindex,nofollow" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
</head>
<body>
<div id="body">
<h1><?php print $config->i18n->app_title; ?></h1>

<ul id="menu">
<?php

$included_file = "_details.php";

if ( empty( $filters ) && !isset( $_GET["show"] ) ) {
	?><li class="selected"><?php print ucfirst( $config->i18n->titles["summary"] ); ?></li><?php
	$included_file = "_summary.php";
} else {
	?><li><a href="index.php?a=68"><?php print ucfirst( $config->i18n->titles["summary"] ); ?></a></li><?php
}
if ( empty( $filters ) && isset( $_GET["show"] ) && $_GET["show"] == "details" ) {
	?><li class="selected"><?php print ucfirst( $config->i18n->titles["details"] ); ?></li><?php
} else {
	?><li><a href="?a=68&show=details"><?php print ucfirst( $config->i18n->titles["details"] ); ?></a></li><?php
}
$menu_options = array(
	"today" => ucfirst( $config->i18n->date_periods["today"] ),
	"thisweek" => ucfirst( $config->i18n->date_periods["this_week"] ),
	"thismonth" => ucfirst( $config->i18n->date_periods["this_month"] )
);
foreach ( array_keys( $menu_options ) as $menu_option ) {
	if ( isset( $_GET["show"] ) && $_GET["show"] == $menu_option && sizeof( $filters ) == 2 ) {
		?><li class="selected"><?php print $menu_options[$menu_option]; ?></li><?php
	} else {
		?><li><a href="?a=68&show=<?php print $menu_option; ?>"><?php print $menu_options[$menu_option]; ?></a></li><?php
	}
}
if ( !empty( $filters ) && !isset( $_GET["show"] ) ) {
	?><li class="selected"><?php print ucfirst( $config->i18n->titles["details_filtered"] ); ?></li><?php
}
$plugins_location =  realpath( dirname( __FILE__ ) )."/plugins";
if ( is_dir( $plugins_location ) ) {
	$plugins_dh = opendir( $plugins_location );
	while ( ( $plugin_dir = readdir( $plugins_dh ) ) !== false ) {
		if ( $plugin_dir{0} != '.' && is_dir( $plugins_location."/".$plugin_dir ) && file_exists( $plugins_location."/".$plugin_dir."/index.php" ) ) {
			if ( isset( $_GET["show"] ) && strtolower( $_GET["show"] ) == strtolower( $plugin_dir ) ) {
				?><li class="selected"><?php print ucfirst( $plugin_dir ); ?></li><?php
				$included_file = $plugins_location."/".$plugin_dir."/index.php";
			} else {
				?><li><a href="?a=68&show=<?php print strtolower( $plugin_dir ); ?><?php print ( !empty( $filters ) ) ? '&amp;'.SlimStat::implode_assoc( '=', '&amp;', $filters ) : ''; ?>"><?php print ucfirst( $plugin_dir ); ?></a></li><?php
			}
		}
	}
}

?>
</ul>

<div style="clear:both;"></div>
<?php

include( $included_file );

?>
<div style="clear:both"></div>
</div>

<div id="donotremove"><a href="http://wettone.com/code/slimstat">SlimStat</a> v<?php print $config->version; ?> &copy; 2006 Stephen Wettone. Based on ShortStat by <a href="http://www.shauninman.com/">Shaun Inman</a><?php
if ( SlimStat::is_ip_to_country_installed() ) {
	?><br />This application uses the <a href="http://ip-to-country.webhosting.info/">IP-to-Country Database</a> provided by <a href="http://www.webhosting.info/">webhosting.info</a><?php
}
?></div>
<!--<?php print number_format( SlimStat::getmicrotime() - $start_time, 2 ); ?>-->
</body>
</html>
