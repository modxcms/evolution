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

include('svconfig.php');
include('functions.php');
include_once( "../../_functions.php" );
$config = SlimStatConfig::get_instance();

// Find out which data sources are specified (from ../../_config.php).
$colour_name1 = $config->i18n->hits;
if(strpos($_GET["statcode"],'v')) {
	$colour_name2 = $config->i18n->visits;
	if(strpos($_GET["statcode"],'u')) {
		$colour_name3 = $config->i18n->uniques;
	}
} elseif(strpos($_GET["statcode"],'u')) {
	$colour_name2 = $config->i18n->uniques;
}

// Create a new blank image
$width = 800;
$height = 25;
$im = imageCreate($width,$height);

// Set the colours and basic dimensions
$colours = unserialize(rawurldecode(stripslashes($_GET["colours"])));
$num_stats = count($colours)-1;
for($i=1;$i<=$num_stats;$i++) {
	${"comp_colour".$i} = explode(',',$colours[$i]);
	${"comp_colour".$i} = imageColorAllocate($im, ${"comp_colour".$i}[0], ${"comp_colour".$i}[1], ${"comp_colour".$i}[2]);
}
$text_colour = explode(',',$colours[0]);
$text_colour = imageColorAllocate($im, $text_colour[0], $text_colour[1], $text_colour[2]);
$white = imageColorAllocate($im, 255, 255, 255);

$point_diameter = (empty($point_diameter) || !preg_match("/^[0-9]{1,2}$/", $point_diameter)) ? 5 : $point_diameter;
$font_size = (empty($font_size) || !preg_match("/^[1-5]{1}$/", $font_size)) ? 2 : $font_size;

// Draw empty image
imageFilledRectangle($im, 0, 0, $width, $height, $white);

// Display the legend for each data source.
$left_indent = 0;
for($i=1;$i<=$num_stats;$i++) {
	imageFilledEllipse($im, 16+$left_indent, 10, $point_diameter+1, $point_diameter+1, ${"comp_colour".$i});
	imageLine($im, $left_indent, 15, 30+$left_indent, 5, ${"comp_colour".$i});
	imageLine($im, 2+$left_indent, 15, 32+$left_indent, 5, ${"comp_colour".$i});
	imagestring($im, $font_size, 35+$left_indent, 4, ${"colour_name".$i}, $text_colour);
	$left_indent = $left_indent+string_width(${"colour_name".$i},$font_size)+50;
}

// Send the headers last of all
header('Content-type: image/png');

// Output the image as a PNG
imagePNG($im);

// Delete the image from memory
imageDestroy($im);
?>