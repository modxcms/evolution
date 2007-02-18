<?php
/*******************************************************************************
    $Id: phpSniff.class.php,v 1.21 2003/07/02 23:28:35 epsilon7 Exp $
    
    phpSniff: HTTP_USER_AGENT Client Sniffer for PHP
    Copyright (C) 2001 Roger Raymond ~ epsilon7@users.sourceforge.net

    This library is free software; you can redistribute it and/or
    modify it under the terms of the GNU Lesser General Public
    License as published by the Free Software Foundation; either
    version 2.1 of the License, or (at your option) any later version.

    This library is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
    Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public
    License along with this library; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*******************************************************************************/
require_once($modx->config['base_path'] . 'manager/includes/sniff/phpSniff.core.php');

//===============================================================
//  the following defines are used to create the short
//  browser name used throughout the script.
//  change these to suit your needs
//===============================================================

class phpSniff extends phpSniff_core
{   var $_version = '2.1.3';
    /**
     *  Configuration
     *
     *  $_temp_file_path
     *      default : /tmp/
     *      desc    : directory writable by the server to store cookie check files.
     *              : trailing slash is needed. only used if you use the check cookie routine
     *
     *  $_check_cookies
     *      default : null
     *      desc    : Allow for the script to redirect the browser in order
     *              : to check for cookies.   In order for this to work, this
     *              : class must be instantiated before any headers are sent.
     *
     *  $_default_language
     *      default : en-us
     *      desc    : language to report as if no languages are found
     *
     *  $_allow_masquerading
     *      default : null
     *      desc    : Allow for browser to Masquerade as another.
     *              : (ie: Opera identifies as MSIE 5.0)
     *
     *  $_browsers
     *      desc    : 2D Array of browsers we wish to search for
     *              : in key => value pairs.
     *              : key   = browser to search for [as in HTTP_USER_AGENT]
     *              : value = value to return as 'browser' property
     *
     *  $_javascript_versions
     *      desc    : 2D Array of javascript version supported by which browser
     *              : in key => value pairs.
     *              : key   = javascript version
     *              : value = search parameter for browsers that support the
     *              :         javascript version listed in the key (comma delimited)
     *              :         note: the search parameters rely on the values
     *              :               set in the $_browsers array
     *
     *  $_browser_features
     *      desc    : 2D Array of browser features supported by which browser
     *              : in key => value pairs.
     *              : key   = feature
     *              : value = search parameter for browsers that support the
     *              :         feature listed in the key (comma delimited)
     *              :         note: the search parameters rely on the values
     *              :               set in the $_browsers array
     *
     *  $_browser_quirks
     *      desc    : 2D Array of browser quirks present in which browser
     *              : in key => value pairs.
     *              : key   = quirk
     *              : value = search parameter for browsers that feature the
     *              :         quirk listed in the key (comma delimited)
     *              :         note: the search parameters rely on the values
     *              :               set in the $_browsers array
     **/

    var $_temp_file_path        = '/tmp/'; // with trailing slash
    var $_check_cookies         = NULL;
    var $_default_language      = 'en-us';
    var $_allow_masquerading    = NULL;
    var $_php_version           = '';
    
    var $_browsers = array(
        'microsoft internet explorer' => 'IE',
        'msie'                        => 'IE',
        'netscape6'                   => 'NS',
        'netscape'                    => 'NS',
        'galeon'                      => 'GA',
        'phoenix'                     => 'PX',
        'mozilla firebird'            => 'FB',
        'firebird'                    => 'FB',
        'chimera'                     => 'CH',
        'camino'                      => 'CA',
        'safari'                      => 'SF',
        'k-meleon'                    => 'KM',
        'mozilla'                     => 'MZ',
        'opera'                       => 'OP',
        'konqueror'                   => 'KQ',
        'icab'                        => 'IC',
        'lynx'                        => 'LX',
        'links'                       => 'LI',                    
        'ncsa mosaic'                 => 'MO',
        'amaya'                       => 'AM',
        'omniweb'                     => 'OW',
        'hotjava'                      => 'HJ',
        'browsex'                     => 'BX',
        'amigavoyager'                => 'AV',
        'amiga-aweb'                  => 'AW',
        'ibrowse'                     => 'IB'
        );

    var $_javascript_versions = array(
        '1.5'   =>  'NS5+,MZ,PX,FB,GA,CH,CA,SF,KQ3+,KM', // browsers that support JavaScript 1.5
        '1.4'   =>  '',
        '1.3'   =>  'NS4.05+,OP5+,IE5+',
        '1.2'   =>  'NS4+,IE4+',
        '1.1'   =>  'NS3+,OP,KQ',
        '1.0'   =>  'NS2+,IE3+',
        '0'     =>    'LI,LX,HJ'    
        );
        
    var $_browser_features = array(
        /**
         *    the following are true by default
         *    (see phpSniff.core.php $_feature_set array)
         *    browsers listed here will be set to false
         **/
        'html'        =>    '',
        'images'    =>    'LI,LX',
        'frames'     =>    'LI,LX',
        'tables'    =>    '',
        'java'        =>    'OP3,LI,LX,NS1,MO,IE1,IE2',
        'plugins'    =>    'IE1,IE2,LI,LX',
        /**  
         *    the following are false by default
         *    (see phpSniff.core.php $_feature_set array)
         *    browsers listed here will be set to true
         **/
        'css2'        =>    'NS5+,IE5+,MZ,PX,FB,CH,CA,SF,GA,KQ3+,OP7+,KM',
        'css1'        =>    'NS4+,IE4+,MZ,PX,FB,CH,CA,SF,GA,KQ,OP7+,KM',
        'iframes'    =>    'IE3+,NS5+,MZ,PX,FB,CH,CA,SF,GA,KQ,OP7+,KM',
        'xml'        =>    'IE5+,NS5+,MZ,PX,FB,CH,CA,SF,GA,KQ,OP7+,KM',
        'dom'        =>    'IE5+,NS5+,MZ,PX,FB,CH,CA,SF,GA,KQ,OP7+,KM',
        'hdml'        =>    '',
        'wml'        =>    ''
        );
        
    var $_browser_quirks = array(
        'must_cache_forms'            =>    'NS,MZ,FB,PX',
        'avoid_popup_windows'        =>    'IE3,LI,LX',
        'cache_ssl_downloads'        =>    'IE',
        'break_disposition_header'    =>    'IE5.5',
        'empty_file_input_value'    =>    'KQ',
        'scrollbar_in_way'            =>    'IE6'
        );

    function phpSniff($UA='',$settings = true)
    {   //  populate the HTTP_USER_AGENT string
        //  20020425 :: rraymond
        //      routine for easier configuration of the client at runtime
        if(is_array($settings)) {
            $run = true;
            extract($settings);
            $this->_check_cookies = $check_cookies;
            $this->_default_language = $default_language;
            $this->_allow_masquerading = $allow_masquerading;
        } else {
            // for backwards compatibility with 2.0.x series
            $run = $settings;
        }
        
        // 20020425 :: besfred
        if(empty($UA)) $UA = getenv('HTTP_USER_AGENT');
        if(empty($UA)) {
            $pv = explode(".", PHP_VERSION);
            $UA = ( $pv[0] > 3 && $pv[1] > 0 ) ? $_SERVER['HTTP_USER_AGENT'] : $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
        }
        // 20020910 :: rraymond
        if(empty($UA)) return false;
        
        $this->_set_browser('ua',$UA);
        if($run) $this->init();
    }
}
?>