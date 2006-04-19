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

include_once( realpath( dirname( __FILE__ ) )."/_config.php" );

class SlimStat {
	/**
	 * Database connection
	 */
	function connect() {
		$config =& SlimStatConfig::get_instance();
		if ( @!mysql_connect( $config->server, $config->username, $config->password ) ) {
			return false;
		}
		/*if ( @!mysql_select_db( $config->database ) ) {
			return false;
		}*/
		return true;
	}
	
	/**
	 * Confirms the existence of the IP-to-country database (http://ip-to-country.webhosting.info/)
	 */
	function is_ip_to_country_installed() {
		$config =& SlimStatConfig::get_instance();
		$query = "SELECT * FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->countries )."` LIMIT 0, 1";
		return ( $result = mysql_query( $query ) ) ? mysql_num_rows( $result ) : 0;
	}
	
	function _get_data( $_query ) {
		//print $_query;
		$results = array();
		if ( $result = mysql_query( $_query ) ) {
			while ( $assoc = mysql_fetch_assoc( $result ) ) {
				$results[] = $assoc;
			}
		}
		return $results;
	}
	
	function _get_table_data( $_col, $_where_clause="", $_group_by, $_order_by, $_filters=array() ) {
		$config =& SlimStatConfig::get_instance();
		
		$col_query_str = "";
		if ( is_array( $_col ) ) {
			foreach ( $_col as $this_col ) {
				$col_query_str .= SlimStat::my_esc( $this_col ).", ";
			}
			$col_query_str = substr( $col_query_str, 0, -2 );
		} else {
			$col_query_str = SlimStat::my_esc( $_col );
		}
		
		$query = "SELECT ".$col_query_str.", COUNT(`id`) AS `hits`";
		if ( $config->show_visits ) { $query .= ", COUNT(DISTINCT `visit`) AS `visits`"; }
		if ( $config->show_uniques ) { $query .= ", COUNT(DISTINCT `remote_ip`) AS `uniques`"; }
		$query .= " FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."` WHERE ";
		if ( $_where_clause != "" ) {
			$query .= $_where_clause." AND ";
		}
		$query .= SlimStat::get_filter_clause( $_filters );
		$query .= " GROUP BY ".$_group_by." ORDER BY `".$_order_by."` DESC, `hits` DESC, `dt` LIMIT 0, ".SlimStat::my_esc( $config->rows );
		
		return SlimStat::_get_data( $query );
	}
	
