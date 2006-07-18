********************************************************************************

SlimStat: a simple web stats analyser based on ShortStat.
Copyright (C) 2006 Stephen Wettone

Version 0.9.4, 19 April 2006

This application uses the IP-to-Country Database provided by webhosting.info:
http://ip-to-country.webhosting.info/


********************************************************************************
LICENSE
................................................................................

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.


********************************************************************************
INSTALLATION INSTRUCTIONS
................................................................................

Step 1. Upload the entire "slimstat" directory to your server. The remainder of
        these instructions assume that you have uploaded this to the root level,
        i.e. that it is at http://www.example.com/slimstat/
        Substitute your actual URL in all following examples.

Step 2. Load http://www.example.com/slimstat/ in the web browser of your choice.
        Follow the instructions to complete installation.

Step 3. Include the inc.stats.php file in your PHP wherever you would like
        stats to be counted. Use code similar to:
        @include_once( $_SERVER["DOCUMENT_ROOT"]."/slimstat/inc.stats.php" );

Step 4. That's it. Load http://www.example.com/slimstat/ in your web browser.


********************************************************************************
UPGRADING FROM OLDER VERSIONS
................................................................................

If you are upgrading from SlimStat 0.4 or earlier, you must add the new
'remote_addr' field before running it. Execute the following SQL:

ALTER TABLE slimstat ADD remote_addr VARCHAR(255) NOT NULL AFTER remote_ip;

If you want to use the updated IP-to-Country database, you should first
remove the old data from your table before running setup.php:

TRUNCATE slimstat_iptocountry;

If not, then you can simply remove setup.php (or not upload it in the first
place) and edit _config.php. You will need to use the new version of
_config.php because there are many new settings contained in it.

Older versions of SlimStat also included _languages.php, which is now obsolete
and may be safely removed.


********************************************************************************
