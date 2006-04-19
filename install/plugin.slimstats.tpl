/*
 * SlimStats plugin for MODx
 * Written By Ryan Thrash - 18 Apr 2006
 * Version 1.0
 *
 * Adds SlimStats tracking to your site. Disable plugin to disable stats. 
 *
 * Configuration:
 * check the OnWebPagePrerender event
 *
 * Based on a modified version of SlimStats from Stephen Wettone.
 * Modifications made to work within MODx path structure:
 *
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

@include_once( $modx->config['base_path']."/assets/plugins/slimstats/inc.stats.php" );