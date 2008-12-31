<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

$warningspresent = 0;

     

if (is_writable("includes/config.inc.php")){
    // Warn if world writable
    if(@fileperms('includes/config.inc.php') & 0x0002) {
      $warningspresent = 1;
      $warnings[] = array($_lang['configcheck_configinc']);      
    }
}

if (file_exists("../install/")) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_installer']);
}

if (ini_get('register_globals')==TRUE) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_register_globals']);
}

if ($modx->db->getValue('SELECT published FROM '.$modx->getFullTableName('site_content').' WHERE id='.$unauthorized_page) == 0) {
	$warningspresent = 1;
    $warnings[] = array($_lang['configcheck_unauthorizedpage_unpublished']);
}

if ($modx->db->getValue('SELECT published FROM '.$modx->getFullTableName('site_content').' WHERE id='.$error_page) == 0) {
	$warningspresent = 1;
    $warnings[] = array($_lang['configcheck_errorpage_unpublished']);
}

if ($modx->db->getValue('SELECT privateweb FROM '.$modx->getFullTableName('site_content').' WHERE id='.$unauthorized_page) == 1) {
	$warningspresent = 1;
    $warnings[] = array($_lang['configcheck_unauthorizedpage_unavailable']);
}

if ($modx->db->getValue('SELECT privateweb FROM '.$modx->getFullTableName('site_content').' WHERE id='.$error_page) == 1) {
	$warningspresent = 1;
    $warnings[] = array($_lang['configcheck_errorpage_unavailable']);
}

if (!function_exists('checkSiteCache')) {
    function checkSiteCache() {
        global $modx;
        $checked= true;
        if (file_exists($modx->config['base_path'] . 'assets/cache/siteCache.idx.php')) {
            $checked= @include_once ($modx->config['base_path'] . 'assets/cache/siteCache.idx.php');
        }
        return $checked;
    }
}

if (!is_writable("../assets/cache/")) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_cache']);
}

if (!checkSiteCache()) {
    $warningspresent = 1;
    $warnings[]= array($lang['configcheck_sitecache_integrity']);
}

if (!is_writable("../assets/images/")) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_images']);
}

if (count($_lang)!=$length_eng_lang) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_lang_difference']);
}

// clear file info cache
clearstatcache();

if ($warningspresent==1) {

$config_check_results = "<h4>".$_lang['configcheck_notok']."</h4>";

for ($i=0;$i<count($warnings);$i++) {
    switch ($warnings[$i][0]) {
        case $_lang['configcheck_configinc'];
            $warnings[$i][1] = $_lang['configcheck_configinc_msg'];
            if(!$_SESSION["mgrConfigCheck"]) $modx->logEvent(0,2,$warnings[$i][1],$_lang['configcheck_configinc']);
            break;    
        case $_lang['configcheck_installer'] :
            $warnings[$i][1] = $_lang['configcheck_installer_msg'];
            if(!$_SESSION["mgrConfigCheck"]) $modx->logEvent(0,2,$warnings[$i][1],$_lang['configcheck_installer']);
            break;
        case $_lang['configcheck_cache'] :
            $warnings[$i][1] = $_lang['configcheck_cache_msg'];
            if(!$_SESSION["mgrConfigCheck"]) $modx->logEvent(0,2,$warnings[$i][1],$_lang['configcheck_cache']);
            break;
        case $_lang['configcheck_images'] :
            $warnings[$i][1] = $_lang['configcheck_images_msg'];
            if(!$_SESSION["mgrConfigCheck"]) $modx->logEvent(0,2,$warnings[$i][1],$_lang['configcheck_images']);
            break;
        case $_lang['configcheck_lang_difference'] :
            $warnings[$i][1] = $_lang['configcheck_lang_difference_msg'];
            break;
        case $_lang['configcheck_register_globals'] :
            $warnings[$i][1] = $_lang['configcheck_register_globals_msg'];
            break;
        case $_lang['configcheck_unauthorizedpage_unpublished'] :
            $warnings[$i][1] = $_lang['configcheck_unauthorizedpage_unpublished_msg'];
            break;
        case $_lang['configcheck_errorpage_unpublished'] :
            $warnings[$i][1] = $_lang['configcheck_errorpage_unpublished_msg'];
            break;
        case $_lang['configcheck_unauthorizedpage_unavailable'] :
            $warnings[$i][1] = $_lang['configcheck_unauthorizedpage_unavailable_msg'];
            break;
        case $_lang['configcheck_errorpage_unavailable'] :
            $warnings[$i][1] = $_lang['configcheck_errorpage_unavailable_msg'];
            break;
        default :
            $warnings[$i][1] = $_lang['configcheck_default_msg'];
    }
    
    $admin_warning = $_SESSION['mgrRole']!=1 ? $_lang['configcheck_admin'] : "" ;
    $config_check_results .= "
            <div class='fakefieldset'>
            <p><strong>".$_lang['configcheck_warning']."</strong> '".$warnings[$i][0]."'</p>
            <p style=\"padding-left:1em\"><em>".$_lang['configcheck_what']."</em><br />
            ".$warnings[$i][1]." ".$admin_warning."</p>
            </div>
    ";
        if ($i!=count($warnings)-1) {
            $config_check_results .= "<br />";
        }
    }
    $_SESSION["mgrConfigCheck"]=true;
} else {
    $config_check_results = $_lang['configcheck_ok'];
}
?>
