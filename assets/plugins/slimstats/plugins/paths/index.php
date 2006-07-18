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

include_once( realpath( dirname( __FILE__ )."/../../_functions.php" ) );

print SlimStat::render_module( "Paths taken by recent visitors", show_paths(), "largemodule" );

function show_paths() {
	$config =& SlimStatConfig::get_instance();
	
	// get max visit
	$query = "SELECT MAX(`visit`) FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."`";
	if ( $result = mysql_query( $query ) ) {
		list( $max_visit ) = mysql_fetch_row( $result );
	}
	
	$str = "";
	
	// get requests
	$query = "SELECT * FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."` WHERE ";
	if ( !$config->show_crawlers ) {
		$query .= "`browser` != '".SlimStat::my_esc( $config->i18n->crawler )."' AND ";
	}
	$query .= "`visit` >= ".( $max_visit - $config->rows );
	$query .= " ORDER BY `visit` DESC, `dt`";
	
	if ( $result = mysql_query( $query ) ) {
		$prev_visit = 0;
		$visits = array();
		$visit = array();
		$pages = array();
		while ( $assoc = mysql_fetch_assoc( $result ) ) {
			if ( $assoc["visit"] != $prev_visit && !empty( $visit ) ) {
				$visits[] = $visit;
				$visit = array();
			}
			$visit[] = $assoc;
			$prev_visit = $assoc['visit'];
		}
		if ( !empty( $visit ) ) {
			$visits[] = $visit;
		}
		$str .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		$str .= "<tr><th>".$config->i18n->fields["remote_ip"]."</th>";
		$str .= "<th>".$config->i18n->when."</th>";
		$str .= "<th>".$config->i18n->fields["browser"]."</th>";
		$str .= "<th>".$config->i18n->fields["platform"]."</th>";
		$str .= "<th>".$config->i18n->fields["country"]."</th></tr>\n";
		
		$usr_today = SlimStat::to_user_time( time() );
		$svr_today = SlimStat::to_server_time( mktime( 0, 0, 0, date( "n", $usr_today ), date( "d", $usr_today ), date( "Y", $usr_today ) ) );
		
		foreach ( $visits as $visit ) {
			$is_today = ( $visit[0]["dt"] >= $svr_today );
			$mindt = SlimStat::time_label( $visit[0]["dt"] );
			$maxdt = SlimStat::time_label( $visit[ sizeof( $visit )-1 ]["dt"] );
			$str .= "<tr><td class=\"accent\">".htmlentities( SlimStat::get_domain( $visit[0]["remote_ip"] ) )."</td>";
			$str .= "<td class=\"accent\">";
			if ( $is_today ) {
				$str .= ( ( $mindt == $maxdt ) ? $mindt : $mindt."-".$maxdt );
			} else {
				$str .= SlimStat::time_label( $visit[0]["dt"], time() );
			}
			$str .= "</td>";
			$str .= "<td class=\"accent\">".htmlentities( $visit[0]["browser"] );
			if ( $visit[0]["version"] != $config->i18n->indeterminable ) {
				$str .= " ".htmlentities( $visit[0]["version"] );
			}
			$str .="</td><td class=\"accent\">".htmlentities( $visit[0]["platform"] )."</td>";
			$str .= "<td class=\"accent\">".htmlentities( $visit[0]["country"] )."</td></tr>\n";
			
			$prev_dt = "";
			foreach ( $visit as $hit ) {
				$str .= "<tr><td>";
				$str .= "<a href=\"".$hit["resource"]."\" class=\"external\"";
				$str .= "\" title=\"".$config->i18n->link_title( "external", "FIELD", strtolower( $config->i18n->fields["resource"] ) )."\">";
				$str .= "<img src=\"external.gif\" width=\"9\" height=\"9\" alt=\"\" /></a>&nbsp;&nbsp;";
				$str .= "<a href=\"?filter_resource=".urlencode( $hit["resource"] );
				$str .= "\" title=\"".$config->i18n->link_title( "details_filtered", "FIELD", strtolower( $config->i18n->fields["resource"] ) )."\">";
				if ( isset( $config->i18n->name_lookups["resource"][ $hit["resource"] ] ) ) {
					$str .= SlimStat::truncate( $config->i18n->name_lookups["resource"][ $hit["resource"] ], 53 );
				} else {
					$str .= SlimStat::truncate( $hit["resource"], 53 );
				}
				$str .= "</a></td>";
				$dt_label = SlimStat::time_label( $hit["dt"] );
				if ( ( !$is_today && $prev_dt == "" ) || ( $mindt != $maxdt && $dt_label != $prev_dt ) ) {
					$str .= "<td>".$dt_label."</td>";
				} else {
					$str .= "<td>&nbsp;</td>";
				}
				$prev_dt = $dt_label;
				if ( $hit["referer"] != "" && $hit["domain"] != SlimStat::trim_referer( $_SERVER["SERVER_NAME"] ) ) {
					$str .= "<td colspan=\"3\" class=\"last\">";
					$str .= "<a href=\"?filter_domain=".urlencode( $hit["domain"] );
					$str .= "\" title=\"".$config->i18n->link_title( "details_filtered", "FIELD", strtolower( $config->i18n->fields["domain"] ) )."\"";
					$str .= ">".htmlentities( SlimStat::truncate( $hit["domain"], 30 ) )."</a>&nbsp;&nbsp;";
					$str .= "<a href=\"".$hit["referer"]."\" class=\"external\" rel=\"nofollow\"";
					$str .= "\" title=\"".$config->i18n->link_title( "external", "FIELD", strtolower( $config->i18n->fields["referer"] ) )."\">";
					$str .= "<img src=\"external.gif\" width=\"9\" height=\"9\" alt=\"\" /></a>";
				} else {
					$str .= "<td colspan=\"3\">&nbsp;</td>";
				}
				$str .= "</tr>\n";
			}
		}
		
		$str .= "</table>\n";
		
		return $str;
	}
}

?>
