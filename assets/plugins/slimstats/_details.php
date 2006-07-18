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

if ( !isset( $_GET["show"] ) && !empty( $filters ) ) {
/*if ( !isset( $_GET["show"] ) || sizeof( $filters ) > 2 ) {*/
	?>
	<h2><?php print $config->i18n->titles["filters"]; ?><?php print ( !isset( $_GET["show"] ) || sizeof( $filters ) > 2 ) ? " <span>(".$config->i18n->titles["filters_subtitle"].")</span>" : ""; ?></h2>
	<ul id="filters">
	<?php
	if ( !isset( $_GET["show"] ) || sizeof( $filters ) != 2 ) {
		foreach ( array_keys( $filters ) as $filter ) {
			if ( $filter == "filter_dt_start" ) {
				$filters_copy = $filters;
				unset( $filters_copy["filter_dt_start"] );
				unset( $filters_copy["filter_dt_end"] );
				print "<li><span>".$config->i18n->fields["dt"]."</span> <a href=\"";
				print ( sizeof( $filters_copy ) > 0 ) ? "?a=68&" : "?a=68";
				
				print SlimStat::implode_assoc( "=", "&amp;", $filters_copy )."\" title=\"".$config->i18n->link_title( "filter_remove" )."\">";
				if ( isset( $hours_spanned ) && $hours_spanned < 24 ) {
					print SlimStat::time_period_label( $filters["filter_dt_start"], $filters["filter_dt_end"] ).", ";
				}
				print SlimStat::date_period_label( $filters["filter_dt_start"], $filters["filter_dt_end"] )."</a></li>";
			} elseif ( $filter == "filter_dt_end" ) {
				// do nothing
			} else {
				$display_value = urldecode( $filters[$filter] );
				if ( isset( $config->i18n->name_lookups[ substr( $filter, 7 ) ] ) ) {
					$name_lookup = $config->i18n->name_lookups[ substr( $filter, 7 ) ];
					if ( is_array( $name_lookup ) && isset( $name_lookup[ $display_value ] ) ) {
						$display_value = $name_lookup[ $display_value ];
					} elseif ( is_string( $name_lookup ) ) {
						$display_value = eval( str_replace( "VALUE", $display_value, $name_lookup ) );
					}
				}
				
				$filters_copy = $filters;
				unset( $filters_copy[$filter] );
				
				print "<li><span>".$config->i18n->fields[ substr( $filter, 7 ) ];
				if ( ( $filter == "filter_remote_ip" || $filter == "filter_domain" ) && $config->whoisurl != "" ) {
					print " <a target=\"_blank\" href=\"".str_replace( '%i', $filters[$filter], $config->whoisurl )."\" title=\"".$config->i18n->link_title( "whois" )."\">?</a>";
				}
				print "</span> <a href=\"?a=68&";
				if ( empty( $filters_copy ) ) {
					print "show=details";
				} elseif ( sizeof( $filters_copy ) == 2 && isset( $filters_copy["filter_dt_start"] ) && isset( $filters_copy["filter_dt_end"] ) ) {
					if ( $filters_copy["filter_dt_start"] == $today_dt_start && $filters_copy["filter_dt_end"] == $today_dt_end ) {
						print "show=today";
						unset( $filters_copy["filter_dt_start"] );
						unset( $filters_copy["filter_dt_end"] );
					} elseif ( $filters_copy["filter_dt_start"] == $thisweek_dt_start && $filters_copy["filter_dt_end"] == $thisweek_dt_end ) {
						print "show=thisweek";
						unset( $filters_copy["filter_dt_start"] );
						unset( $filters_copy["filter_dt_end"] );
					} elseif ( $filters_copy["filter_dt_start"] == $thismonth_dt_start && $filters_copy["filter_dt_end"] == $thismonth_dt_end ) {
						print "show=thismonth";
						unset( $filters_copy["filter_dt_start"] );
						unset( $filters_copy["filter_dt_end"] );
					}
				}
				
				print SlimStat::implode_assoc( "=", "&amp;", $filters_copy );
				print "\" title=\"".$config->i18n->link_title( "filter_remove" )."\">".$display_value."</a></li>";
			}
		}
	}
	
	$addable_filters = array();
	/*asort( $config->i18n->fields );
	foreach ( array_keys( $config->i18n->fields ) as $field ) {
		if ( !isset( $filters["filter_".$field] ) && ( !isset( $config->i18n->name_lookups[$field] ) || $field == "resource" ) && !strstr( $field, "(" ) && substr( $field, 0, 2 ) != "dt" && $field != "referer" && $field != "visit" && $field != "remote_addr" ) {
			$addable_filters[] = $field;
		}
	}*/
	if ( !empty( $addable_filters ) ) {
		?>
		<li><form method="get"><?php
		foreach ( array_keys( $filters ) as $filter ) {
			?><input type="hidden" name="<?php print $filter; ?>" value="<?php print htmlentities( urldecode( $filters[$filter] ) ); ?>" /><?php
		}
		?><span><select name="new_filter_field">
		<?php
		foreach ( $addable_filters as $field ) {
			?><option value="<?php print $field; ?>"><?php print $config->i18n->fields[$field]; ?></option><?php
		}
		?>
		</select></span><input type="text" name="new_filter_value" value="" class="input" /><input type="submit" value="Add" /></form>
		</li>
		<?php
	}
	?>
	</ul>
	
	<div style="clear:both;"></div>
	<?php
}

