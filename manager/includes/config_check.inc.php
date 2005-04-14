<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

$warningspresent = 0;

if(file_exists("../install/")) {
	$warningspresent = 1;
	$warnings[] = array($_lang['configcheck_installer']);
}

if(!is_writable("../assets/cache/")) {
	$warningspresent = 1;
	$warnings[] = array($_lang['configcheck_cache']);
}

if(!is_writable("../assets/images/")) {
	$warningspresent = 1;
	$warnings[] = array($_lang['configcheck_images']);
}

if(count($_lang)!=$length_eng_lang) {
	$warningspresent = 1;
	$warnings[] = array($_lang['configcheck_lang_difference']);
}

// clear file info cache
clearstatcache();

if($warningspresent==1) {

$config_check_results = $_lang['configcheck_notok']."<p />";

for($i=0;$i<count($warnings);$i++) {
	switch ($warnings[$i][0]) {
		case $_lang['configcheck_installer'] :
			$warnings[$i][1] = $_lang['configcheck_installer_msg'];
			break;
		case $_lang['configcheck_cache'] :
			$warnings[$i][1] = $_lang['configcheck_cache_msg'];
			break;
		case $_lang['configcheck_images'] :
			$warnings[$i][1] = $_lang['configcheck_images_msg'];
			break;
		case $_lang['configcheck_lang_difference'] :
			$warnings[$i][1] = $_lang['configcheck_lang_difference_msg'];
			break;
		default :
			$warnings[$i][1] = $_lang['configcheck_default_msg'];
	}
	
	$admin_warning = $_SESSION['role']!=1 ? $_lang['configcheck_admin'] : "" ;
	$config_check_results .= "
			<div class='fakefieldset'>
			<strong>".$_lang['configcheck_warning']."</strong> '".$warnings[$i][0]."'<br />
			<br />
			<em>".$_lang['configcheck_what']."</em><br />
			".$warnings[$i][1]." ".$admin_warning."
			</div>
	";
		if($i!=count($warnings)-1) {
			$config_check_results .= "<br />";
		}
	}
} else {
	$config_check_results = $_lang['configcheck_ok'];
}
?>