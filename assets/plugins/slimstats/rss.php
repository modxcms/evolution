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

ob_start( "ob_gzhandler" );

include_once( realpath( dirname( __FILE__ ) )."/_config.php" );
include_once( realpath( dirname( __FILE__ ) )."/_functions.php" );

$config =& SlimStatConfig::get_instance();

SlimStat::connect();

$recent_threshold = time() - $config->week;

header( "Content-Type: application/xml" );
print "<?xml version=\"1.0\" encoding=\"iso-8859-1\""."?".">\n";
print "<rss version=\"2.0\">\n";

if ( array_key_exists( "QUERY_STRING", $_SERVER ) ) {
	if ( array_key_exists( $_SERVER["QUERY_STRING"], $config->i18n->fields ) ) {
		
		$field = $_SERVER["QUERY_STRING"];
		$results = SlimStat::get_unique_data( array( "field" => $field ), "dt > ".$recent_threshold, $field, array() );
		
		print "<channel>\n";
		print "<title>SlimStat: ".$config->i18n->fields[$field]." feed for ".$config->sitename."</title>\n";
		print "<link>http://".$_SERVER["SERVER_NAME"].dirname( $_SERVER["PHP_SELF"] )."/</link>\n";
		print "<description>SlimStat: ".$config->sitename.": ".$config->i18n->fields[$field]."</description>\n";
		print "<pubDate>".date( "r" )."</pubDate>\n";
		print "<lastBuildDate>".date( "r", $results[0]["mindt"] )."</lastBuildDate>\n";
		print "<docs>http://blogs.law.harvard.edu/tech/rss</docs>\n";
		print "<ttl>60</ttl>\n\n";
		
		foreach ( $results as $result ) {
			print "<item>\n";
			print "<title>".$result[$field]."</title>\n";
			print "<link>http://".$_SERVER["SERVER_NAME"].dirname( $_SERVER["PHP_SELF"] )."/?filter_".$field."=".urlencode( $result[$field] )."</link>\n";
			print "<description>".$config->i18n->fields[$field].": ".$result[$field]."</description>\n";
			print "<guid isPermaLink=\"true\">http://".$_SERVER["SERVER_NAME"].dirname( $_SERVER["PHP_SELF"] )."/?filter_".$field."=".urlencode( $result[$field] )."</guid>\n";
			print "<pubDate>".date( "r", $result["mindt"] )."</pubDate>\n";
			print "</item>\n\n";
		}
		
		print "</channel>\n";
		
	} elseif ( $_SERVER["QUERY_STRING"] == "daily" ) {
		
		print "<channel>\n";
		print "<title>SlimStat: Daily feed for ".$config->sitename."</title>\n";
		print "<link>http://".$_SERVER["SERVER_NAME"].dirname( $_SERVER["PHP_SELF"] )."/</link>\n";
		print "<description>SlimStat: ".$config->sitename.": ".$config->i18n->module_titles["daily"]."</description>\n";
		print "<pubDate>".date( "r" )."</pubDate>\n";
		print "<lastBuildDate>".date( "r", strtotime( date( "Y-m-d 00:00:00" ) ) )."</lastBuildDate>\n";
		print "<docs>http://blogs.law.harvard.edu/tech/rss</docs>\n";
		print "<ttl>60</ttl>\n\n";
		
		$dt = time() - ( 60 * 60 * 24 );
		for ( $i=0; $i<7; $i++ ) {
			$dt_start = strtotime( date( "Y-m-d 00:00:00", $dt ) ) - $config->dt_offset_secs;
			$dt_end = strtotime( date( "Y-m-d 23:59:59", $dt ) ) - $config->dt_offset_secs;
			$filters = array(
				"filter_dt_start" => $dt_start,
				"filter_dt_end" => $dt_end
			);
			
			$hvu = SlimStat::get_hits_visits_uniques( $dt_start, $dt_end );
			print "<item>\n";
			print "<title>".SlimStat::date_period_label( $dt_start + $config->dt_offset_secs, $dt_end + $config->dt_offset_secs )."</title>\n";
			print "<link>http://".$_SERVER["SERVER_NAME"].dirname( $_SERVER["PHP_SELF"] )."/?filter_dt_start=".$dt_start."&amp;filter_dt_end=".$dt_end."</link>\n";
			print "<description>";
			if ( isset( $hvu["hits"] ) ) {
				print $config->i18n->hits.": ".$hvu["hits"]."&lt;br /&gt;";
			}
			if ( isset( $hvu["visits"] ) ) {
				print $config->i18n->visits.": ".$hvu["visits"]."&lt;br /&gt;";
			}
			if ( isset( $hvu["uniques"] ) ) {
				print $config->i18n->uniques.": ".$hvu["uniques"]."&lt;br /&gt;";
			}
			
			print "</description>\n";
			print "<guid isPermaLink=\"true\">http://".$_SERVER["SERVER_NAME"].dirname( $_SERVER["PHP_SELF"] )."/?filter_dt_start=".$dt_start."&amp;filter_dt_end=".$dt_end."</guid>\n";
			print "<pubDate>".date( "r", $dt_end )."</pubDate>\n";
			print "</item>\n\n";
			
			$dt -= $config->day;
		}
		
		print "</channel>\n";
		
	}
}

print "</rss>\n";

?>
