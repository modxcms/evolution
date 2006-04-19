<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>
<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang["visitor_stats"]; ?></span>
</div>
<div class="sectionBody" id="sitestats" style="padding:0">
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

    ob_start();

    if ( get_magic_quotes_gpc() ) {
    	foreach ( array_keys( $_GET ) as $key ) {
    		$_GET[$key] = stripslashes( $_GET[$key] );
    	}
    	foreach ( array_keys( $_POST ) as $key ) {
    		$_POST[$key] = stripslashes( $_POST[$key] );
    	}
    	foreach ( array_keys( $_COOKIE ) as $key ) {
    		$_COOKIE[$key] = stripslashes( $_COOKIE[$key] );
    	}
    	foreach ( array_keys( $_REQUEST ) as $key ) {
    		$_REQUEST[$key] = stripslashes( $_REQUEST[$key] );
    	}
    }

    require_once( realpath( dirname( __FILE__ ) )."../../../../assets/plugins/slimstats/_functions.php" );
    require_once( realpath( dirname( __FILE__ ) )."../../../../assets/plugins/slimstats/index.php" );

    ?>