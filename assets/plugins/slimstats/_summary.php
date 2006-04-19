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

if ( SlimStat::get_first_hit() == 0 ) {
	?>
	<h2>Welcome to SlimStat</h2>
	<p>Congratulations! You have successfully installed SlimStat. The bad news is that you haven't had any visitors yet.</p>
	<p>To get started, you need to include the <code>inc.stats.php</code> file in your site's PHP code for each page where you would like stats to be counted.</p>
	<p>Use code similar to one of these two examples:</p>
	<pre>&lt;?php @include_once( $_SERVER["DOCUMENT_ROOT"]."<?php print dirname( $_SERVER['SCRIPT_NAME'] ); ?>/inc.stats.php" ); ?&gt;</pre>
	<pre>&lt;?php @include_once( "<?php print dirname( __FILE__ ); ?>/inc.stats.php" ); ?&gt;</pre>
	<p>Don't use <em>both</em> examples, because then each hit will be counted twice.</p>
	<p>When you have done that, you'll need to wait for people to start visiting your site. Then the boxes below will start to fill up.</p>
	<p>Enjoy viewing your stats!</p>
	<?php
} else {
	if ( $config->show_modules["summary"] ) {
		?>
		<div class="module">
			<h3><?php print ucfirst( $config->i18n->titles["summary"] ); ?></h3>
			<div><table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th><?php print $config->i18n->period; ?></th>
					<th class="last"><?php print $config->i18n->hits; ?></th>
					<?php if ( $config->show_visits ) { ?><th class="last"><?php print $config->i18n->visits; ?></th><?php } ?>
					<?php if ( $config->show_uniques ) { ?><th class="last"><?php print $config->i18n->uniques; ?></th><?php } ?>
				</tr>
				<?php
				// today
				$usr_dt = SlimStat::to_user_time( time() );
				$svr_dt = SlimStat::to_server_time( mktime( 0, 0, 0, date( "n", $usr_dt ), date( "d", $usr_dt ), date( "Y", $usr_dt ) ) );
				$hvu = SlimStat::get_hits_visits_uniques( $svr_dt );
				$usr_dt = SlimStat::to_user_time( $svr_dt );
				$svr_dt_end = SlimStat::to_server_time( mktime( 0, 0, 0, date( "n", $usr_dt ), date( "d", $usr_dt ) + 1, date( "Y", $usr_dt ) ) ) - 1;
				?>
				<tr>
					<td><a href="?a=68&show=today" title="<?php print $config->i18n->link_title( 'details_dt', 'DT', $config->i18n->date_periods['today'] ); ?>"><?php print SlimStat::date_period_label( $svr_dt, $svr_dt_end ); ?></a></td>
					<td class="last"><?php print $hvu["hits"]; ?></td>
					<?php if ( $config->show_visits ) { ?><td class="last"><?php print $hvu["visits"]; ?></td><?php } ?>
					<?php if ( $config->show_uniques ) { ?><td class="last"><?php print $hvu["uniques"]; ?></td><?php } ?>
				</tr>
				<?php
				// yesterday
				$svr_dt_start = SlimStat::to_server_time( mktime( 0, 0, 0, date( "n", $usr_dt ), date( "d", $usr_dt ) - 1, date( "Y", $usr_dt ) ) );
				$hvu = SlimStat::get_hits_visits_uniques( $svr_dt_start, $svr_dt - 1 );
				?>
				<tr>
					<td><a href="?a=68&filter_dt_start=<?php print $dt_start; ?>&amp;filter_dt_end=<?php print $dt - 1; ?>" title="<?php print $config->i18n->link_title( 'details_dt', 'DT', $config->i18n->date_periods['yesterday'] ); ?>"><?php print ucfirst( $config->i18n->date_periods["yesterday"] ); ?></a></td>
					<td class="last"><?php print $hvu["hits"]; ?></td>
					<?php if ( $config->show_visits ) { ?><td class="last"><?php print $hvu["visits"]; ?></td><?php } ?>
					<?php if ( $config->show_uniques ) { ?><td class="last"><?php print $hvu["uniques"]; ?></td><?php } ?>
				</tr>
				<?php
				// this week
				while ( date( "w", $usr_dt ) != $config->week_start_day ) { // move back to start of week
					$usr_dt -= $config->day;
				}
				$svr_dt = SlimStat::to_server_time( $usr_dt );
				$hvu = SlimStat::get_hits_visits_uniques( $svr_dt );
				?>
				<tr>
					<td><a href="?a=68&show=thisweek" title="<?php print $config->i18n->link_title( 'details_dt', 'DT', $config->i18n->date_periods['this_week'] ); ?>"><?php print ucfirst( $config->i18n->date_periods["this_week"] ); ?></a></td>
					<td class="last"><?php print $hvu["hits"]; ?></td>
					<?php if ( $config->show_visits ) { ?><td class="last"><?php print $hvu["visits"]; ?></td><?php } ?>
					<?php if ( $config->show_uniques ) { ?><td class="last"><?php print $hvu["uniques"]; ?></td><?php } ?>
				</tr>
				<?php
				// last week
				$svr_dt_start = SlimStat::to_server_time( mktime( 0, 0, 0, date( "n", $usr_dt ), date( "d", $usr_dt ) - 7, date( "Y", $usr_dt ) ) );
				$hvu = SlimStat::get_hits_visits_uniques( $svr_dt_start, $svr_dt - 1 );
				?>
				<tr>
					<td><a href="?a=68&filter_dt_start=<?php print $dt_start; ?>&amp;filter_dt_end=<?php print $dt - 1; ?>" title="<?php print $config->i18n->link_title( 'details_dt', 'DT', $config->i18n->date_periods['last_week'] ); ?>"><?php print ucfirst( $config->i18n->date_periods["last_week"] ); ?></a></td>
					<td class="last"><?php print $hvu["hits"]; ?></td>
					<?php if ( $config->show_visits ) { ?><td class="last"><?php print $hvu["visits"]; ?></td><?php } ?>
					<?php if ( $config->show_uniques ) { ?><td class="last"><?php print $hvu["uniques"]; ?></td><?php } ?>
				</tr>
				<?php
				// this month
				$usr_dt = SlimStat::to_user_time( time() );
				while ( date( "j", $usr_dt ) > 1 ) { // move back to start of month
					$usr_dt -= $config->day;
				}
				$svr_dt = SlimStat::to_server_time( $usr_dt );
				$hvu = SlimStat::get_hits_visits_uniques( $svr_dt );
				?>
				<tr>
					<td><a href="?a=68&show=thismonth" title="<?php print $config->i18n->link_title( 'details_dt', 'DT', $config->i18n->date_periods['this_month'] ); ?>"><?php print ucfirst( $config->i18n->date_periods["this_month"] ); ?></a></td>
					<td class="last"><?php print $hvu["hits"]; ?></td>
					<?php if ( $config->show_visits ) { ?><td class="last"><?php print $hvu["visits"]; ?></td><?php } ?>
					<?php if ( $config->show_uniques ) { ?><td class="last"><?php print $hvu["uniques"]; ?></td><?php } ?>
				</tr>
				<?php
				// last month
				$svr_dt_start = SlimStat::to_server_time( mktime( 0, 0, 0, date( "n", $usr_dt ) - 1, 1, date( "Y", $usr_dt ) ) );
				$hvu = SlimStat::get_hits_visits_uniques( $svr_dt_start, $svr_dt - 1 );
				?>
				<tr>
					<td><a href="?a=68&filter_dt_start=<?php print $dt_start; ?>&amp;filter_dt_end=<?php print $dt - 1; ?>" title="<?php print $config->i18n->link_title( 'details_dt', 'DT', $config->i18n->date_periods['last_month'] ); ?>"><?php print ucfirst( $config->i18n->date_periods["last_month"] ); ?></a></td>
					<td class="last"><?php print $hvu["hits"]; ?></td>
					<?php if ( $config->show_visits ) { ?><td class="last"><?php print $hvu["visits"]; ?></td><?php } ?>
					<?php if ( $config->show_uniques ) { ?><td class="last"><?php print $hvu["uniques"]; ?></td><?php } ?>
				</tr>
				<?php
				// all
				$hvu = SlimStat::get_total_hits_visits_uniques();
				?>
				<tr>
					<td><a href="?a=68&show=details" title="<?php print $config->i18n->link_title( 'details_all' ); ?>"><?php print $config->i18n->since." ".SlimStat::date_label( SlimStat::get_first_hit( array() ) ); ?></a></td>
					<td class="last"><?php print $hvu["hits"]; ?></td>
					<?php if ( $config->show_visits ) { ?><td class="last"><?php print $hvu["visits"]; ?></td><?php } ?>
					<?php if ( $config->show_uniques ) { ?><td class="last"><?php print $hvu["uniques"]; ?></td><?php } ?>
				</tr>
			</table></div>
		</div>
		<?php
	}
	
	if ( $config->show_modules["recent_resource"] ) {
		print SlimStat::render_module(
			$config->i18n->module_titles["recent_resource"],
			recent_table(
				array( "href" => "resource", "title" => ( $config->show_hostnames ) ? "remote_addr" : "remote_ip", "display" => "resource" ),
				$config->truncate_medium,
				"dt > ".$config->recent_threshold
			),
			"mediummodule"
		);
	}
	
	if ( $config->show_modules["recent_referer"] ) {
		print SlimStat::render_module(
			$config->i18n->module_titles["recent_referer"],
			recent_table(
				array( "href" => "referer", "title" => "resource", "display" => "domain" ),
				$config->truncate,
				"dt > ".$config->recent_threshold." AND referer NOT LIKE '%".SlimStat::my_esc( SlimStat::trim_referer( $_SERVER["SERVER_NAME"] ) )."/%' AND referer NOT LIKE '%".SlimStat::my_esc( SlimStat::trim_referer( $_SERVER["SERVER_NAME"] ) )."' AND referer != '' AND domain != ''"
			)
		);
	}
	
	if ( $config->show_modules["recent_searchterms"] ) {
		print SlimStat::render_module(
			$config->i18n->module_titles["recent_searchterms"],
			recent_table(
				array( "href" => "referer", "title" => "resource", "display" => "searchterms" ),
				$config->truncate,
				"dt > ".$config->recent_threshold." AND searchterms != ''"
			)
		);
	}
	
	if ( $config->show_modules["unique_domain"] ) {
		print SlimStat::render_module(
			$config->i18n->module_titles["unique_domain"]." <span><a href=\"../assets/plugins/slimstats/rss.php?domain\">".$config->i18n->feed."</a></span>",
			unique_table(
				array( "href" => "referer", "title" => "resource", "display" => "domain" ),
				$config->truncate,
				"dt > ".$config->recent_threshold." AND domain != '".SlimStat::my_esc( SlimStat::trim_referer( $_SERVER["SERVER_NAME"] ) )."' AND domain != ''"
			)
		);
	}
	
	if ( $config->show_modules["unique_resource"] ) {
		print SlimStat::render_module(
			$config->i18n->module_titles["unique_resource"]." <span><a href=\"../assets/plugins/slimstats/rss.php?resource\">".$config->i18n->feed."</a></span>",
			unique_table(
				array( "href" => "resource", "title" => ( $config->show_hostnames ) ? "remote_addr" : "remote_ip", "display" => "resource" ),
				$config->truncate_medium,
				"dt > ".$config->recent_threshold
			),
			"mediummodule"
		);
	}
}

