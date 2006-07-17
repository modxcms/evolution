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

class SlimStatConfig {
	/** Whether SlimStat is enabled */
	var $enabled = true;
	
	/** Database connection */
	var $server = "localhost"; // Leave as localhost unless you know otherwise
	var $username = ""; // The username used to access your database
	var $password = ""; // The password used to access your database
	var $database = ""; // The database containing the stats table
	
	/** Database tables */
	var $stats = "slimstat"; // Primary stats table
	var $dt_table = "slimstat_dt"; // Date/time totals table
	var $countries = "slimstat_iptocountry"; // IP-to-country lookup table
	
	/** The full name of your site */
	var $sitename = "";
	
	/** Whether to use gzip handler in the buffer */
	var $use_gzip = false;

	/** Whether to display number of visits */
	var $show_visits = true;
	
	/** Whether to display number of unique IP addresses */
	var $show_uniques = false;
	
	/** Whether to display hits from crawlers */
	var $show_crawlers = false;
	
	/** Whether to log hits from crawlers. The database will be smaller if
	this is disabled */
	var $log_crawlers = false;
	
	/** Whether to look up and show hostnames (nice, but possibly slow) */
	var $show_hostnames = false;
	
	/** Maximum number of rows displayed in each table */
	var $rows = 50;
	
	/** Maximum number of characters shown in names */
	var $truncate = 25;
	
	/** Maximum number of characters shown shown in medium-size modules */
	var $truncate_medium = 55;
	
	/** Don't log hits from these IP ranges */
	var $ignored_ips = array( "10." );
	
	/** Whether to record user-agent strings in the database. The database
	will be smaller if this is disabled */
	var $log_user_agents = false;
	
	/** Show data in this order. One of "hits", "visits" or "uniques". Note
	the last two only work if $show_visits or $show_uniques is enabled */
	var $order_by = "visits";
	
	/** How many hours your local time is ahead of server time */
	var $dt_offset_hrs = 0;
	
	/** Which URL to use for WHOIS lookups */
	var $whoisurl = 'http://www.dnsstuff.com/tools/whois.ch?ip=%i';
	//var $whoisurl = 'http://www.samspade.org/t/lookat?a=%i';
	
	/** Which language to use. Default is "en-us" */
	var $language = "en-us";
	
	/** Which day of the week to start on. 0=Sunday, 1=Monday, etc. */
	var $week_start_day = 0;
	
	/** Maximum amount of time to keep data, in days */
	var $max_data_age_days = 365;
	
	/** Don't log hits from referring domains containing these words */
	var $spam_words = array(
		"roulette", "gambl", "vegas", "poker", "casino", "blackjack", "omaha",
		"stud", "hold", "slot", "bet", "pills", "cialis", "viagra", "xanax",
		"watches", "loans", "phentermine", "naked", "cam", "sex", "nude",
		"loan", "mortgage", "financ", "rates", "debt", "dollar", "cash",
		"traffic", "babes", "valium"
	);
	
	/** Which modules to show on the Summary and Details pages */
	var $show_modules = array(
		// Summary
		"summary" => true,            // Summary
		"recent_referer" => true,     // Recent Referrers
		"recent_searchterms" => true, // Recent Search Strings
		"recent_resource" => true,    // Recent Resources
		"unique_domain" => true,      // New Referring Domains
		"unique_resource" => true,    // New Resources
		
		// Details
		"hourly" => true,        // Hourly hits
		"daily" => true,         // Daily hits
		"weekly" => true,        // Weekly hits
		"monthly" => true,       // Monthly hits
		"resource" => true,      // Resources
		"next_resource" => true, // Next Resources
		"searchterms" => true,   // Search Strings
		"domain" => true,        // Referring Domains
		"referer" => true,       // Referrers
		"browser" => true,       // Browsers
		"version" => true,       // Browser Versions
		"platform" => true,      // Platforms
		"country" => true,       // Countries
		"language" => true,      // Languages
		"remote_ip" => true,     // Visitors
		"visit" => true,         // Visits
		"dayofweek" => true,     // Weekdays
		"hour" => true,          // Hours
		"pageviews" => true      // Pages Viewed
	);
	
	/** SlimStat version */
	var $version = "0.9.3";
	
	////////////////////////////////////////////////////////////////////////////
	// Don't change anything below this line
	////////////////////////////////////////////////////////////////////////////
	
	var $hour;
	var $day;
	var $week;
	
	var $visit_length;
	
	var $recent_threshold;
	
	/** SlimStatI18n object will be created here */
	var $i18n;
	
	/** Automatically calculated based on the value of $dt_offset_hrs */
	var $dt_offset_secs = 0;
	