?>
<h2><?php print ( isset( $_GET["show"] ) ) ? "" : $config->i18n->titles["details"]." "; ?><span>(<?php print $config->i18n->titles["details_subtitle"]; ?>)</span></h2>
<?php

if ( $config->show_modules["hourly"] && isset( $days_spanned ) && $days_spanned == 1 ) {
	if ( isset( $filters["filter_dt_end"] ) ) {
		$dt = mktime( 23, 59, 59, date( "m", $filters["filter_dt_end"] ), date( "d", $filters["filter_dt_end"] ), date( "Y", $filters["filter_dt_end"] ) );
	} else {
		$dt = mktime( 23, 59, 59 );
	}
	
	print SlimStat::render_module(
		$config->i18n->module_titles["hourly"],
		hour_table( $dt, ucfirst( $config->i18n->date_periods["hour"] ), 24, '0', '$config->hour', $filters )
	);
}

if ( $config->show_modules["daily"] ) {
	//$dt = ( !isset( $filters["filter_dt_end"] ) ) ? time() : min( time(), $filters["filter_dt_end"] );
	$dt = ( !isset( $filters["filter_dt_end"] ) ) ? mktime( 12, 0, 0 ) : $filters["filter_dt_end"];
	$days_from_now = ( !isset( $filters["filter_dt_end"] ) ) ? 0 : floor( abs( time() - $filters["filter_dt_end"] ) / $config->day );
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
		$config->i18n->module_titles["daily"]." <span><a href=\"rss.php?daily\">".$config->i18n->feed."</a></span>",
		date_table( $dt, ucfirst( $config->i18n->date_periods["day"] ), $days, '0', '$config->day', $filters )
	);
}

if ( $config->show_modules["weekly"] && ( !isset( $hours_spanned ) || $hours_spanned >= 24 ) ) {
	//$dt = ( !isset( $filters["filter_dt_end"] ) ) ? time() : min( time(), $filters["filter_dt_end"] );
	$dt = ( !isset( $filters["filter_dt_end"] ) ) ? mktime( 12, 0, 0 ) : $filters["filter_dt_end"];
	while ( date( "w", $dt ) != $config->week_start_day ) { // move back to start of week
		$dt -= $config->day;
	}
	$weeks = ( isset( $days_spanned ) && $days_spanned > 7 && date( "w", mktime( 12, 0, 0, date( "n" ), SlimStat::days_in_month( $dt ) ) ) < 2 ) ? 6 : 5;
	if ( isset( $days_spanned ) && $days_spanned == 1 ) {
		$weeks = 1;
	}
	
	print SlimStat::render_module(
		$config->i18n->module_titles["weekly"],
		date_table( $dt, ucfirst( $config->i18n->date_periods["week"] ), $weeks, '$config->day * 6', '$config->week', $filters )
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
			ucfirst( $config->i18n->date_periods["month"] ),
			$months,
			'$config->day * ( SlimStat::days_in_month( $_dt ) - 1 )',
			'$config->day * SlimStat::days_in_month( $_dt - ( $config->day * 28 ) )',
			$filters
		)
	);
}