	function get_unique_data( $_col, $_where_clause="", $_group_by, $_filters=array() ) {
		$config =& SlimStatConfig::get_instance();
		
		$col_query_str = "";
		if ( is_array( $_col ) ) {
			foreach ( $_col as $this_col ) {
				$col_query_str .= "`".SlimStat::my_esc( $this_col )."`, ";
			}
			$col_query_str = substr( $col_query_str, 0, -2 );
		} else {
			$col_query_str = "`".SlimStat::my_esc( $_col )."`";
		}
		
		$query = "SELECT ".$col_query_str.", MIN(`dt`) AS `mindt`, COUNT(`id`) AS `hits`";
		if ( $config->show_visits ) { $query .= ", COUNT(DISTINCT `visit`) AS `visits`"; }
		if ( $config->show_uniques ) { $query .= ", COUNT(DISTINCT `remote_ip`) AS `uniques`"; }
		$query .= " FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."` WHERE ";
		if ( $_where_clause != "" ) {
			$query .= $_where_clause." AND ";
		}
		$query .= SlimStat::get_filter_clause( $_filters );
		$query .= " GROUP BY ".SlimStat::my_esc( $_group_by )." ORDER BY `mindt` DESC LIMIT 0, ".SlimStat::my_esc( $config->rows );
		
		$results = SlimStat::_get_data( $query );
		$actual_results = array();
		foreach ( $results as $result ) {
			$query = "SELECT MIN(`dt`) AS `mindt` FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."` WHERE ";
			
			$col_query_str = "";
			if ( is_array( $_col ) ) {
				if ( isset( $_col["display"] ) ) {
					$col_query_str .= "`".SlimStat::my_esc( $_col["display"] )."`='".$result[$_col["display"]]."'";
				} else {
					foreach ( $_col as $this_col ) {
						$col_query_str .= "`".SlimStat::my_esc( $this_col )."`='".$result[$this_col]."' AND ";
					}
					$col_query_str = substr( $col_query_str, 0, -5 );
				}
			} else {
				$col_query_str = "`".SlimStat::my_esc( $_col )."`=".$result[$_col];
			}
			$query .= $col_query_str;
			
			if ( $query_result = mysql_query( $query ) ) {
				if ( list( $mindt ) = mysql_fetch_row( $query_result ) ) {
					if ( $mindt > $config->recent_threshold ) {
						$actual_results[] = $result;
					}
				}
			}
		}
		return $actual_results;
	}
	
	function get_visit_data( $_filters=array() ) {
		$config =& SlimStatConfig::get_instance();
		
		$query = "SELECT `visit`, `remote_ip`, COUNT(`id`) AS `hits`, MIN(`dt`) AS `mindt`, MAX(`dt`) as `maxdt` FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."` WHERE ".SlimStat::get_filter_clause( $_filters )." GROUP BY `visit` ORDER BY `hits` DESC, `mindt` DESC, `maxdt` DESC LIMIT 0, ".SlimStat::my_esc( $config->rows );
		
		return SlimStat::_get_data( $query );
	}
	
	function get_pageviews_data( $_where_clause, $_filters=array() ) {
		$config =& SlimStatConfig::get_instance();
		
		$query = "SELECT COUNT(`id`) AS `hits` FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."` WHERE ";
		if ( $_where_clause != "" ) {
			$query .= $_where_clause." AND ";
		}
		$query .= SlimStat::get_filter_clause( $_filters )." GROUP BY `visit`";
		
		$raw_data = SlimStat::_get_data( $query );
		
		$data = array();
		foreach ( $raw_data as $datum ) {
			if ( array_key_exists( $datum["hits"], $data ) ) {
				$data[ $datum["hits"] ]++;
			} else {
				$data[ $datum["hits"] ] = 1;
			}
		}
		ksort( $data );
		
		return $data;
	}
	
	function render_module( $_title, $_content, $_class="" ) {
		$str = "<div class=\"module";
		if ( $_class != "" ) {
			$str .= " ".$_class;
		}
		$str .= "\">\n";
		$str .= "<h3>".$_title."</h3>\n";
		$str .= "<div>".$_content."</div>\n";
		$str .= "</div>\n";
		return $str;
	}
	
	function render_table( $_thead, $_tbody, $_show_changes=false ) {
		$config =& SlimStatConfig::get_instance();
		
		// head
		$str = "<table cellspacing=\"0\">\n\t<thead><tr>";
		if ( is_array( $_thead["first"] ) ) {
			foreach ( $_thead["first"] as $col ) {
				$str .= "<th>".$col."</th>";
			}
		} else {
			$str .= "<th>".$_thead["first"]."</th>";
		}
		if ( is_array( $_thead["last"] ) ) {
			foreach ( $_thead["last"] as $col ) {
				$str .= "<th class=\"last\">".$col."</th>";
			}
		} else {
			$str .= "<th class=\"last\">".$_thead["last"]."</th>";
		}
		$str .= "</tr></thead>\n";
		
		// body
		if ( is_array( $_tbody ) && !empty( $_tbody ) ) {
			$sizeof_tbody = sizeof( $_tbody );
			for ( $row=0; $row<$sizeof_tbody; $row++ ) {
				$str .= "\t<tr>";
				if ( is_array( $_tbody[$row]["first"] ) ) {
					for ( $col=0; $col<sizeof( $_tbody[$row]["first"] ); $col++ ) {
						$str .= "<td>".$_tbody[$row]["first"][$col]."</td>";
					}
				} else {
					$str .= "<td>".$_tbody[$row]["first"]."</td>";
				}
				if ( is_array( $_tbody[$row]["last"] ) ) {
					for ( $col=0; $col<sizeof( $_tbody[$row]["last"] ); $col++ ) {
						$str .= "<td class=\"last";
						if ( $_show_changes == true && $row < ( $sizeof_tbody - 1 ) ) {
							if ( $_tbody[$row]["last"][$col] < ( $_tbody[$row+1]["last"][$col] / 1.1 ) ) {
								if ( $_tbody[$row]["last"][$col] < ( $_tbody[$row+1]["last"][$col] / 1.3 ) ) {
									$str .= " very";
								}
								$str .= " negative";
							} elseif ( $_tbody[$row]["last"][$col] > ( $_tbody[$row+1]["last"][$col] * 1.1 ) ) {
								if ( $_tbody[$row]["last"][$col] > ( $_tbody[$row+1]["last"][$col] * 1.3 ) ) {
									$str .= " very";
								}
								$str .= " positive";
							}
						}
						$str .= "\">".$_tbody[$row]["last"][$col]."</td>";
					}
				} else {
					$str .= "<td class=\"last\">".$_tbody[$row]["last"]."</td>";
				}
				$str .= "</tr>\n";
			}
		} else {
			$colspan = ( is_array( $_thead["first"] ) ) ? sizeof( $_thead["first"] ) : 1;
			$colspan += ( is_array( $_thead["last"] ) ) ? sizeof( $_thead["last"] ) : 1;
			$str .= "\t<tr>";
			$str .= "<td colspan=\"".$colspan."\"><em>".$config->i18n->none."</em></td>";
			$str .= "</tr>\n";
		}
		$str .= "</table>\n";
		
		return $str;
	}
	
	function _count( $_count, $_where_clause="", $_filters=array() ) {
		$config =& SlimStatConfig::get_instance();
		
		$query = "SELECT COUNT(".SlimStat::my_esc( $_count ).") FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."` WHERE ";
		if ( $_where_clause != "" ) {
			$query .= $_where_clause." AND ";
		}
		$query .= SlimStat::get_filter_clause( $_filters );
		
		if ( $result = mysql_query( $query ) ) {
			if ( list( $count ) = mysql_fetch_row( $result ) ) {
				return $count;
			}
		}
		return 1;
	}
	
	function get_hits( $_where_clause, $_filters=array() ) {
		return SlimStat::_count( "`id`", $_where_clause, $_filters );
	}
	
	function get_visits( $_where_clause, $_filters ) {
		return SlimStat::_count( "DISTINCT `visit`", $_where_clause, $_filters );
	}
	
	function get_uniques( $_where_clause, $_filters ) {
		return SlimStat::_count( "DISTINCT `remote_ip`", $_where_clause, $_filters );
	}
	
	function get_total_hits_visits_uniques() {
		return SlimStat::get_hits_visits_uniques( SlimStat::get_first_hit() );
	}
	
	function count_hits_visits_uniques( $_where_clause="", $_filters=array() ) {
		$config =& SlimStatConfig::get_instance();
		
		$query = "SELECT COUNT(`id`) AS `hits`";
		/*if ( $config->show_visits ) {*/ $query .= ", COUNT(DISTINCT `visit`) AS `visits`"; /*}*/
		/*if ( $config->show_uniques ) {*/ $query .= ", COUNT(DISTINCT `remote_ip`) AS `uniques`"; /*}*/
		$query .= " FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."` WHERE ";
		if ( $_where_clause != "" ) {
			$query .= $_where_clause." AND ";
		}
		$query .= SlimStat::get_filter_clause( $_filters );
		
		if ( $result = mysql_query( $query ) ) {
			if ( $datum = mysql_fetch_assoc( $result ) ) {
				return $datum;
			}
		}
		
		return array( "hits" => 0, "visits" => 0, "uniques" => 0 );
	}
	
	function get_hits_visits_uniques( $_dt_start, $_dt_end=0, $_filters=array() ) {
		$config =& SlimStatConfig::get_instance();
		
		if ( !empty( $_filters ) ) {
			return SlimStat::count_hits_visits_uniques( "`dt`>=".intval( $_dt_start )." AND `dt`<=".intval( $_dt_end ), $_filters );
		} elseif ( $_dt_end == 0 ) {
			return SlimStat::count_hits_visits_uniques( "`dt`>=".intval( $_dt_start ) );
		} elseif ( $_dt_end > time() ) {
			return SlimStat::count_hits_visits_uniques( "`dt`>=".intval( $_dt_start )." AND `dt`<=".intval( $_dt_end ) );
		} else {
			$query = "SELECT `hits`, `visits`, `uniques`";
			$query .= " FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->dt_table )."`";
			$query .= " WHERE `dt_start`=".intval( $_dt_start )." AND `dt_end`=".intval( $_dt_end )." LIMIT 1";
			
			if ( $result = mysql_query( $query ) ) {
				if ( mysql_num_rows( $result ) == 1 ) {
					if ( $hvu = mysql_fetch_assoc( $result ) ) {
						return $hvu;
					}
				} else {
					$hvu = SlimStat::count_hits_visits_uniques( "`dt`>=".intval( $_dt_start )." AND `dt`<=".intval( $_dt_end ) );
					$query = "INSERT INTO `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->dt_table )."`";
					$query .= " ( `dt_start`, `dt_end`, `hits`, `visits`, `uniques` ) VALUES ( ";
					$query .= intval( $_dt_start ).", ".intval( $_dt_end ).", ".intval( $hvu["hits"] ).", ".intval( $hvu["visits"] ).", ".intval( $hvu["uniques"] )." )";
					//print $query;
					mysql_query( $query );
					
					return $hvu;
				}
			}
		}
	}
	
	function get_first_hit( $_filters=array() ) {
		$config =& SlimStatConfig::get_instance();
		
		$query = "SELECT `dt` FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."`";
		$query .= " WHERE ".SlimStat::get_filter_clause( $_filters )." ORDER BY `dt` ASC LIMIT 0, 1";
		if ( $result = mysql_query( $query ) ) {
			if ( list( $dt ) = mysql_fetch_row( $result ) ) {
				return $dt + $config->dt_offset_secs;
			}
		}
		return 0;
	}
	
	function get_dt_filter_clause( $_filters ) {
		if ( isset( $_filters["filter_dt_start"] ) && isset( $_filters["filter_dt_end"] ) ) {
			return "`dt` >= ".SlimStat::my_esc( $_filters["filter_dt_start"] )." AND `dt` <= ".SlimStat::my_esc( $_filters["filter_dt_end"] );
		} else {
			return "1=1";
		}
	}
	
	function get_field_filter_clause( $_field, $_filters ) {
		if ( isset( $_filters["filter_".$_field] ) ) {
			return "`".SlimStat::my_esc( $_field )."` = '".SlimStat::my_esc( urldecode( $_filters["filter_".$_field] ) )."'";
		} else {
			return "1=1";
		}
	}
	
	function get_filter_clause( $_filters=array() ) {
		$config =& SlimStatConfig::get_instance();
		
		$clauses = array();
		$clauses[] = SlimStat::get_dt_filter_clause( $_filters );
		$clauses[] = SlimStat::get_filter_clause_without_dt( $_filters );
		$clauses = array_unique( $clauses );
		return implode( " AND ", $clauses );
	}
	
	function get_filter_clause_without_dt( $_filters=array() ) {
		$config =& SlimStatConfig::get_instance();
		
		$clauses = array();
		foreach ( array_keys( $config->i18n->fields ) as $field ) {
			if ( substr( $field, 0, 3 ) != "dt_" ) {
				$clauses[] = SlimStat::get_field_filter_clause( $field, $_filters );
			}
		}
		if ( !$config->show_crawlers ) {
			$clauses[] = "`browser` != '".SlimStat::my_esc( $config->i18n->crawler )."'";
		}
		$clauses = array_unique( $clauses );
		return implode( " AND ", $clauses );
	}
	
	function truncate( $_str, $_len ) {
		$config =& SlimStatConfig::get_instance();
		
		if ( empty( $_str ) || strlen( $_str ) <= $_len ) {
			return $_str;
		}
		
		if ( preg_match( "/^([^\s]{1,5})\s(.*)$/", strrev( substr( $_str, 0, $_len ) ), $match ) ) {
			return strrev( $match[2] )."&hellip;";
		} else {
			$new_str = "";
			$new_str_ems = 0;
			$i = 0;
			while ( $new_str_ems < $_len && $i < strlen( $_str ) ) {
				$chr = $_str{$i};
				$new_str .= $chr;
				$new_str_ems += $config->ems( ord( $chr ) );
				$i++;
			}
			if ( strlen( $new_str ) < strlen( $_str ) ) {
				return substr( $new_str, 0, -1 )."&hellip;";
			} else {
				return $new_str;
			}
			//return substr( $_str, 0, $_len )."&hellip;";
		}
	}
	
	function strlen( $_str ) {
		$config =& SlimStatConfig::get_instance();
		
		if ( empty( $_str ) ) {
			return 0;
		}
		
		$str_ems = 0;
		for ( $i=0; $i<strlen( $_str ); $i++ ) {
			$chr = $_str{$i};
			$str_ems += $config->ems( ord( $chr ) );
		}
		return $str_ems;
	}
	
	function trim_referer( $_r ) {
		$_r = eregi_replace( "http://", "", $_r );
		$_r = eregi_replace( "^www.", "", $_r );
		return $_r;
	}
	
	function get_host( $_ip, $_do_lookup ) {
		$config =& SlimStatConfig::get_instance();
		
		$query = "SELECT `remote_addr` FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."` WHERE `remote_ip`='".SlimStat::my_esc( $_ip )."' AND `remote_addr`<>'' LIMIT 1";
		$host = "";
		if ( $result = mysql_query( $query ) ) {
			list( $host ) = mysql_fetch_row( $result );
		}
		if ( $host == "" && $config->show_hostnames == true && $_do_lookup == true ) {
			if ( (bool)ini_get( "safe_mode" ) == false && !stristr( ini_get( "disable_functions" ), "shell_exec" ) ) {
				if ( class_exists( "COM" ) ) { // win32
					$host = split( "Name:", shell_exec( "nslookup ".$_ip ) );
					$host = ( isset( $host[1] ) ) ? trim( str_replace( "\n"."Address:  ".$_ip, "", $host[1] ) ) : $_ip;
				} else { // unix
					$host = shell_exec( "host -W 1 ".$_ip );
					if ( strstr( $host, "not found" ) || strstr( $host, "timed out" ) || strlen( $host ) == 0 ) {
						$host = "";
					} else {
						$array = explode( " ", substr( trim( $host ), 0, -1 ) );
						$host = end( $array );
					}
				}
			} else {
				$host = gethostbyaddr( $_ip );
			}
		}
		if ( $host != "" ) {
			$query = "UPDATE `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."` SET `remote_addr`='".SlimStat::my_esc( $host )."' WHERE `remote_ip`='".SlimStat::my_esc( $_ip )."'";
			$result = mysql_query( $query );
			return $host;
		} else {
			return $_ip;
		}
	}
	
	function get_domain( $_ip ) {
		$config =& SlimStatConfig::get_instance();
		
		$hostname = SlimStat::get_host( $_ip, false );
		if ( $hostname == $_ip ) {
			return $_ip;
		} else {
			$portions = explode( ".", $hostname );
			$portions = array_reverse( $portions );
			if ( sizeof( $portions ) > 2 && strlen( $portions[0] ) == 2 && ( strlen( $portions[1] ) == 2 || preg_match( "/^(org?|com?|net?|gov|nhs|edu|mod)$/i", $portions[1] ) ) ) {
				$domain = $portions[2].".".$portions[1].".".$portions[0];
			} elseif ( sizeof( $portions ) > 1 ) {
				$domain = $portions[1].".".$portions[0];
			} else {
				$domain = $portions[0];
			}
			return $domain;
			//return ( preg_match( "/[0-9]{1,3}\.[0-9]{1,3}/", $domain ) ) ? $_ip : $domain;
		}
	}

	function days_in_month( $_dt ) {
		return date( "d", mktime( 12, 0, 0, date( "n", $_dt ) + 1, 0, date( "Y", $_dt ) ) );
	}
	
	function date_period_label( $_dt_start, $_dt_end=0 ) {
		$config =& SlimStatConfig::get_instance();
		return $config->i18n->date_period_label( $_dt_start, $_dt_end );
	}
	
	function time_period_label( $_dt_start, $_dt_end=0 ) {
		$config =& SlimStatConfig::get_instance();
		return $config->i18n->time_period_label( $_dt_start, $_dt_end );
	}
	
	function date_label( $_dt ) {
		$config =& SlimStatConfig::get_instance();
		return $config->i18n->date_label( $_dt );
	}
	
	function time_label( $_dt, $_compared_to_dt=0 ) {
		$config =& SlimStatConfig::get_instance();
		return $config->i18n->time_label( $_dt, $_compared_to_dt );
	}
	
	function to_server_time( $_user_dt ) {
		$config =& SlimStatConfig::get_instance();
		return $_user_dt - $config->dt_offset_secs;
	}
	
	function to_user_time( $_server_dt ) {
		$config =& SlimStatConfig::get_instance();
		return $_server_dt + $config->dt_offset_secs;
	}
	
	function my_esc( $_str ) {
		if ( version_compare( phpversion(), "4.3.0", ">=" ) ) {
			return mysql_real_escape_string( $_str );
		} else {
			return mysql_escape_string( $_str );
		}
	}
	
	function implode_assoc( $_inner_glue, $_outer_glue, $_array ) {
		$array2 = array();
		foreach ( $_array as $key => $value ) {
			$array2[] = $key.$_inner_glue.$value;
		}
		return implode( $_outer_glue, $array2 );
	}
	
	function getmicrotime() {
		list( $usec, $sec ) = explode( " ", microtime() );
		return (float)$usec + (float)$sec;
	}
	
	function to_utf8( $_str ) {
		$encoding = mb_detect_encoding( $_str );
		if ( $encoding == "UTF-8" ) {
			return $_str;
		} else {
			print $encoding;
			return mb_convert_encoding( $_str, "UTF-8", $encoding );
		}
	}
	
	function get_client_ip_address() {
		$remote_addr = $_SERVER["REMOTE_ADDR"];
		if ( ( $remote_addr == "127.0.0.1" || $remote_addr == $_SERVER["SERVER_ADDR"] ) && $_SERVER["HTTP_X_FORWARDED_FOR"] ) {
			// There may be multiple comma-separated IPs for the X-Forwarded-For header
			// if the traffic is passing through more than one explict proxy.  Take the
			// last one as being valid.  This is arbitrary, but there is no way to know
			// which IP relates to the client computer.  We pick the first client IP as
			// this is the client closest to our upstream proxy.
			$remote_addrs = explode( ", ", $_SERVER["HTTP_X_FORWARDED_FOR"] );
			$remote_addr = $remote_addrs[0];
		}
		
		return $remote_addr;
	}
	
}

?>
