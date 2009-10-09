<?php
function getSVNRev() {
	// $Rev: 5622 $ - SVN property required to be set: $Rev: 5622 $
    $svnrev = '$Rev: 5622 $';
    $svnrev = substr($svnrev, 6);
    return intval(substr($svnrev, 0, strlen($svnrev) - 2));
}
function getSVNDate() {
	// $Date: 2009-08-05 15:27:23 -0500 (Wed, 05 Aug 2009) $ SVN property required to be set: $Date: 2009-08-05 15:27:23 -0500 (Wed, 05 Aug 2009) $
	$svndate = '$Date: 2009-08-05 15:27:23 -0500 (Wed, 05 Aug 2009) $';
	$svndate = substr($svndate, 40);
	// need to convert this to a timestamp for using MODx native date format function
	return strval(substr($svndate, 0, strlen($svndate) - 3));
}

$modx_version = '1.0.1'; // Current version
$modx_branch = 'Evolution';
$code_name = 'rev '.getSVNRev(); // SVN version number
$modx_release_date = getSVNDate();
$modx_full_appname = 'MODx '.$modx_branch.' '.$modx_version.' (Rev: '.getSVNRev().' Date:'.getSVNDate();