?>
<div style="clear:both;"></div>
<?php

if ( $config->show_modules["resource"] && !isset( $filters["filter_resource"] ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["resource"],
		total_table( "resource", $config->truncate_medium, "", $filters ),
		"mediummodule"
	);
}

if ( $config->show_modules["resource"] && $config->show_modules["next_resource"] && isset( $filters["filter_resource"] ) ) {
	$filters_copy = $filters;
	unset( $filters_copy["filter_resource"] );
	print SlimStat::render_module(
		$config->i18n->module_titles["next_resource"],
		total_table( "resource", $config->truncate_medium, "referer LIKE '%".SlimStat::my_esc( SlimStat::trim_referer( $_SERVER["SERVER_NAME"].urldecode( $filters["filter_resource"] ) ) )."'", $filters_copy ),
		"mediummodule"
	);
}

if ( $config->show_modules["searchterms"] && !isset( $filters["filter_searchterms"] ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["searchterms"],
		total_table( "searchterms", $config->truncate_medium, "searchterms != ''", $filters ),
		"mediummodule"
	);
}

if ( $config->show_modules["domain"] && !isset( $filters["filter_domain"] ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["domain"],
		total_table( "domain", $config->truncate, "domain != '".SlimStat::my_esc( SlimStat::trim_referer( $_SERVER["SERVER_NAME"] ) )."' AND domain != ''", $filters )
	);
}

if ( $config->show_modules["referer"] && isset( $filters["filter_domain"] ) && !isset( $filters["filter_referer"] ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["referer"],
		total_table( "referer", $config->truncate_medium, "", $filters ),
		"mediummodule"
	);
}

if ( $config->show_modules["browser"] && !isset( $filters["filter_browser"] ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["browser"],
		percentage_table( "browser", $config->truncate, "", $filters )
	);
}

if ( $config->show_modules["version"] && ( !isset( $filters["filter_browser"] ) || !isset( $filters["filter_version"] ) ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["version"],
		percentage_table( array( "browser", "version" ), $config->truncate, "", $filters )
	);
}

if ( $config->show_modules["platform"] && !isset( $filters["filter_platform"] ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["platform"],
		percentage_table( "platform", $config->truncate, "", $filters )
	);
}

if ( $config->show_modules["country"] && SlimStat::is_ip_to_country_installed() && !isset( $filters["filter_country"] ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["country"],
		percentage_table( "country", $config->truncate, "", $filters )
	);
}

if ( $config->show_modules["language"] && !isset( $filters["filter_language"] ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["language"],
		percentage_table( "language", $config->truncate, "language != '' AND language != 'empty'", $filters )
	);
}

if ( $config->show_modules["remote_ip"] && $config->show_visits && !isset( $filters["filter_remote_ip"] ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["remote_ip"],
		total_table( "remote_ip", $config->truncate, "", $filters )
	);
}

if ( $config->show_modules["visit"] && $config->show_visits && isset( $days_spanned ) && $days_spanned == 1 ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["visit"],
		visit_table( $filters )
	);
}

if ( $config->show_modules["dayofweek"] && isset( $days_spanned ) && $days_spanned > 7 ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["dayofweek"],
		dt_table( "DAYOFWEEK", $filters )
	);
}

if ( $config->show_modules["hour"] && isset( $days_spanned ) && $days_spanned > 1 ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["hour"],
		dt_table( "HOUR", $filters )
	);
}

if ( $config->show_modules["pageviews"] && !isset( $filters["filter_resource"] ) ) {
	print SlimStat::render_module(
		$config->i18n->module_titles["pageviews"],
		pageviews_table( "", $filters )
	);
}

