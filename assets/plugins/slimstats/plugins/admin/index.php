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

if ( $config->max_data_age_days > 0 ) {
	$max_age = mktime( 0, 0, 0, date( "n" ), date( "d" ) - $config->max_data_age_days ) - $config->dt_offset_secs;
	$first_hit = SlimStat::get_first_hit();
	if ( $max_age > $first_hit ) {
		if ( isset( $_POST["confirm"] ) && $_POST["confirm"] == "kill" ) {
			print "<p>Deleting ";
			$dt = strtotime( date( "Y-m-d 00:00:00", $first_hit ) ) - $config->dt_offset_secs;
			while ( $dt < $max_age ) {
				// hour
				$dt_end = mktime( date( "H", $dt ), 59, 59, date( "n", $dt ), date( "d", $dt ), date( "Y", $dt ) );
				$hvu = SlimStat::get_hits_visits_uniques( $dt, $dt_end );
				print ". ";
				
				print date( "H", $dt + $config->dt_offset_secs )."<br>";
				if ( date( "H", $dt ) == 0 || date( "H", $dt + $config->dt_offset_secs ) == 0 ) {
					// day
					$dt_end = mktime( date( "H", $dt ) - 1, 59, 59, date( "n", $dt ), date( "d", $dt ) + 1, date( "Y", $dt ) );
					$hvu = SlimStat::get_hits_visits_uniques( $dt, $dt_end );
					print ". ";
					// week
					$dt_end = mktime( date( "H", $dt ) - 1, 59, 59, date( "n", $dt ), date( "d", $dt ) + 7, date( "Y", $dt ) );
					$hvu = SlimStat::get_hits_visits_uniques( $dt, $dt_end );
					print ". ";
					// month
					$dt_end = mktime( date( "H", $dt ) - 1, 59, 59, date( "n", $dt ) + 1, date( "d", $dt ), date( "Y", $dt ) );
					$hvu = SlimStat::get_hits_visits_uniques( $dt, $dt_end );
					print ". ";
				}
				
				$dt += 60 * 60;
				$dt = strtotime( date( "Y-m-d H:00:00", $dt ) ) - $config->dt_offset_secs;
			}
			print "done!</p>\n";
			$query = "DELETE FROM `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->stats )."`";
			$query .= " WHERE dt<".$max_age;
			print $query;
			mysql_query( $query );
			print "<p>".mysql_affected_rows()." entries deleted.</p>\n";
		} else {
			$max_age = mktime( 0, 0, 0, date( "n" ), date( "d" ) - $config->max_data_age_days ) - $config->dt_offset_secs;
			?>
			<form method="post">
			<input type="hidden" name="show" value="truncate" />
			<p>If you want to delete all SlimStat data more than <?php print $config->max_data_age_days; ?> days old (before <?php print date( "j M Y", $max_age ); ?>), you must confirm this decision below.</p>
			<p><strong>This CANNOT be undone.</strong> It is recommended that you save a recently-generated SlimStat report for posterity.</p>
			<p><label for="confirm">
			<input type="checkbox" id="confirm" name="confirm" value="kill" /> 
			I confirm deletion of all SlimStat data from before <?php print date( "j M Y", $max_age ); ?>
			</label></p>
			<p><input type="submit" value="Delete" /></p>
			</form>
			<?php
		}
	} else {
		?>
		<p>The oldest entry in the database is from <?php print date( "j M Y", SlimStat::get_first_hit() ); ?>.</p>
		<p>SlimStat is configured to delete data more than <?php print $config->max_data_age_days; ?> days old, or from before <?php print date( "j M Y", $max_age ); ?>.</p>
		<p>To delete more data, change <code>$max_data_age_days</code> in <code>_config.php</code>.</p>
		<?php
	}
} else {
	?>
	<p>Set <code>$max_data_age_days</code> to a number greater than zero in <code>_config.php</code>.</p>
	<?php
}

?>
