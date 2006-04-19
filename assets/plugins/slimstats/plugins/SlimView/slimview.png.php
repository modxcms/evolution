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

//include('./functions.php'); // Need this for HSV <=> RGB and HEX <=> RGB conversions
//include('./svconfig.php');

include('svconfig.php');
include('functions.php');

// Basic statistics from database
$data = unserialize(rawurldecode(stripslashes($_GET["data"])));
$num_stats = count($data[0])-1;
for($i=1;$i<=$num_stats;$i++) {
	${"data".$i} = array();
}
$times = array();
foreach($data as $unit) { // A unit is a day, week or month
	for($i=1;$i<=$num_stats;$i++) {
		${"data".$i}[] = $unit[$i-1];
	}
	$times[] = substr($unit[$num_stats],0,3);
}
$data_num = count($data1);

// Create a new blank image
$im = imageCreate($width,$height);

// Set the colours and basic dimensions
$colours = unserialize(rawurldecode(stripslashes($_GET["colours"])));
for($i=1;$i<=$num_stats;$i++) {
	${"comp_colour".$i} = explode(',',$colours[$i]);
	${"comp_colour".$i} = imageColorAllocate($im, ${"comp_colour".$i}[0], ${"comp_colour".$i}[1], ${"comp_colour".$i}[2]);
}
$text_colour = explode(',',$colours[0]);
$text_colour = imageColorAllocate($im, $text_colour[0], $text_colour[1], $text_colour[2]);
$white = imageColorAllocate($im, 255, 255, 255);
$grey = imageColorAllocate($im, 204, 204, 204);

$point_diameter = (empty($point_diameter) || !preg_match("/^[0-9]{1,2}$/", $point_diameter)) ? 5 : $point_diameter;
$top_margin = $point_diameter/1.99;
$font_size = (empty($font_size) || !preg_match("/^[1-5]{1}$/", $font_size)) ? 2 : $font_size;

// Calculate the factor for y values and round the maximum y value up to the nearest big factor of 4
$max_y = max($data1);
$exp = ($max_y<10) ? 10 : pow(10,strlen($max_y)-1);
$max_y = (ceil($max_y/$exp))*$exp;
while($max_y%4!=0) {
	$max_y = $max_y+$exp;
}
$bottom_margin = $font_size+10;
$gridheight = $height-($bottom_margin+$top_margin);
$gridbase = $height-$bottom_margin;
$y_factor = $gridheight/$max_y;

// Calculate the left and right margins
$max_x = $times[$data_num-1];
$left_margin = (string_width($max_y,$font_size))+(strlen($max_y)*3);
$right_margin = max(string_width($max_x,$font_size),($point_diameter/1.99));

// Calculate the width of each x-increment
$gridwidth = $width-($left_margin+$right_margin);
$x_inc = ($data_num==1) ? $gridwidth : $gridwidth/($data_num-1);

// Draw empty grid
imageFilledRectangle($im, 0, 0, $width, $height, $white);
imageRectangle($im, $left_margin, $top_margin, $width-$right_margin, $height-$bottom_margin, $grey);
$grid = array(($gridheight*0.25)+$top_margin, ($gridheight*0.5)+$top_margin, ($gridheight*0.75)+$top_margin);
foreach($grid as $gridvalue) {
	imageLine($im, $left_margin, $gridvalue, $width-$right_margin, $gridvalue, $grey);
}

// Draw y labels
for($i=0;$i<=4;$i++) {
	// (resource image, font size, int x, int y, string, colour)
	$string = $max_y*(1-($i/4));
	$indent = (strlen($max_y)*($font_size+4))-(strlen($string)*($font_size+4));
	if($i<4) { // Don't print '0'.
		imagestring($im, $font_size, $indent, ($gridheight*($i/4))-$top_margin, $string, $text_colour);
	}
}
// Draw x labels
$i = 0;
$x_factor = ceil($data_num/10); // Used to calculate how many x labels to show
foreach($times as $time) {	
	if($i%$x_factor==0) {
		imagestring($im, $font_size, ($left_margin-((string_width($time,$font_size))/2))+($x_inc*$i), $gridheight+$font_size, $time, $text_colour);
	}
	$i++;
}

// Calculate and plot the points and for hits and visits
for($i=1;$i<=$num_stats;$i++) {
	// For each item in each data array, create an array of x,y points
	$j = 0;
	${"points".$i} = array();
	foreach(${"data".$i} as $data) {
		${"points".$i}[] = array($x_inc*$j,$data*$y_factor);
		$j++;
	}
	// Plot points from x,y point arrays
	$k = 0;
	foreach(${"points".$i} as $point) {
		// Plot the point (centre x, centre y, width, height)
		imageFilledEllipse($im, $left_margin+$point[0], $gridbase-$point[1], $point_diameter, $point_diameter, ${"comp_colour".$i});
		// Draw a line if it's not the first point
		if($k!=0) {
			imageLine($im, $previousx, $previousy, $left_margin+$point[0], $gridbase-$point[1], ${"comp_colour".$i});
		}
		$previousx = $left_margin+$point[0];
		$previousy = $gridbase-$point[1];
		$k++;
	}
}

// Send the headers last of all
header('Content-type: image/png');

// Output the image as a PNG
imagePNG($im);

// Delete the image from memory
imageDestroy($im);
?>