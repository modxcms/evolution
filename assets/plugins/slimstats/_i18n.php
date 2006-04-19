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

class SlimStatI18nBase {
	
	var $config;
	
	function init() {
		// language
		$this->name_lookups["language"] = $this->languages;
		
		// remote_ip
		if ( $this->config->show_hostnames ) {
			$this->name_lookups["remote_ip"] = "return SlimStat::get_domain( 'VALUE' );";
		}
		
		// resource
		$this->name_lookups["resource"] = array( "/" => $this->homepage );
		
		// weekday
		$this->fields["DAYOFWEEK(FROM_UNIXTIME(`dt`".( ( $this->config->dt_offset_secs != 0 ) ? "+".$this->config->dt_offset_secs : "" )."))"] = $this->fields["weekday"];
		$dt = mktime( 12, 0, 0 );
		while ( date( "w", $dt ) > 0 ) { // move back to previous sunday
			$dt -= $this->config->day;
		}
		$this->name_lookups["DAYOFWEEK(FROM_UNIXTIME(`dt`".( ( $this->config->dt_offset_secs != 0 ) ? "+".$this->config->dt_offset_secs : "" )."))"] = array();
		for ( $i=1; $i<8; $i++ ) {
			$this->name_lookups["DAYOFWEEK(FROM_UNIXTIME(`dt`".( ( $this->config->dt_offset_secs != 0 ) ? "+".$this->config->dt_offset_secs : "" )."))"][$i] = date( $this->weekday_format, $dt );
			$dt += $this->config->day;
		}
		
		// hour
		$this->fields["HOUR(FROM_UNIXTIME(`dt`".( ( $this->config->dt_offset_secs != 0 ) ? "+".$this->config->dt_offset_secs : "" )."))"] = $this->fields["hour"];
		$this->name_lookups["HOUR(FROM_UNIXTIME(`dt`".( ( $this->config->dt_offset_secs != 0 ) ? "+".$this->config->dt_offset_secs : "" )."))"] = array();
		for ( $i=0; $i<24; $i++ ) {
			$this->name_lookups["HOUR(FROM_UNIXTIME(`dt`".( ( $this->config->dt_offset_secs != 0 ) ? "+".$this->config->dt_offset_secs : "" )."))"][$i] = date( $this->hour_format, mktime( $i,  0, 0 ) );
		}
	}
	
	function link_title( $_key, $_search="", $_replace="" ) {
		if ( array_key_exists( $_key, $this->link_titles ) ) {
			if ( $_search != "" ) {
				return str_replace( $_search, $_replace, $this->link_titles[$_key] );
			} else {
				return $this->link_titles[$_key];
			}
		} else {
			return "";
		}
	}
	
	function date_period_label( $_dt_start, $_dt_end=0 ) {
		//$config =& SlimStatConfig::get_instance();
		
		$usr_dt_start = SlimStat::to_user_time( $_dt_start );
		$usr_dt_end = SlimStat::to_user_time( $_dt_end );
		
		$start_d = strftime( "%e", $usr_dt_start );
		$start_m = strftime( "%b", $usr_dt_start );
		$start_y = strftime( "%Y", $usr_dt_start );
		if ( $_dt_end == 0 ) {
			$_dt_end = $_dt_start;
		}
		$end_d = strftime( "%e", $usr_dt_end );
		$end_m = strftime( "%b", $usr_dt_end );
		$end_y = strftime( "%Y", $usr_dt_end );
		
		if ( $start_y != $end_y ) {
			return $start_d." ".$start_m." ".$start_y." - ".$end_d." ".$end_m." ".$end_y;
		} elseif ( $start_m != $end_m ) {
			return $start_d." ".$start_m." - ".$end_d." ".$end_m." ".$end_y;
		} elseif ( $start_d != $end_d ) {
			if ( $start_d == 1 && $end_d == SlimStat::days_in_month( $_dt_end ) ) {
				return strftime( "%B", $_dt_end )." ".$end_y;
			} else {
				return $start_d." - ".$end_d." ".$end_m." ".$end_y;
			}
		} elseif ( date( "j M Y", SlimStat::to_user_time( time() ) ) == date( "j M Y", $usr_dt_end ) ) {
			return $this->date_periods["today"].", ".$end_d." ".$end_m." ".$end_y;
		} else {
			return strftime( "%a", $_dt_end ).", ".$end_d." ".$end_m." ".$end_y;
		}
	}
	
	function time_period_label( $_dt_start, $_dt_end=0 ) {
		if ( $_dt_end == 0 ) {
			$_dt_end = $_dt_start;
		}
		
		$usr_dt_start = SlimStat::to_user_time( $_dt_start );
		$usr_dt_end = SlimStat::to_user_time( $_dt_end );
		
		if ( date( "H", $usr_dt_start ) == 0 && date( "H", $usr_dt_end ) == 23 ) {
			return SlimStat::date_label( $_dt_end );
		} elseif ( strftime( "%p", $usr_dt_start ) == "" ) {
			return strftime( "%H:00", $usr_dt_start )." - ".strftime( "%H:00", $usr_dt_end + ( 60 * 60 ) );
		} else {
			return strtolower( preg_replace( "/^0/", "", strftime( "%I%p", $usr_dt_start ) )." - ".preg_replace( "/^0/", "", strftime( "%I%p", $usr_dt_end + ( 60 * 60 ) ) ) );
		}
	}
	
	function date_label( $_dt ) {
		return strftime( "%a, %e %b %Y", SlimStat::to_user_time( $_dt ) );
	}
	
	function time_label( $_dt, $_compared_to_dt=0 ) {
		$usr_dt = SlimStat::to_user_time( $_dt );
		if ( $_compared_to_dt == 0 ) {
			if ( strftime( "%p", $usr_dt ) == "" ) {
				return strftime( "%H:%M", $usr_dt );
			} else {
				return preg_replace( "/^0/", "", strtolower( strftime( "%I:%M%p", $usr_dt ) ) );
			}
			//return strftime( "%r", $usr_dt );
		} elseif ( $_dt >= SlimStat::to_server_time( strtotime( date( "j M Y 00:00:00", SlimStat::to_user_time( $_compared_to_dt ) ) ) ) ) {
			return SlimStat::time_label( $_dt );
		} else {
			return strftime( "%e %b", $usr_dt );
		}
	}
	
}

?>
