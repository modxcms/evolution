MODx CMS is an open source content management system licensed under the GPL.
Download the latest version and obtain support at http://modxcms.com/
Copyright 2005-2006 the MODx CMS project. All rights reserved.

The following guidelines and instructions should apply typical LAMP servers. 

For more installation tips, please visit the following resources:

    http://modxcms.com/getting-started.html
    http://wiki.modxcms.com/index.php/Category:Installation
    http://modxcms.com/forums/

Estimated time to install after uploading files to your server: < 5 minutes
Last revision date of this document: Oct 30, 2006

--------------------------------
MODx SUGGESTED REQUIREMENTS :
--------------------------------
MODx will run under most typical web servers, including IIS or Apache and on platforms including Windows, FeeBSD (and other similar platforms), *nix, OS X and Solaris (untested on Solaris but it should work). To minimize your learning curve, we suggest the following software for configuration and testing for new installs and users:

    Webserver: Apache (1.3.x or 2.x) with mod_rewrite enabled
    PHP: 4.3.11 and above (optimal performance in 5.1.6+)
    MySQL: 4.1.x (performance gains in 5.0.x)


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

