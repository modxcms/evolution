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

// Load the SlimStat files
include_once( realpath( dirname( __FILE__ ) )."/_config.php" );
include_once( realpath( dirname( __FILE__ ) )."/_functions.php" );

$config =& SlimStatConfig::get_instance();

SlimStat::connect();

$hvu = SlimStat::get_hits_visits_uniques( mktime( 0, 0, 0 ) - $config->dt_offset_secs, mktime( 23, 59, 59 ) - $config->dt_offset_secs );

// Make sure the browser knows that this is an xml file
header( "Content-Type: application/xhtml+xml; charset=UTF-8" );

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
?>
<shortstat>
	<websiteinfo>
		<name><?php print $config->sitename; ?></name>
		<url>http://<?php print $_SERVER["HTTP_HOST"] ?></url>
	</websiteinfo>
	<statstoday lastupdate="<?php echo date( 'g:ia' );?>">
		<totalhits><?php print ( isset( $hvu["hits"] ) ) ? $hvu["hits"] : "-"; ?></totalhits>
		<visits><?php print ( isset( $hvu["visits"] ) ) ? $hvu["visits"] : "-"; ?></visits>
		<uniquehits><?php print ( isset( $hvu["uniques"] ) ) ? $hvu["uniques"] : "-"; ?></uniquehits>
		<recentreferrers>
<?php
$query = "SELECT referer, resource, domain, dt 
		  FROM ".SlimStat::my_esc( $config->database ).".".SlimStat::my_esc( $config->stats )."
		  WHERE referer NOT LIKE '%".SlimStat::my_esc( SlimStat::trim_referer( $_SERVER["SERVER_NAME"] ) )."%' AND 
				referer!='' 
		  ORDER BY dt DESC 
		  LIMIT 0,7";
if ( $result = mysql_query( $query ) ) {
	while ( $r = mysql_fetch_array( $result ) ) {
		$when = SlimStat::time_label( $r["dt"] + $config->dt_offset_secs, time() );
		
		$resource = ( $r["resource"] == "/" ) ? $config->i18n->homepage : $r["resource"];
		print "<referrer time=\"".$when."\" url=\"".htmlentities( $r["referer"] )."\">".$r["domain"]."</referrer>";
	}
}
?>
		</recentreferrers>
	</statstoday>
</shortstat>