	/** http://developer.apple.com/internet/safari/uamatrix.html */
	var $safari_ua_matrix = array(
		"417.8" => "2.0.3",
		"416.13" => "2.0.2",
		"416.12" => "2.0.2",
		"412.5" => "2.0.1",
		"412" => "2.0",
		"312.5" => "1.3.2",
		"312.3" => "1.3.1",
		"312" => "1.3",
		"125.12" => "1.2.4",
		"125.11" => "1.2.4",
		"125.9" => "1.2.3",
		"125.8" => "1.2.2",
		"125.7" => "1.2.2",
		"125" => "1.2",
		"85.8" => "1.0.3",
		"85.7" => "1.0.2",
		"85" => "1.0"
	);
	
	/** http://www.omnigroup.com/applications/omniweb/developer/ */
	var $omniweb_ua_matrix = array(
		"563" => "5.1",
		"558" => "5.0",
		"496" => "4.5"
	);
	
	var $ems_values = array(
		"33" => 0.54, "34" => 0.69, "35" => 1.07, "36" => 1.07,
		"37" => 1.23, "38" => 1.23, "39" => 0.38, "40" => 0.61,
		"41" => 0.61, "42" => 0.92, "43" => 1.38, "44" => 0.54,
		"45" => 1.07, "46" => 0.54, "47" => 0.92, "48" => 1.07,
		"49" => 1.07, "50" => 1.07, "51" => 1.07, "52" => 1.07,
		"53" => 1.07, "54" => 1.07, "55" => 1.07, "56" => 1.07,
		"57" => 1.07, "58" => 0.54, "59" => 0.54, "60" => 1.38,
		"61" => 1.38, "62" => 1.38, "63" => 0.92, "64" => 1.53,
		"65" => 1.23, "66" => 1.07, "67" => 1.23, "68" => 1.38,
		"69" => 0.92, "70" => 0.92, "71" => 1.23, "72" => 1.38,
		"73" => 0.54, "74" => 0.54, "75" => 1.23, "76" => 0.92,
		"77" => 1.53, "78" => 1.38, "79" => 1.38, "80" => 0.92,
		"81" => 1.38, "82" => 1.07, "83" => 0.92, "84" => 1.07,
		"85" => 1.23, "86" => 1.23, "87" => 1.53, "88" => 1.07,
		"89" => 1.07, "90" => 1.07, "91" => 0.61, "92" => 0.92,
		"93" => 0.61, "94" => 1.07, "95" => 0.92, "96" => 1.07,
		"97" => 0.92, "98" => 1.07, "99" => 0.92, "100" => 1.07,
		"101" => 1.07, "102" => 0.61, "103" => 1.07, "104" => 1.07,
		"105" => 0.54, "106" => 0.54, "107" => 1.07, "108" => 0.54,
		"109" => 1.53, "110" => 1.07, "111" => 1.07, "112" => 1.07,
		"113" => 1.07, "114" => 0.77, "115" => 0.92, "116" => 0.61,
		"117" => 1.07, "118" => 0.92, "119" => 1.38, "120" => 1.07,
		"121" => 0.92, "122" => 1.07, "123" => 0.61, "124" => 0.69,
		"125" => 0.61, "126" => 1.07
	);
	
    function SlimStatConfig() {
		global $modx;
        $this->hour = 60 * 60;
		$this->day = $this->hour * 24;
		$this->week = $this->day * 7;
		$this->visit_length = $this->hour / 2;
		$this->recent_threshold = time() - $this->week;
		
		$this->dt_offset_secs = $this->dt_offset_hrs * $this->hour;
		
		if ( file_exists( $modx->config['base_path'] . "assets/plugins/slimstats/i18n/".preg_replace( "[^A-Za-z\-]", "", $this->language )."/index.php" ) ) {
			include_once( $modx->config['base_path'] . "assets/plugins/slimstats/i18n/".preg_replace( "[^A-Za-z\-]", "", $this->language )."/index.php" );
			$this->i18n = new SlimStatI18n( $this );
		} else { // fall back on en-gb
			$this->language = "en-gb";
			include_once( $modx->config['base_path'] . "assets/plugins/slimstats/i18n/en-gb/index.php" );
			$this->i18n = new SlimStatI18n( $this );
		}
		
		if ( strlen( $this->language ) == 5 ) {
			setlocale( LC_ALL, substr( $this->language, 0, 2 )."_".strtoupper( substr( $this->language, 3, 2 ) ) );
		} else {
			setlocale( LC_ALL, substr( $this->language, 0, 2 )."_".strtoupper( substr( $this->language, 0, 2 ) ) );
		}
	}
    	
	function ems( $_ord ) {
		if ( array_key_exists( strval( $_ord ), $this->ems_values ) ) {
			return $this->ems_values[strval( $_ord )];
		} else {
			return 1;
		}
	}
	
	function &get_instance() {
		global $modx;
        static $instance = array();
		if ( empty( $instance ) ) {
			$instance[] =& new SlimStatConfig();
            $instance[0]->server= $modx->db->config['host'];
            $instance[0]->username= $modx->db->config['user'];
            $instance[0]->password= $modx->db->config['pass'];
            $instance[0]->database= str_replace('`', '', $modx->db->config['dbase']);
            $instance[0]->sitename= $modx->config['site_name'];
		}
		return $instance[0];
	}
	
}

?>
