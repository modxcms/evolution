<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}

// PROCESSOR FIRST
if($_SESSION['mgrRole'] == 1) {
	if($_REQUEST['b'] == 'resetSysfilesChecksum' && $modx->hasPermission('settings')) {
		$current = $modx->manager->getSystemChecksum($modx->config['check_files_onlogin']);
		if(!empty($current)) {
			$modx->manager->setSystemChecksum($current);
			$modx->clearCache('full');
			$modx->config['sys_files_checksum'] = $current;
		};
	}
}

// NOW CHECK CONFIG
$warningspresent = 0;

$sysfiles_check = $modx->manager->checkSystemChecksum();
if ($sysfiles_check!=='0'){
      $warningspresent = 1;
      $warnings[] = array($_lang['configcheck_sysfiles_mod']);
}

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

if (!extension_loaded('gd') || !extension_loaded('zip')) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_php_gdzip']);
}

if(!isset($modx->config['_hide_configcheck_validate_referer']) || $modx->config['_hide_configcheck_validate_referer'] !== '1') {
    if(isset($_SESSION['mgrPermissions']['settings']) && $_SESSION['mgrPermissions']['settings'] == '1') {
        if ($modx->db->getValue($modx->db->select('COUNT(setting_value)', $modx->getFullTableName('system_settings'), "setting_name='validate_referer' AND setting_value='0'"))) {
            $warningspresent = 1;
            $warnings[] = array($_lang['configcheck_validate_referer']);
        }
    }
}

// check for Template Switcher plugin
if(!isset($modx->config['_hide_configcheck_templateswitcher_present']) || $modx->config['_hide_configcheck_templateswitcher_present'] !== '1') {
    if(isset($_SESSION['mgrPermissions']['edit_plugin']) && $_SESSION['mgrPermissions']['edit_plugin'] == '1') {
        $rs = $modx->db->select('name, disabled', $modx->getFullTableName('site_plugins'), "name IN ('TemplateSwitcher', 'Template Switcher', 'templateswitcher', 'template_switcher', 'template switcher') OR plugincode LIKE '%TemplateSwitcher%'");
        $row = $modx->db->getRow($rs);
        if($row && $row['disabled'] == 0) {
            $warningspresent = 1;
            $warnings[] = array($_lang['configcheck_templateswitcher_present']);
            $tplName = $row['name'];
            $script = <<<JS
<script type="text/javascript">
function deleteTemplateSwitcher(){
    if(confirm('{$_lang["confirm_delete_plugin"]}')) {
        var myAjax = new Ajax('index.php?a=118', {
            method: 'post',
            data: 'action=updateplugin&key=_delete_&lang=$tplName'
        });
        myAjax.addEvent('onComplete', function(resp){
            fieldset = $('templateswitcher_present_warning_wrapper').getParent().getParent();
            var sl = new Fx.Slide(fieldset);
            sl.slideOut();
        });
        myAjax.request();
    }
}
function disableTemplateSwitcher(){
    var myAjax = new Ajax('index.php?a=118', {
        method: 'post',
        data: 'action=updateplugin&lang={$tplName}&key=disabled&value=1'
    });
    myAjax.addEvent('onComplete', function(resp){
        fieldset = $('templateswitcher_present_warning_wrapper').getParent().getParent();
        var sl = new Fx.Slide(fieldset);
        sl.slideOut();
    });
    myAjax.request();
}
</script>

JS;
        $modx->regClientScript($script);
        }
    }
}

if ($modx->db->getValue($modx->db->select('published', $modx->getFullTableName('site_content'), "id='{$unauthorized_page}'")) == 0) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_unauthorizedpage_unpublished']);
}

if ($modx->db->getValue($modx->db->select('published', $modx->getFullTableName('site_content'), "id='{$error_page}'")) == 0) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_errorpage_unpublished']);
}

if ($modx->db->getValue($modx->db->select('privateweb', $modx->getFullTableName('site_content'), "id='{$unauthorized_page}'")) == 1) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_unauthorizedpage_unavailable']);
}

if ($modx->db->getValue($modx->db->select('privateweb', $modx->getFullTableName('site_content'), "id='{$error_page}'")) == 1) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_errorpage_unavailable']);
}

if (!function_exists('checkSiteCache')) {
    /**
     * @return bool
     */
    function checkSiteCache() {
        global $modx;
        $checked= true;
        if (file_exists($modx->config['base_path'] . 'assets/cache/siteCache.idx.php')) {
            $checked= @include_once ($modx->config['base_path'] . 'assets/cache/siteCache.idx.php');
        }
        return $checked;
    }
}

if (!is_writable(MODX_BASE_PATH . "assets/cache/")) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_cache']);
}

if (!checkSiteCache()) {
    $warningspresent = 1;
    $warnings[]= array($lang['configcheck_sitecache_integrity']);
}

if (!is_writable(MODX_BASE_PATH . "assets/images/")) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_images']);
}

if(strpos($modx->config['rb_base_dir'],MODX_BASE_PATH)!==0) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_rb_base_dir']);
}
if(strpos($modx->config['filemanager_path'],MODX_BASE_PATH)!==0) {
    $warningspresent = 1;
    $warnings[] = array($_lang['configcheck_filemanager_path']);
}