function hour_table( $_dt, $_title, $_n_loops, $_end_increment, $_dt_decrement, $_filters ) {
	$config =& SlimStatConfig::get_instance();
	
	$thead = array(
		"first" => array( ( isset( $config->i18n->fields[$_title] ) ? $config->i18n->fields[$_title] : $_title ) ),
		"last" => array( $config->i18n->hits )
	);
	$tbody = array();
	
	if ( $config->show_visits && !isset( $_filters["filter_visit"] ) ) {
		$thead["last"][] = $config->i18n->visits;
	}
	if ( $config->show_uniques ) {
		$thead["last"][] = $config->i18n->uniques;
	}
	
	for ( $i=0; $i<$_n_loops; $i++ ) {
		
		$dt_start = SlimStat::to_server_time( strtotime( date( "Y-m-d H:00:00", $_dt ) ) );
		$dt_end = SlimStat::to_server_time( strtotime( date( "Y-m-d H:59:59", $_dt + eval( "return (".$_end_increment.");" ) ) ) );
		
		if ( SlimStat::to_server_time( $dt_start ) <= time() /*&&
			( !isset( $_filters["filter_dt_start"] ) || $dt_start >= $_filters["filter_dt_start"] ) &&
			( !isset( $_filters["filter_dt_end"] ) || $dt_end <= $_filters["filter_dt_end"] )*/ ) {
			$row = array( "first" => array(), "last" => array() );
			
			$assoc = SlimStat::get_hits_visits_uniques( $dt_start, $dt_end, $_filters );
			if ( $assoc["hits"] > 0 ) {
				if ( $dt_start == $_filters["filter_dt_start"] && $dt_end == $_filters["filter_dt_end"] ) {
					$row["first"][] = "<em>".SlimStat::time_period_label( $dt_start, $dt_end )."</em>";
				} else {
					$filters_copy = $_filters;
					unset( $filters_copy["filter_dt_start"] );
					unset( $filters_copy["filter_dt_end"] );
					$str = "<a href=\"?a=68&".SlimStat::implode_assoc( "=", "&amp;", $filters_copy );
					$str .= ( empty( $filters_copy ) ? "" : "&amp;" )."filter_dt_start=".$dt_start."&amp;filter_dt_end=".$dt_end;
					$str .= "\">".SlimStat::time_period_label( $dt_start, $dt_end )."</a>";
					$row["first"][] = $str;
				}
				$row["last"][] = $assoc["hits"];
				if ( $config->show_visits && !isset( $_filters["filter_visit"] ) ) {
					$row["last"][] = $assoc["visits"];
				}
				if ( $config->show_uniques ) {
					$row["last"][] = $assoc["uniques"];
				}
				
				$tbody[] = $row;
			}
		}
		
		$_dt -= eval( "return (".$_dt_decrement.");" );
	}
	
	return SlimStat::render_table( $thead, $tbody, true );
}