function recent_table( $_cols, $_truncate, $_where_clause="", $_filters=array() ) {
	$config =& SlimStatConfig::get_instance();
	
	$thead = array(
		"first" => array( ( isset( $config->i18n->fields[$_cols["display"]] ) ? $config->i18n->fields[$_cols["display"]] : $_cols["display"] ) ),
		"last" => array( "&nbsp;", $config->i18n->when )
	);
	$tbody = array();
	
	$col_query = ( is_array( $_cols ) ) ? array_merge( $_cols, array( "dt" ) ) : array( $_cols, "dt" );
	$results = SlimStat::_get_table_data( $col_query, $_where_clause, implode( ", ", $col_query ), "dt", $_filters );
	
	foreach ( $results as $result ) {
		$display_value = $result[$_cols["display"]];
		if ( isset( $config->i18n->name_lookups[ $_cols["display"] ] ) ) {
			if ( is_array( $config->i18n->name_lookups[ $_cols["display"] ] ) && isset( $config->i18n->name_lookups[ $_cols["display"] ][ $display_value ] ) ) {
				$display_value = $config->i18n->name_lookups[ $_cols["display"] ][ $display_value ];
			} elseif ( is_string( $config->i18n->name_lookups[ $_cols["display"] ] ) ) {
				$display_value = eval( str_replace( "VALUE", $display_value, $config->i18n->name_lookups[ $_cols["display"] ] ) );
			}
		}
		
		$title_value = $result[$_cols["title"]];
		if ( isset( $config->i18n->name_lookups[ $_cols["title"] ] ) ) {
			if ( is_array( $config->i18n->name_lookups[ $_cols["title"] ] ) && isset( $config->i18n->name_lookups[ $_cols["title"] ][ $title_value ] ) ) {
				$title_value = $config->i18n->name_lookups[ $_cols["title"] ][ $title_value ];
			} elseif ( is_string( $config->i18n->name_lookups[ $_cols["title"] ] ) ) {
				$title_value = eval( str_replace( "VALUE", $title_value, $config->i18n->name_lookups[ $_cols["title"] ] ) );
			}
		}
		
		$row = array( "first" => array(), "last" => array() );
		
		$str = "<a href=\"?a=68&filter_".$_cols["display"]."=".urlencode( $result[$_cols["display"]] );
		$str .= "\" title=\"".$config->i18n->link_title( "details_filtered", "FIELD", strtolower( $config->i18n->fields[ $_cols["display"] ] ) )."\">";
		$str .= SlimStat::truncate( utf8_encode( $display_value ), $_truncate - SlimStat::strlen( $config->i18n->when ) )."</a>";
		$row["first"][] = $str;
		
		$str = "<a target=\"_blank\" href=\"".$result[$_cols["href"]]."\" class=\"external\" rel=\"nofollow\"";
		$str .= " title=\"".$config->i18n->link_title( "external", "FIELD", strtolower( $config->i18n->fields[ $_cols["href"] ] ) )."\">";
		$str .= "<img src=\"external.gif\" width=\"9\" height=\"9\" alt=\"\" /></a>";
		$row["last"][] = $str;
		
		$row["last"][] = $config->i18n->time_label( $result["dt"], time() );
		
		$tbody[] = $row;
	}
	
	return SlimStat::render_table( $thead, $tbody );
}

