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
 
function string_width($string,$font_size) { // Get the pixel width of a string in an image. 
	$width = strlen($string)*($font_size+4);
	return $width;
}
 
function rgb2hsv($rgb) { // Enter $rgb as comma-separated string with or without spaces
	$rgb = str_replace(' ','',$rgb);
	$rgb = explode(',',$rgb);	
	
	$var_R = ($rgb[0]/255);                 //RGB values = 0 ÅÄ 255
	$var_G = ($rgb[1]/255);
	$var_B = ($rgb[2]/255);

	$var_Min = min( $var_R, $var_G, $var_B );    //Min. value of RGB
	$var_Max = max( $var_R, $var_G, $var_B );    //Max. value of RGB
	$del_Max = $var_Max - $var_Min;             //Delta RGB value

	$V = $var_Max;

	if ( $del_Max == 0 ) {                    //This is a grey, no chroma...
		$H = 0;                                //HSV results = 0 ÅÄ 1
		$S = 0;
	} else {                                   //Chromatic data...
		$S = $del_Max / $var_Max;
	
		$del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
		$del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
		$del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

		if ( $var_R == $var_Max ) {
			$H = $del_B - $del_G;
		} else if ( $var_G == $var_Max ) {
			$H = ( 1 / 3 ) + $del_R - $del_B;
		} else if ( $var_B == $var_Max ) {
			$H = ( 2 / 3 ) + $del_G - $del_R;
		}

		if ( $H < 0 ) {
			$H += 1;
		}
		if ( $H > 1 ) {
			$H -= 1;
		}
	}
	return ($H.','.$S.','.$V);
}

function hsv2rgb($hsv) { // Enter $rgb as comma-separated string with or without spaces
	$hsv = str_replace(' ','',$hsv);
	$hsv = explode(',',$hsv);	
	
	$H = $hsv[0];
	$S = $hsv[1];
	$V = $hsv[2];
	
	if ($S == 0) {                      //HSV values = 0 ÅÄ 1
		$R = $V * 255;
		$G = $V * 255;
		$B = $V * 255;
	} else {
		$var_h = $H * 6;
		if ($var_h == 6) {
			$var_h = 0;      //H must be < 1
		}
		$var_i = floor( $var_h );             //Or ... var_i = floor( var_h )
		$var_1 = $V * ( 1 - $S );
		$var_2 = $V * ( 1 - $S * ( $var_h - $var_i ) );
		$var_3 = $V * ( 1 - $S * ( 1 - ( $var_h - $var_i ) ) );

		if ( $var_i == 0 ) {
			$var_r = $V;
			$var_g = $var_3;
			$var_b = $var_1;
		} else if ( $var_i == 1 ) {
			$var_r = $var_2;
			$var_g = $V;
			$var_b = $var_1;
		} else if ( $var_i == 2 ) {
			$var_r = $var_1;
			$var_g = $V;
			$var_b = $var_3;
		} else if ( $var_i == 3 ) {
			$var_r = $var_1;
			$var_g = $var_2;
			$var_b = $V;
		} else if ( $var_i == 4 ) {
			$var_r = $var_3;
			$var_g = $var_1;
			$var_b = $V;
		} else {
			$var_r = $V;
			$var_g = $var_1;
			$var_b = $var_2;
		}
	
		$R = round($var_r * 255);                  //RGB results = 0 ÅÄ 255
		$G = round($var_g * 255);
		$B = round($var_b * 255);	
	}
	return ($R.','.$G.','.$B);
}

function &hex2rgb($hex, $asString = true) {
	// strip off any leading #
	if (0 === strpos($hex, '#')) {
		$hex = substr($hex, 1);
	} else if (0 === strpos($hex, '&H')) {
		$hex = substr($hex, 2);
	}     

	// break into hex 3-tuple
	$cutpoint = ceil(strlen($hex) / 2)-1;
	$rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);

	// convert each tuple to decimal
	$rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
	$rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
	$rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);

	return ($asString ? "{$rgb[0]}, {$rgb[1]}, {$rgb[2]}" : $rgb);
}

function module_color($class, $found, $css_array) { // Gets the module background colour from a CSS file (imported as an array)
	$stage2 = false;
	foreach ($css_array as $key => $value)    {
		if (strpos($value, $class)!==false) {
			$stage2 = true;
		}
		if (strpos($value, $found)!==false && $stage2===true) {
			$colour = preg_split("/[\s;:#]+/", $value);
			if(strlen($colour[2])==3) {
				$r = substr($colour[2],0,1);
				$g = substr($colour[2],1,1);
				$b = substr($colour[2],2,1);
				$new_colour = $r.$r.$g.$g.$b.$b;
			} else {
				$new_colour = $colour[2];
			}
			return $new_colour;
		}
	}  
	return false;
}
?>