function date_table( $_dt, $_title, $_n_loops, $_end_increment, $_dt_decrement, $_filters ) {
	$config =& SlimStatConfig::get_instance();
	
	$thead = array(
		"first" => array( ( isset( $config->i18n->fields[$_title] ) ? $config->i18n->fields[$_title] : $_title ) ),
		"last" => array( $config->i18n->hits )
	);
	$tbody = array();
	
	if ( $config->show_visits && !isset( $_filters["filter_visit"] ) ) {
		$thead["last"][] = $config->i18n->visits;
	}
	if ( $config->show_uniques ) {
		$thead["last"][] = $config->i18n->uniques;
	}
	
	for ( $i=0; $i<$_n_loops; $i++ ) {
		$dt_start = SlimStat::to_server_time( strtotime( date( "Y-m-d 00:00:00", $_dt ) ) );
		$dt_end = SlimStat::to_server_time( strtotime( date( "Y-m-d 23:59:59", $_dt + eval( "return (".$_end_increment.");" ) ) ) );
		
		$assoc = SlimStat::get_hits_visits_uniques( $dt_start, $dt_end, $_filters );
		if ( $assoc["hits"] > 0 ) {
			$row = array( "first" => array(), "last" => array() );
			
			if ( isset( $_filters["filter_dt_start"] ) &&
				 isset( $_filters["filter_dt_end"] ) &&
				 $dt_start == $_filters["filter_dt_start"] &&
				 $dt_end == $_filters["filter_dt_end"] ) {
				$row["first"][] = "<em>".SlimStat::date_period_label( $dt_start, $dt_end )."</em>";
			} else {
				$str = "<a href=\"?a=68&";
				
				$filters_copy = $_filters;
				$printed_show = false;
				if ( sizeof( $filters_copy ) == 0 || ( sizeof( $filters_copy ) == 2 && isset( $filters_copy["filter_dt_start"] ) && isset( $filters_copy["filter_dt_end"] ) ) ) {
					if ( $dt_start == $GLOBALS["today_dt_start"] && $dt_end == $GLOBALS["today_dt_end"] ) {
						$str .= "show=today";
						$printed_show = true;
					} elseif ( $dt_start == $GLOBALS["thisweek_dt_start"] && $dt_end == $GLOBALS["thisweek_dt_end"] ) {
						$str .= "show=thisweek";
						$printed_show = true;
					} elseif ( $dt_start == $GLOBALS["thismonth_dt_start"] && $dt_end == $GLOBALS["thismonth_dt_end"] ) {
						$str .= "show=thismonth";
						$printed_show = true;
					}
				}
				unset( $filters_copy["filter_dt_start"] );
				unset( $filters_copy["filter_dt_end"] );
				
				$str .= SlimStat::implode_assoc( "=", "&amp;", $filters_copy );
				if ( !$printed_show ) {
					$str .= ( empty( $filters_copy ) ? "" : "&amp;" )."filter_dt_start=".$dt_start."&amp;filter_dt_end=".$dt_end;
				}
				$str .= "\">".SlimStat::date_period_label( $dt_start, $dt_end )."</a>";
				$row["first"][] = $str;
			}
			$row["last"][] = $assoc["hits"];
			if ( $config->show_visits && !isset( $_filters["filter_visit"] ) ) {
				$row["last"][] = $assoc["visits"];
			}
			if ( $config->show_uniques ) {
				$row["last"][] = $assoc["uniques"];
			}
			
			$tbody[] = $row;
		}
		
		$_dt -= eval( "return (".$_dt_decrement.");" );
	}
	
	return SlimStat::render_table( $thead, $tbody, true );
}

function total_table( $_col, $_truncate, $_where_clause="", $_filters ) {
	$config =& SlimStatConfig::get_instance();
	
	$thead = array(
		"first" => array( ( isset( $config->i18n->fields[$_col] ) ? $config->i18n->fields[$_col] : $_col ) ),
		"last" => array( $config->i18n->hits )
	);
	$tbody = array();
	
	if ( is_scalar( $_col ) && ( $_col == "referer" || $_col == "resource" ) ) {
		$_truncate -= 4;
		array_unshift( $thead["last"], "&nbsp;" );
	}
	
	$results = SlimStat::_get_table_data( $_col, $_where_clause, ( is_array( $_col ) ) ? implode( ", ", $_col ) : $_col, isset( $_filters["filter_visit"] ) ? "hits" : $config->order_by, $_filters );
	
	if ( $config->show_visits && !isset( $_filters["filter_visit"] ) ) {
		$thead["last"][] = $config->i18n->visits;
		$_truncate -= strlen( $config->i18n->visits );
	}
	if ( $config->show_uniques ) {
		$thead["last"][] = $config->i18n->uniques;
		$_truncate -= strlen( $config->i18n->uniques );
	}
	
	foreach ( $results as $result ) {
		$row = array( "first" => array(), "last" => array() );
		
		if ( is_array( $_col ) ) {
			foreach ( $_col as $this_col ) {
				$row["first"][] = get_td( $_col, $result, $this_col, $_truncate, ( isset( $config->i18n->name_lookups[$this_col] ) ) ? $config->i18n->name_lookups[$this_col] : null, $this_col == $_col[0] );
			}
		} elseif ( $_col == "referer" || $_col == "resource" ) {
			$row["first"][] = get_td( array( $_col ), $result, $_col, $_truncate, ( isset( $config->i18n->name_lookups[$_col] ) ) ? $config->i18n->name_lookups[$_col] : null, true );
			$row["last"][] = "<a href=\"?a=68&".$result[$_col]."\" class=\"external\" rel=\"nofollow\" title=\"".$config->i18n->link_title( "external", "FIELD", strtolower( $config->i18n->fields[$_col] ) )."\"><img src=\"external.gif\" width=\"9\" height=\"9\" alt=\"\" /></a>";
		} else {
			$row["first"][] = get_td( array( $_col ), $result, $_col, $_truncate, ( isset( $config->i18n->name_lookups[$_col] ) ) ? $config->i18n->name_lookups[$_col] : null, true );
		}
		$row["last"][] = $result["hits"];
		if ( $config->show_visits && !isset( $_filters["filter_visit"] ) ) {
			$row["last"][] = $result["visits"];
		}
		if ( $config->show_uniques ) {
			$row["last"][] = $result["uniques"];
		}
		
		$tbody[] = $row;
	}
	
	return SlimStat::render_table( $thead, $tbody );
}

