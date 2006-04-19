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

// Image width and height.
$width = 219;
$height = 139;

// Diameter of each point in pixels.  Valid values are from 1 (dot) to 99 (big blobby mess).
$point_diameter = 5;

// Guess what this is.  Valid values are from 1 (tiny) to 5 (large).
$font_size = 2;

// Leave this blank to use the your SlimStat module header colour.
// Otherwise enter HTML colour code, e.g. cc9966 (with or without #).
$text_colour = '';

// Leave the following blank to use default complementary colours.
// These are automatically calculated for your viewing pleasure.
// Otherwise enter HTML colour code, e.g. cc9966 (with or without #).
$data_colour1 = '';
$data_colour2 = '';
$data_colour3 = '';
?>