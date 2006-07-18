<?php

/*
 * SlimView: a charting plugin for Stephen Wattone's SlimStat.
 * Copyright (C) 2006 Daniel Davis
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

include($modx->config['base_path'] . 'assets/plugins/slimstats/plugins/SlimView/functions.php'); // Need this for HSV <=> RGB and HEX <=> RGB conversions
include($modx->config['base_path'] . 'assets/plugins/slimstats/plugins/SlimView/svconfig.php');

$num_stats = 1;
$statcode = 'h';
if ( $config->show_visits ) {
	$num_stats++;
	$statcode .= 'v';
}
if ( $config->show_uniques ) {
	$num_stats++;
	$statcode .= 'u';
}

// Set the colours
// Try getting background-color from _css.css (h1 or .module h3) and picking the two triadic colours.
$css = file( $modx->config['base_path'] . 'assets/plugins/slimstats/_css.css');
$mod_colour = module_color(".module h3,", "background-color", $css);
$text_colour = (empty($text_colour)) ? hex2rgb($mod_colour) : hex2rgb($text_colour);
if(empty($data_colour1) || empty($data_colour2) || empty($data_colour3)) {
	$hsv = rgb2hsv($text_colour);
	$hsv = explode(',',$hsv);
	if($num_stats==1) {
		$H1 = $hsv[0]-0.5;
	} elseif($num_stats==2) {
		$H1 = $hsv[0]-0.417;
		$H2 = $hsv[0]+0.417;
	} else {
		$H1 = $hsv[0]-0.3;
		$H2 = $hsv[0]-0.7;
		$H3 = $hsv[0]-0.5;
	}
	for($i=1;$i<=$num_stats;$i++) {
		${"H".$i} = (${"H".$i}>1) ? ${"H".$i}-1 : ${"H".$i}; // If H>1, subtract 1
		${"H".$i} = (${"H".$i}<0) ? 1-(${"H".$i}*-1) : ${"H".$i}; // If H<0, subtract the (positive) difference between H1 and 0, from 1
		${"hsv".$i} = ${"H".$i}.','.$hsv[1].','.$hsv[2];
	}
}
$colours = array($text_colour);
for($i=1;$i<=$num_stats;$i++) {
	${"comp_colour".$i} = (empty(${"data_colour".$i})) ? hsv2rgb(${"hsv".$i}) : hex2rgb(${"data_colour".$i});
	$colours[] = ${"comp_colour".$i};
}
$colours = rawurlencode(serialize($colours));	

echo ('<h2><img src="http://'.$_SERVER['HTTP_HOST'].'/assets/plugins/slimstats/plugins/SlimView/legend.png.php?colours='.$colours.'&statcode='.$statcode.'" /></h2>');
 
if ( $config->show_modules["hourly"]  ) {
	$dt = mktime( 23, 59, 59 );
	
	print SlimStat::render_module(
		$config->i18n->module_titles["hourly"],
		hour_table( $dt, 24, '0', '$config->hour', $config->i18n->module_titles["hourly"], $colours ),
		"smallmodule"
	);
}

if ( $config->show_modules["daily"] ) {
	$dt = mktime( 12, 0, 0 );
	$days_from_now = 0;
	$days = 7;
	if ( isset( $days_spanned ) && $days_spanned > 1 && $days_spanned < 7 && $days_from_now > 0 && $days_from_now < 7 ) {
		$days += $days_from_now;
		$dt = mktime( 12, 0, 0 );
	}
	if ( isset( $days_spanned ) ) {
		if ( $days_spanned >= SlimStat::days_in_month( $filters["filter_dt_end"] ) ) {
			$days = $days_spanned;
		} elseif ( $hours_spanned < 24 ) {
			$days = min( $days_spanned, 7 );
		} else {
			while ( date( "w", $dt ) != $config->week_start_day ) {
				$dt += $config->day;
			}
			$dt -= $config->day;
		}
	}
	
	print SlimStat::render_module(
		$config->i18n->module_titles["daily"],
		date_table( $dt, $days, '0', '$config->day', $config->i18n->module_titles["daily"], $colours ),
		"smallmodule"
	);
}

if ( $config->show_modules["weekly"] && ( !isset( $hours_spanned ) || $hours_spanned >= 24 ) ) {
	$dt = mktime( 12, 0, 0 );
	while ( date( "w", $dt ) != $config->week_start_day ) { // move back to start of week
		$dt -= $config->day;
	}
	$weeks = ( isset( $days_spanned ) && $days_spanned > 7 && date( "w", mktime( 12, 0, 0, date( "n" ), SlimStat::days_in_month( $dt ) ) ) < 2 ) ? 6 : 5;
	if ( isset( $days_spanned ) && $days_spanned == 1 ) {
		$weeks = 1;
	}
	
	print SlimStat::render_module(
		$config->i18n->module_titles["weekly"],
		date_table( $dt, $weeks, '$config->day * 6', '$config->week', $config->i18n->module_titles["weekly"], $colours ),
		"smallmodule"
	);
}

if ( $config->show_modules["monthly"] && ( !isset( $days_spanned ) || $days_spanned >= 7 ) ) {
	$dt = mktime( 12, 0, 0 );
	while ( date( "j", $dt ) > 1 ) { // move back to first day of month
		$dt -= $config->day;
	}
	$months = 13;
	if ( isset( $days_spanned ) && $days_spanned == 7 ) {
		$months = 1;
	}
	
	print SlimStat::render_module(
		$config->i18n->module_titles["monthly"],
		date_table(
			$dt,
			$months,
			'$config->day * ( SlimStat::days_in_month( $_dt ) - 1 )',
			'$config->day * SlimStat::days_in_month( $_dt - ( $config->day * 28 ) )',
			$config->i18n->module_titles["monthly"],
			$colours
		),
		"smallmodule"
	);
}

function hour_table( $_dt, $_n_loops, $_end_increment, $_dt_decrement, $title, $colours ) {
	global $_filters;
	$config =& SlimStatConfig::get_instance();
	
	$data = array();
	$hour = date('G') + ($config->dt_offset_secs/3600);
	
	for( $i=$_n_loops-1;$i>=0;$i-- ) {
		$dt_start = strtotime( date( "Y-m-d H:00:00", $_dt ) ) - $config->dt_offset_secs;
		$dt_end = strtotime( date( "Y-m-d H:59:59", $_dt + eval( "return (".$_end_increment.");" ) ) ) - $config->dt_offset_secs;
		
		if ( $dt_start - $config->dt_offset_secs <= time() ) {
			$row = array();
			
			$assoc = SlimStat::get_hits_visits_uniques( $dt_start, $dt_end, $_filters );
			if ( $assoc["hits"] > 0 ) {
				$row[] = $assoc["hits"];
				if ( $config->show_visits ) {
					$row[] = $assoc["visits"];
				}
				if ( $config->show_uniques ) {
					$row[] = $assoc["uniques"];
				}
				$time = ($hour<10) ? '0'.$hour : $hour;
				$row[] = $time;
				
				$data[] = $row;
				$hour--;
			}
		}
		
		$_dt -= eval( "return (".$_dt_decrement.");" );
	}
	
	$str = '<span class="slimview">';
	$data_count = count($data);
	if($data_count==0) {
		$str .= (empty($config->i18n->no_data)) ? 'There is no data for this timespan yet.' : $config->i18n->no_data;
	} elseif($_REQUEST["output"]=="text") {
		echo ('<pre>');
		print_r($data);
		echo ('</pre>');
	} else {
		$data = array_reverse($data);
		$data = rawurlencode(serialize($data));	
		$str .= '<img src="http://'.$_SERVER['HTTP_HOST'].'/assets/plugins/slimstats/plugins/SlimView/slimview.png.php?data='.$data.'&colours='.$colours.'" alt="'.$title.'" />';
	}
	$str .= '</span>';
	
	return $str;
}

function date_table( $_dt, $_n_loops, $_end_increment, $_dt_decrement, $title, $colours ) {
	global $_filters;
	$config =& SlimStatConfig::get_instance();
	
	$data = array();	
	
	for ( $i=0; $i<$_n_loops; $i++ ) { // For each time period.
		$dt_start = strtotime( date( "Y-m-d 00:00:00", $_dt ) ) - $config->dt_offset_secs;
		$dt_end = strtotime( date( "Y-m-d 23:59:59", $_dt + eval( "return (".$_end_increment.");" ) ) ) - $config->dt_offset_secs;
		
		$assoc = SlimStat::get_hits_visits_uniques( $dt_start, $dt_end, $_filters );
		if ( $assoc["hits"] > 0 ) {
			$row = array();			
			$row[] = $assoc["hits"];
			if ( $config->show_visits && !isset( $_filters["filter_visit"] ) ) {
				$row[] = $assoc["visits"];
			}
			if ( $config->show_uniques ) {
				$row[] = $assoc["uniques"];
			}
			$time = SlimStat::date_period_label( $dt_start + $config->dt_offset_secs, $dt_end + $config->dt_offset_secs );
			$time = explode(" ",$time);
			$time = str_replace(",","",$time[0]);
			$row[] = ($title==$config->i18n->module_titles["weekly"]) ? $time.'+' : $time;
			
			$data[] = $row; // $row is one time period's data
		}
		$_dt -= eval( "return (".$_dt_decrement.");" );
	}
	
	$str = '<span class="slimview">';
	$data_count = count($data);
	if($data_count==0) {
		$str .= (empty($config->i18n->no_data)) ? 'There is no data for this timespan yet.' : $config->i18n->no_data;
	} elseif($_REQUEST["output"]=="text") {
		echo ('<pre>');
		print_r($data);
		echo ('</pre>');
	} else {
		$data = array_reverse($data);
		$data = rawurlencode(serialize($data));	
		$str .= '<img src="http://'.$_SERVER['HTTP_HOST'].'/assets/plugins/slimstats/plugins/SlimView/slimview.png.php?data='.$data.'&colours='.$colours.'" alt="'.$title.'" />';
	}
	$str .= '</span>';
	
	return $str;
}

?>
