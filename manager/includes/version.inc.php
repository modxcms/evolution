<?php
if (!function_exists('getSVNRev')) {
    function getSVNRev() {
        // SVN property required to be set, e.g. $Rev$
        $svnrev = '$Rev$';
        $svnrev = substr($svnrev, 6);
        return intval(substr($svnrev, 0, strlen($svnrev) - 2));
    }
}
$modx_version = '1.0.4';           // Current version number
$modx_release_date = '8 June 2010'; // Date of release
$modx_branch = 'Evolution';        // Codebase name
$code_name = 'rev '.getSVNRev();   // SVN version number (used mult places)
$modx_full_appname = 'MODx '.$modx_branch.' '.$modx_version.' (Rev: '.getSVNRev().' Date: '.$modx_release_date.')';