function percentage_table( $_col, $_truncate, $_where_clause="", $_filters=array() ) {
	$config =& SlimStatConfig::get_instance();
	
	$thead = array(
		"first" => array(),
		"last" => array( $config->i18n->percentage )
	);
	if ( is_array( $_col ) ) {
		foreach ( $_col as $this_col ) {
			$thead["first"][] = ( isset( $config->i18n->fields[$this_col] ) ? $config->i18n->fields[$this_col] : $this_col );
		}
	} else {
		$thead["first"][] = ( isset( $config->i18n->fields[$_col] ) ? $config->i18n->fields[$_col] : $_col );
	}
	$tbody = array();
	
	$total_hits = SlimStat::get_hits( $_where_clause, $_filters );
	$results = SlimStat::_get_table_data( $_col, $_where_clause, ( is_array( $_col ) ) ? implode( ", ", $_col ) : $_col, "hits", $_filters );
	
	foreach ( $results as $result ) {
		$row = array( "first" => array(), "last" => array() );
		
		$p = number_format( ( $result["hits"] / $total_hits ) * 100 );
		if ( is_array( $_col ) ) {
			foreach ( $_col as $this_col ) {
				$row["first"][] = get_td( $_col, $result, $this_col, $_truncate + 5, ( isset( $config->i18n->name_lookups[$this_col] ) ) ? $config->i18n->name_lookups[$this_col] : null, $this_col == $_col[0] );
			}
		} else {
			$row["first"][] = get_td( array( $_col ), $result, $_col, $_truncate + 5, ( isset( $config->i18n->name_lookups[$_col] ) ) ? $config->i18n->name_lookups[$_col] : null, true );
		}
		$row["last"][] = ( ( $p < 1 ) ? "&lt;1" : $p )."%";
		
		$tbody[] = $row;
	}
	
	return SlimStat::render_table( $thead, $tbody );
}

function visit_table( $_filters=array() ) {
	$config =& SlimStatConfig::get_instance();
	
	$thead = array(
		"first" => array( ( isset( $config->i18n->fields["visit"] ) ? $config->i18n->fields["visit"] : "visit" ) ),
		"last" => array( $config->i18n->hits )
	);
	$tbody = array();
	
	$results = SlimStat::get_visit_data( $_filters );
	
	foreach ( $results as $result ) {
		$row = array( "first" => array(), "last" => array() );
		
		$mindt = SlimStat::time_label( $result["mindt"] );
		$maxdt = SlimStat::time_label( $result["maxdt"] );
		$display_value = ( $config->show_hostnames ) ? SlimStat::get_domain( $result["remote_ip"] ) : $result["remote_ip"];
		if ( $mindt == $maxdt ) {
			$display_value .= ", ".$mindt;
		} else {
			$display_value .= ", ".$mindt."-".$maxdt;
		}
		$row["first"][] = get_td( array( "visit" ), $result, "visit", $config->truncate, array( $result["visit"] => $display_value ), true );
		$row["last"][] = $result["hits"];
		
		$tbody[] = $row;
	}
	
	return SlimStat::render_table( $thead, $tbody );
}

