MODx CMS free software targeted at developers, licensed under the GPL.
Download the latest version and obtain support at http://modxcms.com/
Copyright 2005-2006 the MODx CMS project. All rights reserved.

MODx is ideal for web developers and designers looking for a customizable web framework or CMS that doesn't place restrictions on their site output -- a tool they can confidently turn over to their end-users for day-to-day site maintenance. It's probably not ideal for web novices -- it's neither Dreamweaver (tm) nor a CSS editor. It's not a portal or blog or community site (though you can build those in it). And it doesn't ship loaded full of plug-and-play add on modules/extras. 

MODx outputs exactly what you create, be it the most semantially pure XHTML/CSS web application or brochureware full of table-tag-soup.

MODx is simply a framework you can build a surprisingly large number of custom web applications, and what we consider to be a pretty solid web CMS that you can use to fly through building marketing websites.


------------------------------
GETTING HELP & LEARNING MODx :
------------------------------
For more installation tips and resources, please visit the following:

    http://modxcms.com/getting-started.html
    http://wiki.modxcms.com/index.php/Category:Installation
    http://modxcms.com/forums/ (Support fourms)
    http://modxcms.com/resources.html (Add-on resources)

Estimated time to install after uploading files to your server: < 5 minutes
Last revision date of this document: Version 1.1 Nov 2, 2006


-------------------------------
SUGGESTED MODx INFRASTRUCTURE :
-------------------------------
The following guidelines should apply to typical LAMP servers. 

<soapbox>
MODx is a PHP application. With that in mind, please be sensible about how you run it and please make sure that your php.ini file is configured for security. If you don't know what that means simple ask your host at a minimum to make sure the following setting is properly handled:

     register_globals OFF

If you DO know what that means, you might be shocked to learn that some of the largest (and cheapest) shared hosting providers out there--including the ones that advertise two-page spreads in glossy magazines--don't either take the time or have the competence to make sure they've properly secured their OS stack and PHP configuration. It's a pity, an inevitable and preventable security compromise waiting for the right exploit to give you a hacked site.

(Anecdotally, the creators of PHP (Zend) consider register_globals being on such a bad idea it's being removed from PHP 6.)
</soapbox>

MODx will run under variety web servers with a little work, including IIS or Apache and on platforms including Linux, Windows, FreeBSD (and other similar platforms), *nix, OS X and Solaris (untested on Solaris but it should work). To minimize your learning curve, we suggest the following software configuration for new users and first-time installations:

    Webserver: Apache (1.3.x or 2.x) with mod_rewrite enabled
    PHP: 4.3.11 and above (optimal performance in 5.1.6)
    MySQL: 4.1.x (performance gains in 5.0.x)

Most modern standards-compliant browsers should work for managing MODx sites. However the following browsers have proven to offer reliable behavior.

    Firefox 1.5.0.7 and 2 (Mac/Windows/Linux)
    Internet Explorer 6 and 7 (Windows)
    Safari 2.0.x (Mac OS X)
    Opera 9.02 (Mac/Windows/Linux) -- needs custom manager template


----------------------------------
UPGRADING PREVIOUS MODx INSTALLS :
----------------------------------
You should be able to upgrade to 0.9.5 from any prior MODx version. For detailed instructions and best practices, please see our wiki for some best practices for upgrading from previous versions:

    http://wiki.modxcms.com/index.php/Upgrading_Guide

As with any upgrade, make sure you have a full backup including your config.inc.php file, all the items in your assets folder, and a current DB dump. If you've customized any default snippets, please make sure they are not named the same as the default snippets, and that if they include items in the assets folder, that you've modified the filesystem path and include lines as appropriate. If you don't have a clue what this means, you should probably be OK, or might consider hiring someone to perform the upgrade for you.


--------------------------------
INSTALLING MODx (NEW INSTALLS) :
--------------------------------
1) After you've downloaded the release, unzip the archive. If you can unzip archives on your server, you may wish to just leave the archive compressed.



2) Transfer the files to the web server directory where you'd like your software installed. If you intend to unzip your archive directly on the server, you may need to move the files out of the enclosing directory to the intended destination. You should have the following filesystem structure at the root of your install directory:

    assets/         - for all your custom site files (css/images/js/etc.)
    ht.access       - a template for Apache servers supporting mod_rewrite
    index.php       - the main controller file
    index-ajax.php  - a secondary controller file for "config free" Ajax apps
    install/        - the installation system
    manager/        - the MODx core files (you config file is in here too)



3) In manager/includes/ there is a "config.inc.php.blank" file. If you wish, you can simply rename this by removing the ".blank" extension. This will be used as your config file. Alternately, you may choose to just create a blank file named "config.inc.php" in manager/includes/. Change the permissions on this file to 666 (read/write). The permissions mentioned here and later apply to most installs. PHP running as suexec may require different permissions (644 and 755)



4) Change the following directory permissions to 777 (read/write/execute):
    assets/cache/
    assets/export/
    assets/images/



5) Change the following files to 666 (read/write):
    assets/cache/siteCache.idx.php
    assets/cache/sitePublishing.idx.php



6) Point your browser to the URL that corresponds to your upload location:
     http://your-server.com/path-to-MODx-files-if-present/install/



7) If you see the red message regarding MODx not being installed, click the red "Install Now" text. Once you start the installer, follow the onscreen instructions to complete the install process. If you skipped any part of steps 2-5, you'll be prompted to fix them during install. 

NOTE: You may need to create a database prior to running the installer. In fact, it's probably a good idea to do so. Make sure to have your DB name, DB username and DB password ready when installing MODx.



8) When the installer has completed running, for security you should take the following measures:

    Delete your installation folder.
    Change your permissions on your config.inc.php file back to 644 (step 3).
    
You can also optionally delete the README.xx.txt files located in the root of your MODx install location (which includes this fiel you're reading).



9) If you intend to use of Friendly "human readible" URLs, you'll need to rename the "ht.access" file in the site root to ".htaccess", and to do the same for the one found in the manager/ directory.



10) When you login to the manger for the first time after an install or an upgrade, make sure to save the configuration screen. You can make changes prior to saving, or revisit the configuration pages later via the Tools > Configuration menu option. 

Welcome to MODx!