// clear file info cache
clearstatcache();

if ($warningspresent==1) {

if(!isset($modx->config['send_errormail'])) $modx->config['send_errormail']='3';
$config_check_results = "<h3>".$_lang['configcheck_notok']."</h3>";

for ($i=0;$i<count($warnings);$i++) {
    switch ($warnings[$i][0]) {
        case $_lang['configcheck_configinc'];
            $warnings[$i][1] = $_lang['configcheck_configinc_msg'];
            if(!$_SESSION["mgrConfigCheck"]) $modx->logEvent(0,3,$warnings[$i][1],$_lang['configcheck_configinc']);
            break;
        case $_lang['configcheck_installer'] :
            $warnings[$i][1] = $_lang['configcheck_installer_msg'];
            if(!$_SESSION["mgrConfigCheck"]) $modx->logEvent(0,3,$warnings[$i][1],$_lang['configcheck_installer']);
            break;
        case $_lang['configcheck_cache'] :
            $warnings[$i][1] = $_lang['configcheck_cache_msg'];
            if(!$_SESSION["mgrConfigCheck"]) $modx->logEvent(0,2,$warnings[$i][1],$_lang['configcheck_cache']);
            break;
        case $_lang['configcheck_images'] :
            $warnings[$i][1] = $_lang['configcheck_images_msg'];
            if(!$_SESSION["mgrConfigCheck"]) $modx->logEvent(0,2,$warnings[$i][1],$_lang['configcheck_images']);
            break;
        case $_lang['configcheck_sysfiles_mod']:
            $warnings[$i][1] = $_lang["configcheck_sysfiles_mod_msg"];
			$warnings[$i][2] = '<ul><li>'. implode('</li><li>', $sysfiles_check) .'</li></ul>';
			if($modx->hasPermission('settings')) {
				$warnings[$i][2] .= '<ul class="actionButtons" style="float:right"><li><a href="index.php?a=2&b=resetSysfilesChecksum" onclick="return confirm(\'' . $_lang["reset_sysfiles_checksum_alert"] . '\')">' . $_lang["reset_sysfiles_checksum_button"] . '</a></li></ul>';
			}
            if(!$_SESSION["mgrConfigCheck"]) $modx->logEvent(0,3,$warnings[$i][1]." ".implode(', ',$sysfiles_check),$_lang['configcheck_sysfiles_mod']);
            break;
        case $_lang['configcheck_lang_difference'] :
            $warnings[$i][1] = $_lang['configcheck_lang_difference_msg'];
            break;
        case $_lang['configcheck_php_gdzip'] :
            $warnings[$i][1] = $_lang['configcheck_php_gdzip_msg'];
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
        case $_lang['configcheck_validate_referer'] :
            $msg = $_lang['configcheck_validate_referer_msg'];
            $msg .= '<br />' . sprintf($_lang["configcheck_hide_warning"], 'validate_referer');
            $warnings[$i][1] = "<span id=\"validate_referer_warning_wrapper\">{$msg}</span>\n";
            break;
        case $_lang['configcheck_templateswitcher_present'] :
            $msg = $_lang["configcheck_templateswitcher_present_msg"];
            if(isset($_SESSION['mgrPermissions']['save_plugin']) && $_SESSION['mgrPermissions']['save_plugin'] == '1') {
                $msg .= '<br />' . $_lang["configcheck_templateswitcher_present_disable"];
            }
            if(isset($_SESSION['mgrPermissions']['delete_plugin']) && $_SESSION['mgrPermissions']['delete_plugin'] == '1') {
                $msg .= '<br />' . $_lang["configcheck_templateswitcher_present_delete"];
            }
            $msg .= '<br />' . sprintf($_lang["configcheck_hide_warning"], 'templateswitcher_present');
            $warnings[$i][1] = "<span id=\"templateswitcher_present_warning_wrapper\">{$msg}</span>\n";
            break;
        case $_lang['configcheck_rb_base_dir'] :
            $warnings[$i][1] = $_lang['configcheck_rb_base_dir_msg'];
            break;
        case $_lang['configcheck_filemanager_path'] :
            $warnings[$i][1] = $_lang['configcheck_filemanager_path_msg'];
            break;
        default :
            $warnings[$i][1] = $_lang['configcheck_default_msg'];
    }

    $admin_warning = $_SESSION['mgrRole']!=1 ? $_lang['configcheck_admin'] : "" ;
    $config_check_results .= "
            <fieldset>
            <p><strong>".$_lang['configcheck_warning']."</strong> '".$warnings[$i][0]."'</p>
            <p style=\"padding-left:1em\"><em>".$_lang['configcheck_what']."</em><br />
            ".$warnings[$i][1]." ".$admin_warning."</p>
            ".(isset($warnings[$i][2]) ? '<div style="padding-left:1em">'.$warnings[$i][2].'</div>' : '')."
            </fieldset>
";
        if ($i!=count($warnings)-1) {
            $config_check_results .= "<br />";
        }
    }
    $_SESSION["mgrConfigCheck"]=true;
} else {
    $config_check_results = $_lang['configcheck_ok'];
}