function dt_table( $_function, $_filters=array() ) {
	$config =& SlimStatConfig::get_instance();
	
	$col = SlimStat::my_esc($_function)."(FROM_UNIXTIME(`dt`".( ( $config->dt_offset_secs != 0 ) ? "+".$config->dt_offset_secs : "" )."))";
	
	$thead = array(
		"first" => array( ( isset( $config->i18n->fields[$col] ) ? $config->i18n->fields[$col] : $col ) ),
		"last" => array( $config->i18n->hits )
	);
	$tbody = array();
	
	if ( $config->show_visits && !isset( $_filters["filter_visit"] ) ) {
		$thead["last"][] = $config->i18n->visits;
	}
	if ( $config->show_uniques ) {
		$thead["last"][] = $config->i18n->uniques;
	}
	
	$results = SlimStat::_get_table_data( $col." AS `col`", "", "`col`", "col", $_filters );
	
	foreach ( $results as $result ) {
		$row = array( "first" => array(), "last" => array() );
		
		$row["first"][] = ( isset( $config->i18n->name_lookups[$col] ) && isset( $config->i18n->name_lookups[$col][ $result["col"] ] ) ) ? $config->i18n->name_lookups[$col][ $result["col"] ] : $result["col"];
		$row["last"][] = $result["hits"];
		if ( $config->show_visits && !isset( $_filters["filter_visit"] ) ) {
			$row["last"][] = $result["visits"];
		}
		if ( $config->show_uniques ) {
			$row["last"][] = $result["uniques"];
		}
		$tbody[] = $row;
	}
	
	return SlimStat::render_table( $thead, $tbody, true );
}

function pageviews_table( $_where_clause, $_filters=array() ) {
	$config =& SlimStatConfig::get_instance();
	
	$thead = array(
		"first" => array( $config->i18n->hits ),
		"last" => array( $config->i18n->percentage )
	);
	$tbody = array();
	
	$total_visits = SlimStat::get_visits( $_where_clause, $_filters );
	$data = SlimStat::get_pageviews_data( $_where_clause, $_filters );
	
	$i = 0;
	foreach ( array_keys( $data ) as $hits ) {
		$p = number_format( ( $data[$hits] / $total_visits ) * 100 );
		
		$row = array(
			"first" => array( $hits ),
			"last" => array( ( ( $p < 1 ) ? "&lt;1" : $p )."%" )
		);
		
		$tbody[] = $row;
		
		$i++;
		if ( $i == $config->rows ) {
			break;
		}
	}
	
	return SlimStat::render_table( $thead, $tbody );
}

function get_td( $_cols, $_result, $_this_col, $_truncate, $_name_lookup=null, $_link=true ) {
	$config =& SlimStatConfig::get_instance();
	$value = $_result[$_this_col];
	
	if ( $_link ) {
		if ( is_array( $_name_lookup ) && isset( $_name_lookup[ $value ] ) ) {
			$display_value = $_name_lookup[ $value ];
		} elseif ( is_array( $_name_lookup ) && isset( $_name_lookup[ strtolower( $value ) ] ) ) {
			$display_value = $_name_lookup[ strtolower( $value ) ];
		} elseif ( is_string( $_name_lookup ) ) {
			$display_value = eval( str_replace( "VALUE", $value, $_name_lookup ) );
		} else {
			$display_value = $value;
		}
		
		$filters_copy = $GLOBALS["filters"];
		foreach ( $_cols as $col ) {
			unset( $filters_copy["filter_".$col] );
			$filters_copy["filter_".$col] = urlencode( $_result[$col] );
		}
		$str = "<a href=\"?a=68&";
		$str .= SlimStat::implode_assoc( "=", "&amp;", $filters_copy );
		if ( strlen( $display_value ) > $_truncate ) {
			$str .= "\" title=\"".htmlentities( $display_value );
		}
		$str .= "\">";
		$str .= utf8_encode( SlimStat::truncate( $display_value, $_truncate ) );
		$str .= "</a>";
	} else {
		$str = SlimStat::truncate( $value, $_truncate );
	}
	
	return $str;
}

?>
