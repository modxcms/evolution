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

include_once( $modx->config['base_path'] . "assets/plugins/slimstats/_config.php" );
include_once( $modx->config['base_path'] . "assets/plugins/slimstats/_functions.php" );

class SlimStatRecord {
	var $config;
	
	function SlimStatRecord() {
		$this->config =& SlimStatConfig::get_instance();
		
		if ( $this->config->enabled ) {
			SlimStat::connect( $this->config );
			
			$stat = array();
			$stat["remote_ip"]   = SlimStat::my_esc( SlimStat::get_client_ip_address() );
			if ( $this->config->show_hostnames == true && (bool)ini_get( "safe_mode" ) == false ) {
				$stat["remote_addr"] = SlimStat::get_host( $stat["remote_ip"], true, $this->config );
			}
			$stat["country"]     = SlimStat::my_esc( $this->_determine_country( $stat["remote_ip"] ) );
			$stat["language"]    = SlimStat::my_esc( $this->_determine_language() );
			$stat["referer"]     = SlimStat::my_esc( ( isset( $_SERVER["HTTP_REFERER"] ) ) ? $_SERVER["HTTP_REFERER"] : "" );
			$url = parse_url( $stat["referer"] );
			$stat["domain"]      = SlimStat::my_esc( ( isset( $url["host"] ) ) ? eregi_replace( "^www.", "", $url["host"] ) : "" );
			$stat["searchterms"] = SlimStat::my_esc( $this->_determine_searchterms( $url ) );
			
			if ( isset( $_SERVER["REQUEST_URI"] ) ) {
				$stat["resource"] = SlimStat::my_esc( $_SERVER["REQUEST_URI"] );
			} elseif ( isset( $_SERVER["SCRIPT_NAME"] ) && isset( $_SERVER["QUERY_STRING"] ) ) {
				$stat["resource"] = SlimStat::my_esc( $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"] );
			} elseif ( isset( $_SERVER["SCRIPT_NAME"] ) ) {
				$stat["resource"] = SlimStat::my_esc( $_SERVER["SCRIPT_NAME"] );
			} elseif ( isset( $_SERVER["PHP_SELF"] ) && isset( $_SERVER["QUERY_STRING"] ) ) {
				$stat["resource"] = SlimStat::my_esc( $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"] );
			} elseif ( isset( $_SERVER["PHP_SELF"] ) ) {
				$stat["resource"] = SlimStat::my_esc( $_SERVER["PHP_SELF"] );
			}
			
			if ( $this->config->log_user_agents == true ) {
				$stat["user_agent"] = SlimStat::my_esc( $_SERVER["HTTP_USER_AGENT"] );
			}
			
			$browser = $this->_parse_user_agent( $_SERVER["HTTP_USER_AGENT"] );
			$stat["browser"]     = SlimStat::my_esc( $browser["browser"] );
			$stat["version"]     = SlimStat::my_esc( $browser["version"] );
			$stat["platform"]    = SlimStat::my_esc( $browser["platform"] );
			
			$stat["visit"]       = SlimStat::my_esc( $this->_determine_visit( $stat["remote_ip"], $browser["browser"], $browser["version"], $browser["platform"], $_SERVER["HTTP_USER_AGENT"] ) );
			$stat["dt"]          = SlimStat::my_esc( time() );
			
			// check whether to ignore this
			$is_ignored = false;
			foreach ( $this->config->ignored_ips as $ip ) {
				if ( strpos( $stat["remote_ip"], $ip ) === 0 ) {
					$is_ignored = true;
					break;
				}
			}
			if ( $this->config->log_crawlers == false &&
			     $stat["browser"] == $this->config->i18n->crawler ) {
				$is_ignored = true;
			}
			
			// attempt to detect spam
			$is_spam = false;
			foreach ( $this->config->spam_words as $spam_word ) {
				if ( stristr( $stat["referer"], $spam_word ) ) {
					$is_spam = true;
					break;
				}
			}
			
			$domain_array = explode( "-", $stat["domain"] );
			if ( sizeof( $domain_array ) > 2 ) {
				$is_spam = true;
			}
			
			if ( strlen( $stat["domain"] ) >= 25 &&
			     ( !isset( $_SERVER["SERVER_NAME"] ) ||
			       $stat["domain"] != eregi_replace( "^www.", "", $_SERVER["SERVER_NAME"] ) ) ) {
				$is_spam = true;
			}
			
			// record this hit
			if ( $is_ignored ) {
				// do nothing
			} elseif ( $is_spam ) {
				// do nothing
			} else {
				$query = "INSERT INTO `".SlimStat::my_esc( $this->config->database )."`.`".SlimStat::my_esc( $this->config->stats )."` ( `";
				$query .= implode( "`, `", array_keys( $stat ) );
				$query .= "` ) VALUES ( \"";
				$query .= implode( "\", \"", array_values( $stat ) );
				$query .= "\" )";
				
				@mysql_query( $query );
			}
		}
	}
	
	/**
	 * Determines the viewer's country based on their ip address.
	 */
	function _determine_country( $_ip ) {
		if ( SlimStat::is_ip_to_country_installed( $this->config ) ) {
			$ip = sprintf( "%u", ip2long( $_ip ) );
			
			$query = "SELECT `country_name` FROM `".SlimStat::my_esc( $this->config->database )."`.`".SlimStat::my_esc( $this->config->countries )."` WHERE `ip_from` <= ".$ip." AND `ip_to` >= ".$ip;
			if ( $result = mysql_query( $query ) ) {
				if ( list( $country_name ) = mysql_fetch_row( $result ) ) {
					return trim( ucwords( preg_replace( "/([A-Z\xC0-\xDF])/e", "chr(ord('\\1')+32)", $country_name ) ) );
				}
			}
		}
		
		return $this->config->i18n->indeterminable;
	}
	
	function _determine_language() {
		global $_SERVER;
		
		if ( isset( $_SERVER["HTTP_ACCEPT_LANGUAGE"] ) ) {
			// Capture up to the first delimiter (comma found in Safari)
			preg_match( "/([^,;]*)/", $_SERVER["HTTP_ACCEPT_LANGUAGE"], $langs );
			$lang_choice = $langs[0];
		} else {
			$lang_choice = "empty";
		}
		
		return $lang_choice;
	}
	
	/**
	 * Sniffs out referrals from search engines and tries to determine the query string.
	 */
	function _determine_searchterms( $_url ) {
		if ( !is_array( $_url ) ) {
			$_url = parse_url( $_url );
		}
		
		$searchterms = "";
		
		if ( isset( $_url["host"] ) && isset( $_url["query"] ) ) {
			$sniffs = array( // host regexp, query portion containing search terms
				array( "/google\./i", "q" ),
				array( "/alltheweb\./i", "q" ),
				array( "/yahoo\./i", "p" ),
				array( "/search\.aol\./i", "query" ),
				array( "/search\.cs\./i", "query" ),
				array( "/search\.netscape\./i", "query" ),
				array( "/hotbot\./i", "query" ),
				array( "/search\.msn\./i", "q" ),
				array( "/altavista\./i", "q" ),
				array( "/web\.ask\./i", "q" ),
				array( "/search\.wanadoo\./i", "q" ),
				array( "/www\.bbc\./i", "q" ),
				array( "/tesco\.net/i", "q" ),
				array( "/yandex\./i", "text" ),
				array( "/rambler\./i", "words" ),
				array( "/aport\./i", "r" ),
				array( "/.*/", "query" ),
				array( "/.*/", "q" )
			);
			
			foreach ( $sniffs as $sniff ) {
				if ( preg_match( $sniff[0], $_url["host"] ) ) {
					parse_str( $_url["query"], $q );
					if ( isset( $q[ $sniff[1] ] ) ) {
						$searchterms = trim( stripslashes( utf8_decode( $q[ $sniff[1] ] ) ) );
						break;
					}
				}
			}
		}
		
		return $searchterms;
	}
	
	function _determine_visit( $_remote_ip, $_browser, $_version, $_platform, $_user_agent ) {
		$query = "SELECT `visit` FROM `".SlimStat::my_esc( $this->config->database )."`.`".SlimStat::my_esc( $this->config->stats )."`";
		$query .= " WHERE `remote_ip`='".SlimStat::my_esc( $_remote_ip )."'";
		if ( $this->config->log_user_agents == true ) {
			$query .= " AND `user_agent`='".SlimStat::my_esc( $_user_agent )."'";
		} else {
			$query .= " AND `browser`='".SlimStat::my_esc( $_browser )."'";
			$query .= " AND `version`='".SlimStat::my_esc( $_version )."'";
			$query .= " AND `platform`='".SlimStat::my_esc( $_platform )."'";
		}
		$query .= " AND `dt` >= ".( time() - $this->config->visit_length );
		$query .= " ORDER BY `dt` LIMIT 1";
		
		if ( $result = mysql_query( $query ) ) {
			if ( list( $visit ) = mysql_fetch_row( $result ) ) {
				if ( $visit > 0 ) {
					return $visit;
				}
			}
		}
		
		$query = "SELECT MAX(`visit`) FROM `".SlimStat::my_esc( $this->config->database )."`.`".SlimStat::my_esc( $this->config->stats )."`";
		
		if ( $result = mysql_query( $query ) ) {
			if ( list( $visit ) = mysql_fetch_row( $result ) ) {
				return $visit + 1;
			}
		}
		
		return 1;
	}
	
	/**
	 * Attempts to suss out the browser info from its user agent string.
	 * It is possible to spoof a string though so don't blame me if something
	 * doesn't seem right. This will need updating as newer browsers are released.
	 */
	function _parse_user_agent( $_ua ) {
		$browser = array(
			"platform" => $this->config->i18n->indeterminable,
			"browser"  => $this->config->i18n->indeterminable,
			"version"  => $this->config->i18n->indeterminable,
			"majorver" => $this->config->i18n->indeterminable,
			"minorver" => $this->config->i18n->indeterminable
		);
		
		// platform
		if ( preg_match( "/Win/", $_ua ) ) {
			$browser["platform"] = $this->config->i18n->platforms["win"];
		} elseif ( preg_match( "/Mac/", $_ua ) ) {
			$browser["platform"] = $this->config->i18n->platforms["mac"];
		} elseif ( preg_match( "/Linux/", $_ua ) ) {
			$browser["platform"] = $this->config->i18n->platforms["linux"];
		}
		
		// browser type
		if ( eregi( "crawl", $_ua ) ||
		     eregi( "bot", $_ua ) ||
		     eregi( "bloglines", $_ua ) ||
		     eregi( "dtaagent", $_ua ) ||
		     eregi( "ia_archiver", $_ua ) ||
		     eregi( "java", $_ua ) ||
		     eregi( "mediapartners", $_ua ) ||
		     eregi( "slurp", $_ua ) ||
		     eregi( "spider", $_ua ) ||
		     eregi( "teoma", $_ua ) ||
		     eregi( "ultraseek", $_ua ) ||
		     eregi( "waypath", $_ua ) ||
		     eregi( "yacy", $_ua ) ) {
			$browser["browser"] = $this->config->i18n->crawler;
		} elseif ( preg_match( "/OmniWeb/", $_ua ) ) {
			$browser["browser"] = "OmniWeb";
			$browser["platform"] = $this->config->i18n->platforms["mac"];
			ereg( 'OmniWeb/v([[:digit:]\.]+)', $_ua, $b );
			$browser["version"] = $b[1];
			
			foreach ( array_keys( $this->config->omniweb_ua_matrix ) as $omniweb_ua ) {
				if ( preg_match( "/".$omniweb_ua."/", $browser["version"] ) ) {
					$browser["version"] = $this->config->omniweb_ua_matrix[$omniweb_ua];
					break;
				}
			}
		} elseif ( preg_match( "/Safari/", $_ua ) ) {
			$browser["browser"] = "Safari";
			$browser["platform"] = $this->config->i18n->platforms["mac"];
			ereg( 'Safari/([[:digit:]\.]+)', $_ua, $b );
			$browser["version"] = $b[1];
			
			foreach ( array_keys( $this->config->safari_ua_matrix ) as $safari_ua ) {
				if ( preg_match( "/".$safari_ua."/", $browser["version"] ) ) {
					$browser["version"] = $this->config->safari_ua_matrix[$safari_ua];
					break;
				}
			}
		} else {
			$sniffs = array( // name regexp, name for display, version regexp, version match, platform (optional)
				array( "Opera", "Opera", "Opera( |/)([[:digit:]\.]+)", 2 ),
				array( "MSIE", "Internet Explorer", "MSIE ([[:digit:]\.]+)", 1 ),
				array( "Firefox", "Firefox", "Firefox/([[:digit:]\.]+)",  1 ),
				array( "Firebird", "Firebird", "Firebird/([[:digit:]\.]+)", 1 ),
				array( "Phoenix", "Phoenix", "Phoenix/([[:digit:]\.]+)", 1 ),
				array( "Camino", "Camino", "Camino/([[:digit:]\.]+)", 1 ),
				array( "Flock", "Flock", "Flock/([[:digit:]\.]+)",  1 ),
				array( "Chimera", "Chimera", "Chimera/([[:digit:]\.]+)", 1 ),
				array( "Thunderbird", "Thunderbird", "Thunderbird/([[:digit:]\.]+)",  1 ),
				array( "Netscape", "Netscape", "Netscape[0-9]?/([[:digit:]\.]+)", 1 ),
				array( "OmniWeb", "OmniWeb", "OmniWeb/([[:digit:]\.]+)", 1 ),
				array( "iCab", "iCab", "iCab/([[:digit:]\.]+)", 1 ),
				array( "Konqueror", "Konqueror", "Konqueror/([[:digit:]\.]+)", 1, $this->config->i18n->platforms["linux"] ),
				array( "Lynx", "Lynx", "Lynx/([[:digit:]\.]+)", 1 ),
				array( "Links", "Links", "\(([[:digit:]\.]+)", 1 ),
				array( "W3C_Validator", "W3C Validator", "W3C_Validator/([[:digit:]\.]+)", 1 ),
				array( "ApacheBench", "Apache Bench tool (ab)", "ApacheBench/(.*)$", 1 ),
				array( "lwp-request", "libwww Perl library", "lwp-request/(.*)$", 1 )
			);
			
			foreach ( $sniffs as $sniff ) {
				if ( ereg( $sniff[0], $_ua ) ) {
					$browser["browser"] = $sniff[1];
					ereg( $sniff[2], $_ua, $b );
					$browser["version"] = $b[ $sniff[3] ];
					if ( sizeof( $sniff ) == 5 ) {
						$browser["platform"] = $sniff[4];
					}
					break;
				}
			}
		}
		
		if ( $browser["browser"] == $this->config->i18n->indeterminable ) {
			if ( ereg( "Mozilla/4", $_ua ) && !eregi( "compatible", $_ua ) ) {
				$browser["browser"] = "Netscape";
				eregi( "Mozilla/([[:digit:]\.]+)", $_ua, $b );
				$browser["version"] = $b[1];
			} elseif ( ( ereg( "Mozilla/5", $_ua ) && !eregi( "compatible", $_ua ) ) || ereg( "Gecko", $_ua ) ) {
				$browser["browser"] = "Mozilla";
				eregi( "rv(:| )([[:digit:]\.]+)", $_ua, $b );
				$browser["version"] = $b[2];
			}
		}
		
		// browser version
		if ( $browser["browser"] != $this->config->i18n->indeterminable && $browser["browser"] != $this->config->i18n->crawler && $browser["version"] != $this->config->i18n->indeterminable ) {
			// Make sure we have at least .0 for a minor version
			$browser["version"] = ( !eregi( "\.", $browser["version"] ) ) ? $browser["version"].".0" : $browser["version"];
			eregi( "^([0-9]*).(.*)$", $browser["version"], $v );
			$browser["majorver"] = $v[1];
			$browser["minorver"] = $v[2];
		}
		if ( empty( $browser["version"] ) || $browser["version"] == ".0" ) {
			$browser["version"] = $this->config->i18n->indeterminable;
			$browser["majorver"] = $this->config->i18n->indeterminable;
			$browser["minorver"] = $this->config->i18n->indeterminable;
		}
		
		return $browser;
	}
	
}

new SlimStatRecord();

?>