function unique_table( $_cols, $_truncate, $_where_clause="", $_filters=array() ) {
	$config =& SlimStatConfig::get_instance();
	
	$thead = array(
		"first" => array( ( isset( $config->i18n->fields[$_cols["display"]] ) ? $config->i18n->fields[$_cols["display"]] : $_cols["display"] ) ),
		"last" => array( "&nbsp", $config->i18n->hits, $config->i18n->since )
	);
	$tbody = array();
	
	$results = SlimStat::get_unique_data( $_cols, $_where_clause, $_cols["display"], $_filters );
	
	foreach ( $results as $result ) {
		$display_value = $result[$_cols["display"]];
		if ( isset( $config->i18n->name_lookups[ $_cols["display"] ] ) ) {
			if ( is_array( $config->i18n->name_lookups[ $_cols["display"] ] ) && isset( $config->i18n->name_lookups[ $_cols["display"] ][ $display_value ] ) ) {
				$display_value = $config->i18n->name_lookups[ $_cols["display"] ][ $display_value ];
			} elseif ( is_string( $config->i18n->name_lookups[ $_cols["display"] ] ) ) {
				$display_value = eval( str_replace( "VALUE", $display_value, $config->i18n->name_lookups[ $_cols["display"] ] ) );
			}
		}
		
		$row = array( "first" => array(), "last" => array() );
		
		$str = "<a href=\"?a=68&filter_".$_cols["display"]."=".urlencode( $result[$_cols["display"]] );
		$str .= "\" title=\"".$config->i18n->link_title( "details_filtered", "FIELD", strtolower( $config->i18n->fields[ $_cols["display"] ] ) )."\">";
		$str .= SlimStat::truncate( $display_value, $_truncate - (int)( SlimStat::strlen( $config->i18n->hits ) + SlimStat::strlen( $config->i18n->since ) ) )."</a>";
		$row["first"][] = $str;
		
		$str = "<a target=\"_blank\" href=\"".$result[$_cols["href"]]."\" class=\"external\" rel=\"nofollow\"";
		$str .= " title=\"".$config->i18n->link_title( "external", "FIELD", strtolower( $config->i18n->fields[ $_cols["href"] ] ) )."\">";
		$str .= "<img src=\"external.gif\" width=\"9\" height=\"9\" alt=\"\" /></a>";
		$row["last"][] = $str;
		
		/*$str = "<a href=\"".$result[$_cols["href"]]."\" class=\"external\" rel=\"nofollow\"";
		$str .= " title=\"".$config->i18n->fields[ $_cols["title"] ].": ".htmlentities( $result[$_cols["title"]] )."\">";
		$str .= SlimStat::truncate( $display_value, $config->truncate - 6 )."</a>";
		$row["first"][] = $str;*/
		
		$row["last"][] = $result["hits"];
		$row["last"][] = $config->i18n->time_label( $result["mindt"], time() );
		
		$tbody[] = $row;
	}
	
	return SlimStat::render_table( $thead, $tbody );
}

?>
